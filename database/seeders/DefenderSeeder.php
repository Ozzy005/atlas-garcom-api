<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Rule;
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

            function percorrerArray($array, $parent = null)
            {
                foreach ($array as $value) {
                    if (array_key_exists('name', $value)) {
                        $permission = Permission::updateOrCreate(
                            ['name' => $value['name']],
                            [
                                'description' => $value['description'],
                                'parent_id' => $parent ? $parent->id : null,
                            ]
                        );
                        if (array_key_exists('children', $value)) {
                            percorrerArray($value['children'], $permission);
                        }
                    }
                }
            }

            percorrerArray($array);
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
    }
}
