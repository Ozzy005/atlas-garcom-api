<?php

namespace Database\Seeders;

use App\Models\Signature;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SignaturesTableSeeder extends Seeder
{
    public function run(): void
    {
        try {
            $messages = [];
            $data = [
                [
                    'id' => 1,
                    'name' => 'Essencial',
                    'description' => 'Assinatura essencial mensal.',
                    'color' => '#ffe6cc',
                    'status' => \App\Enums\Status::ACTIVE,
                    'recurrence' => \App\Enums\Recurrence::MONTHLY,
                    'price' => 89.99,
                    'has_discount' => false,
                    'discount' => 0,
                    'discounted_price' => 0,
                    'total_price' => 89.99,
                    'due_days_ids' => [1, 2, 3, 4, 5],
                    'modules_ids' => [2, 3, 4, 5, 6]
                ]
            ];

            DB::beginTransaction();

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

                array_push($messages, "  {$value['id']} - Assinatura {$value['name']} criada/atualizada.");
            }

            DB::commit();

            foreach ($messages as $message) {
                $this->command->info($message);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->command->alert($th->getMessage());
        }
    }
}
