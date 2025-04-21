@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="/css/sadc-custom.css">
<div class="container mx-auto py-10 px-6">
    <div class="mb-8">
    <div class="text-center mb-6">
    <div class="bg-light py-3 px-2 mb-2 text-center" style="border-radius:0.5rem;">
  <h1 class="fw-bold d-flex flex-column align-items-center justify-content-center sadc-header-darkblue" style="font-size:3rem;margin-bottom:0;font-weight:bold;text-align:center;">
    <span><i class="bi bi-people-fill me-2 sadc-header-darkblue" style="font-size:2.5rem;"></i></span>
    User Management
  </h1>
  <span class="text-gray-600 text-lg" style="font-size:1.15rem;display:block;margin-top:0;text-align:center;margin-left:1cm;">Manage all users of the SADC News Portal.</span>
</div>
<div class="d-flex align-items-center mb-4" style="display: flex; justify-content: space-between; gap: 1rem; width: 100%;">
  <div style="display: flex; align-items: center; gap: 0.5rem;">
    <form method="GET" action="" class="flex items-center" style="min-width:200px;max-width:420px;gap:0.4rem;">
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..." class="w-full px-3 py-2 border border-gray-300 rounded-l focus:outline-none focus:ring-2 focus:ring-blue-300 text-sm" />
      <button type="submit" class="px-3 py-2 bg-blue-600 text-white rounded-none hover:bg-blue-700 text-sm" style="border-top-right-radius:0.375rem; border-bottom-right-radius:0.375rem;"><i class="bi bi-search text-blue-100" style="color:#2563eb;"></i></button>
    </form>
    <button id="user-search-refresh" type="button" class="btn btn-outline-primary" style="height:38px; display:inline-flex; align-items:center; gap:0.3rem; border:2px solid #1677fa; border-radius:2rem; padding:0 1rem; margin-left:0.4rem;">
      <span class="d-none d-sm-inline">Refresh</span>
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 1 0-.908-.418A6 6 0 1 0 8 2v1z"/>
        <path d="M8 1.5a.5.5 0 0 1 .5.5v3.707l2.146-2.147a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 0 1 .708-.708L7.5 5.707V2a.5.5 0 0 1 .5-.5z"/>
      </svg>
    </button>
  </div>
  <a href="#" id="addUserBtn"
     class="inline-flex items-center px-3 py-2 text-xs font-semibold rounded shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-blue-400"
     style="height:36px; background:#2563eb; color:#fff; border-radius:0.375rem; box-shadow:0 2px 8px rgba(0,0,0,0.06); font-weight:600;">
      <i class="bi bi-person-plus-fill mr-2"></i> Add a User
  </a>
</div>

</div>
    <div class="bg-white rounded-xl shadow-lg p-6">
    <div style="overflow-x:auto; width:100%;">
        <table class="min-w-full divide-y divide-gray-200" style="border-collapse: separate; border-spacing: 0; font-size:0.93rem; min-width:700px; width:100%;">
            <thead class="bg-[#f8fafc]">
    <tr>
        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
        <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="min-width:80px; max-width:110px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">Email</th>
        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Country</th>
        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
        {{-- <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">View</th>
        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Edit</th>
        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Password</th>
        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Suspend</th>
        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Delete</th> --}}
    </tr>
</thead>
            </thead>
            <tbody id="usersTableBody" class="bg-white divide-y divide-gray-100">
@php $isOdd = false; @endphp
@if(count($users))
    @foreach($users as $user)
        @php $isOdd = !$isOdd; @endphp
        <tr class="transition hover:bg-blue-50" style="border-bottom:1px solid #e5e7eb;{{ $isOdd ? 'background:#f9fafb;' : '' }}">
            <td class="px-6 py-5 text-left align-middle">
    <span class="font-semibold">{{ $user->name }}</span>
</td>
            <td class="px-2 py-3 text-left align-middle" style="min-width:80px; max-width:110px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; font-size:0.93rem;">
    <button type="button" class="btn btn-outline-primary btn-sm show-email-btn" data-email="{{ $user->email }}" style="font-size:0.95rem; padding:0.2rem 0.7rem; white-space:nowrap; border:2px solid #dc2626; color:#dc2626; background:#fff;">Email</button>
</td>
            <td class="px-6 py-5 text-left align-middle">{{ $user->country->name ?? '-' }}</td>
            <td class="px-6 py-5 text-left align-middle">{{ $user->role->name ?? '-' }}</td>
            
<td class="px-6 py-4">
    
</td>
<td class="px-2 py-3">
    <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: nowrap; justify-content: flex-start; overflow-x: auto;">
        <a href="#" class="view-user-btn"
            data-name="{{ $user->name }}"
            data-email="{{ $user->email }}"
            data-country="{{ $user->country->name ?? '-' }}"
            data-role="{{ $user->role->name ?? '-' }}"
            data-uploaded="{{ $user->videos_uploaded_count ?? 0 }}"
            data-downloaded="{{ $user->videos_downloaded_count ?? 0 }}"
            style="background:#14b8a6;color:#fff;font-weight:bold;border-radius:0.375rem;padding:0.2rem 0.8rem;transition:background 0.2s;white-space:nowrap;display:inline-flex;align-items:center; font-size:1rem;"
            onmouseover="this.style.background='#0d9488'" onmouseout="this.style.background='#14b8a6'">
            <i class="bi bi-eye" style="margin-right:0.5rem;"></i>View
        </a>
        <a href="#" class="edit-user-btn"
            data-user-id="{{ $user->id }}"
            data-name="{{ $user->name }}"
            data-email="{{ $user->email }}"
            data-country-id="{{ $user->country_id ?? '' }}"
            data-role-id="{{ $user->role_id ?? '' }}"
            style="background:#a21caf;color:#fff;font-weight:bold;border-radius:0.375rem;padding:0.2rem 0.8rem;transition:background 0.2s;white-space:nowrap;display:inline-flex;align-items:center; font-size:1rem;"
            onmouseover="this.style.background='#701a75'" onmouseout="this.style.background='#a21caf'">
            <i class="bi bi-pencil-square" style="margin-right:0.5rem;"></i>Edit
        </a>
        <a href="#" class="password-user-btn" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}"
            style="background:#f59e42;color:#fff;font-weight:bold;border-radius:0.375rem;padding:0.2rem 0.8rem;transition:background 0.2s;white-space:nowrap;display:inline-flex;align-items:center; font-size:1rem;"
            onmouseover="this.style.background='#d97706'" onmouseout="this.style.background='#f59e42'">
            <i class="bi bi-key" style="margin-right:0.5rem;"></i>Password
        </a>
        <a href="#" class="suspend-user-btn" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}" data-suspended="{{ $user->suspended ? '1' : '0' }}"
            style="background:{{ $user->suspended ? '#22c55e' : '#facc15' }};color:#fff;font-weight:bold;border-radius:0.375rem;padding:0.2rem 0.8rem;transition:background 0.2s;white-space:nowrap;display:inline-flex;align-items:center; font-size:1rem;"
            onmouseover="this.style.background='{{ $user->suspended ? '#16a34a' : '#eab308' }}'" onmouseout="this.style.background='{{ $user->suspended ? '#22c55e' : '#facc15' }}'">
            <i class="bi {{ $user->suspended ? 'bi-play-circle' : 'bi-pause-circle' }}" style="margin-right:0.5rem;"></i>{{ $user->suspended ? 'Activate' : 'Suspend' }}
        </a>
        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" style="margin:0;">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-block text-white bg-red-600 hover:bg-red-700 rounded px-4 py-2 font-bold transition"
                style="white-space:nowrap;display:inline-flex;align-items:center; font-size:1rem; padding:0.2rem 0.8rem;" onclick="return confirm('Are you sure?')">
                <i class="bi bi-trash" style="margin-right:0.5rem;"></i>Delete
            </button>
        </form>
    </div>
</td>

<!-- Add User Modal -->
<div id="addUserModal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.45); align-items:center; justify-content:center;">
  <div style="background:#fff; border-radius:0.75rem; max-width:460px; width:90vw; margin:auto; padding:2rem; box-shadow:0 8px 32px rgba(0,0,0,0.18); position:relative;">
    <button onclick="document.getElementById('addUserModal').style.display='none'" style="position:absolute;top:1rem;right:1rem;font-size:1.5rem;color:#888;background:none;border:none;cursor:pointer;">&times;</button>
    <h2 style="font-size:1.4rem;font-weight:bold;color:#2563eb;margin-bottom:1rem;">Add New User</h2>
    <form id="addUserForm" method="POST" action="{{ route('admin.users.store') }}">
      @csrf
      <div style="margin-bottom:1rem;">
        <label for="addUserName" style="font-weight:600;">Name:</label>
        <input type="text" name="name" id="addUserName" class="form-control" style="width:100%;margin-top:0.5rem;padding:0.5rem;border:1px solid #ddd;border-radius:0.375rem;" required>
      </div>
      <div style="margin-bottom:1rem;">
        <label for="addUserEmail" style="font-weight:600;">Email:</label>
        <input type="email" name="email" id="addUserEmail" class="form-control" style="width:100%;margin-top:0.5rem;padding:0.5rem;border:1px solid #ddd;border-radius:0.375rem;" required>
      </div>
      <div style="margin-bottom:1rem;">
        <label for="addUserCountry" style="font-weight:600;">Country:</label>
        <select name="country_id" id="addUserCountry" class="form-control" style="width:100%;margin-top:0.5rem;padding:0.5rem;border:1px solid #ddd;border-radius:0.375rem;" required>
          <option value="">Select Country</option>
          @foreach(\App\Models\Country::all() as $country)
            <option value="{{ $country->id }}">{{ $country->name }}</option>
          @endforeach
        </select>
      </div>
      <div style="margin-bottom:1rem;">
        <label for="addUserRole" style="font-weight:600;">Role:</label>
        <select name="role_id" id="addUserRole" class="form-control" style="width:100%;margin-top:0.5rem;padding:0.5rem;border:1px solid #ddd;border-radius:0.375rem;" required>
          <option value="">Select Role</option>
          @foreach(\App\Models\Role::all() as $role)
            <option value="{{ $role->id }}">{{ $role->name }}</option>
          @endforeach
        </select>
      </div>
      <div style="margin-bottom:1rem;">
        <label for="addUserPassword" style="font-weight:600;">Password:</label>
        <input type="password" name="password" id="addUserPassword" class="form-control" style="width:100%;margin-top:0.5rem;padding:0.5rem;border:1px solid #ddd;border-radius:0.375rem;" required>
      </div>
      <div style="margin-bottom:1.5rem;">
        <label for="addUserPasswordConfirm" style="font-weight:600;">Confirm Password:</label>
        <input type="password" name="password_confirmation" id="addUserPasswordConfirm" class="form-control" style="width:100%;margin-top:0.5rem;padding:0.5rem;border:1px solid #ddd;border-radius:0.375rem;" required>
        <div id="addUserPasswordError" style="color:#e11d48;font-size:0.95rem;margin-top:0.5rem;display:none;">Passwords do not match.</div>
      </div>
      <div style="display:flex;justify-content:space-between;gap:1rem;">
        <button type="button" onclick="document.getElementById('addUserModal').style.display='none'" style="background:#e5e7eb;color:#374151;font-weight:bold;border-radius:0.375rem;padding:0.5rem 1.2rem;font-size:1rem;">Cancel</button>
        <button type="submit" id="addUserSubmitBtn" style="background:#2563eb;color:#fff;font-weight:bold;border-radius:0.375rem;padding:0.5rem 1.2rem;font-size:1rem;">Add User</button>
      </div>
    </form>
    
  </div>
</div>
<script>
document.getElementById('addUserBtn').onclick = function(e) {
  e.preventDefault();
  document.getElementById('addUserModal').style.display = 'flex';
};
const addUserForm = document.getElementById('addUserForm');
const addUserPassword = document.getElementById('addUserPassword');
const addUserPasswordConfirm = document.getElementById('addUserPasswordConfirm');
const addUserPasswordError = document.getElementById('addUserPasswordError');
const addUserSubmitBtn = document.getElementById('addUserSubmitBtn');
addUserForm.addEventListener('input', function() {
  if (addUserPassword.value !== addUserPasswordConfirm.value) {
    addUserPasswordError.style.display = 'block';
    addUserSubmitBtn.disabled = true;
  } else {
    addUserPasswordError.style.display = 'none';
    addUserSubmitBtn.disabled = false;
  }
});
addUserForm.onsubmit = function(e) {
  if (addUserPassword.value !== addUserPasswordConfirm.value) {
    addUserPasswordError.style.display = 'block';
    addUserSubmitBtn.disabled = true;
    e.preventDefault();
    return false;
  }
  addUserSubmitBtn.disabled = true;
};

</script>

<!-- Suspend/Activate User Modal -->
<div id="suspendUserModal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.45); align-items:center; justify-content:center;">
  <div style="background:#fff; border-radius:0.75rem; max-width:430px; width:90vw; margin:auto; padding:2rem; box-shadow:0 8px 32px rgba(0,0,0,0.18); position:relative;">
    <button onclick="document.getElementById('suspendUserModal').style.display='none'" style="position:absolute;top:1rem;right:1rem;font-size:1.5rem;color:#888;background:none;border:none;cursor:pointer;">&times;</button>
    <h2 id="suspendModalTitle" style="font-size:1.4rem;font-weight:bold;color:#facc15;margin-bottom:1rem;">Suspend User</h2>
    <form id="suspendUserForm" method="POST" action="{{ route('admin.users.suspend') }}">
      @csrf
      <input type="hidden" name="user_id" id="suspendUserId">
      <input type="hidden" name="action" id="suspendAction">
      <div style="margin-bottom:1.5rem;">
        <span id="suspendUserText">Are you sure you want to suspend this user? Once suspended, they cannot login, and the Forgot Password feature will be disabled for them. You can reactivate later.</span>
      </div>
      <button type="submit" id="suspendModalBtn" style="background:#facc15;color:#fff;font-weight:bold;border-radius:0.375rem;padding:0.5rem 1.2rem;font-size:1rem;">Suspend</button>
    </form>
  </div>
</div>
<script>
document.querySelectorAll('.suspend-user-btn').forEach(btn => {
  btn.addEventListener('click', function(e) {
    e.preventDefault();
    const suspended = this.dataset.suspended === '1';
    document.getElementById('suspendUserId').value = this.dataset.userId;
    document.getElementById('suspendAction').value = suspended ? 'activate' : 'suspend';
    document.getElementById('suspendModalTitle').innerText = suspended ? 'Activate User' : 'Suspend User';
    document.getElementById('suspendUserText').innerText = suspended ? 
      'Are you sure you want to activate this user? They will be able to log in again.' :
      'Are you sure you want to suspend this user? Once suspended, they cannot login, and the Forgot Password feature will be disabled for them. You can reactivate later.';
    document.getElementById('suspendModalBtn').innerText = suspended ? 'Activate' : 'Suspend';
    document.getElementById('suspendModalBtn').style.background = suspended ? '#22c55e' : '#facc15';
    document.getElementById('suspendUserModal').style.display = 'flex';
  });
});
</script>

<!-- Edit User Modal -->
<div id="editUserModal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.45); align-items:center; justify-content:center;">
  <div style="background:#fff; border-radius:0.75rem; max-width:430px; width:90vw; margin:auto; padding:2rem; box-shadow:0 8px 32px rgba(0,0,0,0.18); position:relative;">
    <button onclick="document.getElementById('editUserModal').style.display='none'" style="position:absolute;top:1rem;right:1rem;font-size:1.5rem;color:#888;background:none;border:none;cursor:pointer;">&times;</button>
    <h2 style="font-size:1.4rem;font-weight:bold;color:#2563eb;margin-bottom:1rem;">Edit User Details</h2>
    <form id="editUserForm" method="POST" action="">
  @csrf
  @method('PUT')
      <input type="hidden" name="user_id" id="editUserId">
      <div style="margin-bottom:1rem;">
        <label for="editUserName" style="font-weight:600;">Name:</label>
        <input type="text" name="name" id="editUserName" class="form-control" style="width:100%;margin-top:0.5rem;padding:0.5rem;border:1px solid #ddd;border-radius:0.375rem;" required>
      </div>
      <div style="margin-bottom:1rem;">
        <label for="editUserEmail" style="font-weight:600;">Email:</label>
        <input type="email" name="email" id="editUserEmail" class="form-control" style="width:100%;margin-top:0.5rem;padding:0.5rem;border:1px solid #ddd;border-radius:0.375rem;" required>
      </div>
      <div style="margin-bottom:1rem;">
        <label for="editUserCountry" style="font-weight:600;">Country:</label>
        <select name="country_id" id="editUserCountry" class="form-control" style="width:100%;margin-top:0.5rem;padding:0.5rem;border:1px solid #ddd;border-radius:0.375rem;" required>
          <option value="">Select Country</option>
          @foreach($countries as $country)
            <option value="{{ $country->id }}">{{ $country->name }}</option>
          @endforeach
        </select>
      </div>
      <div style="margin-bottom:1.5rem;">
        <label for="editUserRole" style="font-weight:600;">Role:</label>
        <select name="role_id" id="editUserRole" class="form-control" style="width:100%;margin-top:0.5rem;padding:0.5rem;border:1px solid #ddd;border-radius:0.375rem;" required>
          <option value="">Select Role</option>
          @foreach($roles as $role)
            <option value="{{ $role->id }}">{{ $role->name }}</option>
          @endforeach
        </select>
      </div>
      <button type="submit" style="background:#2563eb;color:#fff;font-weight:bold;border-radius:0.375rem;padding:0.5rem 1.2rem;font-size:1rem;">Update User</button>
    </form>
  </div>
</div>
<script>
document.querySelectorAll('.edit-user-btn').forEach(btn => {
  btn.addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('editUserId').value = this.dataset.userId;
// Set form action to correct user update URL
const editForm = document.getElementById('editUserForm');
if (editForm) {
  editForm.action = `/admin/users/${this.dataset.userId}`;
}
    document.getElementById('editUserName').value = this.dataset.name;
    document.getElementById('editUserEmail').value = this.dataset.email;
    // Set country dropdown
    const countrySelect = document.getElementById('editUserCountry');
    if (countrySelect) countrySelect.value = this.dataset.countryId || '';
    // Set role dropdown
    const roleSelect = document.getElementById('editUserRole');
    if (roleSelect) roleSelect.value = this.dataset.roleId || '';
    document.getElementById('editUserModal').style.display = 'flex';

    // Ensure selects are visually updated (for frameworks or custom styling, if any)
    countrySelect && countrySelect.dispatchEvent(new Event('change'));
    roleSelect && roleSelect.dispatchEvent(new Event('change'));

  });
});
</script>

<!-- Password Change Modal -->
<div id="passwordModal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.45); align-items:center; justify-content:center;">
  <div style="background:#fff; border-radius:0.75rem; max-width:400px; width:90vw; margin:auto; padding:2rem; box-shadow:0 8px 32px rgba(0,0,0,0.18); position:relative;">
    <button onclick="document.getElementById('passwordModal').style.display='none'" style="position:absolute;top:1rem;right:1rem;font-size:1.5rem;color:#888;background:none;border:none;cursor:pointer;">&times;</button>
    <h2 style="font-size:1.4rem;font-weight:bold;color:#f59e42;margin-bottom:1rem;">Change Password</h2>
    <form id="passwordChangeForm" method="POST" action="">
      <input type="hidden" name="user_id" id="passwordUserId">
      <div style="margin-bottom:1rem;">
        <label for="newPassword" style="font-weight:600;">New Password for <span id="passwordUserName"></span>:</label>
        <input type="password" name="password" id="newPassword" class="form-control" style="width:100%;margin-top:0.5rem;padding:0.5rem;border:1px solid #ddd;border-radius:0.375rem;" required>
      </div>
      <div style="margin-bottom:1.5rem;">
        <label for="confirmPassword" style="font-weight:600;">Confirm Password:</label>
        <input type="password" name="password_confirmation" id="confirmPassword" class="form-control" style="width:100%;margin-top:0.5rem;padding:0.5rem;border:1px solid #ddd;border-radius:0.375rem;" required>
      </div>
      <button type="submit" style="background:#f59e42;color:#fff;font-weight:bold;border-radius:0.375rem;padding:0.5rem 1.2rem;font-size:1rem;">Change Password</button>
    </form>
  </div>
</div>

<script>
document.querySelectorAll('.password-user-btn').forEach(btn => {
  btn.addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('passwordUserId').value = this.dataset.userId;
    document.getElementById('passwordUserName').innerText = this.dataset.userName;
    document.getElementById('passwordModal').style.display = 'flex';
  });
});
// Optionally, add AJAX or form submission logic here for password change
</script>

<!-- User Info Modal -->
<div id="userInfoModal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.45); align-items:center; justify-content:center;">
  <div style="background:#fff; border-radius:0.75rem; max-width:400px; width:90vw; margin:auto; padding:2rem; box-shadow:0 8px 32px rgba(0,0,0,0.18); position:relative;">
    <button onclick="document.getElementById('userInfoModal').style.display='none'" style="position:absolute;top:1rem;right:1rem;font-size:1.5rem;color:#888;background:none;border:none;cursor:pointer;">&times;</button>
    <h2 style="font-size:1.4rem;font-weight:bold;color:#2563eb;margin-bottom:1rem;">User Information</h2>
    <div><strong>Name:</strong> <span id="modalUserName"></span></div>
    <div><strong>Email address:</strong> <span id="modalUserEmail"></span></div>
    <div><strong>Country:</strong> <span id="modalUserCountry"></span></div>
    <div><strong>Role:</strong> <span id="modalUserRole"></span></div>
    <div><strong># of Videos Uploaded:</strong> <span id="modalUserUploaded"></span></div>
    <div><strong># of Videos Downloaded:</strong> <span id="modalUserDownloaded"></span></div>
  </div>
</div>
<script>
document.querySelectorAll('.view-user-btn').forEach(btn => {
  btn.addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('modalUserName').innerText = this.dataset.name;
    document.getElementById('modalUserEmail').innerText = this.dataset.email;
    document.getElementById('modalUserCountry').innerText = this.dataset.country;
    document.getElementById('modalUserRole').innerText = this.dataset.role;
    document.getElementById('modalUserUploaded').innerText = this.dataset.uploaded;
    document.getElementById('modalUserDownloaded').innerText = this.dataset.downloaded;
    document.getElementById('userInfoModal').style.display = 'flex';
  });
});
</script>
                </tr>
                @endforeach
@else
<tr>
    <td colspan="8" class="px-6 py-8 text-center text-gray-500">No users found.</td>
</tr>
@endif
</tbody>
</table>

            </tbody>
        </table>
    </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<script src="/js/admin-users-live-search.js"></script>
<!-- Email Modal -->
<div id="emailModal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.35); align-items:center; justify-content:center;">
  <div style="background:#fff; border-radius:0.75rem; max-width:350px; width:90vw; margin:auto; padding:2rem; box-shadow:0 8px 32px rgba(0,0,0,0.18); position:relative; display:flex; flex-direction:column; align-items:center;">
    <button onclick="document.getElementById('emailModal').style.display='none'" style="position:absolute;top:1rem;right:1rem;font-size:1.5rem;color:#888;background:none;border:none;cursor:pointer;">&times;</button>
    <h2 style="font-size:1.1rem;font-weight:600;color:#2563eb;margin-bottom:1rem;">Full Email Address</h2>
    <div id="emailModalText" style="font-size:1.1rem;word-break:break-all;text-align:center;margin-bottom:1rem;"></div>
    <button onclick="document.getElementById('emailModal').style.display='none'" class="btn btn-primary" style="margin-top:0.5rem;">Close</button>
  </div>
</div>
<script>
function bindEmailPopupButtons() {
  document.querySelectorAll('.show-email-btn').forEach(function(btn) {
    btn.onclick = function() {
      document.getElementById('emailModalText').innerText = this.dataset.email;
      document.getElementById('emailModal').style.display = 'flex';
    }
  });
}
// Initial bind
bindEmailPopupButtons();
// If using AJAX or dynamically updating the table, call bindEmailPopupButtons() after update.
// If using jQuery AJAX search, you might add:
// $(document).on('ajaxComplete', bindEmailPopupButtons);
</script>
@endsection
