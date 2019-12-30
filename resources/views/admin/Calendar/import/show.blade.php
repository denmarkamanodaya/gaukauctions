@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))
@section('body_class', '')

@section('meta')
@stop

@section('page_js')
    <script src="{{url('assets/js/ckeditor/ckeditor.js')}}"></script>
    <script type="text/javascript" src="{{url('/vendor/laravel-filemanager/js/lfm.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/js/flatpickr/dist/flatpickr.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/js/calendar.js')}}"></script>

    <script>
        $('#getAddress').submit(function(event) {
            event.preventDefault();
            var form = $(this);
            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize()
            }).done(function(data) {
                $('#address').val(data["address"]);
                $('#county').val(data["county"]);
                $('#country_id').val(data["country_id"]);
                $('#postcode').val(data["postcode"]);
                $('#latitude').val(data["latitude"]);
                $('#longitude').val(data["longitude"]);

                if (data["auction_url"] == null){
                    $('#event_url').val(data["website"]);
                } else {
                    $('#event_url').val(data["auction_url"]);
                }
            })
        });
    </script>
@stop

@section('page_css')
    <link rel="stylesheet" href="{{url('assets/js/flatpickr/dist/flatpickr.css')}}" />
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Calendar' => '/admin/calendar', 'Import' => '/admin/calendar/import', 'Import Post' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Calendar Import Post</span>
@stop


@section('content')

    <div class="row">

        <div class="col-lg-10 col-md-offset-1">

            <div class="panel panel-flat border-top-info border-bottom-info">
                <div class="panel-heading">
                    <h6 class="panel-title">Dealer :  {{$dealer->name}}</h6>
                </div>

                <div class="panel-body">
                    <div class="col-md-6">
                    <a target="_blank" href="{{url('admin/dealers/auctioneer/'.$dealer->slug.'/events/')}}" class="btn bg-info btn-labeled" type="button"><b><i class="far fa-calendar-alt"></i></b> View Dealer Calendar Events</a>
                    @if($dealer->website && $dealer->website != '')
                        <a target="_blank" href="{{$dealer->website}}" class="btn bg-success btn-labeled ml-20" type="button"><b><i class="far fa-gavel"></i></b> Visit Dealer</a>
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

            <div class="panel panel-flat">
                <div class="panel-heading">
                    <div class="heading-elements">

                    </div>
                </div>

                <div class="panel-body">

                    {!! Form::model($dealer, array('method' => 'POST', 'url' => '/admin/dealers/auctioneer/'.$dealer->slug.'/event/create')) !!}
                    {!! Form::hidden('import', $post->id) !!}

                    @include('admin.Auctioneers.Events.partials.ReminderForm')
                    @include('admin.Calendar.import.createForm')

                    {!! Form::close() !!}


                </div>
            </div>


            <div class="panel panel-flat border-top-info border-bottom-info">
                <div class="panel-heading">
                    <h6 class="panel-title">No Event Needed ?</h6>
                </div>

                <div class="panel-body">
                    <p>If you do not want to create an event with this import profile then hit the below button to remove it from the import list.</p>
                    {!! Form::open(array('method' => 'POST', 'url' => '/admin/calendar/import/markComplete')) !!}
                    {!! Form::hidden('import', $post->id) !!}
                    <button type="submit" class="btn btn-info">Only Mark Event As Complete<i class="far fa-check position-right"></i></button>
                    {!! Form::close() !!}
                </div>
            </div>


        </div>

    </div>



@stop


