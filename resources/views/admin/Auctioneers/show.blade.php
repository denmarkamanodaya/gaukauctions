@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')

@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Auctioneers' => '/admin/dealers/auctioneers', 'Auctioneer' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Auctioneer</span>
@stop


@section('content')

    <div class="row">
        <div class="col-md-8">

            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row mb-20">
                        <div class="col-md-2 text-right text-bold">

                        </div>
                        <div class="col-md-10">
                            @if($auctioneer->logo != '')
                                <img style="max-height: 30px;" src="{{dealerLogoUrl($auctioneer, 50)}}">
                            @endif
                            <h3>{{$auctioneer->name}}</h3>
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-md-2 text-right text-bold">
                            Address :
                        </div>
                        <div class="col-md-10">
                            {{$auctioneer->address}}
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-md-2 text-right text-bold">
                            Town :
                        </div>
                        <div class="col-md-10">
                            {{$auctioneer->town}}
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-md-2 text-right text-bold">
                            County :
                        </div>
                        <div class="col-md-10">
                            {{$auctioneer->county}}
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-md-2 text-right text-bold">
                            Postcode :
                        </div>
                        <div class="col-md-10">
                            {{$auctioneer->postcode}}
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-md-2 text-right text-bold">
                            Country :
                        </div>
                        <div class="col-md-10">
                            {{$auctioneer->country->name}}
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-md-2 text-right text-bold">
                            Longitude :
                        </div>
                        <div class="col-md-10">
                            {{$auctioneer->longitude}}
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-md-2 text-right text-bold">
                            Latitude :
                        </div>
                        <div class="col-md-10">
                            {{$auctioneer->latitude}}
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-md-2 text-right text-bold">
                            phone :
                        </div>
                        <div class="col-md-10">
                            {{$auctioneer->phone}}
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-md-2 text-right text-bold">
                            Email :
                        </div>
                        <div class="col-md-10">
                            {{$auctioneer->email}}
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-md-2 text-right text-bold">
                            website :
                        </div>
                        <div class="col-md-10">
                            <a target="_blank" href="{{$auctioneer->website}}">{{$auctioneer->website}}</a>
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-md-2 text-right text-bold">
                            Details :
                        </div>
                        <div class="col-md-10">
                            {!! nl2br($auctioneer->details) !!}
                        </div>
                    </div>

                    @if($auctioneer->media)
                        <div class="col-md-12">
                            @foreach($auctioneer->media as $media)
                                @if($media->area == 'gallery')
                                <div class="col-lg-3" id="media_{{$media->id}}"><img class="img-responsive" src="{{url('images/dealers/'.$auctioneer->id.'/'.$media->name)}}"><br>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    @endif


                </div>
            </div>

        </div>

        <div class="col-md-4">
            @if($auctioneer->logo)
                <div class="row">
                    <div class="col-md-12 text-center mb-10">
                        <img class="img-responsive" src="{{dealerLogoUrl($auctioneer)}}">
                    </div>
                </div>
            @endif

                @if($categories && $categories->count() > 0)
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">Dealer Categories</h4>
                            </div>
                            <div class="panel-body">

                                    @foreach($categories as $CatHeader => $CatList)
                                    <h5>{{$CatHeader}}</h5>
                                        <ul style="list-style: none">
                                        @foreach($CatList as $item)
                                              <li>{{$item->name}}</li>
                                        @endforeach
                                        </ul>
                                    @endForeach

                            </div>
                        </div>

                    </div>

                </div>
                @endif

                @if($auctioneer->features && $auctioneer->features->count() > 0)
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">Dealer Features</h4>
                            </div>
                            <div class="panel-body">

                                        <ul style="list-style: none">
                                            @foreach($auctioneer->features as $feature)
                                                <li>
                                                    @if($feature->icon)
                                                    <i class="{{$feature->icon}} mr-10"></i>
                                                    @endif
                                                    {{$feature->name}}
                                                </li>
                                            @endforeach
                                        </ul>
                            </div>
                        </div>

                    </div>

                </div>
                @endif

                @if($auctioneer->remind && $auctioneer->remind->count() > 0)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Reminders</h4>
                                </div>
                                <div class="panel-body text-center">
                                    @foreach($auctioneer->remind as $reminder)
                                        <p>{{$reminder->about}} on  {{$reminder->remind_on->toFormattedDateString()}}</p>
                                    @endforeach
                                </div>
                            </div>

                        </div>

                    </div>
                @endif

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-body text-center">
                                <p><a href="{{url('admin/dealers/auctioneer/'.$auctioneer->slug.'/edit/')}}" class="btn bg-info btn-labeled" type="button"><b><i class="far fa-pencil"></i></b> Edit Dealer</a></p>
                                <a href="{{url('admin/dealers/auctioneer/'.$auctioneer->slug.'/events/')}}" class="btn bg-success btn-labeled" type="button"><b><i class="far fa-calendar-alt"></i></b> View Dealer Calendar Events</a>
                            </div>
                        </div>

                    </div>

                </div>

        </div>

    </div>
@stop


