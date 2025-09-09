<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Course;
use App\Models\ImageCourse;

class ImageCourseController extends Controller
{
    public function courseImageUpload(Request $request, $id)
    {
        try {
            $course = Course::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'type'  => 'nullable|string|max:50'
            ], [
                'image.required' => 'La imagen es obligatoria.',
                'image.image'    => 'El archivo debe ser una imagen.',
                'image.mimes'    => 'La imagen debe ser jpeg, png, jpg o gif.',
                'image.max'      => 'La imagen no debe pesar más de 2MB.',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $file = $request->file('image');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();

            $path = $file->storeAs('courses', $filename, 'public');
            $url = asset('storage/' . $path);

            if ($request->get('type') === 'portada') {
                $existing = $course->images()->where('type', 'portada')->first();
                if ($existing) {
                    $oldPath = str_replace('storage/', '', parse_url($existing->url, PHP_URL_PATH));
                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                    $existing->update(['url' => $url]);
                    return response()->json([
                        'message' => 'Imagen de portada actualizada correctamente',
                        'image'   => ['url' => $url]
                    ], 200);
                }
            }

            $image = $course->images()->create([
                'url'  => $url,
                'type' => $request->get('type') ?? 'general'
            ]);

            return response()->json([
                'message' => 'Imagen subida correctamente',
                'image'   => $image
            ], 201);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error'   => 'Curso no encontrado',
                'message' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'Error al subir la imagen',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
