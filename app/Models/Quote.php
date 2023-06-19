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
    protected $fillable = ['quote','img', 'movie_id', 'user_id'];
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



}
