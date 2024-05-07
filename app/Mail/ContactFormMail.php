<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;
    public $bodyContent;
    public $subject;

    
    public function __construct($bodyContent)
    {
        $this->bodyContent = $bodyContent;
        $this->subject( $bodyContent['type'].' '.'Request Mail - Lourdes Shrine Perambur');
    }

  
    public function envelope()
    {
        return new Envelope(
            subject: $this->subject, 
        );
    }

 
    public function content()
    {
        return new Content(
            view: 'admin.mail.contact',
        );
    }

  
    public function attachments()
    {
        return [];
    }
}
