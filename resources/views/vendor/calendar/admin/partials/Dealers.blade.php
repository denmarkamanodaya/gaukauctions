<div class="panel panel-flat border-top-info border-bottom-info">
    <div class="panel-heading">
        <h6 class="panel-title">Auctioneer / Dealer Event</h6>
    </div>

    <div class="panel-body">
        This event is for <a target="_blank" href="/admin/dealers/auctioneer/{{$event->cal_eventable->slug}}">{{$event->cal_eventable->name}}</a>
        @if(isset($event) && $event->meta->event_url && $event->meta->event_url != '')
            <a target="_blank" href="{{$event->meta->event_url}}"><i class="far fa-link ml-20"></i> Visit Event Url</a>
        @endif
    </div>
</div>