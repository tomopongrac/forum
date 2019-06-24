<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the given profile.
     *
     * @param  User  $user
     * @param  User  $signinUser
     * @return bool
     */
    public function update(User $user, User $signinUser)
    {
        return $user->id === $signinUser->id;
    }
}
