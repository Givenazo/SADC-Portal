@extends('layouts.app')

@section('title', 'Login - SADC News Portal')

@section('content')
<div class="min-h-screen flex flex-col justify-center items-center bg-gradient-to-br from-[#003366] via-[#F5F5F5] to-white bg-cover bg-center" style="background-image: url('{{ asset('images/sadc-news-bg.jpg') }}');">
    <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8 mx-2">
        <h2 class="text-2xl md:text-3xl font-bold text-[#003366] mb-6 text-center font-sans">Welcome to SADC News Portal</h2>
        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-[#003366] font-sans">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 bg-[#F5F5F5] text-[#003366] focus:outline-none focus:ring-2 focus:ring-[#003366] font-sans">
                @error('email')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-[#003366] font-sans">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 bg-[#F5F5F5] text-[#003366] focus:outline-none focus:ring-2 focus:ring-[#003366] font-sans">
                @error('password')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" type="checkbox" name="remember" class="h-4 w-4 text-[#003366] focus:ring-[#003366] border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-700 font-sans">Remember Me</label>
                </div>
                <div class="text-sm">
                    <a href="{{ route('password.request') }}" class="text-[#003366] hover:underline font-sans">Forgot Password?</a>
                </div>
            </div>
            <div>
                <button type="submit" class="w-full py-2 px-4 bg-[#003366] hover:bg-blue-900 text-white font-semibold rounded-md shadow-sm transition-colors font-sans text-lg">Login</button>
            </div>
        </form>
    </div>
    <footer class="mt-8 text-center text-gray-500 text-xs font-sans">
        © 2025 SADC News Portal. All Rights Reserved.
    </footer>
</div>
@endsection
