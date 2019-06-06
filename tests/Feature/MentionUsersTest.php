<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MentionUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function mentioned_users_in_a_reply_are_notified()
    {
        $john = create(User::class, ['name' => 'JohnDoe']);
        $jane = create(User::class, ['name' => 'JaneDoe']);
        $thread = create(Thread::class);
        $reply = make(Reply::class, [
            'body' => '@JaneDoe look at this.',
        ]);

        $this->be($john);
        $this->json('post', route('reply.store', ['channel' => $thread->channel->slug, 'thread' => $thread->id]),
            $reply->toArray());

        $this->assertCount(1, $jane->notifications);

    }
}
