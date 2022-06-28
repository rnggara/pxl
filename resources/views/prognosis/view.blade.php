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
    @if(!(session()->has('seckey_pl')) || (session()->has('seckey_pl') < 10))
       @include('ha.needsec.index', ["type" => "pl"])
    @else
        <div class="card card-custom gutter-b">
            <div class="card-header">
                <div class="card-title">
                    <a href="javascript:" class="text-black-50">PL VS ACTUAL - <span class="text-primary">{{$prj->prj_name}}</span></a>
                </div>
                <div class="card-toolbar">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a href="{{route('marketing.project')}}" class="btn btn-success btn-xs"><i class="fa fa-arrow-left"></i></a>
                    </div>
                    <!--end::Button-->
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    {{--SALES BEGIN--}}
                    <div class="col-md-12">
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr class="bg-secondary">
                                <th class="text-center" width="5%">#</th>
                                <th class="text-center" width="40%">SALES</th>
                                <th class="text-center" width="10%">PASS CODE PROJECT</th>
                                <th class="text-center" width="10%">PROGNOSIS</th>
                                <th class="text-center" width="5%">%</th>
                                <th class="text-center" width="5%">Setting</th>
                                <th class="text-center" width="10%">Actual Value</th>
                                <th class="text-center" width="5%">%</th>
                                <th class="text-center" width="10%">UNPAID</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $percentSales = 0;
                            $amountSales  = 0;
                            $actPercentSales  = 0;
                            $actAmountSales  = 0;
                            $unpaid = 0;
                            $iSales = 1;
                            ?>
                            @foreach($prognosis as $list)
                                @if($list->category == "sales")
                                    <tr>
                                        <td align="center">{{$iSales++}}</td>
                                        <td>{{strtoupper($list->subject)}}</td>
                                        <td align="center">{{strtoupper($list->RCTR)}}</td>
                                        <td align="right">{{number_format($list->amount, 2)}}</td>
                                        <td align="right">{{number_format(($list->amount / $totalsales) * 100, 2)}} %</td>
                                        <td align="center">
                                            <button type="button" class="btn btn-xs btn-primary btn-icon" onclick="salesModal('sales','{{$list->id}}')"><i class="fa fa-cog"></i></button>
                                        </td>
                                        <td align="right">
                                            @php $act_value = 0; $paid_value = 0; @endphp
                                            @if(isset($data_value[$list->id]))
                                                @foreach($data_value[$list->id] as $val)
                                                    @php
                                                        /** @var TYPE_NAME $val */
                                                        $act_value += $val['actual_value'];
                                                        $paid_value += $val['paid_value'];
                                                    @endphp
                                                @endforeach
                                            @endif
                                            {{number_format($act_value, 2)}}
                                        </td>
                                        <td align="right">{{number_format(($act_value / $totalsales) * 100, 2)}} %</td>
                                        <td align="right">{{number_format($act_value - $paid_value, 2)}}</td>
                                    </tr>
                                    <?php /** @var TYPE_NAME $list */
                                    /** @var TYPE_NAME $totalsales */
                                    $percentSales += ($list->amount / $totalsales) * 100;
                                    $amountSales += $list->amount;
                                    /** @var TYPE_NAME $act_value */
                                    $actAmountSales += $act_value;
                                    $actPercentSales += ($act_value / $totalsales) * 100;
                                    /** @var TYPE_NAME $paid_value */
                                    $unpaid += $act_value - $paid_value;
                                    ?>
                                @endif
                            @endforeach
                            </tbody>
                            <tr class="bg-warning">
                                <td align="center" colspan="3"><b>TOTAL SALES</b></td>
                                <td align="right">{{number_format($amountSales, 2)}}</td>
                                <td align="right" id="total-sales">{{number_format($percentSales, 2)}} %</td>
                                <td></td>
                                <td align="right" id="act-sales">{{number_format($actAmountSales, 2)}}</td>
                                <td align="right" id="act-percent-sales">{{number_format($actPercentSales, 2)}} %</td>
                                <td align="right">{{number_format($unpaid, 2)}}</td>
                            </tr>
                        </table>

                    </div>
                    {{--SALES END--}}

                    {{--COST BEGIN--}}
                    <div class="col-md-12">
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr class="bg-secondary">
                                <th class="text-center" width="5%">#</th>
                                <th class="text-center" width="40%">COST</th>
                                <th class="text-center" width="10%">PASS CODE PROJECT</th>
                                <th class="text-center" width="10%">PROGNOSIS</th>
                                <th class="text-center" width="5%">%</th>
                                <th class="text-center" width="5%">Setting</th>
                                <th class="text-center" width="10%">Actual Value</th>
                                <th class="text-center" width="5%">%</th>
                                <th class="text-center" width="10%">UNPAID</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $percentCost = 0;
                            $amountCost  = 0;
                            $actPercentCost  = 0;
                            $actAmountCost  = 0;
                            $unpaidCost = 0;
                            $iCost = 1;
                            ?>
                            @foreach($prognosis as $list)
                                @if($list->category == "cost")
                                    <tr>
                                        <td align="center">{{$iCost++}}</td>
                                        <td>
                                            {{strtoupper($list->subject)}} <a href="{{route('marketing.prognosis.excel_export', $list->id)}}" download class="btn btn-xs btn-icon btn-success"><i class="fa fa-file-csv"></i></a>
                                            <button type="button" class="btn btn-xs btn-primary btn-icon" data-toggle="collapse" data-target="#collapse{{$list->id}}"><i class="fa fa-angle-double-down"></i></button>
                                            <div class="accordion accordion-light accordion-light-borderless accordion-svg-toggle" id="accordion{{$list->id}}">
                                                <div class="card">
                                                    <div id="collapse{{$list->id}}" class="collapse" data-parent="#accordion{{$list->id}}">
                                                        <div class="card-body pl-12">
                                                            @if(isset($data_value[$list->id]))
                                                                @foreach($data_value[$list->id] as $val)
                                                                    @if(isset($val['description']) && !empty($val['description']))
                                                                        @foreach($val['description']['value'] as $act_list)
                                                                            <a href="{{isset($act_list['url']) ? $act_list['url'] : "#"}}">{{$act_list['paper']}}</a>,
                                                                        @endforeach
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                No data available
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td align="center">{{strtoupper($list->RCTR)}}</td>
                                        <td align="right">{{number_format($list->amount, 2)}}</td>
                                        <td align="right">{{number_format(($list->amount / $totalsales) * 100, 2)}} %</td>
                                        <td align="center">
                                            <button type="button" class="btn btn-xs btn-primary btn-icon" onclick="salesModal('cost','{{$list->id}}')"><i class="fa fa-cog"></i></button>
                                        </td>
                                        <td align="right">
                                            @php $act_value = 0; $paid_value = 0; @endphp
                                            @if(isset($data_value[$list->id]))
                                                @foreach($data_value[$list->id] as $val)
                                                    @php
                                                        /** @var TYPE_NAME $val */
                                                        $act_value += $val['actual_value'];
                                                        $paid_value += $val['paid_value'];
                                                    @endphp
                                                @endforeach
                                            @endif
                                            {{number_format($act_value, 2)}}
                                        </td>
                                        <td align="right">{{number_format(($act_value / $totalsales) * 100, 2)}} %</td>
                                        <td align="right">{{number_format($act_value - $paid_value, 2)}}</td>
                                    </tr>
                                    <?php /** @var TYPE_NAME $list */
                                    /** @var TYPE_NAME $totalsales */
                                    $percentCost += ($list->amount / $totalsales) * 100;
                                    $amountCost += $list->amount;
                                    /** @var TYPE_NAME $act_value */
                                    $actAmountCost += $act_value;
                                    $actPercentCost += ($act_value / $totalsales) * 100;
                                    /** @var TYPE_NAME $paid_value */
                                    $unpaidCost += $act_value - $paid_value;
                                    ?>
                                @endif
                            @endforeach
                            </tbody>
                            <tr class="bg-warning">
                                <td align="center" colspan="3"><b>TOTAL COST</b></td>
                                <td align="right">{{number_format($amountCost, 2)}}</td>
                                <td align="right">{{number_format($percentCost, 2)}} %</td>
                                <td></td>
                                <td align="right">{{number_format($actAmountCost, 2)}}</td>
                                <td align="right">{{number_format($actPercentCost, 2)}} %</td>
                                <td align="right">{{number_format($unpaidCost, 2)}}</td>
                            </tr>
                        </table>

                    </div>
                    {{--COST END--}}

                    {{--OE BEGIN--}}
                    <div class="col-md-12">
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr class="bg-secondary">
                                <th class="text-center" width="5%">#</th>
                                <th class="text-center" width="40%">OPERATING EXPENSE</th>
                                <th class="text-center" width="10%">PASS CODE PROJECT</th>
                                <th class="text-center" width="10%">PROGNOSIS</th>
                                <th class="text-center" width="5%">%</th>
                                <th class="text-center" width="5%">Setting</th>
                                <th class="text-center" width="10%">Actual Value</th>
                                <th class="text-center" width="5%">%</th>
                                <th class="text-center" width="10%">UNPAID</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $percentOe = 0;
                            $amountOe  = 0;
                            $actPercentOe  = 0;
                            $actAmountOe  = 0;
                            $unpaidOe = 0;
                            $iOe = 1;
                            ?>
                            @foreach($prognosis as $list)
                                @if($list->category == "operating_expenses")
                                    <tr>
                                        <td align="center">{{$iOe++}}</td>
                                        <td>
                                            {{strtoupper($list->subject)}} <a href="{{route('marketing.prognosis.excel_export', $list->id)}}" download class="btn btn-xs btn-icon btn-success"><i class="fa fa-file-csv"></i></a>
                                            <button type="button" class="btn btn-xs btn-primary btn-icon" data-toggle="collapse" data-target="#collapse{{$list->id}}"><i class="fa fa-angle-double-down"></i></button>
                                            <div class="accordion accordion-light accordion-light-borderless accordion-svg-toggle" id="accordion{{$list->id}}">
                                                <div class="card">
                                                    <div id="collapse{{$list->id}}" class="collapse" data-parent="#accordion{{$list->id}}">
                                                        <div class="card-body pl-12">
                                                            @if(isset($data_value[$list->id]))
                                                                @foreach($data_value[$list->id] as $val)
                                                                    @if(isset($val['description']))
                                                                        @foreach($val['description']['value'] as $act_list)
                                                                            <a href="{{isset($act_list['url']) ? $act_list['url'] : "#"}}">{{$act_list['paper']}}</a>,
                                                                        @endforeach
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                No data available
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td align="center">{{strtoupper($list->RCTR)}}</td>
                                        <td align="right">{{number_format($totalsales * ($list->amount/100), 2)}} %</td>
                                        <td align="right">{{number_format($list->amount, 2)}}</td>
                                        <td align="center">
                                            <button type="button" class="btn btn-xs btn-primary btn-icon" onclick="salesModal('oe','{{$list->id}}')"><i class="fa fa-cog"></i></button>
                                        </td>
                                        <td align="right">
                                            @php $act_value = 0; $paid_value = 0; @endphp
                                            @if(isset($data_value[$list->id]))
                                                @foreach($data_value[$list->id] as $val)
                                                    @php
                                                        /** @var TYPE_NAME $val */
                                                        $act_value += $val['actual_value'];
                                                        $paid_value += $val['paid_value'];
                                                    @endphp
                                                @endforeach
                                            @endif
                                            {{number_format($act_value, 2)}}
                                        </td>
                                        <td align="right">{{number_format(($act_value / $totalsales) * 100, 2)}} %</td>
                                        <td align="right">{{number_format($act_value - $paid_value, 2)}}</td>
                                    </tr>
                                    <?php /** @var TYPE_NAME $list */
                                    /** @var TYPE_NAME $totalsales */
                                    $percentOe += $list->amount;
                                    $amountOe += $totalsales * ($list->amount/100);
                                    /** @var TYPE_NAME $act_value */
                                    $actAmountOe += $act_value;
                                    $actPercentOe += ($act_value / $totalsales) * 100;
                                    /** @var TYPE_NAME $paid_value */
                                    $unpaidOe += $act_value - $paid_value;
                                    ?>
                                @endif
                            @endforeach
                            </tbody>
                            <tr class="bg-warning">
                                <td align="center" colspan="3"><b>TOTAL OPERATING EXPENSES</b></td>
                                <td align="right">{{number_format($amountOe, 2)}}</td>
                                <td align="right">{{number_format($percentOe, 2)}} %</td>
                                <td></td>
                                <td align="right">{{number_format($actAmountOe, 2)}}</td>
                                <td align="right">{{number_format($actPercentOe, 2)}} %</td>
                                <td align="right">{{number_format($unpaidOe, 2)}}</td>
                            </tr>
                        </table>

                    </div>
                    {{--OE END--}}
                </div>
                <hr>
                <h3>SUMMARY</h3>
                <hr>
                {{--SUMMARY--}}
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                            <tr>
                                <th class="text-center">Title</th>
                                <th class="text-center">Planned Amount</th>
                                <th class="text-center">Planned Pctg</th>
                                <th class="text-center"></th>
                                <th class="text-center">Actual Amount</th>
                                <th class="text-center">Actual Pctg</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td align="center"><b>TOTAL SALES</b></td>
                                <td align="right"><b>{{number_format($totalsales, 2)}}</b></td>
                                <td align="right"><b>{{number_format($percentSales, 2)}} %</b></td>
                                <td align="right"></td>
                                <td align="right"><b>{{number_format($actAmountSales, 2)}}</b></td>
                                <td align="right"><b>{{number_format($actPercentSales, 2)}} %</b></td>
                            </tr>
                            <tr>
                                <td align="center"><b>TOTAL COST</b></td>
                                <td align="right"><b>{{number_format($amountCost, 2)}}</b></td>
                                <td align="right"><b>{{number_format($percentCost, 2)}} %</b></td>
                                <td align="right"></td>
                                <td align="right"><b>{{number_format($actAmountCost, 2)}}</b></td>
                                <td align="right"><b>{{number_format($actPercentCost, 2)}} %</b></td>
                            </tr>
                            <tr>
                                <td align="center"><b>TOTAL OPERATION EXPENSES</b></td>
                                <td align="right"><b>{{number_format($amountOe, 2)}}</b></td>
                                <td align="right"><b>{{number_format($percentOe, 2)}} %</b></td>
                                <td align="right"></td>
                                <td align="right"><b>{{number_format($actAmountOe, 2)}}</b></td>
                                <td align="right"><b>{{number_format($actPercentOe, 2)}} %</b></td>
                            </tr>
                            <?php
                            $npbt = 0;
                            $npbt_t = 0;
                            $act_npbt = 0;
                            $act_npbt_t = 0;
                            $total_unpaid = 0;

                            /** @var TYPE_NAME $prj */
                            $pro_tax = 0;
                            $val_tax_plan = 0;
                            $val_tax_act = 0;
                            $npat = 0;
                            $npat_t = 0;
                            $npat_act = 0;
                            $npat_act_t = 0;

                            $balance = 0;
                            $balance_act = 0;

                            $npbt = $totalsales - $amountCost - $amountOe;
                            $npbt_t = ($npbt / $totalsales) * 100;
                            $act_npbt = $actAmountSales - $actAmountCost - $actAmountOe;
                            if ($actAmountSales > 0){
                                $act_npbt_t = ($act_npbt / $actAmountSales) * 100;
                            }
                            $total_unpaid = $unpaidCost + $unpaidOe;

                            /** @var TYPE_NAME $prj */
                            $pro_tax = (!empty($prj->tax)) ? $prj->tax : 0;
                            $val_tax_plan = ($pro_tax / 100) * $npbt;
                            $val_tax_act = ($pro_tax / 100) * $act_npbt;
                            $npat = $npbt - $val_tax_plan;
                            $npat_t = ($npat / $totalsales) * 100;
                            $npat_act = $act_npbt - $val_tax_act;
                            if ($actAmountSales > 0){
                                $npat_act_t = ($npat_act / $actAmountSales) * 100;
                            }

                            $balance = $npbt - $prj->sharing_profit + $total_unpaid;
                            $balance_act = $act_npbt - $prj->sharing_profit + $total_unpaid;

                            $b_treasure = (isset($balance_treasure[$prj->bank_account])) ? array_sum($balance_treasure[$prj->bank_account]) : 0;

                            ?>
                            <tr>
                                <td align="center"><b>NET PROFIT BERFORE TAX</b></td>
                                <td align="right"><b>{{number_format($npbt, 2)}}</b></td>
                                <td align="right"><b>{{number_format($npbt_t, 2)}} %</b></td>
                                <td align="right"></td>
                                <td align="right"><b>{{number_format($act_npbt, 2)}}</b></td>
                                <td align="right"><b>{{number_format($act_npbt_t, 2)}} %</b></td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <b>SHARING PROFIT</b><br>
                                    <form action="{{route('marketing.prognosis.project', 'share')}}" method="post">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6 mx-auto">
                                                <div class="row">
                                                    <div class="col-md-9">
                                                        <input type="text" name="share_profit" class="form-control" value="{{(!empty($prj->sharing_profit)) ? $prj->sharing_profit : 0}}">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="hidden" name="id_project" value="{{$prj->id}}">
                                                        <button type="submit" class="btn btn-xs btn-primary">save</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </td>
                                <td align="right"><b>{{number_format((!empty($prj->sharing_profit)) ? $prj->sharing_profit : 0, 2)}}</b></td>
                                <td align="right"></td>
                                <td align="right"></td>
                                <td align="right"><b>{{number_format((!empty($prj->sharing_profit)) ? $prj->sharing_profit : 0, 2)}}</b></td>
                                <td align="right"></td>
                            </tr>
                            <tr>
                                <td align="center"><b>UNPAID</b></td>
                                <td align="right"><b>{{number_format($total_unpaid, 2)}}</b></td>
                                <td align="right"></td>
                                <td align="right"></td>
                                <td align="right"><b>{{number_format($total_unpaid, 2)}}</b></td>
                                <td align="right"></td>
                            </tr>
                            <tr>
                                <td align="center"><b>BALANCE</b></td>
                                <td align="right"><b>{{number_format($balance, 2)}}</b></td>
                                <td align="right"></td>
                                <td align="right"></td>
                                <td align="right"><b>{{number_format($balance_act, 2)}}</b></td>
                                <td align="right"></td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <b>BANK ACCOUNT</b>
                                    <form action="{{route('marketing.prognosis.project', 'bank')}}" method="post">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6 mx-auto">
                                                <div class="row">
                                                    <div class="col-md-9">
                                                        <select name="treasure" class="form-control" id="bank">
                                                            <option value="">Select Bank Account</option>
                                                            @foreach($treasure as $item)
                                                                <option value="{{$item->id}}" {{($item->id == $prj->bank_account) ? "SELECTED" : ""}}>{{$item->source}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="hidden" name="id_project" value="{{$prj->id}}">
                                                        <button type="submit" class="btn btn-xs btn-primary">save</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </td>
                                <td align="right"><b>{{number_format($b_treasure, 2)}}</b></td>
                                <td align="right"></td>
                                <td align="right"></td>
                                <td align="right"><b>{{number_format($b_treasure, 2)}}</b></td>
                                <td align="right"></td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <b>TAX</b>
                                    <form action="{{route('marketing.prognosis.project', 'tax')}}" method="post">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6 mx-auto">
                                                <div class="row">
                                                    <div class="col-md-9">
                                                        <input type="text" name="tax" class="form-control" value="{{(!empty($prj->tax)) ? $prj->tax : 0}}">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="hidden" name="id_project" value="{{$prj->id}}">
                                                        <button type="submit" class="btn btn-xs btn-primary">save</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </td>
                                <td align="right"><b>{{number_format($val_tax_plan, 2)}}</b></td>
                                <td align="right"></td>
                                <td align="right"></td>
                                <td align="right"><b>{{number_format($val_tax_act, 2)}}</b></td>
                                <td align="right"></td>
                            </tr>
                            <tr>
                                <td align="center"><b>NET PROFIT AFTER TAX</b></td>
                                <td align="right"><b>{{number_format($npat, 2)}}</b></td>
                                <td align="right"><b>{{number_format($npat_t, 2)}} %</b></td>
                                <td align="right"></td>
                                <td align="right"><b>{{number_format($npat_act, 2)}}</b></td>
                                <td align="right"><b>{{number_format($npat_act_t, 2)}} %</b></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                {{--RESULT--}}
                <hr>
                <h3>RESULT</h3>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="text-center">PLAN</h3>
                            </div>
                            <div class="col-md-6">
                                <h3 class="text-center">ACTUAL</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 py-10">
                                <div class="alert alert-custom alert-light-primary text-center">
                                    <div class="alert-text">
                                        <h3>PROFIT {{number_format($npat_t, 2)}} % <i class="far fa-smile alert-text font-size-h3-xl"></i></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 py-10">
                                <?php
                                $diff = $npat_act_t - $npat_t;
                                if ($diff <= 0){
                                    $text = "NOT GOOD";
                                    $bgAlert = "alert-light-danger";
                                    $emoji = "far fa-sad-tear";
                                } else {
                                    $text = "GOOD";
                                    $bgAlert = "alert-light-success";
                                    $emoji = "far fa-smile-beam";
                                }
                                ?>
                                <div class="alert alert-custom {{$bgAlert}} text-center">
                                    <div class="alert-text">
                                        <h3>{{$text}} PROFIT {{number_format($diff, 2)}} % FROM PLAN. <i class="{{$emoji}} alert-text font-size-h3-xl"></i></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="settingSales" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content" id="salesModal">

                </div>
            </div>
        </div>
    @endif
@endsection
@section('custom_script')
    <script>
        function salesModal(x, y){
            $.ajax({
                url: "{{route('marketing.prognosis.modal')}}/"+x+"/"+y,
                type: "get",
                cache: false,
                success: function(response){
                    $("#settingSales").modal('show')
                    $("#salesModal").html(" ")
                    $("#salesModal").append(response)
                    $("select.select2").select2({
                        width: "100%",
                        theme: "classic"
                    })
                    if (x === "oe"){
                        $("#percentage_value").change(function(){
                            var totalsales = "{{$totalsales}}"
                            var percent = (parseInt($(this).val()) / parseInt(totalsales)) * 100
                            $("#prog_value").val(percent.toFixed(10))
                        })
                    }
                }
            })
        }

        $(document).ready(function () {

            $("#bank").select2({
                width: "100%"
            })

            $('.display').DataTable({
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            });
        })

    </script>
@endsection
