<?php

namespace App\Listeners;

use App\Events\ThreadReceviedNewReply;
use App\Notifications\YouWereMentioned;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyMentionedUsers
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ThreadReceviedNewReply  $event
     * @return void
     */
    public function handle(ThreadReceviedNewReply $event)
    {
        User::whereIn('name', $event->reply->mentionedUsers())
            ->each(function ($user) use ($event) {
                $user->notify(new YouWereMentioned($event->reply));
            });
    }
}
