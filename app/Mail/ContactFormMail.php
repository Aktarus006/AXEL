<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public string $name,
        public string $email,
        public string $message
    ) {}

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->markdown('emails.contact-form')
            ->subject('Nouveau message de contact')
            ->with([
                'name' => $this->name,
                'email' => $this->email,
                'messageContent' => $this->message
            ]);
    }
}
