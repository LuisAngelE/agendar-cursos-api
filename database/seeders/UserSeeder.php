<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'id' => 1,
                'type_person' => 5,
                'rfc' => 'EIML031227HMC',
                'email' => 'luisangelem.dp@gmail.com',
                'phone' => '0123456789',
                'collaborator_number' => '250001',
                'razon_social' => 'LDR Solutions',
                'representante_legal' => 'CEO',
                'domicilio_fiscal' => 'Santa Fe',
                'type_user' => 1,
                'name' => 'Luis Angel',
                'last_name' => 'Espinoza Mauro',
                'birth_date' => '2003-12-27',
                'curp' => 'EIML031227HMCHASDF',
                'created_at' => now(),
                'updated_at' => now(),
                'password' => Hash::make('password'),
            ],
            [
                'id' => 520,
                'name' => 'ABRAHAM',
                'last_name' => 'HERNANDEZ FERNANDEZ',
                'email' => 'abraham.hernandez@ldrsolutions.com.mx',
                'type_user' => 1,
                'type_person' => 4,
                'phone' => '0123456789',
                'collaborator_number' => '240520',
                'birth_date' => '1982-01-21',
                'curp' => 'HEFA820121HHGRRB02',
                'rfc' => 'HEFA820121IE3',
                'razon_social' => null,
                'representante_legal' => null,
                'domicilio_fiscal' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'password' => Hash::make('password'),
            ],
            [
                'id' => 547,
                'name' => 'PEDRO',
                'last_name' => 'LEYVA OJEDA',
                'email' => 'pedro.leyva@ldrsolutions.com.mx',
                'type_user' => 2,
                'type_person' => 4,
                'phone' => '0123456789',
                'collaborator_number' => '240547',
                'birth_date' => '1972-03-09',
                'curp' => 'LEOP720309HQTYJD01',
                'rfc' => 'LEOP720309PT7',
                'razon_social' => null,
                'representante_legal' => null,
                'domicilio_fiscal' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'password' => Hash::make('password'),
            ],
            [
                'id' => 676,
                'name' => 'DAVID',
                'last_name' => 'BERNAL BERNAL',
                'email' => 'david.bernal@ldrsolutions.com.mx',
                'type_user' => 2,
                'type_person' => 4,
                'phone' => '0123456789',
                'collaborator_number' => '250669',
                'birth_date' => '1982-05-14',
                'curp' => 'BEBD820514HMCRRV04',
                'rfc' => 'BEBD820514IF7',
                'razon_social' => null,
                'representante_legal' => null,
                'domicilio_fiscal' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'password' => Hash::make('password'),
            ],
        ]);
    }
}
