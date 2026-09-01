<?php

namespace App\Notifications;

use App\Models\ClientRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminRequestNotification extends Notification
{
    use Queueable;

    public function __construct(
        public ClientRequest $clientRequest,
        public string $message,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('إشعار جديد بطلب')
            ->greeting("مرحباً {$notifiable->name}")
            ->line($this->message)
            ->line("رقم الطلب: #{$this->clientRequest->id}")
            ->action('عرض الطلب', route('admin.requests.show', $this->clientRequest->id))
            ->line('شكراً لاستخدامك نظام إدارة الطلبات.');
    }
}
