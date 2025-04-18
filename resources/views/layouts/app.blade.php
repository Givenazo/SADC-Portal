<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>SADC News Portal</title>
<link rel="icon" type="image/x-icon" href="/favicon.ico">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            @if(Auth::user() && Auth::user()->role && Auth::user()->role->name === 'Admin')
            <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
                <div class="container">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto">

                        </ul>
                    </div>
                </div>
            </nav>
            @endif

            <!-- Page Heading -->
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    @yield('header')
                </div>
            </header>

            <!-- Page Content -->
            <main>
                {{-- $slot removed: not a Blade component layout --}}
                @yield('content')
            </main>
        </div>
        <footer class="text-center py-3 mt-5 bg-white shadow-sm" style="font-size:1rem;">
            &copy; {{ date('Y') }} SADC News Portal. All Rights Reserved.
        </footer>

        <!-- Upload Video Modal -->
        <div class="modal fade" id="uploadVideoModal" tabindex="-1" aria-labelledby="uploadVideoModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="uploadVideoModalLabel"><i class="bi bi-upload"></i> Upload a Video</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form method="POST" action="{{ route('videos.store') }}" enctype="multipart/form-data" id="uploadVideoModalForm">
  <input type="hidden" name="comments_enabled" value="1">
                  @csrf
                  <div class="mb-3">
                    <label for="modal-title" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="modal-title" name="title" required>
                  </div>
                  <div class="mb-3">
                    <label for="modal-description" class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="modal-description" name="description" rows="2" required></textarea>
                  </div>
                  <div class="mb-3">
                    <label for="modal-category" class="form-label">Category <span class="text-danger">*</span></label>
                    <select class="form-select" id="modal-category" name="category_id" required>
                      <option value="">Select category</option>
                      @foreach(App\Models\Category::all() as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="modal-video_file" class="form-label">Video File <span class="text-danger">*</span> <span class="text-muted" style="font-weight:normal;font-size:0.95em;">(MP4, AVI, MOV, WMV)</span></label>
                    <input type="file" class="form-control" id="modal-video_file" name="video_file" accept="video/mp4,video/avi,video/mov,video/wmv" required>
                  </div>
                  <div class="mb-3">
                    <label for="modal-script_file" class="form-label">Script File <span class="text-danger">*</span> <span class="text-muted" style="font-weight:normal;font-size:0.95em;">(PDF, DOC, DOCX, TXT)</span></label>
                    <input type="file" class="form-control" id="modal-script_file" name="script_file" accept=".pdf,.doc,.docx,.txt" required>
                  </div>
                  <div class="mb-3">
                    <label for="modal-voiceover_file" class="form-label">Voiceover File (optional) <span class="text-muted" style="font-weight:normal;font-size:0.95em;">(MP3, WAV)</span></label>
                    <input type="file" class="form-control" id="modal-voiceover_file" name="voiceover_file" accept="audio/mp3,audio/wav">
                  </div>
                  <div class="mb-3">
                    <label for="modal-preview_thumbnail" class="form-label">Preview Thumbnail (optional) <span class="text-muted" style="font-weight:normal;font-size:0.95em;">(JPG, JPEG, PNG, GIF)</span></label>
                    <input type="file" class="form-control" id="modal-preview_thumbnail" name="preview_thumbnail" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                  </div>
                  <input type="hidden" name="status" value="Published">
                  <input type="hidden" name="country_id" value="{{ Auth::user() ? Auth::user()->country_id : '' }}">
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-upload"></i> Upload Video</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- Upload Success Modal -->
        <div class="modal fade" id="uploadSuccessModal" tabindex="-1" aria-labelledby="uploadSuccessModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title text-success" id="uploadSuccessModalLabel"><i class="bi bi-check-circle"></i> Upload Successful</h5>
              </div>
              <div class="modal-body">
                Your video has been uploaded successfully.
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-success" id="uploadSuccessOk">OK</button>
              </div>
            </div>
          </div>
        </div>
        <!-- Upload Error Modal -->
        <div class="modal fade" id="uploadErrorModal" tabindex="-1" aria-labelledby="uploadErrorModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title text-danger" id="uploadErrorModalLabel"><i class="bi bi-x-circle"></i> Upload Failed</h5>
              </div>
              <div class="modal-body" id="uploadErrorMsg">
                There was an error uploading your video. Please check the form and try again.
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">OK</button>
              </div>
            </div>
          </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            const uploadForm = document.getElementById('uploadVideoModalForm');
            if (uploadForm) {
              uploadForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(uploadForm);
                const uploadBtn = uploadForm.querySelector('button[type="submit"]');
                if (uploadBtn) uploadBtn.disabled = true;
                fetch(uploadForm.action, {
                  method: 'POST',
                  body: formData,
                  headers: { 'Accept': 'application/json' }
                })
                .then(async response => {
                  let data;
                  try {
                    data = await response.json();
                  } catch (e) {
                    data = null;
                  }
                  if (uploadBtn) uploadBtn.disabled = false;
                  if (response.ok && data && data.success) {
                    // Success: show modal
                    var successModal = new bootstrap.Modal(document.getElementById('uploadSuccessModal'));
                    successModal.show();
                    document.getElementById('uploadSuccessOk').onclick = function() {
                      window.location.href = '{{ route('videos.index') }}';
                    };
                  } else {
                    // Error: show error modal with message
                    let msg = 'There was an error uploading your video.';
                    if (data && data.errors) {
                      msg = '<ul style="margin:0;padding-left:1.2em;">' + Object.values(data.errors).map(errArr => '<li>' + errArr.join('<br>') + '</li>').join('') + '</ul>';
                    } else if (data && data.message) {
                      msg = data.message;
                    } else if (data) {
                      msg = JSON.stringify(data);
                    } else if (!response.ok) {
                      msg = 'Upload failed with status: ' + response.status;
                    }
                    document.getElementById('uploadErrorMsg').innerHTML = msg;
                    console.error('Full upload error response:', data);
                    var errorModal = new bootstrap.Modal(document.getElementById('uploadErrorModal'));
                    errorModal.show();
                  }
                }).finally(() => {
                  if (uploadBtn) uploadBtn.disabled = false;
                })
                .catch((error) => {
                  document.getElementById('uploadErrorMsg').innerHTML = 'There was an error uploading your video.';
                  var errorModal = new bootstrap.Modal(document.getElementById('uploadErrorModal'));
                  errorModal.show();
                  alert('JS Fetch Error: ' + (error && error.toString ? error.toString() : error));
                  console.error('Upload AJAX error:', error);
                });
              });
            }
          });
        </script>
    </body>
</html>
