<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $user;
    public $company;
    public $credentials;
    public function __construct($user, $company, $credentials)
    {
        $this->user = $user;
        $this->company = $company;
        $this->credentials = $credentials;
    }


    public function build()
    {
        return $this->subject('Workplan - Pendaftaran Perusahaan Berhasil')
            ->view('emails.registration')
            ->with([
                'user' => $this->user,
                'company' => $this->company,
                'credentials' => $this->credentials,
            ]);
    }
}