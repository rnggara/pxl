@extends('layouts.template')
@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
                <h3>Standard Operating Procedure </h3>
            </div>

            <div class="card-toolbar">
                <!--end::Button-->
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addItem"><i class="fa fa-plus"></i>Add SOP</button>
                    &nbsp;&nbsp;
                    <a href="{{route('sop.category')}}" class="btn btn-success"><i class="fa fa-cogs"></i>&nbsp;Add SOP Category</a>
                </div>
            </div>

        </div>
        <div class="card-body">
            <!--begin: Datatable-->
            <table class="table table-bordered table-hover table-checkable" id="kt_datatable1" style="margin-top: 13px !important">
                <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Create Date | Time</th>
                    <th class="text-center">SOP#</th>
                    <th class="text-center">Category</th>
                    <th>Title</th>
                    <th class="text-center">Created by</th>
                    <th class="text-center">Division</th>
                    <th class="text-center">Revision</th>
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
                    <h5 class="modal-title" id="exampleModalLabel">Add SOP</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" id="form-add" action="{{route('sop.store')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">SOP Category</label>
                                    <div class="col-md-10">
                                        <select name="category" class="form-control">
                                            @foreach($categories as $value)
                                                <option value="{{$value->id}}">{{$value->name_category}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">SOP Name</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" placeholder="Title" name="title" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Division</label>
                                    <div class="col-md-10">
                                        <select name="division" class="form-control">
                                            @foreach($divisions as $value)
                                                <option value="{{$value->name}}">{{$value->name}}</option>
                                            @endforeach
                                        </select>
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
                'ajax':'{{route('sop.get')}}',
                'type' : 'GET',
                dataSrc: 'responseData',

                'columns' :[
                    { "data": "no" },
                    { "data": "date" },
                    { "data": "sop_num" },
                    { "data": "category" },
                    { "data": "title" },
                    { "data": "created_by" },
                    { "data": "division" },
                    { "data": "revision" },
                    { "data": "action" },
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
                        "targets": 5,
                        "className": "text-center",
                    },
                    {
                        "targets": 6,
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

                ],
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            })
        });
    </script>

@endsection
