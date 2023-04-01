<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PermissionsTableSeeder::class,
            RolesTableSeeder::class,
            StatesTableSeeder::class,
            CitiesTableSeeder::class,
            NcmsTableSeeder::class,
            DueDaysTableSeeder::class,
            PaymentMethodsTableSeeder::class,
            MeasurementUnitsTableSeeder::class,
            SignaturesTableSeeder::class,
            UsersTableSeeder::class,
            TenantsTableSeeder::class
        ]);
    }
}
