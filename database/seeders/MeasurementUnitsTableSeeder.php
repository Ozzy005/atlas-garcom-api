<?php

namespace Database\Seeders;

use App\Models\MeasurementUnit;
use Illuminate\Database\Seeder;

class MeasurementUnitsTableSeeder extends Seeder
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
                'name' => 'DUZIA',
                'initials' => 'DUZIA'
            ],
            [
                'name' => 'GRAMA',
                'initials' => 'G'
            ],
            [
                'name' => 'LITRO',
                'initials' => 'LT'
            ],
            [
                'name' => 'MEGAWATT HORA',
                'initials' => 'MWHORA'
            ],
            [
                'name' => 'METRO',
                'initials' => 'METRO'
            ],
            [
                'name' => 'METRO CUBICO',
                'initials' => 'M3'
            ],
            [
                'name' => 'METRO QUADRADO',
                'initials' => 'M2'
            ],
            [
                'name' => 'MIL UNIDADES',
                'initials' => '1000UN'
            ],
            [
                'name' => 'PARES',
                'initials' => 'PARES'
            ],
            [
                'name' => 'QUILATE',
                'initials' => 'QUILAT'
            ],
            [
                'name' => 'QUILOGRAMA',
                'initials' => 'KG'
            ],
            [
                'name' => 'TONEL METR LIQUIDA',
                'initials' => 'TON'
            ],
            [
                'name' => 'UNIDADE',
                'initials' => 'UN'
            ]
        ];

        foreach ($data as $value) {
            MeasurementUnit::query()
                ->updateOrCreate(
                    ['name' => $value['name']],
                    $value
                );
        }
    }
}
