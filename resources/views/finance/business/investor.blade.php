@extends('layouts.template')

@section('css')
    <style>
        @media print {
            body * {
                visibility: hidden;
                background-color: #fff;
            }

            #print-section, #print-section * {
                visibility: visible;
            }
            #print-section {
                position: absolute;
                left: 0;
                top: 0;
                height: auto;
            }
        }
    </style>
@endsection
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Business - Investor Details</h3>
            </div>
            <div class="card-toolbar">
                <a href="{{route('business.detail', $business->id)}}" class="btn btn-xs btn-icon btn-success"><i class="fa fa-arrow-left"></i></a>
            </div>
        </div>
        <div class="card-body">
            <div class="card card-custom gutter-b bg-secondary">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <table class="table table-borderless">
                                <tr>
                                    <td>Business Project</td>
                                    <td>:</td>
                                    <td><b>{{$business->bank}}</b></td>
                                </tr>
                                <tr>
                                    <td>Partner Name</td>
                                    <td>:</td>
                                    <td><b>{{$business->description}}</b></td>
                                </tr>
                                <tr>
                                    <td>Currency</td>
                                    <td>:</td>
                                    <td>{{$business->currency}}</td>
                                </tr>
                                <tr>
                                    <td>Invested Amount</td>
                                    <td>:</td>
                                    <td><b>{{$business->currency.". ".number_format($business->value, 2)}}</b></td>
                                </tr>
                                <tr>
                                    <td>Invested Date</td>
                                    <td>:</td>
                                    <td>{{date('d F Y', strtotime($business->moneydrop))}}</td>
                                </tr>
                                <tr>
                                    <td>Interest Percentage</td>
                                    <td>:</td>
                                    <td>{{$business->bunga}} % per month</td>
                                </tr>
                                <tr>
                                    <td>Business Duration</td>
                                    <td>:</td>
                                    <td>{{$business->period}} month(s)</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <table class="table table-borderless">
                                <tr>
                                    <td nowrap="nowrap">Payment Start Date</td>
                                    <td>:</td>
                                    <td>{{date('d F Y', strtotime($business->start))}}</td>
                                </tr>
                                <tr>
                                    <td>Proportional</td>
                                    <td>:</td>
                                    <td>{{$business->type}} - {{($business->type == "LUM") ? "LUMPSUM" : "PROPORTIONAL"}}</td>
                                </tr>
                                <tr>
                                    <td>Penalty</td>
                                    <td>:</td>
                                    <td><b>{{$business->currency.". ".number_format($business->own_amount, 2)}}</b></td>
                                </tr>
                                <tr>
                                    <td nowrap="nowrap">Penalty Remarks</td>
                                    <td>:</td>
                                    <td>
                                        <pre>{{$business->own_remarks}}</pre>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
        </div>
    </div>
    <div class="card card-custom gutter-b">
        <div class="card-body">
            <div class="row mt-10" id="summary">
                <div class="col-md-12">
                    <h3>Add new investor</h3>
                    <hr>
                </div>
                <div class="col-md-6 mx-auto">
                    <form action="{{route('business.addInvestor')}}" method="post">
                        @csrf
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Investor Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="investor_name" placeholder="Insert investor name here" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Profit Rate</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="profit_rate" placeholder="%" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Amount</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control number" name="amount" placeholder="Amount" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"></label>
                            <div class="col-md-9">
                                <input type="hidden" name="id_business" value="{{$business->id}}">
                                <button type="submit" class="btn btn-xs btn-success"><i class="fa fa-plus"></i> Add</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
    /** @var TYPE_NAME $business */
    $investOwn = $business->value;
    ?>
    @if(!empty($business->investors) && is_array(json_decode($business->investors)))
        @foreach(json_decode($business->investors) as $key => $investor)
            <?php
            /** @var TYPE_NAME $investor */
            $investOwn -= $investor->amount;
            $additional = 0;
            if (isset($investor->additional)){
                $additional = $investor->additional;
            }
            $invAmount = $investor->amount + $additional;
            ?>
            <div class="card card-custom gutter-b">
                <div class="card-body">
                    <div class="row mt-10">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="col-form-label font-size-h3-xl ">{{$investor->name}} - business details</label>
                                    <button type="button" class="btn btn-xs btn-primary" data-toggle="collapse" data-target="#collapse{{$key}}">See details</button>
                                    <!-- <button type="button" class="btn btn-xs btn-success">Edit</button> -->
                                    <a href="{{route('business.deleteInvestor')}}?b={{$business->id}}&i={{$key}}" type="button" class="btn btn-xs btn-danger">Delete</a>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-right">
                                        <div class="btn-group">
                                            <button type="button" data-toggle="modal" data-target="#modalField-{{$key}}" class="btn btn-sm btn-icon btn-secondary"><i class="fa fa-cog"></i></button>
                                            <button type="button" onclick="printDiv('print-section-{{$key}}')" class="btn btn-sm btn-icon btn-primary"><i class="fa fa-print"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="">Invested Amount : {{$business->currency}} {{number_format($invAmount, 2)}}</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <form action="{{route('business.updateAmount')}}" class="row" method="post">
                                        @csrf
                                        <div class="col-md-6 row mx-auto">
                                            <div class="col-md-3 text-right">
                                                <label for="" class="col-form-label">Invested Amount</label>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control number" name="amount" value="{{$investor->amount}}">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="hidden" name="business" value="{{$business->id}}">
                                                <input type="hidden" name="index" value="{{$key}}">
                                                <input type="hidden" name="type" value="investor">
                                                <button type="submit" class="btn btn-xs btn-success"><i class="fa fa-check"></i> Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-6">
                                    <form action="{{route('business.updateRate')}}" class="row" method="post">
                                        @csrf
                                        <div class="col-md-6 row mx-auto">
                                            <div class="col-md-3 text-right">
                                                <label for="" class="col-form-label">Profit Rate</label>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" name="profit_rate" value="{{$investor->percentage}}">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="hidden" name="business" value="{{$business->id}}">
                                                <input type="hidden" name="index" value="{{$key}}">
                                                <input type="hidden" name="type" value="investor">
                                                <button type="submit" class="btn btn-xs btn-success"><i class="fa fa-check"></i> Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <hr>
                            <div class="row mt-10">
                                <div class="col-md-12">
                                    <form action="{{route('business.addInvesment')}}" method="post">
                                        @csrf
                                        <table class="table border">
                                            <tr>
                                                <td>
                                                    <label for="" class="col-form-label">Add Invesment&nbsp;<i class="fa fa-plus font-size-sm text-dark"></i></label>
                                                </td>
                                                <td>
                                                    <select name="currency" class="form-control select2" required>
                                                        @foreach(json_decode($list_currency) as $keyCurrency => $valueCurrency)
                                                            <option value="{{$keyCurrency}}" {{($keyCurrency == "IDR") ? "selected" : ""}}>{{strtoupper($keyCurrency."-".$valueCurrency)}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <label for="" class="col-form-label">Amount</label>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control number" name="amount" placeholder="Insert invesment amount">
                                                </td>
                                                <td>
                                                    <label for="" class="col-form-label">Rate IDR</label>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="rate" placeholder="Insert exchange rate">
                                                </td>
                                                <td>
                                                    <input type="hidden" name="business" value="{{$business->id}}">
                                                    <input type="hidden" name="index" value="{{$key}}">
                                                    <input type="hidden" name="type" value="investor">
                                                    <button class="btn btn-xs btn-success" type="submit">Add</button>
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                            </div>
                            <div class="row mt-10">
                                <div class="col-md-12">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th class="text-center">No.</th>
                                            <th>Amount</th>
                                            <th>Exchange Rate</th>
                                            <th>Sub Total (IDR)</th>
                                            <th></th>
                                        </tr>
                                        </thead>

                                        @if(isset($investor->details))
                                            <?php
                                            $inv_total = 0;
                                            ?>
                                            <tbody>
                                            @foreach($investor->details as $keyDetail => $valueDetail)
                                                <tr>
                                                    <td align="center">{{$keyDetail + 1}}</td>
                                                    <td>{{$valueDetail->currency." ".number_format($valueDetail->amount, 2)}}</td>
                                                    <td>{{number_format($investor->percentage, 2)}}</td>
                                                    <td align="right">IDR {{number_format($valueDetail->IDR, 2)}}</td>
                                                    <td>
                                                        <a href="{{route('business.deleteInvesment')}}?b={{$business->id}}&t=i&i={{$key}}&p={{$keyDetail}}" class="btn btn-xs btn-icon btn-danger"><i class="fa fa-trash"></i></a>
                                                    </td>
                                                    <?php
                                                    /** @var TYPE_NAME $valueDetail */
                                                    $inv_total += $valueDetail->IDR;
                                                    ?>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <td colspan="2">Invested Amount : {{number_format($invAmount, 2)}}</td>
                                                <td colspan="2" align="right">Total : IDR {{number_format($inv_total, 2)}}</td>
                                                <td>
                                                    Balance : IDR {{number_format($invAmount - $inv_total, 2)}}
                                                    <form action="{{route('business.updateText')}}" method="post">
                                                        @csrf
                                                        <textarea name="unusedText" class="form-control" id="" cols="30" rows="5">{{(isset($investor->unusedText) ? $investor->unusedText : "")}}</textarea>
                                                        <input type="hidden" name="business" value="{{$business->id}}">
                                                        <input type="hidden" name="index" value="{{$key}}">
                                                        <button type="submit" class="btn btn-xs btn-success">Save</button>
                                                    </form>
                                                </td>
                                            </tr>
                                            </tfoot>
                                        @else
                                            <tr>
                                                <td colspan="5" align="center">No data available</td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                                <hr>
                                <div class="col-md-12">
                                    <table class="table">
                                        <tr>
                                            <td bgcolor="#5dfdcb">{{$business->description}}</td>
                                            <td>{{$investor->name}}</td>
                                            <td>Profit</td>
                                            <td>Administration</td>
                                        </tr>
                                        <tr>
                                            <?php
                                            /** @var TYPE_NAME $investor */
                                            /** @var TYPE_NAME $business */
                                            if(isset($investor->additional)){
                                                $v_a = floor(($investor->amount * $business->bunga / 100) * $investor->newKey);
                                                $v_a += floor($invAmount + ($invAmount * $business->bunga / 100) * ($business->period - $investor->newKey));
                                                $v_c = ($investor->amount * (str_replace("%", "", $investor->percentage)) / 100) * $investor->newKey;
                                                $v_c += ($invAmount * (str_replace("%", "", $investor->percentage)) / 100) * ($business->period - $investor->newKey);
                                                $v_b = $invAmount + $v_c;
                                            } else {
                                                $v_a = floor($invAmount + ($invAmount * $business->bunga / 100) * $business->period);
                                                $v_b = floor($invAmount + ($invAmount * (str_replace("%", "", $investor->percentage)) / 100) * $business->period);
                                                $v_c = ($invAmount * (str_replace("%", "", $investor->percentage)) / 100) * ($business->period);
                                            }
                                            ?>
                                            <td bgcolor="#5dfdcb">{{$business->currency." ".number_format($v_a, 2)}}</td>
                                            <td>{{$business->currency." ".number_format($v_b, 2)}}</td>
                                            <td>{{$business->currency." ".number_format($v_c, 2)}}</td>
                                            <td>{{$business->currency." ".number_format($v_a - $v_b, 2)}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="row collapse" id="print-section-{{$key}}">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card bg-secondary">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <table class="table table-borderless">
                                                                <tr>
                                                                    <td>Investor</td>
                                                                    <td>: {{$investor->name}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Invested Amount</td>
                                                                    <td>: {{number_format($invAmount, 2)}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Project</td>
                                                                    <td>: {{$business->description}}</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <label for="">Investments as follows:</label>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <table class="table table-dark">
                                                                <thead>
                                                                <tr>
                                                                    <th class="text-center">No.</th>
                                                                    <th>Amount</th>
                                                                    <th>Exchange Rate</th>
                                                                    <th>Final Amount</th>
                                                                </tr>
                                                                </thead>
                                                                @if(isset($investor->details))
                                                                    <?php
                                                                    $inv_total = 0;
                                                                    ?>
                                                                    <tbody>
                                                                    @foreach($investor->details as $keyDetail => $valueDetail)
                                                                        <tr>
                                                                            <td align="center">{{$keyDetail + 1}}</td>
                                                                            <td>{{$valueDetail->currency." ".number_format($valueDetail->amount, 2)}}</td>
                                                                            <td>{{number_format($investor->percentage, 2)}}</td>
                                                                            <td align="right">IDR {{number_format($valueDetail->IDR, 2)}}</td>
                                                                            <?php
                                                                            /** @var TYPE_NAME $valueDetail */
                                                                            $inv_total += $valueDetail->IDR;
                                                                            ?>
                                                                        </tr>
                                                                    @endforeach
                                                                    </tbody>
                                                                    <tfoot>
                                                                    <tr>
                                                                        <td colspan="3">Invested Amount : {{number_format($investor->amount, 2)}}</td>
                                                                        <td>
                                                                            Balance : IDR {{number_format($investor->amount - $inv_total, 2)}}
                                                                            <br>
                                                                            {{(isset($investor->unusedText) ? $investor->unusedText : "")}}
                                                                        </td>
                                                                    </tr>
                                                                    </tfoot>
                                                                @else
                                                                    <tr>
                                                                        <td colspan="5" align="center">No data available</td>
                                                                    </tr>
                                                                @endif
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <table class="table table-dark table-hover display" id="table-print-{{$key}}">
                                                                <thead>
                                                                <tr>
                                                                    <th class="text-center">Month#</th>
                                                                    <th class="text-center">Payment Date</th>
                                                                    <th class="text-center">Profit Rate (%)</th>
                                                                    <th class="text-center">Balance</th>
                                                                    <th class="text-center">Installment</th>
                                                                    <th class="text-center">Profit</th>
                                                                    <th class="text-center">Total Amount</th>
                                                                    <th class="text-center">Status</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php
                                                                $balanceOwn = $investor->amount;
                                                                $cicilanOwn = floor($investor->amount / $business->period);
                                                                $bungaOwn = floor($investor->amount * (str_replace("%", "", $investor->percentage)) / 100);
                                                                $nCicilan = 0;
                                                                $nProfit = 0;
                                                                $nTotal = 0;
                                                                ?>
                                                                @foreach($details as $keyDetail => $detail)
                                                                    <?php
                                                                    /** @var TYPE_NAME $keyDetail */
                                                                    /** @var TYPE_NAME $details */
                                                                    if (isset($investor->newKey) && $investor->newKey == $keyDetail){
                                                                        $balanceOwn += $investor->additional;
                                                                        $cicilanOwn = floor($balanceOwn / ($business->period - $investor->newKey));
                                                                        $bungaOwn = floor($balanceOwn * (str_replace("%", "", $investor->percentage)) / 100);
                                                                    }
                                                                    if ($keyDetail == count($details) - 1){
                                                                        $cicilNow = $balanceOwn;
                                                                    } else {
                                                                        $cicilNow = $cicilanOwn;
                                                                    }
                                                                    ?>
                                                                    <tr>
                                                                        <td align="center">{{$keyDetail + 1}}</td>
                                                                        <td align="center">{{date('d F Y', strtotime($detail->plan_date))}}</td>
                                                                        <td align="right">{{number_format((str_replace("%", "", $investor->percentage)), 2)}}</td>
                                                                        <td align="right">{{number_format($balanceOwn, 2)}}</td>
                                                                        <td align="right">{{number_format($cicilNow, 2)}}</td>
                                                                        <td align="right">{{number_format($bungaOwn, 2)}}</td>
                                                                        <td align="right">{{number_format($cicilanOwn + $bungaOwn, 2)}}</td>
                                                                        <td align="center">
                                                                            @if(!isset($investor->payments[$keyDetail]))
                                                                                waiting
                                                                            @else
                                                                                {{date('d F Y', strtotime($investor->payments[$keyDetail]))}}
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                    $balanceOwn -= $cicilNow;
                                                                    $nCicilan += $cicilNow;
                                                                    $nProfit += $bungaOwn;
                                                                    $nTotal += $cicilNow + $bungaOwn;
                                                                    ?>
                                                                @endforeach
                                                                </tbody>
                                                                <tfoot>
                                                                <tr>
                                                                    <td colspan="3" align="right">Total</td>
                                                                    <td align="right">{{number_format($balanceOwn, 2)}}</td>
                                                                    <td align="right">{{number_format($nCicilan, 2)}}</td>
                                                                    <td align="right">{{number_format($nProfit, 2)}}</td>
                                                                    <td align="right">{{number_format($nTotal, 2)}}</td>
                                                                    <td></td>
                                                                </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="accordion accordion-toggle-arrow col-md-12" id="accordion{{$key}}">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card bg-secondary">
                                                    <div id="collapse{{$key}}" class="collapse" data-parent="#accordion{{$key}}">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <table class="table table-borderless">
                                                                        <tr>
                                                                            <td>Investor</td>
                                                                            <td>: {{$investor->name}}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Invested Amount</td>
                                                                            <td>: {{number_format($invAmount, 2)}}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Project</td>
                                                                            <td>: {{$business->description}}</td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <label for="">Investments as follows:</label>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <table class="table table-dark">
                                                                        <thead>
                                                                        <tr>
                                                                            <th class="text-center">No.</th>
                                                                            <th>Amount</th>
                                                                            <th>Exchange Rate</th>
                                                                            <th>Final Amount</th>
                                                                        </tr>
                                                                        </thead>
                                                                        @if(isset($investor->details))
                                                                            <?php
                                                                            $inv_total = 0;
                                                                            ?>
                                                                            <tbody>
                                                                            @foreach($investor->details as $keyDetail => $valueDetail)
                                                                                <tr>
                                                                                    <td align="center">{{$keyDetail + 1}}</td>
                                                                                    <td>{{$valueDetail->currency." ".number_format($valueDetail->amount, 2)}}</td>
                                                                                    <td>{{number_format($investor->percentage, 2)}}</td>
                                                                                    <td align="right">IDR {{number_format($valueDetail->IDR, 2)}}</td>
                                                                                    <?php
                                                                                    /** @var TYPE_NAME $valueDetail */
                                                                                    $inv_total += $valueDetail->IDR;
                                                                                    ?>
                                                                                </tr>
                                                                            @endforeach
                                                                            </tbody>
                                                                            <tfoot>
                                                                            <tr>
                                                                                <td colspan="3">Invested Amount : {{number_format($invAmount, 2)}}</td>
                                                                                <td>
                                                                                    Balance : IDR {{number_format($investor->amount - $inv_total, 2)}}
                                                                                    <br>
                                                                                    {{(isset($investor->unusedText) ? $investor->unusedText : "")}}
                                                                                </td>
                                                                            </tr>
                                                                            </tfoot>
                                                                        @else
                                                                            <tr>
                                                                                <td colspan="5" align="center">No data available</td>
                                                                            </tr>
                                                                        @endif
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <table class="table table-dark table-hover">
                                                                        <thead>
                                                                        <tr>
                                                                            <th class="text-center">Month#</th>
                                                                            <th class="text-center">Payment Date</th>
                                                                            <th class="text-center">Profit Rate (%)</th>
                                                                            <th class="text-center">Balance</th>
                                                                            <th class="text-center">Installment</th>
                                                                            <th class="text-center">Profit</th>
                                                                            <th class="text-center">Total Amount</th>
                                                                            <th class="text-center">Status</th>
                                                                            <th></th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?php
                                                                        $balanceOwn = $investor->amount;
                                                                        $cicilanOwn = floor($investor->amount / $business->period);
                                                                        $bungaOwn = floor($investor->amount * (str_replace("%", "", $investor->percentage)) / 100);
                                                                        $nCicilan = 0;
                                                                        $nProfit = 0;
                                                                        $nTotal = 0;
                                                                        ?>
                                                                        @foreach($details as $keyDetail => $detail)
                                                                            <?php
                                                                            /** @var TYPE_NAME $keyDetail */
                                                                            /** @var TYPE_NAME $details */
                                                                            if (isset($investor->newKey) && $investor->newKey == $keyDetail){
                                                                                $balanceOwn += $investor->additional;
                                                                                $cicilanOwn = floor($balanceOwn / ($business->period - $investor->newKey));
                                                                                $bungaOwn = floor(($investor->amount + $investor->additional) * (str_replace("%", "", $investor->percentage)) / 100);
                                                                            }
                                                                            if ($keyDetail == count($details) - 1){
                                                                                $cicilNow = $balanceOwn;
                                                                            } else {
                                                                                $cicilNow = $cicilanOwn;
                                                                            }
                                                                            ?>
                                                                            <tr>
                                                                                <td align="center">{{$keyDetail + 1}}</td>
                                                                                <td align="center">{{date('d F Y', strtotime($detail->plan_date))}}</td>
                                                                                <td align="right">{{number_format((str_replace("%", "", $investor->percentage)), 2)}}</td>
                                                                                <td align="right">{{number_format($balanceOwn, 2)}}</td>
                                                                                <td align="right">{{number_format($cicilNow, 2)}}</td>
                                                                                <td align="right">{{number_format($bungaOwn, 2)}}</td>
                                                                                <td align="right">{{number_format($cicilanOwn + $bungaOwn, 2)}}</td>
                                                                                <td align="center">
                                                                                    @if(!isset($investor->payments[$keyDetail]))
                                                                                        <a href="{{route('business.investorPay')}}?b={{$business->id}}&t=i&i={{$key}}&p={{$keyDetail}}" class="btn btn-xs btn-success">pay</a>
                                                                                    @else
                                                                                        {{date('d F Y', strtotime($investor->payments[$keyDetail]))}}
                                                                                    @endif
                                                                                </td>
                                                                                <?php
                                                                                $balanceOwn -= $cicilNow;
                                                                                $nCicilan += $cicilNow;
                                                                                $nProfit += $bungaOwn;
                                                                                $nTotal += $cicilNow + $bungaOwn;
                                                                                ?>
                                                                                <td align="center">
                                                                                    @if (!isset($investor->payments[$keyDetail]))
                                                                                    <button data-toggle="modal" data-target="#modalTransfer" onclick="this_investor({{$key}}, {{$keyDetail}})" type="button" class="btn btn-xs btn-success">transfer</button>
                                                                                    @endif
                                                                                    {{-- @if(isset($investor->newKey) && $investor->newKey != $keyDetail) --}}

                                                                                    {{-- @endif --}}
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                        </tbody>
                                                                        <tfoot>
                                                                        <tr>
                                                                            <td colspan="3" align="right">Total</td>
                                                                            <td align="right">{{number_format($balanceOwn, 2)}}</td>
                                                                            <td align="right">{{number_format($nCicilan, 2)}}</td>
                                                                            <td align="right">{{number_format($nProfit, 2)}}</td>
                                                                            <td align="right">{{number_format($nTotal, 2)}}</td>
                                                                            <td></td>
                                                                        </tr>
                                                                        </tfoot>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="modalField-{{$key}}" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Payment Confirmation</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <i aria-hidden="true" class="ki ki-close"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label text-right">Visible Columns</label>
                                        <div class="col-md-8 col-form-label">
                                            @foreach($fieldInvestor as $keyFields => $field)
                                                <div class="checkbox-inline">
                                                    <label class="checkbox checkbox-outline checkbox-success">
                                                        <input type="checkbox" checked value="{{$keyFields}}" class="field-{{$key}}" name="Checkboxes15"/>
                                                        <span></span>
                                                        {{ucwords(str_replace("_", " ", $field))}}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btn-close-modal-{{$key}}" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                            <button type="button" onclick="saveField('{{$key}}')"name="submit" class="btn btn-xs btn-primary font-weight-bold">
                                <i class="fa fa-check"></i>
                                Save</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    <div class="card card-custom gutter-b">
        <div class="card-body"><div class="row mt-10">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="col-form-label font-size-h3-xl ">{{\Session::get('company_tag')}} calculation</label>
                            <button type="button" class="btn btn-xs btn-primary" data-toggle="collapse" data-target="#collapse{{\Session::get('company_tag')}}">See details</button>
                        </div>
                        <div class="col-md-6">
                            <div class="text-right">
                                <div class="btn-group">
                                    <button type="button" data-toggle="modal" data-target="#modalField" class="btn btn-sm btn-icon btn-secondary"><i class="fa fa-cog"></i></button>
                                    <button type="button" onclick="printDiv('print-company')" class="btn btn-sm btn-icon btn-primary"><i class="fa fa-print"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">Invested Amount : {{number_format($investOwn, 2)}}</label>
                        </div>
                    </div>
                    <form action="{{route('business.updateRate')}}" class="row" method="post">
                        @csrf
                        <div class="col-md-6 row mx-auto">
                            <div class="col-md-3 text-right">
                                <label for="" class="col-form-label">Profit Rate</label>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="profit_rate" value="{{($business->own_percent == null) ? 0 : $business->own_percent}}">
                            </div>
                            <div class="col-md-3">
                                <input type="hidden" name="business" value="{{$business->id}}">
                                <input type="hidden" name="type" value="company">
                                <button type="submit" class="btn btn-xs btn-success"><i class="fa fa-check"></i> Save</button>
                            </div>
                        </div>
                    </form>
                    <div class="row mt-10">
                        <div class="col-md-12">
                            <form action="{{route('business.addInvesment')}}" method="post">
                                @csrf
                                <table class="table border">
                                    <tr>
                                        <td>
                                            <label for="" class="col-form-label">Add Invesment&nbsp;<i class="fa fa-plus font-size-sm text-dark"></i></label>
                                        </td>
                                        <td>
                                            <select name="currency" class="form-control select2" required>
                                                @foreach(json_decode($list_currency) as $keyCurrency => $valueCurrency)
                                                    <option value="{{$keyCurrency}}" {{($keyCurrency == "IDR") ? "selected" : ""}}>{{strtoupper($keyCurrency."-".$valueCurrency)}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <label for="" class="col-form-label">Amount</label>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control number" name="amount" placeholder="Insert invesment amount">
                                        </td>
                                        <td>
                                            <label for="" class="col-form-label">Rate IDR</label>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="rate" placeholder="Insert exchange rate">
                                        </td>
                                        <td>
                                            <input type="hidden" name="business" value="{{$business->id}}">
                                            <input type="hidden" name="type" value="company">
                                            <button class="btn btn-xs btn-success" type="submit">Add</button>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>
                    <div class="row mt-10">
                        <div class="col-md-12">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th>Amount</th>
                                    <th>Exchange Rate</th>
                                    <th>Sub Total (IDR)</th>
                                    <th></th>
                                </tr>
                                </thead>
                                @if(empty($business->company))
                                    <tr>
                                        <td colspan="5" align="center">No data available</td>
                                    </tr>
                                @else
                                    <tbody>
                                    @foreach(json_decode($business->company) as $keyCompany => $valueCompany)
                                        <tr>
                                            <td align="center">{{$keyCompany + 1}}</td>
                                            <td>{{$valueCompany->currency." ".number_format($valueCompany->amount, 2)}}</td>
                                            <td>{{number_format($business->own_percent, 2)}}</td>
                                            <td align="right">IDR {{number_format($valueCompany->IDR, 2)}}</td>
                                            <td align="center">
                                                <a href="{{route('business.deleteInvesment')}}?b={{$business->id}}&t=c&p={{$keyCompany}}" class="btn btn-xs btn-icon btn-danger"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td colspan="2">
                                            Invesment Amount : {{$business->currency}} {{number_format($investOwn, 2)}}
                                        </td>
                                        <td align="right">Total</td>
                                        <td align="right">
                                            {{$business->currency}} {{number_format($investOwn, 2)}}
                                        </td>
                                        <td>
                                            Balance : {{$business->currency}} {{number_format(0, 2)}}
                                        </td>
                                    </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                        <hr>
                        <div class="col-md-12">
                            <table class="table">
                                <tr>
                                    <td bgcolor="#5dfdcb">{{$business->description}}</td>
                                    <td>{{\Session::get('company_tag')}}</td>
                                    <td>Profit</td>
                                    <td>Administration</td>
                                </tr>
                                <tr>
                                    <?php
                                    /** @var TYPE_NAME $investor */
                                    /** @var TYPE_NAME $business */
                                    $v_a = floor($investOwn + ($investOwn * $business->bunga / 100) * $business->period);
                                    $v_b = floor($investOwn + ($investOwn * $business->own_percent / 100) * $business->period);
                                    ?>
                                    <td bgcolor="#5dfdcb">{{$business->currency." ".number_format($v_a, 2)}}</td>
                                    <td>{{$business->currency." ".number_format($v_b, 2)}}</td>
                                    <td>{{$business->currency." ".number_format($v_b - $investOwn, 2)}}</td>
                                    <td>{{$business->currency." ".number_format($v_a - $v_b, 2)}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-10">
                        <div class="accordion accordion-toggle-arrow col-md-12" id="accordion{{\Session::get('company_tag')}}">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card bg-secondary">
                                        <div id="collapse{{\Session::get('company_tag')}}" class="collapse" data-parent="#accordion{{\Session::get('company_tag')}}">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <table class="table table-borderless">
                                                            <tr>
                                                                <td>Investor</td>
                                                                <td>: {{\Session::get('company_tag')}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Invested Amount</td>
                                                                <td>: {{number_format($investOwn, 2)}}</td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <table class="table table-dark">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center">No.</th>
                                                                <th>Amount</th>
                                                                <th>Exchange Rate</th>
                                                                <th>Sub Total (IDR)</th>
                                                            </tr>
                                                            </thead>
                                                            @if(empty($business->company))
                                                                <tr>
                                                                    <td colspan="5" align="center">No data available</td>
                                                                </tr>
                                                            @else
                                                                <tbody>
                                                                @foreach(json_decode($business->company) as $keyCompany => $valueCompany)
                                                                    <tr>
                                                                        <td align="center">{{$keyCompany + 1}}</td>
                                                                        <td>{{$valueCompany->currency." ".number_format($valueCompany->amount, 2)}}</td>
                                                                        <td>{{number_format($business->own_percent, 2)}}</td>
                                                                        <td align="right">IDR {{number_format($valueCompany->IDR, 2)}}</td>
                                                                    </tr>
                                                                @endforeach
                                                                </tbody>
                                                            @endif
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table class="table table-dark table-hover">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center">Month#</th>
                                                                <th class="text-center">Payment Date</th>
                                                                <th class="text-center">Profit Rate (%)</th>
                                                                <th class="text-center">Balance</th>
                                                                <th class="text-center">Installment</th>
                                                                <th class="text-center">Profit</th>
                                                                <th class="text-center">Total Amount</th>
                                                                <th class="text-center">Status</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                            $balanceOwn = $investOwn;
                                                            $cicilanOwn = floor($investOwn / $business->period);
                                                            $bungaOwn = floor($investOwn * $business->own_percent / 100);
                                                            $nCicilan = 0;
                                                            $nProfit = 0;
                                                            $nTotal = 0;
                                                            $archive = json_decode($business->archive_by);
                                                            ?>
                                                            @foreach($details as $keyDetail => $detail)
                                                                <?php
                                                                if ($keyDetail == count($details) - 1){
                                                                    $cicilNow = $balanceOwn;
                                                                } else {
                                                                    $cicilNow = $cicilanOwn;
                                                                }
                                                                ?>
                                                                <tr>
                                                                    <td align="center">{{$keyDetail + 1}}</td>
                                                                    <td align="center">{{date('d F Y', strtotime($detail->plan_date))}}</td>
                                                                    <td align="right">{{number_format($business->own_percent, 2)}}</td>
                                                                    <td align="right">{{number_format($balanceOwn, 2)}}</td>
                                                                    <td align="right">{{number_format($cicilNow, 2)}}</td>
                                                                    <td align="right">{{number_format($bungaOwn, 2)}}</td>
                                                                    <td align="right">{{number_format($cicilanOwn + $bungaOwn, 2)}}</td>
                                                                    <td align="center">
                                                                        @if(isset($archive[$keyDetail]))
                                                                            {{$archive[$keyDetail]}}
                                                                        @else
                                                                            <a href="{{route('business.investorPay')}}?b={{$business->id}}&t=c&i={{$keyDetail}}" class="btn btn-xs btn-success">pay</a>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                                $balanceOwn -= $cicilNow;
                                                                $nCicilan += $cicilNow;
                                                                $nProfit += $bungaOwn;
                                                                $nTotal += $cicilNow + $bungaOwn;
                                                                ?>
                                                            @endforeach
                                                            </tbody>
                                                            <tfoot>
                                                            <tr>
                                                                <td colspan="3" align="right">Total</td>
                                                                <td align="right">{{number_format($balanceOwn, 2)}}</td>
                                                                <td align="right">{{number_format($nCicilan, 2)}}</td>
                                                                <td align="right">{{number_format($nProfit, 2)}}</td>
                                                                <td align="right">{{number_format($nTotal, 2)}}</td>
                                                                <td></td>
                                                            </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-10 collapse" id="print-company">
                        <div class="col-md-12">
                            <div class="card bg-secondary">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td>Investor</td>
                                                    <td>: {{\Session::get('company_tag')}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Invested Amount</td>
                                                    <td>: {{number_format($investOwn, 2)}}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-dark">
                                                <thead>
                                                <tr>
                                                    <th class="text-center">No.</th>
                                                    <th>Amount</th>
                                                    <th>Exchange Rate</th>
                                                    <th>Sub Total (IDR)</th>
                                                </tr>
                                                </thead>
                                                @if(empty($business->company))
                                                    <tr>
                                                        <td colspan="5" align="center">No data available</td>
                                                    </tr>
                                                @else
                                                    <tbody>
                                                    @foreach(json_decode($business->company) as $keyCompany => $valueCompany)
                                                        <tr>
                                                            <td align="center">{{$keyCompany + 1}}</td>
                                                            <td>{{$valueCompany->currency." ".number_format($valueCompany->amount, 2)}}</td>
                                                            <td>{{number_format($business->own_percent, 2)}}</td>
                                                            <td align="right">IDR {{number_format($valueCompany->IDR, 2)}}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                @endif
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-dark table-hover display" id="table-print">
                                                <thead>
                                                <tr>
                                                    <th class="text-center">Month#</th>
                                                    <th class="text-center">Payment Date</th>
                                                    <th class="text-center">Profit Rate (%)</th>
                                                    <th class="text-center">Balance</th>
                                                    <th class="text-center">Installment</th>
                                                    <th class="text-center">Profit</th>
                                                    <th class="text-center">Total Amount</th>
                                                    <th class="text-center">Status</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $balanceOwn = $investOwn;
                                                $cicilanOwn = floor($investOwn / $business->period);
                                                $bungaOwn = floor($investOwn * $business->own_percent / 100);
                                                $nCicilan = 0;
                                                $nProfit = 0;
                                                $nTotal = 0;
                                                $archive = json_decode($business->archive_by);
                                                ?>
                                                @foreach($details as $keyDetail => $detail)
                                                    <?php
                                                    if ($keyDetail == count($details) - 1){
                                                        $cicilNow = $balanceOwn;
                                                    } else {
                                                        $cicilNow = $cicilanOwn;
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td align="center">{{$keyDetail + 1}}</td>
                                                        <td align="center">{{date('d F Y', strtotime($detail->plan_date))}}</td>
                                                        <td align="right">{{number_format($business->own_percent, 2)}}</td>
                                                        <td align="right">{{number_format($balanceOwn, 2)}}</td>
                                                        <td align="right">{{number_format($cicilNow, 2)}}</td>
                                                        <td align="right">{{number_format($bungaOwn, 2)}}</td>
                                                        <td align="right">{{number_format($cicilanOwn + $bungaOwn, 2)}}</td>
                                                        <td align="center">
                                                            @if(isset($archive[$keyDetail]))
                                                                {{$archive[$keyDetail]}}
                                                            @else
                                                                waiting
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    $balanceOwn -= $cicilNow;
                                                    $nCicilan += $cicilNow;
                                                    $nProfit += $bungaOwn;
                                                    $nTotal += $cicilNow + $bungaOwn;
                                                    ?>
                                                @endforeach
                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <td colspan="3" align="right">Total</td>
                                                    <td align="right">{{number_format($balanceOwn, 2)}}</td>
                                                    <td align="right">{{number_format($nCicilan, 2)}}</td>
                                                    <td align="right">{{number_format($nProfit, 2)}}</td>
                                                    <td align="right">{{number_format($nTotal, 2)}}</td>
                                                    <td></td>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div></div>
    </div>
    <div class="modal fade" id="modalField" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Payment Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label text-right">Visible Columns</label>
                                <div class="col-md-8 col-form-label">
                                    @foreach($fieldInvestor as $key => $field)
                                        <div class="checkbox-inline">
                                            <label class="checkbox checkbox-outline checkbox-success">
                                                <input type="checkbox" checked value="{{$key}}" class="field" name="Checkboxes15"/>
                                                <span></span>
                                                {{ucwords(str_replace("_", " ", $field))}}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="target-table">
                    <button type="button" id="btn-close-modal" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                    <button type="button" id="btn-set-field" name="submit" class="btn btn-xs btn-primary font-weight-bold">
                        <i class="fa fa-check"></i>
                        Save</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalTransfer" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Transfer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('business.transfer')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group row">
                                    <label class="col-form-label col-3">To</label>
                                    <div class="col-9">
                                        <select name="to" class="form-control" id="sel-to" required>
                                            <option value="">Choose one</option>
                                            @if(!empty($business->investors))
                                                @foreach(json_decode($business->investors) as $i => $sel)
                                                    <option value="{{$i}}" data-amount="{{$sel->amount}}">{{$sel->name}}</option>
                                                @endforeach
                                            @endif
                                            <option value="new">New Investor</option>
                                        </select>
                                    </div>
                                </div>
                                <div id="new-investor">
                                    <div class="form-group row">
                                        <label class="col-form-label col-3">Investor Name</label>
                                        <div class="col-9">
                                            <input type="text" class="form-control" name="inv_name">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-3">Profit Rate</label>
                                        <div class="col-9">
                                            <input type="text" class="form-control number" name="profit_rate">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-3">Amount</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control number" id="am" name="amount" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="key_investor" id="key-investor">
                        <input type="hidden" name="key_detail" id="key-detail">
                        <input type="hidden" name="id_business" value="{{$business->id}}">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Transfer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="print-section" class="collapse">

    </div>
@endsection

@section('custom_script')
    <script src="{{asset('theme/assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js?v=7.0.5')}}"></script>
    <script src="{{ asset('assets/jquery-number/jquery.number.js') }}"></script>
    <script>
        function submit_edit_form(){
            Swal.fire({
                title: "Update",
                text: "Are you sure you want to update this data?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Submit",
                cancelButtonText: "Cancel",
                reverseButtons: true,
            }).then(function(result){
                if(result.value){
                    $("#btn-submit-edit").click()
                }
            })
        }
        function button_delete(x){
            Swal.fire({
                title: "Reject",
                text: "Are you sure you want to delete?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Delete",
                cancelButtonText: "Cancel",
                reverseButtons: true,
            }).then(function(result){
                if(result.value){
                    $.ajax({
                        url: "{{URL::route('loan.delete')}}",
                        type: "POST",
                        dataType: "json",
                        data: {
                            '_token' : '{{csrf_token()}}',
                            'val' : x
                        },
                        cache: false,
                        success: function(response){
                            if (response.error == 0) {
                                location.reload()
                            } else {
                                Swal.fire({
                                    title: "Error Occured",
                                    icon: "error"
                                })
                            }
                        }
                    })
                }
            })
        }
        function button_edit(x){
            $.ajax({
                url: "{{URL::route('treasury.find')}}",
                type: "POST",
                dataType: "json",
                data: {
                    '_token' : '{{csrf_token()}}',
                    'val' : x
                },
                cache: false,
                success: function(response){
                    $("#bank_name").val(response.source)
                    $("#branch_name").val(response.branch)
                    $("#account_name").val(response.account_name)
                    $("#account_number").val(response.account_number)
                    $("#currency").val(response.currency).trigger('change')
                    $("#id_tre").val(response.id)
                }
            })
        }
        function printDiv(divName) {
            $("#print-section").html("")
            $(divName).removeClass('collapse')
            $("#print-section").removeClass('collapse')
            var printContents = document.getElementById(divName).innerHTML;
            $("#print-section").append(printContents)
            print()

            // window.print();
            $("#print-section").addClass('collapse')
            $(divName).addClass('collapse')
        }
        function saveField(x){
            console.log(x)
            $("#table-print-"+x).DataTable().destroy()
            var t = $("#table-print-"+x).DataTable({
                paging: false,
                sorting: false,
                bInfo: false,
            })
            var fields = $(".field-"+x).toArray()
            console.log(t.column())
            for (const fieldsKey in fields) {
                var column = t.column(fields[fieldsKey].value)
                if (fields[fieldsKey].checked){
                    column.visible(true)
                } else {
                    column.visible(false)
                }
            }
            $("#btn-close-modal-"+x).click()

        }

        function this_investor(x, y){
            var opt = $("#sel-to option").toArray()
            for (const optKey in opt) {
                console.log()
                $(opt[optKey]).show()
                if ($(opt[optKey]).val() == x){
                    $(opt[optKey]).hide()
                }
            }

            $("#key-investor").val(x)
            $("#key-detail").val(y)

        }

        $(document).ready(function(){
            $("#new-investor").hide()

            $("#sel-to").change(function(){
                // var am = $("#sel-to option:selected").attr('data-amount')
                if ($(this).val() == "new"){
                    // show for description
                    $("#new-investor").show()
                    $("#new-investor input").attr('required', true)
                } else {
                    $("#new-investor").hide()
                    $("#new-investor input").attr('required', false)
                }
            })

            $(".number").number(true, 2)
            $("#modalField").on('hidden.bs.modal', function(){
                $("#target-table").val("")
            })
            $("#btn-set-field").click(function(){
                $("#table-print").DataTable().destroy()
                var t = $("#table-print").DataTable({
                    paging: false,
                    sorting: false,
                    bInfo: false,
                })
                $("#btn-close-modal").click()
                var fields = $(".field").toArray()
                var field = []
                for (const fieldsKey in fields) {
                    var column = t.column(fields[fieldsKey].value)
                    if (fields[fieldsKey].checked){
                        column.visible(true)
                    } else {
                        column.visible(false)
                    }
                }
                // var c = btoa(JSON.stringify(field))
                {{--$("#frame_print").attr('src', "{{route('business.print', $business->id)}}?c="+c)--}}
            })
            $("#btn-submit-edit").hide()
            $("#btn-submit").hide()
            $("#btn-deposit").click(function(){
                Swal.fire({
                    title: "Add Deposit",
                    text: "Are you sure you want to submit this data?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Submit",
                    cancelButtonText: "Cancel",
                    reverseButtons: true,
                }).then(function(result){
                    if(result.value){
                        $("#btn-submit").click()
                    }
                })
            })

            $("table.display").DataTable({
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                },
                bInfo: false,
                paging: false
            })
            $("select.select2").select2({
                width: "100%"
            })
        })
    </script>
@endsection
