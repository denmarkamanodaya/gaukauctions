<?php

namespace App\Http\Controllers\Members;

use App\Models\DealersUser;
use App\Services\GavelBoxService;
use App\Services\DealerService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Quantum\calendar\Http\Requests\Admin\getEventDayRequest;
use Quantum\calendar\Http\Requests\Admin\getEventmonthRequest;
use Quantum\calendar\Services\CalendarService;

class GavelBox extends Controller
{
    /**
     * @var DealerService
     */
    private $dealerService;
    /**
     * @var CalendarService
     */
    private $calendarService;

    private $defaultEvents;
    /**
     * @var GavelBoxService
     */
    private $gavelBoxService;

    public function __construct(GavelBoxService $gavelBoxService, DealerService $dealerService, CalendarService $calendarService)
    {
        $this->dealerService = $dealerService;
        $this->calendarService = $calendarService;
        $this->defaultEvents = config('calendar.default_types');
        $this->gavelBoxService = $gavelBoxService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \Auth::user();
        $premium = ($user->can('premium-auctions-access')) ? true : false;
        $pageSnippet = $this->gavelBoxService->getPageSnippet($premium);
        $dealerFavouriteCount = DealersUser::where('user_id', $user->id)->count();
        return view('members.GavelBox.index', compact('pageSnippet', 'dealerFavouriteCount'));
    }

    public function favouriteAuctioneers()
    {
        if(!\Auth::user()->hasRole(\Settings::get('main_content_role'))) abort(404);
        $dealers = $this->gavelBoxService->getFavouriteDealers();
        $favouriteList = $this->gavelBoxService->favouriteList();
        return view('members.GavelBox.Favourites.index', compact('dealers', 'favouriteList'));
    }
}
