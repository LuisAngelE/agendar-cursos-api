<?php

namespace App\Http\Controllers;

use App\Models\Models;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ModelsController extends Controller
{
    public function index()
    {
        try {
            $models = Models::all();
            return response()->json($models, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener los modelos',
                'mensaje' => $e->getMessage()
            ], 500);
        }
    }

    public function indexByUser($user_id)
    {
        try {
            $models = Models::where('user_id', $user_id)->get();

            return response()->json($models, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener los modelos del usuario',
                'mensaje' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $model = Models::findOrFail($id);
            return response()->json($model, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Modelo no encontrado',
                'mensaje' => $e->getMessage()
            ], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre_segmento' => 'required|string|max:255',
                'nombre_tipo_unidad' => 'required|string|max:255',
            ], [
                'nombre_tipo_unidad.required' => 'El nombre del segmento es obligatorio',
                'nombre_modelo.required' => 'El nombre de tipo unidad es obligatorio',
            ]);

            $validated['user_id'] = auth()->id();

            $model = Models::create($validated);

            return response()->json([
                'mensaje' => 'Modelo creado correctamente',
                'modelo' => $model
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al crear el modelo',
                'mensaje' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $model = Models::with('courses')->findOrFail($id);

            if ($model->courses->count() > 0) {
                return response()->json([
                    'error' => 'No se puede actualizar el modelo',
                    'mensaje' => 'Este modelo tiene cursos asociados y no puede ser modificado.'
                ], 400);
            }

            $validated = $request->validate([
                'nombre_segmento' => 'required|string|max:255',
                'nombre_tipo_unidad' => 'required|string|max:255',
            ], [
                'nombre_tipo_unidad.required' => 'El nombre del segmento es obligatorio',
                'nombre_modelo.required' => 'El nombre de tipo unidad es obligatorio',
            ]);

            $model->update($validated);

            return response()->json([
                'mensaje' => 'Modelo actualizado correctamente',
                'modelo' => $model
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al actualizar el modelo',
                'mensaje' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $model = Models::with('courses')->findOrFail($id);

            if ($model->courses->count() > 0) {
                return response()->json([
                    'error' => 'No se puede eliminar el modelo',
                    'mensaje' => 'Este modelo tiene cursos asociados y no puede ser eliminado.'
                ], 400);
            }

            $model->delete();

            return response()->json(['mensaje' => 'Modelo eliminado correctamente.'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al eliminar el modelo',
                'mensaje' => $e->getMessage()
            ], 500);
        }
    }
}
