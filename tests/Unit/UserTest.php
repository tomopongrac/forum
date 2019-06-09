<?php

namespace Tests\Unit;

use App\Reply;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_fetch_their_most_recent_reply()
    {
        $user = create(User::class);
        $reply = create(Reply::class, ['user_id' => $user->id]);

        $this->assertEquals($reply->id, $user->lastReply->id);
    }

    /** @test */
    public function a_user_can_determine_their_avatar_path()
    {
        $user = create(User::class, ['avatar_path' => null]);

        $this->assertEquals('avatars/default.jpg', $user->avatar());

        $user->avatar_path = 'avatars/some-image.jpg';

        $this->assertEquals('avatars/some-image.jpg', $user->avatar());
    }
}
