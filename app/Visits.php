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

    /**
     * Delete the record for the current thread.
     *
     * @return $this
     */
    public function reset()
    {
        Redis::del($this->cacheKey());

        return $this;
    }

    /**
     * Return number of visits for the current thread.
     *
     * @return int
     */
    public function count()
    {
        return Redis::get($this->cacheKey()) ?? 0;
    }

    /**
     * Record the visit for the current thread.
     *
     * @return $this
     */
    public function record()
    {
        Redis::incr($this->cacheKey());

        return $this;
    }

    /**
     * Cache key for the current thread.
     *
     * @return string
     */
    protected function cacheKey()
    {
        return "threads.{$this->thread->id}.visits";
    }
}