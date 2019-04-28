<?php

namespace Tests\Feature;

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
        $this->withoutExceptionHandling();
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->post(route('threads.store'), []);
    }

    /** @test */
    public function guests_cannot_see_the_create_thread_page()
    {
        $this->get(route('threads.create'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_can_create_new_forum_threads()
    {
        $user = create(User::class);

        $thread = make(Thread::class, [
            'title' => 'My new thread',
            'body' => 'Body of my new thread',
        ]);

        $this->actingAs($user)
            ->post(route('threads.store'), $thread->toArray());

        $this->get(route('threads.show', ['thread' => $thread]))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
