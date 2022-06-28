@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                Inventory Category
            </div>
            @actionStart('item_database', 'create')
            <!-- <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addEmployee"><i class="fa fa-plus"></i>Add Category</button>
                </div>
            </div> -->
            @actionEnd
        </div>
        <div class="card-body">
            <form method="post" action="{{route('categoryinventory.search')}}">
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
                                <a href="{{route('item_classinventory.index', $value->id)}}">
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
        });
    </script>
@endsection
