<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(): JsonResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = User::updateOrCreate([
             'email' => $googleUser->email,
            ], [
             'name' => $googleUser->name,
             'email' => $googleUser->email,
             'img' => $googleUser->avatar,
             'google_id' => $googleUser->id,
            ]);

            Auth::login($user);
            return Response()->json([ 'user' => $user], 201);

        } catch (\Throwable $th) {
            return Response()->json([ 'msg' => 'something went wrong', 'error' => $th], 500);
        }


    }
}
