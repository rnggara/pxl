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
                    <a href="{{ route('to.ticketing') }}" class="btn btn-info"><i class="fa fa-eye"></i>Ticketing View</a>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addTo"><i class="fa fa-plus"></i>New Travel Order</button>
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
                    <th class="text-center">Project</th>
                    <th class="text-center">Departs On</th>
                    <th class="text-center">Returns On</th>
                    <th class="text-center">FT</th>
                    <th class="text-center">Approval</th>
                    <th class="text-center">Check</th>
                    <th class="text-center">FT Status</th>
                    <th class="text-center">Action</th>
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
                            <td class="text-center">
                                @if (isset($prj_name[$value->project]))
                                    {{ $prj_name[$value->project] }}
                                @else
                                    @if (isset($data_prj['name'][$value->project]))
                                        {{ $data_prj['name'][$value->project] }} [{{ $comp_tag[$data_prj['comp'][$value->project]] }}]
                                    @endif
                                @endif
                            </td>
                            <td class="text-center">{{date('d F Y', strtotime($value->departure_dt))}}</td>
                            <td class="text-center">{{date('d F Y', strtotime($value->return_dt))}}</td>
                            @php
                                /** @var TYPE_NAME $value */
                                $to_duration = $value->duration;
                                if($value->dest_type == "wh"){
                                    $to_duration = $value->duration + 1;
                                }
                                $meal = intval($to_duration) * intval($value->to_meal);
                                $spending = intval($to_duration) * intval($value->to_spending);
                                $overnight = intval($to_duration) * intval($value->to_overnight);
                                $transport = intval($value->transport);
                                $local_trans = intval($value->to_transport);
                                $taxi = intval($value->taxi);
                                $carrent = intval($value->rent);
                                $airtax = intval($value->airtax);

                                $totalcostFT = $meal + $spending + $overnight + $transport + $local_trans + $taxi + $carrent + $airtax;

                            @endphp
                            <td class="text-right"><a href="{{route('to.ftdetail',['id' => $value->id])}}" class="btn-link">{{number_format($totalcostFT,2)}}</a></td>
                            <td class="text-center">
                                @if($value->action == null)
                                    <a class='btn-link' href='{{route('to.tsappr',['id'=>$value->id,'code' => 'approve'])}}'>waiting</a>
                                @else
                                    @if($value->action == 'approve')
                                        <button class="btn btn-circle btn-icon btn-xs btn-success" disabled><i class='fa fa-check-circle'></i></button>{{ $value->action_by }} at {{date("d F Y", strtotime($value->action_time))}}
                                    @else
                                        <button class="btn btn-circle btn-icon btn-xs btn-danger" disabled><i class='fa fa-window-close'></i></button>{{ $value->action_by }} at {{date("d F Y", strtotime($value->action_time))}}
                                    @endif
                                @endif
                            </td>
                            <td class="text-center">
                                @if(($value->action != null)&&($value->admin == null))
                                    @if($value->action == 'approve')
                                        <a class='btn-link' href='{{route('to.tsappr',['id'=>$value->id,'code' => 'check'])}}'>check</a>
                                    @else
                                        N/A
                                    @endif
                                @elseif(($value->action == null)&&($value->admin != null))
                                    {{$value->admin}}&nbsp;|&nbsp;<button class="btn btn-circle btn-icon btn-xs btn-success" disabled><i class='fa fa-check-circle'></i></button>&nbsp;&nbsp;{{date("d-m-Y", strtotime($value->admin_time))}}
                                @elseif(($value->action != null)&&($value->admin != null))
                                    @if($value->action == 'approve')
                                        {{$value->admin}}&nbsp;&nbsp;<button class="btn btn-circle btn-icon btn-xs btn-success" disabled><i class='fa fa-check-circle'></i></button>&nbsp;&nbsp;{{date("d-m-Y", strtotime($value->admin_time))}}<br>
                                        @if($value->paid_by == null && $value->paid_time == null)
                                            <a href='{{route('to.tsappr',['id'=>$value->id,'code' => 're-check'])}}' class='btn-link'>re-check</a>
                                        @endif
                                    @else
                                        N/A
                                    @endif
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="text-center">
                                @if(($value->paid_by == null) && $value->action != null)
                                    @if($value->action == 'approve')
                                        <a class='btn-link' href='{{route('to.tsappr',['id'=>$value->id,'code' => 'pay'])}}'>Pay</a>
                                    @else
                                        N/A
                                    @endif

                                @elseif($value->paid_by != null)
                                    <button class="btn btn-circle btn-icon btn-xs btn-success" disabled><i class='fa fa-check-circle'></i></button>&nbsp;&nbsp;&nbsp;&nbsp;
                                    Paid by: {{$value->paid_by}} at {{$value->paid_time}}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="text-center">
                                @actionStart('to', 'delete')
                                <a class="btn btn-danger btn-xs dttb" href="{{route('to.delete',['id'=> $value->id])}}" title="Delete" onclick="return confirm('Are you sure you want to delete?'); ">
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
                                        <select name="emp" id="emp" class="form-control select2" required data-placeholder="Select Employee">
                                            <option value=""></option>
                                            @foreach($emp as $value)
                                                <option value="{{$value->id}}">{{$value->emp_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Type of Travel</label>
                                    <div class="col-md-6">
                                        <select name="type_travel" id="type_travel" class="form-control select2" data-placeholder="Select Type" required>
                                            <option value=""></option>
                                            <option value="dom">domestic</option>
                                            <option value="ovs">overseas</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Project</label>
                                    <div class="col-md-6">
                                        <select name="project" id="project" class="form-control select2" data-placeholder="Select Project" required>
                                            <option value=""></option>
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

            $("select.select2").select2({
                width : "100%"
            })
        })
    </script>
@endsection
