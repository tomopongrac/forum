<?php

namespace App;

use App\Events\ThreadReceviedNewReply;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Thread extends Model
{
    use RecordsActivity;

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['creator', 'channel'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['isSubscribedTo'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'locked' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($thread) {
            $thread->replies->each(function ($reply) {
                $reply->delete();
            });
        });

        static::created(function ($thread) {
            $thread->slug = $thread->title;
            $thread->save();
        });
    }

    /**
     * A thread may have many replies.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * Get a string path for the thread.
     *
     * @return string
     */
    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->slug}";
    }

    /**
     * A thread belongs to creator.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * A thread is assigned a channel.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    /**
     * Add a reply to the thread.
     *
     * @param $reply
     * @return Model
     */
    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);

        event(new ThreadReceviedNewReply($reply));

        return $reply;
    }

    /**
     * Apply all relevant thread filters.
     *
     * @param $query
     * @param $filters
     * @return mixed
     */
    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    /**
     * Subscribe a user to the current thread.
     *
     * @param  int|null  $userId
     * @return $this
     */
    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id(),
        ]);

        return $this;
    }

    /**
     * Unsubscribe a user from the current thread.
     *
     * @param  int|null  $userId
     */
    public function unsubscribe($userId = null)
    {
        $this->subscriptions()->where('user_id', $userId ?: auth()->id())->delete();
    }

    /**
     * A thread can have many subscriptions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    /**
     * Determine if the current user is subscribed to the thread.
     *
     * @return bool
     */
    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }

    /**
     * Determine if the thread is updated since the user is last read it.
     *
     * @param  int|null  $user
     * @return bool
     * @throws \Exception
     */
    public function hasUpdatesFor($user = null)
    {
        $user = $user ?: auth()->user();

        return $this->updated_at > cache($user->visitedThreadCacheKey($this));
    }

    /**
     * Return object for record visits for thread.
     *
     * @return Visits
     */
    public function visits()
    {
        return new Visits($this);
    }

    /**
     * Get the route key name.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Set the proper slug attribute.
     *
     * @param $value
     */
    public function setSlugAttribute($value)
    {
        $slug = Str::slug($value);

        if (static::whereSlug($slug)->exists()) {
            $slug = "{$slug}-".$this->id;
        }

        $this->attributes['slug'] = $slug;
    }

    /**
     * Mark the given reply as best answer.
     *
     * @param  Reply  $reply
     */
    public function markBestReply(Reply $reply)
    {
        $this->best_reply_id = $reply->id;
        $this->save();
    }
}
