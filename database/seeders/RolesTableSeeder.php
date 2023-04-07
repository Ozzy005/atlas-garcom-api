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
                        'permissions' => [],
                        'full-permissions' => true
                    ],
                    [
                        'id' => 2,
                        'name' => 'categories',
                        'description' => 'Categorias',
                        'type' => \App\Enums\RoleType::MODULE,
                        'permissions' => [
                            ['name', 'like', '%categories_%']
                        ],
                        'full-permissions' => false
                    ],
                    [
                        'id' => 3,
                        'name' => 'complements',
                        'description' => 'Complementos',
                        'type' => \App\Enums\RoleType::MODULE,
                        'permissions' => [
                            ['name', 'like', '%complements_%']
                        ],
                        'full-permissions' => false
                    ],
                    [
                        'id' => 4,
                        'name' => 'products',
                        'description' => 'Produtos',
                        'type' => \App\Enums\RoleType::MODULE,
                        'permissions' => [
                            ['name', 'like', '%products_%']
                        ],
                        'full-permissions' => false
                    ],
                    [
                        'id' => 5,
                        'name' => 'users',
                        'description' => 'Usuários',
                        'type' => \App\Enums\RoleType::MODULE,
                        'permissions' => [
                            ['name', 'like', '%users_%']
                        ],
                        'full-permissions' => false
                    ],
                    [
                        'id' => 6,
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
                        ->when(!$fullPermissions, function (Builder $query) use ($permissions) {
                            $query->where(function (Builder $query) use ($permissions) {
                                foreach ($permissions  as $permission) {
                                    $query->orWhere($permission[0], $permission[1], $permission[2]);
                                }
                            });
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
