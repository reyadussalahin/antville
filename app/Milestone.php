<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "description"
    ];


    /**
     * Get the Task that owns the task.
     *
     * @return Task::class instance
     */

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
