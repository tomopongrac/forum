<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddAvatarTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function only_members_can_add_avatars()
    {
        $this->json('POST', 'api/users/1/avatar')
            ->assertStatus(401);
    }

    /** @test */
    public function a_valid_avatar_must_be_provided()
    {
        $user = create(User::class);

        $this->be($user);
        $this->json('POST', 'api/users/'.auth()->id().'/avatar', [
            'avatar' => 'not-an-image',
        ])->assertStatus(422);
    }

    /** @test */
    public function a_user_may_add_an_avatar_to_their_profile()
    {
        Storage::fake('public');

        $user = create(User::class);

        $this->be($user);
        $this->json('POST', 'api/users/'.auth()->id().'/avatar', [
            'avatar' => $file = UploadedFile::fake()->image('avatar.jpg'),
        ]);

        $this->assertEquals('avatars/'.$file->hashName(), auth()->user()->avatar_path);

        Storage::disk('public')->assertExists('avatars/'.$file->hashName());
    }
}
