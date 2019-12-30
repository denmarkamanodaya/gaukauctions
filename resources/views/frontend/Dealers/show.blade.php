@extends('base::frontend.Template')

@section('body-class', 'cs-agent-detail single-page')

@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script src="{{url('assets/js/fotorama.js')}}" type="text/javascript"></script>

@stop

@section('page_css')
    <link href="{{url('assets/css/fotorama.css')}}" rel="stylesheet" type="text/css">
@stop

@section('breadcrumbs')
    {!! breadcrumbs(['Home' => '/', 'Auctioneers' => '/auctioneers/', $dealer->name => 'is_current']) !!}
@stop

@section('page-header')
    <span class="text-semibold">Dealer - {{ $dealer->name }}</span>
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
                            @include('frontend.NeedRegister.textAuctioneerOneLine')
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
                                @include('frontend.NeedRegister.sideBannerRegister')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop


