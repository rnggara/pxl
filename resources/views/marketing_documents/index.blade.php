@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                Documents
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" onclick="file_allowed()" class="btn btn-primary" data-toggle="modal" data-target="#addDocument"><i class="fa fa-plus"></i>Add Document</button>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <!-- <div class="row">
                <div class="col-md-4">
                    <div class="input-group">
                        <select name="" id="filter" class="form-control">
                            <option value="">All</option>
                            <option value="document">Documents</option>
                            <option value="presentation">Presentations</option>
                            <option value="file">Files</option>
                        </select>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-primary" id="btn-filter"><i class="fa fa-filter"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <hr> -->
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered table-hover display font-size-sm" id="table-doc" style="margin-top: 13px !important; width: 100%;">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Document Name</th>
                            <th class="text-center">Type</th>
                            <th class="text-center">Uploaded By</th>
                            <th class="text-center">Uploaded Date</th>
                            <th class="text-center">Download</th>
                            <th class="text-center"></th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addDocument" tabindex="-1" role="dialog" aria-labelledby="addDocument" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Document</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('marketing.doc.add')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- <div class="form-group row">
                                    <label for="" class="col-form-label col-md-4">Document Name</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="doc_name" required>
                                    </div>
                                </div> -->
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-4">File Type</label>
                                    <div class="col-md-8">
                                        <select name="type" class="form-control select2" id="file-type" required>
                                            <option value="private_marketing">Private Marketing</option>
                                           
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-4">File</label>
                                    <div class="col-md-8">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="file_upload" id="file-input" required>
                                            <span class="custom-file-label">Choose File</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script>
        function file_allowed(){
            var sel = $("#file-type option:selected").val()
            // if (sel === "document"){
                $("#file-input").attr('accept', '.doc,.docx,.pdf,.xls,.xlsx,.ppt,.pptx')
            // } else if(sel === "presentation"){
            //     $("#file-input").attr('accept', '.ppt,.pptx')
            // } else {
            //     $("#file-input").attr('accept', '*')
            // }
        }

        function delete_document(x){
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{route('marketing.doc.delete')}}/"+x,
                        type: "get",
                        dataType: "json",
                        success: function (response) {
                            if (response.delete === 1){
                                location.reload()
                            } else {
                                Swal.fire('Error occured', "Please contact your system administration", 'error')
                            }
                        }
                    })
                }
            })
        }

        function show_table(){
            var sel = $("#filter option:selected").val()
            $("#table-doc").DataTable().destroy()
            $("#table-doc").DataTable({
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                },
                ajax: {
                    url: "{{route('marketing.doc.list')}}",
                    type: "post",
                    data: {
                        _token: "{{csrf_token()}}",
                        filter: sel
                    }
                },
                columns : [
                    { "data" : "i" }, //0
                    { "data" : "name" }, //1
                    { "data" : "file_type" }, //2
                    { "data" : "by" }, //3
                    { "data" : "date" },//4
                    { "data" : "download" },//5
                    { "data" : "delete" },//6
                ],
                columnDefs: [
                    { targets: [0,2,3,4,5,6], className: "text-center" }
                ]
            })
        }

        $(document).ready(function () {
            file_allowed()
            show_table()
            $("#btn-filter").click(function () {
                show_table()
            })
            $("select.select2").select2({
                width: "100%"
            })
            $("#file-type").change(function () {
                file_allowed()
            })
        });
    </script>
@endsection
