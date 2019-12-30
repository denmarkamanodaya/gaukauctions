@extends('base::members.Template')

@section('body-class', 'cs-agent-detail single-page')

@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script src="{{url('assets/js/fotorama.js')}}" type="text/javascript"></script>
    <script type='text/javascript' src="{{url('assets/js/gavelBoxFavourite.js')}}"></script>

@stop

@section('page_css')
    <link href="{{url('assets/css/fotorama.css')}}" rel="stylesheet" type="text/css">
@stop

@section('breadcrumbs')
    {!! breadcrumbs([Settings::get('members_home_page_title') => Settings::get('members_home_page'), 'Auctioneers' => '/members/auctioneers', $dealer->name => 'is_current']) !!}
@stop

@section('page-header')
    <span class="text-semibold">Auctioneer - {{ $dealer->name }}</span>
@stop


@section('pre-content')


    <div style="background-color:#fafafa; padding:40px 0;" class="page-section mb-10">
        <div class="container">

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="cs-admin-info">
                        <div class="cs-media">
                            <figure>
                                @if($dealer->logo != '')
                                    <a href="{!! url('/members/auctioneers/'.$dealer->slug) !!}"><img class="img-responsive" alt="{{ $dealer->name }}" src="{!! url('/images/dealers/'.$dealer->id.'/thumb300-'.$dealer->logo) !!}"></a>
                                @endif
                            </figure>
                        </div>
                        <div class="cs-text">
                            <div class="cs-title">
                                <h3>{{ $dealer->name }}</h3>
                            </div>
                            @if(Auth::user()->hasRole(Settings::get('main_content_role')))<address>{!! nl2br($dealer->address) !!}</address>
                            <ul>
                                <li>
                                    <span>Phone number</span>
                                    {{ $dealer->phone }}

                                </li>
                                <li>
                                    <span>Website</span>
                                    <a target="_blank" href="{{ $dealer->website }}">{{ $dealer->website }}</a>
                                </li>
                            </ul>
                            @else @include('members.NeedUpgrade.textAuctioneerOneLine') @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop


@section('content')
    <div class="page-section">
        <div class="container">
            <div class="row">
                <div class="section-fullwidth col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">

                            @if($snippets and count($snippets['snippet'] > 0))

                                @foreach($snippets['snippet'] as $snippet)
                                    <div class="linkedSiteInfo mb-20">
                                        {!! $snippet->content !!}
                                    </div>
                                @endforeach

                            @endif

                            <div class="pageContent">
                                <strong>About {{ $dealer->name }}</strong>
                            {!! $dealer->details !!}
                            </div>
                            @if($dealer->media)
                                <div class="col-md-12">
                                    <div class="fotorama" data-width="70%" data-ratio="800/600" data-max-width="100%" data-nav="thumbs" data-arrows="always" data-autoplay="true">

                                    @foreach($dealer->media as $media)
                                            @if($media->area == 'gallery')
                                            <img class="img-responsive" src="{{url('images/dealers/'.$dealer->id.'/'.$media->name)}}">
                                            @endif
                                    @endforeach
                                    </div>
                                </div>
                            @endif



                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="cs-tabs-holder ">

                                @if(Auth::user()->hasRole(Settings::get('main_content_role')))

                                    @if($dealer->longitude != '')
                                        <div class="cs-agent-contact-form bg-white">
                                            <span class="cs-form-title">Location</span>
                                            <div class="cs-agent-map2 loader">
                                                {!! Mapper::render(1) !!}
                                            </div>
                                        </div>
                                    @else
                                        <div class="cs-agent-contact-form col-lg-12 col-md-12 col-sm-6 col-xs-6 bg-white">
                                            <span class="cs-form-title">Location</span>
                                            <div class="">
                                                <img src="{!! url('images/Online-side.jpg') !!}" class="img-responsive online_side_img">
                                            </div>
                                        </div>

                                    @endif


                                        <div class="cs-category-link-icon mt-10 mb-10">
                                            <ul>
                                                @if(in_array($dealer->id, $favouriteList))
                                                    <li><a class="gavelBoxFavourite cs-color" href="{!! url('/members/auctioneer/'.$dealer->slug.'/favourite') !!}"><i class="icon-heart"></i>Favourite</a></li>
                                                @else
                                                    <li><a class="gavelBoxFavourite cs-color" href="{!! url('/members/auctioneer/'.$dealer->slug.'/favourite') !!}"><i class="icon-heart-o"></i>Favourite</a></li>
                                                @endif
                                                @if($dealer->website != '')
                                                    <li><a target="_blank" href="{!! $dealer->website !!}"><i class="cs-color fas fa-gavel"></i>Visit Auctioneer</a></li>
                                                @endif
                                                <li><a href="#" onclick="javascript:window.print();"><i class="cs-color icon-print3"></i>Print Details</a></li>
                                                <li><a href="{!! url('/members/auctioneer/'.$dealer->slug.'/report-a-problem') !!}"><i class="cs-color far fa-exclamation-triangle"></i>Report a Problem</a></li>
                                            </ul>
                                        </div>

                                        @if($dealer->media && $dealer->media->contains('area', 'featured'))
                                            <div class="dealerCard">
                                            <div class="dealerFeaturedHero mb-10" style="background-image: url({!! $dealer->media->firstWhere('area', 'featured')->name; !!})"></div>
                                            </div>
                                        @endif

                                        @include('members.Dealers.partials.upcomingEvents')

                                    @if($dealer->categories && $dealer->categories->count() > 0)
                                        <div class="widget widget-text mt-20">
                                            <h6>Dealer Categories</h6>
                                            @foreach($dealer->categories as $CatHeader => $CatList)
                                                <div class="dealerCategories">
                                                    <h5>{{$CatHeader}}</h5>
                                                    <ul style="list-style: none">
                                                        @foreach($CatList as $item)
                                                            <li><a href="{{url('/members/auctioneers?category='.$item->slug)}}">{{$item->name}}</a></li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endForeach
                                        </div>
                                    @endif

                                    @if($dealer->features && $dealer->features->count() > 0)
                                        <div class="widget widget-text">
                                            <h6>Dealer Features</h6>
                                            <div class="dealerFeatures">
                                                @foreach($dealer->features as $feature)
                                                    <ul>
                                                            <li>
                                                                @if($feature->icon)
                                                                    <div class="featureIcon"><i class="{{$feature->icon}} mr-10"></i></div>
                                                                @endif
                                                                {{$feature->name}}
                                                            </li>
                                                    </ul>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif



                                @else
                                    @include('members.NeedUpgrade.sideBannerUpgrade')
                                @endif



                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(Auth::user()->hasRole(Settings::get('main_content_role')))
    </div></div>
    @if($dealer->longitude != '')
        <div class="cs-agent-map2 loader">
            {!! Mapper::render(0) !!}
        </div>
    @else
        <div class="">
            <img src="{!! url('images/Online-hero.jpg') !!}" class="img-responsive online_hero_img">
        </div>
    @endif
    <div class="page-section" style="padding-top:70px; padding-bottom:0px; margin-bottom: -70px;">
        <div class="container">
    @endif
@stop


