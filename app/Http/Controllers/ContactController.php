<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Mail\ContactMessage;

class ContactController extends Controller
{
    /**
     * Show contact form.
     */
    public function show()
    {
        return view('contact-us');
    }

    /**
     * Handle contact form submission.
     */
    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email',
            'subject' => 'required|string|in:order,product,seller,general',
            'message' => 'required|string|max:2000',
        ]);

        try {
            Mail::to('support@crafty.com')->send(new ContactMessage(
                $validated['name'],
                $validated['email'],
                $validated['subject'],
                $validated['message']
            ));

            return back()->banner('Message sent successfully');
        } catch (\Exception $e) {
            Log::error('Contact form email failed: ' . $e->getMessage());
            return back()->warningBanner('Failed to send message. Please try again later.');
        }
    }
}
