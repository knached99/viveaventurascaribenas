<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoStepVerification extends Controller
{
    public function showChallengeForm()
    {
        return view('auth.two-factor-challenge');
    }

    public function verifyChallenge(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $user = Auth::user();

        if ($user->verifyTwoFactor($request->code)) {
            $request->session()->forget(['login.email', 'login.password']);
            return redirect()->intended();
        }

        return redirect()->route('two-factor.challenge')->with('error', 'Invalid two-factor authentication code.');
    }
}
