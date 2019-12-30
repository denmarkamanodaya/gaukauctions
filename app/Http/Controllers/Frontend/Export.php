<?php

namespace App\Http\Controllers\Frontend;

use App\Services\ExportService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Export extends Controller
{

    /**
     * @var ExportService
     */
    private $exportService;

    private $apiKey = 'djfuYR73bbs3bClwOiUAfr502QLse';

    public function __construct(ExportService $exportService)
    {
        $this->exportService = $exportService;
    }

    private function checkKey($apiKey)
    {
        if($apiKey != $this->apiKey) abort(404);
    }

    public function categories($apiKey)
    {
        $this->checkKey($apiKey);
        return $this->exportService->getCategories();
    }

    public function features($apiKey)
    {
        $this->checkKey($apiKey);
        return $this->exportService->getFeatures();
    }

    public function dealers($apiKey)
    {
        $this->checkKey($apiKey);
        return $this->exportService->getDealers();
    }
}
