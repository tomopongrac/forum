<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LockThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function non_administrators_may_not_locked_threads()
    {
        $user = create(User::class);
        $thread = create(Thread::class, ['user_id' => $user->id]);

        $this->actingAs($user);
        $this->post(route('locked-threads.store', $thread))
            ->assertStatus(403);

        $this->assertFalse(!!$thread->fresh()->locked);
    }

    /** @test */
    public function administrators_may_lock_threads()
    {
        $user = factory(User::class)->states('administratorj')->create();
        $thread = create(Thread::class, ['user_id' => $user->id]);

        $this->actingAs($user);
        $this->post(route('locked-threads.store', $thread));

        $this->assertTrue(!!$thread->fresh()->locked, 'Failed asserting that the thread is locked.');
    }

    /** @test */
    public function once_locked_a_thread_may_not_receive_new_replies()
    {
        $thread = create(Thread::class);

        $thread->lock();
        $user= create(User::class);

        $this->actingAs($user);
        $this->post($thread->path().'/replies', [
            'body' => 'Foobar',
            'user_id' => $user->id,
        ])->assertStatus(422);
    }
}
