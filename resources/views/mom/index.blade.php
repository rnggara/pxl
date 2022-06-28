@extends('layouts.template')
@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
                <h3>Minutes Of Meeting (MOM)</h3>
            </div>
            @actionStart('mom', 'create')
            <div class="card-toolbar">
                <!--end::Button-->
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addItem"><i class="fa fa-plus"></i>Add MOM</button>
                </div>
            </div>
            @actionEnd
        </div>
        <div class="card-body">
            <!--begin: Datatable-->
            <table class="table table-bordered table-hover table-checkable" id="kt_datatable1" style="margin-top: 13px !important">
                <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Meeting Date | Time</th>
                    <th class="text-center">Estimate Date | Time</th>
                    <th class="text-center">Meeting Num</th>
                    <th>Topic</th>
                    <th>Location</th>
                    <th>Created by</th>
                    <th class="text-center">Action</th>
                    <th class="text-center"></th>
                </tr>
                </thead>
            </table>
            <!--end: Datatable-->
        </div>
    </div>
    <div class="modal fade" id="addItem" tabindex="-1" role="dialog" aria-labelledby="addItem" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Minutes Of Meeting (MOM)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" id="form-add" action="{{route('mom.store')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Topic</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" placeholder="Topic" name="topic" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Location</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" placeholder="Location" name="location" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Meeting Date Time</label>
                                    <div class="col-md-5">
                                        <input type="date" class="form-control" placeholder="start date" name="start_date" required>
                                        <span><small><i>start date</i></small></span>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="time" class="form-control" placeholder="start time" name="start_time" required>
                                        <span><small><i>start time</i></small></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Estimate Meeting End Time</label>
                                    <div class="col-md-5">
                                        <input type="date" class="form-control" placeholder="end date" name="end_date" required>
                                        <span><small><i>end date</i></small></span>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="time" class="form-control" placeholder="end time" name="end_time" required>
                                        <span><small><i>end time</i></small></span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script>
        $(document).ready(function (){
            $('#kt_datatable1').DataTable({
                'ajax':'{{route('mom.get')}}',
                'type' : 'GET',
                dataSrc: 'responseData',

                'columns' :[
                    { "data": "no" },
                    { "data": "meeting" },
                    { "data": "estimate" },
                    { "data": "meeting_num" },
                    { "data": "topic" },
                    { "data": "location" },
                    { "data": "created_by" },
                    { "data": "action" },
                    { "data": "guest" },
                ],
                'columnDefs': [
                    {
                        "targets": 0,
                        "className": "text-center",
                    },
                    {
                        "targets": 1,
                        "className": "text-center",
                    },
                    {
                        "targets": 2,
                        "className": "text-center",
                    },
                    {
                        "targets": 3,
                        "className": "text-center",
                    },
                    {
                        "targets": 7,
                        "className": "text-center",
                    },
                    {
                        "targets": 8,
                        "className": "text-center",
                    },

                ]
            })
        });
    </script>

@endsection
