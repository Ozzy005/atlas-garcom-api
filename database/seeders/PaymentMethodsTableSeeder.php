<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodsTableSeeder extends Seeder
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

        foreach ($data as $value) {
            PaymentMethod::query()
                ->updateOrCreate(
                    ['id' => $value['id']],
                    $value
                );
        }
    }
}
