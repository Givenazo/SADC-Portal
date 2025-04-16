<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Video;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class VideoApiController extends Controller
{
    // List all available videos
    public function index(Request $request)
    {
        $videos = Video::with(['uploader:id,name', 'country:id,name'])
            ->where('status', 'Published')
            ->latest('upload_date')
            ->get();
        return response()->json($videos);
    }

    // Upload a new video
    public function upload(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'video_file' => 'required|file|mimes:mp4,avi,mov,wmv|max:512000',
            'script_file' => 'required|file|mimes:pdf,doc,docx,txt|max:10240',
            'voiceover_file' => 'nullable|file|mimes:mp3,wav|max:20480',
        ]);
        $user = $request->user();
        $videoPath = $request->file('video_file')->store('videos', 'public');
        $scriptPath = $request->file('script_file')->store('scripts', 'public');
        $voiceoverPath = $request->file('voiceover_file') ? $request->file('voiceover_file')->store('voiceovers', 'public') : null;
        $video = Video::create([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'video_path' => $videoPath,
            'script_path' => $scriptPath,
            'uploaded_by' => $user->id,
            'upload_date' => now(),
            'voiceover_path' => $voiceoverPath,
            'preview_thumbnail' => null,
            'country_id' => $user->country_id,
            'status' => 'Published',
            'comments_enabled' => true,
        ]);
        return response()->json(['video' => $video], 201);
    }

    // Download a video file
    public function downloadVideo(Video $video)
    {
        // Dispatch VideoDownloaded event for uploader
        event(new \App\Events\VideoDownloaded($video, $video->uploaded_by));
        return Storage::disk('public')->download($video->video_path);
    }

    // Download a script file
    public function downloadScript(Video $video)
    {
        return Storage::disk('public')->download($video->script_path);
    }
}
