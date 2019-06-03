<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;

class ThreadSubscriptionsController extends Controller
{
    public function store($channelSlug, Thread $thread)
    {
        $thread->subscribe();
    }

    public function destroy($channelSlug, Thread $thread)
    {
        $thread->unsubscribe();
    }
}
