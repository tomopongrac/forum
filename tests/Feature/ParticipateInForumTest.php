<?php

namespace Tests\Feature;

use App\Activity;
use App\Channel;
use App\Reply;
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

        $this->assertDatabaseHas('replies', ['body' => 'New reply']);
    }

    /** @test */
    public function a_reply_requires_a_body()
    {
        $this->withExceptionHandling();

        $channel = create(Channel::class);
        $thread = create(Thread::class, ['channel_id' => $channel->id]);
        $reply = make(Reply::class, ['body' => null]);

        $response = $this->actingAs(create(User::class))
            ->post(route('reply.store', ['channel' => $channel->slug, 'thread' => $thread->id]), $reply->toArray());

        $response->assertSessionHasErrors('body');
    }

    /** @test */
    public function unauthorized_users_cannot_delete_replies()
    {
        $reply = create(Reply::class);

        $this->delete(route('reply.destroy', $reply))
            ->assertRedirect(route('login'));

        $user = create(User::class);
        $this->actingAs($user)
            ->delete(route('reply.destroy', $reply))
            ->assertStatus(403);
    }

    /** @test */
    public function authorized_users_can_delete_replies()
    {
        $user = create(User::class);
        $reply = create(Reply::class, ['user_id' => $user->id]);

        $this->actingAs($user)
            ->delete(route('reply.destroy', $reply));

        $this->assertEquals(0, Activity::count());
        $this->assertDatabaseMissing('replies', $reply->toArray());
    }

    /** @test */
    public function authorized_users_can_delete_replies_which_is_favorited_and_than_activity_is_also_deleted()
    {
        $user = create(User::class);
        $reply = create(Reply::class, ['user_id' => $user->id]);

        $this->actingAs($user);
        $reply->favorite();
        $this->delete(route('reply.destroy', $reply));

        $this->assertEquals(0, Activity::count());
        $this->assertDatabaseMissing('replies', $reply->toArray());
    }

    /** @test */
    public function unauthorized_users_cannot_update_replies()
    {
        $reply = create(Reply::class);

        $this->patch(route('reply.update', $reply), [])
            ->assertRedirect(route('login'));

        $user = create(User::class);
        $this->actingAs($user)
            ->patch(route('reply.update', $reply), [])
            ->assertStatus(403);
    }

    /** @test */
    public function authorized_users_can_update_replies()
    {
        $user = create(User::class);
        $reply = create(Reply::class, ['user_id' => $user->id]);

        $updatedReply = 'Changed reply';
        $this->actingAs($user)
            ->patch(route('reply.update', $reply), ['body' => $updatedReply]);

        $this->assertDatabaseHas('replies', [
            'id' => $reply->id,
            'body' => $updatedReply,
        ]);
    }
}
