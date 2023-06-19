<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $with = ['sender','quote'];
    protected $fillable = ['sender_id','quote_id', 'user_id', 'seen'];


    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }


    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }



}
