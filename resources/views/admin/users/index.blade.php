@extends('layouts.app')
@section('content')
<div class="container mx-auto py-10 px-6">
    <div class="mb-8">
    <h1 class="text-3xl font-bold text-[#003366] mb-2">User Management</h1>
    <p class="text-gray-600 mb-4">Manage all users of the SADC News Portal. Add, edit, or remove users as needed.</p>
</div>
<div class="w-full flex flex-col md:flex-row md:justify-between items-center mb-6">
    <a href="{{ route('admin.users.create') }}"
       style="background:#003366;color:white;font-weight:600;padding:0.75rem 2rem;border-radius:0.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.06);font-size:1.15rem;display:inline-flex;align-items:center;text-decoration:none;"
       class="hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-300 mb-3 md:mb-0">
        <i class="bi bi-person-plus-fill mr-2"></i> Add User
    </a>
    <form method="GET" action="" class="w-full md:w-1/3 flex items-center">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..." class="w-full px-4 py-2 border border-gray-300 rounded-l focus:outline-none focus:ring-2 focus:ring-blue-300" />
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-r hover:bg-blue-700"><i class="bi bi-search"></i></button>
    </form>
</div>
    <a href="{{ route('admin.users.create') }}"
       style="background:#003366;color:white;font-weight:600;padding:0.75rem 2rem;border-radius:0.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.06);font-size:1.15rem;display:inline-flex;align-items:center;text-decoration:none;"
       class="hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-300">
        <i class="bi bi-person-plus-fill mr-2"></i> Add User
    </a>
</div>
    <div class="bg-white rounded-xl shadow-lg p-6">
        <table class="min-w-full divide-y divide-gray-200" style="border-collapse: separate; border-spacing: 0;">
            <thead class="bg-[#f8fafc]" style="position:sticky;top:0;z-index:2;">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Country</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Edit</th>
<th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Change Password</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
@php $isOdd = false; @endphp
                @forelse($users as $user)
                @php $isOdd = !$isOdd; @endphp
                <tr class="transition hover:bg-blue-50" style="border-bottom:1px solid #e5e7eb;{{ $isOdd ? 'background:#f9fafb;' : '' }}">
                    <td class="px-6 py-5 text-left align-middle">
                        <div class="w-10 h-10 bg-[#003366] text-white flex items-center justify-center rounded-full font-bold text-lg uppercase">
                            {{ strtoupper(substr($user->name,0,1)) }}
                        </div>
                        <span class="font-semibold">{{ $user->name }}</span>
                    </td>
                    <td class="px-6 py-5 text-left align-middle" style="word-break:break-all;">{{ $user->email }}</td>
                    <td class="px-6 py-5 text-left align-middle">{{ $user->country->name ?? '-' }}</td>
                    <td class="px-6 py-5 text-left align-middle">{{ $user->role->name ?? '-' }}</td>
                    <td class="px-6 py-4">
    <a href="{{ route('admin.users.edit', $user->id) }}" style="background:#2563eb;color:#fff;font-weight:bold;border-radius:0.375rem;padding:0.5rem 1rem;margin-right:0.5rem;transition:background 0.2s;white-space:nowrap;display:inline-flex;align-items:center;" onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='#2563eb'"><i class="bi bi-pencil-square mr-1"></i> Edit</a>
</td>
<td class="px-6 py-4">
    <a href="#" style="background:#f59e42;color:#fff;font-weight:bold;border-radius:0.375rem;padding:0.5rem 1rem;margin-right:0.5rem;transition:background 0.2s;white-space:nowrap;display:inline-flex;align-items:center;" onmouseover="this.style.background='#d97706'" onmouseout="this.style.background='#f59e42'"><i class="bi bi-key mr-1"></i> Change Password</a>
</td>
<td class="px-6 py-4">
    <a href="#" style="background:#22c55e;color:#fff;font-weight:bold;border-radius:0.375rem;padding:0.5rem 1rem;margin-right:0.5rem;transition:background 0.2s;white-space:nowrap;display:inline-flex;align-items:center;" onmouseover="this.style.background='#16a34a'" onmouseout="this.style.background='#22c55e'"><i class="bi bi-eye mr-1"></i> View</a>
</td>
<td class="px-6 py-4">
    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="inline-block text-white bg-red-600 hover:bg-red-700 rounded px-4 py-2 font-bold transition" style="white-space:nowrap;display:inline-flex;align-items:center;" onclick="return confirm('Are you sure?')"><i class="bi bi-trash mr-1"></i> Delete</button>
    </form>
</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">No users found.</td>
                </tr>
                @endforelse
</tbody>
</table>
<div class="flex justify-center mt-6">
    {{ $users->links() }}
</div>
            </tbody>
        </table>
    </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

@endsection
