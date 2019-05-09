<?php

namespace App;

trait RecordsActivity
{
    protected static function bootRecordsActivity()
    {
        static::created(function ($thread) {
            $thread->recordActivity('created');
        });
    }

    protected function recordActivity($event)
    {
        if (auth()->guest()) {
            return;
        }

        $this->activity()->create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event),
        ]);
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    protected function getActivityType($event): string
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());

        return "{$event}_{$type}";
    }
}
