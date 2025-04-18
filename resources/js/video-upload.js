





        
    
    
          </div
           class="modal-body text-center"
             id="upload-fail-msg"There was an error uploading your video.</div
          </div
           class="modal-footer justify-content-center"
            <button type="button" class="btn btn-danger" id="fail-ok-btn"OK</button
          </div
        </div
      </div
    </div`;
    // Inject modals if not present
    if (!document.getElementById('uploadSuccessModal')) {
        document.body.insertAdjacentHTML('beforeend', successModalHtml);
    }
    if (!document.getElementById('uploadFailModal')) {
        document.body.insertAdjacentHTML('beforeend', failModalHtml);
    }
    const successModal = new bootstrap.Modal(document.getElementById('uploadSuccessModal'));
    const failModal = new bootstrap.Modal(document.getElementById('uploadFailModal'));

    if (!form) return;
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        btn.disabled = true;
        const formData = new FormData(form);
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: formData
        })
        .then(async response = {
            btn.disabled = false;
            if (response.ok) {
                let data = await response.json();
                document.getElementById('upload-success-msg').textContent = data.message || 'Video uploaded successfully!';
                successModal.show();
                document.getElementById('success-ok-btn').onclick = function () {
                    window.location.href = '/videos';
                };
            } else {
                let errorMsg = 'There was an error uploading your video.';
                try {
                    let data = await response.json();
                    if (data.errors) {
                        errorMsg = Object.values(data.errors).flat().join(' ');
                    } else if (data.message) {
                        errorMsg = data.message;
                    }
                } catch (e) {}
                document.getElementById('upload-fail-msg').textContent = errorMsg;
                failModal.show();
                document.getElementById('fail-ok-btn').onclick = function () {
                    failModal.hide();
                };
            }
        })
        .catch(err = {
            btn.disabled = false;
            document.getElementById('upload-fail-msg').textContent = 'Network error. Please try again.';
            failModal.show();
            document.getElementById('fail-ok-btn').onclick = function () {
                failModal.hide();
            };
        });
    });
});
