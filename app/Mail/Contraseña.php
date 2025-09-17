<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Contraseña extends Mailable
{
    use Queueable, SerializesModels;

    public $randomPassword;
    public $user;
    public $url;

    public function __construct($randomPassword, $user, $url)
    {
        $this->randomPassword = $randomPassword;
        $this->user = $user;
        $this->url = $url;
    }

    public function build()
    {
        return $this->from('notificacion@ldrsolutions.com.mx', 'Cursos')
            ->subject('Contraseña de tu cuenta')
            ->view('mail.contraseña');
    }
}
