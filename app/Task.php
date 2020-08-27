<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "title", "description", "expected_within", "finished_at"
    ];


    /**
     * Get the user that owns the task.
     *
     * @return User::class instance
     */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the milestones for the task.
     *
     * @return array
     */
    
    public function milestones()
    {
        return $this->hasMany(Milestone::class);
    }
}
