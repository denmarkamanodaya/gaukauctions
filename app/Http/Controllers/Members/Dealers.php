<?php

namespace App\Http\Controllers\Members;

use App\Services\DealerCategoryService;
use App\Services\DealersService;
use App\Services\GavelBoxService;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;


class Dealers extends Controller
{

    /**
     * @var DealersService
     */
    private $dealersService;

    public function __construct(DealersService $dealersService)
    {
        $this->dealersService = $dealersService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $clearFilters=null)
    {

        if($clearFilters && $clearFilters = 'clearFilters')
        {
            \Request::session()->forget('dealerSearchTerms');
            return redirect('/members/auctioneers');
        }
        $categoryService = new DealerCategoryService();
        if(isset($request->page))
        {
            $searchPaginate = $this->searchPaginated($request);
            if($searchPaginate) return $searchPaginate;
        }
        if(isset($request->location) || isset($request->category))
        {
            return $this->searchGetRequest($request);
        }
        $dealers = $this->dealersService->getDealers(true, 'auctioneer', 'Blackpool', 7);
        $dealers = $this->dealersService->setMarkers($dealers);
        $dealerCounties = $this->dealersService->getDealerCounty();
        $dealerList[0] = 'Select Auctioneer';
        $dealerList = array_merge($dealerList,$this->dealersService->dealerSelectList()->toArray());
        array_unshift($dealerCounties, 'Choose Location');
        $searches = ['0', ' '];
        foreach ($searches as $search)
        {
            if(($key = array_search($search, $dealerCounties)) !== false) {
                unset($dealerCounties[$key]);
            }
        }

        $catList = $categoryService->cachedCategoryList(config('categories.except'));
        $gavelBoxService = new GavelBoxService();
        $favouriteList = $gavelBoxService->favouriteList();

    return view('members.Dealers.indexFullMap', compact('dealers', 'dealerCounties', 'dealerList', 'catList', 'favouriteList'));
    }

    public function search(Requests\Members\AuctioneerSearchRequest $request)
    {
        $dealerSearchTerms = [
            'name' => $request->name,
            'location' => $request->location,
            'auctioneer' => $request->auctioneer,
            'categories' => $request->categories
        ];
        \Request::session()->put('dealerSearchTerms', $dealerSearchTerms);

        return $this->searchCommon($request);
    }

    public function searchGetRequest($request)
    {
        $input = $request->all();

        if(isset($input['name'])) $input['name'] = filter_var($input['name'], FILTER_SANITIZE_STRING);
        if(isset($input['location'])) $input['location'] = filter_var($input['location'], FILTER_SANITIZE_STRING);
        if(isset($input['auctioneer'])) $input['auctioneer'] = filter_var($input['auctioneer'], FILTER_SANITIZE_STRING);
        if(isset($input['category'])) $input['category'] = filter_var($input['category'], FILTER_SANITIZE_STRING);
        $request->replace($input);

        $validatedData = $request->validate([
            'name' => 'nullable|alpha_num_spaces|min:3',
            'location' => 'nullable|alpha-dash',
            'auctioneer' => 'nullable|alpha-dash',
            'category' => 'nullable|alpha-dash'
        ]);

        if(!$request->name && !$request->location && !$request->auctioneer && !$request->category) return redirect('/members/auctioneers');

        if($request->category && $request->category != '')
        {
            $categoryService = new DealerCategoryService();
            $category = $categoryService->getCategoryBySlug($request->category);
            $request->request->add(['categories' => [$category->id]]);
        }
        if($request->location && $request->location != '')
        {
            $location = str_replace('-', ' ', $request->location);
            $location = ucwords($location);
            $request->merge(['location' => $location]);
        }
        $dealerSearchTerms = [
            'name' => $request->name,
            'location' => $request->location,
            'auctioneer' => $request->auctioneer,
            'categories' => $request->categories
        ];
        \Request::session()->put('dealerSearchTerms', $dealerSearchTerms);

        return $this->searchCommon($request);
    }

    public function searchPaginated(Request $request)
    {
        $searchTerms = $request->session()->get('dealerSearchTerms');
        if(!$searchTerms) return;
        $request->request->add([
            'name' => isset($searchTerms['name']) ? $searchTerms['name']: null,
            'location' => isset($searchTerms['location']) ? $searchTerms['location']: null,
            'auctioneer' => isset($searchTerms['auctioneer']) ? $searchTerms['auctioneer'] : null,
            'categories' => isset($searchTerms['categories']) ? $searchTerms['categories'] : null
        ]);
        return $this->searchCommon($request);

    }

    private function searchCommon($request)
    {
        $categoryService = new DealerCategoryService();
        if($request->name == '' && $request->location == '0' && $request->auctioneer == '0' && is_null($request->categories)) return redirect('/members/auctioneers');
        $dealers = $this->dealersService->searchDealers($request, true, 'auctioneer', 'Blackpool', 7);
        $dealers = $this->dealersService->setMarkers($dealers);
        $dealerCounties = $this->dealersService->getDealerCounty();
        $dealerList[0] = 'Select Auctioneer';
        $dealerList = array_merge($dealerList,$this->dealersService->dealerSelectList()->toArray());
        $searchName = $request->name;
        $searchLocation = $request->location;
        $searchAuctioneer = $request->auctioneer;
        $searchCategories = $request->categories;
        if($request->categories)
        {
            $searchCategories = $categoryService->cachedSearchCategories($request->categories);
        }

        if($request->auctioneer && $request->auctioneer != '0')
        {
            $dealer = $this->dealersService->getDealer($request->auctioneer);
            $searchAuctioneer = $dealer->name;
        }
        $catList = $categoryService->cachedCategoryList(config('categories.except'));
        array_unshift($dealerCounties, 'Choose Location');
        $gavelBoxService = new GavelBoxService();
        $favouriteList = $gavelBoxService->favouriteList();
        return view('members.Dealers.searchFullMap', compact('dealers', 'dealerCounties', 'searchName', 'searchLocation', 'dealerList', 'searchAuctioneer', 'searchCategories', 'catList', 'favouriteList'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dealer = $this->dealersService->getDealer($id);
        $dealer->categories = $dealer->categories->groupBy('parent.name');
        //is category on other site
        $snippets = $this->dealersService->dealerRedirectCategory($dealer);
        if($snippets && $snippets['catCount'] == 1)
        {
            $snippets = $snippets['snippet'][0];
            return view('members.Dealers.showMainSnippet', compact('snippets', 'dealer'));
        }

        $gavelBoxService = new GavelBoxService();
        $favouriteList = $gavelBoxService->favouriteList();
        if(\Auth::user()->hasRole(\Settings::get('main_content_role'))) {
            $events = $this->dealersService->getUpcomingEvents($dealer, 7);
        } else {
            $events = null;
        }
        return view('members.Dealers.show', compact('dealer', 'favouriteList', 'snippets', 'events'));
    }

    public function favourite(Request $request, $id)
    {
        if(!\Auth::user()->hasRole(\Settings::get('main_content_role'))) abort(404);
        $message = $this->dealersService->userFavouriteToggle($id);

        if($request->ajax()) return $message;
        \Flash::success('Success : '.$message['message']);
        return back();
    }

    public function problem($id)
    {
        $dealer = $this->dealersService->getDealer($id);
        $dealer->categories = $dealer->categories->groupBy('parent.name');
        $snippet = $this->dealersService->getSnippet('Dealer Report Problem');
        $gavelBoxService = new GavelBoxService();
        $favouriteList = $gavelBoxService->favouriteList();
        if(\Auth::user()->hasRole(\Settings::get('main_content_role'))) {
            $events = $this->dealersService->getUpcomingEvents($dealer, 7);
        } else {
            $events = null;
        }
        return view('members.Dealers.problem', compact('dealer', 'snippet', 'favouriteList', 'events'));
    }

    public function problemStore(Requests\Members\DealerProblemRequest $request, $id)
    {
        $dealer = $this->dealersService->getDealer($id);
        $this->dealersService->saveProblem($request, $dealer);
        $dealer->categories = $dealer->categories->groupBy('parent.name');
        $snippet = $this->dealersService->getSnippet('Dealer Report Problem Received');
        $gavelBoxService = new GavelBoxService();
        $favouriteList = $gavelBoxService->favouriteList();
        if(\Auth::user()->hasRole(\Settings::get('main_content_role'))) {
            $events = $this->dealersService->getUpcomingEvents($dealer, 7);
        } else {
            $events = null;
        }
        return view('members.Dealers.problemReceived', compact('dealer', 'snippet', 'favouriteList', 'events'));
    }

    public function showEvent($id, $eventId, $eventDate)
    {
        if(!\Auth::user()->hasRole(\Settings::get('main_content_role'))) return redirect('/members/upgrade');
        $dealer = $this->dealersService->getDealer($id);
        $event = $this->dealersService->getEvent($dealer, $eventId);
        $dealer->categories = $dealer->categories->groupBy('parent.name');
        $gavelBoxService = new GavelBoxService();
        $favouriteList = $gavelBoxService->favouriteList();
        $this->dealersService->addEventMap($dealer, $event);
        $data['eventdate'] = $eventDate;
        $validator = Validator::make($data, [
            'eventdate' => 'required|date_format:Y-m-d'
        ]);
        if ($validator->fails()) {
            $eventDate = null;
        } else {
            $eventDate = Carbon::createFromFormat('Y-m-d', $eventDate)->format('l, M jS, Y');
        }
        if(\Auth::user()->hasRole(\Settings::get('main_content_role'))) {
            $events = $this->dealersService->getUpcomingEvents($dealer, 7);
        } else {
            $events = null;
        }

        return view('members.Dealers.event', compact('dealer', 'event', 'favouriteList', 'eventDate', 'events'));
    }
}
