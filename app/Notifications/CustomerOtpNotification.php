<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomerOtpNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly string $otp)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Kode Verifikasi Login Anda')
            ->greeting('Halo ' . $notifiable->full_name . ',')
            ->line('Gunakan kode berikut untuk masuk ke akun Anda:')
            ->line(new \Illuminate\Support\HtmlString("<h2 style=\"letter-spacing:4px\">{$this->otp}</h2>"))
            ->line('Kode ini berlaku selama 5 menit. Jangan bagikan kode ini kepada siapapun.');
    }
}
