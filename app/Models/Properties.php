<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
#use Quantum\calendar\Traits\CalEventable;
#use Cviebrock\EloquentSluggable\Sluggable;

class Properties extends Model
{
	#use CalEventable, Sluggable;
	
	protected $table = 'properties';

	protected $connection = 'mysql';

	//protected $fillable = [];
	



	public static function getAll()
	{
		return 'App\Models GetALL';
	}
	
}
