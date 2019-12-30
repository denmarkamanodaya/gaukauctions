<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Quantum\calendar\Traits\CalEventable;
use Cviebrock\EloquentSluggable\Sluggable;

class DealersAddresses extends Model
{

    /**
     * The database table
     *
     * @var string
     */
    protected $table = 'dealers_addresses';

    protected $connection = 'mysql';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['dealer_id', 'name','address', 'country_id', 'postcode', 'town', 'phone', 'auction_url',
        'longitude', 'latitude'];


    public function dealer()
    {
        return $this->belongsTo(\App\Models\Dealers::class);
    }
}
