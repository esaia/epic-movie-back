<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function store(RegisterRequest $request)
    {
        $attributes = $request->validated();
        $attributes['password'] = bcrypt($attributes['password']);
        $user = User::create($attributes);
        event(new Registered($user));
        return Response()->json([ 'user' => $user], 201);
    }


    public function verify(Request $request)
    {
        $user = User::find($request->route('id'));
        if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException();
        }
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }
        return redirect()->away(env('FRONT_APP_URL'. '/landing?modal=account-activation', 'http://localhost:3000/landing?modal=account-activation'));
    }


    public function login(LoginRequest $request)
    {
        $attributes = $request->validated();

        if(isset($attributes['remember']) && $attributes['remember']) {
            $remember=true;
            unset($attributes['remember']);
        } else {
            $remember = false;
        }

        $loginField = filter_var($attributes['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        $attributes[$loginField] = $attributes['email'];
        if($loginField == 'email') {
            unset($attributes['name']);
        } elseif($loginField == 'name') {
            unset($attributes['email']);
        };

        $user = User::where($loginField, $attributes[$loginField])->first();
        if(!$user || !Hash::check($attributes['password'], $user->password)) {
            return Response()->json(['msg' => "bad credantils"]);
        }
        if($remember) {
            $token = $user->createToken('authtoken', ['remember'])->plainTextToken;
        } else {
            $token = $user->createToken('authtoken')->plainTextToken;
        }

        return Response()->json(['access_token' => $token, 'user' => $user], 201);
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return Response()->json(['msg' => "user logged out",], 201);
    }

    public function getUser(Request $request)
    {
        return $request->user();
    }


}
