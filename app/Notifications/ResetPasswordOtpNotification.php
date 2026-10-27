<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordOtpNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $otp,
        public int $expiresInMinutes = 15
    ) {}

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
        $locale = $notifiable instanceof User && ! empty($notifiable->locale)
            ? (string) $notifiable->locale
            : app()->getLocale();

        $subject = $locale === 'ar'
            ? 'رمز التحقق لإعادة تعيين كلمة المرور - منصة الأمين'
            : 'Password Reset Verification Code - Alameen Platform';

        return (new MailMessage)
            ->subject($subject)
            ->view('emails.auth.reset_password', [
                'otp' => $this->otp,
                'expiresInMinutes' => $this->expiresInMinutes,
                'user' => $notifiable,
                'locale' => $locale,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'otp' => $this->otp,
            'expires_in_minutes' => $this->expiresInMinutes,
        ];
    }
}
