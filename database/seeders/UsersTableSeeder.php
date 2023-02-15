<?php

namespace Database\Seeders;

use App\Models\Person;
use App\Models\User;
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
        $person = Person::query()
            ->updateOrCreate(
                ['nif' => '57289851000113'],
                [
                    'full_name' => 'Atlas Garçom',
                    'name' => 'Atlas',
                    'birthdate' => '2018-06-26',
                    'state_registration' => '675.569.840.666',
                    'city_registration' => null,
                    'email' => 'admin@atlasgarcom.com',
                    'phone' => '66996040978',
                    'zip_code' => '78580000',
                    'address' => 'Avenida Sumaúma',
                    'number' => 745,
                    'district' => 'Jardim das Oliveiras',
                    'complement' => 'Casa roxa',
                    'city_id' => 5179
                ]
            );

        $user = User::query()
            ->updateOrcreate(
                ['person_id' => $person->id],
                [
                    'name' => 'Atlas Garçom',
                    'email' => 'admin@atlasgarcom.com',
                    'password' => Hash::make('1234567o'),
                ]
            );

        $user->assignRole('administrator');
    }
}
