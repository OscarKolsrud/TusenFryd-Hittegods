<?php

namespace App\Mail;

use App\Models\Investigation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewMessageNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $investigation;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Investigation $investigation)
    {
        $this->investigation = $investigation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.newmessage-notification')
                    ->subject('VIKTIG! Ny oppdatering i din etterlysning');
    }
}
