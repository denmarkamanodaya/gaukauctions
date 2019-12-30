<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Quantum\calendar\Traits\CalEventable;
use Cviebrock\EloquentSluggable\Sluggable;

class Dealers extends Model
{

    use CalEventable, Sluggable;
    /**
     * The database table
     *
     * @var string
     */
    protected $table = 'dealers';

    protected $connection = 'mysql';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'logo', 'address', 'country_id', 'postcode', 'town', 'phone', 'email', 'website', 'auction_url',
        'online_bidding_url', 'details', 'buyers_premium', 'directions', 'rail_station', 'notes', 'longitude', 'latitude', 'type', 'status', 'county', 'has_streetview', 'online_only', 'to_parse'];


    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function media()
    {
        return $this->hasMany('App\Models\DealersMedia', 'dealer_id');
    }

    public function scopeSearchLocation($query, $location)
    {
        if ($location) $query->where('county', $location);
    }

    public function scopeSearchName($query, $name)
    {
        if (!is_null($name)) $query->where('name', 'LIKE', '%'.$name.'%');
    }

    public function scopeSearchAuctioneer($query, $auctioneer)
    {
        if ($auctioneer) $query->where('slug', $auctioneer);
    }

    public function scopeSearchCategories($query, $categories)
    {
        if (is_array($categories)) $query->whereHas('categories', function($query) use($categories) {
            $query->whereIn('id', $categories)->allowed();
        });
    }

    public function scopeActive($query)
    {
        $query->where('status', 'active');
    }

    public function categories()
    {
        return $this->belongsToMany(\App\Models\DealerCategories::class, 'dealers_categories');
    }

    public function features()
    {
        return $this->belongsToMany(\App\Models\DealersFeatures::class, 'dealer_features')->orderBy('name', 'ASC');
    }

    public function country2()
    {
        //return $this->belongsTo(\Quantum\base\Models\Countries::class, 'mysqlMainRemote.countries');
        $relation = $this->belongsTo(\Quantum\base\Models\Countries::class);
        $relation->connection = 'mysqlMainRemote';
        return $relation;
    }

    public function remind()
    {
        return $this->morphMany('App\Models\Reminders', 'remindable');
    }

    public function problem()
    {
        return $this->morphMany('App\Models\Problems', 'problemable');
    }

    public function addresses()
    {
        return $this->hasMany('App\Models\DealersAddresses');
    }
}
