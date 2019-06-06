<?php

namespace App\Listeners;

use App\Events\ThreadReceviedNewReply;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifySubscribers
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
        $thread = $event->reply->thread;

        $thread->subscriptions
            ->where('user_id', '!=', $event->reply->user_id)
            ->each(function ($subscription) use ($event) {
                $subscription->notify($event->reply);
            });
    }
}
