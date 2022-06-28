@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Travel Order</h3><br>

            </div>
            @actionStart('to', 'create')
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{ route('to.index') }}" class="btn btn-success"><i class="fa fa-arrow-left"></i></a>
                </div>
                <!--end::Button-->
            </div>
            @actionEnd
        </div>
        <div class="card-body">
            <div class="col-md-4 col-sm-4">
                <div class="alert alert-info" role="alert">
                    <i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;&nbsp;This page contains a list of Travel Order which has been formed.
                </div>
            </div>

            <table class="table display">
                <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">TO #</th>
                    <th class="text-left">Employee</th>
                    <th class="text-center">Destination</th>
                    <th class="text-center" style="width: 30%">Project</th>
                    <th class="text-center">Departs On</th>
                    <th class="text-center">Returns On</th>
                    <th class="text-center" nowrap="nowrap">Departure Ticket</th>
                    <th class="text-center" nowrap="nowrap">Return Ticket</th>
                </tr>
                </thead>
                <tbody>
                @actionStart('to', 'read')
                @foreach($to as $key => $value)
                        <tr>
                            <td>{{($key+1)}}</td>
                            <td class="text-center"><a href="{{route('to.edit',['id' => $value->id])}}" class="btn-link">{{$value->doc_num}}</a></td>
                            <td>{{(isset($emp_name[$value->employee_id]))?$emp_name[$value->employee_id]:''}}</td>
                            <td class="text-center">{{$value->destination}}</td>
                            <td class="text-center">{{(isset($prj_name[$value->project]))?$prj_name[$value->project]:''}}</td>
                            <td class="text-center">{{date('d F Y', strtotime($value->departure_dt))}}</td>
                            <td class="text-center">{{date('d F Y', strtotime($value->return_dt))}}</td>
                            <td nowrap="nowrap">
                                <form action="{{ route('to.ticket.se', 'departure') }}" method="post">
                                    @csrf
                                    @php
                                        $no = "";
                                        $btn_bg = "primary";
                                        $icon = "pencil-alt";
                                    @endphp
                                    @if (isset($se[$value->departure_no]))
                                        <label for="" class="label label-inline label-primary">{{ $se[$value->departure_no] }}</label>
                                        @php
                                            $no = $se[$value->departure_no];
                                            $btn_bg = "danger";
                                            $icon = "times";
                                        @endphp
                                    @endif
                                    <div class="form-group">
                                        <div class="input-group">
                                            <select name="dt_time" class="form-control" id="">
                                                <option value="1" {{ ($value->departure_time == 1) ? "SELECTED" : "" }}>Pagi</option>
                                                <option value="2" {{ ($value->departure_time == 2) ? "SELECTED" : "" }}>Siang</option>
                                                <option value="3" {{ ($value->departure_time == 3) ? "SELECTED" : "" }}>Malam</option>
                                            </select>
                                            <div class="input-group-append">
                                                <input type="hidden" name="id_to" value="{{ $value->id }}">
                                                <input type="hidden" name="post_type" value="{{ (!empty($no)) ? "delete" : "create" }}">
                                                <button type="submit" class="btn btn-{{ $btn_bg }}"><i class="fa fa-{{ $icon }}"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </td>
                            <td nowrap="nowrap">
                                <form action="{{ route('to.ticket.se', 'return') }}" method="post">
                                    @csrf
                                    @php
                                        $no_return = "";
                                        $btn_bg_return = "primary";
                                        $icon_return = "pencil-alt";
                                    @endphp
                                    @if (isset($se[$value->return_no]))
                                        <label for="" class="label label-inline label-primary">{{ $se[$value->return_no] }}</label>
                                        @php
                                            $no_return = $se[$value->return_no];
                                            $btn_bg_return = "danger";
                                            $icon_return = "times";
                                        @endphp
                                    @endif
                                    <div class="form-group">
                                        <div class="input-group">
                                            <select name="dt_time" class="form-control" id="">
                                                <option value="1" {{ ($value->return_time == 1) ? "SELECTED" : "" }}>Pagi</option>
                                                <option value="2" {{ ($value->return_time == 2) ? "SELECTED" : "" }}>Siang</option>
                                                <option value="3" {{ ($value->return_time == 3) ? "SELECTED" : "" }}>Malam</option>
                                            </select>
                                            <div class="input-group-append">
                                                <input type="hidden" name="id_to" value="{{ $value->id }}">
                                                <input type="hidden" name="post_type" value="{{ (!empty($no_return)) ? "delete" : "create" }}">
                                                <button type="submit" class="btn btn-{{ $btn_bg_return }}"><i class="fa fa-{{ $icon_return }}"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @actionEnd
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="addTo" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Travel Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('to.add')}}" >
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="form col-md-12">
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Employee</label>
                                    <div class="col-md-6">
                                        <select name="emp" id="emp" class="form-control" required>
                                            <option></option>
                                            @foreach($emp as $value)
                                                <option value="{{$value->id}}">{{$value->emp_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Type of Travel</label>
                                    <div class="col-md-6">
                                        <select name="type_travel" id="type_travel" class="form-control" required>
                                            <option></option>
                                            <option value="dom">domestic</option>
                                            <option value="ovs">overseas</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Project</label>
                                    <div class="col-md-6">
                                        <select name="project" id="project" class="form-control" required>
                                            <option></option>
                                            @foreach($prj as $value)
                                                <option value="{{$value->id}}">{{$value->prj_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Proceed</button>
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
            });
        })
    </script>
@endsection
