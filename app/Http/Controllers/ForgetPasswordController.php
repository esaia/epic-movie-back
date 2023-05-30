<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ForgetPasswordController extends Controller
{
    public function sendPasswordResetLink(ResetPasswordRequest $request)
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                    ? redirect()->away(env('FRONT_APP_URL'. '/landing?modal=forgot-password-check', 'http://localhost:3000/landing?modal=forgot-password-check'))
                    : back()->withErrors(['email' => __($status)]);
    }

    public function getToken($token)
    {
        return Response()->json(['token' => $token], 201);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? redirect()->away(env('FRONT_APP_URL'. '/landing?modal=password-change-notification', 'http://localhost:3000/landing?modal=password-change-notification'))
                    : back()->withErrors(['email' => [__($status)]]);
    }
}
