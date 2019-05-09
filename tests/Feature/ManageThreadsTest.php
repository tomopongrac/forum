<?php

namespace Tests\Feature;

use App\Channel;
use App\Reply;
use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageThreadsTest extends TestCase
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

    /** @test */
    public function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_thread_requires_a_valid_channel()
    {
        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $channel = create(Channel::class);
        $this->publishThread(['channel_id' => $channel->id + 1])
            ->assertSessionHasErrors('channel_id');
    }

    /** @test */
    public function unauthorized_users_may_not_delete_threads()
    {
        $thread = create(Thread::class);

        $this->delete(route('threads.destroy', $thread))
            ->assertRedirect(route('login'));

        $user = create(User::class);
        $this->actingAs($user)
            ->delete(route('threads.destroy', $thread))
            ->assertStatus(403);
    }

    /** @test */
    public function authorized_users_can_delete_threads()
    {
        $user = create(User::class);
        $thread = create(Thread::class, ['user_id' => $user->id]);
        $reply = create(Reply::class, ['thread_id' => $thread->id]);

        $this->actingAs($user);
        $response = $this->json('DELETE', route('threads.destroy', $thread));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('threads', $thread->toArray());
        $this->assertDatabaseMissing('replies', $reply->toArray());
    }

    private function publishThread($overrides = [])
    {
        $this->withExceptionHandling();

        $thread = make(Thread::class, $overrides);

        return $this->actingAs(create(User::class))
            ->post(route('threads.store'), $thread->toArray());
    }
}
