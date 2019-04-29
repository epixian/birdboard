<?php

namespace App;

trait RecordsActivity
{
    public $oldAttributes = [];

    public static function bootRecordsActivity()
    {
        foreach (self::recordableEvents() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity("{$event}_" . strtolower(class_basename($model)));
            });

            if ($event === 'updated') 
            {
                static::updating(function ($model) {
                    $model->oldAttributes = $model->getOriginal();
                });
            }
        }
    }

    public function recordActivity($description)
    {
        $this->activity()->create([
            'user_id' => ($this->project ?? $this)->owner->id,
            'project_id' => class_basename($this) === 'Project' ? $this->id : $this->project->id,
            'description' => $description,
            'changes' => $this->activityChanges()
        ]);
    }

    public function activityOwner()
    {
        $project = $this->project ?? $this;

        return $project->owner;
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject')->latest();
    }

    public function activityChanges()
    {
        if ($this->wasChanged()) {
            return [
                'before' => array_except(
                    array_diff($this->oldAttributes, $this->getAttributes()), 'updated_at'
                ),
                'after' => array_except(
                    $this->getChanges(), 'updated_at'
                )
            ];
        }
    }

    protected static function recordableEvents() 
    {
        return isset(static::$recordableEvents)
            ? static::$recordableEvents
            : ['created', 'updated'];
    }
}