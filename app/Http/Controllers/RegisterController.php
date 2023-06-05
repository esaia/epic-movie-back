<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
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


    public function verify(Request $request): JsonResponse
    {
        $user = User::find($request->route('id'));
        if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException();
        }
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return Response()->json([ 'msg' => "user verified"], 201);
    }


    public function login(LoginRequest $request): JsonResponse
    {
        $attributes = $request->validated();
        $remember = !!$attributes['remember'] ?? false;
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

        $user = User::where($loginField, $attributes[$loginField])->first();

        session()->regenerate();
        return Response()->json([ 'user' => $user], 201);

    }

    public function updateUser(UpdateUserRequest $request, $id): User
    {

        $validatedData = $request->validated();

        if ($request->hasFile('img')) {
            $imgPath = $request->file('img')->store('public/profiles');
            $validatedData['img'] = $imgPath;
        }

        $user = User::findOrFail($id);
        $user->update($validatedData);
        return $user;
    }


    public function logout(): JsonResponse
    {
        Auth::guard('api')->logout();

        $response = new Response('Logged out');
        $response->cookie('XSRF-TOKEN', '', 0, '/', null, false, true);


        return Response()->json(['msg' => "user logged out",], 201);
    }

    public function getUser(Request $request): JsonResponse
    {
        return Response()->json(['user' => $request->user()], 201);
    }


}
