<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public function store(RegisterRequest $request): JsonResponse
    {
        $attributes = $request->validated();
        $attributes['password'] = bcrypt($attributes['password']);
        $user = User::create($attributes);
        event(new Registered($user));
        return Response()->json([ 'user' => $user], 201);
    }


    public function verify(Request $request): RedirectResponse
    {
        $user = User::find($request->route('id'));
        if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException();
        }
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        $frontAppUrl = config('app.front_app_url', 'http://localhost:3000') . '/landing?modal=account-activation';

        return redirect()->away($frontAppUrl);
    }


    public function login(LoginRequest $request): JsonResponse
    {

        $attributes = $request->validated();

        if(isset($attributes['remember']) && $attributes['remember']) {
            $remember=true;
        } else {
            $remember = false;
        }
        unset($attributes['remember']);

        $loginField = filter_var($attributes['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        $attributes[$loginField] = $attributes['email'];
        if($loginField == 'email') {
            unset($attributes['name']);
        } elseif($loginField == 'name') {
            unset($attributes['email']);
        };


        if (!auth()->attempt($attributes, $remember)) {
            throw ValidationException::withMessages(['email' =>  "Email or password is incorrect"  ]);
        }

        session()->regenerate();

        return Response()->json([ 'user' => $attributes], 201);
    }


    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return Response()->json(['msg' => "user logged out",], 201);
    }

    public function getUser(Request $request): mixed
    {
        return $request->user();
    }


}
