<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Video;

class VideoDeletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $video;

    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Video Deleted Due to Expiry')
            ->greeting('Hello!')
            ->line('The following video has been deleted due to expiry:')
            ->line('Title: ' . $this->video->title)
            ->line('Uploader: ' . optional($this->video->uploader)->name)
            ->line('Upload Date: ' . $this->video->upload_date)
            ->line('If you have any questions, please contact support.');
    }
}
