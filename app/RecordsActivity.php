<?php

namespace App;

trait RecordsActivity
{
    /**
     * Boot the trait.
     */
    protected static function bootRecordsActivity()
    {
        foreach (self::getActivitiesToRecord() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }

        static::deleting(function ($model) {
            $model->activity()->delete();
        });
    }

    /**
     * Fetch all model events that require activity recording.
     *
     * @return array
     */
    protected static function getActivitiesToRecord()
    {
        return ['created'];
    }

    /**
     * Record new activity for the model.
     *
     * @param $event
     */
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

    /**
     * Fetch the activity relationship.
     *
     * @return mixed
     */
    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    /**
     * Determine the activity type.
     *
     * @param $event
     * @return string
     * @throws \ReflectionException
     */
    protected function getActivityType($event): string
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());

        return "{$event}_{$type}";
    }
}
