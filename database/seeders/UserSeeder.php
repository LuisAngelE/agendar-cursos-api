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
            [
                'id' => 1,
                'name' => null,
                'last_name' => null,
                'email' => 'luisangelem.dp@gmail.com',
                'type_user' => 1,
                'type_person' => 5,
                'phone' => '0123456789',
                'collaborator_number' => null,
                'birth_date' => null,
                'curp' => null,
                'rfc' => 'EIML031227HLD',
                'razon_social' => 'LDR Solutions',
                'representante_legal' => 'CEO',
                'domicilio_fiscal' => 'Santa Fe',
                'created_at' => now(),
                'updated_at' => now(),
                'password' => Hash::make('password'),
            ],
            [
                'id' => 2,
                'name' => 'Luis Angel',
                'last_name' => 'Espinoza Mauro',
                'email' => 'luis.espinoza@ldrsolutions.com.mx',
                'type_user' => 3,
                'type_person' => 4,
                'phone' => '7291623408',
                'collaborator_number' => '250852',
                'birth_date' => '2003-12-27',
                'curp' => 'EIML031227HMCSRSA3',
                'rfc' => 'EIML031227HMC',
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
