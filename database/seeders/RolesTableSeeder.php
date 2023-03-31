<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::withoutEvents(function () {
            try {
                $messages = [];
                $data = [
                    [
                        'name' => 'administrator',
                        'description' => 'Administrador',
                        'type' => \App\Enums\RoleType::ROLE,
                        'permissions' => [],
                        'full-permissions' => true
                    ],
                    [
                        'name' => 'users',
                        'description' => 'Usuários',
                        'type' => \App\Enums\RoleType::MODULE,
                        'permissions' => [
                            ['name', 'like', '%users_%']
                        ],
                        'full-permissions' => false
                    ],
                    [
                        'name' => 'roles',
                        'description' => 'Atribuições',
                        'type' => \App\Enums\RoleType::MODULE,
                        'permissions' => [
                            ['name', 'like', '%roles_%']
                        ],
                        'full-permissions' => false
                    ]
                ];

                DB::beginTransaction();

                foreach ($data as $value) {
                    $permissions = $value['permissions'];
                    $fullPermissions = $value['full-permissions'];
                    unset($value['permissions']);
                    unset($value['full-permissions']);

                    $permissions = Permission::query()
                        ->when($fullPermissions === false, function (Builder $query) use ($permissions) {
                            $query->where($permissions);
                        })
                        ->get();

                    $role = Role::query()
                        ->updateOrCreate(
                            ['name' => $value['name']],
                            $value
                        );

                    $role->permissions()->sync($permissions);

                    array_push($messages, "  $role->id - Role {$value['name']} criada/atualizada.");
                }

                DB::commit();

                foreach ($messages as $message) {
                    $this->command->info($message);
                }
            } catch (\Throwable $th) {
                DB::rollBack();
                $this->command->alert($th->getMessage());
            }
        });
    }
}
