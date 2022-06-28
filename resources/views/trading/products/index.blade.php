@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Products</h3><br>

            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addItem"><i class="fa fa-plus"></i>New Products</button>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            {{--            <h5><span class="span">This page contains a list of Travel Order which has been formed.</span></h5>--}}
            <table class="table display">
                <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-left">Product Name</th>
                    <th class="text-center">UoM</th>
                    <th class="text-center">Supplier</th>
                    <th class="text-center"></th>
                </tr>
                </thead>
                <tbody>
                @foreach($producst as $key => $value)
                    <tr>
                        <td align="center">{{$key+1}}</td>
                        <td align="left">{{$value->name}}</td>
                        <td align="center">{{$value->uom}}</td>
                        <td align="center">{{$data_supplier[$value->supplier]->name}}</td>
                        <td align="center">
                            <button type="button" onclick="editProduct('{{$value->id}}')" class="btn btn-xs btn-primary btn-icon"><i class="fa fa-edit"></i></button>
                            <button type="button" class="btn btn-xs btn-danger btn-icon"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="addItem" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{URL::route('trading.products.add')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Basic Information</h4>
                                <hr>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Picture</label>
                                    <div class="col-md-6">
                                        <div class="col-lg-9 col-xl-6">
                                            <div class="image-input image-input-outline" id="printed_logo">
                                                <div class="image-input-wrapper"></div>
                                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change">
                                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                                    <input type="file" name="pict" id="p_logo_add" accept=".png, .jpg, .jpeg" />
                                                </label>
                                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel">
                                                                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Product Name</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="Product Name" name="product_name" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Supplier</label>
                                    <div class="col-md-6">
                                        <select name="supplier" id="" class="form-control select2" required>
                                            <option value="">Select Supplier</option>
                                            @foreach($suppliers as $value)
                                                <option value="{{$value->id}}">{{ucwords($value->name)}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Serial Number</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="Serial Number" name="serial_number" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">UoM</label>
                                    <div class="col-md-6">
                                        <select name="uom" id="uom" class="form-control" required>
                                            <option value="">- Select UOM -</option>
                                            @foreach($uom as $v)
                                                <option value="{{$v}}">{{$v}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4>Detail Info</h4>
                                <hr>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Notes</label>
                                    <div class="col-md-6">
                                        <textarea name="notes" class="form-control" id="" cols="30" rows="10"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Sample</label>
                                    <div class="col-md-6 custom-file">
                                        <input type="file" class="custom-file-input" name="upload_sample">
                                        <span class="custom-file-label">Choose File</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Lab</label>
                                    <div class="col-md-6 custom-file">
                                        <input type="file" class="custom-file-input" name="upload_lab">
                                        <span class="custom-file-label">Choose File</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Survey</label>
                                    <div class="col-md-6 custom-file">
                                        <input type="file" class="custom-file-input" name="upload_survey">
                                        <span class="custom-file-label">Choose File</span>
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
    <div class="modal fade" id="editProduct" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content" id="edit-product">

            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        function editProduct(x){
            $("#editProduct").modal('show')
            $.ajax({
                url: "{{route('trading.products.detail')}}/" + x,
                type: "GET",
                cache: false,
                success: function(response){
                    $("#edit-product").append(response)
                    $("#edit-product select.select2").select2({
                        width: "100%"
                    })
                }
            })
        }
        $(document).ready(function(){

            $("#editProduct").on('hidden.bs.modal', function () {
                $("#edit-product").html('')
            })
            $("table.display").DataTable({
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            })
            $("select.select2").select2({
                width: "100%"
            })
        })
    </script>
@endsection
