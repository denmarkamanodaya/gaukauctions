<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\DeleteAuctioneerEventMultiRequest;
use App\Models\Dealers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Quantum\base\Models\Countries;
use Quantum\calendar\Http\Requests\Admin\createCalendarEventRequest;
use Quantum\calendar\Models\Calendar;
use Quantum\calendar\Services\CalendarService;

class AuctioneerEvents extends Controller
{


    /**
     * @var CalendarService
     */
    private $calendarService;

    public function __construct(CalendarService $calendarService)
    {
        $this->calendarService = $calendarService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $dealer = Dealers::with('calendarEvents')->where('slug', $id)->firstOrFail();
        return view('admin.Auctioneers.Events.index', compact('dealer'));
    }


    public function edit($id, $event)
    {
        $dealer = Dealers::with(['calendarEvents' => function($query) use($event) {
            $query->with('meta');
            //$query->where('id', $event)->firstOrFail();
        }])->where('slug', $id)->firstOrFail();
        $event = $dealer->calendarEvents->where('id', $event)->first();
        if(!$event) abort(404);
        $categories = $this->calendarService->getCategoryList();

        $event->all_day_event = is_null($event->start_time) ? 'yes' : 'no';
        $event->repeat_event = is_null($event->repeat_year) ? 'no' : 'yes';
        $countries = Countries::pluck('name', 'id');

        return view('admin.Auctioneers.Events.edit', compact('dealer', 'event', 'categories', 'countries'));
    }

    public function cloneEvent($id, $event)
    {
        $dealer = Dealers::with(['calendarEvents' => function($query) use($event) {
            $query->with('meta');
            //$query->where('id', $event)->firstOrFail();
        }])->where('slug', $id)->firstOrFail();
        $event = $dealer->calendarEvents->where('id', $event)->first();
        if(!$event) abort(404);
        $categories = $this->calendarService->getCategoryList();

        $event->all_day_event = is_null($event->start_time) ? 'yes' : 'no';
        $event->repeat_event = is_null($event->repeat_year) ? 'no' : 'yes';
        $countries = Countries::pluck('name', 'id');
        return view('admin.Auctioneers.Events.cloneEvent', compact('dealer', 'event', 'categories', 'countries'));
    }

    public function create($id)
    {
        $dealer = Dealers::with('calendarEvents')->where('slug', $id)->firstOrFail();
        $categories = $this->calendarService->getCategoryList();
        $countries = Countries::pluck('name', 'id');

        if(!is_null($dealer->auction_url))
        {
            $dealer->event_url = $dealer->auction_url;
        } else {
            $dealer->event_url = $dealer->website;
        }

        return view('admin.Auctioneers.Events.create', compact('dealer', 'categories', 'countries'));
    }

    public function store(createCalendarEventRequest $request, $id)
    {
        $dealer = Dealers::where('slug', $id)->firstOrFail();
        $dealer->calendarEventCreate($request);

        $this->setReminder($dealer, $request);

        \Cache::forget('auctioneers');
        if($request->import)
        {
            \Quantum\base\Models\Import::where('id', $request->import)->update(['complete' => 1]);
            flash('Event Imported')->success();
            return redirect('/admin/calendar/import');
        }
        return redirect('/admin/dealers/auctioneer/'.$id.'/events');
    }

    public function update(createCalendarEventRequest $request, $id, $event)
    {
        $dealer = Dealers::with(['calendarEvents' => function($query) use($event) {
            $query->with('meta');
            $query->where('id', $event)->firstOrFail();
        }])->where('slug', $id)->firstOrFail();
        $event = $dealer->calendarEvents->first();
        $calendarService = new CalendarService();
        $calendarService->updateEvent($request, $event);
        $this->setReminder($dealer, $request);
        \Cache::forget('auctioneers');
        return redirect('/admin/dealers/auctioneer/'.$id.'/events');
    }

    public function delete($id, $event)
    {
        $dealer = Dealers::with(['calendarEvents' => function($query) use($event) {
            $query->with('meta');
            $query->where('id', $event)->firstOrFail();
        }])->where('slug', $id)->firstOrFail();
        $event = $dealer->calendarEvents->first();
        $calendarService = new CalendarService();
        $calendarService->deleteEvent($event);
        \Cache::forget('auctioneers');
        return redirect('/admin/dealers/auctioneer/'.$id.'/events');
    }

    public function deleteSelectedEvents(DeleteAuctioneerEventMultiRequest $request, $id)
    {
        $dealer = Dealers::where('slug', $id)->firstOrFail();
        if($request->deleteSelected && count($request->deleteSelected) > 0)
        {
            $dealer->load(['calendarEvents' => function($query) use($request) {
                $query->whereIn('id', $request->deleteSelected);
            }]);

            if($dealer->calendarEvents && $dealer->calendarEvents->count() > 0)
            {
                foreach ($dealer->calendarEvents as $event)
                {
                    $event->meta()->delete();
                    $event->delete();
                }
            }

        }
        flash('Events have been deleted')->success();
        return redirect()->back();
    }

    private function setReminder($dealer, $request)
    {
        if($request->remindAmount && $request->remindAmount > 0)
        {
            $startDate = Carbon::now();
            //$startDate = Carbon::createFromFormat('Y-m-d', $request->start_date);
            $remind_on = null;
            if($request->remindType == 'days') $remind_on = $startDate->addDays($request->remindAmount);
            if($request->remindType == 'weeks') $remind_on = $startDate->addWeeks($request->remindAmount);
            if($request->remindType == 'months') $remind_on = $startDate->addMonths($request->remindAmount);
            if($request->remindType == 'years') $remind_on = $startDate->addYears($request->remindAmount);
            if($remind_on)
            {
                $dealer->remind()->create([
                    'remind_on' => $remind_on,
                    'about' => 'Review Events'
                ]);
            }
        }
    }
}
