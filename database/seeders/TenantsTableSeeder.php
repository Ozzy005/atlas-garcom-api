<?php

namespace Database\Seeders;

use App\Models\Person;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TenantsTableSeeder extends Seeder
{
    public function run(): void
    {
        User::withoutEvents(function () {
            try {
                $messages = [];
                $data = [
                    [
                        'person' => [
                            'nif' => '83643264000190',
                            'full_name' => 'Contratante',
                            'name' => 'Contratante',
                            'birthdate' => '2015-08-12',
                            'state_registration' => '522.799.920.946',
                            'city_registration' => null,
                            'email' => 'contratante@atlasgarcom.com',
                            'phone' => '11981278555',
                            'zip_code' => '06412150',
                            'address' => 'Rua Egeu',
                            'number' => 566,
                            'district' => 'Jardim Regina Alice',
                            'complement' => null,
                            'city_id' => 5179
                        ],
                        'tenant' => [
                            'signature_id' => 1,
                            'due_day_id' => 1,
                            'status' => \App\Enums\TenantStatus::ACTIVE
                        ],
                        'user' => [
                            'name' => 'Contratante',
                            'email' => 'contratante@atlasgarcom.com',
                            'password' => Hash::make('atlas1234567o'),
                            'is_tenant' => \App\Enums\IsTenant::YES,
                            'status' => \App\Enums\Status::ACTIVE
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

                    $tenant = Tenant::query()
                        ->updateOrcreate(
                            ['person_id' => $person->id],
                            $value['tenant']
                        );

                    $user = User::query()
                        ->updateOrcreate(
                            ['person_id' => $person->id],
                            $value['user'] + ['tenant_id' => $tenant->id]
                        );

                    $user->assignRole(['categories', 'complements']);

                    array_push($messages, "  {$tenant->id} - Tenant {$person->full_name} criado/atualizado.");
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
