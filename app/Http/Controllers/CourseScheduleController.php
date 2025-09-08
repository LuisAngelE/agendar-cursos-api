<?php

namespace App\Http\Controllers;

use App\Models\CourseSchedule;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class CourseScheduleController extends Controller
{
    public function index()
    {
        try {
            $schedules = CourseSchedule::with('course', 'course.instructor', 'reservations')->get();
            return response()->json($schedules, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener los horarios', 'mensaje' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'course_id'  => 'required|exists:courses,id',
                'start_date' => 'required|date',
                'start_time' => 'required|date_format:H:i',
                'end_time'   => 'required|date_format:H:i|after:start_time',
            ], [
                'course_id.required'  => 'El ID del curso es obligatorio.',
                'course_id.exists'    => 'El curso seleccionado no existe.',
                'start_date.required' => 'La fecha de inicio es obligatoria.',
                'start_date.date'     => 'La fecha de inicio debe ser válida.',
                'start_time.required' => 'La hora de inicio es obligatoria.',
                'start_time.date_format' => 'La hora de inicio debe tener el formato HH:MM.',
                'end_time.required'   => 'La hora de fin es obligatoria.',
                'end_time.date_format' => 'La hora de fin debe tener el formato HH:MM.',
                'end_time.after'      => 'La hora de fin debe ser posterior a la hora de inicio.',
            ]);

            $schedule = CourseSchedule::create([
                'course_id'  => $validated['course_id'],
                'start_date' => $validated['start_date'],
                'start_time' => $validated['start_time'],
                'end_time'   => $validated['end_time'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Horario creado correctamente.',
                'data'    => $schedule,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'error'   => 'Error de validación.',
                'messages' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => 'Error al crear el horario.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $schedule = CourseSchedule::with('course', 'reservations')->findOrFail($id);
            return response()->json($schedule, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Horario no encontrado', 'mensaje' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $schedule = CourseSchedule::findOrFail($id);

            $validated = $request->validate([
                'course_id'  => 'sometimes|required|exists:courses,id',
                'start_date' => 'sometimes|required|date',
                'start_time' => 'sometimes|required|date_format:H:i',
                'end_time'   => 'sometimes|required|date_format:H:i|after:start_time',
            ], [
                'course_id.required'  => 'El ID del curso es obligatorio.',
                'course_id.exists'    => 'El curso seleccionado no existe.',
                'start_date.required' => 'La fecha de inicio es obligatoria.',
                'start_date.date'     => 'La fecha de inicio debe ser válida.',
                'start_time.required' => 'La hora de inicio es obligatoria.',
                'start_time.date_format' => 'La hora de inicio debe tener el formato HH:MM.',
                'end_time.required'   => 'La hora de fin es obligatoria.',
                'end_time.date_format' => 'La hora de fin debe tener el formato HH:MM.',
                'end_time.after'      => 'La hora de fin debe ser posterior a la hora de inicio.',
            ]);

            $schedule->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Horario actualizado correctamente.',
                'data'    => $schedule,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'error'   => 'Error de validación.',
                'messages' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => 'Error al actualizar el horario.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $schedule = CourseSchedule::findOrFail($id);
            $schedule->delete();
            return response()->json(['mensaje' => 'Horario eliminado correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar el horario', 'mensaje' => $e->getMessage()], 500);
        }
    }
}
