<?php

namespace Tests\Feature;

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

        $this->post(route('reply.store', ['thread' => 1]), []);
    }

    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {
        $user = create(User::class);
        $thread = create(Thread::class);

        $this->be($user);
        $this->post(route('reply.store', ['thread' => $thread->id]), [
            'body' => 'New reply',
        ]);

        $this->get(route('threads.show', ['thread' => $thread]))
            ->assertSee('New reply');
    }
}
