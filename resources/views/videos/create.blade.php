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
    .progress-bar-container {
        padding: 2rem 2rem 0;
    }
    .upload-steps {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin-bottom: 2rem;
    }
    .upload-steps::before {
        content: '';
        position: absolute;
        top: 15px;
        left: 0;
        right: 0;
        height: 2px;
        background: #e9ecef;
        z-index: 1;
    }
    .step {
        position: relative;
        z-index: 2;
        background: white;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        border: 2px solid #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: #6c757d;
        transition: all 0.3s ease;
    }
    .step.active {
        border-color: #43cea2;
        color: #43cea2;
    }
    .step.completed {
        background: #43cea2;
        border-color: #43cea2;
        color: white;
    }
    .step-label {
        position: absolute;
        top: 45px;
        left: 50%;
        transform: translateX(-50%);
        white-space: nowrap;
        font-size: 0.85rem;
        color: #6c757d;
    }
    .upload-content {
        padding: 2rem;
    }
    .drag-drop-area {
        border: 2px dashed #43cea2;
        border-radius: 1rem;
        background: #f8fdfb;
        text-align: center;
        padding: 3rem 2rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .drag-drop-area:hover {
        background: #f0faf7;
    }
    .drag-drop-area.dragover {
        background: #e0f7fa;
        border-color: #185a9d;
    }
    .drag-drop-icon {
        font-size: 3.5rem;
        color: #43cea2;
        margin-bottom: 1rem;
    }
    .file-input-container {
        background: #f8f9fa;
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .file-input-container .form-label {
        color: #2d3748;
        font-weight: 600;
        margin-bottom: 0.75rem;
    }
    .file-input-container .form-text {
        color: #718096;
    }
    .thumbnail-preview {
        width: 200px;
        height: 120px;
        object-fit: cover;
        border-radius: 0.75rem;
        margin-bottom: 1rem;
        background: #f3f4f6;
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
    .btn-nav {
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        font-weight: 500;
    }
    .upload-tips {
        background: rgba(67, 206, 162, 0.08);
        border-radius: 1rem;
        padding: 1.5rem;
        margin-top: 2rem;
    }
    .upload-tips ul {
        margin-bottom: 0;
        padding-left: 1.5rem;
    }
    .upload-tips li {
        color: #2d3748;
        margin-bottom: 0.5rem;
    }
    .upload-tips li:last-child {
        margin-bottom: 0;
    }
</style>

<div class="upload-container">
    <div class="upload-card">
        <div class="progress-bar-container">
            <div class="upload-steps">
                <div class="step active" id="step1">
                    1
                    <span class="step-label">Basic Info</span>
                </div>
                <div class="step" id="step2">
                    2
                    <span class="step-label">Video Upload</span>
                </div>
                <div class="step" id="step3">
                    3
                    <span class="step-label">Additional Files</span>
                </div>
                <div class="step" id="step4">
                    4
                    <span class="step-label">Review</span>
                </div>
            </div>
        </div>

        <form id="uploadVideoForm" method="POST" action="{{ route('videos.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="country_id" value="{{ isset($country) ? $country->id : '' }}">
            <input type="hidden" name="status" value="Published">
            <input type="hidden" name="comments_enabled" value="1">

            <div class="upload-content">
                <h3 class="mb-4 fw-bold">Upload Video</h3>
                
                <!-- Basic Information -->
                <div class="section-content mb-5">
                    <h4 class="mb-4">Video Information</h4>
                    <div class="mb-4">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg" id="title" name="title" required
                               placeholder="Enter a descriptive title for your video">
                    </div>
                    <div class="mb-4">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control form-control-lg" id="description" name="description" rows="4" required
                                  placeholder="Provide a detailed description of your video content"></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                        <select class="form-control form-control-lg" id="category_id" name="category_id" required>
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Video Upload -->
                <div class="section-content mb-5">
                    <h4 class="mb-4">Video File</h4>
                    <div class="drag-drop-area" id="dragDropArea">
                        <i class="bi bi-cloud-upload drag-drop-icon"></i>
                        <h4 class="mb-3">Drag & Drop Your Video Here</h4>
                        <p class="text-muted mb-3">or</p>
                        <button type="button" class="btn btn-outline-primary btn-lg" id="browseBtn">Browse Files</button>
                        <input type="file" class="d-none" id="video_file" name="video_file" accept="video/*" required>
                        <div id="videoFileName" class="mt-3 text-muted"></div>
                    </div>
                    <div class="upload-tips">
                        <h5 class="mb-3"><i class="bi bi-info-circle me-2"></i>Upload Guidelines</h5>
                        <ul>
                            <li>Maximum file size: 500MB</li>
                            <li>Supported formats: MP4, AVI, MOV, WMV</li>
                            <li>Recommended resolution: 1920x1080 (Full HD)</li>
                            <li>Keep your video length under 30 minutes</li>
                        </ul>
                    </div>
                </div>

                <!-- Additional Files -->
                <div class="section-content mb-5">
                    <h4 class="mb-4">Additional Files</h4>
                    <div class="file-input-container">
                        <label for="script_file" class="form-label">
                            <i class="bi bi-file-text me-2"></i>Script/Transcript File <span class="text-danger">*</span>
                        </label>
                        <input type="file" class="form-control" id="script_file" name="script_file" 
                               accept=".pdf,.doc,.docx,.txt" required>
                        <div class="form-text">Accepted formats: PDF, DOC, DOCX, TXT (Max: 10MB)</div>
                    </div>

                    <div class="file-input-container">
                        <label for="voiceover_file" class="form-label">
                            <i class="bi bi-mic me-2"></i>Voiceover File <span class="text-muted">(optional)</span>
                        </label>
                        <input type="file" class="form-control" id="voiceover_file" name="voiceover_file" accept="audio/*">
                        <div class="form-text">Accepted formats: MP3, WAV (Max: 20MB)</div>
                    </div>

                    <div class="file-input-container">
                        <label for="preview_thumbnail" class="form-label">
                            <i class="bi bi-image me-2"></i>Preview Thumbnail <span class="text-muted">(optional)</span>
                        </label>
                        <img id="thumbnail-preview-img" src="https://placehold.co/200x120?text=No+Image" 
                             alt="Thumbnail Preview" class="thumbnail-preview">
                        <input type="file" class="form-control" id="preview_thumbnail" name="preview_thumbnail" accept="image/*">
                        <div class="form-text">Recommended size: 1280x720px (16:9 ratio)</div>
                    </div>
                </div>

                <!-- Live Review -->
                <div class="section-content mb-5">
                    <h4 class="mb-4">Upload Summary</h4>
                    <div class="review-content bg-light p-4 rounded-3">
                        <!-- Will be updated dynamically -->
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-upload" id="submitBtn">
                        <i class="bi bi-cloud-upload me-2"></i>Upload Video
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Drag and drop functionality
const dragDropArea = document.getElementById('dragDropArea');
const videoInput = document.getElementById('video_file');
const browseBtn = document.getElementById('browseBtn');
const fileNameDiv = document.getElementById('videoFileName');

dragDropArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    dragDropArea.classList.add('dragover');
});

dragDropArea.addEventListener('dragleave', () => {
    dragDropArea.classList.remove('dragover');
});

dragDropArea.addEventListener('drop', (e) => {
    e.preventDefault();
    dragDropArea.classList.remove('dragover');
    if (e.dataTransfer.files.length) {
        videoInput.files = e.dataTransfer.files;
        updateFileName(e.dataTransfer.files[0]);
        updateReviewContent();
    }
});

browseBtn.addEventListener('click', () => videoInput.click());

videoInput.addEventListener('change', (e) => {
    if (e.target.files.length) {
        updateFileName(e.target.files[0]);
        updateReviewContent();
    }
});

function updateFileName(file) {
    fileNameDiv.innerHTML = `
        <div class="mt-3">
            <i class="bi bi-check-circle-fill text-success me-2"></i>
            <span class="fw-semibold">${file.name}</span>
            <br>
            <small class="text-muted">${(file.size / (1024 * 1024)).toFixed(2)} MB</small>
        </div>
    `;
}

// Thumbnail preview
const thumbInput = document.getElementById('preview_thumbnail');
const thumbImg = document.getElementById('thumbnail-preview-img');

thumbInput.addEventListener('change', function(e) {
    if (thumbInput.files && thumbInput.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            thumbImg.src = e.target.result;
        };
        reader.readAsDataURL(thumbInput.files[0]);
        updateReviewContent();
    }
});

// Update review content whenever any input changes
const formInputs = document.querySelectorAll('input, textarea, select');
formInputs.forEach(input => {
    input.addEventListener('change', updateReviewContent);
    input.addEventListener('input', updateReviewContent);
});

function updateReviewContent() {
    const reviewContent = document.querySelector('.review-content');
    const title = document.getElementById('title').value || 'Not provided';
    const description = document.getElementById('description').value || 'Not provided';
    const category = document.getElementById('category_id').options[document.getElementById('category_id').selectedIndex]?.text || 'Not selected';
    const videoFile = document.getElementById('video_file').files[0]?.name || 'No file selected';
    const scriptFile = document.getElementById('script_file').files[0]?.name || 'No file selected';
    const voiceoverFile = document.getElementById('voiceover_file').files[0]?.name || 'Not provided';
    const thumbnailFile = document.getElementById('preview_thumbnail').files[0]?.name || 'Not provided';

    reviewContent.innerHTML = `
        <div class="row g-4">
            <div class="col-md-6">
                <div class="review-item">
                    <h6 class="fw-bold text-primary mb-3">Video Information</h6>
                    <div class="ps-3">
                        <p class="mb-2"><strong>Title:</strong> ${title}</p>
                        <p class="mb-2"><strong>Category:</strong> ${category}</p>
                        <p class="mb-0"><strong>Description:</strong><br>
                        <small class="text-muted">${description}</small></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="review-item">
                    <h6 class="fw-bold text-primary mb-3">Uploaded Files</h6>
                    <div class="ps-3">
                        <p class="mb-2"><i class="bi bi-file-earmark-play me-2"></i><strong>Video:</strong> ${videoFile}</p>
                        <p class="mb-2"><i class="bi bi-file-text me-2"></i><strong>Script:</strong> ${scriptFile}</p>
                        <p class="mb-2"><i class="bi bi-file-music me-2"></i><strong>Voiceover:</strong> ${voiceoverFile}</p>
                        <p class="mb-0"><i class="bi bi-image me-2"></i><strong>Thumbnail:</strong> ${thumbnailFile}</p>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Initial review content update
updateReviewContent();

// Form validation before submission
document.getElementById('uploadVideoForm').addEventListener('submit', function(e) {
    const requiredFields = ['title', 'description', 'category_id', 'video_file', 'script_file'];
    let isValid = true;

    requiredFields.forEach(field => {
        const element = document.getElementById(field);
        if (!element.value) {
            isValid = false;
            element.classList.add('is-invalid');
        } else {
            element.classList.remove('is-invalid');
        }
    });

    if (!isValid) {
        e.preventDefault();
        alert('Please fill in all required fields before submitting.');
    }
});
</script>
@endsection
