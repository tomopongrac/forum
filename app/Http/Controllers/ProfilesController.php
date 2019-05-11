<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class ProfilesController extends Controller
{
    public function show(User $user)
    {
        $activities = $user->activity()->latest()->with('subject')->get();

        return view('profiles.show', [
            'profileUser' => $user,
            'activities' => $activities,
        ]);
    }
}
