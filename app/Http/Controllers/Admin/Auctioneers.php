<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\EditAuctioneerAddressRequest;
use App\Http\Requests\Admin\EditAuctioneerRequest;
use App\Http\Requests\Admin\GalleryImageDeleteRequest;
use App\Http\Requests\Admin\GetAuctioneerAddressRequest;
use App\Http\Requests\Admin\ImageUploadRequest;
use App\Models\Dealers;
use App\Models\DealersAddresses;
use App\Models\DealersFeatures;
use App\Http\Controllers\Controller;
use App\Services\DealerService;
use Quantum\base\Models\Categories;
use Quantum\base\Models\Countries;
use Yajra\DataTables\Facades\DataTables;

class Auctioneers extends Controller
{

    /**
     * @var DealerService
     */
    private $dealerService;

    public function __construct(DealerService $dealerService)
    {
        $this->dealerService = $dealerService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.Auctioneers.index');
    }

    public function data()
    {
        $auctioneers = Dealers::with(['categories', 'media' => function ($query) {
            $query->where('area', 'featured');
        }])->withCount('calendarEvents')->where('type', 'auctioneer')->orderBy('name', 'ASC');

        return Datatables::eloquent($auctioneers)
            ->editColumn('created_at', function ($model) {
                return $model->created_at->diffForHumans();
            })
            ->editColumn('calendar_events_count', function ($model) {
                if($model->calendar_events_count > 0) return '<i class="fas fa-check fa-lg"></i>';
                return '';
            })
            ->editColumn('to_parse', function ($model) {
                if($model->to_parse == 1) return '<i class="fas fa-check fa-lg"></i>';
                return '';
            })
            ->addColumn('action', function ($auctioneer) {
                return '<a href="'.url('admin/dealers/auctioneer/'.$auctioneer->slug).'" class="btn bg-teal-400 btn-labeled" type="button"><b><i class="fas fa-gavel"></i></b> Details</a>
                <a href="'.url('admin/dealers/auctioneer/'.$auctioneer->slug.'/edit').'" class="btn bg-info btn-labeled ml-5" type="button"><b><i class="fas fa-pencil"></i></b> Edit</a>
                <a href="'.url('admin/dealers/auctioneer/'.$auctioneer->slug.'/events').'" class="btn bg-success btn-labeled ml-5" type="button"><b><i class="fas fa-calendar-alt"></i></b> Events</a>';
            })
            ->addColumn('logo', function ($auctioneer) {
                if($auctioneer->logo != ''){
                    return '<img class="img-responsive" style="max-width: 60px;" src="'.url('images/dealers/'.$auctioneer->id.'/'.$auctioneer->logo).'">';
                }
                return '';
            })
            ->addColumn('featured', function ($auctioneer) {
                if($auctioneer->media && count($auctioneer->media) > 0){
                    return '<img class="img-responsive" style="max-width: 60px;" src="'.url($auctioneer->media->first()->name).'">';
                }
                return '';
            })
            ->rawColumns(['logo', 'featured', 'action', 'calendar_events_count', 'status', 'to_parse'])

            ->make(true);
    }
    
    public function show($id)
    {
        $auctioneer = Dealers::withCount('calendarEvents')->with('categories', 'categories.parent', 'features', 'media', 'remind')->where('slug', $id)->where('type', 'auctioneer')->firstOrFail();


        $categories = $auctioneer->categories->groupBy('parent.name');
        //dd($auctioneer, $categories);
        $auctioneer->country = Countries::where('id', $auctioneer->country_id)->first();
        return view('admin.Auctioneers.show', compact('auctioneer', 'categories'));
    }

    public function edit($slug)
    {
        $auctioneer = Dealers::withCount('calendarEvents')->with('categories', 'categories.parent', 'features', 'media', 'addresses')->where('slug', $slug)->firstOrFail();


        $categories = \App\Models\DealerCategories::whereHas('children')->orderBy('name', 'ASC')->get();
        $features = DealersFeatures::orderBy('name', 'ASC')->get();
        $countries = Countries::orderBy('name', 'ASC')->pluck('name', 'id');
        return view('admin.Auctioneers.edit', compact('auctioneer', 'categories', 'features', 'countries'));

    }

    public function update($id, EditAuctioneerRequest $request)
    {
        $auctioneer = $this->dealerService->updateDealer($id,$request);
        return redirect('/admin/dealers/auctioneer/'.$auctioneer->slug.'/edit');
    }

    public function create()
    {
        $categories = \App\Models\DealerCategories::whereHas('children')->orderBy('name', 'ASC')->get();
        $features = DealersFeatures::orderBy('name', 'ASC')->get();
        $countries = Countries::orderBy('name', 'ASC')->pluck('name', 'id');
        return view('admin.Auctioneers.create', compact('categories', 'features', 'countries'));
    }

    public function store(EditAuctioneerRequest $request)
    {
        $auctioneer = $this->dealerService->createDealer($request);
        return redirect('/admin/dealers/auctioneers');
    }

    public function galleryUpload(ImageUploadRequest $request, $auctioneer)
    {
        $upload = $this->dealerService->imageUpload($request, $auctioneer);

        if( $upload ) {
            return \Response::json('success', 200);
        } else {
            return \Response::json('error', 400);
        }
    }

    public function galleryDelete(GalleryImageDeleteRequest $request, $auctioneer)
    {
        $this->dealerService->deleteGalleryImage($request, $auctioneer);
        return response('success');
    }

    public function delete(\Request $request, $dealer)
    {
        $this->dealerService->deleteDealer($dealer);
        return redirect('/admin/dealers/auctioneers');
    }

    public function addressCreate($slug)
    {
        $auctioneer = Dealers::where('slug', $slug)->firstOrFail();
        $countries = Countries::orderBy('name', 'ASC')->pluck('name', 'id');
        return view('admin.Auctioneers.Addresses.create', compact('auctioneer', 'countries'));
    }

    public function addressStore(EditAuctioneerAddressRequest $request, $slug)
    {
        $this->dealerService->createAddress($request, $slug);
        return redirect('/admin/dealers/auctioneer/'.$slug.'/edit#addresses');
    }

    public function addressEdit($slug, $addressId)
    {
        $auctioneer = Dealers::where('slug', $slug)->firstOrFail();
        $address = DealersAddresses::where('dealers_id', $auctioneer->id)->where('id', $addressId)->firstOrFail();
        $countries = Countries::orderBy('name', 'ASC')->pluck('name', 'id');
        return view('admin.Auctioneers.Addresses.edit', compact('auctioneer', 'countries', 'address'));
    }

    public function addressUpdate(EditAuctioneerAddressRequest $request, $slug, $addressId)
    {
        $this->dealerService->editAddress($request, $slug, $addressId);
        return redirect('/admin/dealers/auctioneer/'.$slug.'/edit#addresses');
    }

    public function addressDelete($slug, $addressId)
    {
        $this->dealerService->deleteAddress($slug, $addressId);
        return redirect('/admin/dealers/auctioneer/'.$slug.'/edit#addresses');
    }

    public function getAddress(GetAuctioneerAddressRequest $request, $slug)
    {
        $address = $this->dealerService->getAddress($request, $slug);
        return $address;
    }
}
