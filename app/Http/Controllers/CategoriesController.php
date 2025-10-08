<?php

namespace App\Http\Controllers;

use App\Models\categories;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CategoriesController extends Controller
{
    public function index()
    {
        try {
            $categories = categories::all();
            return response()->json($categories, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener las categorías', 'mensaje' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $category = categories::findOrFail($id);
            return response()->json($category, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Categoría no encontrada', 'mensaje' => $e->getMessage()], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name',
                'description' => 'nullable|string',
            ], [
                'name.required' => 'El nombre es obligatorio',
                'name.max' => 'El nombre no puede tener más de 255 caracteres',
                'name.unique' => 'El nombre ya existe',
            ]);

            $category = categories::create($validated);

            return response()->json(['mensaje' => 'Categoría creada correctamente', 'categoría' => $category], 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Error de validación', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear la categoría', 'mensaje' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $category = categories::with('courses')->findOrFail($id);

            if ($category->courses->count() > 0) {
                return response()->json([
                    'error' => 'No se puede actualizar la categoría',
                    'mensaje' => 'Esta categoría tiene cursos asociados y no puede ser modificada.',
                ], 400);
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
                'description' => 'nullable|string',
            ], [
                'name.required' => 'El nombre es obligatorio',
                'name.max' => 'El nombre no puede tener más de 255 caracteres',
                'name.unique' => 'El nombre ya existe',
            ]);

            $category->update($validated);

            return response()->json(['mensaje' => 'Categoría actualizada correctamente', 'categoría' => $category], 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Error de validación', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar la categoría', 'mensaje' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $category = categories::with('courses')->findOrFail($id);

            if ($category->courses->count() > 0) {
                return response()->json([
                    'error' => 'No se puede eliminar la categoría',
                    'mensaje' => 'Esta categoría tiene cursos asociados y no puede ser eliminada.',
                ], 400);
            }

            $category->delete();

            return response()->json(['mensaje' => 'Categoría eliminada correctamente.'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al eliminar la categoría',
                'mensaje' => $e->getMessage(),
            ], 500);
        }
    }
}
