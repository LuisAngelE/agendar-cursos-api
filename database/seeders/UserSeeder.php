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
            'type_person' => '5',
            'rfc' => 'EIML031227HMC',
            'email' => 'luisangelem.dp@gmail.com',
            'phone' => '0123456789',
            'collaborator_number' => '250001',
            'razon_social' => 'LDR Solutions',
            'representante_legal' => 'CEO',
            'domicilio_fiscal' => 'Santa Fe',
            'type_user' => 1,
            'updated_at' => now(),
            'created_at' => now(),
            'password' => Hash::make('password'),
        ]);
    }
}
