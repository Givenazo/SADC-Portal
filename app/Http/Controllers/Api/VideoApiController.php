<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Video;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        // Create video record first to get the ID
        $video = Video::create([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'video_path' => '', // Temporary empty value
            'script_path' => '', // Temporary empty value
            'uploaded_by' => $user->id,
            'upload_date' => now(),
            'voiceover_path' => null,
            'preview_thumbnail' => null,
            'country_id' => $user->country_id,
            'status' => 'Published',
            'comments_enabled' => true,
        ]);

        // Create directories for this video
        $videoDir = "videos/{$video->id}";
        $scriptDir = "scripts/{$video->id}";
        $voiceoverDir = "voiceovers/{$video->id}";

        // Store files in their respective directories
        $videoPath = $request->file('video_file')->store($videoDir, 'public');
        $scriptPath = $request->file('script_file')->store($scriptDir, 'public');
        $voiceoverPath = $request->file('voiceover_file') ? $request->file('voiceover_file')->store($voiceoverDir, 'public') : null;

        // Update video record with file paths
        $video->update([
            'video_path' => $videoPath,
            'script_path' => $scriptPath,
            'voiceover_path' => $voiceoverPath,
        ]);

        return response()->json(['video' => $video], 201);
    }

    // Download a video file
    public function downloadVideo(Video $video)
    {
        // Log the download
        DB::table('audit_logs')->insert([
            'user_id' => auth()->id(),
            'action_type' => 'download',
            'details' => $video->id,
            'created_at' => now()
        ]);

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
