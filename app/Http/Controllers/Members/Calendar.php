<?php

namespace App\Http\Controllers\Members;

use App\Http\Controllers\Controller;
use Quantum\base\Services\NewsService;
use Quantum\calendar\Http\Requests\Admin\getEventDayRequest;
use Quantum\calendar\Http\Requests\Admin\getEventmonthRequest;
use Quantum\calendar\Services\CalendarService;

class Calendar extends Controller
{

    /**
     * @var CalendarService
     */
    private $calendarService;

    private $defaultEvents;

    public function __construct(CalendarService $calendarService)
    {
        $this->calendarService = $calendarService;
        $this->defaultEvents = config('calendar.default_types');
    }

    private function setDefaultEvents()
    {
        if(count($this->defaultEvents) == 0) return;
        foreach($this->defaultEvents as $key => $values)
        {
            $this->calendarService->addEventType($key, $values);
        }
    }

    public function calendar()
    {
        //$allowed = [1,4];
        //if(in_array(\Auth::user()->id, $allowed))
        //{
        //$this->calendarService->addEventType('App\User', \Auth::User()->id);
        $newsService = new NewsService();
        $this->setDefaultEvents();
        $categories = $this->calendarService->getCategoryList();
        $pageSnippet = $newsService->getSnippet('GavelBox Calendar');
        return view('members.GavelBox.Calendar.index', compact('categories', 'pageSnippet'));
        //}

        //return view('members.MyGarage.Calendar.underConstruction');

    }

    public function getDay(getEventDayRequest $request)
    {
        \View::share('javascript', true);
        $this->calendarService->addEventType('App\User', \Auth::User()->id);
        $this->setDefaultEvents();
        $events = $this->calendarService->getDay($request->caldate, $request->filters);
        if($events)
        {
            $view = \View::make('calendar::members.dailyEvent.index', compact('events'));
            $data['status'] = 'success';
            $data['events'] = $view->render();
        }
        return $data;

    }

    public function getMonth(getEventmonthRequest $request)
    {

        $this->calendarService->addEventType('App\User', \Auth::User()->id);
        $this->setDefaultEvents();
        $events = $this->calendarService->getMonthEvents($request->caldate, $request->filters);
        $thisEvents = $this->calendarService->formatMonthEvents($events);
        return $thisEvents;
    }
}
