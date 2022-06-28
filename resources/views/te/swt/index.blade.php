@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <a href="#" class="text-black-50">Surface Welltesting</a>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addLeads"><i class="fa fa-plus"></i>Add Project</button>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-5 col-sm-5">
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                            <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th nowrap="nowrap" class="text-left">Project Name</th>
                                <th nowrap="nowrap" class="text-center"></th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($swt as $key => $value)
                                    <tr>
                                        <td align="center">{{$key + 1}}</td>
                                        <td><a href="{{route('te.swt.items', $value->id)}}" class="label label-inline label-lg label-primary">{{$value->subject}}</a></td>
                                        <td align="center">
                                            <button type="button" class="btn btn-xs btn-primary btn-icon" onclick="button_edit({{$value->id}})"><i class="fa fa-edit"></i></button>
                                            <button type="button" class="btn btn-xs btn-danger btn-icon" onclick="button_delete({{$value->id}})"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addLeads" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Project</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="POST" action="{{URL::route('te.swt.add')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Project Name</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="project_name" placeholder="Project Name" required/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" id="btn-save-leads" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editCategory" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" id="editContent">

            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script>

        function button_edit(x) {
            $("#editContent").html(" ")
            $("#editCategory").modal('show')
            $.ajax({
                url: "{{route('te.swt.find')}}/" + x,
                type: "get",
                cache: false,
                success: function(response){
                    $("#editContent").append(response)
                }
            })
        }

        function button_delete(x){
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
                    location.href="{{route('te.swt.delete')}}/"+x
                }
            })
        }

        $(document).ready(function () {
            $("select.select2").select2({
                width: "100%"
            })


            $('.display').DataTable({
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            });
        })

    </script>
@endsection
