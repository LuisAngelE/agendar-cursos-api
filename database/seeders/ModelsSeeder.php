<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModelsSeeder extends Seeder
{
    public function run()
    {
        $models = [
            //  ABRAHAM HERNÁNDEZ (ID 520) Ligeros y medianos
            ['nombre_segmento' => null, 'nombre_tipo_unidad' => 'View',   'user_id' => 520],
            ['nombre_segmento' => null, 'nombre_tipo_unidad' => 'Hi Van', 'user_id' => 520],
            ['nombre_segmento' => null, 'nombre_tipo_unidad' => 'TM',     'user_id' => 520],
            ['nombre_segmento' => null, 'nombre_tipo_unidad' => 'S3',     'user_id' => 520],
            ['nombre_segmento' => null, 'nombre_tipo_unidad' => 'S5',     'user_id' => 520],
            ['nombre_segmento' => null, 'nombre_tipo_unidad' => 'S8',     'user_id' => 520],
            ['nombre_segmento' => null, 'nombre_tipo_unidad' => 'S12',    'user_id' => 520],
            ['nombre_segmento' => null, 'nombre_tipo_unidad' => 'S20',    'user_id' => 520],
            ['nombre_segmento' => null, 'nombre_tipo_unidad' => 'S38',    'user_id' => 520],

            //  OMAR VALADEZ (ID 881) Tractocamiones
            ['nombre_segmento' => null, 'nombre_tipo_unidad' => 'Tractos ISG',  'user_id' => 881],
            ['nombre_segmento' => null, 'nombre_tipo_unidad' => 'EST-A X13',    'user_id' => 881],
            ['nombre_segmento' => null, 'nombre_tipo_unidad' => 'Galaxy',        'user_id' => 881],
            ['nombre_segmento' => null, 'nombre_tipo_unidad' => 'Galaxus',       'user_id' => 881],
            ['nombre_segmento' => null, 'nombre_tipo_unidad' => 'GTL',           'user_id' => 881],
        ];

        DB::table('models')->insert($models);
    }
}
