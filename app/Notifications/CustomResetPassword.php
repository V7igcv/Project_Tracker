<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPassword extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $resetUrl = url(config('app.url') . '/reset-password/' . $this->token . '?email=' . urlencode($notifiable->getEmailForPasswordReset()));

        return (new MailMessage)
            ->subject('Reset Your Password - Project Tracker') // Custom subject
            ->greeting('Hello ' . $notifiable->name . '!') // Personalized greeting
            ->line('We received a request to reset your password for your Project Tracker account.')
            ->line('Click the button below to reset your password. This link will expire in 60 minutes.')
            ->action('Reset Password', $resetUrl)
            ->line('If you did not request a password reset, no further action is required.')
            ->line('Thank you for using Project Tracker!')
            ->salutation('Best regards, The Project Tracker Team');
    }
}