<?php

namespace App\Services;


use App\Models\Dealers;
use App\Models\Postcode;
use Quantum\calendar\Models\Calendar;

class GeoService
{

    public function updateAllAuctioneers()
    {
        $auctioneers = Dealers::all();

        foreach($auctioneers as $auctioneer)
        {
            if($auctioneer->postcode != '')
            {
                if($geo_location = Postcode::postcode($auctioneer->postcode)->first())
                {
                    $auctioneer->longitude  = $geo_location->longitude;
                    $auctioneer->latitude   = $geo_location->latitude;
                    $auctioneer->save();
                }
            }
        }
    }

    public function updateAllEvents()
    {
        $events = Calendar::with('meta')->tenant()->get();

        foreach($events as $event)
        {
            if($event->meta && $event->meta->postcode != '')
            {
                if($geo_location = Postcode::postcode($event->meta->postcode)->first())
                {
                    $event->meta->longitude  = $geo_location->longitude;
                    $event->meta->latitude   = $geo_location->latitude;
                    $event->meta->save();
                }
            }
        }
    }

}