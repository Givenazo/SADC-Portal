<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'video_id' => 'required|exists:videos,id',
            'comment' => 'required|string',
        ]);

        $video = Video::findOrFail($validated['video_id']);
        if (!$video->comments_enabled) {
            return response()->json(['error' => 'Comments are disabled for this video.'], 403);
        }

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'video_id' => $validated['video_id'],
            'comment' => $validated['comment'],
            'created_at' => now(),
        ]);

        return response()->json(['comment' => $comment], 201);
    }
}
