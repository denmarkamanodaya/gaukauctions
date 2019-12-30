
@if($categories && $categories->count() > 0)
<a class="btn-gauk" data-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1">Toggle filters</a>

<div class="row text-left mt-20 mb-20">
    <div class="col-md-12">
        <div class="collapse multi-collapse" id="multiCollapseExample1">
            <div class="card card-body" style="text-align: left!important;" id="filter-list">

                @foreach($categories as $category)
                    <div class="col-md-3">
                    <span class="categorySelectTitle">{{$category->name}}</span>
                            <span class="">
                                {!! Form::select('category[]', $category->children->pluck('name', 'id'), null, ['class' => 'chosen-select', 'id' => 'category', 'autocomplete' => 'false', 'data-placeholder' => 'Select Categories', 'multiple', 'tabindex' => '-1']) !!}
                            </span>
                    </div>
                @endforeach
                <div class="col-md-4"><a href="#" id="resetFilters" class="ml-20">Reset Filters</a></div>
            </div>
        </div>
    </div>
</div>
@endif