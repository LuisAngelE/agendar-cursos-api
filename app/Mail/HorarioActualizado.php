<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HorarioActualizado extends Mailable
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
        return $this->from('notificacion@ldrsolutions.com.mx', 'LDR Solutons, Foton')
            ->subject('Tienes un Curso Reservado Actualizado')
            ->view('mail.horarioactualizado');
    }
}
