<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CancelReservation extends Mailable
{
    use Queueable, SerializesModels;

    public $schedule;
    public $reservation;
    public $url;

    public function __construct($schedule, $reservation, $url)
    {
        $this->schedule = $schedule;
        $this->reservation = $reservation;
        $this->url = $url;
    }

    public function build()
    {
        $email = $this->from('notificacion@ldrsolutions.com.mx', 'LDR Solutions, Foton')
            ->subject('Reservación Cancelada')
            ->view('mail.cancelreservation');

        if (!empty($this->reservation->proof_path)) {
            $filePath = storage_path('app/public/' . $this->reservation->proof_path);
            if (file_exists($filePath)) {
                $email->attach($filePath, [
                    'as' => 'comprobante_cancelacion.' . pathinfo($filePath, PATHINFO_EXTENSION),
                    'mime' => mime_content_type($filePath),
                ]);
            }
        }

        return $email;
    }
}
