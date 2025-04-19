<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Video;
use App\Events\VideoDownloaded;

class TrackDownloads
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Check if this is a video download request
        if ($this->isVideoDownload($request)) {
            $videoId = $this->extractVideoId($request->path());
            if ($videoId) {
                $video = Video::find($videoId);
                if ($video) {
                    // Log the download
                    DB::table('audit_logs')->insert([
                        'user_id' => auth()->id(),
                        'action_type' => 'download',
                        'details' => $video->id, // Store video ID for accurate tracking
                        'created_at' => now()
                    ]);

                    // Dispatch event for real-time notifications
                    event(new VideoDownloaded($video, $video->uploaded_by));
                }
            }
        }

        return $response;
    }

    private function isVideoDownload(Request $request)
    {
        $path = $request->path();
        return (
            str_contains($path, '/storage/videos/') && 
            $request->headers->get('sec-fetch-dest') === 'download'
        ) || str_contains($path, '/api/videos/') && str_contains($path, '/download');
    }

    private function extractVideoId($path)
    {
        // Extract video ID from storage path
        if (preg_match('/videos\/(\d+)/', $path, $matches)) {
            return $matches[1];
        }
        
        // Extract video ID from API path
        if (preg_match('/api\/videos\/(\d+)\/download/', $path, $matches)) {
            return $matches[1];
        }

        return null;
    }
} 