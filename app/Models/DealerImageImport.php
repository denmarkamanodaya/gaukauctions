<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DealerImageImport extends Model
{
    protected $table = 'dealers_image_import';

    protected $connection = 'mysql';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['post_id', 'image', 'processed'];

}
