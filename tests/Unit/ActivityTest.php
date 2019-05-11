<?php

namespace Tests\Unit;

use App\Activity;
use App\Reply;
use App\Thread;
use App\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_records_activity_when_a_thread_is_created()
    {
        $user = create(User::class);

        $this->actingAs($user);
        $thread = create(Thread::class);


        $this->assertDatabaseHas('activities', [
            'user_id' => auth()->id(),
            'type' => 'created_thread',
            'subject_id' => $thread->id,
            'subject_type' => 'App\Thread',
        ]);

        $activity = Activity::first();

        $this->assertEquals($activity->subject->id, $thread->id);
    }

    /** @test */
    public function it_records_activity_when_a_reply_is_createad()
    {
        $user = create(User::class);

        $this->actingAs($user);
        $reply = create(Reply::class);

        $this->assertEquals(2, Activity::count());

        $this->assertDatabaseHas('activities', [
            'user_id' => auth()->id(),
            'type' => 'created_reply',
            'subject_id' => $reply->id,
            'subject_type' => 'App\Reply',
        ]);
    }

    /** @test */
    public function it_fetches_a_feed_for_any_user()
    {
        $user = create(User::class);
        $this->actingAs($user);

        create(Thread::class, ['user_id' => $user->id], 2);

        $activityForModify = auth()->user()->activity()->first();
        $activityForModify->created_at = Carbon::now()->subWeek();
        $activityForModify->save();

        $feed = Activity::feed($user);

        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->format('Y-m-d')
        ));

        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->subWeek()->format('Y-m-d')
        ));
    }
}
