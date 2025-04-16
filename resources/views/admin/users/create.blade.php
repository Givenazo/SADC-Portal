@extends('layouts.app')
@section('content')
<div class="container mx-auto p-6 max-w-lg">
    <div class="mb-8">
    <a href="{{ route('admin.users.index') }}"
       class="inline-flex items-center bg-warning text-dark font-bold px-6 py-3 rounded-xl shadow-lg border-2 border-warning hover:bg-yellow-400 hover:text-black transition-all text-xl"
       style="background:#ffc107;color:#212529;text-decoration:none;box-shadow:0 4px 16px rgba(0,0,0,0.12);border:2px solid #ffc107;">
        <i class="bi bi-arrow-left-short text-2xl mr-2"></i> Back to Users
    </a>
</div>
    <h1 class="text-2xl font-bold text-[#003366] mb-4">Add User</h1>
    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-[#003366]">Name</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-[#003366]">Email</label>
            <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-[#003366]">Country</label>
            <select name="country_id" class="w-full border rounded px-3 py-2" required>
                <option value="">Select Country</option>
                @foreach($countries as $country)
                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-[#003366]">Role</label>
            <select name="role_id" class="w-full border rounded px-3 py-2" required>
                <option value="">Select Role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-[#003366]">Password</label>
            <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-[#003366]">Confirm Password</label>
            <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2" required>
        </div>
        <button type="submit" class="w-full py-2 px-4 bg-[#003366] hover:bg-blue-900 text-white font-semibold rounded">Add User</button>
    </form>
</div>
@endsection
