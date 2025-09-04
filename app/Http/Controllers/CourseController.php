<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CourseController extends Controller
{
    // Listar todos los cursos
    public function index()
    {
        try {
            $courses = Course::with('instructor', 'schedules', 'reservations')->get();
            return response()->json($courses, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener los cursos', 'mensaje' => $e->getMessage()], 500);
        }
    }

    // Mostrar un curso específico
    public function show($id)
    {
        try {
            $course = Course::with('instructor', 'schedules', 'reservations')->findOrFail($id);
            return response()->json($course, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Curso no encontrado', 'mensaje' => $e->getMessage()], 404);
        }
    }

    // Crear un nuevo curso
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'instructor_id' => 'required|exists:users,id',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'modality' => 'required|string|max:50',
                'capacity' => 'nullable|integer|min:1',
            ], [
                'instructor_id.required' => 'El instructor es obligatorio',
                'instructor_id.exists' => 'El instructor no existe',
                'title.required' => 'El título es obligatorio',
                'title.max' => 'El título no puede tener más de 255 caracteres',
                'description.required' => 'La descripción  es obligatoria',
                'modality.required' => 'La modalidad es obligatoria',
                'modality.max' => 'La modalidad no puede tener más de 50 caracteres',
                'capacity.integer' => 'La capacidad debe ser un número entero',
                'capacity.min' => 'La capacidad debe ser al menos 1',
            ]);

            $course = Course::create($validated);

            return response()->json(['mensaje' => 'Curso creado correctamente', 'curso' => $course], 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Error de validación', 'mensajes' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear el curso', 'mensaje' => $e->getMessage()], 500);
        }
    }

    // Actualizar un curso existente
    public function update(Request $request, $id)
    {
        try {
            $course = Course::findOrFail($id);

            $validated = $request->validate([
                'instructor_id' => 'sometimes|required|exists:users,id',
                'title' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'modality' => 'sometimes|required|string|max:50',
                'capacity' => 'sometimes|required|integer|min:1',
            ], [
                'instructor_id.required' => 'El instructor es obligatorio',
                'instructor_id.exists' => 'El instructor no existe',
                'title.required' => 'El título es obligatorio',
                'title.max' => 'El título no puede tener más de 255 caracteres',
                'modality.required' => 'La modalidad es obligatoria',
                'modality.max' => 'La modalidad no puede tener más de 50 caracteres',
                'capacity.required' => 'La capacidad es obligatoria',
                'capacity.integer' => 'La capacidad debe ser un número entero',
                'capacity.min' => 'La capacidad debe ser al menos 1',
            ]);

            $course->update($validated);

            return response()->json(['mensaje' => 'Curso actualizado correctamente', 'curso' => $course], 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Error de validación', 'mensajes' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar el curso', 'mensaje' => $e->getMessage()], 500);
        }
    }

    // Eliminar un curso
    public function destroy($id)
    {
        try {
            $course = Course::findOrFail($id);
            $course->delete();

            return response()->json(['mensaje' => 'Curso eliminado correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar el curso', 'mensaje' => $e->getMessage()], 500);
        }
    }
}
