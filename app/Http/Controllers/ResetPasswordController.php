<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function index(Request $request)
    {
        $token = $request->query('token');
        return view('password/reset-password', compact('token'));
    }


    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::where('reset_token', $request->token)->first();

        if (!$user) {
            return back()->withErrors(['token' => 'Token inválido.']);
        }

        $user->password = Hash::make($request->password);
        $user->reset_token = null;
        $user->save();

        return redirect()->route('login.page')->with('success', 'Senha redefinida com sucesso.');
    }
}
