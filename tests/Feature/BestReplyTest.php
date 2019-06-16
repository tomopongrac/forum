<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BestReplyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_thread_creator_may_mark_any_reply_as_the_best_reply()
    {
        $user = create(User::class);
        $thread = create(Thread::class, ['user_id' => $user->id]);
        $replies = create(Reply::class, ['thread_id' => $thread->id], 2);

        $this->assertFalse($replies[1]->isBest());

        $this->actingAs($user);
        $this->postJson(route('best-replies.store', [$replies[1]->id]));

        $this->assertTrue($replies[1]->fresh()->isBest());
    }

    /** @test */
    public function only_the_thread_creator_may_mark_a_reply_as_best()
    {
        $userCreator = create(User::class);
        $thread = create(Thread::class, ['user_id' => $userCreator->id]);
        $replies = create(Reply::class, ['thread_id' => $thread->id], 2);

        $user = create(User::class);
        $this->actingAs($user);
        $this->postJson(route('best-replies.store', [$replies[1]->id]))
            ->assertStatus(403);

        $this->assertFalse($replies[1]->fresh()->isBest());
    }

    /** @test */
    public function if_a_best_reply_is_deleted_then_the_thread_is_properly_updated_to_reflect_that()
    {
        $user = create(User::class);
        $thread = create(Thread::class);
        $reply = create(Reply::class, ['user_id' => $user->id, 'thread_id' => $thread->id]);
        $thread->markBestReply($reply);

        $this->actingAs($user);
        $this->deleteJson(route('reply.destroy', [$reply]));

        $this->assertNull($thread->fresh()->best_reply_id);
    }
}
