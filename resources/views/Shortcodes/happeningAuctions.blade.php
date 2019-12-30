<?php
$pageRoute = Auth::check() ? '/members' : '';
?>

<div class="col-md-6 col-sm-6  col-xs-12">
    <div class="city-girds lp-border-radius-8">
        <div class="city-thumb"><img alt="{{$cityArray[0]['name']}}" src="{{$cityArray[0]['image']}}" /></div>

        <div class="city-title text-center">
            <h3 class="lp-h3"><a href="{{$pageRoute}}/auctioneers?location={{$cityArray[0]['slug']}}">{{$cityArray[0]['name']}}</a></h3>
            <label class="lp-listing-quantity">{{$cityCount[$cityArray[0]['name']] or 0}} Listings</label></div>
        <a class="overlay-link" href="{{$pageRoute}}/auctioneers?location={{$cityArray[0]['slug']}}">&nbsp;</a></div>
</div>

<div class="col-md-6 col-sm-6  col-xs-12">
    <div class="col-md-12 col-sm-12  col-xs-12">
        <div class="city-girds lp-border-radius-8">
            <div class="city-thumb"><img alt="{{$cityArray[1]['name']}}" src="{{$cityArray[1]['image']}}" /></div>

            <div class="city-title text-center">
                <h3 class="lp-h3"><a href="{{$pageRoute}}/auctioneers?location={{$cityArray[1]['slug']}}">{{$cityArray[1]['name']}}</a></h3>
                <label class="lp-listing-quantity">{{$cityCount[$cityArray[1]['name']] or 0}} Listings</label></div>
            <a class="overlay-link" href="{{$pageRoute}}/auctioneers?location={{$cityArray[1]['slug']}}">&nbsp;</a></div>
    </div>

    <div class="col-md-6 col-sm-12 col-xs-12">
        <div class="city-girds lp-border-radius-8">
            <div class="city-thumb"><img alt="{{$cityArray[2]['name']}}" src="{{$cityArray[2]['image']}}" /></div>

            <div class="city-title text-center">
                <h3 class="lp-h3"><a href="{{$pageRoute}}/auctioneers?location={{$cityArray[2]['slug']}}">{{$cityArray[2]['name']}}</a></h3>
                <label class="lp-listing-quantity">{{$cityCount[$cityArray[2]['name']] or 0}} Listings</label></div>
            <a class="overlay-link" href="{{$pageRoute}}/auctioneers?location={{$cityArray[2]['slug']}}">&nbsp;</a></div>
    </div>

    <div class="col-md-6 col-sm-12 col-xs-12">
        <div class="city-girds lp-border-radius-8">
            <div class="city-thumb"><img alt="{{$cityArray[3]['name']}}" src="{{$cityArray[3]['image']}}" /></div>

            <div class="city-title text-center">
                <h3 class="lp-h3"><a href="{{$pageRoute}}/auctioneers?location={{$cityArray[3]['slug']}}">{{$cityArray[3]['name']}}</a></h3>
                <label class="lp-listing-quantity">{{$cityCount[$cityArray[3]['name']] or 0}} Listings</label></div>
            <a class="overlay-link" href="{{$pageRoute}}/auctioneers?location={{$cityArray[3]['slug']}}">&nbsp;</a></div>
    </div>
</div>

<div class="col-md-3 col-sm-3 col-xs-12">
    <div class="city-girds lp-border-radius-8">
        <div class="city-thumb"><img alt="{{$cityArray[4]['name']}}" src="{{$cityArray[4]['image']}}" /></div>

        <div class="city-title text-center">
            <h3 class="lp-h3"><a href="{{$pageRoute}}/auctioneers?location={{$cityArray[4]['slug']}}">{{$cityArray[4]['name']}}</a></h3>
            <label class="lp-listing-quantity">{{$cityCount[$cityArray[4]['name']] or 0}} Listings</label></div>
        <a class="overlay-link" href="{{$pageRoute}}/auctioneers?location={{$cityArray[4]['slug']}}">&nbsp;</a></div>
</div>

<div class="col-md-3 col-sm-3 col-xs-12">
    <div class="city-girds lp-border-radius-8">
        <div class="city-thumb"><img alt="{{$cityArray[5]['name']}}" src="{{$cityArray[5]['image']}}" /></div>

        <div class="city-title text-center">
            <h3 class="lp-h3"><a href="{{$pageRoute}}/auctioneers?location={{$cityArray[5]['slug']}}">{{$cityArray[5]['name']}}</a></h3>
            <label class="lp-listing-quantity">{{$cityCount[$cityArray[5]['name']] or 0}} Listings</label></div>
        <a class="overlay-link" href="{{$pageRoute}}/auctioneers?location={{$cityArray[5]['slug']}}">&nbsp;</a></div>
</div>

<div class="col-md-3 col-sm-3 col-xs-12">
    <div class="city-girds lp-border-radius-8">
        <div class="city-thumb"><img alt="{{$cityArray[6]['name']}}" src="{{$cityArray[6]['image']}}" /></div>

        <div class="city-title text-center">
            <h3 class="lp-h3"><a href="{{$pageRoute}}/auctioneers?location=={{$cityArray[6]['slug']}}">{{$cityArray[6]['name']}}</a></h3>
            <label class="lp-listing-quantity">{{$cityCount[$cityArray[6]['name']] or 0}} Listings</label></div>
        <a class="overlay-link" href="{{$pageRoute}}/auctioneers?location={{$cityArray[6]['slug']}}">&nbsp;</a></div>
</div>

<div class="col-md-3 col-sm-3 col-xs-12">
    <div class="city-girds lp-border-radius-8">
        <div class="city-thumb"><img alt="{{$cityArray[7]['name']}}" src="{{$cityArray[7]['image']}}" /></div>

        <div class="city-title text-center">
            <h3 class="lp-h3"><a href="{{$pageRoute}}/auctioneers?location={{$cityArray[7]['slug']}}">{{$cityArray[7]['name']}}</a></h3>
            <label class="lp-listing-quantity">{{$cityCount[$cityArray[7]['name']] or 0}} Listings</label></div>
        <a class="overlay-link" href="{{$pageRoute}}/auctioneers?location={{$cityArray[7]['slug']}}">&nbsp;</a></div>
</div>
