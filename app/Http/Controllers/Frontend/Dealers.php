<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Requests\Frontend\AuctioneerSearchRequest;
use App\Services\DealersService;
use App\Services\RestrictUserService;
use App\Services\SeoService;
use App\Http\Controllers\Controller;
use App\Services\DealerCategoryService;
use Illuminate\Http\Request;


class Dealers extends Controller
{

    /**
     * @var DealersService
     */
    private $dealersService;
    /**
     * @var RestrictUserService
     */
    private $restrictUserService;
    /**
     * @var SeoService
     */
    private $seoService;

    public function __construct(DealersService $dealersService, RestrictUserService $restrictUserService, SeoService $seoService)
    {
        $this->dealersService = $dealersService;
        $this->restrictUserService = $restrictUserService;
        $this->seoService = $seoService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $clearFilters=null)
    {
        //limit pagination
        if(isset($request->page))
        {
            if($request->page > 2) return redirect('/register');
            $searchPaginate = $this->searchPaginated($request);
            if($searchPaginate) return $searchPaginate;
        }

        //clear search
        if($clearFilters && $clearFilters = 'clearFilters')
        {
            \Request::session()->forget('dealerSearchTerms');
            return redirect('/auctioneers');
        }
        \Request::session()->put('searchSession', true);
        //get request search
        if(isset($request->location) || isset($request->category))
        {
            return $this->searchGetRequest($request);
        }

        $dealers = $this->dealersService->getDealers(true, 'auctioneer', 'Blackpool', 7);
        $dealers = $this->dealersService->setMarkers($dealers);
        //return view('frontend.Dealers.index', compact('dealers', 'dealerCounties'));
        return view('frontend.Dealers.indexFullMap', compact('dealers'));
    }

    public function search(AuctioneerSearchRequest $request)
    {
        //limit dealer search
        if($this->restrictUserService->restrictView('displayedDS')) return redirect('/register');
        $this->restrictUserService->updateCount('displayedDS');
        //end limit

        $dealerSearchTerms = [
            'name' => null,
            'location' => null,
            'auctioneer' => null,
            'categories' => $request->categories
        ];
        $request->request->add([
            'name' => null,
            'location' => null,
            'auctioneer' => null,
        ]);
        \Request::session()->put('dealerSearchTerms', $dealerSearchTerms);

        return $this->searchCommon($request);
    }

    public function searchPaginated(Request $request)
    {
        if($nosession = $this->checkSession($request)) return $nosession;
        $searchTerms = $request->session()->get('dealerSearchTerms');
        if(!$searchTerms) return;
        $request->request->add([
            'name' => null,
            'location' => isset($searchTerms['location']) ? $searchTerms['location']: null,
            'auctioneer' => null,
            'categories' => isset($searchTerms['categories']) ? $searchTerms['categories'] : null
        ]);
        return $this->searchCommon($request);

    }

    public function searchGetRequest($request)
    {
        //limit dealer search
        if($this->restrictUserService->restrictView('displayedDS')) return redirect('/register');
        $this->restrictUserService->updateCount('displayedDS');
        //end limit

        //sanitise
        $input = $request->all();
        if(isset($input['location'])) $input['location'] = filter_var($input['location'], FILTER_SANITIZE_STRING);
        if(isset($input['category'])) $input['category'] = filter_var($input['category'], FILTER_SANITIZE_STRING);
        if(isset($input['auctioneer'])) $input['auctioneer'] = null;
        if(isset($input['name'])) $input['name'] = null;
        $request->replace($input);

        $validatedData = $request->validate([
            'location' => 'nullable|alpha-dash',
            'category' => 'nullable|alpha-dash'
        ]);
        if(!$request->location && !$request->category) return redirect('/auctioneers');

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
            'name' => null,
            'location' => $request->location,
            'auctioneer' => null,
            'categories' => $request->categories
        ];
        \Request::session()->put('dealerSearchTerms', $dealerSearchTerms);

        return $this->searchCommon($request);
    }

    private function searchCommon($request)
    {
        if($nosession = $this->checkSession($request)) return $nosession;
        $categoryService = new DealerCategoryService();
        if(is_null($request->categories) && !$request->location) return redirect('/auctioneers');
        $dealers = $this->dealersService->searchDealers($request, true, 'auctioneer', 'Blackpool', 7);
        $dealers = $this->dealersService->setMarkers($dealers);
        $searchCategories = $request->categories;
        $searchAuctioneer = null;
        $searchLocation = $request->location;
        if($request->categories)
        {
            $searchCategories = $categoryService->cachedSearchCategories($request->categories);
        }

        return view('frontend.Dealers.searchFullMap', compact('dealers', 'searchCategories', 'searchAuctioneer', 'searchLocation'));
    }

    private function checkSession($request)
    {
        $searchSession = $request->session()->get('searchSession');
        if(!$searchSession) return redirect('/auctioneers');
        return false;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //limit dealer view
        if($this->restrictUserService->restrictView('displayedD')) return redirect('/register');
        $this->restrictUserService->updateCount('displayedD');
        //end limit
        
        $dealer = $this->dealersService->getDealer($id);
        $dealer->categories = $dealer->categories->groupBy('parent.name');
        //is category on other site
        $snippets = $this->dealersService->dealerRedirectCategory($dealer);
        if($snippets && $snippets['catCount'] == 1)
        {
            $snippets = $snippets['snippet'][0];
            return view('frontend.Dealers.showMainSnippet', compact('snippets', 'dealer'));
        }

        $this->seoService->auctioneer($dealer);
        return view('frontend.Dealers.show', compact('dealer', 'snippets'));
    }
}
