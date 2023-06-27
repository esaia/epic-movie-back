<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quote extends Model
{
	use HasFactory;

	protected $with = ['user', 'movie', 'comment', 'like'];

	protected $fillable = ['quote', 'img', 'movie_id', 'user_id'];

	public $casts = ['quote'=> 'array'];

	public function movie(): BelongsTo
	{
		return $this->BelongsTo(Movie::class);
	}

	public function user(): BelongsTo
	{
		return $this->BelongsTo(User::class);
	}

	public function comment(): HasMany
	{
		return $this->HasMany(Comment::class);
	}

	public function notification(): HasMany
	{
		return $this->HasMany(Notification::class);
	}

	public function like(): HasMany
	{
		return $this->HasMany(Like::class);
	}

	public function scopeSearchByQuote($query, $condition, $searchQuery, $keyWord = 'quote')
	{
		return $query->{$condition}("{$keyWord}->en", 'like', '%' . $searchQuery . '%')
				->orWhere("{$keyWord}->ka", 'like', '%' . $searchQuery . '%');
	}
}
