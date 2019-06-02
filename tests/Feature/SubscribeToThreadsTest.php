<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscribeToThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_subscribe_to_threads()
    {
        $thread = create(Thread::class);
        $user = create(User::class);

        $this->be($user);
        $this->post(route('thread.subscription.store', ['channel' => $thread->channel->slug, 'thread' => $thread]));

        $this->assertCount(1, $thread->subscriptions);
    }
}
