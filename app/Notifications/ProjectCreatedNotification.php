<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ProjectCreatedNotification extends Notification
{
    use Queueable;

    protected $projectTitle;

    public function __construct($projectTitle)
    {
        $this->projectTitle = $projectTitle;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Project Created')
            ->greeting('Hello ' . $notifiable->first_name)
            ->line('A new project has been created:')
            ->line($this->projectTitle)
            ->action('View Platform', url('/'))
            ->line('Thank you for using HireHub!');
    }
}
