<?php

namespace App;

use Illuminate\Support\Facades\Redis;

class Visits
{
    /**
     * @var Thread
     */
    protected $thread;

    /**
     * Visits constructor.
     */
    public function __construct(Thread $thread)
    {
        $this->thread = $thread;
    }

    public function reset()
    {
        Redis::del($this->cacheKey());

        return $this;
    }

    public function count()
    {
        return Redis::get($this->cacheKey()) ?? 0;
    }

    public function record()
    {
        Redis::incr($this->cacheKey());

        return $this;
    }

    protected function cacheKey()
    {
        return "threads.{$this->thread->id}.visits";
    }
}