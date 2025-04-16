@extends('layouts.app')
@section('content')
<div class="container mx-auto p-6 max-w-lg">
    <h1 class="text-2xl font-bold text-[#003366] mb-4">Edit User</h1>
    <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium text-[#003366]">Name</label>
            <input type="text" name="name" value="{{ $user->name }}" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-[#003366]">Email</label>
            <input type="email" name="email" value="{{ $user->email }}" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-[#003366]">Country</label>
            <select name="country_id" class="w-full border rounded px-3 py-2" required>
                @foreach($countries as $country)
                    <option value="{{ $country->id }}" @if($user->country_id == $country->id) selected @endif>{{ $country->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-[#003366]">Role</label>
            <select name="role_id" class="w-full border rounded px-3 py-2" required>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" @if($user->role_id == $role->id) selected @endif>{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-[#003366]">Password <span class="text-gray-500">(leave blank to keep current)</span></label>
            <input type="password" name="password" class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium text-[#003366]">Confirm Password</label>
            <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2">
        </div>
        <button type="submit" class="w-full py-2 px-4 bg-[#003366] hover:bg-blue-900 text-white font-semibold rounded">Update User</button>
    </form>
</div>
@endsection
