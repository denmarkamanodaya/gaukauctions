<?php

namespace App\Shortcodes;

use App\Models\Dealers;
use App\Services\DealerCategoryService;
use App\Services\DealersService;
use App\Services\GavelBoxService;
use Illuminate\Support\Facades\View;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class Auctioneers
{

    public static function popularAuctions(ShortcodeInterface $s)
    {
        $total = $s->getParameter('amount') ? $s->getParameter('amount') : 20;
        $dealerService = new DealersService();
        $dealers = $dealerService->getCachedDealers('auctioneer');
        $dealers = $dealers->where('logo', '!=', '')->shuffle()->take($total);
        $favouriteList = [];
        if(\Auth::check())
        {
            $gavelBoxService = new GavelBoxService();
            $favouriteList = $gavelBoxService->favouriteList();
        }

        $view = View::make('Shortcodes.popularAuctions', compact('dealers', 'total', 'favouriteList'));
        $widget = $view->render();
        //echo $widget; exit;
        return $widget;

    }

    public static function categorySearchBox(ShortcodeInterface $s)
    {
        $categoryService = new DealerCategoryService();
        $catList = ['0' => ''] + $categoryService->cachedCategoryList(config('categories.except'), false);
        $view = View::make('Shortcodes.categorySearchBox', compact('catList'));
        $widget = $view->render();
        return $widget;
    }

    public static function happeningAuctions(ShortcodeInterface $s)
    {
        $cityArray = [
            [
                'name' => 'City of Birmingham',
                'slug' => 'city-of-birmingham',
                'image' => '/images/index/birmingham-auctions-570x455.jpg'
            ],[
                'name' => 'City of Bristol',
                'slug' => 'city-of-bristol',
                'image' => '/images/index/bristol-auctions-1024x512-570x228.jpg'
            ],[
                'name' => 'City of Cardiff',
                'slug' => 'city-of-cardiff',
                'image' => '/images/index/cardiff-auctions-270x197.jpg'
            ],[
                'name' => 'City of Dublin',
                'slug' => 'city-of-dublin',
                'image' => '/images/index/dublin-top-ten-hapenny-bridge-bg-1024x615-270x197.jpg'
            ],[
                'name' => 'City of Glasgow',
                'slug' => 'city-of-glasgow',
                'image' => '/images/index/glasgow-auctions-270x197.jpg'
            ],[
                'name' => 'City of Liverpool',
                'slug' => 'city-of-liverpool',
                'image' => '/images/index/liverpool-auctions-270x197.jpg'
            ],[
                'name' => 'City of London',
                'slug' => 'city-of-london',
                'image' => '/images/index/london-auction-270x197.jpg'
            ],[
                'name' => 'City of Manchester',
                'slug' => 'city-of-manchester',
                'image' => '/images/index/manchester-auctions-1024x576-270x197.jpg'
            ]
        ];

        $cityCount = \Cache::rememberForever('happeningCounty', function () use($cityArray) {
            return Dealers::select('county', \DB::raw('count(*) as total'))
                ->groupBy('county')
                ->WhereIn('county', array_pluck($cityArray, 'name'))
                ->get();
        });

        $cityCount = $cityCount->pluck('total', 'county')->toArray();
        //dd($cityCount, $cityArray);

        $view = View::make('Shortcodes.happeningAuctions', compact('cityCount', 'cityArray'));
        $widget = $view->render();
        return $widget;
    }


}