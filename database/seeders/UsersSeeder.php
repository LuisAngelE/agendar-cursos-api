<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $csvPath = database_path('seeders/data/consulta_modificada.csv');

        if (!file_exists($csvPath)) {
            dd("No se encontró el archivo CSV en: " . $csvPath);
        }

        $file = fopen($csvPath, 'r');

        $headers = fgetcsv($file);

        while (($row = fgetcsv($file)) !== false) {
            $record = array_combine($headers, $row);

            User::create([
                'id'                   => $record['id_colaborador'] ?? null,
                'first_name'           => $record['nombre_1'] ?? null,
                'middle_name'          => $record['nombre_2'] ?: null,
                'first_last_name'      => $record['apellido_paterno'] ?? null,
                'second_last_name'     => $record['apellido_materno'] ?? null,
                'email'                => $record['email_corporativo'] !== 'SIN ASIGNAR'
                    ? strtolower($record['email_corporativo'])
                    : null,
                'password'             => Hash::make('password'),
                'phone'                => $record['telefono_corporativo'] ?? null,
                'collaborator_number'  => $record['numero_colaborador'] ?? null,
                'birth_date'           => $this->formatCsvDate($record['fecha_nacimiento']),
                'curp'                 => $record['curp'] ?? null,
                'rfc'                  => $record['rfc'] ?? null,
                'position'             => $record['nombre_puesto'] ?? null,
                'url'                  => $record['avatar'] ?? null,
            ]);
        }

        fclose($file);
    }

    private function formatCsvDate($value)
    {
        if (!$value || trim($value) === '' || $value === '0000-00-00') {
            return null;
        }

        try {
            return \Carbon\Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
        } catch (\Exception $e) {
            try {
                return \Carbon\Carbon::parse($value)->format('Y-m-d');
            } catch (\Exception $e2) {
                return null;
            }
        }
    }
}
