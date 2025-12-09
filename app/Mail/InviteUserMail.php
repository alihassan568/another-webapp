<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InviteUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public $inviteLink;
    public $email;
    public $role;
    public $subject;
    public $inviteMessage;

    /**
     * Create a new message instance.
     */
    public function __construct($inviteLink, $email, $role, $subject, $inviteMessage)
    {
        $this->inviteLink = $inviteLink;
        $this->email = $email;
        $this->role = $role;
        $this->subject = $subject;
        $this->inviteMessage = $inviteMessage;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.invite-user',
            with: [
                'inviteLink' => $this->inviteLink,
                'email' => $this->email,
                'role' => $this->role,
                'subject' => $this->subject,
                'inviteMessage' => $this->inviteMessage,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
