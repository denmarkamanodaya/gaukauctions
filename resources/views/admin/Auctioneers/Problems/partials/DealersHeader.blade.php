<div class="panel panel-flat border-top-info border-bottom-info">
    <div class="panel-heading">
        <h6 class="panel-title">Dealer: {{$problem->problemable->name}}</h6>
    </div>
    <div class="panel-body">
        <div class="col-md-6">
        <a target="_blank" href="{{url('admin/dealers/auctioneer/'.$problem->problemable->slug.'/edit')}}" class="btn bg-info btn-labeled" type="button"><b><i class="far fa-gavel"></i></b> Edit Dealer</a>
        @if($problem->problemable->website && $problem->problemable->website != '')
        <a target="_blank" href="{{$problem->problemable->website}}"><i class="far fa-link ml-20"></i> Visit Dealer</a>
        @endif
        </div>
    </div>
</div>