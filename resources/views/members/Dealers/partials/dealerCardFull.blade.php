<?php
/*$catList = '';

if($dealer->categories && $dealer->categories->count() > 0)
    {
        foreach($dealer->categories as $category)
            {
                $catList .= $category->name.', ';
            }
    }

$catList = trim($catList);
$catList = rtrim($catList, ',');*/
?>

<div class="auto-listing auto-grid dealerCard">
        @if($dealer->media && $dealer->media->contains('area', 'featured'))
        <a href="{!! url('/members/auctioneer/'.$dealer->slug) !!}">
        <div class="dealerFeaturedHero mb-10" style="background-image: url({!! $dealer->media->firstWhere('area', 'featured')->name; !!})"></div>
        </a>
        @endif
    <div class="col-md-12 dealerInfoWrap">

        @if($dealer->logo)

        <div class="col-lg-12 col-md-12 col-sm-6 col-xs-12">
            <div class="text-center"><a href="{!! url('/members/auctioneer/'.$dealer->slug) !!}"><img class="img-responsive" src="{!! dealerLogoUrl($dealer) !!}" alt="{{ $dealer->name }}"></a></div>

            <h4 class="mt-10"><a href="{!! url('/members/vehicle/'.$dealer->slug) !!}">{{ $dealer->name }}</a></h4>
            <div class="col-md-12 mt-5 mb-5">
                @if($dealer->categories && $dealer->categories->count() > 0)
                    @foreach($dealer->categories as $category)
                        <a href="{!! url('/members/auctioneers?category='.$category->slug) !!}">{{ $category->name }}</a>
                    @endforeach
                @endif
            </div>
        </div>
        @else
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h4 class="mt-10"><a href="{!! url('/members/vehicle/'.$dealer->slug) !!}">{{ $dealer->name }}</a></h4>
                <div class="col-md-12 mt-5 mb-5">
                    {{ nl2br($dealer->address) }}
                </div>
            </div>
        @endif
    </div>

    <div class="col-md-12 locationDetail">
        @if($dealer->county != '')
            <i class="far fa-map-marker-alt"><a href="{{url('/members/auctioneers?location='.str_slug($dealer->county))}}"> {{$dealer->county}}</a> </i>
        @endif
    </div>

    <div class="vehicleDetailWrap_2">
        @if(Auth::user()->hasRole(Settings::get('main_content_role')))
            @if(in_array($dealer->id, $favouriteList))
                <a class="gavelBoxFavourite cs-color" href="{!! url('/members/auctioneer/'.$dealer->slug.'/favourite') !!}"><i class="icon-heart"></i>Favourite</a>
            @else
                <a class="gavelBoxFavourite cs-color" href="{!! url('/members/auctioneer/'.$dealer->slug.'/favourite') !!}"><i class="icon-heart-o"></i>Favourite</a>
            @endif
        @endif

            <a class="View-btn" href="{!! url('/members/auctioneer/'.$dealer->slug) !!}">View Details<i class="far fa-angle-double-right"></i></a>

    </div>

</div>