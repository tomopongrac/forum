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

    /** @test */
    public function a_user_can_sort_threads_by_popularity()
    {
        $user = create(User::class);

        $threadWithTwoReplies = create(Thread::class);
        create(Reply::class, ['thread_id' => $threadWithTwoReplies->id], 2);

        $threadWithoutReplies = create(Thread::class);

        $threadWithThreeReplies = create(Thread::class);
        create(Reply::class, ['thread_id' => $threadWithThreeReplies->id], 3);

        $response = $this->get(route('threads.index').'?popular=1');
        $response->assertSeeInOrder([
            $threadWithThreeReplies->title, $threadWithTwoReplies->title, $threadWithoutReplies->title,
        ]);
    }

    /** @test */
    public function a_user_can_filter_threads_by_those_that_are_unanswered()
    {
        $threadWithoutReply = create(Thread::class);
        $threadWithReply = create(Thread::class);
        $reply = create(Reply::class, ['thread_id' => $threadWithReply->id]);

        $this->get(route('threads.index').'?unanswered=1')
            ->assertSee($threadWithoutReply->title)
            ->assertDontSee($threadWithReply->title);
    }

    /** @test */
    public function a_user_can_request_all_replies_for_a_given_thread()
    {
        $thread = create(Thread::class);
        $reply = create(Reply::class, ['thread_id' => $thread->id], 2);

        $response = $this->getJson(route('reply.index', ['channel' => $thread->channel->slug, 'thread' => $thread]))
            ->json();

        $this->assertCount(2, $response['data']);
        $this->assertEquals(2, $response['total']);
    }
}
