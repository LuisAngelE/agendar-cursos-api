<?php

namespace App\Http\Controllers;

use App\Models\CourseSchedule;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class CourseScheduleController extends Controller
{
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
                'message'     => 'Horario y reserva creados correctamente.',
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
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
