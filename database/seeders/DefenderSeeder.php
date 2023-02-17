<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class DefenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createPermissions();
        $this->createRoles();
    }

    private function createPermissions()
    {
        DB::transaction(function () {

            $array = Permission::permissions;

            function loop($array, $parent = null)
            {
                foreach ($array as $value) {
                    if (array_key_exists('name', $value)) {
                        $permission = Permission::query()
                            ->updateOrCreate(
                                ['name' => $value['name']],
                                [
                                    'description' => $value['description'],
                                    'parent_id' => $parent ? $parent->id : null,
                                ]
                            );
                        if (array_key_exists('children', $value)) {
                            loop($value['children'], $permission);
                        }
                    }
                }
            }

            loop($array);
        });
    }

    private function createRoles()
    {
        $admin = Role::query()
            ->updateOrCreate(
                ['name' => 'administrator'],
                ['description' => 'Administrador']
            );

        $admin->permissions()->sync(Permission::query()->get()->toFlatTree());

        $tenant = Role::query()
            ->updateOrCreate(
                ['name' => 'tenant'],
                ['description' => 'Contratante']
            );

        $permissions = Permission::query()
            ->where([
                ['name', 'not like', '%tenants_%'],
                ['name', 'not like', '%cities_%'],
                ['name', 'not like', '%states_%'],
                ['name', 'not like', '%permissions_%']
            ])
            ->get();

        $tenant->permissions()->sync($permissions);
    }
}
