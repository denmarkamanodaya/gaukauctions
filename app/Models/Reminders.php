<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reminders extends Model
{
    /**
     * The database table
     *
     * @var string
     */
    protected $table = 'reminders';

    protected $connection = 'mysql';

    protected $dates = ['remind_on'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['remind_on', 'status', 'about', 'remindable_id', 'remindable_type'];


    public function remindable()
    {
        return $this->morphTo();
    }

}
