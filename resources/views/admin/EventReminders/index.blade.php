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
                message: 'Are you sure you want to delete this reminder?',
                callback: function(result) {
                    if (result) {
                        window.location = '{{url('admin/dealers/eventReminders')}}/' + id + '/delete';
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
    {!! breadcrumbs(array('Dashboard' => '/admin/dashboard', 'Dealers' => '/admin/dealers/auctioneers', 'Event Reminders' => 'is_current')) !!}
@stop

@section('page-header')
    <span class="text-semibold">Reminders</span> - Manage Event Reminders
@stop


@section('content')


    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">Reminders</h6>
                    <div class="heading-elements">

                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                <div class="panel-body">
                    &nbsp;&nbsp;
                    {!! Form::open(array('method' => 'POST', 'url' => '/admin/dealers/eventReminders/deleteSelected')) !!}
                    <table class="table datatable-ajax table-bordered table-striped table-hover" id="users-table">
                        <thead>
                        <tr>
                            <th><input type="checkbox" id="checkAll" > Select All</th>
                            <th>Remind On</th>
                            <th>Dealer</th>
                            <th>About</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($reminders as $reminder)
                            <tr>
                                <td>{!! Form::checkbox('deleteSelected[]',$reminder->id, null, array('class' => '')) !!}</td>
                                <td>{{$reminder->remind_on->toDayDateTimeString()}}</td>
                                <td>{{$reminder->remindable->name}}</td>
                                <td>{{$reminder->about}}</td>
                                <td>
                                    &nbsp;<a href="#" class="btn btn-danger btn-labeled confirmDelete" type="button" id="" data-id='{{$reminder->id}}'><b><i class="far fa-times"></i></b> Delete</a>
                                    <a target="_blank" href="{{url('admin/dealers/auctioneer/'.$reminder->remindable->slug.'/events')}}" class="btn bg-info btn-labeled" type="button"><b><i class="far fa-calendar-alt"></i></b> Dealer Events</a>
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
                            Showing {!! $reminders->count() !!} out of {!! $reminders->total() !!}
                        </div>
                        <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_3_paginate">
                            {!! $reminders->render() !!}
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>

@stop


