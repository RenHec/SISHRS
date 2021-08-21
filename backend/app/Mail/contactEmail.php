<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class contactEmail extends Mailable
{
    use Queueable, SerializesModels;
    protected $reservation;
    protected $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($reservation, $email)
    {
        $this->reservation = $reservation;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $base = Config::get('app.link_contract');
        $url = "$base{$this->reservation->contract->url}";
        $saludo = "Hola {$this->reservation->name}, hemos recibido una reservación. 
        <br> El número de reservación es <strong>{$this->reservation->code}</strong>, 
        el detalle de la reservación te lo damos a conocer a continuación.";

        return $this->subject("Terminos y Condiciones - Reservación No. {$this->reservation->code}")
            ->view('email.contract')
            /*->attachData($this->pdf, 'constancia.pdf', [
                'mime' => 'application/pdf',
            ])*/
            ->with([
                'saludo' => $saludo,
                'url' => $url,
                'reservation' => $this->reservation
            ]);
    }
}
