<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : DealerService.php
 **/

namespace App\Services;


use App\Models\Dealers;
use App\Models\DealersUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Cornford\Googlmapper\Facades\MapperFacade as Mapper;
use Illuminate\Support\Facades\Input;
use Quantum\base\Models\Categories;
use Quantum\base\Services\NewsService;
use Quantum\calendar\Models\Calendar;
use Quantum\calendar\Services\CalendarService;

class DealersService
{
    private $markerNumber = 0;
    private $markers = [];

    public function __construct()
    {
        \Config::set('googlmapper.GOOGLE_API_KEY', \Settings::get('google_map_api_key'));
    }

    public function getDealers($addmap = false, $type = 'auctioneer', $mapLocation=null, $mapZoom=null)
    {
        $dealers = $this->getCachedDealersPaginate($type);
        if($addmap) $this->addMap($dealers, $mapLocation, $mapZoom);
        return $dealers;
    }

    
    public function searchDealers($request, $addmap = false, $type = 'auctioneer', $mapLocation=null, $mapZoom=null)
    {
        
        if($dealers = $this->cachedSearchDealers($request, $type))
        {
            if($addmap) $this->addMap($dealers, $mapLocation, $mapZoom);
        }
        return $dealers;
    }

    public function cachedSearchDealers($request, $type = 'auctioneer')
    {

        $page = Input::get('page', 1);
        if(\Auth::user()) {
            $cacheprefix = 'members';
        } else {
            $cacheprefix = 'public';
        }

        $cacheKey = md5($request->name.$request->location.$request->auctioneer.serialize($request->categories)).$page;

        if (Cache::tags([$cacheprefix.'_search_'.$type])->has($cacheKey)) {
            return Cache::tags([$cacheprefix.'_search_'.$type])->get($cacheKey);
        }


        $dealers = Dealers::SearchName($request->name)->SearchLocation($request->location)->searchAuctioneer($request->auctioneer)->searchCategories($request->categories)->active()->orderBy('name', 'ASC')->paginate(20);
        Cache::tags([$cacheprefix.'_search_'.$type])->put($cacheKey, $dealers, 10);
        return $dealers;
    }
    
    public function getCachedDealers($type)
    {
        if (Cache::has('dealers_'.$type)) {
            return Cache::get('dealers_'.$type);
        }

        $dealers = Dealers::with(['categories' => function($query) {
            $query->allowed();
        }, 'media'])->where('type', $type)->active()->orderBy('name', 'ASC')->get();
        Cache::forever('dealers_'.$type, $dealers);
        return $dealers;
    }

    private function getCachedDealersPaginate($type)
    {
        $page = Input::get('page', 1);

        if(\Auth::user()) {
            $cacheprefix = 'members';
        } else {
            $cacheprefix = 'public';
        }
        if (Cache::tags([$cacheprefix.'_dealers_'.$type])->has('page_'.$page)) {
            return Cache::tags([$cacheprefix.'_dealers_'.$type])->get('page_'.$page);
        }

        $dealers = Dealers::where('type', $type)->active()->orderBy('name', 'ASC')->paginate(20);
        Cache::tags([$cacheprefix.'_dealers_'.$type])->forever('page_'.$page, $dealers);
        return $dealers;
    }

    public function getDealerCounty($type='auctioneer')
    {
        return Cache::rememberForever('dealer_'.$type.'_county', function () use($type) {
                return Dealers::where('type', $type)->where('county', '!=', 'null')->where('county', '!=', '0')->where('county', '!=', '')->active()->groupBy('county')->pluck('county', 'county')->toArray();
        });
    }

    public function getDealer($slug, $addMap=true)
    {
        if (Cache::tags(['dealer_details'])->has('dealer_'.$slug)) {
            $dealer = Cache::tags(['dealer_details'])->get('dealer_'.$slug);
        } else {
            $dealer = Dealers::with(['media', 'categories' => function($query) {
                $query->allowed();
            }, 'categories.parent', 'features'])->where('slug', $slug)->active()->firstOrFail();
            Cache::tags(['dealer_details'])->forever('dealer_'.$slug, $dealer);
        }
        if($addMap)
        {
            $this->addDealerMap($dealer);
            $this->addDealerMap($dealer, true);
        }

        return $dealer;
    }

    public function getDealerById($id, $classified=false)
    {

        if (Cache::tags(['dealer_details'])->has('dealer_'.$id)) {
            $dealer = Cache::tags(['dealer_details'])->get('dealer_'.$id);
        } else {
            $dealer = Dealers::where('id', $id)->active()->firstOrFail();
            Cache::tags(['dealer_details'])->forever('dealer_'.$id, $dealer);
        }

        if(!$classified)
        {
            $this->addDealerMap($dealer);
            $this->addDealerMap($dealer, true);
        }

        return $dealer;
    }

    public function addDealerMap($dealer, $widget=false, $useCity=null)
    {
        if(!$useCity)
        {
            if(isset($dealer->longitude) && isset($dealer->latitude))
            {
                if($widget)
                {
                    $this->addSingleMap($dealer, 8);
                } else {
                    if($dealer->has_streetview)
                    {
                        Mapper::streetview($dealer->latitude, $dealer->longitude, 1, 1);
                    } else {
                        $this->addSingleMap($dealer);
                    }
                }

            }
        } else {
            if(isset($dealer->city) && $dealer->city != '')
            {
                if($widget)
                {
                    $this->addSingleMapLocation($dealer, 9);
                } else {
                    $this->addSingleMapLocation($dealer);
                }

            }
        }

    }

    private function addMap($dealers, $location='Leicester', $zoom=6)
    {
        if(!$location) $location='Leicester';
        if(!$zoom) $zoom=6;

        Mapper::location($location)->map(['zoom' => $zoom, 'marker' => false]);
        $this->markerNumber = 0;
        $this->markers = [];
        foreach ($dealers as $dealer)
        {
            $this->addMapItem($dealer);
        }
    }

    private function addSingleMap($dealer, $zoom=15)
    {
        Mapper::map($dealer->latitude, $dealer->longitude, ['zoom' => $zoom, 'marker' => false]);
        $this->addMapItem($dealer);
    }

    private function addSingleMapLocation($dealer, $zoom=15)
    {
        try {
            if($dealer->city != '' && $dealer->county != '')
            {
                Mapper::location(ucfirst(strtolower($dealer->city)) . ',' . ucfirst(strtolower($dealer->county)))->map(['zoom'   => $zoom, 'marker' => false]);
            } elseif($dealer->city != '' && $dealer->county == ''){
                Mapper::location(ucfirst(strtolower($dealer->city)))->map(['zoom' => $zoom, 'marker' => false]);
            }
        } catch (\Exception $e) {
        }

    }

    private function addMapItem($dealer)
    {

        if(isset($dealer->longitude) && isset($dealer->latitude))
        {
            if($dealer->longitude == '' || is_null($dealer->longitude)) return;
            if($dealer->latitude == '' || is_null($dealer->latitude)) return;
            if($dealer->logo != '')
            {
                $content = "<div class='text-center'><a href='".url('/members/auctioneer/'.$dealer->slug)."'><img alt='$dealer->name' src='".url('/images/dealers/'.$dealer->id.'/thumb150-'.$dealer->logo)."'></a><br><h5><a href='".url('/members/auctioneer/'.$dealer->slug)."'>".$dealer->name."</a></h5><br><i class='far fa-map-marker-alt'></i> ".$this->addDealerAddress($dealer)."</div>";
            } else {
                $content = "<div class='text-center'><h5><a href='".url('/members/auctioneer/'.$dealer->slug)."'>".$dealer->name."</a></h5><br><i class='far fa-map-marker-alt'></i> ".$this->addDealerAddress($dealer)."</div>";
            }

            $this->markers[$dealer->id] = $this->markerNumber;
            $this->markerNumber ++;
            Mapper::informationWindow($dealer->latitude,
                $dealer->longitude,
                $content,
                ['markers' => ['title' => $dealer->name,
                               'animation' => 'DROP',
                               'eventMouseOver' => 'infowindow.setContent("'.$content.'"); infowindow.open(map, this);',
                               'eventMouseOut'  => 'infowindow.close()']
                ]);
        }
    }

    public function addEventMap($dealer, $event)
    {
        if($event->meta->latitude && $event->meta->latitude != '' && $event->meta->longitude && $event->meta->longitude != '')
        {
            Mapper::map($event->meta->latitude, $event->meta->longitude, ['zoom' => 15, 'marker' => false]);

            $content = "<div class='text-center'><h5><a href='".url('/members/auctioneer/'.$dealer->slug)."'>".$dealer->name."</a></h5><br><i class='far fa-map-marker-alt'></i> ".$this->addDealerAddress($event->meta)."</div>";

            Mapper::informationWindow($event->meta->latitude,
                $event->meta->longitude,
                $content,
                ['markers' => ['title' => $dealer->name,
                               'animation' => 'DROP',
                               'eventMouseOver' => 'infowindow.setContent("'.$content.'"); infowindow.open(map, this);',
                               'eventMouseOut'  => 'infowindow.close()']
                ]);
        }
    }

    private function addDealerAddress($dealer)
    {
        if(\Auth::guest()) return "<a href='".url('/register')."'>Registered Only</a>";
        return str_limit($this->tidyText($dealer->address), 50);
    }

    private function tidyText($string)
    {
        return str_replace(array("\n\r", "\n", "\r"), '', $string);
    }

    public function getMarkers()
    {
        return $this->markers;
    }

    public function setMarkers($dealers)
    {
        if(count($this->markers) == 0) return $dealers;
        foreach($this->markers as $key => $marker)
        {
            $dealer = $dealers->where('id', $key)->first();
            $dealer->markerNumber = $marker;
        }
        return $dealers;
    }

    public function dealerSelectList($type='all')
    {
        if($type == 'all')
        {
            return Cache::rememberForever('dealer_list_'.$type, function () use ($type) {
                return Dealers::orderBy('name', 'ASC')->active()->pluck('name', 'slug');
            });
        }
        return Cache::rememberForever('dealer_list_'.$type, function () use ($type) {
            return Dealers::where('type', $type)->active()->orderBy('name', 'ASC')->pluck('name', 'slug');
        });
    }

    public function cachedCategoryList()
    {
        return Cache::rememberForever('categoryList', function () {
            $categories = Categories::whereHas('children')->with(['children' => function($query) {
                $query->where('slug', '!=', 'uncategorised')
                    ->active()
                    ->orderBy('name', 'ASC');
            }])->where('name', '!=', 'Blog')->orderBy('name', 'ASC')->active()->get();

            $catList = [];
            foreach ($categories as $category)
            {
                $catList[$category->name] = $category->children->pluck('name', 'id');
            }
            return $catList;
        });
    }

    public function cachedSearchCategories($request)
    {
        $cachekey = md5(serialize($request->categories));
        if (Cache::tags(['search_categories'])->has($cachekey)) {
            $searchCategories = Cache::tags(['search_categories'])->get($cachekey);
        } else {
            $searchCategories = Categories::whereIn('id', $request->categories)->active()->pluck('name');
            Cache::tags(['search_categories'])->put($cachekey, $searchCategories, 10);
        }
        return $searchCategories;
    }

    public function userFavouriteToggle($dealerId)
    {
        $dealer = $this->getDealer($dealerId, false);
        $user = \Auth::user();

        if(DealersUser::where('user_id', $user->id)->where('dealers_id', $dealer->id)->first())
        {
            DealersUser::where('user_id', $user->id)->where('dealers_id', $dealer->id)->delete();
            $message['type'] = 'remove';
            $message['message'] = 'Auctioneer removed from favourites';
        } else {
            DealersUser::create([
                'user_id' => $user->id,
                'dealers_id' => $dealer->id
            ]);
            $message['type'] = 'add';
            $message['message'] = 'Auctioneer added to favourites';
        }
        \Cache::forget('favDealer_'.$user->id);
        return $message;
    }

    public function dealerRedirectCategory($dealer)
    {
        $newsService = new NewsService();
        $ConfigRedirect = config('categories.redirect');
        $snippets = [];
        $snippets['snippet'] = [];
        $snippets['catCount'] = $dealer->categories->count();
        $member = \Auth::check();
        foreach ($dealer->categories as $category => $categories)
        {
            if(array_key_exists($category, $ConfigRedirect))
            {

                if($snippets['catCount'] > 1)
                {
                    if($member) {
                        array_push($snippets['snippet'], $newsService->getSnippet($ConfigRedirect[$category]['member-partial']));
                    } else {
                        array_push($snippets['snippet'], $newsService->getSnippet($ConfigRedirect[$category]['public-partial'], 'public'));
                    }
                } else {
                    if($member) {
                        array_push($snippets['snippet'], $newsService->getSnippet($ConfigRedirect[$category]['member']));
                    } else {
                        array_push($snippets['snippet'], $newsService->getSnippet($ConfigRedirect[$category]['public'], 'public'));
                    }
                }

            }
        }
        if(count($snippets['snippet']) == 0) return false;
        $snippets = $this->snippetReplacement($snippets, $dealer);
        return $snippets;
    }

    private function snippetReplacement($snippets, $dealer)
    {
        foreach($snippets['snippet'] as $key => $snippet)
        {
            $snippet->content = str_replace('[dealerName]', $dealer->name, $snippet->content);
            $snippet->content = str_replace('[dealerSlug]', $dealer->slug, $snippet->content);
            $snippets['snippet'][$key] = $snippet;
        }
        return $snippets;
    }

    public function getSnippet($title, $area='members')
    {
        $newsService = new NewsService();
        return $newsService->getSnippet($title, $area);
    }

    public function saveProblem($request, $dealer)
    {
        $dealer->problem()->create([
            'user_id' => \Auth::user()->id,
            'about' => $request->about
        ]);
        \Cache::forget('problemCount');
    }

    public function getUpcomingEvents($dealer, $days=7)
    {
        if($days < 1 || !is_integer($days)) return false;
        if(!$dealer || !isset($dealer->id)) return false;

        if (Cache::has('upcomingEvents_'.$dealer->id.$days)) {
            return Cache::get('upcomingEvents_'.$dealer->id.$days);
        }

        $calendarService = new CalendarService();
        $calendarService->addEventType('App\Models\Dealers', $dealer->id);

        $dealerEvents = [];

        for ($i = 0; $i <= $days - 1; $i++) {
            $searchDay = Carbon::now()->addDays($i);
            $events = $calendarService->getDay($searchDay->format('Y-n-j'));
            $events->eventdate = $searchDay;
            if(!is_null($events) && count($events) > 0) $dealerEvents[$searchDay->format('D, M jS, Y')] = $events;
        }
        Cache::put('upcomingEvents_'.$dealer->id.$days, $dealerEvents, 60);
        return $dealerEvents;
    }

    public function getEvent($dealer, $eventId)
    {
        $event = Cache::remember('dealerEvent_'.$dealer->id.$eventId, 10, function () use($dealer, $eventId){
            return Calendar::with('meta')->where('cal_eventable_id', $dealer->id)->where('cal_eventable_type', 'App\Models\Dealers')->where('slug', $eventId)->where('status', 'active')->firstOrFail();
        });

        return $event;
    }


    public function clearCache()
    {
        \App\Services\CacheService::clearDealers();
    }
 
}