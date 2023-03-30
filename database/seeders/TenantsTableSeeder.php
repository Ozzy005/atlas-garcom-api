<?php

namespace Database\Seeders;

use App\Models\Person;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TenantsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::withoutEvents(function () {
            $person = Person::query()
                ->updateOrCreate(
                    ['nif' => '83643264000190'],
                    [
                        'full_name' => 'Noah e Osvaldo Padaria Ltda',
                        'name' => 'Noah e Osvaldo Padaria',
                        'birthdate' => '2015-08-12',
                        'state_registration' => '522.799.920.946',
                        'city_registration' => null,
                        'email' => 'noah_padaria@hotmail.com',
                        'phone' => '11981278555',
                        'zip_code' => '06412150',
                        'address' => 'Rua Egeu',
                        'number' => 566,
                        'district' => 'Jardim Regina Alice',
                        'complement' => null,
                        'city_id' => 5179
                    ]
                );

            $tenant = Tenant::query()
                ->updateOrcreate(
                    ['person_id' => $person->id],
                    [
                        'signature_id' => 3,
                        'due_day_id' => 2,
                        'status' => \App\Enums\TenantStatus::ACTIVE
                    ]
                );

            $user = User::query()
                ->updateOrcreate(
                    ['person_id' => $person->id],
                    [
                        'name' => 'Noah e Osvaldo Padaria',
                        'email' => 'noah_padaria@hotmail.com',
                        'password' => Hash::make('1234567o'),
                        'tenant_id' => $tenant->id,
                        'is_tenant' => \App\Enums\IsTenant::YES,
                        'status' => \App\Enums\Status::ACTIVE
                    ]
                );

            $user->assignRole(['users', 'roles']);
        });
    }
}
