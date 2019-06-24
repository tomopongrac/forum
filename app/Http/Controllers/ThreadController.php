<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Filters\ThreadFilters;
use App\Inspections\Spam;
use App\Rules\SpamFree;
use App\Thread;
use App\Trending;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    /**
     * ThreadController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth')->except('index', 'show');
    }

    /**
     * Display threads.
     *
     * @param  Channel  $channel
     * @param  ThreadFilters  $filters
     * @param  Trending  $trending
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel, ThreadFilters $filters, Trending $trending)
    {
        $threads = $this->getThreads($channel, $filters);

        return view('threads.index', [
            'threads' => $threads,
            'trending' => $trending->get(),
        ]);
    }

    /**
     * Store a thread.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Spam $spam)
    {
        $this->validate($request, [
            'title' => ['required', new SpamFree],
            'body' => ['required', new SpamFree],
            'channel_id' => 'required|exists:channels,id',
        ]);

        $spam->detect($request->input('body'));

        $thread = new Thread();
        $thread->user_id = auth()->id();
        $thread->channel_id = $request->input('channel_id');
        $thread->title = $request->input('title');
        $thread->body = $request->input('body');
        $thread->save();

        if (request()->wantsJson()) {
            return response($thread, 201);
        }

        return redirect(route('threads.show', ['channel' => $thread->channel->slug, 'thread' => $thread]))
            ->with('flash', 'Your thread has been published!');
    }

    /**
     * Display thread.
     *
     * @param  Channel  $channel
     * @param  Thread  $thread
     * @param  Trending  $trending
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Channel $channel, Thread $thread, Trending $trending)
    {
        if (auth()->check()) {
            auth()->user()->read($thread);
        }

        $trending->push($thread);
        $thread->visits()->record();

        return view('threads.show', compact('thread'));
    }

    /**
     * Update thread.
     *
     * @param  Request  $request
     * @param  Channel  $channel
     * @param  Thread  $thread
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Channel $channel, Thread $thread)
    {
        $this->authorize('update', $thread);

        $this->validate($request, [
            'title' => ['required', new SpamFree],
            'body' => ['required', new SpamFree],
        ]);

        $thread->title = request('title');
        $thread->body = request('body');
        $thread->save();
    }

    /**
     * Delete thread.
     *
     * @param  Thread  $thread
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Thread $thread)
    {
        $this->authorize('update', $thread);

        $thread->delete();

        if (request()->wantsJson()) {
            return response([], 204);
        }

        return redirect(route('threads.index'));
    }

    /**
     * Fetch all relevant threads.
     *
     * @param  Channel  $channel
     * @param  ThreadFilters  $filters
     * @return mixed
     */
    protected function getThreads(Channel $channel, ThreadFilters $filters)
    {
        $threads = Thread::latest();

        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }

        return $threads->filter($filters)->paginate(25);

    }
}
