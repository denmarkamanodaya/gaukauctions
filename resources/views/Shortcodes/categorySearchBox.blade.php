<div class="col-md-6">

        <div class="lp-search-bar">
            @if(\Auth::guest())
                {!! Form::open(array('method' => 'POST', 'url' => '/auctioneers', 'class' => 'search-form', 'autocomplete' => 'off')) !!}
            @else
                {!! Form::open(array('method' => 'POST', 'url' => '/members/auctioneers', 'class' => 'search-form', 'autocomplete' => 'off')) !!}
            @endif
<div class="col-md-9">
    <div class="form-group">
        <label for="exampleInputEmail2">What</label>
        {!! Form::select('categories[]', $catList, null, ['class' => 'chosen-select', 'id' => 'categories', 'autocomplete' => 'false', 'data-placeholder' => 'Do you want to see? (Click Here)', 'tabindex' => '-1']) !!}
    </div>
</div>
<div class="col-md-3">
    <button type="submit" class="btn-gauk"><i class="far fa-search"></i> Search</button>
</div>

            {!! Form::close() !!}

        </div>

</div>