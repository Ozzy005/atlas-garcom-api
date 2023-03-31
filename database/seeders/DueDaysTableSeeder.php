<?php

namespace Database\Seeders;

use App\Models\DueDay;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DueDaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $messages = [];
            $data = [
                [
                    'id' => 1,
                    'day' => '5',
                    'description' => 'Todo dia 5'
                ],
                [
                    'id' => 2,
                    'day' => '10',
                    'description' => 'Todo dia 10'
                ],
                [
                    'id' => 3,
                    'day' => '15',
                    'description' => 'Todo dia 15'
                ],
                [
                    'id' => 4,
                    'day' => '20',
                    'description' => 'Todo dia 20'
                ],
                [
                    'id' => 5,
                    'day' => '25',
                    'description' => 'Todo dia 25'
                ]
            ];

            DB::beginTransaction();

            foreach ($data as $value) {
                DueDay::query()
                    ->updateOrCreate(
                        ['id' => $value['id']],
                        $value
                    );

                array_push($messages, "  {$value['id']} - Dia de Vencimento {$value['day']} criado/atualizado.");
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
