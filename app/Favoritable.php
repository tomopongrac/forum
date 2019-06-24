<?php

namespace App;

trait Favoritable
{
    /**
     * Boot the trait.
     */
    protected static function bootFavoritable()
    {
        static::deleting(function ($model) {
            $model->favorites->each(function ($favorite) {
                $favorite->delete();
            });
        });
    }

    /**
     * A reply can be favorited.
     *
     * @return bool
     */
    public function isFavorited()
    {
        return !!$this->favorites->where('user_id', auth()->id())->count();
    }

    /**
     * Fetch the favorited attribute as a property.
     *
     * @return bool
     */
    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }

    /**
     * Get the number of favorites for the reply.
     *
     * @return int
     */
    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }

    /**
     * A reply can be favorited.
     *
     * @return mixed
     */
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    /**
     * Favorite the curent reply.
     *
     * @return mixed
     */
    public function favorite()
    {
        $attributes = ['user_id' => auth()->id()];

        if (!$this->favorites()->where($attributes)->exists()) {
            return $this->favorites()->create($attributes);
        }
    }

    /**
     * Unfavorite the current reply.
     */
    public function unfavorite()
    {
        $attributes = ['user_id' => auth()->id()];

        if ($this->favorites()->where($attributes)->exists()) {
            $this->favorites()->where($attributes)->get()->each(function ($favorite) {
                $favorite->delete();
            });
        }
    }
}
