<?php

namespace Tests\Feature;

use App\Channel;
use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_may_not_create_threads()
    {
        $this->post(route('threads.store'), [])
            ->assertRedirect(route('login'));

        $this->get(route('threads.create'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_can_create_new_forum_threads()
    {
        $this->withoutExceptionHandling();
        $user = create(User::class);
        $channel = create(Channel::class);

        $thread = make(Thread::class, [
            'channel_id' => $channel->id,
            'title' => 'My new thread',
            'body' => 'Body of my new thread',
        ]);

        $this->actingAs($user)
            ->post(route('threads.store'), $thread->toArray());

        $threadId = Thread::where('title', $thread->title)->where('body', $thread->body)->first()->id;

        $this->get(route('threads.show', ['channel' => $channel->slug, 'thread' => $threadId]))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
