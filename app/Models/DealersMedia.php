<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DealersMedia extends Model
{
    /**
     * The database table
     *
     * @var string
     */
    protected $table = 'dealer_media';

    protected $connection = 'mysql';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['dealer_id', 'name', 'type', 'area'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dealer()
    {
        return $this->belongsTo('App\Models\Dealers');
    }
}
