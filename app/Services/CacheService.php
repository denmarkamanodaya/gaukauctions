<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : CacheService.php
 **/

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheService
{

    public static function clearDealers()
    {
        Cache::tags('public_dealers_auctioneer')->flush();
        Cache::tags('members_dealers_auctioneer')->flush();
        Cache::forget('dealers_auctioneer');
        Cache::forget('dealer_auctioneer_county');
        Cache::tags('dealer_details')->flush();
        Cache::forget('dealer_list_auctioneer');
        Cache::forget('dealer_list_classified');
        Cache::forget('happeningCounty');
        Cache::tags('members_search_auctioneer')->flush();
        Cache::tags('public_search_auctioneer')->flush();

    }

    public static function clearCategories()
    {
        Cache::tags('search_categories')->flush();
        Cache::forget('categoryList');
    }

}