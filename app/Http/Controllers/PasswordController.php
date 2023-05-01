<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendResetPasswordEmail;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    public function index()
    {
        return view('password/forgot-password');
    }

    public function sendPasswordEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Não foi encontrado um usuário com esse e-mail.']);
        }

        $token = Str::random(60);
        $user->reset_token = $token;
        $user->save();

        // Enviar e-mail com o token
        Mail::to($user->email)->send(new SendResetPasswordEmail($user, $token));

        return back()->with('success', 'E-mail enviado com sucesso.');
    }

}
