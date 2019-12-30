<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-20 mb-20">
    @if(Auth::user()->hasRole(Settings::get('main_content_role')))
        <div class="col-md-12" style="text-align: right">
        <a class="btn-gauk" data-toggle="collapse" href="#searchCollapse" role="button" aria-expanded="false" aria-controls="searchCollapse">Toggle Search Filters</a>
        </div>
        <div class="collapse multi-collapse" id="searchCollapse">
        <div class="cs-listing-filters mt-20">
            <div class="cs-search">
                {!! Form::open(array('method' => 'POST', 'url' => '/members/auctioneers', 'class' => 'search-form', 'autocomplete' => 'off')) !!}

                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="loction-search vehicle-search">
                        {!! Form::text('name', '', ['class' => '', 'id' => 'search', 'autocomplete' => 'false', 'placeholder' => 'Name', 'tabindex' => '-1']) !!}
                        {!!inputError($errors, 'name')!!}
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="select-input select-auctioneer">
                        {!! Form::select('auctioneer', $dealerList, 0, ['class' => 'chosen-select', 'id' => 'auctioneerList', 'autocomplete' => 'false', 'data-placeholder' => 'Select Auctioneer', 'tabindex' => '-1']) !!}
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="select-input select-location">
                        {!! Form::select('location', $dealerCounties, 0, ['class' => 'chosen-select', 'id' => 'auctioneer', 'autocomplete' => 'false', 'data-placeholder' => 'Select Auctioneer', 'tabindex' => '-1']) !!}
                    </div>
                </div>
                <hr>
                <div class="cs-filter-title">
                    <h5>Categories</h5>
                </div>
                @foreach($catList as $category => $categoryItems)
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="select-multi mb-10">
                            <span class="categorySelectTitle">{{$category}}</span>
                            {!! Form::select('categories[]', $categoryItems, 0, ['class' => 'chosen-select', 'id' => 'auctioneer', 'autocomplete' => 'false', 'data-placeholder' => 'Select Categories', 'multiple', 'tabindex' => '-1']) !!}
                        </div>
                    </div>
                @endforeach
                <div class="cs-field-holder text-center mt-20">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="cs-btn-submit">
                            <input type="submit" value="Filter Auctioneers">
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>

        </div>
        </div>
    @else
        @include('members.NeedUpgrade.horizontalBannerUpgrade')
    @endif
</div>