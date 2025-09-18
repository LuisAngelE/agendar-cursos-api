<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CourseController extends Controller
{
    // Listar todos los cursos
    public function index(Request $request)
    {
        try {
            $query = Course::with('user', 'category', 'images', 'schedules', 'reservations');

            if ($request->has('nombre') && !empty($request->nombre)) {
                $search = $request->nombre;
                $query->where('title', 'LIKE', "%{$search}%");
            }

            if ($request->has('category_id') && !empty($request->category_id)) {
                $query->where('category_id', $request->category_id);
            }

            $courses = $query->get();

            $courses = $courses->map(function ($course) {
                $course->image = $course->images->first();
                unset($course->images);
                return $course;
            });

            return response()->json($courses, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener los cursos', 'mensaje' => $e->getMessage()], 500);
        }
    }

    // Listar cursos asociados a un instructor
    public function indexTypeUserCourse(Request $request, $id)
    {
        try {
            $query = Course::with('user', 'category', 'images', 'schedules', 'reservations')
                ->whereHas('schedules', function ($q) use ($id) {
                    $q->where('instructor_id', $id);
                });

            if ($request->has('nombre') && !empty($request->nombre)) {
                $search = $request->nombre;
                $query->where('title', 'LIKE', "%{$search}%");
            }

            if ($request->has('category_id') && !empty($request->category_id)) {
                $query->where('category_id', $request->category_id);
            }

            $courses = $query->get()->map(function ($course) {
                $course->image = $course->images->first();
                unset($course->images);
                return $course;
            });

            return response()->json($courses, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'Error al obtener los cursos',
                'mensaje' => $e->getMessage()
            ], 500);
        }
    }

    // Mostrar un curso específico
    public function show($id)
    {
        try {
            $course = Course::with('user', 'category', 'images', 'schedules', 'reservations')->findOrFail($id);
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
                'instructor_id' => 'nullable|exists:users,id',
                'category_id' => 'required|exists:categories,id',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'modality' => 'required|string|max:50',
                'duration' => 'required|string|max:100',
                'syllabus_pdf' => 'nullable|string|max:255',
            ], [
                'instructor_id.exists' => 'El instructor no existe',
                'category_id.required' => 'La categoría es obligatoria',
                'category_id.exists' => 'La categoría no existe',
                'title.required' => 'El título es obligatorio',
                'title.max' => 'El título no puede tener más de 255 caracteres',
                'description.required' => 'La descripción es obligatoria',
                'modality.required' => 'La modalidad es obligatoria',
                'modality.max' => 'La modalidad no puede tener más de 50 caracteres',
                'duration.required' => 'La duración es obligatoria',
                'duration.max' => 'La duración no puede tener más de 100 caracteres',
                'syllabus_pdf.max' => 'El nombre del archivo PDF no puede tener más de 255 caracteres',
            ]);

            $validated['user_id'] = auth()->id();

            $course = Course::create($validated);

            return response()->json(['mensaje' => 'Curso creado correctamente', 'curso' => $course], 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Error de validación', 'errors' => $e->errors()], 422);
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
                'instructor_id' => 'nullable|exists:users,id',
                'category_id' => 'required|exists:categories,id',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'modality' => 'required|string|max:50',
                'duration' => 'required|string|max:100',
                'syllabus_pdf' => 'nullable|string|max:255',
            ], [
                'instructor_id.exists' => 'El instructor no existe',
                'category_id.required' => 'La categoría es obligatoria',
                'category_id.exists' => 'La categoría no existe',
                'title.required' => 'El título es obligatorio',
                'title.max' => 'El título no puede tener más de 255 caracteres',
                'description.required' => 'La descripción es obligatoria',
                'modality.required' => 'La modalidad es obligatoria',
                'modality.max' => 'La modalidad no puede tener más de 50 caracteres',
                'duration.required' => 'La duración es obligatoria',
                'duration.max' => 'La duración no puede tener más de 100 caracteres',
                'syllabus_pdf.max' => 'El nombre del archivo PDF no puede tener más de 255 caracteres',
            ]);

            $course->update($validated);

            return response()->json(['mensaje' => 'Curso actualizado correctamente', 'curso' => $course], 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Error de validación', 'errors' => $e->errors()], 422);
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
