<?php

namespace App\Http\Controllers\Admin;

use App\Services\DealersService;
use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\CalendarImportService;
use Quantum\base\Models\Countries;
use Quantum\calendar\Services\CalendarService;


class CalendarImport extends Controller
{
    /**
     * @var CalendarService
     */
    private $calendarService;
    /**
     * @var CalendarImportService
     */
    private $calendarImportService;
    /**
     * @var DealersService
     */
    private $dealersService;

    public function __construct(CalendarService $calendarService, CalendarImportService $calendarImportService, DealersService $dealersService)
    {
        $this->calendarService = $calendarService;
        $this->calendarImportService = $calendarImportService;
        $this->dealersService = $dealersService;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = $this->calendarImportService->import_list();
        $dealers = $this->dealersService->dealerSelectList();
        return view('admin.Calendar.import.index', compact('posts', 'dealers'));
    }

    public function importFromFile()
    {
        $imported = $this->calendarImportService->importFromFile();
        return view('admin.Calendar.import.imported', compact('imported'));

    }

    public function create(Requests\Admin\ImportPostRequest $request)
    {
        $post = \Quantum\base\Models\Import::where('id', $request->post)->tenant()->firstOrFail();
        $dealer = $this->dealersService->getDealer($request->dealer);
        $dealer->load('addresses');
        $countries = Countries::pluck('name', 'id');
        $categories = $this->calendarService->getCategoryList();
        $post->content = unserialize($post->content);
        $dealer->description = $post->content['post_content'];
        $dealer->title = $post->title;
        $dealer->event_url = $dealer->website;

        foreach($post->content['postmeta'] as $postmeta)
        {
            switch ($postmeta['key'])
            {
                case 'evcal_allday':
                    $dealer->all_day_event = $postmeta['value'];
                    break;
                case 'evcal_srow':
                    $dealer->start_date = Carbon::createFromTimestamp($postmeta['value'])->format('Y-m-d');
                    $dealer->start_time = Carbon::createFromTimestamp($postmeta['value'])->format('H:i');
                    break;
                case 'evcal_erow':
                    $dealer->end_date = Carbon::createFromTimestamp($postmeta['value'])->format('Y-m-d');
                    $dealer->end_time = Carbon::createFromTimestamp($postmeta['value'])->format('H:i');
                    break;
                case 'evcal_repeat':
                    $dealer->repeat_event = $postmeta['value'];
                    break;
                case 'evcal_rep_freq':
                    $dealer->repeat_type = $postmeta['value'];
                    break;
            }
        }

        if($dealer->all_day_event && $dealer->all_day_event == 'yes') $dealer->end_date = null;

        return view('admin.Calendar.import.show', compact('post', 'dealer', 'categories', 'countries'));
    }

    public function markComplete(Requests\Admin\ImportCompleteRequest $request)
    {
        \Quantum\base\Models\Import::where('id', $request->import)->tenant()->update(['complete' => 1]);
        flash('Event Marked as Complete')->success();
        return redirect('/admin/calendar/import');
    }



}
