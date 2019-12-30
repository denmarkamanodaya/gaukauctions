@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script src="{{url('assets/js/ckeditor/ckeditor.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/js/auctioneerEdit.js')}}"></script>
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Auctioneers' => '/admin/dealers/auctioneers', 'Create Auctioneer' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Create Auctioneer</span>
@stop


@section('content')

    <div class="row">
        <div class="col-md-12 mb-10">

            <div class="panel panel-default">
                <div class="panel-heading"></div>

                <div class="panel-body">

                    {!! Form::open(array('method' => 'POST', 'url' => '/admin/dealers/auctioneer/create', 'files' => true,  'autocomplete' => 'off')) !!}

                    <div class="col-md-6">

                        <div class="form-group">
                            {!! Form::label('name', 'Auctioneer Name:', array('class' => 'control-label')) !!}
                            {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'name')) !!}
                            {!!inputError($errors, 'name')!!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('logo', 'Logo:', ['class' => 'control-label']) !!}
                            {!! Form::file('logo') !!}
                            {!!inputError($errors, 'logo')!!}
                        </div>

                        <div class="form-group">
                            <label>
                                {!! Form::checkbox('online_only', 1, null, ['id' => 'online_only']) !!}
                                {!!inputError($errors, 'online_only')!!}
                                Online Only ?
                            </label>
                        </div>

                        <div id="not_online_only">

                            <div class="form-group">
                                {!! Form::label('address', 'Full Address:', array('class' => 'control-label')) !!}
                                {!! Form::textarea('address', null, array('class' => 'form-control', 'id' => 'address')) !!}
                                {!!inputError($errors, 'address')!!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('town', 'Town:', array('class' => 'control-label')) !!}
                                {!! Form::text('town', null, array('class' => 'form-control', 'id' => 'town')) !!}
                                {!!inputError($errors, 'town')!!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('county', 'County:', array('class' => 'control-label')) !!}
                                {!! Form::text('county', null, array('class' => 'form-control', 'id' => 'county')) !!}
                                {!!inputError($errors, 'county')!!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('postcode', 'Post Code:', array('class' => 'control-label')) !!}
                                {!! Form::text('postcode', null, array('class' => 'form-control', 'id' => 'postcode')) !!}
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
                                {!! Form::label('has_streetview', 'Has Google Map Streetview:', array('class' => 'control-label')) !!}
                                {!! Form::select('has_streetview', array('0'=> 'No', '1' => 'Yes'), null, array('class' => 'form-control', 'id' => 'country')) !!}
                                {!!inputError($errors, 'has_streetview')!!}
                            </div>

                        </div>

                        <div class="form-group">
                            {!! Form::label('phone', 'Phone:', array('class' => 'control-label')) !!}
                            {!! Form::text('phone', null, array('class' => 'form-control', 'id' => 'phone')) !!}
                            {!!inputError($errors, 'phone')!!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('email', 'Email:', array('class' => 'control-label')) !!}
                            {!! Form::text('email', null, array('class' => 'form-control', 'id' => 'email')) !!}
                            {!!inputError($errors, 'email')!!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('website', 'Website:', array('class' => 'control-label')) !!}
                            {!! Form::text('website', null, array('class' => 'form-control', 'id' => 'website')) !!}
                            {!!inputError($errors, 'website')!!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('auction_url', 'Auction Url:', array('class' => 'control-label')) !!}
                            {!! Form::text('auction_url', null, array('class' => 'form-control', 'id' => 'auction_url')) !!}
                            {!!inputError($errors, 'auction_url')!!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('online_bidding_url', 'Online Bidding Url:', array('class' => 'control-label')) !!}
                            {!! Form::text('online_bidding_url', null, array('class' => 'form-control', 'id' => 'online_bidding_url')) !!}
                            {!!inputError($errors, 'online_bidding_url')!!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('status', 'Status:', array('class' => 'control-label')) !!}
                            {!! Form::select('status', ['active' => 'Active', 'inactive' => 'Inactive', 'hidden' => 'Hidden on this Site'], null, array('class' => 'form-control', 'id' => 'status')) !!}
                            {!!inputError($errors, 'status')!!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('details', 'Details:', array('class' => 'control-label')) !!}
                            {!! Form::textarea('details', null, array('class' => 'form-control', 'id' => 'details')) !!}
                            {!!inputError($errors, 'details')!!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('buyers_premium', 'Buyers Premium:', array('class' => 'control-label')) !!}
                            {!! Form::text('buyers_premium', null, array('class' => 'form-control', 'id' => 'buyers_premium')) !!}
                            {!!inputError($errors, 'slug')!!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('directions', 'Directions:', array('class' => 'control-label')) !!}
                            {!! Form::textarea('directions', null, array('class' => 'form-control', 'id' => 'directions')) !!}
                            {!!inputError($errors, 'directions')!!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('notes', 'Admin Only Notes:', array('class' => 'control-label')) !!}
                            {!! Form::textarea('notes', null, array('class' => 'form-control', 'id' => 'notes')) !!}
                            {!!inputError($errors, 'notes')!!}
                        </div>



                    </div>

                    <div class="col-md-6">

                        <div class="form-group">
                            <h4>Dealer Categories</h4>
                            @foreach($categories as $catParent)
                                <div class="col-md-12 mb-10">
                                    <h4>{{$catParent->name}}</h4>
                                    @foreach($catParent->children as $category)
                                        <div class="col-md-4">
                                            {!! Form::checkbox('categories[]', $category->id) !!}
                                            {!!$category->name!!}
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach

                            {!!inputError($errors, 'categories')!!}
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <hr>
                                <h4>Dealer Features</h4>
                                @foreach($features as $feature)
                                    {!! Form::checkbox('features[]', $feature->id) !!}
                                    @if(!is_null($feature->icon))
                                    <i class="{{$feature->icon}} ml-5 mr-5"></i>
                                    @endif
                                    {!!$feature->name!!}<br />
                                @endforeach
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <hr>
                                <h4>Parsing</h4>
                                <label>
                                    {!! Form::checkbox('to_parse', 1, null, ['id' => 'to_parse']) !!}
                                    {!!inputError($errors, 'to_parse')!!}
                                    Can the auctioneer be parsed ?
                                </label>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <hr>
                            <h4>Featured Image</h4>
                            <div class="form-group">
                                <input name="featured_image" type="file">
                                <span class="help-block">Accepted formats: gif, png, jpg. Max file size 2Mb</span>

                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <hr>
                                {!! Form::button('<i class="fa fa-save"></i> Create Auctioneer', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}
                                {!! Form::close() !!}
                            </div>
                        </div>



                    </div>

                </div>
            </div>



        </div>

    </div>
@stop


