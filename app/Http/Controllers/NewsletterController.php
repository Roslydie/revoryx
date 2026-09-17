<?php

namespace App\Http\Controllers;

use App\Mail\NewsletterMessageMail;
use App\Models\Newsletter;
use App\Models\NewsletterMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class NewsletterController extends Controller
{
    public function index()
    {
        return response()->json(['status' => 200, 'data' => Newsletter::latest()->get()]);
    }

    public function show($id)
    {
        return response()->json(['status' => 200, 'data' => Newsletter::findOrFail($id)]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:newsletters,email',
            'language' => 'required|in:fr,en',
        ]);

        return response()->json([
            'status' => 200,
            'data' => Newsletter::create($validated),
            'message' => 'Subscriber added successfully.',
        ]);
    }

    public function edit(Request $request, $id)
    {
        $subscriber = Newsletter::findOrFail($id);
        $subscriber->update($request->validate([
            'email' => 'required|email|unique:newsletters,email,' . $id,
            'language' => 'required|in:fr,en',
        ]));

        return response()->json(['status' => 200, 'data' => $subscriber]);
    }

    public function destroy($id)
    {
        Newsletter::findOrFail($id)->delete();
        return response()->json(['status' => 200, 'message' => 'Subscriber deleted successfully.']);
    }

    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'language' => 'nullable|in:fr,en',
        ]);

        $email = Str::lower(trim($validated['email']));
        $subscriber = Newsletter::firstOrCreate(
            ['email' => $email],
            ['language' => $validated['language'] ?? 'fr']
        );

        return response()->json([
            'status' => 200,
            'already_subscribed' => !$subscriber->wasRecentlyCreated,
            'message' => $subscriber->wasRecentlyCreated
                ? 'You are now subscribed to our newsletter.'
                : 'This email is already subscribed to our newsletter.',
        ]);
    }

    public function messages()
    {
        return response()->json(['status' => 200, 'data' => NewsletterMessage::latest()->get()]);
    }

    public function storeMessage(Request $request)
    {
        $validated = $request->validate([
            'subject.fr' => 'required|string|max:255',
            'subject.en' => 'required|string|max:255',
            'content.fr' => 'required|string',
            'content.en' => 'required|string',
        ]);

        $message = NewsletterMessage::create([
            'subject_fr' => $validated['subject']['fr'],
            'subject_en' => $validated['subject']['en'],
            'content_fr' => $validated['content']['fr'],
            'content_en' => $validated['content']['en'],
        ]);

        return response()->json(['status' => 201, 'data' => $message]);
    }

    public function sendMessage($id)
    {
        $message = NewsletterMessage::findOrFail($id);
        $subscribers = Newsletter::all();

        if ($subscribers->isEmpty()) {
            return response()->json(['message' => 'No subscribers found.'], 422);
        }

        $sent = 0;
        $errors = [];

        foreach ($subscribers as $subscriber) {
            $language = $subscriber->language === 'en' ? 'en' : 'fr';
            $subject = $language === 'en' ? $message->subject_en : $message->subject_fr;
            $content = $language === 'en' ? $message->content_en : $message->content_fr;

            try {
                Mail::mailer('smtp')->to($subscriber->email)->send(
                    new NewsletterMessageMail($subject, $content, $subscriber->id)
                );
                $sent++;
            } catch (Throwable $exception) {
                $errors[] = $subscriber->email . ': ' . $exception->getMessage();
                report($exception);
            }
        }

        $message->update([
            'status' => $sent === $subscribers->count() ? 'sent' : ($sent ? 'partial' : 'failed'),
            'recipients_count' => $sent,
            'sent_at' => $sent ? now() : null,
            'error' => $errors ? implode("\n", $errors) : null,
        ]);

        return response()->json([
            'status' => $sent ? 200 : 500,
            'message' => $sent . ' of ' . $subscribers->count() . ' messages sent.',
            'sent' => $sent,
            'attempted' => $subscribers->count(),
        ], $sent ? 200 : 500);
    }

    public function unsubscribe($id)
    {
        Newsletter::findOrFail($id)->delete();
        return response('<script>alert("You have been unsubscribed successfully."); window.close();</script>');
    }
}
