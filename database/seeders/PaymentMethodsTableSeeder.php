<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodsTableSeeder extends Seeder
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
                    'code' => 'DINHEIRO',
                    'name' => 'Dinheiro'
                ],
                [
                    'id' => 2,
                    'code' => 'PIX',
                    'name' => 'Pix'
                ],
                [
                    'id' => 3,
                    'code' => 'CARTAO_CREDITO',
                    'name' => 'Cartão de Crédito'
                ],
                [
                    'id' => 4,
                    'code' => 'CARTAO_DEBITO',
                    'name' => 'Cartão de Débito'
                ]
            ];

            DB::beginTransaction();

            foreach ($data as $value) {
                PaymentMethod::query()
                    ->updateOrCreate(
                        ['id' => $value['id']],
                        $value
                    );

                array_push($messages, "  {$value['id']} - Método de Pagamento {$value['name']} criado/atualizado.");
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
