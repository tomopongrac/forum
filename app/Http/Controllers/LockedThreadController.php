<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;

class LockedThreadController extends Controller
{
    public function store(Thread $thread)
    {
        $thread->lock();
    }
}
