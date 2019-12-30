@extends('base::members.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script type='text/javascript' src="{{url('assets/js/gavelBoxFavourite.js')}}"></script>
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs([Settings::get('members_home_page_title') => Settings::get('members_home_page'), 'Auctioneers' => 'is_current']) !!}
@stop

@section('page-header')
    <span class="text-semibold">Auctioneers</span>
@stop


@section('content')


    </div></div>





            <div class="dealer-listing-wrap">
                @include('members.Dealers.partials.fullSearch')

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="auto-your-search text-center">
                        <ul class="filtration-tags">
                            @if($searchName && $searchName != '')
                                <li>{{ $searchName }} <i class="icon-search"></i></li>
                            @endif
                            @if($searchAuctioneer && $searchAuctioneer != '0')
                                <li>{{ $searchAuctioneer }} <i class="icon-search"></i></li>
                            @endif
                            @if($searchLocation && $searchLocation != '0')
                                <li>{{ $searchLocation }} <i class="icon-location"></i></li>
                            @endif
                            @if(!is_null($searchCategories))
                                @foreach($searchCategories as $category)
                                    <li>{{ $category }} <i class="far fa-sitemap"></i></li>
                                @endforeach
                            @endif
                        </ul>
                        <a class="clear-tags cs-color" href="{!! url('/members/auctioneers/clearFilters') !!}">Clear Filters</a>
                    </div>
                </div>

                <hr style="margin-top: -20px"/>


                    @foreach($dealers as $dealer)
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            @include('members.Dealers.partials.dealerCard')
                        </div>
                    @endforeach

                    <div class="datatable-footer mr-10">
                        <div class="dataTables_info mt-10 ml-5" id="DataTables_Table_3_info" role="status" aria-live="polite">
                            Showing {!! $dealers->count() !!} out of {!! $dealers->total() !!}
                        </div>
                        <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_3_paginate">
                            <nav>{!! $dealers->render() !!}</nav>
                        </div>
                    </div>


            </div>

                <div class="dealer-listing-map-wrap">
                    <div class="dealer-listing-map loader">
                        {!! Mapper::render() !!}
                    </div>
                </div>



    <div class="page-section" style="padding-top:0px; padding-bottom:0px; margin-bottom: -20px;">
        <div class="container">


@stop


