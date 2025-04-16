@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2>{{ $video->title }}</h2>
            <p>{{ $video->description }}</p>
            <div class="mb-3">
                <video width="100%" height="auto" controls poster="{{ $video->preview_thumbnail ? asset('storage/' . $video->preview_thumbnail) : '' }}">
                    <source src="{{ asset('storage/' . $video->video_path) }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
            @if($video->voiceover_path)
            <div class="mb-3">
                <h5>Voiceover</h5>
                <audio controls>
                    <source src="{{ asset('storage/' . $video->voiceover_path) }}" type="audio/mpeg">
                    Your browser does not support the audio element.
                </audio>
            </div>
            @endif
            <div class="mb-3">
                <h5>Script</h5>
                <a href="{{ asset('storage/' . $video->script_path) }}" class="btn btn-primary" download>Download Script</a>
                <iframe src="{{ asset('storage/' . $video->script_path) }}" width="100%" height="400" style="border:1px solid #ccc; margin-top:10px;"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection
