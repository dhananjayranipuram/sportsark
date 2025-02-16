<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingConfirmed extends Mailable
{
    use Queueable, SerializesModels;
    public $email_data;
    /**
     * Create a new message instance.
     */
    public function __construct($email_data,$user)
    {
        $this->email_data = $email_data;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Ground Booking Confirmed',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        if($this->user == 'admin'){
            return new Content(view: 'emails.bookings.bookings-admin');
        }else{
            return new Content(view: 'emails.bookings.bookings-customer');
        }
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
