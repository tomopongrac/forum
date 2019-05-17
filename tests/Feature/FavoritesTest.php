<?php

namespace Tests\Feature;

use App\Activity;
use App\Reply;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FavoritesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_can_not_favorite_anything()
    {
        $this->post(route('favorite.reply.store', ['reply' => 1]))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_can_favorite_any_reply()
    {
        $user = create(User::class);
        $reply = create(Reply::class);

        $this->actingAs($user)
            ->post(route('favorite.reply.store', ['reply' => $reply->id]));

        $this->assertCount(1, $reply->favorites);
    }

    /** @test */
    public function an_authenticated_user_can_unfavorite_any_reply()
    {
        $user = create(User::class);
        $reply = create(Reply::class);

        $this->actingAs($user);

        $reply->favorite();

        $this->assertCount(1, $reply->favorites);

        $this->delete(route('favorite.reply.destroy', ['reply' => $reply->id]));

        $this->assertEquals(0, Activity::count());
        $this->assertCount(0, $reply->fresh()->favorites);
    }

    /** @test */
    public function an_authenticated_user_may_only_favorite_a_reply_once()
    {
        $this->withoutExceptionHandling();
        $user = create(User::class);
        $reply = create(Reply::class);

        $this->actingAs($user);

        try {
            $this->post(route('favorite.reply.store', ['reply' => $reply->id]));
            $this->post(route('favorite.reply.store', ['reply' => $reply->id]));
        } catch (\Exception $e) {
            $this->fail('Did not except to insert the same record twice.');
        }

        $this->assertCount(1, $reply->favorites);
    }
}
