<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SADC News Portal</title>
    <!-- Tailwind CSS CDN (remove if using Laravel Mix/Vite) -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700|Open+Sans:400,700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Roboto', 'Open Sans', Arial, sans-serif;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-center items-center bg-gradient-to-br from-[#003366] via-[#F5F5F5] to-white bg-cover bg-center" style="background-image: url('{{ asset('images/sadc-news-bg.jpg') }}');">
    <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8 mx-2 mt-8">
        <!-- SADC News Portal Logo -->
        <div class="flex justify-center mb-4">
            <svg width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="56" height="56" rx="12" fill="#003366"/>
                <text x="50%" y="54%" text-anchor="middle" fill="#F5F5F5" font-family="Roboto, Open Sans, Arial, sans-serif" font-size="20" font-weight="bold" dy=".3em">SADC</text>
                <rect x="14" y="40" width="28" height="4" rx="2" fill="#F5C542"/>
            </svg>
        </div>
        <h2 class="text-2xl md:text-3xl font-bold text-[#003366] mb-6 text-center">Welcome to the <br/>SADC News Portal</h2>
        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf
            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-medium text-[#003366]">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 bg-[#F5F5F5] text-[#003366] focus:outline-none focus:ring-2 focus:ring-[#003366]">
                @error('email')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-[#003366]">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 bg-[#F5F5F5] text-[#003366] focus:outline-none focus:ring-2 focus:ring-[#003366]">
                @error('password')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" type="checkbox" name="remember" class="h-4 w-4 text-[#003366] focus:ring-[#003366] border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-700">Remember Me</label>
                </div>
                <div class="text-sm">
                    <a href="{{ route('password.request') }}" class="text-[#003366] hover:underline">Forgot Password?</a>
                </div>
            </div>
            <!-- Login Button -->
            <div>
                <button type="submit" class="w-full py-2 px-4 bg-[#003366] hover:bg-blue-900 text-white font-semibold rounded-md shadow-sm transition-colors text-lg">Login</button>
            </div>
        </form>
    </div>
    <footer class="mt-8 text-center text-gray-500 text-xs w-full">
        2025 SADC News Portal. All Rights Reserved.
    </footer>
</body>
</html>
