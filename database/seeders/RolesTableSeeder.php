<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        Role::withoutEvents(function () {
            try {
                $messages = [];
                $data = [
                    [
                        'id' => 1,
                        'name' => 'administrator',
                        'description' => 'Administrador',
                        'type' => \App\Enums\RoleType::ROLE,
                        'permissions' => [
                            ['name', 'like', '%dashboard_%'],
                            ['name', 'like', '%people_%'],
                            ['name', 'like', '%tenants_%'],
                            ['name', 'like', '%operational_%'],
                            ['name', 'like', '%signatures_%'],
                            ['name', 'like', '%due-days_%'],
                            ['name', 'like', '%categories_%'],
                            ['name', 'like', '%complements_%'],
                            ['name', 'like', '%general_%'],
                            ['name', 'like', '%payment-methods_%'],
                            ['name', 'like', '%measurement-units_%'],
                            ['name', 'like', '%ncms_%'],
                            ['name', 'like', '%states_%'],
                            ['name', 'like', '%cities_%'],
                            ['name', 'like', '%management_%'],
                            ['name', 'like', '%users_%'],
                            ['name', 'like', '%roles_%'],
                            ['name', 'like', '%permissions_%']
                        ]
                    ],
                    [
                        'id' => 2,
                        'name' => 'categories',
                        'description' => 'Categorias',
                        'type' => \App\Enums\RoleType::MODULE,
                        'permissions' => [
                            ['name', 'like', '%categories_%']
                        ]
                    ],
                    [
                        'id' => 3,
                        'name' => 'complements',
                        'description' => 'Complementos',
                        'type' => \App\Enums\RoleType::MODULE,
                        'permissions' => [
                            ['name', 'like', '%complements_%']
                        ]
                    ],
                    [
                        'id' => 4,
                        'name' => 'users',
                        'description' => 'Usuários',
                        'type' => \App\Enums\RoleType::MODULE,
                        'permissions' => [
                            ['name', 'like', '%users_%']
                        ]
                    ],
                    [
                        'id' => 5,
                        'name' => 'roles',
                        'description' => 'Atribuições',
                        'type' => \App\Enums\RoleType::MODULE,
                        'permissions' => [
                            ['name', 'like', '%roles_%']
                        ]
                    ]
                ];

                DB::beginTransaction();

                foreach ($data as $value) {
                    $permissions = $value['permissions'];
                    unset($value['permissions']);

                    $permissions = Permission::query()
                        ->where(function (Builder $query) use ($permissions) {
                            foreach ($permissions  as $permission) {
                                $query->orWhere($permission[0], $permission[1], $permission[2]);
                            }
                        })
                        ->get();

                    $role = Role::query()
                       ->updateOrCreate(
                            ['id' => $value['id']],
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
