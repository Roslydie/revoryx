<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Mail\ContactMessageMail;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return response()->json(Contact::latest()->get());
    }
public function store(Request $request)
{
    $validated = $request->validate([
        'full_name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:50',
        'subject' => 'required|string|max:255',
        'message' => 'required|string',
    ]);

    $validated['status'] = 'new';

    $contact = Contact::create($validated);

    Mail::to(config('mail.from.address'))->send(
    new ContactMessageMail($contact)
);

    return response()->json($contact, 201);
}

    public function show(Contact $contact)
    {
        return response()->json($contact);
    }

    public function update(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'status' => 'sometimes|required|string|in:new,read,closed',
        ]);

        $contact->update($validated);

        return response()->json($contact);
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return response()->json(['message' => 'Contact deleted']);
    }
}
