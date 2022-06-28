@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Storage List</h3><br>

            </div>
            @actionStart('warehouses', 'create')
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addItem"><i class="fa fa-plus"></i>Add Storage</button>
                </div>
                <!--end::Button-->
            </div>
            @actionEnd
        </div>
        <div class="card-body">
            <table class="table display">
                <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Storage Name</th>
                    <th class="text-center">Address</th>
                    <th class="text-center">Phone</th>
                    <th class="text-center">PIC</th>
                    <th class="text-center">Company</th>
                    <th class="text-center">&nbsp;</th>
                </tr>
                </thead>
                <tbody>
                    @actionStart('warehouses', 'read')
                @foreach($whs as $key => $val)
                    @php
                        if(!empty($val->office)){
                            if($val->office == 1){
                                $icon = "fas fa-building";
                            } elseif($val->office == 2){
                                $icon = "fas fa-warehouse";
                            } elseif($val->office == 3){
                                $icon = "fas fa-map-marker";
                            } else {
                                $icon = "fas fa-people";
                            }
                        }
                    @endphp
                    <tr>
                        <td class="text-center">{{($key+1)}}</td>
                        <td class="text-center">
                            <a class="btn btn-link" href="{{route('items.warehouses',['id_wh' => $val->id])}}">
                                {!! (!empty($val->office)) ? '<i class="'.$icon.' text-primary"></i>' : '' !!} {{$val->name}}
                            </a>
                        </td>
                        <div class="modal fade" id="editItem{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Edit Storage</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <i aria-hidden="true" class="ki ki-close"></i>
                                        </button>
                                    </div>
                                    <form method="post" action="{{route('wh.update')}}" >
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-group row">
                                                <label class="col-md-3 col-form-label text-right">Storage Name</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" name="name" placeholder="Storage Name" value="{{$val->name}}">
                                                </div>
                                            </div>
                                            <input type="hidden" name="id" value="{{$val->id}}">
                                            <div class="form-group row">
                                                <label class="col-md-3 col-form-label text-right">Address</label>
                                                <div class="col-md-9">
                                                    <textarea name="address" id="" class="form-control" cols="30" rows="10">{!! $val->address !!}</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 col-form-label text-right">Telephone</label>
                                                <div class="col-md-9">
                                                    <input type="text" name="telephone" class="form-control" placeholder="Telephone" value="{{$val->telephone}}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 col-form-label text-right">PIC</label>
                                                <div class="col-md-9">
                                                    <input type="text" name="pic" class="form-control" placeholder="PIC" value="{{$val->pic}}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 col-form-label text-right">Type</label>
                                                <div class="col-md-9">
                                                    <select name="_type" class="form-control select2" id="" data-placeholder="Select Type">
                                                        <option value="">Others</option>
                                                        <option value="1" {{ ($val->office == 1) ? "SELECTED" : "" }}>office</option>
                                                        <option value="2" {{ ($val->office == 2) ? "SELECTED" : "" }}>warehouse</option>
                                                        <option value="3" {{ ($val->office == 3) ? "SELECTED" : "" }}>project</option>
                                                    </select>
                                                </div>
                                            </div>
                                            {{-- <div class="form-group row">
                                                <label class="col-md-3 col-form-label text-right">Office</label>
                                                <div class="col-md-9">
                                                    <div class="checkbox-inline">
                                                        <label class="checkbox checkbox-outline checkbox-primary col-form-label">
                                                            <input type="checkbox" name="office" {{ ($val->office == 1) ? "checked" : "" }}/>
                                                            <span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div> --}}
                                            <div class="form-group row">
                                                <label class="col-md-3 col-form-label text-right">Longitude</label>
                                                <div class="col-md-9">
                                                    <input type="text" name="longitude" class="form-control" value="{{ $val->longitude }}" placeholder="Longitude">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 col-form-label text-right">Latitude</label>
                                                <div class="col-md-9">
                                                    <input type="text" name="latitude" class="form-control" value="{{ $val->latitude }}" placeholder="Latitude">
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
                        <td class="text-center">{{strip_tags($val->address)}}</td>
                        <td class="text-center">{{$val->telephone}}</td>
                        <td class="text-center">{{$val->pic}}</td>
                        <td class="text-center">
                            {{$view_company[$val->company_id]->tag}}
                        </td>
                        <td class="text-center" nowrap="now">
                            @actionStart('warehouses', 'update')
                            <button type="button" class="btn btn-primary btn-xs btn-icon" data-toggle="modal" data-target="#editItem{{$val->id}}"><i class="fa fa-edit"></i></button>
                            @actionEnd
                            @actionStart('warehouses', 'delete')
                            <a class="btn btn-danger btn-xs btn-icon" href="{{route('wh.delete',['id'=> $val->id])}}" title="Delete" onclick="return confirm('Are you sure you want to delete?'); ">
                                <i class="fa fa-trash"></i>
                            </a>
                            @actionEnd
                        </td>
                    </tr>
                @endforeach
                @actionEnd
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="addItem" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Storage</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('wh.store')}}" >
                    @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Storage Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="name" placeholder="Storage Name" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Address</label>
                            <div class="col-md-9">
                                <textarea name="address" id="" class="form-control" cols="30" rows="10" required></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Telephone</label>
                            <div class="col-md-9">
                                <input type="text" name="telephone" class="form-control" placeholder="Telephone" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">PIC</label>
                            <div class="col-md-9">
                                <input type="text" name="pic" class="form-control" placeholder="PIC" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Type</label>
                            <div class="col-md-9">
                                <select name="_type" class="form-control select2" id="" data-placeholder="Select Type">
                                    <option value="">Others</option>
                                    <option value="1">office</option>
                                    <option value="2">warehouse</option>
                                    <option value="3">project</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Longitude</label>
                            <div class="col-md-9">
                                <input type="text" name="longitude" class="form-control" placeholder="Longitude">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Latitude</label>
                            <div class="col-md-9">
                                <input type="text" name="latitude" class="form-control" placeholder="Latitude">
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
