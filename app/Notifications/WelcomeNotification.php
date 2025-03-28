<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $message = (new MailMessage)
            ->subject('Welcome to ' . config('app.name'))
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Thank you for joining our platform. We\'re excited to have you on board!');

        if ($notifiable->role === 'chercheur') {
            $message->line('As a job seeker, you can now:')
                ->line('- Browse and apply for jobs')
                ->line('- Create and manage your profile')
                ->line('- Save jobs for later')
                ->action('Complete Your Profile', url('/profile'));
        } else {
            $message->line('As a recruiter, you can now:')
                ->line('- Post job listings')
                ->line('- Manage your company profile')
                ->line('- Review applications')
                ->action('Post Your First Job', url('/emplois/create'));
        }

        $message->line('If you have any questions, feel free to contact our support team.')
            ->line('Best regards,')
            ->salutation('The ' . config('app.name') . ' Team');

        return $message;
    }
} 