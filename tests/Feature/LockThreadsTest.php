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
