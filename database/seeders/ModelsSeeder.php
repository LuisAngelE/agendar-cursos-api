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
            ['nombre_segmento' => 'Heavy Duty Truck', 'nombre_tipo_unidad' => 'S35', 'user_id' => 520],
            ['nombre_segmento' => 'Heavy Duty Truck', 'nombre_tipo_unidad' => 'GTL', 'user_id' => 520],
            ['nombre_segmento' => 'Heavy Duty Truck', 'nombre_tipo_unidad' => 'S40', 'user_id' => 520],
            ['nombre_segmento' => 'Heavy Duty Truck', 'nombre_tipo_unidad' => 'EST-A', 'user_id' => 520],
            ['nombre_segmento' => 'Heavy Duty Truck', 'nombre_tipo_unidad' => 'Galaxy', 'user_id' => 520],
            ['nombre_segmento' => 'Heavy Duty Truck', 'nombre_tipo_unidad' => 'EST', 'user_id' => 520],

            ['nombre_segmento' => 'Light Duty Truck (LDT)', 'nombre_tipo_unidad' => 'Miler', 'user_id' => 520],
            ['nombre_segmento' => 'Light Duty Truck (LDT)', 'nombre_tipo_unidad' => 'S3', 'user_id' => 520],
            ['nombre_segmento' => 'Light Duty Truck (LDT)', 'nombre_tipo_unidad' => 'S4', 'user_id' => 520],
            ['nombre_segmento' => 'Light Duty Truck (LDT)', 'nombre_tipo_unidad' => 'S5', 'user_id' => 520],
            ['nombre_segmento' => 'Light Duty Truck (LDT)', 'nombre_tipo_unidad' => 'S6', 'user_id' => 520],

            ['nombre_segmento' => 'Medium Duty Truck (MDT)', 'nombre_tipo_unidad' => 'S8', 'user_id' => 520],
            ['nombre_segmento' => 'Medium Duty Truck (MDT)', 'nombre_tipo_unidad' => 'S12', 'user_id' => 520],
            ['nombre_segmento' => 'Medium Duty Truck (MDT)', 'nombre_tipo_unidad' => 'S13', 'user_id' => 520],
            ['nombre_segmento' => 'Medium Duty Truck (MDT)', 'nombre_tipo_unidad' => 'S20', 'user_id' => 520],

            ['nombre_segmento' => 'Mini Truck', 'nombre_tipo_unidad' => 'Wonder', 'user_id' => 520],
            ['nombre_segmento' => 'Mini Truck', 'nombre_tipo_unidad' => 'TM3', 'user_id' => 520],

            ['nombre_segmento' => 'Passengers', 'nombre_tipo_unidad' => 'VIEW', 'user_id' => 520],
            ['nombre_segmento' => 'Passengers', 'nombre_tipo_unidad' => 'Grand VIEW', 'user_id' => 520],
            ['nombre_segmento' => 'Passengers', 'nombre_tipo_unidad' => 'HiVan', 'user_id' => 520],
            ['nombre_segmento' => 'Passengers', 'nombre_tipo_unidad' => 'Tunland', 'user_id' => 520],
        ];

        DB::table('models')->insert($models);
    }
}
