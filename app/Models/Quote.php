<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quote extends Model
{
	use HasFactory;

	protected $with = ['user', 'movie', 'comment'];

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

	public function scopeSearchByMovieTitle($query, $searchQuery)
	{
		return $query->whereHas('movie', function ($subQuery) use ($searchQuery) {
			$subQuery->where('title->en', 'like', '%' . substr($searchQuery, 1) . '%')
				->orWhere('title->ka', 'like', '%' . substr($searchQuery, 1) . '%');
		});
	}

	public function scopeSearchByQuote($query, $searchQuery)
	{
		return $query->where('quote->en', 'like', '%' . substr($searchQuery, 1) . '%')
				->orWhere('quote->ka', 'like', '%' . substr($searchQuery, 1) . '%');
	}
}
