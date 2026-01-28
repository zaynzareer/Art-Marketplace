<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessage extends Mailable
{
    use Queueable, SerializesModels;

    public string $name;
    public string $email;
    public string $subjectKey;
    public string $body;

    /**
     * Create a new message instance.
     */
    public function __construct(string $name, string $email, string $subjectKey, string $body)
    {
        $this->name = $name;
        $this->email = $email;
        $this->subjectKey = $subjectKey;
        $this->body = $body;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subjectMap = [
            'order' => 'Order Inquiry',
            'product' => 'Product Question',
            'seller' => 'Seller Support',
            'general' => 'General Question',
        ];

        $subject = $subjectMap[$this->subjectKey] ?? 'Contact Message';

        return $this->from($this->email, $this->name)
            ->replyTo(config('mail.from.address'), 'Crafty Support')
            ->subject('[Crafty] ' . $subject)
            ->view('emails.contact-message')
            ->with([
                'name' => $this->name,
                'email' => $this->email,
                'subject' => $subject,
                'body' => $this->body,
            ]);
    }
}
