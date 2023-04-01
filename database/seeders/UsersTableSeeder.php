<?php

namespace Database\Seeders;

use App\Enums\IsAdmin;
use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        User::withoutEvents(function () {
            try {
                $messages = [];
                $data = [
                    [
                        'person' => [
                            'nif' => '57289851000113',
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
                        ],
                        'user' => [
                            'name' => 'Atlas Garçom',
                            'email' => 'admin@atlasgarcom.com',
                            'is_admin' => IsAdmin::YES,
                            'password' => Hash::make('atlas1234567o'),
                        ]
                    ]
                ];

                DB::beginTransaction();

                foreach ($data as $value) {
                    $person = Person::query()
                        ->updateOrCreate(
                            ['nif' => $value['person']['nif']],
                            $value['person']
                        );

                    $user = User::query()
                        ->updateOrcreate(
                            ['person_id' => $person->id],
                            $value['user']
                        );

                    $user->assignRole('administrator');

                    array_push($messages, "  {$user->id} - User {$person->full_name} criado/atualizado.");
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
