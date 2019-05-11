<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = ['type', 'user_id', 'subject_id', 'subject_type'];

    public function subject()
    {
        return $this->morphTo();
    }

    public static function feed($user, $take = 50)
    {
        return static::where('user_id', $user->id)
            ->latest()
            ->with('subject')
            ->take($take)
            ->get()
            ->groupBy(function ($acitivity) {
                return $acitivity->created_at->format('Y-m-d');
            });
    }
}
