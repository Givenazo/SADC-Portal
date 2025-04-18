@php $isOdd = false; @endphp
@if(count($users))
    @foreach($users as $user)
        @php $isOdd = !$isOdd; @endphp
        <tr class="transition hover:bg-blue-50" style="border-bottom:1px solid #e5e7eb;{{ $isOdd ? 'background:#f9fafb;' : '' }}">
            <td class="px-6 py-5 text-left align-middle">
                <div class="w-10 h-10 bg-[#003366] text-white flex items-center justify-center rounded-full font-bold text-lg uppercase">
                    {{ strtoupper(substr($user->name,0,1)) }}
                </div>
                <span class="font-semibold">{{ $user->name }}</span>
            </td>
            <td class="px-6 py-5 text-left align-middle" style="word-break:break-all;font-size:0.95rem;">{{ $user->email }}</td>
            <td class="px-6 py-5 text-left align-middle">{{ $user->country->name ?? '-' }}</td>
            <td class="px-6 py-5 text-left align-middle">{{ $user->role->name ?? '-' }}</td>
            <td class="px-6 py-4">
    <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
        <a href="#" class="view-user-btn" 
            data-name="{{ $user->name }}"
            data-email="{{ $user->email }}"
            data-country="{{ $user->country->name ?? '-' }}"
            data-role="{{ $user->role->name ?? '-' }}"
            data-uploaded="{{ $user->videos_uploaded_count ?? 0 }}"
            data-downloaded="{{ $user->videos_downloaded_count ?? 0 }}"
            style="background:#14b8a6;color:#fff;font-weight:bold;border-radius:0.375rem;padding:0.25rem 0.6rem;margin-right:0.2rem;transition:background 0.2s;white-space:nowrap;display:inline-flex;align-items:center;" onmouseover="this.style.background='#0d9488'" onmouseout="this.style.background='#14b8a6'">
            <i class="bi bi-eye" style="margin-right:0.5rem;"></i>View
        </a>
        <a href="#" class="edit-user-btn" 
            data-user-id="{{ $user->id }}"
            data-name="{{ $user->name }}"
            data-email="{{ $user->email }}"
            data-country="{{ $user->country->name ?? '' }}"
            data-role="{{ $user->role->name ?? '' }}"
            style="background:#a21caf;color:#fff;font-weight:bold;border-radius:0.375rem;padding:0.25rem 0.6rem;transition:background 0.2s;white-space:nowrap;display:inline-flex;align-items:center;" onmouseover="this.style.background='#701a75'" onmouseout="this.style.background='#a21caf'">
            <i class="bi bi-pencil-square" style="margin-right:0.5rem;"></i>Edit
        </a>
        <a href="#" class="password-user-btn" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}" style="background:#f59e42;color:#fff;font-weight:bold;border-radius:0.375rem;padding:0.25rem 0.6rem;transition:background 0.2s;white-space:nowrap;display:inline-flex;align-items:center;" onmouseover="this.style.background='#d97706'" onmouseout="this.style.background='#f59e42'">
            <i class="bi bi-key" style="margin-right:0.5rem;"></i>Password</a>
        <a href="#" class="suspend-user-btn" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}" data-suspended="{{ $user->suspended ? '1' : '0' }}" style="background:{{ $user->suspended ? '#22c55e' : '#facc15' }};color:#fff;font-weight:bold;border-radius:0.375rem;padding:0.25rem 0.6rem;transition:background 0.2s;white-space:nowrap;display:inline-flex;align-items:center;" onmouseover="this.style.background='{{ $user->suspended ? '#16a34a' : '#eab308' }}'" onmouseout="this.style.background='{{ $user->suspended ? '#22c55e' : '#facc15' }}'">
            <i class="bi {{ $user->suspended ? 'bi-play-circle' : 'bi-pause-circle' }}" style="margin-right:0.5rem;"></i>{{ $user->suspended ? 'Activate' : 'Suspend' }}</a>
        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" style="margin:0;">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-block text-white bg-red-600 hover:bg-red-700 rounded px-4 py-2 font-bold transition" style="white-space:nowrap;display:inline-flex;align-items:center;" onclick="return confirm('Are you sure?')"><i class="bi bi-trash" style="margin-right:0.5rem;"></i>Delete</button>
        </form>
    </div>
</td>
        </tr>
    @endforeach
@else
    <tr><td colspan="9" class="text-center py-6">No users found.</td></tr>
@endif
