<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NcmsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = file_get_contents(database_path('data/tabela_ncm_20230305.json'));
        $obj = json_decode($json);
        $data = [];

        foreach ($obj->Nomenclaturas as $value) {
            $data[] = [
                'code' => $value->Codigo,
                'description' => $value->Descricao,
                'date_start' => carbon($value->Data_Inicio)->toDateString(),
                'date_end' => carbon($value->Data_Fim)->toDateString(),
                'ato_type' => $value->Tipo_Ato,
                'ato_number' => $value->Numero_Ato,
                'ato_year' => $value->Ano_Ato
            ];
        }

        foreach (array_chunk($data, 1000) as $value) {
            DB::table('ncms')->insert($value);
        }
    }
}
