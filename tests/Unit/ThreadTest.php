<?php

namespace Tests\Unit;

use App\Notifications\ThreadWasUpdated;
use App\Thread;
use App\User;
use App\Channel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThreadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_thread_has_creator()
    {
        $thread = factory(Thread::class)->create();

        $this->assertInstanceOf(User::class, $thread->creator);
    }

    /** @test */
    public function a_thread_has_a_path()
    {
        $thread = create(Thread::class);

        $this->assertEquals(
            "/threads/{$thread->channel->slug}/{$thread->slug}", $thread->path()
        );
    }

    /** @test */
    public function a_thread_has_replies()
    {
        $thread = factory(Thread::class)->create();

        $this->assertInstanceOf(Collection::class, $thread->replies);
    }

    /** @test */
    public function a_thread_can_add_a_reply()
    {
        $thread = factory(Thread::class)->create();

        $thread->addReply([
            'body' => 'Some body',
            'user_id' => 1,
        ]);

        $this->assertCount(1, $thread->replies);
    }

    /** @test */
    public function a_thread_notifies_all_registered_subscribers_when_a_reply_is_added()
    {
        Notification::fake();

        $thread = factory(Thread::class)->create();
        $user = create(User::class);

        $this->be($user);
        $thread->subscribe();
        $thread->addReply([
            'body' => 'Some body',
            'user_id' => 999,
        ]);

        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
    }

    /** @test */
    public function a_thread_belongs_to_channel()
    {
        $thread = create(Thread::class);

        $this->assertInstanceOf(Channel::class, $thread->channel);
    }

    /** @test */
    public function a_thread_can_be_subscribed_to()
    {
        $thread = create(Thread::class);

        $thread->subscribe($userId = 1);

        $this->assertEquals(1, $thread->subscriptions()->where('user_id', $userId)->count());
    }

    /** @test */
    public function a_thread_can_be_unsubscribed_from()
    {
        $thread = create(Thread::class);

        $thread->subscribe($userId = 1);
        $thread->unsubscribe($userId = 1);

        $this->assertEquals(0, $thread->subscriptions()->where('user_id', $userId)->count());
    }

    /** @test */
    public function it_knows_if_the_authenticated_user_is_subscribed_to_it()
    {
        $thread = create(Thread::class);
        $user = create(User::class);

        $this->be($user);

        $this->assertFalse($thread->isSubscribedTo);

        $thread->subscribe();

        $this->assertTrue($thread->isSubscribedTo);
    }

    /** @test */
    public function a_thread_can_check_if_authenticated_user_has_read_all_replies()
    {
        $thread = create(Thread::class);
        $user = create(User::class);

        $this->be($user);

        $this->assertTrue($thread->hasUpdatesFor($user));

        $user->read($thread);

        $this->assertFalse($thread->hasUpdatesFor($user));
    }

    /** @test */
    public function a_thread_records_each_visit()
    {
        $thread = make(Thread::class, ['id' => 1]);

        $thread->visits()->reset();

        $this->assertSame(0, $thread->visits()->count());

        $thread->visits()->record();
        $this->assertEquals(1, $thread->visits()->count());

        $thread->visits()->record();
        $this->assertEquals(2, $thread->visits()->count());
    }
}
