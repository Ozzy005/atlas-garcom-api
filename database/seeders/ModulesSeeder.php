<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class ModulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'users',
                'description' => 'Usuários',
                'type' => \App\Enums\RoleType::MODULE,
                'permissions' => 'users_'
            ],
            [
                'name' => 'roles',
                'description' => 'Atribuições',
                'type' => \App\Enums\RoleType::MODULE,
                'permissions' => 'roles_'
            ]
        ];

        foreach ($data as $value) {
            $permissions = $value['permissions'];
            unset($value['permissions']);

            $module = Role::query()
                ->updateOrCreate(
                    ['name' => $value['name']],
                    $value
                );

            $module->permissions()
                ->sync(
                    Permission::query()
                        ->where('name', 'like', '%' . $permissions . '%')
                        ->get()
                        ->toFlatTree()
                );

            $this->command->info("  $module->id - Módulo {$value['name']} criado.");
        }
    }
}
