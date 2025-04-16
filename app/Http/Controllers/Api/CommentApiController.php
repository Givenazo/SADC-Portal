<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Video;
use App\Models\Comment;

class CommentApiController extends Controller
{
    // Store a comment on a video
    public function store(Request $request, Video $video)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);
        $user = $request->user();
        $comment = new Comment([
            'user_id' => $user->id,
            'video_id' => $video->id,
            'comment' => $request->comment,
        ]);
        $comment->save();
        return response()->json(['comment' => $comment], 201);
    }
}
