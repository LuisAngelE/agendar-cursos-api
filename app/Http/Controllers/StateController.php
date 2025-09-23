<?php

namespace App\Http\Controllers;

use App\Models\State;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function index()
    {
        try {
            $states = State::all();
            return response()->json([
                'success' => true,
                'message' => 'Estados obtenidos correctamente',
                'data'    => $states
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los estados',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function getMunicipalitiesByState($state_id)
    {
        try {
            $state = State::findOrFail($state_id);
            $municipalities = $state->municipalities;

            return response()->json([
                'success' => true,
                'message' => 'Municipios obtenidos correctamente',
                'data'    => $municipalities
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los municipios',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
