@extends('layouts.template')

@section('content')
    <div class="card gutter-b card-custom">
        <div class="card-header">
            <h3 class="card-title">PL Report</h3>
            <div class="card-toolbar">
                <button class="btn btn-secondary btn-sm"><i class="fa fa-cog"></i> Configuration</button>
            </div>
        </div>
        <div class="card-body">
            <form action="" method="post">
                <div class="row">
                    <div class="col-3 mx-auto">
                        <div class="form-group row">
                            <div class="col-6">
                                <select name="project" class="form-control select2" required data-placeholder="Select Project">
                                    <option value=""></option>
                                    @foreach ($projects as $item)
                                        <option value="{{ $item->id }}" {{ (!empty($prj_sel) && $item->id == $prj_sel->id) ? "SELECTED" : "" }}>{{ $item->prj_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                @csrf
                                <button type="submit" name="submit" value="search" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Search</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <hr>
            @if (!empty($prj_sel))
                <div class="row">
                    <div class="col-12 mx-auto">
                        <div class="text-center">
                            <h3>PROFIT AND LOSS REPORT</h3>
                            <h3>{{ $prj_sel->prj_name }}</h3>
                            <h3>Tahun Anggaran {{ date("Y", strtotime($prj_sel->start_time)) }}</h3>
                            <h3>KSO SOP-PSI</h3>
                        </div>
                    </div>
                    <div class="col-8 mx-auto">
                        <table class="table">
                            <thead>
                                <tr style="background-color: black; color: white;">
                                    <th>No</th>
                                    <th>Description</th>
                                    <th class="text-right">Actual Value</th>
                                    <th class="text-right">Percent</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="background-color: #9c9c9c;">
                                    <td colspan='2' style="color: #000; font-weight: bold;"><b>REVENUE</b></td>
                                    <td align="right">
                                        <span class="font-weight-boldest">
                                            {{ number_format($total['revenue'], 2) }}
                                        </span>
                                    </td>
                                    <td align="right">
                                        <span class="font-weight-boldest">
                                            {{ number_format(100, 2) }} %
                                        </span>
                                    </td>
                                </tr>
                                <tr style="background-color: yellow">
                                    <td colspan='4' style="color: #000; font-weight: bold;"><b>COST</b></td>
                                </tr>
                                @php
                                    $total_cost = 0;
                                    $total_cost_pctg = 0;
                                @endphp
                                @foreach ($pl as $i => $item)
                                @php
                                    $act_val = 0;
                                    $pctg = 0;
                                    if(isset($data[$item->id])){
                                        foreach ($data[$item->id] as $key => $value) {
                                            $act_val += $value['actual_value'];
                                        }
                                    }

                                    if($total['revenue'] > 0){
                                        $pctg = ($act_val / $total['revenue']) * 100;
                                    }
                                    $total_cost_pctg += $pctg;
                                    $total_cost += $act_val;
                                @endphp
                                    <tr style="background-color: yellow">
                                        <td align="center">{{ $i+1 }}</td>
                                        <td>{{ $item->subject }}</td>
                                        <td align="right">{{ number_format($act_val, 2) }}</td>
                                        <td align="right">
                                            {{ number_format($pctg, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr style="background-color: yellow;">
                                    <td colspan='2' align="center" style="color: #000; font-weight: bold;"><b>TOTAL COST</b></td>
                                    <td align="right">
                                        <span class="font-weight-boldest text-danger">
                                            {{ number_format($total_cost, 2) }}
                                        </span>
                                    </td>
                                    <td align="right">
                                        <span class="font-weight-boldest text-danger">
                                            {{ number_format($total_cost_pctg, 2) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr style="background-color: #68b575;">
                                    @php
                                        $total_opex = 0;
                                        $total_opex_pctg = 0;
                                        foreach ($opex as $key => $value) {
                                            if(isset($data[$value->id])){
                                                foreach ($data[$item->id] as $key => $value) {
                                                    $total_opex += $value['actual_value'];
                                                }
                                            }
                                        }

                                        if($total['revenue'] > 0){
                                            $total_opex_pctg = ($total_opex / $total['revenue']) * 100;
                                        }
                                    @endphp
                                    <td colspan='2' style="color: #000; font-weight: bold;"><b>OPEX</b></td>
                                    <td align="right">
                                        <span class="font-weight-boldest">
                                            {{ number_format($total_opex, 2) }}
                                        </span>
                                    </td>
                                    <td align="right">
                                        <span class="font-weight-boldest">
                                            {{ number_format($total_opex_pctg, 2) }} %
                                        </span>
                                    </td>
                                </tr>

                                {{-- NETT --}}
                                @php
                                    $npbt = $total['revenue'] - ($total_cost + $total_opex);
                                    $ntax = $npbt * ($prj_sel->actual_tax / 100);
                                    $npat = $npbt - $ntax;
                                    $profit = $npat - $prj_sel->sharing_profit;
                                @endphp
                                <tr style="background-color: #9c9c9c;">
                                    <td colspan='2' style="color: #000; font-weight: bold;"><b>NETT PROFIT BEFORE/AFTER TAX</b></td>
                                    <td align="right">
                                        <span class="font-weight-boldest">
                                            {{ number_format($npat, 2) }}
                                        </span>
                                    </td>
                                    <td align="right">
                                        <span class="font-weight-boldest">
                                            {{ number_format(100, 2) }} %
                                        </span>
                                    </td>
                                </tr>
                                {{-- SHARING PROFIT --}}
                                <tr style="background-color: #9c9c9c;">
                                    <td colspan='2' style="color: #000; font-weight: bold;"><b>SHARING PROFIT</b></td>
                                    <td align="right">
                                        <span class="font-weight-boldest">
                                            {{ number_format($prj_sel->sharing_profit, 2) }}
                                        </span>
                                    </td>
                                    <td align="right">
                                        <span class="font-weight-boldest">
                                            {{ number_format(100, 2) }} %
                                        </span>
                                    </td>
                                </tr>
                                {{-- PROFIT LEFT --}}
                                <tr style="background-color: #9c9c9c;">
                                    <td colspan='2' style="color: #000; font-weight: bold;"><b>PROFIT LEFT</b></td>
                                    <td align="right">
                                        <span class="font-weight-boldest">
                                            {{ number_format($profit, 2) }}
                                        </span>
                                    </td>
                                    <td align="right">
                                        <span class="font-weight-boldest">
                                            {{ number_format(100, 2) }} %
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('custom_script')
<script>
    $(document).ready(function(){
        $("select.select2").select2({
            width : "100%"
        })
    })
</script>
@endsection
