@if($dealer->calendarEvents && $dealer->calendarEvents->count() > 0)





<div class="panel panel-flat border-top-info border-bottom-info mb-20">
    <div class="panel-heading">
        <h6 class="panel-title">Extra Start Dates</h6>
    </div>
    <div class="panel-body">
        The below dates are from existing events, tick any that you want to use as an event date. Please note you will still need to create the initial start date below..
        <div class="row">

            @foreach($dealer->calendarEvents as $event)
                <div class="col-md-3 mt-10">
                <div class="form-group" style="">
                    {!! Form::checkbox('extra_start_dates[]', $event->start_day->format('Y-m-d'), false, array('class' => 'styled')) !!} {{$event->start_day->format('D jS F, Y')}}
                </div>
                </div>
            @endforeach

        </div>
    </div>
</div>
@endif