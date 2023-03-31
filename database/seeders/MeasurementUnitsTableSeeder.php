<?php

namespace Database\Seeders;

use App\Models\MeasurementUnit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MeasurementUnitsTableSeeder extends Seeder
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
                    'name' => 'DUZIA',
                    'initials' => 'DUZIA'
                ],
                [
                    'id' => 2,
                    'name' => 'GRAMA',
                    'initials' => 'G'
                ],
                [
                    'id' => 3,
                    'name' => 'LITRO',
                    'initials' => 'LT'
                ],
                [
                    'id' => 4,
                    'name' => 'MEGAWATT HORA',
                    'initials' => 'MWHORA'
                ],
                [
                    'id' => 5,
                    'name' => 'METRO',
                    'initials' => 'METRO'
                ],
                [
                    'id' => 6,
                    'name' => 'METRO CUBICO',
                    'initials' => 'M3'
                ],
                [
                    'id' => 7,
                    'name' => 'METRO QUADRADO',
                    'initials' => 'M2'
                ],
                [
                    'id' => 8,
                    'name' => 'MIL UNIDADES',
                    'initials' => '1000UN'
                ],
                [
                    'id' => 8,
                    'name' => 'PARES',
                    'initials' => 'PARES'
                ],
                [
                    'id' => 9,
                    'name' => 'QUILATE',
                    'initials' => 'QUILAT'
                ],
                [
                    'id' => 10,
                    'name' => 'QUILOGRAMA',
                    'initials' => 'KG'
                ],
                [
                    'id' => 11,
                    'name' => 'TONEL METR LIQUIDA',
                    'initials' => 'TON'
                ],
                [
                    'id' => 12,
                    'name' => 'UNIDADE',
                    'initials' => 'UN'
                ]
            ];

            DB::beginTransaction();

            foreach ($data as $value) {
                MeasurementUnit::query()
                    ->updateOrCreate(
                        ['id' => $value['id']],
                        $value
                    );

                array_push($messages, "  {$value['id']} - Unidade de Medida {$value['name']} criada/atualizada.");
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
