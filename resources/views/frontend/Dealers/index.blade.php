@extends('base::frontend.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script type='text/javascript' src="{{url('assets/js/gavelBoxFavourite.js')}}"></script>
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(['Home' => '/', 'Dealers' => 'is_current']) !!}
@stop

@section('page-header')
    <span class="text-semibold">Dealers</span>
@stop


@section('content')
    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
        @include('frontend.NeedRegister.sideBannerRegister')
    </div>

    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
    <div class="row">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="row">

                @foreach($dealers as $dealer)
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="cs-agent-listing">
                            <div class="cs-media">
                                @if($dealer->logo != '')
                                <figure>
                                        <a href="{!! url('/dealer/'.$dealer->slug) !!}"><img alt="{{ $dealer->name }}" src="{!! url('/images/dealers/'.$dealer->id.'/thumb150-'.$dealer->logo) !!}"></a>
                                </figure>
                                @endif
                            </div>
                            <div class="cs-text">
                                <div class="cs-post-title">
                                    <h6><a href="{!! url('/dealer/'.$dealer->slug) !!}">{{ $dealer->name }}</a></h6>
                                </div>
                                @include('frontend.NeedRegister.textAuctioneerOneLine')
                                <a class="contact-btn" href="{!! url('/dealer/'.$dealer->slug) !!}"><i class="fas fa-gavel"></i>Details</a>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="datatable-footer">
                    <div class="dataTables_info" id="DataTables_Table_3_info" role="status" aria-live="polite">
                        Showing {!! $dealers->count() !!} out of {!! $dealers->total() !!}
                    </div>
                    <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_3_paginate">
                        <nav>{!! $dealers->render() !!}</nav>
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>



@stop


