<?php

namespace Tests\Feature;

use App\Channel;
use App\Reply;
use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_view_all_threads()
    {
        $thread = create(Thread::class);

        $response = $this->get(route('threads.index'));

        $response->assertSee($thread->title);
    }

    /** @test */
    public function a_user_can_view_single_thread()
    {
        $channel = create(Channel::class);
        $thread = create(Thread::class, ['channel_id' => $channel->id]);

        $response = $this->get(route('threads.show', ['channel' => $channel->slug, 'thread' => $thread]));

        $response->assertSee($thread->title);
    }

    /** @test */
    public function a_user_can_read_replies_that_associated_with_a_thread()
    {
        $channel = create(Channel::class);
        $thread = create(Thread::class, ['channel_id' => $channel->id]);
        $reply = create(Reply::class, ['thread_id' => $thread->id]);

        $response = $this->get(route('threads.show', ['channel' => $channel->slug, 'thread' => $thread]));

        $response->assertSee($reply->body);
    }
}
