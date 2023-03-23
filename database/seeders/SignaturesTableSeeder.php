<?php

namespace Database\Seeders;

use App\Models\Signature;
use Illuminate\Database\Seeder;

class SignaturesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'name' => 'Bronze - Mensal',
                'description' => 'Assinatura básica mensal.',
                'color' => '#ffe6cc',
                'status' => \App\Enums\Status::ACTIVE,
                'recurrence' => \App\Enums\Recurrence::MONTHLY,
                'price' => 89.99,
                'has_discount' => false,
                'discount' => 0,
                'discounted_price' => 0,
                'total_price' => 89.99,
                'due_days_ids' => [1, 3],
                'modules_ids' => [2]
            ],
            [
                'id' => 2,
                'name' => 'Bronze - Anual',
                'description' => 'Assinatura básica anual.',
                'color' => '#ffe6cc',
                'status' => \App\Enums\Status::ACTIVE,
                'recurrence' => \App\Enums\Recurrence::ANNUALLY,
                'price' => 89.99,
                'has_discount' => true,
                'discount' => 5,
                'discounted_price' => 85.5,
                'total_price' => 1026,
                'due_days_ids' => [1, 3],
                'modules_ids' => [2]
            ],
            [
                'id' => 3,
                'name' => 'Prata - Mensal',
                'description' => 'Assinatura intermediária mensal.',
                'color' => '#cdcdcd',
                'status' => \App\Enums\Status::ACTIVE,
                'recurrence' => \App\Enums\Recurrence::MONTHLY,
                'price' => 109.99,
                'has_discount' => false,
                'discount' => 0,
                'discounted_price' => 0,
                'total_price' => 109.99,
                'due_days_ids' => [2, 4],
                'modules_ids' => [2, 3]
            ],
            [
                'id' => 4,
                'name' => 'Prata - Anual',
                'description' => 'Assinatura intermediária anual.',
                'color' => '#cdcdcd',
                'status' => \App\Enums\Status::ACTIVE,
                'recurrence' => \App\Enums\Recurrence::ANNUALLY,
                'price' => 109.99,
                'has_discount' => true,
                'discount' => 5,
                'discounted_price' => 104.5,
                'total_price' => 1254,
                'due_days_ids' => [2, 4],
                'modules_ids' => [2, 3]
            ],
            [
                'id' => 5,
                'name' => 'Gold - Mensal',
                'description' => 'Assinatura avançada mensal.',
                'color' => '#ffff99',
                'status' => \App\Enums\Status::ACTIVE,
                'recurrence' => \App\Enums\Recurrence::MONTHLY,
                'price' => 129.99,
                'has_discount' => false,
                'discount' => 0,
                'discounted_price' => 0,
                'total_price' => 129.99,
                'due_days_ids' => [1, 2, 3, 4, 5],
                'modules_ids' => [2, 3]
            ],
            [
                'id' => 6,
                'name' => 'Gold - Anual',
                'description' => 'Assinatura avançada anual.',
                'color' => '#ffff99',
                'status' => \App\Enums\Status::ACTIVE,
                'recurrence' => \App\Enums\Recurrence::ANNUALLY,
                'price' => 129.99,
                'has_discount' => true,
                'discount' => 5,
                'discounted_price' => 123.5,
                'total_price' => 1482,
                'due_days_ids' => [1, 2, 3, 4, 5],
                'modules_ids' => [2, 3]
            ]
        ];

        foreach ($data as $value) {
            $dueDaysIds = $value['due_days_ids'];
            $modulesIds = $value['modules_ids'];
            unset($value['due_days_ids']);
            unset($value['modules_ids']);
            $signature = Signature::query()
                ->updateOrCreate(
                    ['id' => $value['id']],
                    $value
                );

            $signature->dueDays()->sync($dueDaysIds);
            $signature->modules()->sync($modulesIds);

            $this->command->info("  {$value['id']} - Assinatura {$value['name']} criado.");
        }
    }
}
