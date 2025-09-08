<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    // Listar todos los cursos
    public function index()
    {
        try {
            $courses = Course::with('instructor', 'category', 'schedules', 'reservations')->get();
            return response()->json($courses, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener los cursos', 'mensaje' => $e->getMessage()], 500);
        }
    }

    // Mostrar un curso específico
    public function show($id)
    {
        try {
            $course = Course::with('instructor', 'category', 'schedules', 'reservations')->findOrFail($id);
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

    public function CourseImage(Request $request, $id)
    {
        try {
            $course = Course::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ], [
                'image.required' => 'La imagen es obligatoria.',
                'image.image' => 'El archivo debe ser una imagen.',
                'image.mimes' => 'La imagen debe ser jpeg, png, jpg o gif.',
                'image.max' => 'La imagen no debe pesar más de 2MB.',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/course'), $filename);

            $url = asset('images/course/' . $filename);

            if ($course->images) {
                $oldImage = public_path(parse_url($course->images->url, PHP_URL_PATH));
                if (file_exists($oldImage)) {
                    @unlink($oldImage);
                }

                $course->images->update(['url' => $url]);
            } else {
                $course->images()->create(['url' => $url]);
            }

            return response()->json([
                'message' => 'Imagen subida correctamente',
                'image' => ['url' => $url]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al subir la imagen',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
