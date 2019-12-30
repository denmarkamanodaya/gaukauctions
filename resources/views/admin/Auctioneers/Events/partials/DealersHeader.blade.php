<div class="panel panel-flat border-top-info border-bottom-info">
    <div class="panel-heading">
        <h6 class="panel-title">Auctioneer / Dealer Event</h6>
    </div>
    <div class="panel-body">
        <div class="col-md-6">
        This event is for <a target="_blank" href="/admin/dealers/auctioneer/{{$dealer->slug}}/edit">{{$dealer->name}}</a>
        @if($dealer->website && $dealer->website != '')
        <a target="_blank" href="{{$dealer->website}}"><i class="far fa-link ml-20"></i> Visit Auctioneer</a>
        @endif
            @if(isset($event) && $event->meta != null)
                @if($event->meta->event_url && $event->meta->event_url != '')
                    <a target="_blank" href="{{$event->meta->event_url}}"><i class="far fa-link ml-20"></i> Visit Event Url</a>
                @endif
            @endif
        </div>
        <div class="col-md-6 text-right">
            {!! Form::model($dealer, array('method' => 'POST', 'url' => '/admin/dealers/auctioneer/'.$dealer->slug.'/getAddress', 'class' => 'form-inline', 'id' => 'getAddress')) !!}
            Set Address as <select name="addresses" class="form-control ml-5 mr-5">
                <option value="0">Main Auctioneer Address</option>
                @if($dealer->addresses && $dealer->addresses->count() > 0)
                    @foreach($dealer->addresses as $address)
                        <option value="{{$address->id}}">{{$address->name}}</option>
                    @endforeach
                @endif
            </select>
            <button type="submit" class="btn btn-info">Set<i class="far fa-check position-right"></i></button>
            {!! Form::close() !!}
        </div>
    </div>
</div>