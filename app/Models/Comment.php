<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;
    protected $with = ['user'];
    protected $fillable = [ 'comment' , 'quote_id', 'user_id' ];

    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class);
    }

    public function quote(): BelongsTo
    {
        return $this->BelongsTo(Quote::class);
    }


}
