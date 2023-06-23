<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Movie extends Model
{
	use HasFactory;

	protected $fillable = [
		'title',
		'genre',
		'date',
		'director',
		'description',
		'img',
		'user_id',
	];

	public $casts = ['genre'=> 'array', 'title'=> 'array', 'director'=> 'array', 'description'=> 'array'];

	public function user(): BelongsTo
	{
		return $this->BelongsTo(User::class);
	}

	public function quote()
	{
		return $this->hasMany(Quote::class);
	}

	public function scopeSearchByQuote($query, $condition, $searchQuery, $keyWord = 'quote')
	{
		return $query->{$condition}("{$keyWord}->en", 'like', '%' . $searchQuery . '%')
				->orWhere("{$keyWord}->ka", 'like', '%' . $searchQuery . '%');
	}
}
