<?php

namespace App\Events;

use App\Reply;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ThreadReceviedNewReply
{
    use Dispatchable, SerializesModels;

    public $reply;

    /**
     * Create a new event instance.
     *
     * @param  Reply  $reply
     */
    public function __construct(Reply $reply)
    {
        $this->reply = $reply;
    }
}
