<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Problems extends Model
{
    /**
     * The database table
     *
     * @var string
     */
    protected $table = 'problems';

    protected $connection = 'mysql';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'about', 'problemable_id', 'problemable_type'];


    public function problemable()
    {
        return $this->morphTo();
    }
}
