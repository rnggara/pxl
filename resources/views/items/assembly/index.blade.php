@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">Items Assembly</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalAdd"><i class="fa fa-plus"></i> Add Item Assembly</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered table-hover display">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Item Name</th>
                                <th class="text-center">View Components</th>
                                <th class="text-center">Approve</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($assembly as $i => $val)
                                <tr>
                                    <td align="center">{{ $i+1 }}</td>
                                    <td>
                                        [{{ $val->item_code }}] {{ $val->name }}
                                    </td>
                                    <td align="center">
                                        <a href="{{ route('items.assembly.list', $val->id) }}" class="btn btn-sm btn-light-primary"><i class="fa fa-search"></i> View</a>
                                    </td>
                                    <td align="center">
                                        @if (empty($val->assembly_approved_at))
                                            <button type="button" data-toggle="modal" data-target="#modalApprove" onclick="_approve({{ $val->id }})" class="btn btn-sm btn-primary">waiting</button>
                                        @else
                                            Approved at {{ date("d F Y", strtotime($val->assembly_approved_at)) }} <br>
                                            By {{ $val->assembly_approved_by }}
                                        @endif
                                    </td>
                                    <td align="center">
                                        <button type="button" class="btn btn-sm btn-icon btn-danger">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalAdd" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title">Add Item Assembly</h1>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" data-toggle="modal" data-target="#addItem" class="btn btn-block btn-primary">Create New Item</button>
                        </div>
                        <div class="col-6">
                            <button type="button" data-toggle="modal" data-target="#modalAssign" class="btn btn-block btn-primary">Assign Item</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalApprove" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">

            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAssign" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title">Assign Item</h1>
                </div>
                <form method="post" action="{{URL::route('items.assembly.add')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <label class="col-form-label">Item</label>
                            <select name="item_id" class="form-control select2" id="" data-placeholder="Select Item" required>
                                <option value=""></option>
                                @foreach ($all_item as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" value="assign" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addItem" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create New Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{URL::route('items.assembly.add')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <h4>Basic Information</h4>
                        <hr>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Item Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" placeholder="Item Name" name="item_name" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Item Category</label>
                            <div class="col-md-9">
                                <select name="item_category" class="form-control select2" required data-placeholder="Select Category">
                                    <option value=""></option>
                                    @foreach ($item_category as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Item Classification</label>
                            <div class="col-md-9">
                                <select name="item_class" class="form-control select2" required data-placeholder="Select Category">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Brand Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" placeholder="Brand Name" name="item_series" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Serial Number</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" placeholder="Serial Number" name="serial_number" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Item Code</label>
                            <div class="col-md-9">
                                <input type="hidden" name="code" id="code">
                                <input type="text" class="form-control" placeholder="Item Code" name="item_code" id="item_code" readonly>
                            </div>
                        </div>

                        <br>
                        <h4>Detail Info</h4>
                        <hr>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Type</label>
                            <div class="col-md-9">
                                <select name="type" id="" class="form-control" required>
                                    <option value="1">Consumable</option>
                                    <option value="2">Non Consumable</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Minimal Stock</label>
                            <div class="col-md-9">
                                <input type="number" class="form-control" placeholder="Minimal Stock" name="min_stock" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">UoM</label>
                            <div class="col-md-9">
                                <select name="uom" id="uom" class="form-control select2" required>
                                    <option value="">- Select UOM -</option>
                                    @foreach($uom as $v)
                                        <option value="{{$v}}">{{$v}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Picture</label>
                            <div class="col-md-9">
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
                            <label class="col-md-3 col-form-label text-right">Notes</label>
                            <div class="col-md-9">
                                <textarea name="notes" class="form-control" id="" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Specification</label>
                            <div class="col-md-9">
                                <textarea name="specification" class="form-control" id="" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" value="add" class="btn btn-primary font-weight-bold">
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

        function _approve(x){
            $.ajax({
                url : "{{ route('items.assembly.index') }}?id=" + x,
                type : "get",
                success : function(response){
                    $("#modalApprove .modal-content").html(response)

                    $("#modalApprove select.select2").select2({
                        width : "100%"
                    })

                    $("#modalApprove #btn-approve").click(function(e){
                        e.preventDefault()
                        console.log("tes")

                        var storage = 0
                        $("#modalApprove .form-approve").each(function(){
                            if($(this).val() != ""){
                                storage++
                            }
                        })

                        var _storage = $("#modalApprove .form-approve")

                        if(storage == 0 || storage < _storage.length){
                            return Swal.fire('Field required', 'Please select storage for each items', 'error')
                        } else {
                            var form = $("#modalApprove #btn-approve").parents('form')
                            form.submit()
                        }
                    })
                }
            })
        }

        $(document).ready(function(){
            $("select.select2").select2({
                width : "100%"
            })

            $("table.display").DataTable()

            var item_class = $("select[name=item_class]")
            var item_category = $("select[name=item_category]")
            item_class.prop('disabled', true)

            $("select[name=item_category]").change(function(){
                item_class.find("option").remove()
                $.ajax({
                    url : "{{ route('items.assembly.index') }}?_category=" + $(this).val(),
                    type : "get",
                    dataType : "json",
                    cache : false,
                    success : function(response){
                        if(response.success){
                            item_class.prop('disabled', false)
                            var data = response.data
                            var option = new Option("", "", false, false)
                            item_class.append(option)
                            data.forEach((item) => {
                                var option = new Option(item.text, item.id, false, false)
                                item_class.append(option)
                            })
                        }
                    },
                    error : function(xhr, status, error){
                        var err = eval("("+xhr.responseText+");")
                        Swal.fire("Error", err.message, "error")
                    }
                })
            })

            item_class.change(function(){
                $.ajax({
                    url : "{{ route('items.assembly.index') }}?_category=" + item_category.val() + "&_class=" + $(this).val(),
                    type : "get",
                    dataType : "json",
                    cache : false,
                    success : function(response){
                        $("#item_code").val(response)
                    },
                    error : function(xhr, status, error){
                        var err = eval("("+xhr.responseText+");")
                        Swal.fire("Error", err.message, "error")
                    }
                })
            })

            @if(\Session::get('msg'))
                Swal.fire("{{ \Session::get('msg') }}", "", "{{ (\Session::get('msg') == "Approved") ? "success"  : "error"}}")
            @endif
        })
    </script>
@endsection
