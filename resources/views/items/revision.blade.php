@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Items Revision</h3><br>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{$back}}" class="btn btn-success btn-xs"><i class="fa fa-arrow-circle-left"></i></a>
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
                    <th class="text-left">Item Name</th>
                    {{--                    <th class="text-left">Catogory</th>--}}
                    <th class="text-left">Type</th>
                    <th class="text-left">Code</th>
                    <th class="text-center">Minimal Stock</th>
                    <th class="text-center">UoM</th>
                    <th class="text-center">Request Revision By</th>
                    <th class="text-center">Request Revision Date</th>
                    <th class="text-center"></th>
                </tr>
                </thead>
                <tbody>
                @foreach($itemsup as $key => $item)
                    <tr>
                        <td align="center">{{$key + 1}}</td>
                        <td>
                            {{$data[$item->id_item]['name']}}&nbsp;
                            @if($data[$item->id_item]['name'] != $item->name)
                                <i class="fa fa-arrow-right"></i> {{$item->name}}
                            @endif
                        </td>
                        <td>
                            {{($data[$item->id_item]['type_id'] == 1) ? "Consumable" : "Non Consumable"}}
                            @if($data[$item->id_item]['type_id'] != $item->type_id)
                                <i class="fa fa-arrow-right"></i> {{($item->type_id == 1) ? "Consumable" : "Non Consumable"}}
                            @endif
                        </td>
                        <td>
                            {{$data[$item->id_item]['item_code']}}&nbsp;
                            @if($data[$item->id_item]['item_code'] != $item->item_code)
                                <i class="fa fa-arrow-right"></i> {{$item->item_code}}
                            @endif
                        </td>
                        <td align="center">
                            {{$data[$item->id_item]['minimal_stock']}}&nbsp;
                            @if($data[$item->id_item]['minimal_stock'] != $item->minimal_stock)
                                <i class="fa fa-arrow-right"></i> {{$item->minimal_stock}}
                            @endif
                        </td>
                        <td align="center">
                            {{$data[$item->id_item]['uom']}}&nbsp;
                            @if($data[$item->id_item]['uom'] != $item->uom)
                                <i class="fa fa-arrow-right"></i> {{$item->uom}}
                            @endif
                        </td>
                        <td align="center">{{$item->created_by}}</td>
                        <td align="center">{{date('d F Y', strtotime($item->created_at))}}</td>
                        <td align="center">
                            <a href="{{URL::route('items.revision_detail', base64_encode(rand(100, 999)."-".$item->id))}}" class="btn btn-primary btn-xs btn-icon mr-1"><i class="fa fa-pencil-alt"></i></a>
                            <button class="btn btn-danger btn-xs btn-icon" onclick="delete_item({{$item->id}})"><i class="fa fa-trash"></i></button>
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
                <form method="post" action="{{URL::route('items.add')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <h4>Basic Information</h4>
                        <hr>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">Item Name</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="Item Name" name="item_name" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">Item Code</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="Item Code" name="item_code" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">Item Series</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="Item Series" name="item_series" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">Supplier</label>
                            <div class="col-md-6">
                                <select name="supplier" id="" class="form-control select2" required>
                                    <option value="">Select Supplier</option>
                                    @foreach($vendor as $value)
                                        <option value="{{$value->id}}">{{ucwords($value->name)}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">Price</label>
                            <div class="col-md-6">
                                <input type="number" class="form-control" placeholder="Price" name="price" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">Serial Number</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="Serial Number" name="serial_number" required>
                            </div>
                        </div>
                        <br>
                        <h4>Detail Info</h4>
                        <hr>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">Type</label>
                            <div class="col-md-6">
                                <select name="type" id="" class="form-control" required>
                                    <option value="1">Consumable</option>
                                    <option value="2">Non Consumable</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">Minimal Stock</label>
                            <div class="col-md-6">
                                <input type="number" class="form-control" placeholder="Minimal Stock" name="min_stock" required>
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
                            <label class="col-md-2 col-form-label text-right">Notes</label>
                            <div class="col-md-6">
                                <textarea name="notes" class="form-control" id="" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">Specification</label>
                            <div class="col-md-6">
                                <textarea name="specification" class="form-control" id="" cols="30" rows="10"></textarea>
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
    <div class="modal fade" id="editItem" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{URL::route('items.edit')}}" id="form-edit" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <h4>Basic Information</h4>
                        <hr>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">Item Name</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="Item Name" id="item_name" name="item_name" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">Item Code</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="Item Code" id="item_code" name="item_code" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">Item Series</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="Item Series" id="item_series" name="item_series" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">Supplier</label>
                            <div class="col-md-6">
                                <select name="supplier" id="supplier" class="form-control select2" required>
                                    <option value="">Select Supplier</option>
                                    @foreach($vendor as $value)
                                        <option value="{{$value->id}}">{{ucwords($value->name)}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">Price</label>
                            <div class="col-md-6">
                                <input type="number" class="form-control" placeholder="Price" id="price" name="price" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">Serial Number</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="Serial Number" id="serial_number" name="serial_number" required>
                            </div>
                        </div>
                        <br>
                        <h4>Detail Info</h4>
                        <hr>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">Type</label>
                            <div class="col-md-6">
                                <select name="type" id="type" class="form-control" required>
                                    <option value="1">Consumable</option>
                                    <option value="2">Non Consumable</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">Minimal Stock</label>
                            <div class="col-md-6">
                                <input type="number" class="form-control" placeholder="Minimal Stock" id="minimal_stock" name="min_stock" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">UoM</label>
                            <div class="col-md-6">
                                <select name="uom" id="uomedit" class="form-control" required>
                                    <option value="">- Select UOM -</option>
                                    @foreach($uom as $v)
                                        <option value="{{$v}}">{{$v}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">Picture</label>
                            <div class="col-md-6">
                                <div class="col-lg-9 col-xl-6">
                                    <div class="image-input image-input-outline" id="app_logo">
                                        <div class="image-input-wrapper"></div>
                                        <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change">
                                            <i class="fa fa-pen icon-sm text-muted"></i>
                                            <input type="file" name="pict" id="p_logo_edit" accept=".png, .jpg, .jpeg" />
                                        </label>
                                        <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel">
                                                                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                                                </span>
                                    </div>
                                    <span class="form-text text-muted">
                                            <div class="checkbox-inline">
                                                <label class="checkbox checkbox-success">
                                                    <input type="checkbox" name="del_pict"/>
                                                    <span></span>
                                                    Check this to delete the picture
                                                </label>
                                            </div>
                                        </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">Notes</label>
                            <div class="col-md-6">
                                <textarea name="notes" class="form-control" id="notes" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">Specification</label>
                            <div class="col-md-6">
                                <textarea name="specification" class="form-control" id="specification" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="id_item" name="id_item">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="button" onclick="button_edit()" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        function delete_item(id) {
            Swal.fire({
                title: "Delete",
                text: "Delete this item?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Delete",
                cancelButtonText: "Cancel",
                reverseButtons: true,
            }).then(function(result){
                if(result.value){
                    $.ajax({
                        url: '{{URL::route('items.revision_delete')}}',
                        data: {
                            '_token': '{{csrf_token()}}',
                            'id': id
                        },
                        type: "POST",
                        cache: false,
                        dataType: 'json',
                        success : function(response){
                            if (response.del = 1){
                                location.reload()
                            } else {
                                Swal.fire({
                                    title: "Delete",
                                    text: "Error",
                                    icon: "error"
                                })
                            }
                        }
                    })
                }
            })
        }
        function edit_item(id){
            $.ajax({
                url: '{{URL::route('items.find')}}',
                data: {
                    '_token': '{{csrf_token()}}',
                    'id': id
                },
                type: "POST",
                cache: false,
                dataType: 'json',
                success : function(response){
                    $("#id_item").val(response.id)
                    $("#item_name").val(response.name)
                    $("#item_code").val(response.item_code)
                    $("#item_series").val(response.item_series)
                    $("#serial_number").val(response.serial_number)
                    $("#price").val(response.price)
                    $("#notes").val(response.notes)
                    $("#specification").val(response.specification)
                    $("#minimal_stock").val(response.minimal_stock)
                    $("#supplier").val(response.supplier).trigger('change')
                    $("#type").val(response.type_id).trigger('change')
                    $("#uomedit").val(response.uom).trigger('change')
                    var imgUrl = "{{str_replace("\\", "/", asset('media/asset/'))}}/" + response.picture
                    $("#app_logo .image-input-wrapper").css('background-image', "url('"+imgUrl+"')")
                }
            })
        }
        function button_edit(){
            Swal.fire({
                title: "Edit data",
                text: "Are you sure you want to edit this data?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Edit",
                cancelButtonText: "Cancel",
                reverseButtons: true,
            }).then(function(result){
                if(result.value){
                    $("#form-edit").submit()
                }
            })
        }
        $(document).ready(function(){
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
