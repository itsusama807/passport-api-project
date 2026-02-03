<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class PasswordResetViewController extends Controller
{
    public function showResetForm(Request $request)
    {

        $token = $request->token;
        $email = $request->email;
        if (!$token || !$email) {
            return view('errors.invalid-reset-link');
        }
        $record = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$record) {
            return view('errors.invalid-reset-link');
        }

        if (!Hash::check($token, $record->token)) {
            return view('errors.invalid-reset-link');
        }
        $expiresAt = now()->subMinutes(config('auth.passwords.users.expire'));
        if ($record->created_at < $expiresAt) {
            return view('errors.invalid-reset-link');
        }
        return view('auth.reset-password',  ['token' => $token, 'email' => $email]);
    }
}
