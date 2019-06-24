<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use RecordsActivity;

    protected $fillable = ['user_id', 'favorited_id', 'favorited_type'];

    /**
     * Favorited bellongs to the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Fetch the model that was favorited.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function favorited()
    {
        return $this->morphTo();
    }
}
