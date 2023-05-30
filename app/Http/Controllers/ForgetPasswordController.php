<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ForgetPasswordController extends Controller
{
    public function sendPasswordResetLink(ResetPasswordRequest $request): JsonResponse
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if($status == Password::RESET_LINK_SENT) {
            return Response()->json([ 'msg' => "password recovery link sent"], 201);
        } else {
            return Response()->json([ 'msg' => "something went wrong"], 401);
        }

    }


    public function getToken($token, Request $request): RedirectResponse
    {
        $frontAppUrl = config('app.front_app_url', 'http://localhost:3000') .'/landing?modal=recover-password&token='.$token . '&email=' . $request->email;

        return redirect()->away($frontAppUrl);
    }

    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
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


        if($status == Password::PASSWORD_RESET) {
            return Response()->json([ 'msg' => "password updated successfully"], 201);
        } else {
            return Response()->json([ 'msg' => "something went wrong"], 401);
        }
    }
}
