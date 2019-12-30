<?php
$catList = '';

if($dealer->categories && $dealer->categories->count() > 0)
    {
        foreach($dealer->categories as $category)
            {
                $catList .= $category->name.', ';
            }
    }

$catList = trim($catList);
$catList = rtrim($catList, ',');
?>


<div class="auto-listing auto-grid dealerCard">
    <div class="col-md-12 dealerInfoWrap">
        @if($dealer->logo)

        <div class="col-lg-12 col-md-12 col-sm-6 col-xs-12">
            <div class="text-center"><a href="{!! url('/auctioneer/'.$dealer->slug) !!}"><img class="img-responsive" src="{!! dealerLogoUrl($dealer) !!}" alt="{{ $dealer->name }}"></a></div>

            <h4 class="mt-10"><a href="{!! url('/auctioneer/'.$dealer->slug) !!}">{{ $dealer->name }}</a></h4>
            <div class="col-md-12 mt-5 mb-5">
                {{ str_limit($catList, 200) }}
            </div>
        </div>
        @else
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h4 class="mt-10"><a href="{!! url('/auctioneer/'.$dealer->slug) !!}">{{ $dealer->name }}</a></h4>
                <div class="col-md-12 mt-5 mb-5">
                    {{ nl2br($dealer->address) }}
                </div>
            </div>
        @endif
    </div>

    <div class="vehicleDetailWrap_2">


            <a class="View-btn" href="{!! url('/auctioneer/'.$dealer->slug) !!}">View Details<i class="far fa-angle-double-right"></i></a>

    </div>

</div>