<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = $request->user();

            $favorites = $user->favoriteCourses()
                ->with('user', 'category', 'images', 'schedules', 'reservations', 'usersWhoFavorited')
                ->get()
                ->map(function ($course) {
                    $course->image = $course->images->first();
                    unset($course->images);
                    return $course;
                });

            return response()->json($favorites, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'Error al obtener los cursos favoritos',
                'mensaje' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'course_id' => 'required|exists:courses,id',
            ], [
                'course_id.required' => 'El curso es obligatorio',
                'course_id.exists'   => 'El curso no existe',
            ]);

            $user = $request->user();

            $user->favoriteCourses()->syncWithoutDetaching([$validated['course_id']]);

            return response()->json([
                'mensaje' => 'Curso agregado a favoritos correctamente'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'error'  => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'Error al agregar el curso a favoritos',
                'mensaje' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, $courseId)
    {
        try {
            $course = Course::findOrFail($courseId);

            $request->user()->favoriteCourses()->detach($course->id);

            return response()->json([
                'mensaje' => 'Curso eliminado de favoritos correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'Error al eliminar el curso de favoritos',
                'mensaje' => $e->getMessage()
            ], 500);
        }
    }
}
