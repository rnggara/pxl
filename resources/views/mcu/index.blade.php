
@extends('layouts.template')
@section('content')
    @if(session()->has('failed_mcu'))
        <div class="alert alert-danger">
            {{ session()->get('failed_mcu') }}
        </div>
    @endif
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                Medical Check Up (MCU)
            </div>
            <div class="card-toolbar">
                @actionStart('mcu', 'create')
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addEmployee"><i class="fa fa-plus"></i>Add MCU</button>
                </div>
                @actionEnd
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th nowrap="nowrap" class="text-center">Employee Name</th>
                        <th nowrap="nowrap" class="text-center">Last Check Up</th>
                        <th nowrap="nowrap" class="text-center">Last Remarks</th>
                        <th nowrap="nowrap" class="text-center">Expired Date</th>
                        <th nowrap="nowrap" class="text-center">Log Document</th>
                        <th nowrap="nowrap" data-priority=1 class="text-center"></th>
                    </tr>
                    </thead>
                    <tbody>
                        @actionStart('mcu', 'read')
                    @foreach($main as $key => $value)
                        @php

                            /** @var TYPE_NAME $value */
                            $date1 = date('Y-m-d');
                            /** @var TYPE_NAME $expDate */
                            $date2 = (isset($expDate[$value->id][0]) ? $expDate[$value->id][0] : date('Y-m-d',strtotime('-1 days')));
                            $diff = (strtotime($date2) - strtotime($date1))/60/60/24;

                        @endphp
                        <tr @if($diff < 30) class="bg-danger text-white" @endif>
                            <td>{{($key+1)}}</td>
                            <td class="text-center">{{$value->empName}}</td>
                            <td class="text-center">
                                {{(isset($lastCheckUp[$value->id][0])) ? date('d F Y',strtotime($lastCheckUp[$value->id][0])) :'-'}}
                            </td>
                            <td class="text-center">{{(isset($lastRemark[$value->id][0])) ? $lastRemark[$value->id][0]:'-'}}</td>
                            <td class="text-center">{{(isset($expDate[$value->id][0])) ? date('d F Y',strtotime($expDate[$value->id][0])):'-'}}</td>
                            <td class="text-center">
                                <a href="{{route('mcu.logs',['id' => $value->id])}}" title="Log Document" class="btn btn-primary btn-icon btn-xs"><i class="fa fa-search"></i></a>
                            </td>
                            <td width="10%" class="text-center">
                                @actionStart('mcu', 'delete')
                                <a href="{{route('mcu.delete',['id' => $value->id])}}" title="Delete" class="btn  @if($diff < 30) btn-secondary @else btn-danger @endif btn-icon btn-xs" onclick="return confirm('Delete MCU?')"><i class="fa fa-trash"></i></a>
                                @actionEnd
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
                    <h5 class="modal-title" id="exampleModalLabel">Add CSR</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('mcu.store')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="form col-md-12">

                                <div class="form-group">
                                    <label>Employee</label>
                                    <select name="employee" id="employee" class="form-control">
                                        @foreach($employees as $val)
                                            <option value="{{$val->id}}">{{$val->emp_name}}</option>
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
                            Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')

@endsection
