<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DefenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PermissionsTableSeeder::class,
            RolesTableSync::class,
            RolesTableSeeder::class,
        ]);
    }
}
