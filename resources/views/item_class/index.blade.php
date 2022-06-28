@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                Item Classification - &nbsp;<span class="text-primary">{{$category->name}}</span>
            </div>
            @actionStart('item_database', 'create')
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addEmployee"><i class="fa fa-plus"></i>Add Classification</button> &nbsp;&nbsp;
                    <a href="{{route('category.index')}}" class="btn btn-success"><i class="fa fa-arrow-left"></i></a>
                </div>
                <!--end::Button-->
            </div>
            @actionEnd
        </div>
        <div class="card-body">
            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th nowrap="nowrap">Classification Name</th>
                        <th nowrap="nowrap" class="text-center">Classification Code</th>
                        <th nowrap="nowrap" class="text-center">Category</th>
                        <th nowrap="nowrap" class="text-center">Quantity</th>
                        <th nowrap="nowrap" data-priority=1 class="text-center"></th>
                    </tr>
                    </thead>
                    <tbody>
                        @php
                            $link = 'items.index';
                            if (isset($type) && $type == "inventory"){
                                $link = 'itemsinventory.inventory';
                            }
                        @endphp
                    @actionStart('item_database', 'read')
                    @foreach($classifications as $key => $value)
                        <tr>
                            <td>{{($key+1)}}</td>
                            <td>
                                <a href="{{route($link, ['category'=>$cat_id,'classification' =>$value->id])}}">
                                    <span class="label label-inline label-primary">
                                        {{$value->classification_name}}
                                    </span>
                                </a>

                            </td>
                            <td class="text-center">
                                {{$value->classification_code}}
                            </td>
                            <td class="text-center">
                                {{$value->catName}}
                            </td>
                            <td align="center">
                                <span class="label label-inline label-primary">
                                    {{ (isset($class_item[$value->id])) ? count($class_item[$value->id]) : 0 }}
                                </span>
                            </td>
                            <div class="modal fade" id="edit{{$value->id}}" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Edit Classification</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <i aria-hidden="true" class="ki ki-close"></i>
                                            </button>
                                        </div>
                                        <form method="post" action="{{route('item_class.update')}}" >
                                            @csrf
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="form col-md-12">
                                                        <div class="form-group">
                                                            <label>Classification Name</label>
                                                            <input type="hidden" name="id" id="id" value="{{$value->id}}">
                                                            <input type="text" class="form-control" name="name" value="{{$value->classification_name}}" required/>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Classification Code <span class="text-danger">*</span></label>
                                                        </div>
                                                        <div class="form-group" style="margin-top: -20px;">
                                                            <input type="text" class="form-control col-md-6 form-check-inline" name="code" value="{{$value->classification_code}}" required/>
                                                            <span class="col-md-6 text-danger">* Maximum 3 Characters</span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Category</label>
                                                            <select class="form-control" name="category">
                                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
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
                                <a href="{{route('item_class.delete',['id' => $value->id])}}" title="Delete" class="btn btn-sm btn-danger btn-icon btn-icon-md" onclick="return confirm('Delete Category?')"><i class="fa fa-trash"></i></a>
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
                    <h5 class="modal-title" id="exampleModalLabel">Add Classification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('item_class.store')}}" >
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="form col-md-12">
                                <div class="form-group">
                                    <label>Classification Name</label>

                                    <input type="text" class="form-control" name="name" required/>
                                </div>
                                <div class="form-group">
                                    <label>Classification Code <span class="text-danger">*</span></label>
                                </div>
                                <div class="form-group" style="margin-top: -20px;">
                                    <input type="text" class="form-control col-md-6 form-check-inline" name="code" id="code" required/>
                                    <span class="col-md-6 text-danger">* Maximum 5 Characters</span>
                                </div>
                                <div class="form-group">
                                    <label>Category</label>
                                    <select class="form-control" name="category">
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
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
            var maxLength = 5;
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
