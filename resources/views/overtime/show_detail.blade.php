@extends('layouts.template')
@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">Overtime - <b>{{$emp->emp_name}}</b></h3>
                <!-- <span class="d-block text-muted pt-2 font-size-sm">Datatable initialized from HTML table</span></h3> -->
            </div>
        </div>
        <hr>
        <div class="card-body">
            <form method="post" action="{{route('overtime.storeOvertime')}}">
                <input type="hidden" name="id_emp" id="" value="{{$emp->id}}">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="text-center info" colspan="2">{{date("F Y", mktime(0,0,0,$month-1))}}</th>
                            </tr>
                            <tr>
                                <th class="text-center">Date</th>
                                <th class="text-center">Time In</th>
                                <th class="text-center">Time Out</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $disabled = "";
                                $btn = "primary";
                                $btn_label = "Save";
                                // if(date("d") > \Session::get('company_period_start') + 7){
                                //     $disabled = "disabled";
                                //     $btn = "danger";
                                //     $btn_label = "You can't input pass allowed time";
                                // }
                            @endphp
                            @for($i = 16; $i<= $max_col1; $i++)
                                @php
                                    $index = date("Ymd", mktime(0,0,0,$month-1,$i,$year));
                                @endphp
                                @if(in_array(date("w", strtotime($index)), array(0,6)))
                                    @php  $bgcolor = "orange"; @endphp
                                @else
                                    @php  $bgcolor = "white"; @endphp
                                @endif
                                <tr bgcolor="{{$bgcolor}}">
                                    <td align="center">{{date("d M", mktime(0,0,0,$month-1,$i,$year))}}</td>
                                    <td align="center">
                                        <input type="hidden" name="id_ovt[{{$index}}]" value="{{(!empty($idovt[$index]))?$idovt[$index]:''}}">
                                        <input name="overtime[{{$index}}]" id="tin{{$index}}" type="time" {{ $disabled }} class="form-control" value="{{(!empty($overtime[$index]))?$overtime[$index]:''}}" />
                                    </td>
                                    <td align="center">
                                        <input name="overtimeout[{{$index}}]" onchange="_min(this, {{ $index }})" id="tout{{$index}}" type="time" {{ $disabled }} class="form-control" value="{{(!empty($overtimeOut[$index]))?$overtimeOut[$index]:''}}" />
                                    </td>
                                </tr>
                            @endfor
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="text-center info" colspan="2">{{date("F Y", mktime(0,0,0,$month))}}</th>
                            </tr>
                            <tr>
                                <th class="text-center">Date</th>
                                <th class="text-center">Time In</th>
                                <th class="text-center">Time Out</th>
                            </tr>
                            </thead>
                            <tbody>

                            @for($i = 1; $i<= 15; $i++)
                                @php
                                    $index = date("Ymd", mktime(0,0,0,$month,$i,$year));
                                @endphp
                                @if(in_array(date("w", strtotime($index)), array(0,6)))
                                    @php  $bgcolor = "orange"; @endphp
                                @else
                                    @php  $bgcolor = "white"; @endphp
                                @endif
                                <tr bgcolor="{{$bgcolor}}">
                                    <td align="center">{{date("d M", mktime(0,0,0,$month,$i,$year))}}</td>
                                    <td align="center">
                                        <input type="hidden" name="id_ovt[{{$index}}]" value="{{(!empty($idovt[$index]))?$idovt[$index]:''}}">
                                        <input name="overtime[{{$index}}]" id="tin{{$index}}" type="time" {{ $disabled }} class="form-control" value="{{(!empty($overtime[$index]))?$overtime[$index]:''}}" />
                                    </td>
                                    <td align="center">
                                        <input name="overtimeout[{{$index}}]" onchange="_min(this, {{ $index }})" id="tout{{$index}}" type="time" {{ $disabled }} class="form-control" value="{{(!empty($overtimeOut[$index]))?$overtimeOut[$index]:''}}" />
                                    </td>
                                </tr>
                            @endfor
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6"></div>
                    <div class="col-md-6">
                        @csrf
                        @actionStart('overtime', 'create')
                        <div class="col-md-3">
                            <button class="btn btn-{{ $btn }}" type="submit" {{ $disabled }} name="save">{{ $btn_label }}</button>
                        </div>
                        @actionEnd
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('custom_script')
    <script>
        function _min(x, y){
            var min = $("#tin"+y).val()
            console.log(min+":00", $(x).val())
            if($(x).val()+":00" <= min+":00"){
                $(x).attr('title', 'must be larger than ...')
                alert("Time out must be larger than " + min)
                $(x).val(min)
            }
        }
        $(document).ready(function () {
            $('.display').DataTable({
                responsive: true,
            });
        });
    </script>
@endsection
