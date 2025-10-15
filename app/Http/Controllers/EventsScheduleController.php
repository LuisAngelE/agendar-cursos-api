<?php

namespace App\Http\Controllers;

use App\Mail\HorarioActualizado;
use App\Mail\Instructor;
use App\Mail\NuevoHorario;
use App\Models\EventsSchedule;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class EventsScheduleController extends Controller
{
    public function index()
    {
        try {
            $query = EventsSchedule::with([
                'course',
                'instructor',
                'reservations',
                'reservations.student',
                'state',
                'municipality'
            ]);

            $schedule = $query->get();

            return response()->json($schedule, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener los cursos',
                'mensaje' => $e->getMessage()
            ], 500);
        }
    }

    public function indexCount()
    {
        try {
            $schedule = EventsSchedule::select('id')->where('event_type', 'curso')
                ->get();

            return response()->json($schedule, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener los cursos',
                'mensaje' => $e->getMessage()
            ], 500);
        }
    }

    public function indexTypeUserAgenda($id)
    {
        try {
            $schedule = EventsSchedule::with('course', 'instructor', 'reservations', 'reservations.student', 'state', 'municipality')
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

    public function indexTypeUserAgendaCount($id)
    {
        try {
            $schedule = EventsSchedule::whereHas('course', function ($query) use ($id) {
                $query->where('instructor_id', $id);
            })
                ->orWhereHas('reservations', function ($query) use ($id) {
                    $query->where('student_id', $id);
                })
                ->select('id')
                ->get();

            return response()->json($schedule, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener los cursos',
                'mensaje' => $e->getMessage()
            ], 500);
        }
    }

    public function getDates()
    {
        $dates = EventsSchedule::with('instructor')
            ->select('id', 'instructor_id', 'event_type', 'reference_id', 'start_date', 'end_date')
            ->orderBy('start_date', 'asc')
            ->get()
            ->map(function ($schedule) {
                return [
                    'id' => $schedule->id,
                    'event_type' => $schedule->event_type,
                    'reference_id' => $schedule->reference_id,
                    'start_date' => $schedule->start_date,
                    'end_date' => $schedule->end_date,
                    'instructor_id' => $schedule->instructor_id,
                    'instructor_name' => $schedule->instructor ? $schedule->instructor->name : null,
                    'collaborator_number' => $schedule->instructor ? $schedule->instructor->collaborator_number : null,
                ];
            });

        return response()->json($dates);
    }

    public function getDatesTypeUser($id)
    {
        $dates = EventsSchedule::with('instructor')
            ->select('id', 'instructor_id', 'event_type', 'reference_id', 'start_date', 'end_date')
            ->orderBy('start_date', 'asc')
            ->where(function ($query) use ($id) {
                $query->where('instructor_id', $id)
                    ->orWhereHas('course', function ($subQuery) use ($id) {
                        $subQuery->where('instructor_id', $id);
                    })
                    ->orWhereHas('reservations', function ($subQuery) use ($id) {
                        $subQuery->where('student_id', $id);
                    });
            })
            ->get()
            ->map(function ($schedule) {
                return [
                    'id' => $schedule->id,
                    'event_type' => $schedule->event_type,
                    'reference_id' => $schedule->reference_id,
                    'start_date' => $schedule->start_date,
                    'end_date' => $schedule->end_date,
                    'instructor_id' => $schedule->instructor_id,
                    'instructor_name' => $schedule->instructor ? $schedule->instructor->name : null,
                    'collaborator_number' => $schedule->instructor ? $schedule->instructor->collaborator_number : null,
                ];
            });

        return response()->json($dates);
    }

    public function show($id)
    {
        try {
            $schedule = EventsSchedule::with('state', 'municipality', 'course', 'course.category', 'course.models', 'course.user', 'instructor', 'reservations', 'reservations.student')->findOrFail($id);

            return response()->json($schedule, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Categoría no encontrada', 'mensaje' => $e->getMessage()], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'course_id'       => 'required|exists:courses,id',
                'state_id'        => 'required|exists:states,id',
                'municipality_id' => 'required|exists:municipalities,id',
                'start_date'      => 'required|date_format:Y-m-d H:i:s',
                'location'        => 'required|string|max:255',
            ], [
                'course_id.required'       => 'El ID del curso es obligatorio.',
                'course_id.exists'         => 'El curso seleccionado no existe.',
                'state_id.required'        => 'El estado es obligatorio.',
                'state_id.exists'          => 'El estado seleccionado no existe.',
                'municipality_id.required' => 'El municipio es obligatorio.',
                'municipality_id.exists'   => 'El municipio seleccionado no existe.',
                'start_date.required'      => 'La fecha de inicio es obligatoria.',
                'start_date.date_format'   => 'La fecha de inicio debe tener el formato AAAA-MM-DD HH:MM:SS.',
                'location.required'        => 'La ubicación es obligatoria.',
                'location.string'          => 'La ubicación debe ser una cadena de texto.',
                'location.max'             => 'La ubicación no debe exceder los 255 caracteres.',
            ]);

            $date = Carbon::parse($validated['start_date']);

            $existingSchedulesCount  = EventsSchedule::where('course_id', $validated['course_id'])
                ->whereDate('start_date', $date->toDateString())
                ->count();

            if ($existingSchedulesCount >= 6) {
                return response()->json([
                    'success' => false,
                    'error'   => 'Solo se pueden registrar hasta 6 horarios para este curso en la misma fecha.',
                ], 422);
            }

            if ($date->isPast()) {
                return response()->json([
                    'success' => false,
                    'error'   => 'No puedes agendar en una fecha pasada.'
                ], 422);
            }

            $schedule = EventsSchedule::create(array_merge(
                $validated,
                [
                    'event_type'   => 'curso',
                    'reference_id' => $validated['course_id'],
                ]
            ));

            $reservation = Reservation::create([
                'student_id'  => Auth::id(),
                'course_id'   => $validated['course_id'],
                'schedule_id' => $schedule->id,
                'status'      => Reservation::STATUS_PENDING,
            ]);

            $url = getBaseUrl();

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
                'errors'   => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => 'Error al crear el horario y reserva.',
                'mensaje' => $e->getMessage(),
            ], 500);
        }
    }

    public function storeByAdmin(Request $request)
    {
        try {
            $validated = $request->validate([
                'course_id'       => 'required|exists:courses,id',
                'student_id'      => 'required|exists:users,id',
                'state_id'        => 'required|exists:states,id',
                'municipality_id' => 'required|exists:municipalities,id',
                'start_date'      => 'required|date_format:Y-m-d H:i:s',
                'location'        => 'required|string|max:255',
            ], [
                'course_id.required'       => 'El ID del curso es obligatorio.',
                'course_id.exists'         => 'El curso seleccionado no existe.',
                'student_id.required'       => 'El ID del cliente es obligatorio.',
                'student_id.exists'         => 'El cliente seleccionado no existe.',
                'state_id.required'        => 'El estado es obligatorio.',
                'state_id.exists'          => 'El estado seleccionado no existe.',
                'municipality_id.required' => 'El municipio es obligatorio.',
                'municipality_id.exists'   => 'El municipio seleccionado no existe.',
                'start_date.required'      => 'La fecha de inicio es obligatoria.',
                'start_date.date_format'   => 'La fecha de inicio debe tener el formato AAAA-MM-DD HH:MM:SS.',
                'location.required'        => 'La ubicación es obligatoria.',
                'location.string'          => 'La ubicación debe ser una cadena de texto.',
                'location.max'             => 'La ubicación no debe exceder los 255 caracteres.',
            ]);

            $date = Carbon::parse($validated['start_date']);

            $existingSchedulesCount  = EventsSchedule::where('course_id', $validated['course_id'])
                ->whereDate('start_date', $date->toDateString())
                ->count();

            if ($existingSchedulesCount >= 6) {
                return response()->json([
                    'success' => false,
                    'error'   => 'Solo se pueden registrar hasta 6 horarios para este curso en la misma fecha.',
                ], 422);
            }

            if ($date->isPast()) {
                return response()->json([
                    'success' => false,
                    'error'   => 'No puedes agendar en una fecha pasada.'
                ], 422);
            }

            $schedule = EventsSchedule::create(array_merge(
                $validated,
                [
                    'event_type'   => 'curso',
                    'reference_id' => $validated['course_id'],
                ]
            ));

            $reservation = Reservation::create([
                'course_id'   => $validated['course_id'],
                'student_id'   => $validated['student_id'],
                'schedule_id' => $schedule->id,
                'status'      => Reservation::STATUS_PENDING,
            ]);

            return response()->json([
                'success'     => true,
                'mensaje'     => 'Horario y reserva para invitado creados correctamente.',
                'schedule'    => $schedule,
                'reservation' => $reservation,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success'  => false,
                'error'    => 'Error de validación.',
                'errors'   => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => 'Error al crear el horario y reserva.',
                'mensaje' => $e->getMessage(),
            ], 500);
        }
    }

    public function storeDemo(Request $request)
    {
        try {
            $validated = $request->validate([
                'instructor_id' => 'required|exists:users,id',
                'reference_id'  => 'required|integer',
                'start_date'    => 'required|date_format:Y-m-d',
                'end_date'    => 'required|date_format:Y-m-d',
            ], [
                'instructor_id.required' => 'El master driver es obl igatorio.',
                'instructor_id.exists'   => 'El master driver seleccionado no existe.',
                'reference_id.required'  => 'El ID de referencia es obligatorio.',
                'start_date.required'    => 'La fecha de inicio es obligatoria.',
                'start_date.date_format' => 'La fecha de inicio debe tener el formato AAAA-MM-DD',
                'end_date.required'    => 'La fecha fin es obligatoria.',
                'end_date.date_format' => 'La fecha fin debe tener el formato AAAA-MM-DD',
            ]);

            $startDate = Carbon::createFromFormat('Y-m-d', $validated['start_date'])->startOfDay();
            $endDate   = Carbon::createFromFormat('Y-m-d', $validated['end_date'])->endOfDay();

            $existingInstructorAssignment = EventsSchedule::where('instructor_id', $validated['instructor_id'])
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('start_date', [$startDate, $endDate])
                        ->orWhereBetween('end_date', [$startDate, $endDate])
                        ->orWhere(function ($q) use ($startDate, $endDate) {
                            $q->where('start_date', '<=', $startDate)
                                ->where('end_date', '>=', $endDate);
                        });
                })
                ->exists();

            if ($existingInstructorAssignment) {
                return response()->json([
                    'success' => false,
                    'error' => 'Este instructor ya está asignado a un curso o demo en esas fechas.',
                ], 422);
            }

            $existingSchedulesCount = EventsSchedule::whereIn('event_type', ['curso', 'demo'])
                ->whereDate('start_date', $startDate->toDateString())
                ->count();

            if ($existingSchedulesCount >= 3) {
                return response()->json([
                    'success' => false,
                    'error'   => 'Solo se pueden registrar hasta 3 horarios para este demo en la misma fecha.',
                ], 422);
            }

            if ($startDate->lt(Carbon::today())) {
                return response()->json([
                    'success' => false,
                    'error'   => 'No puedes agendar una demo en una fecha pasada.',
                ], 422);
            }

            if ($endDate->lt($startDate)) {
                return response()->json([
                    'success' => false,
                    'error'   => 'La fecha fin no puede ser anterior a la fecha de inicio.',
                ], 422);
            }

            $schedule = EventsSchedule::create([
                'instructor_id' => $validated['instructor_id'],
                'reference_id'  => $validated['reference_id'],
                'start_date'    => $startDate->toDateTimeString(),
                'end_date'      => $endDate->toDateTimeString(),
                'event_type'    => 'demo',
            ]);

            return response()->json([
                'success'  => true,
                'mensaje'  => 'Horario de demo creado correctamente.',
                'schedule' => $schedule,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'error'   => 'Error de validación.',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => 'Error al crear el horario.',
                'mensaje' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'course_id'       => 'required|exists:courses,id',
                'state_id'        => 'required|exists:states,id',
                'municipality_id' => 'required|exists:municipalities,id',
                'start_date'      => 'required|date_format:Y-m-d H:i:s',
                'location'        => 'required|string|max:255',
            ], [
                'course_id.required'       => 'El ID del curso es obligatorio.',
                'course_id.exists'         => 'El curso seleccionado no existe.',
                'state_id.required'        => 'El estado es obligatorio.',
                'state_id.exists'          => 'El estado seleccionado no existe.',
                'municipality_id.required' => 'El municipio es obligatorio.',
                'municipality_id.exists'   => 'El municipio seleccionado no existe.',
                'start_date.required'      => 'La fecha de inicio es obligatoria.',
                'start_date.date_format'   => 'La fecha de inicio debe tener el formato AAAA-MM-DD HH:MM:SS.',
                'location.required'        => 'La ubicación es obligatoria.',
                'location.string'          => 'La ubicación debe ser una cadena de texto.',
                'location.max'             => 'La ubicación no debe exceder los 255 caracteres.',
            ]);

            $schedule = EventsSchedule::findOrFail($id);
            $date = Carbon::parse($validated['start_date']);

            $existingSchedulesCount = EventsSchedule::where('course_id', $validated['course_id'])
                ->whereDate('start_date', $date->toDateString())
                ->where('id', '!=', $schedule->id)
                ->count();

            if ($existingSchedulesCount >= 6) {
                return response()->json([
                    'success' => false,
                    'error'   => 'Solo se pueden registrar hast 6 horarios para este curso en la misma fecha.',
                ], 422);
            }

            if ($date->isPast()) {
                return response()->json([
                    'success' => false,
                    'error'   => 'No puedes actualizar a una fecha pasada.',
                ], 422);
            }

            $schedule->update($validated);

            $reservation = Reservation::where('schedule_id', $schedule->id)->first();
            if ($reservation) {
                $reservation->update([
                    'course_id' => $validated['course_id'],
                    'status'    => Reservation::STATUS_PENDING,
                ]);
            }

            $url = getBaseUrl();

            $courseOwner = $schedule->course->user;
            if ($courseOwner && $courseOwner->email) {
                Mail::to($courseOwner->email)->send(new HorarioActualizado($schedule, $reservation, $url));
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

            $schedule = EventsSchedule::with('reservations.student', 'course')->findOrFail($scheduleId);

            if (!$schedule) {
                return response()->json(['error' => 'No existe ese horario con ese id.'], 404);
            }

            $startDate = \Carbon\Carbon::parse($schedule->start_date);
            $fecha = $startDate->toDateString();
            $hora = $startDate->format('H');

            $existingAssignment = EventsSchedule::where('instructor_id', $validated['instructor_id'])
                ->where('id', '<>', $schedule->id)
                ->whereDate('start_date', $fecha)
                ->whereRaw("HOUR(start_date) = ?", [$hora])
                ->exists();

            if ($existingAssignment) {
                return response()->json([
                    'error' => 'Este instructor ya está asignado a un curso o demo en este día y hora.',
                ], 422);
            }

            $schedule->instructor_id = $validated['instructor_id'];
            $schedule->save();

            $reservation = $schedule->reservations->first();
            $url = getBaseUrl();

            $instructor = $schedule->instructor;
            if ($instructor && $instructor->email) {
                Mail::to($instructor->email)->send(new Instructor($schedule, $reservation, $url));
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
            $schedule = EventsSchedule::findOrFail($id);

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
