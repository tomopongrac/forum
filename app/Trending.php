<?php

namespace App;

use Illuminate\Support\Facades\Redis;

class Trending
{
    /**
     * Fetch all trending threads.
     *
     * @return array
     */
    public function get()
    {
        return array_map('json_decode', Redis::zrevrange('trending_threads', 0, 4));
    }

    /**
     * Push a new thread to the trending list.
     *
     * @param $thread
     */
    public function push($thread)
    {
        Redis::zincrby('trending_threads', 1, json_encode([
            'title' => $thread->title,
            'path' => route('threads.show', ['channel' => $thread->channel, 'thread' => $thread]),
        ]));
    }
}
