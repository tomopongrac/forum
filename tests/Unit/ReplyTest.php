<?php

namespace Tests\Unit;

use App\Reply;
use App\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReplyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_an_owner()
    {
        $reply = factory(Reply::class)->create();

        $this->assertInstanceOf(User::class, $reply->owner);
    }

    /** @test */
    public function it_knows_if_it_was_just_published()
    {
        $reply = create(Reply::class);

        $this->assertTrue($reply->wasJustPublished());

        $reply->created_at = Carbon::now()->subMonth();

        $this->assertFalse($reply->wasJustPublished());
    }

    /** @test */
    public function it_can_detect_all_mentioned_users_in_the_body()
    {
        $reply = create(Reply::class, [
            'body' => '@JaneDoe wants to talk to @JohnDoe',
        ]);

        $this->assertEquals(['JaneDoe', 'JohnDoe'], $reply->mentionedUsers());
    }

    /** @test */
    public function it_wraps_mentioned_usernames_in_the_body_within_anchor_tags()
    {
        $reply = create(Reply::class, [
            'body' => 'Hello @JaneDoe',
        ]);

        $this->assertEquals(
            'Hello <a href="/profiles/JaneDoe">@JaneDoe</a>',
            $reply->body
        );
    }

    /** @test */
    public function it_knows_it_is_the_best_reply()
    {
        $reply = create(Reply::class);

        $this->assertFalse($reply->isBest());

        $reply->thread->markBestReply($reply);

        $this->assertTrue($reply->fresh()->isBest());
    }
}
