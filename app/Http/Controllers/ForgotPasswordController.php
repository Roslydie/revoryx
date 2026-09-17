<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Throwable;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $email = Str::lower(trim($request->input('email')));
        $userModel = config('auth.providers.users.model');
        $user = $userModel::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Si cette adresse existe, un lien de réinitialisation sera envoyé.',
            ]);
        }

        try {
            $token = Password::broker()->createToken($user);
            $url = URL::to('/admin/reset-password') . '?' . http_build_query([
                'token' => $token,
                'email' => $email,
            ]);

            $html = View::make('emails.reset-password', ['url' => $url])->render();

            Mail::mailer('smtp')->to($email)->send(
                new ResetPasswordMail($html)
            );

            return response()->json([
                'message' => 'Un lien de réinitialisation a été envoyé à votre adresse email.',
            ]);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'message' => 'Impossible d\'envoyer le lien de réinitialisation.',
            ], 500);
        }
    }
}