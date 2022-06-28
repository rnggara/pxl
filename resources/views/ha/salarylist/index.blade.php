@extends('layouts.template')
@section('content')
    @if(session()->has('message_needsec_fail'))
        <div class="alert alert-danger">
            {!! session()->get('message_needsec_fail') !!}
        </div>
    @endif
    @if(session()->has('message_needsec_success'))
        <div class="alert alert-success">
            {!! session()->get('message_needsec_success') !!}
        </div>
    @endif
    @if(!(session()->has('seckey_sallist')) || (session()->has('seckey_sallist') < 10))
       @include('ha.needsec.index', ["type" => "sallist"])
    @else
        <div class="card card-custom gutter-b">
            <div class="card-header">
                <div class="card-title">
                    <h3>Salary List</h3><br>

                </div>
                @actionStart('salary_list', 'create')
                <div class="card-toolbar">
                    <form action="{{route('salarylist.generateTHR')}}" method="POST">
                        @csrf
                        <button type="submit" name="genthr" class="btn btn-primary" style="margin-right: 5px;">
                            <i class="fa flaticon-refresh"></i>&nbsp;&nbsp;Generate THR
                        </button>
                    </form>
                    <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-eraser"></i>&nbsp;&nbsp;Reset <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <form action="{{route('salarylist.reset')}}" method="POST">
                                @csrf
                                <button type="submit" name="thr" class="btn btn-xs btn-link">THR</button>
                            </form>
                        </li>
                        <li> <form action="{{route('salarylist.reset')}}" method="POST">
                                @csrf
                                <button type="submit" name="bonus" class="btn btn-xs btn-link">Bonus</button>
                            </form></li>
                    </ul>
                </div>
                @actionEnd
            </div>

            <div class="card-body">
                @actionStart('salary_list', 'read')
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    @foreach($types as $type)
                        <li class="nav-item">
                            <a class="nav-link @if($type->id == 1) active @endif" id="home-tab" data-toggle="tab" href="#list{{$type->id}}">
                            <span class="nav-icon">
                                <i class="flaticon-folder-1"></i>
                            </span>
                                <span class="nav-text">{{$type->name}}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
                <div class="tab-content mt-5" id="myTabContent">
                    @foreach($types as $type)
                        @php
                            $nomor = 1;
                        @endphp
                        <div class="tab-pane fade show @if($type->id == 1) active @endif" id="list{{$type->id}}" role="tabpanel" aria-labelledby="home-tab">
                            <form action="{{route('salarylist.save')}}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-11"></div>
                                    <div class="col-md-1">
                                        <input type="submit" name="save_all[{{$type->id}}]" class="btn btn-xs btn-success" value="Save">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-bordered font-size-sm table-responsive display" style="margin-top: 13px">
                                            <thead>
                                            <tr>
                                                <th class="text-center" nowrap="nowrap">#</th>
                                                <th class="text-center" style="width: 150px !important">Employee ID</th>
                                                <th class="text-left" style="width: 200px !important">Name</th>
                                                <th class="text-center" nowrap="nowrap">Total Salary</th>
                                                <th class="text-center" style="width: 150px !important" nowrap="nowrap">Basic</th>
                                                <th class="text-center" style="width: 150px !important" nowrap="nowrap">Voucher</th>
                                                <th class="text-center" style="width: 150px !important" nowrap="nowrap">Position Allowance</th>
                                                <th class="text-center" nowrap="nowrap">Salary</th>
                                                <th class="text-center" nowrap="nowrap">Transport</th>
                                                <th class="text-center" nowrap="nowrap">Meal</th>
                                                <th class="text-center" nowrap="nowrap">House</th>
                                                <th class="text-center" nowrap="nowrap">Health</th>
                                                <th class="text-center" style="width: 150px !important" nowrap="nowrap">Pension</th>
                                                <th class="text-center" style="width: 150px !important" nowrap="nowrap">Health Insurance</th>
                                                <th class="text-center" style="width: 150px !important" nowrap="nowrap">Jamsostek</th>
                                                <th class="text-center" style="width: 150px !important" nowrap="nowrap">Overtime</th>
                                                <th class="text-center" style="width: 150px !important" nowrap="nowrap">Field Bonus</th>
                                                <th class="text-center" style="width: 150px !important" nowrap="nowrap">ODO Bonus</th>
                                                <th class="text-center" style="width: 150px !important" nowrap="nowrap">WH Bonus</th>
                                                <th class="text-center" style="width: 150px !important" nowrap="nowrap">Performa Bonus Multiplier</th>
                                                <th class="text-center" style="width: 150px !important" nowrap="nowrap">Performa Bonus Amount</th>
                                                <th class="text-center" style="width: 150px !important" nowrap="nowrap">THR</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($employees as $key => $val)
                                                @if($val->emp_type == $type->id)
                                                    <tr>
                                                        @php
                                                            /** @var TYPE_NAME $val */
                                                            $salary = base64_decode($val->salary);
                                                            $transport= base64_decode($val->transport);
                                                            $meal = base64_decode( $val->meal);
                                                            $house= base64_decode($val->house);
                                                            $health = base64_decode($val->health);
                                                            $voucher = $val->voucher;
                                                            $allowance =$val->allowance_office;

                                                            $total = $salary+$transport+$meal+$house+$health+$voucher+$allowance;
                                                            $basic = $salary+$transport+$meal+$house+$health;
                                                        @endphp
                                                        <td class="text-center">
                                                            {{($nomor++)}}
                                                            <input type='checkbox' name='checkedit[{{$val->id}}]' value='{{$val->id}}' />
                                                        </td>
                                                        <td class="text-center"><a href="{{route('salarylist.history',['id' => $val->id])}}" class="btn btn-link btn-xs"><i class="fa fa-search"></i>{{($val->emp_id)}}</a></td>
                                                        <td class="text-left">{{($val->emp_name)}}</td>
                                                        <td class="text-center">{{number_format($total,2)}}</td>
                                                        <td class="text-center" width="20%">
                                                            <input type="number" name="BASIC[{{$val->id}}]" class="form-control" value="{{$basic}}">
                                                        </td>
                                                        <td class="text-center" width="20%">
                                                            <input type="number" name="VOUCHER[{{$val->id}}]" class="form-control" value="{{$voucher}}">
                                                        </td>
                                                        <td class="text-center" width="20%">
                                                            <input type="number" name="ALLOWANCE[{{$val->id}}]" class="form-control" value="{{$allowance}}">
                                                        </td>
                                                        <td class="text-center">
                                                            {{number_format($salary,2)}}
                                                        </td>
                                                        <td class="text-center">
                                                            {{number_format($transport,2)}}
                                                        </td>
                                                        <td class="text-center">
                                                            {{number_format($meal,2)}}
                                                        </td>
                                                        <td class="text-center">
                                                            {{number_format($house,2)}}
                                                        </td>
                                                        <td class="text-center">
                                                            {{number_format($health,2)}}
                                                        </td>
                                                        @php
                                                            /** @var TYPE_NAME $val */
                                                            $pension = $val->pension;
                                                            /** @var TYPE_NAME $total */
                                                            /** @var TYPE_NAME $voucher */
                                                            /** @var TYPE_NAME $allowance */
                                                            $pen_default = round(($total+$voucher+$allowance)/12,0);
                                                            $rrr = strpos($val->emp_id,'K');
                                                            if ($pension != $pen_default && $rrr !=0){
                                                                $pensionisi = $pen_default;
                                                            } else {
                                                                $pensionisi = $pension;
                                                            }
                                                        @endphp
                                                        <td class="text-center" width="20%">
                                                            <input type="number" name="PENSION[{{$val->id}}]" class="form-control" value="{{$pensionisi}}">
                                                        </td>
                                                        <td class="text-center" width="20%">
                                                            <input type="number" name="HEALTH_I[{{$val->id}}]" class="form-control" value="{{$val->health_insurance}}">
                                                        </td>
                                                        <td class="text-center" width="20%">
                                                            <input type="number" name="JAMSOSTEK[{{$val->id}}]" class="form-control" value="{{$val->jamsostek}}">
                                                        </td>
                                                        <td class="text-center" width="20%">
                                                            <input type="number" name="OVERTIME[{{$val->id}}]" class="form-control" value="{{$val->overtime}}">
                                                        </td>
                                                        <td class="text-center" width="20%">
                                                            <input type="number" name="FLD_BONUS[{{$val->id}}]" class="form-control" value="{{$val->fld_bonus}}">
                                                        </td>
                                                        <td class="text-center" width="20%">
                                                            <input type="number" name="ODO_BONUS[{{$val->id}}]" class="form-control" value="{{$val->odo_bonus}}">
                                                        </td>
                                                        <td class="text-center" width="20%">
                                                            <input type="number" name="WH_BONUS[{{$val->id}}]" class="form-control" value="{{$val->wh_bonus}}">
                                                        </td>
                                                        <td class="text-center" width="20%">
                                                            <input type="text" name="YEARLY[{{$val->id}}]" class="form-control number" value="{{$val->yearly_bonus}}">
                                                        </td>
                                                        <td class="text-center" width="20%">
                                                            <input type="text" name="FX_YEARLY[{{$val->id}}]" class="form-control number" value="{{$val->fx_yearly_bonus}}">
                                                        </td>
                                                        <td class="text-center" width="20%">
                                                            <input type="text" name="THR[{{$val->id}}]" class="form-control number" value="{{$val->thr}}">
                                                        </td>
                                                        <input name="type[{{$val->id}}]" type="hidden" id="type" value='{{$type->name}}' />
                                                        <input name="ID[{{$val->id}}]" type="hidden" id="ID" value="{{$val->id}}" />
                                                    </tr>
                                                @endif
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endforeach
                </div>
                @actionEnd
            </div>
        </div>
    @endif
@endsection
@section('custom_script')
    <script src="{{ asset('assets/jquery-number/jquery.number.js') }}"></script>
    <script>
        $(document).ready(function(){
            $("table.display").DataTable({
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                },
                searching: false
            })

            $("input.number").number(true, 2)
        })
    </script>
@endsection
