<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Password;

class CustomAuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $user = User::where('email', $credentials['email'])->first();
        if ($user && $user->suspended) {
            return back()->withErrors(['email' => 'Your account is suspended. Please contact admin.']);
        }
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }
        return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
    }

    public function forgotPassword(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user && $user->suspended) {
            return back()->withErrors(['email' => 'Your account is suspended. Password reset is disabled.']);
        }
        $status = Password::sendResetLink(
            $request->only('email')
        );
        return $status === Password::RESET_LINK_SENT
                    ? back()->with(['status' => __($status)])
                    : back()->withErrors(['email' => __($status)]);
    }
}
