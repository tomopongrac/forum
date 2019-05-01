<?php

namespace Tests\Feature;

use App\Channel;
use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipateInForumTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function unauthenticated_user_may_not_add_replies()
    {
        $this->withoutExceptionHandling();
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->post(route('reply.store', ['channel' => 'some-channel', 'thread' => 1]), []);
    }

    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {
        $user = create(User::class);
        $channel = create(Channel::class);
        $thread = create(Thread::class, ['channel_id' => $channel->id]);

        $this->be($user);
        $this->post(route('reply.store', ['channel' => $channel->slug, 'thread' => $thread->id]), [
            'body' => 'New reply',
        ]);

        $this->get(route('threads.show', ['channel' => $channel->slug, 'thread' => $thread]))
            ->assertSee('New reply');
    }
}
