@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="/css/sadc-custom.css">
<div class="container py-4">
    <div class="bg-light py-3 px-2 mb-4 text-center" style="border-radius:0.5rem;">
      <h1 class="fw-bold d-flex flex-column align-items-center justify-content-center sadc-header-darkblue" style="font-size:3rem;margin-bottom:0;font-weight:bold;text-align:center;color:inherit;">
        <span>
          <i class="bi bi-globe me-1 sadc-header-darkblue" style="font-size:2.5rem;"></i>
          <i class="bi bi-newspaper me-2 sadc-header-darkblue" style="font-size:2.5rem;"></i>
        </span>
        SADC News
      </h1>
    </div>
    <div class="row g-4">
        @forelse($news as $article)
            <div class="col-md-4 col-12">
                <div class="card h-100 shadow-sm news-card" id="news-card-{{ $article->id }}">
                    @if($article->image)
                        <img src="{{ asset('storage/' . $article->image) }}" class="card-img-top" style="object-fit:cover;height:200px;" alt="News image">
                    @endif
                    <div class="card-body d-flex flex-column">
                        <span class="badge bg-secondary mb-2">{{ $article->category }}</span>
                        <h5 class="card-title">{{ $article->title }}</h5>
                        <p class="card-text">{{ $article->summary }}</p>
                        <a href="#" class="btn btn-outline-primary mt-auto">Read More</a>
@if(Auth::user() && Auth::user()->can('delete', $article))
    <button type="button" class="btn btn-outline-danger btn-sm mt-2 delete-news-btn" data-news-id="{{ $article->id }}" data-delete-url="{{ route('news.destroy', $article->id) }}"><i class="bi bi-trash"></i> Delete</button>
@endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="d-flex justify-content-center align-items-center" style="min-height:340px;">
                  <div class="card shadow-sm p-4" style="max-width:520px;width:100%;background:#f9fafb;border-radius:1rem;">
                    <div class="d-flex align-items-center mb-3">
                      <span class="me-3">
                        <i class="bi bi-newspaper me-2 sadc-header-darkblue" style="font-size:2.7rem;"></i>
                      </span>
                      <div>
                        <h3 class="fw-bold mb-1" style="color:#222;font-size:2rem;">No News Yet</h3>
                        <span class="badge bg-secondary" style="font-size:1rem;">Awaiting Updates</span>
                      </div>
                    </div>
                    <p class="mb-2" style="color:#444;">News articles uploaded from the <b>Manage News</b> link in your dashboard will appear here.</p>
                    <p class="mb-0" style="color:#888;font-size:1.05rem;">Stay tuned for the latest updates and stories from the SADC region. Admins can add news anytime!</p>
                  </div>
                </div>
            </div>
        @endforelse
    </div>
    <div class="mt-4">
        {{ $news->links() }}
    </div>
</div>

        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
    </div>
  </div>
</div>
<div id="news-delete-alerts"></div>
<script>
let deleteNewsId = null;
let deleteNewsUrl = null;
Array.from(document.querySelectorAll('.delete-news-btn')).forEach(function(btn) {
    btn.addEventListener('click', function() {
        deleteNewsId = btn.getAttribute('data-news-id');
        deleteNewsUrl = btn.getAttribute('data-delete-url');
        var modal = new bootstrap.Modal(document.getElementById('deleteNewsModal'));
        modal.show();
    });
});
const confirmBtn = document.getElementById('confirmDeleteNews');
if (confirmBtn) {
    confirmBtn.addEventListener('click', function() {
        if (!deleteNewsId || !deleteNewsUrl) return;
        fetch(deleteNewsUrl, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
        }).then(function(response) {
            if (response.ok) {
                const card = document.getElementById('news-card-' + deleteNewsId);
                if (card) card.remove();
                showNewsAlert('News article deleted successfully.', 'success');
            } else {
                response.json().then(function(data) {
                    showNewsAlert(data.message || 'Failed to delete news article.', 'danger');
                }).catch(function() {
                    showNewsAlert('Failed to delete news article.', 'danger');
                });
            }
        }).catch(function() {
            showNewsAlert('Failed to delete news article.', 'danger');
        });
        var modalEl = document.getElementById('deleteNewsModal');
        var modal = bootstrap.Modal.getInstance(modalEl);
        modal.hide();
    });
}
function showNewsAlert(msg, type) {
    const alerts = document.getElementById('news-delete-alerts');
    if (!alerts) return;
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show mt-3`;
    alert.innerHTML = `<i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>${msg}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;
    alerts.appendChild(alert);
    setTimeout(() => { alert.remove(); }, 4000);
}
</script>
@endsection
