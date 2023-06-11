<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = ['quote','img', 'movie_id'];
    public $casts = ['quote'=> 'array'];

    public function movie(): BelongsTo
    {
        return $this->BelongsTo(Movie::class);
    }

}
