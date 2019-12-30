@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script>
        $('#addressDelete').submit(function(e) {
            var currentForm = this;
            e.preventDefault();
            bootbox.confirm({
                title: 'Delete Confirmation',
                message: 'Are you sure you want to delete this address?',
                callback: function(result) {
                    if (result) {
                        currentForm.submit();
                    }
                }
            });
        });


    </script>
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Auctioneers' => '/admin/dealers/auctioneers/', $auctioneer->name => '/admin/dealers/auctioneer/'.$auctioneer->slug.'/edit', 'Auction Address' => '/admin/dealers/auctioneer/'.$auctioneer->slug.'/edit#addresses', 'Edit Address' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Edit Auction Address</span>
@stop


@section('content')

    <div class="row">
        <div class="col-md-8 col-md-offset-2 mb-10">

            <div class="panel panel-default">
                <div class="panel-heading"></div>

                <div class="panel-body">

                    {!! Form::model($address, array('method' => 'POST', 'url' => '/admin/dealers/auctioneer/'.$auctioneer->slug.'/address/'.$address->id.'/edit', 'autocomplete' => 'off')) !!}

                    <div class="col-md-12">

                        <div class="form-group">
                            {!! Form::label('name', 'Address Name:', array('class' => 'control-label')) !!}
                            {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'name', 'required')) !!}
                            {!!inputError($errors, 'name')!!}
                            <span class="help-block">Set a name for this address.</span>
                        </div>

                            <div class="form-group">
                                {!! Form::label('address', 'Full Address:', array('class' => 'control-label')) !!}
                                {!! Form::textarea('address', null, array('class' => 'form-control', 'id' => 'address','required')) !!}
                                {!!inputError($errors, 'address')!!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('town', 'Town:', array('class' => 'control-label')) !!}
                                {!! Form::text('town', null, array('class' => 'form-control', 'id' => 'town', 'required')) !!}
                                {!!inputError($errors, 'town')!!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('county', 'County:', array('class' => 'control-label')) !!}
                                {!! Form::text('county', null, array('class' => 'form-control', 'id' => 'county', 'required')) !!}
                                {!!inputError($errors, 'county')!!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('postcode', 'Post Code:', array('class' => 'control-label')) !!}
                                {!! Form::text('postcode', null, array('class' => 'form-control', 'id' => 'postcode', 'required')) !!}
                                {!!inputError($errors, 'postcode')!!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('country_id', 'Country:', array('class' => 'control-label')) !!}
                                {!! Form::select('country_id', $countries, 826, array('class' => 'form-control', 'id' => 'country_id')) !!}
                                {!!inputError($errors, 'country_id')!!}
                            </div>


                            <div class="form-group">
                                {!! Form::label('longitude', 'Longitude: (Automatically filled using postcode)', array('class' => 'control-label')) !!}
                                {!! Form::text('longitude', null, array('class' => 'form-control', 'id' => 'longitude')) !!}
                                {!!inputError($errors, 'longitude')!!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('latitude', 'Latitude: (Automatically filled using postcode)', array('class' => 'control-label')) !!}
                                {!! Form::text('latitude', null, array('class' => 'form-control', 'id' => 'latitude')) !!}
                                {!!inputError($errors, 'latitude')!!}
                            </div>



                        <div class="form-group">
                            {!! Form::label('phone', 'Phone:', array('class' => 'control-label')) !!}
                            {!! Form::text('phone', null, array('class' => 'form-control', 'id' => 'phone')) !!}
                            {!!inputError($errors, 'phone')!!}
                        </div>


                        <div class="form-group">
                            {!! Form::label('auction_url', 'Auction Url:', array('class' => 'control-label')) !!}
                            {!! Form::text('auction_url', null, array('class' => 'form-control', 'id' => 'auction_url')) !!}
                            {!!inputError($errors, 'auction_url')!!}
                        </div>


                        <div class="col-md-12">
                            <div class="form-group">
                                <hr>
                                {!! Form::button('<i class="fa fa-save"></i> Edit Address', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}
                                {!! Form::close() !!}
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <div class="panel panel-default">

                <div class="panel-body">

                    {!! Form::open(array('method' => 'POST', 'url' => '/admin/dealers/auctioneer/'.$auctioneer->slug.'/address/'.$address->id.'/delete', 'autocomplete' => 'off', 'id' => 'addressDelete')) !!}


                        <div class="col-md-12">
                            <div class="form-group">
                                <p>Click the below button to delete this address.</p>
                                <hr>
                                {!! Form::button('<i class="far fa-times"></i> Delete Address', array('type' => 'submit', 'class' => 'btn btn-warning')) !!}
                                {!! Form::close() !!}
                            </div>
                        </div>



                </div>
            </div>

        </div>

    </div>
@stop


