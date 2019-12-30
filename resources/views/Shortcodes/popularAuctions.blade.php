<?php
$isMember = Auth::check();
?>

@if($dealers->count() > 0)

    @foreach($dealers as $dealer)
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            @if($isMember)
                @include('members.Dealers.partials.dealerCardFull')
            @else
                @include('frontend.Dealers.partials.dealerCardFull')
            @endif

        </div>
    @endforeach

@endif
