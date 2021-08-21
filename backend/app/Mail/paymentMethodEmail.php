<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class paymentMethodEmail extends Mailable
{
    use Queueable, SerializesModels;
    private $reservation;
    private $advance;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($reservation, $advance)
    {
        $this->reservation = $reservation;
        $this->advance = $advance;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $boton = '<a href="' . $this->advance->link . '" target="_blank" class="link-black" style=" color: #000001; text-decoration: none;">
            <span class="link-black" style=" color: #000001; text-decoration: none; ">' . $this->advance->link . '</span></a>';
        $saludo = "Hola {$this->reservation->name}, hemos recibido una petición de pago por link. 
        <br> El link de pago para cancelar tu reservación con código <strong>{$this->reservation->code}</strong>, 
        es: {$boton}.";

        return $this->subject("Link de Pago - Reservación No. {$this->reservation->code}")
            ->view('email.link')
            ->with([
                'saludo' => $saludo,
                'url' => $this->advance->link,
                'reservation' => $this->reservation
            ]);
    }
}
