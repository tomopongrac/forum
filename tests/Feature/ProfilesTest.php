<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfilesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_has_a_profile()
    {
        $this->withoutExceptionHandling();

        $user = create(User::class);

        $this->get(route('profiles.show', $user))
            ->assertSee(htmlentities($user->name, ENT_QUOTES));
    }

    /** @test */
    public function profiles_displays_all_threads_created_by_the_associated_user()
    {
        $user = create(User::class);
        $this->actingAs($user);

        $thread = create(Thread::class, ['user_id' => $user->id]);

        $this->get(route('profiles.show', $user))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
