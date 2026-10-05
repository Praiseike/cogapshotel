<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $contact = Contact::create($validated);

        \App\Support\ActivityLogger::log('contact.received', $contact, ['subject' => $validated['subject']], "Message from {$validated['name']}");

        return redirect()->route('contact.show')
            ->with('success', 'Your message has been sent. We will get back to you soon.');
    }
}
