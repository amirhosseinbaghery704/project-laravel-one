<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

use function Pest\Laravel\from;

class SendPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $fullname;
    public string $password;

    
    public function __construct(string $fullname, string $password)
    {
        $this->fullname = $fullname;
        $this->password = $password;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('info@gitmag.ir', 'Gitmag'),
            subject: 'Account password',
        );

    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.send_password',
            with: [
                'fullname' => $this->fullname,
                'password' => $this->password,
            ],
        );

    }
}
