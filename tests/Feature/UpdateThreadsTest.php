<?php

namespace Tests\Feature;

use App\Channel;
use App\Reply;
use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function unauthorized_users_may_not_update_threads()
    {
        $user = create(User::class);
        $thread = create(Thread::class, ['user_id' => $user->id]);
        $anotherUser = create(User::class);

        $this->actingAs($anotherUser);
        $this->patch($thread->path(), [])->assertStatus(403);
    }

    /** @test */
    public function a_thread_requires_a_title_and_body_to_be_updated()
    {
        $user = create(User::class);
        $thread = create(Thread::class, ['user_id' => $user->id]);

        $this->actingAs($user);
        $this->patch($thread->path(), [
            'title' => 'Changed',
            'body' => null,
        ])->assertSessionHasErrors('body');

        $this->patch($thread->path(), [
            'title' => null,
            'body' => 'Changed body',
        ])->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_can_be_updated_by_its_creator()
    {
        $user = create(User::class);
        $thread = create(Thread::class, ['user_id' => $user->id]);

        $this->actingAs($user);
        $this->patch($thread->path(), [
            'title' => 'Changed',
            'body' => 'Changed body',
        ]);

        $thread = $thread->fresh();
        $this->assertEquals('Changed', $thread->title);
        $this->assertEquals('Changed body', $thread->body);
    }
}
