@extends('layouts.template')
@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">

                <h3>{{ucwords($forum_name->nama_forum)}}</h3>
            </div>
            <div class="card-toolbar">

                <a href="{{route('forum.index')}}" class="btn btn-secondary font-weight-bolder">
                    <span class="svg-icon svg-icon-md">
                        <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg-->
                        <i class="fa fa-backspace"></i>
                        <!--end::Svg Icon-->
                    </span>Back</a> &nbsp;&nbsp;
                <!--end::Button-->
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addItem"><i class="fa fa-plus"></i>Create Thread</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!--begin: Datatable-->
            <table class="table table-bordered table-hover table-checkable" id="kt_datatable1" style="margin-top: 13px !important">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th class="text-right">Statistic</th>
                    <th>Last Post</th>
                    <th class="text-center">Actions</th>
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
                    <h5 class="modal-title" id="exampleModalLabel">Create Thread</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" id="form-add" action="{{route('forum.topic.store')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Title</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="Title" name="title" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Content</label>
                                    <div class="col-md-6">
                                        <textarea class="form-control" name="content" required></textarea>
                                    </div>
                                </div>
                                <input type="hidden" name="id_forum" value="{{$id_forum}}" id="">
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
                'ajax':'{{route('forum.topicAjax',['id' => $id_forum])}}',
                'type' : 'GET',
                dataSrc: 'responseData',

                'columns' :[
                    { "data": "no" },
                    { "data": "title" },
                    { "data": "statistik" },
                    { "data": "last_post" },
                    { "data": "action" },
                ],
                'columnDefs': [
                    {
                        "targets": 2, // your case first column
                        "className": "text-right",
                    },
                    {
                        "targets": 4, // your case first column
                        "className": "text-center",
                    },
                ]
            })
        });
    </script>
@endsection
