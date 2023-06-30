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
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
	public function store(RegisterRequest $request): JsonResponse
	{
		$attributes = $request->validated();
		$attributes['password'] = bcrypt($attributes['password']);
		$user = User::create($attributes);
		event(new Registered($user));
		return Response()->json(['user' => $user], 201);
	}

	public function verify(Request $request): JsonResponse
	{
		$user = User::find($request->route('id'));

		$user->email_verified_at = null;
		$user->save();

		Log::info($request->email);

		if (!hash_equals((string) $request->route('hash'), sha1($request->email))) {
			throw new AuthorizationException();
		}
		if ($user->markEmailAsVerified()) {
			event(new Verified($user));
			$user->update(['email' => $request->email]);
		}

		return Response()->json(['msg' => 'user verified'], 200);
	}

	public function login(LoginRequest $request): JsonResponse
	{
		$attributes = $request->validated();
		$remember = (bool)$attributes['remember'] ?? false;
		unset($attributes['remember']);
		$loginField = filter_var($attributes['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
		$attributes[$loginField] = $attributes['email'];

		if ($loginField == 'email') {
			unset($attributes['name']);
		} elseif ($loginField == 'name') {
			unset($attributes['email']);
		}
		$user = User::where($loginField, $attributes[$loginField])->first();

		if (!auth()->attempt($attributes, $remember)) {
			throw ValidationException::withMessages(['email' =>  'Email or password is incorrect']);
		}

		if (!$user->email_verified_at) {
			return Response()->json(['message' =>  'user email is not verified'], 403);
		}

		session()->regenerate();
		return Response()->json(['user' => $user], 201);
	}

	public function updateUser(UpdateUserRequest $request, $id): User
	{
		$validatedData = $request->validated();

		if ($request->hasFile('img')) {
			$imgPath = $request->file('img')->store('public/profiles');
			$validatedData['img'] = $imgPath;
		}
		$user = User::findOrFail($id);
		$email = $user->email;

		if ($request->email) {
			$user->email = $request->email;
			$user->sendEmailVerificationNotification();
		}

		$user->email = $email;
		unset($validatedData['email']);

		$user->update($validatedData);
		return $user;
	}

	public function logout(): JsonResponse
	{
		auth()->logout();
		request()->session()->invalidate();
		request()->session()->regenerateToken();

		return Response()->json(['msg' => 'user logged out'], 200);
	}

	public function getUser(Request $request): JsonResponse
	{
		return Response()->json(['user' => $request->user()], 200);
	}
}
