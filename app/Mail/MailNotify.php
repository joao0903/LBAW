<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailNotify extends Mailable
{
    use Queueable, SerializesModels;

    public $senderName;
    public $senderEmail;
    public $senderMessage;

    public function __construct($name, $email, $message)
    {
        $this->senderName = $name;
        $this->senderEmail = $email;
        $this->senderMessage = $message;
    }

    public function build()
    {
        return $this->view('emails.contact')->with([
            'name' => $this->senderName,
            'email' => $this->senderEmail,
            'senderMessage' => $this->senderMessage,
        ]);
    }
}
