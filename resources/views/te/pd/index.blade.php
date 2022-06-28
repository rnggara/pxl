@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <a href="#" class="text-black-50">Project Design</a>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addLeads"><i class="fa fa-plus"></i>Add Category</button>
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
                                <th nowrap="nowrap" class="text-left">Category Name</th>
                                <th nowrap="nowrap" class="text-center"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($elCats as $key => $item)
                                <tr>
                                    <td align="center">{{$key + 1}}</td>
                                    <td>
                                        <a href="{{route('te.pd.detail', $item->id)}}" class="btn btn-xs btn-info">{{$item->category_name}}</a>
                                    </td>
                                    <td align="center">
                                        <button class="btn btn-primary btn-xs btn-icon" onclick="button_edit('{{$key}}')"><i class="fa fa-edit"></i></button>
                                        <button class="btn btn-danger btn-xs btn-icon" onclick="button_delete('{{$item->id}}')"><i class="fa fa-trash"></i></button>
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
                    <h5 class="modal-title" id="exampleModalLabel">Add Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="POST" action="{{URL::route('te.pd.addCategory')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Category Name</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="cat_name" placeholder="Category Name" required/>
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
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="POST" action="{{URL::route('te.pd.updateCategory')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Category Name</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="cat_name" id="cat-name" placeholder="Category Name" required/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_cat" id="id-cat">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script>

        function button_edit(x) {
            var json_cat = "{{json_encode($elCats)}}".replaceAll("&quot;", "\"")
            var cat = JSON.parse(json_cat)
            console.log(cat[x])
            $("#editCategory").modal('show')
            $("#cat-name").val(cat[x].category_name)
            $("#tag").val(cat[x].tag)
            $("#id-cat").val(cat[x].id)
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
                    $.ajax({
                        url : "{{URL::route('te.pd.deleteCategory')}}/" + x,
                        type: "get",
                        dataType: "json",
                        cache: "false",
                        success: function(response){
                            if (response.error == 0){
                                location.reload()
                            } else {
                                Swal.fire('Error occured', 'Please contact your administrator!', 'error')
                            }
                        }
                    })
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
