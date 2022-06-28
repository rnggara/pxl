@extends('layouts.template')
@section('content')
<div class="card card-custom gutter-b">
    <div class="card-header card-header-tabs-line">
        <div class="card-title">
            Maintenances - <b class="text-primary">{{$vehicle->name}}</b>
        </div>
        <div class="card-toolbar btn-group">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addMt"><i class="fa fa-plus"></i> Add Maintenance</button>
            <a href="{{route('ha.ve.index')}}" class="btn btn-success"><i class="fa fa-arrow-left"></i></a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">

            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12">
                <table class="table table-bordered table-hover display">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Part Number</th>
                        <th class="text-center">Description</th>
                        <th class="text-center">Price</th>
                        <th class="text-center"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($mt as $i => $item)
                        <tr>
                            <td align="center">{{$i+1}}</td>
                            <td align="center">{{date('d F Y', strtotime($item->mt_date))}}</td>
                            <td align="center">{{$item->part_number}}</td>
                            <td align="left">{{$item->description}}</td>
                            <td align="right">{{number_format($item->price, 2)}}</td>
                            <td align="center">
                                <button onclick="edit_modal({{$item->id}})" class="btn btn-xs btn-icon btn-primary"><i class="fa fa-edit"></i></button>
                                <button onclick="delete_item({{$item->id}})" class="btn btn-xs btn-icon btn-danger"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="addMt" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <form method="POST" action="{{route('ha.ve.add.maintenance')}}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label for="" class="col-form-label col-md-4">Part Number</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="part_number" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-form-label col-md-4">Description</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="description" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-form-label col-md-4">Date</label>
                                <div class="col-md-8">
                                    <input type="date" class="form-control" name="mt_date" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-form-label col-md-4">Price</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control number required" name="price" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id_ve" value="{{$vehicle->id}}">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                    <button type="submit" id="btn-save-leads" class="btn btn-primary font-weight-bold">
                        <i class="fa fa-check"></i>
                        Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="editMtModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" id="paper-edit">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <form method="POST" action="{{route('ha.ve.add.maintenance')}}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label for="" class="col-form-label col-md-4">Part Number</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="part_number" id="partNumber" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-form-label col-md-4">Description</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="description" id="description" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-form-label col-md-4">Date</label>
                                <div class="col-md-8">
                                    <input type="date" class="form-control" name="mt_date" id="mtDate" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-form-label col-md-4">Price</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control number required" name="price" id="price" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id_mt" id="idMt">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                    <button type="submit" id="btn-save-leads" class="btn btn-primary font-weight-bold">
                        <i class="fa fa-check"></i>
                        Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('custom_script')
    <script src="{{asset('assets/jquery-number/jquery.number.js')}}"></script>
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
                    location.href = "{{route('ha.ve.delete.maintenance')}}/"+x
                }
            })
        }

        function show_toast(type, msg) {
            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": "toast-bottom-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };

            toastr.success(msg, type);
        }

        function edit_modal(x){
            $.ajax({
                url : "{{route('ha.ve.find.maintenance')}}/"+x,
                type : "get",
                dataType: "json",
                success: function (response) {
                    $("#partNumber").val(response.data.part_number)
                    $("#mtDate").val(response.data.mt_date)
                    $("#description").val(response.data.description)
                    $("#idMt").val(response.data.id)
                    $("#price").val(response.data.price)
                    $("#editMtModal").modal('show')
                }
            })
        }

        $(document).ready(function () {
            $(".number").number(true, 2)
            $(".display").DataTable({
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                },
                pageLength: 100
            })

            @if(\Session::has('msg'))
            show_toast('Success', "{{\Session::get('msg')}}")
            @endif
        })
    </script>
@endsection
