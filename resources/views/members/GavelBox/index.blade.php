@extends('base::members.Template')

@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')

@stop

@section('page_css')
    <style>
        .main {
            margin-top: 5px !important;
        }
        .widget {
            margin-bottom: 1px !important;
            padding-bottom: 1px !important;
        }
    </style>
@stop

@section('breadcrumbs')
    {!! breadcrumbs([Settings::get('members_home_page_title') => Settings::get('members_home_page'), 'GavelBox' => 'is_current']) !!}
@stop

@section('sidebarText')
@stop

@section('sidebarFooter')

@stop

@section('page-header')
    <span class="text-semibold">GavelBox</span>
@stop

@section('headerContent')
    @if($pageSnippet && $pageSnippet->header_content)
    <div style="margin-left: 15em">
        <div>
                {!! $pageSnippet->header_content !!}
        </div>
    </div>
    @endif
@stop

@section('content')
    @if(Auth::user()->can('premium-auctions-access'))
    <div class="row">

        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="cs-services">
                <div class="cs-media"> <i class="far fa-heart cs-color" style="font-size:40px;"> </i> </div>
                <div class="cs-text">
                    <h6>My Auctioneers </h6>
                    <p>You currently have {{$dealerFavouriteCount}} {{str_plural('auctioneer', $dealerFavouriteCount)}} in your favourites.</p>
                </div>
            </div>
        </div>

    </div>
    @else
        <div class="row">
            <div class="col-md-12">
                <div class="widget widget-text text-center" style="">
                    <a class="contact-btn cs-color" href="{!! url('/members/upgrade') !!}">Upgrade Membership</a>
                </div>
            </div>
        </div>

    @endif

@if($pageSnippet)
    <div class="row">
    <div class="col-md-12">{!! $pageSnippet->content !!}</div>
    </div>
@endif

    @if(!Auth::user()->can('premium-auctions-access'))
        <div class="row">
            <div class="col-md-12">
                <div class="widget widget-text text-center" style="">
                    <a class="contact-btn cs-color" href="{!! url('/members/upgrade') !!}">Upgrade Membership</a>
                </div>
            </div>
        </div>
    @endif

@stop


