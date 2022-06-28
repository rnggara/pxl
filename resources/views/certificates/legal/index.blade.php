@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                Legal Documents
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCertificate"><i class="fa fa-plus"></i>Add Legal Document</button>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Legal Doc Name</th>
                        <th class="text-center">Legal Doc Holder</th>
                        <th class="text-center">Legal Doc Number</th>
                        <th class="text-center">Legal Doc Owner</th>
                        <th class="text-center">Legal Doc Description</th>
                        <th class="text-center">Expired Date</th>
                        <th class="text-center"></th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($legals as $i => $legal)
                            <tr>
                                <td align="center">{{$i+1}}</td>
                                <td align="center">{{$legal->name}}</td>
                                <td align="center">{{$legal->certificate_holder}}</td>
                                <td align="center">{{$legal->certificate_no}}</td>
                                <td align="center">{{$legal->certificate_owner}}</td>
                                <td align="center">{{$legal->description}}</td>
                                <td align="center">{{date('d F Y', strtotime($legal->exp_date))}}</td>
                                <td align="center">
                                    @if(!empty($legal->picture))
                                        @if(isset($file_type[$legal->picture]))
                                            @if($file_type[$legal->picture] == "image")
                                                <button type="button" onclick="image_modal('{{$legal->id}}')" class="btn btn-xs btn-icon btn-light-dark"><i class="fa fa-eye"></i></button>
                                            @else
                                                <a href="{{route('download', $legal->picture)}}" target="_blank" class="btn btn-xs btn-icon btn-light-dark"><i class="fa fa-download"></i></a>
                                            @endif
                                        @endif
                                    @endif
                                    <button type="button" onclick="edit_modal('{{$legal->id}}')" class="btn btn-xs btn-icon btn-light-dark" data-toggle="modal" data-target="#editCertificate"><i class="fa fa-edit"></i></button>
                                    <button type="button" onclick="upload_modal('{{$legal->id}}')" class="btn btn-xs btn-icon btn-light-dark"><i class="fa fa-image" data-toggle="modal" data-target="#uploadImage"></i></button>
                                    <button type="button" onclick="delete_item('{{$legal->id}}')" class="btn btn-xs btn-icon btn-light-dark"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addCertificate" tabindex="-1" role="dialog" aria-labelledby="addCertificate" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Legal Document</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('asset.legal.add')}}" >
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-3">Expired Date</label>
                                    <div class="col-md-9">
                                        <input type="date" class="form-control" name="exp_date">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-3">Legal Doc. Name</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-3">Doc. Name</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="description">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-3">No Doc. Legal</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="certificate_no">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-3">Doc. Legal Holder</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="certificate_holder">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-3">Owner of The Document</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="certificate_owner">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="company_id" value="{{\Session::get('company_id')}}">
                        <input type="hidden" name="type" value="LEGAL">
                        <input type="hidden" name="view" value="1">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editCertificate" tabindex="-1" role="dialog" aria-labelledby="editCertificate" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" id="editContent">

            </div>
        </div>
    </div>
    <div class="modal fade" id="uploadImage" tabindex="-1" role="dialog" aria-labelledby="uploadImage" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" id="editContent">
                <form action="{{route('asset.legal.upload')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h3 class="modal-title">Upload Image</h3>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="picture">
                                    <span class="custom-file-label">Choose File</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id" id="id-legal">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="showImage" tabindex="-1" role="dialog" aria-labelledby="showImage" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" id="imageContent">

            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script>
        function delete_item(x) {
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
                        url: "{{route('asset.legal.delete')}}/"+x,
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
        function edit_modal(x){
            $.ajax({
                url: "{{route('asset.legal.detail')}}/"+x,
                type: "GET",
                success:function (response) {
                    $("#editContent").html("")
                    $("#editContent").append(response)
                }
            })
        }

        function image_modal(x){
            $.ajax({
                url: "{{route('asset.legal.image')}}/"+x,
                type: "GET",
                success:function (response) {
                    $("#showImage").modal('show')
                    $("#imageContent").html("")
                    $("#imageContent").append(response)
                }
            })
        }

        function upload_modal(x){
            $("#id-legal").val(x)
        }
        $(document).ready(function () {
            $('.display').DataTable({
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            });
        });
    </script>
@endsection
