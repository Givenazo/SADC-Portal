@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<style>
    .upload-modern-card {
        border-radius: 1.5rem;
        box-shadow: 0 4px 32px rgba(0,0,0,0.10);
        overflow: hidden;
        border: none;
    }
    .upload-illustration {
        background: linear-gradient(135deg, #e0f7fa 0%, #e3ffe6 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        min-height: 410px;
    }
    .upload-illustration .bi {
        font-size: 5rem;
        color: #43cea2;
        opacity: 0.8;
    }
    .upload-tips {
        margin-top: 2rem;
        font-size: 1rem;
        color: #185a9d;
        background: rgba(67,206,162,0.08);
        border-radius: 1rem;
        padding: 1rem 1.5rem;
    }
    .drag-drop-area {
        border: 2px dashed #43cea2;
        border-radius: 1rem;
        background: #f7fcfa;
        text-align: center;
        padding: 2rem 1rem;
        margin-bottom: 1.5rem;
        transition: border-color 0.2s;
    }
    .drag-drop-area.dragover {
        border-color: #185a9d;
        background: #e0f7fa;
    }
    .modern-upload-btn {
        background: linear-gradient(90deg, #43cea2 0%, #185a9d 100%);
        color: #fff;
        font-weight: 600;
        border-radius: 0.5rem;
        padding: 0.75rem 2.5rem;
        font-size: 1.15rem;
        box-shadow: 0 2px 12px rgba(67,206,162,0.12);
        border: none;
        transition: background 0.2s;
    }
    .modern-upload-btn:hover {
        background: linear-gradient(90deg, #185a9d 0%, #43cea2 100%);
        color: #fff;
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<style>
    .upload-modern-card {
        border-radius: 1.5rem;
        box-shadow: 0 4px 32px rgba(0,0,0,0.10);
        overflow: hidden;
        border: none;
    }
    .upload-illustration {
        background: linear-gradient(135deg, #e0f7fa 0%, #e3ffe6 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        min-height: 410px;
    }
    .upload-illustration .bi {
        font-size: 5rem;
        color: #43cea2;
        opacity: 0.8;
    }
    .upload-tips {
        margin-top: 2rem;
        font-size: 1rem;
        color: #185a9d;
        background: rgba(67,206,162,0.08);
        border-radius: 1rem;
        padding: 1rem 1.5rem;
    }
    .drag-drop-area {
        border: 2px dashed #43cea2;
        border-radius: 1rem;
        background: #f7fcfa;
        text-align: center;
        padding: 2rem 1rem;
        margin-bottom: 1.5rem;
        transition: border-color 0.2s;
    }
    .drag-drop-area.dragover {
        border-color: #185a9d;
        background: #e0f7fa;
    }
    .modern-upload-btn {
        background: linear-gradient(90deg, #43cea2 0%, #185a9d 100%);
        color: #fff;
        font-weight: 600;
        border-radius: 0.5rem;
        padding: 0.75rem 2.5rem;
        font-size: 1.15rem;
        box-shadow: 0 2px 12px rgba(67,206,162,0.12);
        border: none;
        transition: background 0.2s;
    }
    .modern-upload-btn:hover {
        background: linear-gradient(90deg, #185a9d 0%, #43cea2 100%);
        color: #fff;
    }
</style>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card upload-modern-card">
                <div class="row g-0">
                    <div class="col-md-5 d-none d-md-flex flex-column upload-illustration">
                        <div class="w-100 text-center">
                            <i class="bi bi-cloud-arrow-up"></i>
                            <h4 class="fw-bold mt-4 mb-2 text-success">Share Your Video with SADC</h4>
                            <div class="upload-tips">
                                <ul class="mb-0 ps-3">
                                    <li>Accepted formats: MP4, AVI, MOV, WMV</li>
                                    <li>Max size: 500MB</li>
                                    <li>Attach your script and optional voiceover</li>
                                    <li>Preview thumbnail is optional</li>
                                    <li>Make sure your content follows community guidelines</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7 bg-white p-4 p-lg-5">
                        <h3 class="fw-bold mb-4 text-primary"><i class="bi bi-upload"></i> Upload a Video</h3>
                        


<form method="POST" action="{{ route('videos.store') }}" enctype="multipart/form-data">
    <input type="hidden" name="country_id" value="{{ isset($country) ? $country->id : '' }}">
    <input type="hidden" name="status" value="Published">
                            @csrf
                            <div class="row g-4 align-items-center mb-3">
                                <div class="col-md-4 text-md-end">
                                    <label for="title" class="form-label fw-semibold">Title</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control form-control-lg" id="title" name="title" required>
                                </div>
                                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                    <label for="description" class="form-label fw-semibold">Description</label>
                                </div>
                                <div class="col-md-8 mt-3 mt-md-0">
                                    <textarea class="form-control form-control-lg" id="description" name="description" rows="2" required></textarea>
                                </div>
                                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                    <label for="category_id" class="form-label fw-semibold">Category</label>
                                </div>
                                <div class="col-md-8 mt-3 mt-md-0">
    <select class="form-control form-control-lg w-100" style="min-width:0;" id="category_id" name="category_id" required>
        @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
    </select>
</div>
                                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                    <label for="video_file" class="form-label fw-semibold">Video File</label>
                                </div>
                                <div class="col-md-8 mt-3 mt-md-0">
                                    <div class="drag-drop-area" id="dragDropArea">
                                        <i class="bi bi-file-earmark-play-fill display-5 text-success mb-2"></i>
                                        <div class="mb-2">Drag & drop your video here, or click to select</div>
                                        <input type="file" class="form-control d-none" id="video_file" name="video_file" accept="video/*" required>
                                        <button type="button" class="btn btn-outline-success btn-sm mt-2" id="browseBtn">Browse</button>
                                        <div id="videoFileName" class="small text-muted mt-2">
    @if(old('video_file'))
        {{ old('video_file') }}
    @endif
</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-4 mb-3">
                                <div class="col-md-6">
                                    <div class="p-3 h-100 bg-light rounded-3 shadow-sm border">
                                        <label for="script_file" class="form-label fw-semibold">
                                            <i class="bi bi-file-earmark-text text-primary me-1"></i> Script File
                                        </label>
                                        <input type="file" class="form-control form-control-lg" id="script_file" name="script_file" accept=".pdf,.doc,.docx,.txt" required>
                                        <div class="form-text ms-1 mt-1">PDF, DOC, DOCX, TXT &mdash; Max 10MB</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 h-100 bg-light rounded-3 shadow-sm border">
                                        <label for="voiceover_file" class="form-label fw-semibold">
                                            <i class="bi bi-mic text-success me-1"></i> Voiceover File <span class="text-muted small">(optional)</span>
                                        </label>
                                        <input type="file" class="form-control form-control-lg" id="voiceover_file" name="voiceover_file" accept="audio/*">
                                        <div class="form-text ms-1 mt-1">MP3, WAV, AAC &mdash; Max 10MB</div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
    <label for="preview_thumbnail" class="form-label">Preview Thumbnail (optional)</label>
    <div id="thumbnail-preview-wrapper" style="margin-bottom:0.7rem;">
        <img id="thumbnail-preview-img" src="https://placehold.co/120x80?text=No+Image" alt="Thumbnail Preview" style="width:120px;height:80px;object-fit:cover;border-radius:0.5rem;background:#f3f4f6;display:block;" />
    </div>
    <input type="file" class="form-control form-control-lg" id="preview_thumbnail" name="preview_thumbnail" accept="image/*">
</div>
<script>
// Live preview for thumbnail
const thumbInput = document.getElementById('preview_thumbnail');
const thumbImg = document.getElementById('thumbnail-preview-img');
thumbInput.addEventListener('change', function(e) {
    if (thumbInput.files && thumbInput.files[0]) {
        const reader = new FileReader();
        reader.onload = function(ev) {
            thumbImg.src = ev.target.result;
        };
        reader.readAsDataURL(thumbInput.files[0]);
    } else {
        thumbImg.src = 'https://placehold.co/120x80?text=No+Image';
    }
});
</script>
                            <button type="submit" class="modern-upload-btn w-100 mt-3">Upload Video</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Drag and drop for video upload
    const dragDropArea = document.getElementById('dragDropArea');
    const videoInput = document.getElementById('video_file');
    const browseBtn = document.getElementById('browseBtn');
    const fileNameDiv = document.getElementById('videoFileName');
    dragDropArea.addEventListener('click', () => videoInput.click());
    browseBtn.addEventListener('click', (e) => { e.stopPropagation(); videoInput.click(); });
    dragDropArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        dragDropArea.classList.add('dragover');
    });
    dragDropArea.addEventListener('dragleave', () => dragDropArea.classList.remove('dragover'));
    dragDropArea.addEventListener('drop', (e) => {
        e.preventDefault();
        dragDropArea.classList.remove('dragover');
        if (e.dataTransfer.files.length) {
            videoInput.files = e.dataTransfer.files;
            fileNameDiv.textContent = e.dataTransfer.files[0].name;
        }
    });
    videoInput.addEventListener('change', () => {
        if (videoInput.files.length) {
            fileNameDiv.textContent = videoInput.files[0].name;
        }
    });
    // On page load, if there is a file selected (after validation error), show its name
    document.addEventListener('DOMContentLoaded', function() {
        if (videoInput.files && videoInput.files.length) {
            fileNameDiv.textContent = videoInput.files[0].name;
        }
    });
</script>
@endsection
