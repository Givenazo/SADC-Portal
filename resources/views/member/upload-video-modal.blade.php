

    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="uploadVideoModalLabel"><i class="bi bi-upload"></i> Upload a Video</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        
        <form id="uploadVideoForm" method="POST" action="{{ route('videos.store') }}" enctype="multipart/form-data">
  @csrf
  <!-- Hidden fields for required backend values -->

  <input type="hidden" name="country_id" value="{{ Auth::user()->country_id }}">
  <input type="hidden" name="status" value="Published">
  <input type="hidden" name="comments_enabled" value="1">
          @csrf
          <div class="mb-3">
            <label for="title" class="form-label">Title <span class="text-danger">*</span> <span class="text-muted small">(required)</span></label>
            <input type="text" class="form-control" id="title" name="title" required>
          </div>
          <div class="mb-3">
            <label for="description" class="form-label">Description <span class="text-danger">*</span> <span class="text-muted small">(required)</span></label>
            <textarea class="form-control" id="description" name="description" rows="2" required></textarea>
          </div>

          <div class="mb-3">
            <label for="video_file" class="form-label">Video File <span class="text-danger">*</span> <span class="text-muted small">(required)</span></label>
            <input type="file" class="form-control" id="video_file" name="video_file" accept="video/*" required>
            <div class="form-text">Formats: <strong>MP4, AVI, MOV, WMV</strong> | Max: 500MB</div>
          </div>
          <div class="mb-3">
            <label for="script_file" class="form-label">Script File <span class="text-muted small">(optional)</span></label>
            <input type="file" class="form-control" id="script_file" name="script_file" accept=".pdf,.doc,.docx,.txt">
            <div class="form-text">Formats: <strong>PDF, DOC, DOCX, TXT</strong></div>
          </div>
          <div class="mb-3">
            <label for="voiceover_file" class="form-label">Voiceover File <span class="text-muted small">(optional)</span></label>
            <input type="file" class="form-control" id="voiceover_file" name="voiceover_file" accept="audio/*">
            <div class="form-text">Formats: <strong>MP3, WAV, AAC</strong></div>
          </div>
          <div class="mb-3">
            <label for="preview_thumbnail" class="form-label">Preview Thumbnail <span class="text-muted small">(optional)</span></label>
            <input type="file" class="form-control" id="preview_thumbnail" name="preview_thumbnail" accept="image/*">
            <div class="form-text">Formats: <strong>JPG, PNG, GIF</strong></div>
          </div>
          
<button id="uploadVideoBtn" type="submit" class="btn btn-primary w-100">Upload Video</button>
        </form>
      </div>
    </div>
  </div>
</div>
