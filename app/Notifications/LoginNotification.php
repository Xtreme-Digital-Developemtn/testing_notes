<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoginNotification extends Notification
{
    use Queueable;

    public $ipAddress;
    public $loginTime;

    public function __construct($ipAddress = null)
    {
        $this->ipAddress = $ipAddress;
        $this->loginTime = now()->format('Y-m-d H:i:s');
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('تسجيل دخول جديد في حسابك')
            ->greeting('مرحباً ' . $notifiable->name)
            ->line('تم تسجيل دخول جديد في حسابك.')
            ->line('الوقت: ' . $this->loginTime)
            ->line('عنوان IP: ' . ($this->ipAddress ?: 'غير معروف'))
            ->line('إذا لم تقم أنت بهذا التسجيل، يرجى تغيير كلمة المرور فوراً.')
            ->action('تغيير كلمة المرور', url('/profile'))
            ->salutation('مع تحياتنا، فريق الدعم');
    }

    public function toArray($notifiable)
    {
        return [
            'ip_address' => $this->ipAddress,
            'login_time' => $this->loginTime,
        ];
    }
}
