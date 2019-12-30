<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DealerFeaturedImageImport extends Model
{
    protected $table = 'dealers_featured_image';

    protected $connection = 'mysql';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['dealer_id', 'dealers_image_import_id'];

    public function media()
    {
        return $this->belongsTo('App\Models\DealerImageImport', 'dealers_image_import_id', 'post_id');
    }

}
