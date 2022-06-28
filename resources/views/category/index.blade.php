@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                Item Category
            </div>
            @actionStart('item_database', 'create')
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    @if (Auth::id() == 1)
                        <button type="button" class="btn btn-primary mr-3" data-toggle="modal" data-target="#addItems"><i class="fa fa-plus"></i> Add Item</button>
                    @endif
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addEmployee"><i class="fa fa-plus"></i>Add Category</button>
                </div>
                <!--end::Button-->
            </div>
            @actionEnd
        </div>
        <div class="card-body">
            <form method="post" action="{{route('category.search')}}">
                @csrf
                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-right"></label>
                    <div class="col-md-6">
                        <input type="text" name="search_val" id="search_val" class="form-control" placeholder="Search here.." required>
                    </div>

                    <div class="col-md-3">
                        <button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </form>
            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th nowrap="nowrap">Category Name</th>
                        <th nowrap="nowrap" class="text-center">Code</th>
                        <th nowrap="nowrap" class="text-left">Parent</th>
                        <th nowrap="nowrap" data-priority=1 class="text-center"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @actionStart('item_database', 'read')
                    @php
                        $no = 1;

                    @endphp
                    @foreach($categories as $key => $value)
                        <tr>
                            <td>{{$no}}</td>
                            @php
                                /** @var TYPE_NAME $no */
                                $no ++;
                            @endphp
                            <td>
                                <a href="{{route('items.class.index', $value->id)}}">
                                    <span class="label label-inline label-primary">
                                        {{$value->name}}
                                    </span>
                                </a>
                            </td>
                            <td class="text-center">{{$value->code}}</td>
                            <td class="text-left">
                                {{($id_parents[$value->id] != 0)? $parents[$id_parents[$value->id]]:''}}
                            </td>
                            <div class="modal fade" id="edit{{$value->id}}" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Edit Category</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <i aria-hidden="true" class="ki ki-close"></i>
                                            </button>
                                        </div>
                                        <form method="post" action="{{route('category.update')}}" >
                                            @csrf
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="form col-md-12">
                                                        <div class="form-group">
                                                            <label>Category Name</label>
                                                            <input type="hidden" name="id" id="id" value="{{$value->id}}">
                                                            <input type="text" class="form-control" name="name" value="{{$value->name}}"/>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Category Code <span class="text-danger">*</span></label>
                                                        </div>
                                                        <div class="form-group" style="margin-top: -20px;">
                                                            <input type="text" class="form-control col-md-6 form-check-inline" name="code" value="{{$value->code}}" required/>
                                                            <span class="col-md-6 text-danger">* Maximum 3 Characters</span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Parent Category</label>
                                                            <select class="form-control" name="id_parent" id="id_parent">
                                                                <option value="0"></option>
                                                                @foreach($categories as $key2 => $val)
                                                                    <option value="{{$val->id}}" @if($val->id == $value->id_parent) SELECTED @endif>{{$val->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                                                <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                                                    <i class="fa fa-check"></i>
                                                    Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <td class="text-center">
                                <a href="#edit{{$value->id}}" data-toggle="modal" class="btn btn-sm btn-primary btn-icon btn-icon-md" title="Edit"><i class="fa fa-edit"></i></a>
                                <a href="{{route('category.del',['id' => $value->id])}}" title="Delete" class="btn btn-sm btn-danger btn-icon btn-icon-md" onclick="return confirm('Delete Category?')"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    @foreach($categories2 as $key => $value)
                        <tr>
                            <td>{{$no}}</td>
                            @php
                                /** @var TYPE_NAME $no */
                                $no ++;
                            @endphp
                            <td>
                                <a href="{{route('items.class.index', $value->id)}}">
                                    <span class="label label-inline label-primary">
                                        {{$value->name}}
                                    </span>
                                </a>
                            </td>
                            <td class="text-center">{{$value->code}}</td>
                            <td class="text-left">
                                {{($id_parents[$value->id] != 0)? $parents[$id_parents[$value->id]]:''}}
                            </td>
                            <div class="modal fade" id="edit{{$value->id}}" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Edit Category</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <i aria-hidden="true" class="ki ki-close"></i>
                                            </button>
                                        </div>
                                        <form method="post" action="{{route('category.update')}}" >
                                            @csrf
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="form col-md-12">
                                                        <div class="form-group">
                                                            <label>Category Name</label>
                                                            <input type="hidden" name="id" id="id" value="{{$value->id}}">
                                                            <input type="text" class="form-control" name="name" value="{{$value->name}}"/>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Category Code <span class="text-danger">*</span></label>
                                                        </div>
                                                        <div class="form-group" style="margin-top: -20px;">
                                                            <input type="text" class="form-control col-md-6 form-check-inline" name="code" value="{{$value->code}}" required/>
                                                            <span class="col-md-6 text-danger">* Maximum 3 Characters</span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Parent Category</label>
                                                            <select class="form-control" name="id_parent" id="id_parent">
                                                                <option value="0"></option>
                                                                @foreach($categories as $key2 => $val)
                                                                    <option value="{{$val->id}}" @if($val->id == $value->id_parent) SELECTED @endif>{{$val->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                                                <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                                                    <i class="fa fa-check"></i>
                                                    Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <td class="text-center">
                                <a href="#edit{{$value->id}}" data-toggle="modal" class="btn btn-sm btn-primary btn-icon btn-icon-md" title="Edit"><i class="fa fa-edit"></i></a>
                                <a href="{{route('category.del',['id' => $value->id])}}" title="Delete" class="btn btn-sm btn-danger btn-icon btn-icon-md" onclick="return confirm('Delete Category?')"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    @foreach($categories3 as $key => $value)
                        <tr>
                            <td>{{$no}}</td>
                            @php
                                /** @var TYPE_NAME $no */
                                $no ++;
                            @endphp
                            <td>
                                <a href="{{route('items.class.index', $value->id)}}">
                                    <span class="label label-inline label-primary">
                                        {{$value->name}}
                                    </span>
                                </a>
                            </td>
                            <td class="text-center">{{$value->code}}</td>
                            <td class="text-left">
                                {{($id_parents[$value->id] != 0)? $parents[$id_parents[$value->id]]:''}}
                            </td>
                            <div class="modal fade" id="edit{{$value->id}}" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Edit Category</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <i aria-hidden="true" class="ki ki-close"></i>
                                            </button>
                                        </div>
                                        <form method="post" action="{{route('category.update')}}" >
                                            @csrf
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="form col-md-12">
                                                        <div class="form-group">
                                                            <label>Category Name</label>
                                                            <input type="hidden" name="id" id="id" value="{{$value->id}}">
                                                            <input type="text" class="form-control" name="name" value="{{$value->name}}"/>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Category Code <span class="text-danger">*</span></label>
                                                        </div>
                                                        <div class="form-group" style="margin-top: -20px;">
                                                            <input type="text" class="form-control col-md-6 form-check-inline" name="code" value="{{$value->code}}" required/>
                                                            <span class="col-md-6 text-danger">* Maximum 3 Characters</span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Parent Category</label>
                                                            <select class="form-control" name="id_parent" id="id_parent">
                                                                <option value="0"></option>
                                                                @foreach($categories as $key2 => $val)
                                                                    <option value="{{$val->id}}" @if($val->id == $value->id_parent) SELECTED @endif>{{$val->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                                                <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                                                    <i class="fa fa-check"></i>
                                                    Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <td class="text-center">
                                <a href="#edit{{$value->id}}" data-toggle="modal" class="btn btn-sm btn-primary btn-icon btn-icon-md" title="Edit"><i class="fa fa-edit"></i></a>
                                <a href="{{route('category.del',['id' => $value->id])}}" title="Delete" class="btn btn-sm btn-danger btn-icon btn-icon-md" onclick="return confirm('Delete Category?')"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    @actionEnd
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addEmployee" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('category.store')}}" >
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="form col-md-12">
                                <div class="form-group">
                                    <label>Category Name</label>
                                    <input type="text" class="form-control" name="name" required/>
                                </div>
                                <div class="form-group">
                                    <label>Category Code <span class="text-danger">*</span></label>
                                </div>
                                <div class="form-group" style="margin-top: -20px;">
                                    <input type="text" class="form-control col-md-6 form-check-inline" name="code" id="code" required/>
                                    <span class="col-md-6 text-danger">* Maximum 3 Characters</span>
                                </div>
                                <div class="form-group">
                                    <label>Parent Category</label>
                                    <select class="form-control" name="id_parent" id="id_parent">
                                        <option value="0"></option>
                                        @foreach($categories as $key2 => $val)
                                            <option value="{{$val->id}}" @if($val->id == $value->id_parent) SELECTED @endif>{{$val->name}}</option>
                                        @endforeach
                                    </select>
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
    <div class="modal fade" id="addItems" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
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
                            <label class="col-md-3 col-form-label text-right">Item Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" placeholder="Item Name" name="item_name" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Item Category</label>
                            <div class="col-md-9">
                                <select name="category" class="form-control select2" id="item-category" required>
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Item Classification</label>
                            <div class="col-md-9">
                                <select name="class_id" class="form-control select2" id="item-class" required>
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
                                <select name="uom" id="uom" class="form-control" required>
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
        function codeLength(value){
            var maxLength = 3;
            if(value.length > maxLength) return false;
            return true;
        }
        $(document).ready(function () {
            $('.display').DataTable({
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            });
            $('#code').on('keyup', function () {
                if (!codeLength($(this).val())){
                    alert('CODE IS TOO LONG!')
                }
            })

            $("#item-category").select2({
                width : "100%"
            })

            $("#item-category").change(function(){
                console.log(this.value)
                $("#item_code").val("")
                $("#item-class").select2({
                    ajax : {
                        url : "{{ route('items.approval.class.get') }}/" + $("#item-category").val(),
                        dataType : 'json'
                    }
                })
            })

            $("#item-class").change(function(){
                $.ajax({
                    url : "{{ route('items.approval.get.code') }}",
                    type : "post",
                    dataType : "json",
                    data : {
                        _token : "{{ csrf_token() }}",
                        cat : $("#item-category").val(),
                        class : $("#item-class").val()
                    },
                    cache : false,
                    success : function(response){
                        $("#item_code").val(response)
                    }
                })
            })
        });
    </script>
@endsection
