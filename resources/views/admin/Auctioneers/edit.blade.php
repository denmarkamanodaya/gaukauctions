@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script src="{{url('assets/js/ckeditor/ckeditor.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/js/auctioneerEdit.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/js/dropzone.js')}}"></script>
    <script>
        var deleteUrl = "{{url('/admin/dealers/auctioneer/'.$auctioneer->slug.'/gallery/delete')}}";
        var token = "{{csrf_token()}}";
        $('.mediaDelete').click(function () {
            var media = this.id;
            console.log(media);
            $.ajax({
                type: "POST",
                url: deleteUrl,
                data: { image: media, _token : token }
            }).done(function () {
                $('#media_'+media).hide();
            });
        })

        $('#dealerDelete').submit(function(e) {
            var currentForm = this;
            e.preventDefault();
            bootbox.confirm({
                title: 'Delete Confirmation',
                message: 'Are you sure you want to delete this auctioneer?',
                callback: function(result) {
                    if (result) {
                        currentForm.submit();
                    }
                }
            });
        });


        var hash = window.location.hash;

        if (hash) {
            var selectedTab = $('.nav li a[href="' + hash + '"]');
            selectedTab.trigger('click', true);
        }

    </script>
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Auctioneers' => '/admin/dealers/auctioneers', 'Auctioneer' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Auctioneer</span>
@stop


@section('content')

    <div class="row">
        <div class="col-md-12 mb-10">
            <div class="panel panel-flat border-top-info border-bottom-info">
                <div class="panel-heading">
                    <h6 class="panel-title">Dealer :  {{$auctioneer->name}}</h6>
                </div>

                <div class="panel-body">
                    @if($auctioneer->website && $auctioneer->website != '')
                        <a target="_blank" href="{{$auctioneer->website}}" class="btn bg-success btn-labeled ml-20" type="button"><b><i class="far fa-gavel"></i></b> Visit Dealer</a>
                    @endif
                        <a target="_blank" href="{{url('/admin/dealers/auctioneer/'.$auctioneer->slug.'/events')}}" class="btn bg-info btn-labeled ml-20" type="button"><b><i class="far fa-calendar-alt"></i></b> Events</a>

                </div>
            </div>

            <div class="navbar navbar-default navbar-component navbar-xs">
                <ul class="nav navbar-nav visible-xs-block">
                    <li class="full-width text-center"><a data-target="#navbar-filter" data-toggle="collapse"><i class="icon-menu7"></i></a></li>
                </ul>

                <div id="navbar-filter" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav element-active-slate-400">
                        <li class="active"><a data-toggle="tab" href="#dealerdetails" aria-expanded="false"><i class="far fa-user position-left"></i> Details</a></li>
                        <li class=""><a data-toggle="tab" href="#gallery" aria-expanded="true"><i class="far fa-money-bill-alt position-left"></i> Gallery</a></li>
                        <li class=""><a data-toggle="tab" href="#addresses" aria-expanded="true"><i class="far fa-map-marker-alt position-left"></i> Auction Addresses</a></li>
                    </ul>
                </div>
            </div>


            <div class="tabbable">
                <div class="tab-content">

                    <div id="dealerdetails" class="tab-pane fade active in">
                        <div class="panel panel-default">
                            <div class="panel-heading"></div>

                            <div class="panel-body">

                                {!! Form::model($auctioneer,array('method' => 'POST', 'url' => '/admin/dealers/auctioneer/'. $auctioneer->id . '/edit', 'files' => true,  'autocomplete' => 'off')) !!}

                                <div class="col-md-6">

                                    <div class="form-group">
                                        {!! Form::label('name', 'Auctioneer Name:', array('class' => 'control-label')) !!}
                                        {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'name')) !!}
                                        {!!inputError($errors, 'name')!!}
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('logo', 'Logo:', ['class' => 'control-label']) !!}
                                        @if($auctioneer->logo)
                                            <span class="logoPreview"><img class='img-responsive' src="{{dealerLogoUrl($auctioneer, 150)}}"></span>
                                            <div class="form-group">{!! Form::checkbox('delPicture', '1', false) !!}
                                                Delete Image<br /></div>
                                        @endif
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
                                            {!! Form::select('country_id', $countries, null, array('class' => 'form-control', 'id' => 'country_id')) !!}
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
                                                        {!! Form::checkbox('categories[]', $category->id, $auctioneer->categories->contains($category->id) ? true : false) !!}
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
                                                {!! Form::checkbox('features[]', $feature->id, $auctioneer->features->contains($feature->id) ? true : false) !!}
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
                                        @foreach($auctioneer->media as $media)
                                            @if($media->area == 'featured')
                                                <img id="holder" class="mt-10 mb-10" style="max-height:100px;" src="{!! url($media->name) !!}">
                                            <div class="form-group">
                                                {!! Form::checkbox('remove_featured_image', 1, null, ['id' => 'remove_featured_image']) !!}
                                                Remove featured image?
                                            </div>
                                            @endif
                                        @endforeach
                                        <div class="form-group">
                                            <input name="featured_image" type="file">
                                            <span class="help-block">Accepted formats: gif, png, jpg. Max file size 2Mb</span>

                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <hr>
                                        <h4>Hero Image</h4>
                                        @foreach($auctioneer->media as $media)
                                            @if($media->area == 'hero')
                                                <img id="holder" class="mt-10 mb-10" style="max-height:100px;" src="{!! url($media->name) !!}">
                                                <div class="form-group">
                                                    {!! Form::checkbox('remove_hero_image', 1, null, ['id' => 'remove_hero_image']) !!}
                                                    Remove hero image?
                                                </div>
                                            @endif
                                        @endforeach
                                        <div class="form-group">
                                            <input name="hero_image" type="file">
                                            <span class="help-block">Accepted formats: gif, png, jpg. Max file size 2Mb</span>

                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <hr>
                                            {!! Form::button('<i class="far fa-save"></i> Edit Auctioneer', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}
                                            {!! Form::close() !!}
                                        </div>
                                    </div>

<div class="col-md-12 mt-20">
    <hr>
    <h2>Delete Auctioneer</h2>
    Click below to delete this auctioneer.
    {!! Form::model($auctioneer,array('method' => 'POST', 'url' => '/admin/dealers/auctioneer/'. $auctioneer->slug . '/delete', 'autocomplete' => 'off', 'id' => 'dealerDelete')) !!}
    {!! Form::button('<i class="far fa-times"></i> Delete this Auctioneer', array('type' => 'submit', 'class' => 'btn btn-warning')) !!}
    {!! Form::close() !!}
</div>

                                </div>


                            </div>
                        </div>

                    </div>


                    <div id="gallery" class="tab-pane fade">

                        <div class="panel panel-default">
                            <div class="panel-heading"></div>

                            <div class="panel-body">
                                {!! Form::open(array('method' => 'POST', 'url' => '/admin/dealers/auctioneer/'.$auctioneer->slug.'/gallery/upload', 'autocomplete' => 'false', 'class' => 'dropzone', 'id' => 'dropzoneupload')) !!}
                                {!! Form::close() !!}
                            </div>

                            <div class="row">
                                @if($auctioneer->media)
                                    <div class="col-md-12">
                                        @foreach($auctioneer->media as $media)
                                            @if($media->area == 'gallery')
                                            <div class="col-lg-3" id="media_{{$media->id}}"><img class="img-responsive" src="{{url('images/dealers/'.$auctioneer->id.'/'.$media->name)}}"><br>
                                            <button class="btn btn-danger mediaDelete" id="{{$media->id}}">Delete</button>
                                            </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>

                    <div id="addresses" class="tab-pane fade">

                        <div class="panel panel-default">
                            <div class="panel-heading"></div>

                            <div class="panel-body">

                                <a href="{{url('/admin/dealers/auctioneer/'.$auctioneer->slug.'/address/create')}}">
                                    <button class="btn bg-teal-400 btn-labeled mb-20" type="button">
                                        <b><i class="far fa-map-marker-alt"></i></b>Create New Auction Address
                                    </button>
                                </a>

                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th class="col-md-8">Name</th>
                                        <th class="col-md-2">Updated On</th>
                                        <th class="col-md-2"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($auctioneer->addresses as $address)

                                        <tr>
                                            <td>{{ $address->name }}</td>
                                            <td>{{ $feature->updated_at->diffForHumans() }}</td>
                                            <td><a href='{{ url("admin/dealers/auctioneer/".$auctioneer->slug."/address/".$address->id."/edit") }}' class="btn btn-primary btn-icon btn-xs" type="button"><i class="far fa-edit"></i> Manage</a>
                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>

                            </div>


                        </div>

                    </div>

                </div>
            </div>








        </div>

    </div>
@stop


