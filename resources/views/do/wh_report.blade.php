@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Delivery Order Report on {{$wh->name}}</h3> &nbsp;&nbsp;

            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{route('do.index')}}" class="btn btn-secondary btn-xs" ><i class="fa fa-arrow-left"></i></a>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <form method="post" action="{{route('do.whReport',['id_wh' => $id_wh])}}">
                @csrf
                <input type="hidden" name="id_wh" id="id_wh" value="{{$id_wh}}">
                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-right">Periode :</label>
                    <div class="col-md-3">
                        <select name="month" id="month" class="form-control">
                            @foreach($months as $key => $month)
                                @if($key == $s_month)
                                    <option value="{{$key}}" SELECTED>{{$month}}</option>
                                @else
                                    <option value="{{$key}}">{{$month}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="year" id="year" class="form-control">
                            @for($y=2016; $y <= (($now->year)+2); $y++)
                                @if($y == $s_year)
                                    <option value="{{$y}}" SELECTED>{{$y}}</option>
                                @else
                                    <option value="{{$y}}">{{$y}}</option>
                                @endif
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" name="show" class="btn btn-primary"><i class="fa fa-search"></i> Show</button>
                    </div>
                </div>
            </form>
            @if(isset($post) && $post ==1)
                <table id="table-display" class="table table-bordered table-responsive">
                    <thead>
                    <tr>
                        <th nowrap="nowrap" class="text-center" rowspan="2">#</th>
                        <th nowrap="nowrap" class="text-left px-30" rowspan="2" width="15%">Item</th>
                        @for($i = 0; $i< $days; $i++)
                            <th nowrap="nowrap" class="text-center" colspan="2">{{($i+1)}}</th>
                        @endfor
                        <th nowrap="nowrap" class="text-center" colspan="2">Balance</th>
                    </tr>
                    <tr>
                        @for($i = 0; $i< $days; $i++)
                            <th nowrap="nowrap" class="text-center" >IN</th>
                            <th nowrap="nowrap" class="text-center" >OUT</th>
                        @endfor
                            <th nowrap="nowrap" class="text-center" >IN</th>
                            <th nowrap="nowrap" class="text-center" >OUT</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $no = 0;
                    @endphp
                    @if(count($itemQty)>0)
                        @foreach($itemQty as $key => $value)
                            @php
                                /** @var TYPE_NAME $no */
                                $no += 1;
                            @endphp
                            <tr>
                                @php
                                    $total_in = 0;
                                    $total_out = 0;
                                @endphp
                                <td class="text-center">{{$no}}</td>
                                <td>{{(isset($itemName[$key]))?$itemName[$key]:"[Undefined Item]"}}</td>
                                @for($i = 0; $i< $days; $i++)
                                    @php
                                        /** @var TYPE_NAME $i */
                                        $in = 0;
                                        $out = 0;
                                        if (isset($value[$i+1]['in'])){
                                            for ($j = 0; $j< count($value[$i+1]['in']); $j++){
                                                $in += $value[$i+1]['in'][$j];
                                            }
                                            /** @var TYPE_NAME $total_in */
                                            $total_in += $in;
                                        }
                                        if (isset($value[$i+1]['out'])){
                                            for ($j = 0; $j< count($value[$i+1]['out']); $j++){
                                                $out += $value[$i+1]['out'][$j];
                                            }
                                            /** @var TYPE_NAME $total_out */
                                            $total_out += $out;
                                        }
                                    @endphp
                                    <td nowrap="nowrap" class="text-center">{{($in>0)?$in:''}}</td>
                                    <td nowrap="nowrap" class="text-center">{{($out>0)?"-".$out:''}}</td>
                                @endfor
                                <td nowrap="nowrap" class="text-center" >{{$total_in}}</td>
                                <td nowrap="nowrap" class="text-center" >{{($total_out > 0)?"-".$total_out:0}}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td align="center" colspan="{{$days+4}}">
                               No Record Found
                            </td>
                        </tr>
                    @endif

                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
@section('custom_script')
@endsection
