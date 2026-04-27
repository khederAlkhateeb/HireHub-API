<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\ProjectCreatedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendProjectCreatedNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $queue = 'emails';
    public int $tries = 3;
    public array $backoff = [60, 120, 300];

    protected User $recipient;
    protected string $projectTitle;

    public function __construct(User $recipient, string $projectTitle)
    {
        $this->recipient = $recipient;
        $this->projectTitle = $projectTitle;
    }

    public function handle(): void
    {
        $this->recipient->notify(new ProjectCreatedNotification($this->projectTitle));
    }

    public function failed(Throwable $exception): void
    {
        Log::error('SendProjectCreatedNotification failed', [
            'user_id' => $this->recipient->id,
            'project_title' => $this->projectTitle,
            'exception' => $exception->getMessage(),
        ]);
    }
}
