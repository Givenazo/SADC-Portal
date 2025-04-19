<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function info()
    {
        return view('contact.info');
    }

    public function submit(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Here you can add your logic to handle the contact form submission
        // For example, you could:
        // 1. Send an email
        // 2. Save to database
        // 3. Send notification to admin
        // etc.

        // For now, we'll just return a success message
        return back()->with('success', 'Thank you for your message. We will get back to you soon!');
    }
}
