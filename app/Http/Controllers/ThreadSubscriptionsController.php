<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;

class ThreadSubscriptionsController extends Controller
{
    /**
     * Store a new thread subscription.
     *
     * @param $channelSlug
     * @param  Thread  $thread
     */
    public function store($channelSlug, Thread $thread)
    {
        $thread->subscribe();
    }

    /**
     * Delete an existing thread subscription.
     *
     * @param $channelSlug
     * @param  Thread  $thread
     */
    public function destroy($channelSlug, Thread $thread)
    {
        $thread->unsubscribe();
    }
}
