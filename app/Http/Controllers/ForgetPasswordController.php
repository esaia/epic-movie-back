<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
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

        return $status == Password::RESET_LINK_SENT ? Response()->json([ 'msg' => "password recovery link sent"], 201) : Response()->json([ 'msg' => "something went wrong"], 401);
    }


    public function getToken(): JsonResponse
    {
        return Response()->json([ 'msg' => "token created"], 201);
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

        return $status == Password::PASSWORD_RESET ? Response()->json([ 'msg' => "password updated successfully"], 201) : Response()->json([ 'msg' => "something went wrong"], 401);

    }
}
