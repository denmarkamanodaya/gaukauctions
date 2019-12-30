<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : GarageService.php
 **/

namespace App\Services;


use App\Filters\GarageFilters;
use App\Filters\VehicleFilters;
use App\Models\DealersUser;
use App\Models\GarageFeed;
use App\Models\Vehicles;
use Illuminate\Support\Facades\Cache;
use Laracasts\Flash\Flash;
use Quantum\base\Models\News;
use Quantum\base\Services\NewsService;

class GavelBoxService
{

    /**
     * @var DealerService
     */
    private $dealerService;

    /**
     * @var NewsService
     */
    private $newsService;

    public function __construct()
    {
        $this->dealerService = new DealersService();
        $this->newsService = new NewsService();
    }

    public function getPageSnippet($premium)
    {
        if($premium) return $this->newsService->getSnippet('GavelBox Index');
        return $this->newsService->getSnippet('GavelBox Free Index');
    }

    public function getCalendarPageSnippet()
    {
        return $this->newsService->getSnippet('GavelBox Calendar');
    }

    public function getFavouriteDealers()
    {
        $user = \Auth::user()->id;
        $dealers = Cache::remember('favDealer_'.$user, 30, function () use($user){
            $dealers = DealersUser::with(['dealers' => function($query) {
                $query->active();
            }])->where('user_id', $user)->get();
            $dealersColl = [];
            foreach ($dealers as $dealer)
            {
                array_push($dealersColl, $dealer->dealers);
            }
            return collect($dealersColl);
        });
        return $dealers;
    }

    public function favouriteList()
    {
        $dealers = $this->getFavouriteDealers();
        $favouriteList = $dealers->pluck('id')->toArray();
        return $favouriteList;
    }


}