<div class="auto-listing auto-grid dealerCard">
    <div class="col-md-12 dealerInfoWrapMin">
        @if($dealer->logo)

        <div class="col-lg-12 col-md-12 col-sm-6 col-xs-12">
            <div class="text-center"><a @if(isset($dealer->markerNumber))onmouseover="google.maps.event.trigger(maps[0].markers[{{$dealer->markerNumber}}], 'mouseover');" onmouseout="google.maps.event.trigger(maps[0].markers[{{$dealer->markerNumber}}], 'mouseout');" @endif href="{!! url('/auctioneer/'.$dealer->slug) !!}"><img class="img-responsive" src="{!! dealerLogoUrl($dealer) !!}" alt="{{ $dealer->name }}"></a></div>
            <h4 class="mt-10"><a @if(isset($dealer->markerNumber))onmouseover="google.maps.event.trigger(maps[0].markers[{{$dealer->markerNumber}}], 'mouseover');" onmouseout="google.maps.event.trigger(maps[0].markers[{{$dealer->markerNumber}}], 'mouseout');" @endif href="{!! url('/auctioneer/'.$dealer->slug) !!}">{{ $dealer->name }}</a></h4>
            <div class="col-md-12 mt-5 mb-5">
                @include('frontend.NeedRegister.textAuctioneerOneLine')
            </div>
        </div>
        @else
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h4 class="mt-10"><a @if(isset($dealer->markerNumber))onmouseover="google.maps.event.trigger(maps[0].markers[{{$dealer->markerNumber}}], 'mouseover');" onmouseout="google.maps.event.trigger(maps[0].markers[{{$dealer->markerNumber}}], 'mouseout');" @endif href="{!! url('/auctioneer/'.$dealer->slug) !!}">{{ $dealer->name }}</a></h4>
                <div class="col-md-12 mt-5 mb-5">
                    @include('frontend.NeedRegister.textAuctioneerOneLine')
                </div>
            </div>
        @endif
    </div>

    <div class="vehicleDetailWrap_2">
            <a class="View-btn" href="{!! url('/auctioneer/'.$dealer->slug) !!}">View Details<i class="far fa-angle-double-right"></i></a>
    </div>

</div>