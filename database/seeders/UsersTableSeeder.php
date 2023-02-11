<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
                    'password' => Hash::make('1234567o'),
                ]
            );
    }
}
