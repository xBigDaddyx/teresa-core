<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PlanQueueNotification extends Notification
{
    use Queueable;
    public $mail_content;
    /**
     * Create a new notification instance.
     */
    public function __construct($mail_content)
    {
        $this->mail_content = $mail_content;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line($this->mail_content['first_line'])
            ->line($this->mail_content['second_line'])
            ->action('View Plan Queue', url($this->mail_content['url_action']))
            ->line('Thank you for using our application!')
            ->line('Best Regards')
            ->line('Teresa Helpdesk (Cartini Division)');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
