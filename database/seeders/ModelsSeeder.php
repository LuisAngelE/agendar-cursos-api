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
            ['nombre_tipo_unidad' => 'EST-A', 'nombre_modelo' => 'EST-A 283253-(GNC)', 'nombre_producto' => 'EST-A 2853-(CNG)'],
            ['nombre_tipo_unidad' => 'EST-A', 'nombre_modelo' => 'EST-A 6x2', 'nombre_producto' => 'EST-A 6x2'],
            ['nombre_tipo_unidad' => 'EST-A', 'nombre_modelo' => 'EST-A 6x4', 'nombre_producto' => 'EST-A 6x4'],
            ['nombre_tipo_unidad' => 'EST-A', 'nombre_modelo' => 'EST-A 6x4 560', 'nombre_producto' => 'N/D'],
            ['nombre_tipo_unidad' => 'Galaxy', 'nombre_modelo' => 'Galaxy / 3256', 'nombre_producto' => 'Galaxy / 3256 / Rel.:2.71 (Nacional)'],
            ['nombre_tipo_unidad' => 'Galaxy', 'nombre_modelo' => 'Galaxy / 3256', 'nombre_producto' => 'Galaxy / 3256 / Rel.:3.08 (Nacional)'],
            ['nombre_tipo_unidad' => 'Galaxy', 'nombre_modelo' => 'Galaxy / 3256', 'nombre_producto' => 'Galaxy / 3256 / Rel.:3.36 (Nacional)'],
            ['nombre_tipo_unidad' => 'Galaxy', 'nombre_modelo' => 'Galaxy / 3256', 'nombre_producto' => 'Galaxy / 3256 / Rel.:3.70 (Nacional)'],
            ['nombre_tipo_unidad' => 'Miler', 'nombre_modelo' => 'Miler 4.5T DR', 'nombre_producto' => 'Miler 2'],
            ['nombre_tipo_unidad' => 'Miler', 'nombre_modelo' => 'Miler 4.84.5T RS', 'nombre_producto' => 'Miler 3'],
            ['nombre_tipo_unidad' => 'S12', 'nombre_modelo' => 'S12', 'nombre_producto' => 'Aumark S12-2402'],
            ['nombre_tipo_unidad' => 'S12', 'nombre_modelo' => 'S12', 'nombre_producto' => 'Aumark S12-EV'],
            ['nombre_tipo_unidad' => 'S12', 'nombre_modelo' => 'S12-E6', 'nombre_producto' => 'Aumark S12-E6 (Nacional)'],
            ['nombre_tipo_unidad' => 'S20', 'nombre_modelo' => 'NO SE ENCUENTRA', 'nombre_producto' => 'Aumark S20'],
            ['nombre_tipo_unidad' => 'S3', 'nombre_modelo' => 'S3', 'nombre_producto' => 'Aumark S3 (Importado)'],
            ['nombre_tipo_unidad' => 'S3', 'nombre_modelo' => 'S3', 'nombre_producto' => 'Aumark S3'],
            ['nombre_tipo_unidad' => 'S3', 'nombre_modelo' => 'S3 EV', 'nombre_producto' => 'Aumark S3 EV'],
            ['nombre_tipo_unidad' => 'S3', 'nombre_modelo' => 'S3-E6 AMT', 'nombre_producto' => 'Aumark S3-E6-AMT'],
            ['nombre_tipo_unidad' => 'S3', 'nombre_modelo' => 'S3-E6 MT', 'nombre_producto' => 'Aumark S3-E6-MT'],
            ['nombre_tipo_unidad' => 'S35', 'nombre_modelo' => 'S35', 'nombre_producto' => 'Aumark S35'],
            ['nombre_tipo_unidad' => 'S5', 'nombre_modelo' => 'S5', 'nombre_producto' => 'Aumark S5'],
            ['nombre_tipo_unidad' => 'S5', 'nombre_modelo' => 'S5-E6 AMT', 'nombre_producto' => 'Aumark S5-E6-AMT (Importado)'],
            ['nombre_tipo_unidad' => 'S5', 'nombre_modelo' => 'S5-E6 AMT', 'nombre_producto' => 'Aumark S5-E6-AMT (Nacional)'],
            ['nombre_tipo_unidad' => 'S5', 'nombre_modelo' => 'S5-E6 MT', 'nombre_producto' => 'Aumark S5-E6-MT (Importado)'],
            ['nombre_tipo_unidad' => 'S5', 'nombre_modelo' => 'S5-E6 MT', 'nombre_producto' => 'Aumark S5-E6-MT (Nacional)'],
            ['nombre_tipo_unidad' => 'S6', 'nombre_modelo' => 'S6', 'nombre_producto' => 'Aumark S6'],
            ['nombre_tipo_unidad' => 'S6', 'nombre_modelo' => 'S6', 'nombre_producto' => 'Aumark S6 (Nacional)'],
            ['nombre_tipo_unidad' => 'S6', 'nombre_modelo' => 'S6-E6 MT', 'nombre_producto' => 'Aumark S6-E6-MT (Importado)'],
            ['nombre_tipo_unidad' => 'S6', 'nombre_modelo' => 'S6-E6 MT', 'nombre_producto' => 'Aumark S6-E6-MT (Nacional)'],
            ['nombre_tipo_unidad' => 'S8', 'nombre_modelo' => 'S8', 'nombre_producto' => 'Aumark S8'],
            ['nombre_tipo_unidad' => 'S8', 'nombre_modelo' => 'S8', 'nombre_producto' => 'Aumark S8 (R19.5)'],
            ['nombre_tipo_unidad' => 'Tunland', 'nombre_modelo' => 'Tunland E5', 'nombre_producto' => 'TUNLAND G-CS'],
            ['nombre_tipo_unidad' => 'Tunland', 'nombre_modelo' => 'Tunland G7 AT', 'nombre_producto' => 'Tunland G7 AT'],
            ['nombre_tipo_unidad' => 'Tunland', 'nombre_modelo' => 'Tunland G7 MT', 'nombre_producto' => 'Tunland G7 MT'],
            ['nombre_tipo_unidad' => 'Tunland', 'nombre_modelo' => 'Tunland G7 MT Gasolina', 'nombre_producto' => 'Tunland G7 MT Gasolina'],
            ['nombre_tipo_unidad' => 'Tunland', 'nombre_modelo' => 'Tunland G9 AT', 'nombre_producto' => 'Tunland G9 AT'],
            ['nombre_tipo_unidad' => 'Tunland', 'nombre_modelo' => 'Tunland V7 (MHEV)', 'nombre_producto' => 'Tunland V7 (MHEV)'],
            ['nombre_tipo_unidad' => 'Tunland', 'nombre_modelo' => 'Tunland V9 (MHEV)', 'nombre_producto' => 'Tunland V9 (MHEV)'],
            ['nombre_tipo_unidad' => 'VIEW', 'nombre_modelo' => 'BECCAR URBI G2 (Ver B)', 'nombre_producto' => 'D9-BECCAR URBI G2'],
            ['nombre_tipo_unidad' => 'VIEW', 'nombre_modelo' => 'Chasis Araña', 'nombre_producto' => 'AUV BJ6118 Chasis CNG'],
            ['nombre_tipo_unidad' => 'VIEW', 'nombre_modelo' => 'Chasis Araña', 'nombre_producto' => 'FOTON D9 Midibus (Nacional)'],
            ['nombre_tipo_unidad' => 'VIEW', 'nombre_modelo' => 'NO SE ENCUENTRA', 'nombre_producto' => 'EST-A / 3246 Relacion (3.083)'],
            ['nombre_tipo_unidad' => 'VIEW', 'nombre_modelo' => 'NO SE ENCUENTRA', 'nombre_producto' => 'EST-A / 3246 Relacion (3.364)'],
            ['nombre_tipo_unidad' => 'VIEW', 'nombre_modelo' => 'FOTON-D9 Midibus (Ver B)', 'nombre_producto' => 'FOTON D9 Midibus (Nacional)'],
            ['nombre_tipo_unidad' => 'VIEW', 'nombre_modelo' => 'S10', 'nombre_producto' => 'Aumark S10'],
            ['nombre_tipo_unidad' => 'VIEW', 'nombre_modelo' => 'TM Chasis C/AB', 'nombre_producto' => 'Aumark TM'],
            ['nombre_tipo_unidad' => 'VIEW', 'nombre_modelo' => 'Toano Panel', 'nombre_producto' => 'TOANO P'],
            ['nombre_tipo_unidad' => 'VIEW', 'nombre_modelo' => 'Toano Panel', 'nombre_producto' => 'TOANO PANEL HR TM'],
            ['nombre_tipo_unidad' => 'VIEW', 'nombre_modelo' => 'Toano Pasajero', 'nombre_producto' => 'TOANO PASAJEROS'],
            ['nombre_tipo_unidad' => 'VIEW', 'nombre_modelo' => 'View CS2 Panel', 'nombre_producto' => 'View CS2 P'],
            ['nombre_tipo_unidad' => 'VIEW', 'nombre_modelo' => 'View CS2 Panel', 'nombre_producto' => 'VIEW CS2-2501 Panel (Nacional)'],
            ['nombre_tipo_unidad' => 'VIEW', 'nombre_modelo' => 'View CS2 Royal', 'nombre_producto' => 'View CS2'],
            ['nombre_tipo_unidad' => 'VIEW', 'nombre_modelo' => 'AYCO ORION FT', 'nombre_producto' => 'AYCO ORION FT'],
            ['nombre_tipo_unidad' => 'Wonder', 'nombre_modelo' => 'Wonder', 'nombre_producto' => 'N/D'],
            ['nombre_tipo_unidad' => 'Wonder', 'nombre_modelo' => 'Wonder EV', 'nombre_producto' => 'Wonder EV'],
        ];

        DB::table('models')->insert($models);
    }
}
