<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_notification_is_prepared_when_a_subscribed_thread_receives_a_new_reply_that_is_not_by_current_user(
    )
    {
        $user = create(User::class);

        $this->be($user);
        $thread = create(Thread::class)->subscribe();

        $this->assertCount(0, auth()->user()->notifications);

        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'Some reply body',
        ]);

        $this->assertCount(0, auth()->user()->fresh()->notifications);

        $thread->addReply([
            'user_id' => create(User::class)->id,
            'body' => 'Some reply body',
        ]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }

    /** @test */
    public function a_user_can_fetch_their_unread_notifications()
    {
        $user = create(User::class);

        $this->be($user);
        $thread = create(Thread::class)->subscribe();

        $thread->addReply([
            'user_id' => create(User::class)->id,
            'body' => 'Some reply body',
        ]);

        $reponse = $this->getJson(route('user.notifications.index',
            ['user' => $user->name]))->json();

        $this->assertCount(1, $reponse);
    }

    /** @test */
    public function a_user_can_mark_a_notification_as_read()
    {
        $user = create(User::class);

        $this->be($user);
        $thread = create(Thread::class)->subscribe();

        $thread->addReply([
            'user_id' => create(User::class)->id,
            'body' => 'Some reply body',
        ]);

        $user = auth()->user();

        $this->assertCount(1, $user->unreadNotifications);

        $notificationId = $user->unreadNotifications->first()->id;

        $this->delete(route('user.notifications.destroy',
            ['user' => $user->name, 'notification' => $notificationId]));

        $this->assertCount(0, $user->fresh()->unreadNotifications);
    }
}
