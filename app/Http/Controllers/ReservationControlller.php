<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationControlller extends Controller
{
    public function confirmReservation($reservationId)
    {
        try {
            $reservation = Reservation::find($reservationId);

            if (!$reservation) {
                return response()->json([
                    'error' => 'La reserva no existe.',
                ], 404);
            }

            if ($reservation->status !== Reservation::STATUS_PENDING) {
                return response()->json([
                    'error' => 'Solo se pueden confirmar reservas con estado pendiente.',
                ], 400);
            }

            $reservation->status = Reservation::STATUS_CONFIRMED;
            $reservation->save();

            return response()->json([
                'mensaje' => 'Reserva confirmada correctamente.',
                'reserva' => $reservation,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al confirmar la reserva.',
                'mensaje' => $e->getMessage(),
            ], 500);
        }
    }

    public function cancelReservation($reservationId)
    {
        try {
            $reservation = Reservation::find($reservationId);

            if (!$reservation) {
                return response()->json([
                    'error' => 'La reserva no existe.',
                ], 404);
            }

            if ($reservation->status !== Reservation::STATUS_CONFIRMED) {
                return response()->json([
                    'error' => 'Solo se pueden cancelar reservas con estado confirmado.',
                ], 400);
            }

            $reservation->status = Reservation::STATUS_CANCELED;
            $reservation->save();

            return response()->json([
                'mensaje' => 'Reserva cancelada correctamente.',
                'reserva' => $reservation,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al cancelar la reserva.',
                'mensaje' => $e->getMessage(),
            ], 500);
        }
    }

    public function servedReservation($reservationId)
    {
        try {
            $reservation = Reservation::find($reservationId);

            if (!$reservation) {
                return response()->json([
                    'error' => 'La reserva no fue encontrada.',
                ], 404);
            }

            if ($reservation->status !== Reservation::STATUS_CONFIRMED) {
                return response()->json([
                    'error' => 'Solo se pueden marcar como completadas las reservas en estado confirmado.',
                ], 400);
            }

            $reservation->status = Reservation::STATUS_SERVED;
            $reservation->save();

            return response()->json([
                'mensaje' => 'La reserva se completó exitosamente.',
                'reserva' => $reservation,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Ocurrió un error al intentar completar la reserva.',
                'detalle' => $e->getMessage(),
            ], 500);
        }
    }

    public function rescheduleReservation($reservationId)
    {
        try {
            $reservation = Reservation::find($reservationId);

            if (!$reservation) {
                return response()->json([
                    'error' => 'La reserva no fue encontrada.',
                ], 404);
            }

            if ($reservation->status !== Reservation::STATUS_CANCELED) {
                return response()->json([
                    'error' => 'Solo es posible reprogramar reservas que estén en estado cancelado.',

                ], 400);
            }

            $reservation->status = Reservation::STATUS_CONFIRMED;
            $reservation->save();

            return response()->json([
                'mensaje' => 'La reserva se reprogramó correctamente y ahora está confirmada.',
                'reserva' => $reservation,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Ocurrió un error al intentar reprogramar la reserva.',

                'detalle' => $e->getMessage(),
            ], 500);
        }
    }
}
