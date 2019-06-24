<?php

namespace App\Policies;

use App\Reply;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReplyPolicy
{
    use HandlesAuthorization;

    /**
     * Determinate if the authenticated user has permission to update a reply.
     *
     * @param  User  $user
     * @param  Reply  $reply
     * @return bool
     */
    public function update(User $user, Reply $reply)
    {
        return $reply->user_id == $user->id;
    }

    /**
     * Determine if the authenticated user has permission to create a new reply.
     * @param  User  $user
     * @param  Reply  $reply
     * @return bool
     */
    public function create(User $user)
    {
        $lastReply = $user->fresh()->lastReply;

        if (!$lastReply) {
            return true;
        }

        return !$lastReply->wasJustPublished();
    }
}
