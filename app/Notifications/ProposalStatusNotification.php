<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ProposalStatusNotification extends Notification
{
    use Queueable;

    protected string $status;
    protected string $projectTitle;

    public function __construct(string $status, string $projectTitle)
    {
        $this->status = $status;
        $this->projectTitle = $projectTitle;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->greeting('Hello ' . $notifiable->first_name)
            ->line('Project: ' . $this->projectTitle);

        if ($this->status === 'accepted') {
            $mail->subject('Proposal Accepted')
                ->line('Congratulations! Your proposal has been accepted.')
                ->line('Please check your dashboard for next steps.');
        } else {
            $mail->subject('Proposal Update')
                ->line('Your proposal was not selected for this project.')
                ->line('Thank you for your effort and keep applying.');
        }

        return $mail->action('Visit HireHub', url('/'))
            ->line('Thank you for using our platform.');
    }
}
