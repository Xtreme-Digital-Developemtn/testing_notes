<?php

namespace App\Notifications;

use App\Models\ClientRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClientRequestNotification extends Notification
{
    use Queueable;

    public function __construct(
        public ClientRequest $clientRequest,
        public string $type,
        public string $message,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $subject = match ($this->type) {
            'in_progress' => 'بدء العمل على طلبك',
            'solved'      => 'تم حل طلبك',
            'reply'       => 'رد جديد على طلبك',
            default       => 'إشعار جديد',
        };

        return (new MailMessage)
            ->subject($subject)
            ->greeting("مرحباً {$notifiable->name}")
            ->line($this->message)
            ->line("رقم الطلب: #{$this->clientRequest->id}")
            ->action('عرض الطلب', route('client.requests.show', $this->clientRequest->id))
            ->line('شكراً لاستخدامك نظام إدارة الطلبات.');
    }
}
