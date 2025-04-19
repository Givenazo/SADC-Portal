<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class VideoController extends Controller
{
    /**
     * Display a listing of the user's uploaded videos.
     */
    public function index()
    {
        $user = auth()->user();
        $videos = \App\Models\Video::where('uploaded_by', $user->id)
            ->orderByDesc('upload_date')
            ->orderByDesc('created_at')
            ->paginate(12);
        $categories = \App\Models\Category::all();
        return view('videos.index', compact('videos', 'categories'));
    }
    /**
     * Show the video upload form.
     */
    public function create()
    {
        $categories = \App\Models\Category::all();
        $user = auth()->user();
        // For member users, country is their own country
        $country = $user->country;
        // Fetch all active videos (status = 'Published')
        $activeVideos = \App\Models\Video::with('uploader')
            ->where('status', 'Published')
            ->orderByDesc('upload_date')
            ->get();
        return view('videos.create', compact('categories', 'country', 'activeVideos'));
    }
    /**
     * Store a newly uploaded video.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'video_file' => 'required|file|mimes:mp4,avi,mov,wmv|max:512000',
            'script_file' => 'required|file|mimes:pdf,doc,docx,txt|max:10240',
            'voiceover_file' => 'nullable|file|mimes:mp3,wav|max:20480',
            'preview_thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'country_id' => 'required|exists:countries,id',
            'status' => 'required|in:Published,Archived,Blocked',
            'comments_enabled' => 'boolean',
        ]);

        // Create video record first to get the ID
        $video = Video::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'video_path' => '', // Temporary empty value
            'script_path' => '', // Temporary empty value
            'uploaded_by' => Auth::id(),
            'upload_date' => now()->toDateString(),
            'voiceover_path' => null,
            'preview_thumbnail' => null,
            'country_id' => $validated['country_id'],
            'status' => $validated['status'],
            'comments_enabled' => $validated['comments_enabled'] ?? true,
        ]);

        // Create directories for this video
        $videoDir = "videos/{$video->id}";
        $scriptDir = "scripts/{$video->id}";
        $voiceoverDir = "voiceovers/{$video->id}";
        $thumbnailDir = "thumbnails/{$video->id}";

        // Store files in their respective directories
        $videoPath = $request->file('video_file')->store($videoDir, 'public');
        $scriptPath = $request->file('script_file')->store($scriptDir, 'public');
        $voiceoverPath = $request->file('voiceover_file') ? $request->file('voiceover_file')->store($voiceoverDir, 'public') : null;
        $thumbnailPath = $request->file('preview_thumbnail') ? $request->file('preview_thumbnail')->store($thumbnailDir, 'public') : null;

        // Update video record with file paths
        $video->update([
            'video_path' => $videoPath,
            'script_path' => $scriptPath,
            'voiceover_path' => $voiceoverPath,
            'preview_thumbnail' => $thumbnailPath,
        ]);

        // Dispatch VideoUploaded event
        event(new \App\Events\VideoUploaded($video));

        return redirect()->route('videos.index');
    }

    /**
     * Show all test videos to members.
     */
    public function testVideos()
    {
        $videos = Video::orderByDesc('upload_date')->paginate(12);
        return view('member.videos.index', compact('videos'));
    }

    /**
     * Delete a video uploaded by the authenticated user.
     */
    public function destroy($id)
    {
        $video = Video::findOrFail($id);
        
        // Check if user has permission to delete
        if ($video->uploaded_by !== auth()->id() && !auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'You do not have permission to delete this video.');
        }

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

        $video->delete();
        return redirect()->back()->with('success', 'Video deleted successfully.');
    }

    /**
     * Update a video uploaded by the authenticated user.
     */
    public function update(Request $request, $id)
    {
        $video = Video::findOrFail($id);
        if ($video->uploaded_by !== auth()->id()) {
            return redirect()->route('dashboard');
        }
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'video_file' => 'nullable|file|mimes:mp4,avi,mov,wmv|max:512000',
            'script_file' => 'nullable|file|mimes:pdf,doc,docx,txt|max:10240',
            'voiceover_file' => 'nullable|file|mimes:mp3,wav|max:20480',
            'preview_thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $video->title = $validated['title'];
        $video->description = $validated['description'];
        $video->category_id = $validated['category_id'];

        // Define directories for this video
        $videoDir = "videos/{$video->id}";
        $scriptDir = "scripts/{$video->id}";
        $voiceoverDir = "voiceovers/{$video->id}";
        $thumbnailDir = "thumbnails/{$video->id}";

        // Handle file replacements
        if ($request->hasFile('video_file')) {
            if ($video->video_path) Storage::disk('public')->delete($video->video_path);
            $video->video_path = $request->file('video_file')->store($videoDir, 'public');
        }
        if ($request->hasFile('script_file')) {
            if ($video->script_path) Storage::disk('public')->delete($video->script_path);
            $video->script_path = $request->file('script_file')->store($scriptDir, 'public');
        }
        if ($request->hasFile('voiceover_file')) {
            if ($video->voiceover_path) Storage::disk('public')->delete($video->voiceover_path);
            $video->voiceover_path = $request->file('voiceover_file')->store($voiceoverDir, 'public');
        }
        if ($request->hasFile('preview_thumbnail')) {
            if ($video->preview_thumbnail) Storage::disk('public')->delete($video->preview_thumbnail);
            $video->preview_thumbnail = $request->file('preview_thumbnail')->store($thumbnailDir, 'public');
        }

        $video->save();
        return redirect()->route('videos.index');
    }
}
