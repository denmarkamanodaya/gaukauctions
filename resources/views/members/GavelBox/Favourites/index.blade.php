@extends('base::members.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script>
        var token = '{{ csrf_token() }}';
    </script>
    <script type='text/javascript' src="{{url('assets/js/gavelBoxFavourite.js')}}"></script>
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs([Settings::get('members_home_page_title') => Settings::get('members_home_page'), 'GavelBox' => '/members/gavelbox', 'My Auctioneers' => 'is_current']) !!}
@stop

@section('sidebarText')
@stop

@section('sidebarFooter')
@stop

@section('page-header')
    <span class="text-semibold">Favourite Auctioneers</span>
@stop


@section('content')
    <div class="row">

        <div class="section-content col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="row">


@if(count($dealers) > 0)
                @foreach($dealers as $dealer)

                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            @include('members.Dealers.partials.dealerCard')
                        </div>

                @endforeach
    @else
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 nothing-found">
                        <p>To add an auctioneer to your favourites click on the 'Heart' within its listing. </p>
                    </div>
                @endif


            </div>
        </div>
    </div>
@stop


