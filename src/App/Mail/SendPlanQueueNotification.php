<?php

namespace App\Mail;

use Domain\Kanban\Models\PlanQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendPlanQueueNotification extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public PlanQueue $planQueue;
    /**
     * Create a new message instance.
     */
    public function __construct($user, PlanQueue $planQueue)
    {
        $this->user = $user;
        $this->planQueue = $planQueue;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Action Required: Plan queue doesn't have next plan",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.kanban.plan-queue.missing',
            with: [
                'data' => $this->queue,
            ]
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
