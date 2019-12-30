@extends('base::members.Template')

@section('body-class', 'cs-agent-detail single-page')

@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')

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
    @if($snippets && $snippets->header_content)
        <div style="margin-left: 15em">
            <div>
                {!! $snippets->header_content !!}
            </div>
        </div>
    @endif
@stop


@section('content')
    <div class="page-section">
        <div class="container">
            <div class="row">
                <div class="section-fullwidth col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    @if($snippets)
                        <div class="row">
                            <div class="col-md-12">{!! $snippets->content !!}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop


