<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Http\Requests\CreatePostRequest;
use App\Notifications\YouWereMentioned;
use App\Reply;
use App\Rules\SpamFree;
use App\Thread;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ReplyController extends Controller
{
    /**
     * ReplyController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    /**
     * Fetch all relevant replies.
     *
     * @param int   $channelId
     * @param  Thread  $thread
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }

    /**
     * Persist a new reply.
     *
     * @param  Channel  $channel
     * @param  Thread  $thread
     * @param  CreatePostRequest  $form
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Database\Eloquent\Model|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function store(Channel $channel, Thread $thread, CreatePostRequest $form)
    {
        if ($thread->locked) {
            return response('Thread is locked', 422);
        }

        $reply = $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id(),
        ]);

        if (request()->expectsJson()) {
            return $reply->load('owner');
        }

        return redirect(route('threads.show', ['channel' => $thread->channel->slug, 'thread' => $thread]))
            ->with('flash', 'Your reply has been left.');
    }

    /**
     * Update an existing reply.
     *
     * @param  Request  $request
     * @param  Reply  $reply
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Reply $reply)
    {
        $this->authorize('update', $reply);

        $this->validate($request, [
            'body' => ['required', new SpamFree],
        ]);

        $reply->update(['body' => request('body')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if (request()->expectsJson()) {
            return response(['status' => 'Reply deleted']);
        }

        return back();
    }
}
