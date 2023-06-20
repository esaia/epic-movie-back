<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
	use HasApiTokens;

	use HasFactory;

	use Notifiable;

	protected $fillable = ['name', 'email', 'password', 'img', 'google_id'];

	protected $hidden = [
		'password',
		'remember_token',
	];

	protected $casts = [
		'email_verified_at' => 'datetime',
		'password'          => 'hashed',
	];

	public function movie(): HasMany
	{
		return $this->hasMany(Movie::class);
	}

	public function quote(): HasMany
	{
		return $this->hasMany(Quote::class);
	}

	public function comment(): HasMany
	{
		return $this->HasMany(Comment::class);
	}

	public function notification(): HasMany
	{
		return $this->HasMany(Notification::class);
	}
}
