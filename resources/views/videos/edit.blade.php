@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<style>
    .upload-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }
    .upload-card {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        border: none;
    }
    .upload-content {
        padding: 2rem;
    }
    .form-control {
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
        border-color: #e2e8f0;
        transition: all 0.3s ease;
    }
    .form-control:focus {
        border-color: #43cea2;
        box-shadow: 0 0 0 3px rgba(67, 206, 162, 0.1);
    }
    .btn-upload {
        background: linear-gradient(135deg, #43cea2 0%, #185a9d 100%);
        color: white;
        border: none;
        border-radius: 0.75rem;
        padding: 1rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-upload:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(67, 206, 162, 0.2);
    }
    .thumbnail-preview {
        width: 200px;
        height: 120px;
        object-fit: cover;
        border-radius: 0.75rem;
        margin-bottom: 1rem;
        background: #f3f4f6;
    }
    .file-input-container {
        background: #f8f9fa;
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
</style>

<div class="upload-container">
    <div class="upload-card">
        <form method="POST" action="{{ route('videos.update', $video->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="country_id" value="{{ $video->country_id }}">
            <input type="hidden" name="status" value="{{ $video->status }}">
            <input type="hidden" name="comments_enabled" value="{{ $video->comments_enabled ? 1 : 0 }}">

            <div class="upload-content">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold">Edit Video</h3>
                    <a href="{{ route('videos.index') }}" class="text-gray-600 hover:text-gray-800">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
                
                <!-- Basic Information -->
                <div class="section-content mb-5">
                    <h4 class="mb-4">Video Information</h4>
                    <div class="mb-4">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg" id="title" name="title" required
                               value="{{ old('title', $video->title) }}"
                               placeholder="Enter a descriptive title for your video">
                    </div>
                    <div class="mb-4">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control form-control-lg" id="description" name="description" rows="4" required
                                  placeholder="Provide a detailed description of your video content">{{ old('description', $video->description) }}</textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                        <select class="form-control form-control-lg" id="category_id" name="category_id" required>
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $video->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Current Files -->
                <div class="section-content mb-5">
                    <h4 class="mb-4">Current Files</h4>
                    
                    @if($video->video_path)
                    <div class="file-input-container">
                        <h5 class="mb-3">Current Video</h5>
                        <video width="320" height="240" controls class="mb-3">
                            <source src="{{ asset('storage/' . $video->video_path) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                    @endif

                    @if($video->preview_thumbnail)
                    <div class="file-input-container">
                        <h5 class="mb-3">Current Thumbnail</h5>
                        <img src="{{ asset('storage/' . $video->preview_thumbnail) }}" 
                             alt="Current thumbnail" 
                             class="thumbnail-preview">
                    </div>
                    @endif
                </div>

                <!-- File Updates -->
                <div class="section-content mb-5">
                    <h4 class="mb-4">Update Files</h4>
                    
                    <div class="file-input-container">
                        <label for="video_file" class="form-label">
                            <i class="bi bi-camera-video me-2"></i>Update Video File
                        </label>
                        <input type="file" class="form-control" id="video_file" name="video_file" accept="video/*">
                        <div class="form-text">Leave empty to keep the current video</div>
                    </div>

                    <div class="file-input-container">
                        <label for="script_file" class="form-label">
                            <i class="bi bi-file-text me-2"></i>Update Script File
                        </label>
                        <input type="file" class="form-control" id="script_file" name="script_file" accept=".pdf,.doc,.docx,.txt">
                        <div class="form-text">Leave empty to keep the current script</div>
                    </div>

                    <div class="file-input-container">
                        <label for="voiceover_file" class="form-label">
                            <i class="bi bi-mic me-2"></i>Update Voiceover File
                        </label>
                        <input type="file" class="form-control" id="voiceover_file" name="voiceover_file" accept="audio/*">
                        <div class="form-text">Leave empty to keep the current voiceover</div>
                    </div>

                    <div class="file-input-container">
                        <label for="preview_thumbnail" class="form-label">
                            <i class="bi bi-image me-2"></i>Update Thumbnail
                        </label>
                        <input type="file" class="form-control" id="preview_thumbnail" name="preview_thumbnail" accept="image/*">
                        <div class="form-text">Leave empty to keep the current thumbnail</div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end gap-3">
                    <a href="{{ route('videos.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-upload">
                        <i class="bi bi-check-lg me-2"></i>Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Thumbnail preview
const thumbInput = document.getElementById('preview_thumbnail');
const thumbImg = document.querySelector('.thumbnail-preview');

if (thumbInput && thumbImg) {
    thumbInput.addEventListener('change', function(e) {
        if (thumbInput.files && thumbInput.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                thumbImg.src = e.target.result;
            };
            reader.readAsDataURL(thumbInput.files[0]);
        }
    });
}
</script>
@endsection 