<?php

namespace App\Http\Controllers;

use App\Mail\Instructor;
use App\Mail\InstructorCliente;
use App\Mail\NuevoHorario;
use App\Models\Course;
use App\Models\CourseSchedule;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
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

    public function indexTypeUserAgenda($id)
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

    public function show($id)
    {
        try {
            $schedule = CourseSchedule::with('course', 'instructor', 'reservations', 'reservations.student')->findOrFail($id);

            return response()->json($schedule, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Categoría no encontrada', 'mensaje' => $e->getMessage()], 404);
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

            $url = url('http://localhost:3000/Agenda');

            $courseOwner = $schedule->course->user;
            if ($courseOwner && $courseOwner->email) {
                Mail::to($courseOwner->email)->send(new NuevoHorario($schedule, $reservation, $url));
            }

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

    public function update(Request $request, $id)
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

            $schedule = CourseSchedule::findOrFail($id);
            $date = Carbon::parse($validated['start_date']);

            $existingSchedule = CourseSchedule::where('course_id', $validated['course_id'])
                ->whereDate('start_date', $date->toDateString())
                ->where('id', '!=', $id)
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

            $schedule->update($validated);

            $reservation = Reservation::where('schedule_id', $schedule->id)->first();
            if ($reservation) {
                $reservation->update([
                    'course_id' => $validated['course_id'],
                ]);
            }

            return response()->json([
                'success'     => true,
                'mensaje'     => 'Horario y reserva actualizados correctamente.',
                'schedule'    => $schedule,
                'reservation' => $reservation ? $reservation->load(['student', 'course', 'schedule']) : null,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'error'   => 'Error de validación.',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => 'Error al actualizar el horario y reserva.',
                'mensaje' => $e->getMessage(),
            ], 500);
        }
    }

    public function assignInstructor(Request $request, $scheduleId)
    {
        try {
            $validated = $request->validate([
                'instructor_id' => 'required|exists:users,id',
            ], [
                'instructor_id.required' => 'El ID del instructor es obligatorio.',
                'instructor_id.exists' => 'El instructor no existe.',
            ]);

            $schedule = CourseSchedule::with('reservations.student', 'course')->findOrFail($scheduleId);
            if (!$schedule) {
                return response()->json(['error' => 'No existe ese horario con ese id.'], 404);
            }

            $schedule->instructor_id = $validated['instructor_id'];
            $schedule->save();

            $reservation = $schedule->reservations->first();
            $url = url('http://localhost:3000/Agenda');

            // Enviar correo al instructor
            $instructor = $schedule->instructor;
            if ($instructor && $instructor->email) {
                Mail::to($instructor->email)->send(new Instructor($schedule, $reservation, $url));
            }

            // Enviar correo al cliente (student)
            if ($reservation && $reservation->student && $reservation->student->email) {
                Mail::to($reservation->student->email)->send(new InstructorCliente($schedule, $reservation, $url));
            }

            return response()->json([
                'mensaje' => 'Instructor asignado y notificaciones enviadas correctamente.',
                'schedule' => $schedule,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al asignar el instructor',
                'mensaje' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $schedule = CourseSchedule::findOrFail($id);

            Reservation::where('schedule_id', $schedule->id)->delete();

            $schedule->delete();

            return response()->json([
                'success' => true,
                'mensaje' => 'Horario y reservas asociadas eliminados correctamente.'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'error'   => 'El horario no existe.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => 'Error al eliminar el horario.',
                'mensaje' => $e->getMessage(),
            ], 500);
        }
    }
}
