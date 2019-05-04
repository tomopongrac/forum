<?php

namespace Tests\Feature;

use App\Channel;
use App\Reply;
use App\Thread;
use App\User;
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

    /** @test */
    public function a_user_can_filter_threads_according_to_a_channel()
    {
        $this->withoutExceptionHandling();
        $channel = create(Channel::class);
        $threadInChannel = create(Thread::class, ['channel_id' => $channel->id]);
        $threadNotInChannel = create(Thread::class);

        $this->get(route('threads.channel', ['channel' => $channel->slug]))
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }

    /** @test */
    public function a_user_can_filter_threads_by_any_username()
    {
        $user = create(User::class, ['name' => 'JohnDoe']);
        $threadByJohn = create(Thread::class, ['user_id' => $user->id]);
        $threadNotByJohn = create(Thread::class);

        $this->get(route('threads.index').'?by='.$user->name)
            ->assertSee($threadByJohn->title)
            ->assertDontSee($threadNotByJohn->title);
    }
}
