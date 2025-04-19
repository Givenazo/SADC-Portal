<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Video;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\VideoDeletedNotification;
use App\Models\User;
use Carbon\Carbon;

class DeleteExpiredVideos extends Command
{
    protected $signature = 'videos:delete-expired';
    protected $description = 'Delete all videos from the system that have expired (30 days after upload_date)';

    public function handle()
    {
        $now = Carbon::now();
        $expiredVideos = Video::whereDate('upload_date', '<=', $now->copy()->subDays(30))->get();
        $count = 0;
        foreach ($expiredVideos as $video) {
            // Delete video directory and all its contents
            $directories = [
                "videos/{$video->id}",
                "scripts/{$video->id}",
                "voiceovers/{$video->id}",
                "thumbnails/{$video->id}"
            ];

            foreach ($directories as $dir) {
                if (Storage::disk('public')->exists($dir)) {
                    Storage::disk('public')->deleteDirectory($dir);
                }
            }

            // Notify uploader if possible
            $notified = [];
            if ($video->uploader) {
                $video->uploader->notify(new VideoDeletedNotification($video));
                $notified[] = $video->uploader->email;
            }

            // Notify all admins
            $admins = User::where('role_id', 1)->get();
            Notification::send($admins, new VideoDeletedNotification($video));
            foreach ($admins as $admin) {
                $notified[] = $admin->email;
            }

            // Log the deletion
            Log::info('Deleted expired video', [
                'video_id' => $video->id,
                'title' => $video->title,
                'notified' => $notified,
                'deleted_at' => now()->toDateTimeString(),
            ]);

            $video->delete();
            $count++;
        }
        $this->info("Deleted $count expired video(s).");
    }
}
