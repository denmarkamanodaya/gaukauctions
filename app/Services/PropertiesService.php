<?php





namespace App\Services;


use App\Models\Properties;






class PropertiesService
{

	public function __construct()
	{

	}


	public function getProperties()
	{
		$properties = Properties::getAll();
		return $properties;
	}
}
