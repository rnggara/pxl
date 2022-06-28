@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-line mb-5">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle active" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="nav-icon"><i class="flaticon2-group"></i></span>
                        <span class="nav-text">Type Managements</span>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" data-toggle="tab" href="#po-type">PO Type</a>
                        <a class="dropdown-item" data-toggle="tab" href="#wo-type">WO Type</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="nav-icon"><i class="flaticon2-group"></i></span>
                        <span class="nav-text">PO/WO Check</span>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" data-toggle="tab" href="#po-out">PO Outstanding</a>
                        <a class="dropdown-item" data-toggle="tab" href="#po-all">All PO</a>
                        <a class="dropdown-item" data-toggle="tab" href="#wo-out">WO Outstanding</a>
                        <a class="dropdown-item" data-toggle="tab" href="#wo-all">All WO</a>
                    </div>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="po-type" role="tabpanel" aria-labelledby="po-type">
                    <div class="card card-custom gutter-b">
                        <div class="card-header">
                            <div class="card-title">
                                <a href="#" class="text-black-50">PO Types</a>
                                <span class="text-danger">&nbsp;* To manage PO / WO Types, you can manage it from Preference - Transaction Code</span>
                            </div>
                            <div class="card-toolbar">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    {{-- <button type="button" class="btn btn-primary+" onclick="button_add_type('po')"><i class="fa fa-plus"></i>Add Type</button> --}}
                                </div>
                                <!--end::Button-->
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mx-auto">
                                    <table class="table table-responsive-xl table-striped display">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>Type</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @actionStart('powo_types', 'read')
                                            @foreach($pos as $key => $item)
                                                <tr>
                                                    <td align="center">{{$key + 1}}</td>
                                                    <td>
                                                        <span class="label label-inline label-primary">{{$item->name}}</span>
                                                    </td>
                                                    {{-- <td align="center">
                                                        <button type="button" class="btn btn-xs btn-icon btn-primary" onclick="button_edit('{{$key}}', 'po')"><i class="fa fa-edit"></i></button>
                                                        @actionStart('powo_types', 'delete')
                                                        <button type="button" class="btn btn-xs btn-icon btn-danger" onclick="button_delete_type('{{$item->id}}', 'po')"><i class="fa fa-trash"></i></button>
                                                        @actionEnd
                                                    </td> --}}
                                                </tr>
                                            @endforeach
                                        @actionEnd
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="wo-type" role="tabpanel" aria-labelledby="wo-type">
                    <div class="card card-custom gutter-b">
                        <div class="card-header">
                            <div class="card-title">
                                <a href="#" class="text-black-50">WO Types</a>
                                <span class="text-danger">&nbsp;* To manage PO / WO Types, you can manage it from Preference - Transaction Code</span>
                            </div>
                            <div class="card-toolbar">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    {{-- <button type="button" class="btn btn-primary" onclick="button_add_type('wo')"><i class="fa fa-plus"></i>Add Type</button> --}}
                                </div>
                                <!--end::Button-->
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mx-auto">
                                    <table class="table table-responsive-xl table-striped display">
                                        <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Type</th>
                                            {{-- <th class="text-center"></th> --}}
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @actionStart('powo_types', 'read')
                                        @foreach($wos as $key => $item)
                                            <tr>
                                                <td align="center">{{$key + 1}}</td>
                                                <td>
                                                    <span class="label label-inline label-primary">{{$item->name}}</span>
                                                </td>
                                                {{-- <td align="center">
                                                    <button type="button" class="btn btn-xs btn-icon btn-primary" onclick="button_edit('{{$key}}', 'wo')"><i class="fa fa-edit"></i></button>
                                                    @actionStart('powo_types', 'delete')
                                                    <button type="button" class="btn btn-xs btn-icon btn-danger" onclick="button_delete_type('{{$item->id}}', 'wo')"><i class="fa fa-trash"></i></button>
                                                    @actionEnd
                                                </td> --}}
                                            </tr>
                                        @endforeach
                                        @actionEnd
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade show" id="po-out" role="tabpanel" aria-labelledby="po-out">
                    <div class="card card-custom gutter-b">
                        <div class="card-header">
                            <div class="card-title">
                                <a href="#" class="text-black-50">PO Outstanding</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8 mx-auto">
                                    <table class="table table-responsive-xl table-striped display">
                                        <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Paper #</th>
                                            <th class="text-center">Vendor</th>
                                            <th class="text-center">Paper Type</th>
                                            <th class="text-center"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @actionStart('powo_types', 'read')
                                        @php
                                            $num = 1;
                                        @endphp
                                        @foreach($po_data as $key => $item)
                                            @if($item->po_type == null || $item->po_type == "")
                                                <tr>
                                                    <td align="center">{{$num++}}</td>
                                                    <td>
                                                        <span class="label label-inline label-primary">{{$item->po_num}}</span>
                                                    </td>
                                                    <td align="center">
                                                        <b>{{isset($vendor_name[$item->supplier_id]) ? $vendor_name[$item->supplier_id] : "N/A"}}</b>
                                                    </td>
                                                    <td align="center">
                                                        <span class="label label-inline label-warning">undefined</span>
                                                    </td>
                                                    <td align="center">
                                                        <button type="button" class="btn btn-xs btn-icon btn-primary" onclick="button_edit_type('{{$item->id}}', 'po')"><i class="fa fa-edit"></i></button>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        @actionEnd
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade show" id="po-all" role="tabpanel" aria-labelledby="po-all">
                    <div class="card card-custom gutter-b">
                        <div class="card-header">
                            <div class="card-title">
                                <a href="#" class="text-black-50">PO All</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8 mx-auto">
                                    <table class="table table-responsive-xl table-striped display">
                                        <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Paper #</th>
                                            <th class="text-center">Vendor</th>
                                            <th class="text-center">Paper Type</th>
                                            <th class="text-center"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @actionStart('powo_types', 'read')
                                        @php
                                            $num = 1;
                                        @endphp
                                        @foreach($po_data as $key => $item)
                                            @if($item->po_type != null || $item->po_type != "")
                                                <tr>
                                                    <td align="center">{{$num++}}</td>
                                                    <td>
                                                        <span class="label label-inline label-primary">{{$item->po_num}}</span>
                                                    </td>
                                                    <td align="center">
                                                        <b>{{isset($vendor_name[$item->supplier_id]) ? $vendor_name[$item->supplier_id] : "N/A"}}</b>
                                                    </td>
                                                    <td align="center">
                                                        <span class="label label-inline label-success">{{strip_tags($item->po_type)}}</span>
                                                    </td>
                                                    <td align="center">
                                                        <button type="button" class="btn btn-xs btn-icon btn-primary" onclick="button_edit_type('{{$item->id}}', 'po')"><i class="fa fa-edit"></i></button>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        @actionEnd
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="wo-out" role="tabpanel" aria-labelledby="wo-out">
                    <div class="card card-custom gutter-b">
                        <div class="card-header">
                            <div class="card-title">
                                <a href="#" class="text-black-50">WO Outstanding</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8 mx-auto">
                                    <table class="table table-responsive-xl table-striped display">
                                        <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Paper #</th>
                                            <th class="text-center">Vendor</th>
                                            <th class="text-center">Paper Type</th>
                                            <th class="text-center"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @actionStart('powo_types', 'read')
                                        @php
                                            $num = 1;
                                        @endphp
                                        @foreach($wo_data as $key => $item)
                                            @if($item->wo_type == null || $item->wo_type == "")
                                                <tr>
                                                    <td align="center">{{$num++}}</td>
                                                    <td>
                                                        <span class="label label-inline label-primary">{{$item->wo_num}}</span>
                                                    </td>
                                                    <td align="center">
                                                        <b>{{isset($vendor_name[$item->supplier_id]) ? $vendor_name[$item->supplier_id] : "N/A"}}</b>
                                                    </td>
                                                    <td align="center">
                                                        <span class="label label-inline label-warning">undefined</span>
                                                    </td>
                                                    <td align="center">
                                                        <button type="button" class="btn btn-xs btn-icon btn-primary" onclick="button_edit_type('{{$item->id}}', 'wo')"><i class="fa fa-edit"></i></button>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        @actionEnd
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="wo-all" role="tabpanel" aria-labelledby="wo-all">
                    <div class="card card-custom gutter-b">
                        <div class="card-header">
                            <div class="card-title">
                                <a href="#" class="text-black-50">WO All</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8 mx-auto">
                                    <table class="table table-responsive-xl table-striped display">
                                        <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Paper #</th>
                                            <th class="text-center">Vendor</th>
                                            <th class="text-center">Paper Type</th>
                                            <th class="text-center"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @actionStart('powo_types', 'read')
                                        @php
                                            $num = 1;
                                        @endphp
                                        @foreach($wo_data as $key => $item)
                                            @if($item->wo_type != null || $item->wo_type != "")
                                                <tr>
                                                    <td align="center">{{$num++}}</td>
                                                    <td>
                                                        <span class="label label-inline label-primary">{{$item->wo_num}}</span>
                                                    </td>
                                                    <td align="center">
                                                        <b>{{isset($vendor_name[$item->supplier_id]) ? $vendor_name[$item->supplier_id] : "N/A"}}</b>
                                                    </td>
                                                    <td align="center">
                                                        <span class="label label-inline label-success">{{strip_tags($item->wo_type)}}</span>
                                                    </td>
                                                    <td align="center">
                                                        <button type="button" class="btn btn-xs btn-icon btn-primary" onclick="button_edit_type('{{$item->id}}', 'wo')"><i class="fa fa-edit"></i></button>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        @actionEnd
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addType" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Type <span id="modal-label"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="POST" id="form-add-type">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Type Name</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="type_name" placeholder="Type Name" required/>
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
                    <h5 class="modal-title" id="exampleModalLabel">Update Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="POST" action="{{route('ha.powotypes.updateType')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Type Name</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="type_name" id="type-name" placeholder="Type Name" required/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_type" id="id-type">
                        <input type="hidden" name="type" id="type">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        @actionStart('powo_types', 'update')
                        <button type="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Update</button>
                        @actionEnd
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="updateType" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="POST" action="{{route('ha.powotypes.changeType')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Type</label>
                                    <div class="col-md-8">
                                        <select name="type_" id="type-update" class="form-control select2" required>

                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_data" id="id-data">
                        <input type="hidden" name="type_data" id="type-data">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        @actionStart('powo_types', 'update')
                        <button type="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Update</button>
                        @actionEnd
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script>

        function button_add_type(x){
            $("#addType").modal('show')
            if (x === "po"){
                $("#modal-label").text("PO")
                $("#form-add-type").attr("action", "{{route('ha.powotypes.addPoType')}}")
            } else {
                $("#modal-label").text("WO")
                $("#form-add-type").attr("action", "{{route('ha.powotypes.addWoType')}}")
            }
        }

        function button_edit_type(x, y){
            $("#type-update").val(null).trigger("change");
            $("#type-update").empty()
            $("#updateType").modal('show')
            $.ajax({
                url: "{{route('ha.powotypes.getTypes')}}/" + y + "/" + x,
                type: "get",
                dataType: "json",
                cache: "false",
                success: function(response){
                    $("#type-update").append("<option value=''>&nbsp;</option>")
                    $("#type-update").select2({
                        data: response.results,
                        width: "100%"
                    })

                    var paper = response.paper
                    $("#type-data").val(y)
                    $("#id-data").val(paper.id)
                    $("#type-update").val(paper.type).trigger("change")
                }
            })
        }

        function button_edit(x, y) {
            if (y === "po"){
                var json_cat = "{{json_encode($pos)}}".replaceAll("&quot;", "\"")
            } else {
                var json_cat = "{{json_encode($wos)}}".replaceAll("&quot;", "\"")
            }
            var cat = JSON.parse(json_cat)
            console.log(cat[x])
            $("#editCategory").modal('show')
            $("#type-name").val(cat[x].name)
            $("#type").val(y)
            $("#id-type").val(cat[x].id)
        }

        function button_delete_type(x, y){
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
                        url : "{{URL::route('ha.powotypes.deleteType')}}/" + x + "/" + y,
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
                },
                pageLength: 100
            });
        })

    </script>
@endsection
