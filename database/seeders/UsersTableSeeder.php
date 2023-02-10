<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = \App\Models\User::query()
            ->updateOrcreate(
                ['email' => 'admin@atlasgarcom.com'],
                [
                    'name' => 'Atlas Garçom',
                    'password' => bcrypt('1234567o'),
                ]
            );
    }
}
