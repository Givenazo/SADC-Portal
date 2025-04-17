<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use App\Models\Country;
use App\Models\User;

class AdminVideoController extends Controller
{
    // Display all uploaded videos for admin
    public function index()
    {
        $videos = Video::with(['uploader', 'country'])
            ->orderByDesc('upload_date')
            ->orderByDesc('created_at')
            ->paginate(20);
        return view('admin.videos.index', compact('videos'));
    }
}
