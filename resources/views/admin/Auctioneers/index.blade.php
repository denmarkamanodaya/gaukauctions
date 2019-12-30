@extends('base::admin.Template')


@section('page_title', Settings::get('site_name'))

@section('meta')
@stop

@section('page_js')
    <script src="//cdn.datatables.net/plug-ins/1.10.16/pagination/full_numbers_no_ellipses.js"></script>
    <script>
        $(function() {
            $('#auctioneers-table').DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                "pagingType": "full_numbers_no_ellipses",
                ajax: '{!! route('admin_auctioneers_data') !!}',
                columns: [
                    {data: 'logo', name: 'logo', orderable: false, searchable: false},
                    {data: 'featured', name: 'featured', orderable: false, searchable: false},
                    {data: 'name', name: 'name'},
                    {data: 'calendar_events_count', name: 'calendar_events_count', searchable: false},
                    {data: 'to_parse', name: 'to_parse', searchable: false},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                order: [[ 2, 'asc' ]]
            });
        });
    </script>
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Auctioneers' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Auctioneers</span>
@stop


@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title"></h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">
                    <a href="{{url('/admin/dealers/auctioneer/create')}}">
                        <button class="btn bg-teal-400 btn-labeled mb-20" type="button">
                            <b><i class="far fa-gavel"></i></b>Create New Auctioneer
                        </button>
                    </a>


                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="auctioneers-table">
                        <thead>
                        <tr>
                            <th class="col-md-1 text-center">Logo</th>
                            <th class="col-md-1 text-center">Featured</th>
                            <th class="col-md-3">Name</th>
                            <th class="col-md-1 text-center">Events</th>
                            <th class="col-md-1 text-center">Parsable</th>
                            <th class="col-md-1">Created</th>
                            <th class="col-md-3">Action</th>
                        </tr>
                        </thead>

                    </table>



                </div>
            </div>

        </div>

    </div>
@stop


