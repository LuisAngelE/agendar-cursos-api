<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseSchedule;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class CourseScheduleController extends Controller
{
    public function index()
    {
        try {
            $schedule = CourseSchedule::with('course', 'instructor', 'reservations', 'reservations.student')->get();
            return response()->json($schedule, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener los cursos', 'mensaje' => $e->getMessage()], 500);
        }
    }

    public function indexTypeUser($id)
    {
        try {
            $schedule = CourseSchedule::with('course', 'instructor', 'reservations', 'reservations.student')
                ->whereHas('course', function ($query) use ($id) {
                    $query->where('instructor_id', $id);
                })
                ->orWhereHas('reservations', function ($query) use ($id) {
                    $query->where('student_id', $id);
                })
                ->get();

            return response()->json($schedule, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener los cursos', 'mensaje' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'course_id'  => 'required|exists:courses,id',
                'start_date' => 'required|date_format:Y-m-d H:i:s',
                'location'   => 'required|string|max:255',
                'start_time' => 'nullable|date_format:H:i',
                'end_time'   => 'nullable|date_format:H:i|after:start_time',
            ], [
                'course_id.required'     => 'El ID del curso es obligatorio.',
                'course_id.exists'       => 'El curso seleccionado no existe.',
                'start_date.required'    => 'La fecha de inicio es obligatoria.',
                'start_date.date_format' => 'La fecha de inicio debe tener el formato AAAA-MM-DD HH:MM:SS.',
                'location.required'      => 'La ubicación es obligatoria.',
                'location.string'        => 'La ubicación debe ser una cadena de texto.',
                'location.max'           => 'La ubicación no debe exceder los 255 caracteres.',
                'start_time.date_format' => 'La hora de inicio debe tener el formato HH:MM.',
                'end_time.date_format'   => 'La hora de fin debe tener el formato HH:MM.',
                'end_time.after'         => 'La hora de fin debe ser posterior a la hora de inicio.',
            ]);

            $date = Carbon::parse($validated['start_date']);

            $existingSchedule = CourseSchedule::where('course_id', $validated['course_id'])
                ->whereDate('start_date', $date->toDateString())
                ->first();

            if ($existingSchedule) {
                return response()->json([
                    'success' => false,
                    'error'   => 'Ya existe un horario registrado para este curso en esta fecha.',
                ], 422);
            }

            if ($date->isWeekend()) {
                return response()->json([
                    'success' => false,
                    'error'   => 'Solo puedes agendar de lunes a viernes.'
                ], 422);
            }

            if ($date->isPast()) {
                return response()->json([
                    'success' => false,
                    'error'   => 'No puedes agendar en una fecha pasada.'
                ], 422);
            }

            $schedule = CourseSchedule::create($validated);

            $reservation = Reservation::create([
                'student_id'  => Auth::id(),
                'course_id'   => $validated['course_id'],
                'schedule_id' => $schedule->id,
                'status'      => Reservation::STATUS_PENDING,
            ]);

            return response()->json([
                'success'     => true,
                'mensaje'     => 'Horario y reserva creados correctamente.',
                'schedule'    => $schedule,
                'reservation' => $reservation->load(['student', 'course', 'schedule']),
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success'  => false,
                'error'    => 'Error de validación.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => 'Error al crear el horario y reserva.',
                'mensaje' => $e->getMessage(),
            ], 500);
        }
    }

    public function assignInstructor(Request $request, $courseId)
    {
        try {
            $validated = $request->validate([
                'instructor_id' => 'required|exists:users,id',
            ], [
                'instructor_id.required' => 'El ID del instructor es obligatorio.',
                'instructor_id.exists' => 'El instructor no existe.',
            ]);

            $schedule = CourseSchedule::where('course_id', $courseId)->first();
            if (!$schedule) {
                return response()->json(['error' => 'No hay horarios para este curso.'], 404);
            }

            $schedule->instructor_id = $validated['instructor_id'];
            $schedule->save();

            return response()->json([
                'mensaje' => 'Instructor asignado correctamente al curso.',
                'curso' => $schedule,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al asignar el instructor al curso',
                'mensaje' => $e->getMessage(),
            ], 500);
        }
    }
}
