<?php

namespace App\Controllers\User;

use App\Models\Tip;
use App\Models\Comment;
use App\Types\Controller;
use App\Actions\Comment\ReplyAction;
use App\Actions\Comment\StoreAction;
use App\Actions\Comment\DeleteAction;
use App\Actions\Comment\UpdateAction;
use Illuminate\Http\RedirectResponse;
use App\Requests\Comment\ReplyRequest;
use App\Requests\Comment\StoreRequest;
use App\Requests\Comment\DeleteRequest;
use App\Requests\Comment\UpdateRequest;

class CommentController extends Controller
{
    /**
     * Constructor.
     *
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('throttle:3,6')->only(['store', 'reply']);
    }

    /**
     * Delete the given comment.
     *
     */
    public function delete(DeleteRequest $request, Comment $comment) : RedirectResponse
    {
        DeleteAction::execute($comment);

        return redirect()
            ->route('tips.show', ['tip' => $comment->tip->slug])
            ->notify('The comment has been deleted');
    }

    /**
     * Reply to the given comment.
     *
     */
    public function reply(ReplyRequest $request, Comment $comment) : RedirectResponse
    {
        ReplyAction::execute(user(), $comment->load('tip'), $request->message);

        return redirect()
            ->route('tips.show', ['tip' => $comment->tip->slug])
            ->notify('The comment has been added');
    }

    /**
     * Store a new comment for the given tip.
     *
     */
    public function store(StoreRequest $request, Tip $tip) : RedirectResponse
    {
        StoreAction::execute(user(), $tip, $request->message);

        return redirect()
            ->route('tips.show', ['tip' => $tip->slug])
            ->notify('The comment has been added');
    }

    /**
     * Update the given comment.
     *
     */
    public function update(UpdateRequest $request, Comment $comment) : RedirectResponse
    {
        UpdateAction::execute($comment, $request->validated());

        return redirect()
            ->route('tips.show', ['tip' => $comment->tip->slug])
            ->notify('The comment has been updated');
    }
}
