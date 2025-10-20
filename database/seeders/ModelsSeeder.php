<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $models = [
            ['nombre_segmento' => 'Heavy Duty Truck', 'nombre_tipo_unidad' => 'S35'],
            ['nombre_segmento' => 'Heavy Duty Truck', 'nombre_tipo_unidad' => 'GTL'],
            ['nombre_segmento' => 'Heavy Duty Truck', 'nombre_tipo_unidad' => 'S40'],
            ['nombre_segmento' => 'Heavy Duty Truck', 'nombre_tipo_unidad' => 'EST-A'],
            ['nombre_segmento' => 'Heavy Duty Truck', 'nombre_tipo_unidad' => 'Galaxy'],
            ['nombre_segmento' => 'Heavy Duty Truck', 'nombre_tipo_unidad' => 'EST'],

            ['nombre_segmento' => 'Light Duty Truck (LDT)', 'nombre_tipo_unidad' => 'Miler'],
            ['nombre_segmento' => 'Light Duty Truck (LDT)', 'nombre_tipo_unidad' => 'S3'],
            ['nombre_segmento' => 'Light Duty Truck (LDT)', 'nombre_tipo_unidad' => 'S4'],
            ['nombre_segmento' => 'Light Duty Truck (LDT)', 'nombre_tipo_unidad' => 'S5'],
            ['nombre_segmento' => 'Light Duty Truck (LDT)', 'nombre_tipo_unidad' => 'S6'],

            ['nombre_segmento' => 'Medium Duty Truck (MDT)', 'nombre_tipo_unidad' => 'S8'],
            ['nombre_segmento' => 'Medium Duty Truck (MDT)', 'nombre_tipo_unidad' => 'S12'],
            ['nombre_segmento' => 'Medium Duty Truck (MDT)', 'nombre_tipo_unidad' => 'S13'],
            ['nombre_segmento' => 'Medium Duty Truck (MDT)', 'nombre_tipo_unidad' => 'S20'],

            ['nombre_segmento' => 'Mini Truck', 'nombre_tipo_unidad' => 'Wonder'],
            ['nombre_segmento' => 'Mini Truck', 'nombre_tipo_unidad' => 'TM3'],

            ['nombre_segmento' => 'Passengers', 'nombre_tipo_unidad' => 'VIEW'],
            ['nombre_segmento' => 'Passengers', 'nombre_tipo_unidad' => 'Grand VIEW'],
            ['nombre_segmento' => 'Passengers', 'nombre_tipo_unidad' => 'HiVan'],
            ['nombre_segmento' => 'Passengers', 'nombre_tipo_unidad' => 'Tunland'],
        ];

        DB::table('models')->insert($models);
    }
}
