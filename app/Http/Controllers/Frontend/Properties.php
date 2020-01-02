<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Requests\Frontend\AuctioneerSearchRequest;
use App\Services\RestrictUserService;
use App\Services\SeoService;
use App\Http\Controllers\Controller;
use App\Services\DealerCategoryService;
use Illuminate\Http\Request;

use App\Services\PropertiesService;

class Properties extends Controller
{
	private $propertiesService;

	public function __construct(PropertiesService $propertiesService)
	{
		$this->propertiesService = $propertiesService;
	}

	public function index()
	{
		$properties = $this->propertiesService->getProperties();
		dd($properties);
	}
}
