<?php

namespace App\Mail;

use Domain\Purchases\Models\ApprovalRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendRequestApprovedNotification extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public ApprovalRequest $approvalRequest;
    /**
     * Create a new message instance.
     */
    public function __construct($user, ApprovalRequest $request)
    {
        $this->user = $user;
        $this->approvalRequest = $request;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Request Form Has Been Approved',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.purchase.request.approved',
            with: [
                'data' => $this->approvalRequest,
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
