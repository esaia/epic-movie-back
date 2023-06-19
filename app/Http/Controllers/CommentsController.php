<?php

namespace App\Http\Controllers;

use App\Events\CommentEvent;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Quote;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    public function index($quoteID): Collection
    {
        $quote = Quote::find($quoteID);
        $comments = $quote->comment;
        return $comments;
    }


    public function store(CommentRequest $request): Comment
    {
        $commentAttributes = $request->validated();
        $comment = Comment::Create($commentAttributes);


        $notificatinAttributes = [
            'sender_id' => $comment->user->id,
            'quote_id' => $comment->quote_id,
            'status' => 'comment',
        ];

        if ($comment->quote->user_id !== Auth::id()) {
            Notification::create($notificatinAttributes);
        }

        event(new CommentEvent($comment));


        return $comment;
    }
}
