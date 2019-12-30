<div class="panel panel-flat border-top-info border-bottom-info mb-20">
    <div class="panel-heading">
        <h6 class="panel-title">Set Reminder</h6>
    </div>
    <div class="panel-body">
        Set a reminder for x amount of time from <b>NOW</b>.
        <div class="row">
            <div class="col-md-1 mt-10">
                <div class="form-group">
                        {!! Form::number('remindAmount', null, array('class' => 'form-control', 'id' => 'remindAmount', 'min' => "0")) !!}
                        {!! inputError($errors, 'remindAmount') !!}

                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    {!! Form::select('remindType', ['days' => 'Days', 'weeks' => 'Weeks', 'months' => 'Months', 'years' => 'Years'],null, array('class' => 'form-control mt-10', 'id' => 'remindType', 'required')) !!}
                    {!! inputError($errors, 'remindType') !!}
                </div>
            </div>
        </div>
    </div>
</div>