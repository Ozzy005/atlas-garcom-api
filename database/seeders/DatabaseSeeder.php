<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
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
