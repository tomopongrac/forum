<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favoritable, RecordsActivity;

    protected $fillable = ['body', 'user_id'];

    protected $with = ['owner', 'favorites'];

    protected $appends = ['favoritesCount', 'isFavorited'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }
}
