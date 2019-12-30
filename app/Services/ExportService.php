<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : ExportService.php
 **/

namespace App\Services;


use App\Models\DealerCategories;
use App\Models\Dealers;
use App\Models\DealersFeatures;

class ExportService
{
    public function getCategories()
    {
        return DealerCategories::with('children')->whereNull('parent_id')->whereNull('user_id')->orderBy('name', 'ASC')->get();
    }

    public function getFeatures()
    {
        return DealersFeatures::orderBy('id', 'ASC')->get();
    }

    public function getDealers()
    {
        return Dealers::with(['categories', 'media', 'features', 'addresses'])->orderBy('id', 'ASC')->get();
    }
}