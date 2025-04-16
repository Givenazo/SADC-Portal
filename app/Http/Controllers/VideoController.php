<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{
    /**
     * Display a listing of the user's uploaded videos.
     */
    public function index()
    {
        $user = auth()->user();
        $videos = \App\Models\Video::where('uploaded_by', $user->id)->latest('upload_date')->paginate(12);
        return view('videos.index', compact('videos'));
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
            'preview_thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'country_id' => 'required|exists:countries,id',
            'status' => 'required|in:Published,Archived,Blocked',
            'comments_enabled' => 'boolean',
        ]);

        // Store files
        $videoPath = $request->file('video_file')->store('videos', 'public');
        $scriptPath = $request->file('script_file')->store('scripts', 'public');
        $voiceoverPath = $request->file('voiceover_file') ? $request->file('voiceover_file')->store('voiceovers', 'public') : null;
        $thumbnailPath = $request->file('preview_thumbnail') ? $request->file('preview_thumbnail')->store('thumbnails', 'public') : null;

        $video = Video::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'video_path' => $videoPath,
            'script_path' => $scriptPath,
            'uploaded_by' => Auth::id(),
            'upload_date' => now()->toDateString(),
            'voiceover_path' => $voiceoverPath,
            'preview_thumbnail' => $thumbnailPath,
            'country_id' => $validated['country_id'],
            'status' => $validated['status'],
            'comments_enabled' => $validated['comments_enabled'] ?? true,
        ]);

        // Dispatch VideoUploaded event
        event(new \App\Events\VideoUploaded($video));

        return redirect()->route('videos.create')->with('success', 'Video uploaded successfully!');
    }

    /**
     * Delete a video uploaded by the authenticated user.
     */
    public function destroy($id)
    {
        $video = Video::findOrFail($id);
        if ($video->uploaded_by !== auth()->id()) {
            return redirect()->route('videos.index')->with('error', 'You are not authorized to delete this video.');
        }
        // Optionally: Delete associated files from storage
        if ($video->video_path) {
            Storage::disk('public')->delete($video->video_path);
        }
        if ($video->script_path) {
            Storage::disk('public')->delete($video->script_path);
        }
        if ($video->voiceover_path) {
            Storage::disk('public')->delete($video->voiceover_path);
        }
        if ($video->preview_thumbnail) {
            Storage::disk('public')->delete($video->preview_thumbnail);
        }
        $video->delete();
        return redirect()->route('videos.index')->with('success', 'Video deleted successfully.');
    }
}
