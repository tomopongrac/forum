<?php

namespace App\Http\Controllers;

use App\Thread;

class LockedThreadController extends Controller
{
    /**
     * Lock the given thread.
     *
     * @param  Thread  $thread
     */
    public function store(Thread $thread)
    {
        $thread->locked = true;
        $thread->save();
    }

    /**
     * Unlock the given thread.
     *
     * @param  Thread  $thread
     */
    public function destroy(Thread $thread)
    {
        $thread->locked = false;
        $thread->save();
    }
}
