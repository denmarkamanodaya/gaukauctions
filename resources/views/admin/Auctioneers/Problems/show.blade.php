@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script>
        $('.confirmDelete').on('click', function() {
            var id = $(this).data('id');
            bootbox.confirm({
                title: 'Delete Confirmation',
                message: 'Are you sure you want to delete this problem?',
                callback: function(result) {
                    if (result) {
                        window.location = '{{url('admin/dealers/problem')}}/' + id + '/delete';
                    }
                }
            });
        });
        $('#checkAll').click(function () {
            $('input:checkbox').prop('checked', this.checked);
        });
    </script>

@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Dealers' => '/admin/dealers/auctioneers', 'Problems' => '/admin/dealers/problems', 'Problem' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Problems</span> - Manage Reported Problem
@stop


@section('content')


    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            @include('admin.Auctioneers.Problems.partials.DealersHeader')

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Problem</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">

                    {!! nl2br(e($problem->about))!!}

                    <div class="col-md-12 mt-20 text-center">
                        <a href="#" class="btn btn-danger btn-labeled confirmDelete" type="button" id="" data-id='{{$problem->id}}'><b><i class="far fa-times"></i></b> Delete Problem</a>
                    </div>

                </div>
            </div>

        </div>

    </div>

@stop


