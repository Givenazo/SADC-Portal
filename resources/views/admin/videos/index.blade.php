@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="/css/sadc-custom.css">
<div class="container mx-auto py-10 px-6">
    <div class="mb-8">
    <div class="text-center mb-6">
    <div class="bg-light py-3 px-2 mb-2 text-center" style="border-radius:0.5rem;">
      <h1 class="fw-bold d-flex flex-column align-items-center justify-content-center sadc-header-darkblue" style="font-size:3rem;margin-bottom:0;font-weight:bold;text-align:center;">
        <span><i class="bi bi-collection-play-fill me-2 sadc-header-darkblue" style="font-size:2.5rem;"></i></span>
        Uploaded Videos
      </h1>
      <span class="text-gray-600 text-lg" style="font-size:1.15rem;display:block;margin-top:0;text-align:center;margin-left:1cm;">Manage all uploaded videos of the SADC News Portal.</span>
    </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6">
    <div class="mb-4" style="display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap;">
    <div style="display:flex; gap:0.5rem; align-items:center; flex:1 1 400px; min-width:280px;">
        <input type="text" id="video-search" class="form-control" style="max-width:320px; display:inline-block;" placeholder="Search for Videos...">
        <button id="video-search-clear" type="button" class="btn btn-outline-secondary" style="height:38px; display:none;">Clear</button>
        <button id="video-search-refresh" type="button" class="btn btn-outline-primary" style="height:38px; display:inline-flex; align-items:center; gap:0.3rem; border:2px solid #1677fa; border-radius:2rem; padding:0 1rem;">
            <span class="d-none d-sm-inline">Refresh</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 1 0-.908-.418A6 6 0 1 0 8 2v1z"/>
              <path d="M8 1.5a.5.5 0 0 1 .5.5v3.707l2.146-2.147a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 0 1 .708-.708L7.5 5.707V2a.5.5 0 0 1 .5-.5z"/>
            </svg>
        </button>
    </div>
    <div style="display:flex; align-items:center; gap:0.5rem; flex:0 0 auto;">
        <input type="checkbox" id="show-today-only">
        <label for="show-today-only" style="margin-bottom:0; font-size:0.98rem; cursor:pointer;">Only show today's uploads</label>
    </div>
</div>
        <div>
            <table class="min-w-full divide-y divide-gray-200" style="border-collapse: separate; border-spacing: 0; font-size:0.95rem;">


                <thead class="bg-[#f8fafc]">
                    <tr>
                        <th class="px-2 py-4 text-left text-xs text-gray-500 uppercase tracking-wider" style="width:3.5em; min-width:3.5em; max-width:3.5em; text-align:center; font-weight:700;">#</th>
                        <th class="px-4 py-4 text-left text-xs text-gray-500 uppercase tracking-wider" style="font-weight:700; width:70px; min-width:70px; max-width:70px;">Preview</th>
                        <th class="px-6 py-4 text-left text-xs text-gray-500 uppercase tracking-wider" style="font-weight:700;">Title</th>
                        <th class="px-6 py-4 text-left text-xs text-gray-500 uppercase tracking-wider" style="font-weight:700;">Description</th>
                        <th class="px-6 py-4 text-left text-xs text-gray-500 uppercase tracking-wider" style="font-weight:700;">Uploader</th>
                        <th class="px-6 py-4 text-left text-xs text-gray-500 uppercase tracking-wider" style="font-weight:700;">Country</th>
                        <th class="px-6 py-4 text-left text-xs text-gray-500 uppercase tracking-wider" style="font-weight:700;">Status</th>
                        <th class="px-6 py-4 text-left text-xs text-gray-500 uppercase tracking-wider" style="font-weight:700;">Upload Date</th>
                        <th class="px-6 py-4 text-left text-xs text-gray-500 uppercase tracking-wider" style="font-weight:700;">Expire Date</th>
                        <th class="px-6 py-4 text-left text-xs text-gray-500 uppercase tracking-wider" style="font-weight:700;">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @php $isOdd = false; @endphp
                    @forelse($videos as $video)
                        @php $isOdd = !$isOdd; @endphp
                        <tr id="video-row-{{ $video->id }}" class="transition hover:bg-blue-50" style="border-bottom:1px solid #e5e7eb;{{ $isOdd ? 'background:#f9fafb;' : '' }}">
                            <td class="px-2 py-5 text-center align-middle" style="width:3.5em; min-width:3.5em; max-width:3.5em;">{{ $loop->iteration + ($videos->currentPage() - 1) * $videos->perPage() }}</td>
                            <td class="px-4 py-5 text-left align-middle" style="width:70px; min-width:70px; max-width:70px;">
                                @if(!empty($video->preview_thumbnail))
    <img src="{{ asset('storage/' . $video->preview_thumbnail) }}" alt="{{ $video->title }}" style="width:56px; height:36px; object-fit:cover; border-radius:0.4rem; background:#f3f4f6;" />
@else
    <div style="width:56px; height:36px; background:#e5e7eb; border-radius:0.4rem; display:flex; align-items:center; justify-content:center; color:#888; font-size:1.1rem;">—</div>
@endif
                            </td>
                            <td class="px-6 py-5 text-left align-middle">
    @php $truncated = Str::limit($video->title, 15); @endphp
    <span class="full-title-btn" data-title="{{ e($video->title) }}" title="View full title" style="color:#222; background:none; cursor:default; padding:0; border-radius:0; font-weight:400; box-shadow:none;">{{ $truncated }}</span>
</td>
                            <td class="px-6 py-5 text-left align-middle">
    <button type="button" class="desc-btn bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-1 rounded shadow-sm border border-blue-100" data-description="{{ $video->description ? e($video->description) : 'No description available' }}">Description</button>
</td>
                            <td class="px-6 py-5 text-left align-middle">{{ $video->uploader->name ?? 'Unknown' }}</td>
                            <td class="px-6 py-5 text-left align-middle">{{ $video->country->name ?? 'N/A' }}</td>
                            <td class="px-6 py-5 text-left align-middle">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $video->status == 'Published' ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-600' }}">
                                    {{ $video->status }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-left align-middle">{{ $video->upload_date ? \Carbon\Carbon::parse($video->upload_date)->format('Y-m-d') : '' }}</td>
                            <td class="px-6 py-5 text-left align-middle">{{ $video->upload_date ? \Carbon\Carbon::parse($video->upload_date)->addDays(30)->format('Y-m-d') : '' }}</td>
                            <td class="px-6 py-5 text-left align-middle">
    <style>
        .dropdown-action-wrapper { position: relative; display: inline-block; vertical-align: top; }
        .dropdown-action-btn {
            font-weight: bold;
            border-radius: 0.75rem 0.75rem 0 0;
            padding: 0.5rem 1.5rem;
            background: #1677fa;
            color: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            outline: none;
            border: none;
            font-size: 1rem;
            cursor: pointer;
            min-width: 140px;
        }
        .dropdown-action-wrapper:hover .dropdown-menu-custom,
        .dropdown-action-wrapper:focus-within .dropdown-menu-custom {
            display: block !important;
        }
        .dropdown-menu-custom {
            display: none;
            position: absolute;
            left: 0;
            top: 100%;
            margin-top: 0px;
            width: 100%;
            background: #fff;
            border-radius: 0 0 1rem 1rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.13);
            z-index: 9999;
            border: 1px solid #e5e7eb;
            border-top: none;
            overflow: visible;
        }
        .dropdown-menu-custom ul {
            padding: 0.5rem 0;
            margin: 0;
            list-style: none;
        }
        .dropdown-menu-custom li a {
            display: block;
            padding: 0.5rem 1.5rem;
            color: #222;
            text-decoration: none;
            font-size: 1.08rem;
            transition: background 0.2s;
        }
        .dropdown-menu-custom li a:hover {
            background: #f3f4f6;
        }
        .bg-white.rounded-xl.shadow-lg.p-6 {
            overflow: visible !important;
        }
        .table-responsive, .min-w-full, .divide-y, .divide-gray-200, .p-6, .shadow-lg, .rounded-xl {
            overflow: visible !important;
        }
    </style>
    <style>
    .action-icons-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-auto-rows: 1fr;
    gap: 0.3em 0.3em;
    justify-items: center;
    align-items: center;
    max-width: 10em;
    margin: 0 auto;
}
.action-icons-row .action-icon-btn {
    width: 2.6em;
    height: 2.6em;
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
    margin: 0.12em;
    padding: 0;
}
    .action-icon-btn {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.28rem;
    border-radius: 0.5rem;
    transition: background 0.18s;
    font-size: 1.1rem;
    cursor: pointer;
    box-sizing: border-box;
    min-width: 1.7rem;
    min-height: 1.7rem;
}
    .action-icon-btn .action-tooltip {
        visibility: hidden;
        opacity: 0;
        width: max-content;
        background: #222;
        color: #fff;
        text-align: center;
        border-radius: 0.4rem;
        padding: 0.22rem 0.7rem;
        position: absolute;
        z-index: 99999;
        bottom: 105%;
        left: 50%;
        transform: translateX(-50%) translateY(0.2rem);
        font-size: 0.95rem;
        pointer-events: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.16);
        transition: opacity 0.2s, transform 0.2s;
        white-space: nowrap;
    }
    .action-icon-btn:hover .action-tooltip, .action-icon-btn:focus .action-tooltip {
        visibility: visible;
        opacity: 1;
        transform: translateX(-50%) translateY(-0.25rem);
    }
</style>
<div class="action-icons-row" style="">

    <a href="#" class="action-icon-btn text-blue-600 hover:bg-blue-50 video-info-btn" tabindex="0"
    data-title="{{ e($video->title) }}"
    data-description="{{ e($video->description) }}"
    data-thumbnail="{{ $video->preview_thumbnail ? asset('storage/' . $video->preview_thumbnail) : '' }}"
    data-upload-date="{{ $video->upload_date ? \Carbon\Carbon::parse($video->upload_date)->format('Y-m-d H:i') : '' }}"
    data-expire-date="{{ $video->upload_date ? \Carbon\Carbon::parse($video->upload_date)->addDays(30)->format('Y-m-d H:i') : '' }}"
    data-format="{{ pathinfo($video->video_path, PATHINFO_EXTENSION) }}"
    data-size="{{ isset($video->video_path) && file_exists(storage_path('app/public/' . $video->video_path)) ? number_format(filesize(storage_path('app/public/' . $video->video_path)) / 1048576, 2) . ' MB' : '' }}"
    data-video-url="{{ $video->video_path ? asset('storage/' . $video->video_path) : '' }}"
    >
    <i class="bi bi-info-circle"></i><span class="action-tooltip">Video Info</span>
</a>
    <a href="#" class="action-icon-btn text-green-600 hover:bg-green-50 play-video-btn" tabindex="0"
        data-video-url="{{ $video->video_path ? asset('storage/' . $video->video_path) : '' }}">
        <i class="bi bi-play-circle"></i><span class="action-tooltip">Play Video</span>
    </a>
    <a href="#" class="action-icon-btn text-indigo-600 hover:bg-indigo-50 download-video-btn" tabindex="0" data-video-url="{{ $video->video_path ? asset('storage/' . $video->video_path) : '' }}"><i class="bi bi-download"></i><span class="action-tooltip">Download Video</span></a>
    <a href="#" class="action-icon-btn text-gray-700 hover:bg-gray-100 download-script-btn" tabindex="0" data-script-url="{{ $video->script_path ? asset('storage/' . $video->script_path) : '' }}"><i class="bi bi-file-earmark-text"></i><span class="action-tooltip">Download Script</span></a>
    <a href="#" class="action-icon-btn text-purple-600 hover:bg-purple-50 download-voiceover-btn" tabindex="0" data-voiceover-url="{{ $video->voiceover_path ? asset('storage/' . $video->voiceover_path) : '' }}"><i class="bi bi-mic"></i><span class="action-tooltip">Download Voice Over</span><span class="voiceover-tooltip" style="display:none; position:absolute; left:110%; top:50%; transform:translateY(-50%); background:#fff; color:#b91c1c; border:1px solid #fca5a5; border-radius:0.35rem; font-size:0.96rem; padding:0.35rem 0.7rem; white-space:nowrap; z-index:10; box-shadow:0 2px 8px rgba(0,0,0,0.08);">No voice over uploaded</span></a>
    <a href="#" class="action-icon-btn text-red-600 hover:bg-red-50 delete-video-btn" tabindex="0" data-video-id="{{ $video->id }}"><i class="bi bi-trash"></i><span class="action-tooltip">Delete Video</span></a>
</div>
</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-400" style="font-size:1.08rem;">No videos found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
    <script>
    // Enhance dropdown for mobile/touch: tap to open, tap outside to close
    document.addEventListener('DOMContentLoaded', function() {
        var dropdowns = document.querySelectorAll('.dropdown-action-wrapper');
        dropdowns.forEach(function(wrapper) {
            var btn = wrapper.querySelector('.dropdown-action-btn');
            var menu = wrapper.querySelector('.dropdown-menu-custom');
            // Toggle on click (touch/tap support)
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                // Hide all other dropdowns
                document.querySelectorAll('.dropdown-menu-custom').forEach(function(m) {
                    if(m !== menu) m.style.display = 'none';
                });
                menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
            });
            // Prevent closing when clicking inside menu
            menu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
        // Hide dropdowns when clicking outside
        document.addEventListener('click', function() {
            document.querySelectorAll('.dropdown-menu-custom').forEach(function(menu) {
                menu.style.display = 'none';
            });
        });
    });
    </script>
    <!-- Pagination Bar (outside white card, in grey area) -->
    <div class="d-flex align-items-center justify-content-center" style="background:#f8fafc; border-radius:0.7rem; margin:18px auto 0 auto; max-width:98vw; width:100%; padding:0.3rem 0.7rem; font-size:0.93rem; box-shadow:0 1px 3px rgba(0,0,0,0.02);">
        <div class="d-flex align-items-center flex-wrap justify-content-between w-100" style="gap:1.5rem; max-width:1100px;">
            <form method="GET" style="display:inline-block;">
                <select name="per_page" class="form-select form-select-sm" style="width:54px; display:inline-block; font-size:0.93rem; padding:0.1rem 0.6rem; height:28px;" onchange="this.form.submit()">
                    @foreach([10,25,50,100] as $size)
                        <option value="{{ $size }}" {{ request('per_page', $videos->perPage()) == $size ? 'selected' : '' }}>{{ $size }}</option>
                    @endforeach
                </select>
            </form>
            <span class="ms-2 text-gray-600" style="font-size:0.93rem;">Showing {{ $videos->firstItem() }} to {{ $videos->lastItem() }} of {{ $videos->total() }} records</span>
            <div style="font-size:0.93rem;">
                {{ $videos->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
    <!-- End Pagination Bar -->

    <!-- Delete Video Modal -->
    <div id="delete-video-modal" style="display:none; position:fixed; z-index:100003; left:0; top:0; width:100vw; height:100vh; background:rgba(30,41,59,0.22); align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:1rem; max-width:370px; width:92vw; margin:auto; box-shadow:0 8px 32px rgba(0,0,0,0.19); padding:2.1rem 1.2rem 1.7rem 1.2rem; position:relative; text-align:center;">
            <button id="delete-video-modal-close" style="position:absolute; top:0.7rem; right:1.1rem; background:none; border:none; font-size:1.5rem; color:#888; cursor:pointer;">&times;</button>
            <h4 style="font-weight:bold; font-size:1.11rem; margin-bottom:1rem; color:#b91c1c;">Delete Video</h4>
            <div style="font-size:1.01rem; color:#222; margin-bottom:1.2rem;">Are you sure you want to delete this video? This action cannot be undone.</div>
            <form id="delete-video-form" method="POST" action="">
                @csrf
                @method('DELETE')
                <input type="hidden" name="video_id" id="delete-video-id" value="">
                <div style="display:flex; justify-content:center; gap:1.2rem;">
                    <button type="button" id="delete-video-cancel" style="padding:0.5rem 1.2rem; background:#f3f4f6; color:#222; border:none; border-radius:0.4rem; font-weight:500; cursor:pointer;">Cancel</button>
                    <button type="submit" id="delete-video-confirm-btn" style="padding:0.5rem 1.2rem; background:#b91c1c; color:#fff; border:none; border-radius:0.4rem; font-weight:500; cursor:pointer;">Delete</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Video Play Modal -->
    <div id="video-play-modal" style="display:none; position:fixed; z-index:100002; left:0; top:0; width:100vw; height:100vh; background:rgba(30,41,59,0.22); align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:1rem; max-width:640px; width:98vw; margin:auto; box-shadow:0 8px 32px rgba(0,0,0,0.19); padding:2rem 1.2rem 1.4rem 1.2rem; position:relative; text-align:center;">
            <button id="video-play-modal-close" style="position:absolute; top:0.7rem; right:1.1rem; background:none; border:none; font-size:1.5rem; color:#888; cursor:pointer;">&times;</button>
            <h4 style="font-weight:bold; font-size:1.2rem; margin-bottom:1rem; color:#1677fa;">Play Video</h4>
            <video id="video-play-modal-video" controls style="width:100%; max-height:360px; border-radius:0.7rem; background:#000;">
                <source src="" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    </div>

    <!-- Video Info Modal -->
    <script>
        // Video Play Modal Logic
        document.querySelectorAll('.play-video-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                var modal = document.getElementById('video-play-modal');
                var video = document.getElementById('video-play-modal-video');
                var src = btn.getAttribute('data-video-url');
                if(src) {
                    video.querySelector('source').src = src;
                    video.load();
                    modal.style.display = 'flex';
                }
            });
        });
        document.getElementById('video-play-modal-close').onclick = function() {
            var modal = document.getElementById('video-play-modal');
            var video = document.getElementById('video-play-modal-video');
            video.pause();
            video.currentTime = 0;
            video.querySelector('source').src = '';
            video.load();
            modal.style.display = 'none';
        };
        document.getElementById('video-play-modal').onclick = function(e) {
            if(e.target === this) {
                var modal = document.getElementById('video-play-modal');
                var video = document.getElementById('video-play-modal-video');
                video.pause();
                video.currentTime = 0;
                video.querySelector('source').src = '';
                video.load();
                modal.style.display = 'none';
            }
        };
    </script>
    <script>
        // Download Video Action (auto-download)
        document.querySelectorAll('.download-video-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                var url = btn.getAttribute('data-video-url');
                if(url) {
                    var a = document.createElement('a');
                    a.href = url;
                    a.download = '';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                }
            });
        });
    </script>
    <script>
        // Download Voice Over Action (auto-download or tooltip)
        document.querySelectorAll('.download-voiceover-btn').forEach(function(btn) {
            var tooltip = btn.querySelector('.voiceover-tooltip');
            btn.addEventListener('click', function(e) {
                var url = btn.getAttribute('data-voiceover-url');
                if(url) {
                    e.preventDefault();
                    var a = document.createElement('a');
                    a.href = url;
                    a.download = '';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                } else {
                    // No voiceover, show tooltip and prevent click
                    e.preventDefault();
                    if(tooltip) {
                        tooltip.style.display = 'block';
                    }
                }
            });
            btn.addEventListener('mouseenter', function() {
                var url = btn.getAttribute('data-voiceover-url');
                if(!url && tooltip) {
                    tooltip.style.display = 'block';
                }
            });
            btn.addEventListener('mouseleave', function() {
                if(tooltip) tooltip.style.display = 'none';
            });
        });
    </script>
    <script>
        // Delete Video Modal Logic
        document.querySelectorAll('.delete-video-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                var videoId = btn.getAttribute('data-video-id');
                var modal = document.getElementById('delete-video-modal');
                var form = document.getElementById('delete-video-form');
                var idField = document.getElementById('delete-video-id');
                idField.value = videoId;
                // Set form action to the destroy route
                form.action = '/admin/videos/' + videoId;
                modal.style.display = 'flex';
            });
        });
        document.getElementById('delete-video-modal-close').onclick = function() {
            document.getElementById('delete-video-modal').style.display = 'none';
        };
        document.getElementById('delete-video-cancel').onclick = function() {
            document.getElementById('delete-video-modal').style.display = 'none';
        };
        document.getElementById('delete-video-modal').onclick = function(e) {
            if(e.target === this) this.style.display = 'none';
        };
    </script>
    <script>
    // Highlight text nodes only, preserving HTML structure
    function highlightText(element, query) {
        if (!query) return;
        var walk = document.createTreeWalker(element, NodeFilter.SHOW_TEXT, null, false);
        var nodes = [];
        while (walk.nextNode()) {
            nodes.push(walk.currentNode);
        }
        nodes.forEach(function(textNode) {
            var val = textNode.nodeValue;
            var regex = new RegExp('('+query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')+')', 'gi');
            if (regex.test(val)) {
                var span = document.createElement('span');
                span.innerHTML = val.replace(regex, '<span class="highlight" style="background:yellow;color:#222;">$1</span>');
                textNode.parentNode.replaceChild(span, textNode);
            }
        });
    }
    // Video Table Live Search (fixed: only filter and highlight visible text, not HTML/attributes)
    document.addEventListener('DOMContentLoaded', function() {
        var searchInput = document.getElementById('video-search');
        var rows = Array.from(document.querySelectorAll('tr[id^="video-row-"]'));
        // Columns: 2=Title, 3=Description, 4=Uploader
        // Refresh button logic
        document.getElementById('video-search-refresh').addEventListener('click', function() {
            window.location.reload();
        });
        // Clear button logic
        document.getElementById('video-search-clear').addEventListener('click', function() {
            searchInput.value = '';
            rows.forEach(function(row) {
                [2,3,4].forEach(function(idx) {
                    var cell = row.cells[idx];
                    if(cell) {
                        if(idx === 3) { // Description column
                            var btn = cell.querySelector('.desc-btn');
                            if(btn && btn.dataset.originalLabel) {
                                btn.innerHTML = btn.dataset.originalLabel;
                            }
                        } else {
                            // Remove highlights and restore original HTML if needed
                            cell.innerHTML = cell.textContent;
                        }
                    }
                });
                row.style.display = '';
            });
            // Restore all action icons and tooltips
            document.querySelectorAll('.action-icons-row').forEach(function(row){
                row.querySelectorAll('[data-original-label]').forEach(function(btn){
                    btn.innerHTML = btn.dataset.originalLabel;
                });
            });
            searchInput.focus();
        });
        searchInput.addEventListener('input', function() {
            var query = this.value.trim().toLowerCase();
            rows.forEach(function(row) {
                // Remove previous highlights only in relevant cells
                [2,3,4].forEach(function(idx) {
                    var cell = row.cells[idx];
                    if(cell) {
                        cell.innerHTML = cell.textContent; // Remove highlight safely
                    }
                });
                if (!query) {
                    row.style.display = '';
                    return;
                }
                // Gather searchable text from only title, description, uploader
                var title = row.cells[2]?.textContent.toLowerCase() || '';
                var desc = row.cells[3]?.textContent.toLowerCase() || '';
                var uploader = row.cells[4]?.textContent.toLowerCase() || '';
                if (title.includes(query) || desc.includes(query) || uploader.includes(query)) {
                    row.style.display = '';
                    // Highlight matches only in those cells
                    [2,3,4].forEach(function(idx) {
                        var cell = row.cells[idx];
                        if(cell) {
                            if(idx === 3) { // Description column
                                // Only highlight the button label, not the button HTML
                                var btn = cell.querySelector('.desc-btn');
                                if(btn) {
                                    // Store original label if not already stored
                                    if(!btn.dataset.originalLabel){
                                        btn.dataset.originalLabel = btn.innerHTML;
                                    }
                                    // Remove previous highlight by restoring original label
                                    btn.innerHTML = btn.dataset.originalLabel;
                                    var label = btn.textContent;
                                    var regex = new RegExp('('+query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')+')', 'gi');
                                    // Only highlight the visible label, not the HTML
                                    if(label.toLowerCase().includes(query)){
                                        btn.innerHTML = label.replace(regex, '<span class="highlight" style="background:yellow;color:#222;">$1</span>');
                                    }
                                }
                            } else {
                                // Highlight only in text nodes, preserving HTML (e.g., buttons)
                                highlightText(cell, query);
                            }
                        }
                    });
                } else {
                    row.style.display = 'none';
                }
            });
        });
        // Delete Video Modal Logic
        document.querySelectorAll('.delete-video-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                var videoId = btn.getAttribute('data-video-id');
                var modal = document.getElementById('delete-video-modal');
                var form = document.getElementById('delete-video-form');
                var idField = document.getElementById('delete-video-id');
                idField.value = videoId;
                // Set form action to the destroy route
                form.action = '/admin/videos/' + videoId;
                modal.style.display = 'flex';
            });
        });
        document.getElementById('delete-video-modal-close').onclick = function() {
            document.getElementById('delete-video-modal').style.display = 'none';
        };
        document.getElementById('delete-video-cancel').onclick = function() {
            document.getElementById('delete-video-modal').style.display = 'none';
        };
        document.getElementById('delete-video-modal').onclick = function(e) {
            if(e.target === this) this.style.display = 'none';
        };
        // AJAX Delete Video
        document.getElementById('delete-video-form').onsubmit = function(e) {
            e.preventDefault();
            var form = this;
            var videoId = document.getElementById('delete-video-id').value;
            var row = document.getElementById('video-row-' + videoId);
            var modal = document.getElementById('delete-video-modal');
            var action = form.action;
            fetch(action, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
            }).then(function(response) {
                if (response.ok) {
                    if (row) row.remove();
                    modal.style.display = 'none';
                } else {
                    response.json().then(function(data) {
                        alert(data.message || 'Failed to delete video.');
                    }).catch(function(err){
                        alert('Failed to delete video.');
                        console.error(err);
                    });
                }
            }).catch(function(err) {
                alert('Failed to delete video.');
                console.error(err);
            });
        };
    });
    </script>
    <script>
        // Download Script/Transcript Action (auto-download)
        document.querySelectorAll('.download-script-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                var url = btn.getAttribute('data-script-url');
                if(url) {
                    var a = document.createElement('a');
                    a.href = url;
                    a.download = '';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                }
            });
        });
    </script>

    <div id="video-info-modal" style="display:none; position:fixed; z-index:100001; left:0; top:0; width:100vw; height:100vh; background:rgba(30,41,59,0.22); align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:1rem; max-width:480px; width:94vw; margin:auto; box-shadow:0 8px 32px rgba(0,0,0,0.19); padding:2.2rem 1.5rem 1.7rem 1.5rem; position:relative; text-align:left;">
            <button id="video-info-modal-close" style="position:absolute; top:0.7rem; right:1.1rem; background:none; border:none; font-size:1.5rem; color:#888; cursor:pointer;">&times;</button>
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.1rem;">
                <h4 style="font-weight:bold; font-size:1.2rem; color:#1677fa; margin:0;">Video Information</h4>
            </div>
            <div id="video-info-modal-content">
                <div style="display:flex; gap:1rem; align-items:flex-start; margin-bottom:1.1rem;">
                    <img id="video-info-thumbnail" src="https://placehold.co/120x80?text=No+Image" alt="Thumbnail" style="width:120px; height:80px; object-fit:cover; border-radius:0.5rem; background:#f3f4f6;" />
                    <div style="flex:1;">
                        <div style="font-weight:bold; font-size:1.09rem; margin-bottom:0.2rem; color:#222;" id="video-info-title"></div>
                        <div style="font-size:0.97rem; color:#666; margin-bottom:0.4rem;" id="video-info-format"></div>
                        <div style="font-size:0.97rem; color:#666;" id="video-info-size"></div>
                    </div>
                </div>
                <div style="margin-bottom:0.8rem;">
                    <strong>Description:</strong>
                    <div id="video-info-description" style="font-size:1.04rem; color:#222; white-space:pre-line; max-height:300px; overflow-y:auto;"></div>
                </div>
                <div style="margin-bottom:0.7rem;">
                    <strong>Upload Date:</strong> <span id="video-info-upload-date"></span><br/>
                    <strong>Expire Date:</strong> <span id="video-info-expire-date"></span>
                </div>
            </div>
            <div style="margin-top:1.6rem; text-align:center;">
                <a id="video-info-download" href="#" download title="Download Video" style="font-size:1.3rem; color:#1677fa; background:none; border:none; cursor:pointer; display:inline-flex; align-items:center; padding:0.5rem 1.1rem; border-radius:0.4rem; transition:background 0.15s; text-decoration:none; font-weight:600; gap:0.5rem;">
                    <i class="bi bi-download"></i> <span style="font-size:1rem;">Download Video</span>
                </a>
            </div>
        </div>
    </div>
    <script>
        // Video Info Modal Logic
        document.querySelectorAll('.video-info-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                var modal = document.getElementById('video-info-modal');
                document.getElementById('video-info-title').textContent = btn.getAttribute('data-title') || '-';
                document.getElementById('video-info-description').textContent = btn.getAttribute('data-description') || '-';
                document.getElementById('video-info-upload-date').textContent = btn.getAttribute('data-upload-date') || '-';
                document.getElementById('video-info-expire-date').textContent = btn.getAttribute('data-expire-date') || '-';
                document.getElementById('video-info-format').textContent = btn.getAttribute('data-format') ? 'Format: ' + btn.getAttribute('data-format').toUpperCase() : '';
                document.getElementById('video-info-size').textContent = btn.getAttribute('data-size') ? 'Size: ' + btn.getAttribute('data-size') : '';
                var thumb = btn.getAttribute('data-thumbnail');
                document.getElementById('video-info-thumbnail').src = thumb ? thumb : 'https://placehold.co/120x80?text=No+Image';
                // Set download link
                var downloadBtn = document.getElementById('video-info-download');
                var videoUrl = btn.getAttribute('data-video-url');
                if(videoUrl) {
                    downloadBtn.href = videoUrl;
                    downloadBtn.style.display = '';
                } else {
                    downloadBtn.href = '#';
                    downloadBtn.style.display = 'none';
                }
                modal.style.display = 'flex';
            });
        });
        document.getElementById('video-info-modal-close').onclick = function() {
            document.getElementById('video-info-modal').style.display = 'none';
        };
        document.getElementById('video-info-modal').onclick = function(e) {
            if(e.target === this) this.style.display = 'none';
        };
    </script>


    <!-- Description Modal -->
    <div id="desc-modal" style="display:none; position:fixed; z-index:100000; left:0; top:0; width:100vw; height:100vh; background:rgba(30,41,59,0.22); align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:1rem; max-width:420px; width:90vw; margin:auto; box-shadow:0 8px 32px rgba(0,0,0,0.19); padding:2rem 1.2rem 1.4rem 1.2rem; position:relative; text-align:left;">
            <button id="desc-modal-close" style="position:absolute; top:0.7rem; right:1.1rem; background:none; border:none; font-size:1.5rem; color:#888; cursor:pointer;">&times;</button>
            <h4 style="font-weight:bold; font-size:1.2rem; margin-bottom:1rem; color:#1677fa;">Video Description</h4>
            <div id="desc-modal-content" style="font-size:1.05rem; color:#222; white-space:pre-line;"></div>
        </div>
    </div>
    <script>
    document.querySelectorAll('.desc-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var modal = document.getElementById('desc-modal');
            var content = document.getElementById('desc-modal-content');
            content.textContent = btn.getAttribute('data-description');
            modal.style.display = 'flex';
        });
    });
    document.getElementById('desc-modal-close').onclick = function() {
        document.getElementById('desc-modal').style.display = 'none';
    };
    document.getElementById('desc-modal').onclick = function(e) {
        if(e.target === this) this.style.display = 'none';
    };
    </script>

    <!-- Full Title Modal -->
    <div id="full-title-modal" style="display:none; position:fixed; z-index:100010; left:0; top:0; width:100vw; height:100vh; background:rgba(30,41,59,0.22); align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:1rem; max-width:420px; width:90vw; margin:auto; box-shadow:0 8px 32px rgba(0,0,0,0.19); padding:2rem 1.2rem 1.4rem 1.2rem; position:relative; text-align:left;">
            <button id="full-title-modal-close" style="position:absolute; top:0.7rem; right:1.1rem; background:none; border:none; font-size:1.5rem; color:#888; cursor:pointer;">&times;</button>
            <h4 style="font-weight:bold; font-size:1.2rem; margin-bottom:1rem; color:#1677fa;">Full Video Title</h4>
            <div id="full-title-modal-content" style="font-size:1.05rem; color:#222; white-space:pre-line;"></div>
        </div>
    </div>
    <script>
    document.querySelectorAll('.full-title-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var modal = document.getElementById('full-title-modal');
            var content = document.getElementById('full-title-modal-content');
            content.textContent = btn.getAttribute('data-title');
            modal.style.display = 'flex';
        });
    });
    document.getElementById('full-title-modal-close').onclick = function() {
        document.getElementById('full-title-modal').style.display = 'none';
    };
    document.getElementById('full-title-modal').onclick = function(e) {
        if(e.target === this) this.style.display = 'none';
    };
    </script>
    <script>
    const showTodayOnlyCheckbox = document.getElementById('show-today-only');
    const getToday = () => new Date().toISOString().slice(0, 10);
    function updateTodayRowsAndNumbering() {
        const checked = showTodayOnlyCheckbox.checked;
        const today = getToday();
        let visibleIndex = 0;
        document.querySelectorAll('tbody tr').forEach(function(row) {
            const uploadDateCell = row.querySelector('td:nth-child(8)');
            const numCell = row.querySelector('td:nth-child(1)');
            if (!uploadDateCell || !numCell) return;
            const uploadDate = uploadDateCell.textContent.trim();
            if (checked) {
                if (uploadDate !== today) {
                    row.style.display = 'none';
                } else {
                    row.style.display = '';
                    visibleIndex++;
                    if (visibleIndex > 999) {
                        numCell.textContent = '999+';
                    } else {
                        numCell.textContent = visibleIndex;
                    }
                }
            } else {
                row.style.display = '';
                // Restore original number from data attribute if present
                if (numCell.dataset.originalNum) {
                    numCell.textContent = numCell.dataset.originalNum;
                }
            }
        });
    }
    // Store original numbers on page load
    document.querySelectorAll('tbody tr').forEach(function(row) {
        const numCell = row.querySelector('td:nth-child(1)');
        if (numCell) numCell.dataset.originalNum = numCell.textContent.trim();
    });
    showTodayOnlyCheckbox.addEventListener('change', updateTodayRowsAndNumbering);
    </script>
    </div>
@endsection
