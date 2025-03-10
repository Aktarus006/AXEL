<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JewelInquiry extends Mailable
{
    use SerializesModels;

    public function __construct(
        public string $userName,
        public string $userEmail,
        public string $userMessage,
        public $jewel
    ) {}

    public function build()
    {
        return $this->markdown('emails.jewel-inquiry')
            ->subject("Inquiry about {$this->jewel->name}");
    }
}
