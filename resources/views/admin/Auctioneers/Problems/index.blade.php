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
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Dealers' => '/admin/dealers/auctioneers', 'Problems' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Problems</span> - Manage Reported Problems
@stop


@section('content')


    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Problems</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">
                    &nbsp;&nbsp;
                    {!! Form::open(array('method' => 'POST', 'url' => '/admin/dealers/problems/deleteSelected')) !!}
                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="users-table">
                        <thead>
                        <tr>
                            <th class="col-md-1"><input type="checkbox" id="checkAll" > Select All</th>
                            <th class="col-md-5">Dealer</th>
                            <th class="col-md-2">Reported</th>
                            <th class="col-md-4">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($problems as $problem)
                            <tr>
                                <td>{!! Form::checkbox('deleteSelected[]',$problem->id, null, array('class' => '')) !!}</td>
                                <td>{{$problem->problemable->name}}</td>
                                <td>{{$problem->updated_at->diffForHumans()}}</td>
                                <td>
                                    &nbsp;<a href="{{url('admin/dealers/problem/'.$problem->id)}}" class="btn bg-info btn-labeled" type="button"><b><i class="far fa-eye"></i></b> Show Problem</a>
                                    &nbsp;<a href="#" class="btn btn-danger btn-labeled confirmDelete" type="button" id="" data-id='{{$problem->id}}'><b><i class="far fa-times"></i></b> Delete Problem</a>
                                    <a target="_blank" href="{{url('admin/dealers/auctioneer/'.$problem->problemable->slug.'/edit')}}" class="btn bg-info btn-labeled" type="button"><b><i class="far fa-gavel"></i></b> Edit Dealer</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-md-12 mt-10 mb-10">
                            <button type="submit" class="btn btn-warning"><i class="far fa-times"></i> Delete Selected</button>
                        </div>

                    </div>
                    {!! Form::close() !!}

                    <div class="datatable-footer">
                        <div class="dataTables_info" id="DataTables_Table_3_info" role="status" aria-live="polite">
                            Showing {!! $problems->count() !!} out of {!! $problems->total() !!}
                        </div>
                        <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_3_paginate">
                            {!! $problems->render() !!}
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>

@stop


