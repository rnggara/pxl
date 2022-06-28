@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Leasing Description</h3><br>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{URL::route('leasing.index')}}" class="btn btn-success btn-xs"><i class="fa fa-arrow-circle-left"></i></a>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div class="card card-custom bg-primary m-5">
                <div class="separator separator-solid separator-white opacity-20"></div>
                <div class="card-body text-white">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="text-white">
                                <tr>
                                    <td>Subject</td>
                                    <td>&nbsp;:&nbsp;</td>
                                    <td>{{$loan->subject}}</td>
                                </tr>
                                <tr>
                                    <td>Vendor</td>
                                    <td>&nbsp;:&nbsp;</td>
                                    <td>{{$loan->vendor}}</td>
                                </tr>
                                <tr>
                                    <td>Currency</td>
                                    <td>&nbsp;:&nbsp;</td>
                                    <td>{{$loan->currency}}</td>
                                </tr>
                                <tr>
                                    <td>Leasing Price</td>
                                    <td>&nbsp;:&nbsp;</td>
                                    <td>{{$loan->value}}</td>
                                </tr>
                                <tr>
                                    <td>Down Payment</td>
                                    <td>&nbsp;:&nbsp;</td>
                                    <td>{{$loan->dp}}</td>
                                </tr>
                                <tr>
                                    <td>Administration Cost</td>
                                    <td>&nbsp;:&nbsp;</td>
                                    <td>{{$loan->adm}}</td>
                                </tr>
                                <tr>
                                    <td>Insurance</td>
                                    <td>&nbsp;:&nbsp;</td>
                                    <td>{{$loan->insurance}}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="text-white">
                                <tr>
                                    <td>Investment Percentage</td>
                                    <td>&nbsp;:&nbsp;</td>
                                    <td>{{$loan->bunga}} %</td>
                                </tr>
                                <tr>
                                    <td>Start Date</td>
                                    <td>&nbsp;:&nbsp;</td>
                                    <td>{{date('Y-m-d', strtotime($loan->start))}}</td>
                                </tr>
                                <tr>
                                    <td>Leasing Duration</td>
                                    <td>&nbsp;:&nbsp;</td>
                                    <td>{{$loan->period}} month(s)</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="separator separator-dashed separator-border-2 separator-primary"></div>
            <div class="m-5">
                <form action="{{URL::route('leasing.save_plan')}}" method="post">
                    @csrf
                    <h3>Loan Detail</h3>
                    <hr>
                    <table class="table display">
                        <thead>
                        <tr>
                            <th class="text-center">Month Period</th>
                            <th class="text-center">Date Period</th>
                            <th class="text-center">Credit Balance</th>
                            <th class="text-center">Principal Installments</th>
                            <th class="text-center">Interest Rate</th>
                            <th class="text-center">Interest Amount</th>
                            <th class="text-center">Total Payment</th>
                            <th class="text-center">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($loan_item) == 0)
                            <?php $installment = 0;
                            $total_amount = 0;
                        ?>
                            @for($i=1; $i <= $loan->period; $i++)
                                <?php
                                $loan_amount = $loan->value - $loan->dp;
                                    if ($i > $loan->cicil_start){
                                        $cicilan = round($loan_amount / ($loan->period - $loan->cicil_start), 2);
                                        $read = "";
                                    } else {
                                        $cicilan = 0;
                                        $read = "readonly";
                                    }
                                //$rate = ($cicilan * (($loan->bunga/100) / 365 * date('t', strtotime($perbayar[$i]))));
                                $rate = $cicilan * ($loan->bunga/100);
                                $total_amount = $cicilan + $rate;
                                ?>
                                <tr>
                                    <td align="center">{{$i}}</td>
                                    <td align="center">
                                        {{date('Y-m-d', strtotime($perbayar[$i]))}}
                                        <input type="hidden" name="plan_date[{{$i}}]" value="{{date('Y-m-d', strtotime($perbayar[$i]))}}">
                                    </td>
                                    <td align="center">{{number_format($loan->value - $installment, 2)}}</td>
                                    <td align="center">
                                        <input type="text" id="cicilan[{{$i}}]" onchange="calc_1({{$i}})" {{$read}} class="form-control kt_inputmask_6 number" name="installment[{{$i}}]" value="{{$cicilan}}">
                                    </td>
                                    <td>
                                        <input type="text" id="percent[{{$i}}]" onchange="calc_3({{$i}})" class="form-control kt_inputmask_6" name="rate[{{$i}}]" value="{{$loan->bunga}}">
                                    </td>
                                    <td align="right">
                                        <input type="text" class="form-control kt_inputmask_6 number" id="bunga[{{$i}}]" name="interest[{{$i}}]" readonly value="{{number_format($rate, 2)}}">
                                    </td>
                                    <td align="right">
                                        <input type="text" class="form-control kt_inputmask_6 number" id="total_bayar[{{$i}}]" readonly value="{{number_format($total_amount, 2)}}">
                                    </td>
                                    <td align="center">
                                        planned
                                    </td>
                                </tr>
                                <?php
                                $installment += $cicilan;
                                ?>
                            @endfor
                        @else
                            <?php $installment = 0;
                            $total_amount = 0;
                            ?>
                            @foreach($loan_item as $key => $value)
                                <?php $total_amount = $value->cicilan + $value->bunga ?>
                                <tr>
                                    <td align="center">{{$key + 1}}</td>
                                    <td align="center">
                                        {{date('Y-m-d', strtotime($value->plan_date))}}
                                        <input type="hidden" name="plan_date[{{$key}}]" value="{{date('Y-m-d', strtotime($value->plan_date))}}">
                                    </td>
                                    <td align="center">{{number_format($loan->value - $installment, 2)}}</td>
                                    <td align="right">
                                        {{number_format($value->cicilan, 2)}}
                                    </td>
                                    <td align="right">
                                        {{number_format($value->bunga_rate, 2)}} %
                                    </td>
                                    <td align="right">
                                        {{number_format($value->bunga, 2)}}
                                    </td>
                                    <td align="right">
                                        {{number_format($total_amount, 2)}}
                                    </td>
                                    <td align="center">
                                        {{$value->status}}
                                    </td>
                                </tr>
                                <?php $installment += $value->cicilan ?>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    <hr>
                    <div class="row">
                        <span class="col-md-10"></span>
                        <div class="col-md-2">
                            <input type="hidden" name="loan" value="{{$loan->id}}">
                            @if(count($loan_item) > 0)
                                <a href="{{URL::route('leasing.edit_plan', $loan->id)}}" class="btn btn-primary btn-xs"><i class="fa fa-pencil-alt"></i> Edit Plan</a>
                            @else
                                <button type="submit" class="btn btn-success btn-xs"><i class="fa fa-check"></i> Save Plan</button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
<script src="{{asset('assets/jquery-number/jquery.number.js')}}"></script>
    <script>
        function button_approve(x){
            Swal.fire({
                title: "Approve",
                text: "Are you sure you want to approve?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Submit",
                cancelButtonText: "Cancel",
                reverseButtons: true,
            }).then(function(result){
                if(result.value){
                    $.ajax({
                        url: "{{URL::route('treasury.approve')}}",
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
        function button_reject(x){
            Swal.fire({
                title: "Reject",
                text: "Are you sure you want to reject?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Submit",
                cancelButtonText: "Cancel",
                reverseButtons: true,
            }).then(function(result){
                if(result.value){
                    $.ajax({
                        url: "{{URL::route('treasury.reject')}}",
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
        function toNum(string){
            var num = Number(string.replace(/[^0-9.-]+/g,""))
            return num
        }


        function calc_1(index){
            var cicilan = document.getElementById("cicilan["+index+"]")
            var perc    = document.getElementById("percent["+index+"]")
            var bunga   = document.getElementById("bunga["+index+"]")
            var total   = document.getElementById("total_bayar["+index+"]")
            var newbunga = (toNum(perc.value) / 100) * toNum(cicilan.value)
            bunga.value  = new Intl.NumberFormat('en-ID', {minimumFractionDigits: 2,}).format(newbunga.toFixed(2))
            cicilan.value = new Intl.NumberFormat('en-ID', {minimumFractionDigits: 2,}).format(toNum(cicilan.value).toFixed(2))
            var newTotal = toNum(cicilan.value) + toNum(bunga.value)
            total.value = new Intl.NumberFormat('en-ID', {minimumFractionDigits: 2,}).format(newTotal.toFixed(2))
        }

        function calc_2(index){
            var cicilan = document.getElementById("cicilan["+index+"]")
            var perc    = document.getElementById("percent["+index+"]")
            var bunga   = document.getElementById("bunga["+index+"]")
            var total   = document.getElementById("total_bayar["+index+"]")
            var newperc = (toNum(bunga.value) / toNum(cicilan.value)) * 100
            perc.value  = newperc.toFixed(2)
            bunga.value = new Intl.NumberFormat('en-ID', {minimumFractionDigits: 2,}).format(toNum(bunga.value).toFixed(2))
            var newTotal = toNum(cicilan.value) + toNum(bunga.value)
            total.value = new Intl.NumberFormat('en-ID', {minimumFractionDigits: 2,}).format(newTotal.toFixed(2))
            calc_4(index)
        }

        function calc_3(index){
            var cicilan = document.getElementById("cicilan["+index+"]")
            var perc    = document.getElementById("percent["+index+"]")
            var bunga   = document.getElementById("bunga["+index+"]")
            var total   = document.getElementById("total_bayar["+index+"]")
            var newbunga = (toNum(perc.value) / 100) * toNum(cicilan.value)
            bunga.value  = new Intl.NumberFormat('en-ID', {minimumFractionDigits: 2,}).format(newbunga.toFixed(2))
            cicilan.value = new Intl.NumberFormat('en-ID', {minimumFractionDigits: 2,}).format(toNum(cicilan.value).toFixed(2))
            var newTotal = toNum(cicilan.value) + toNum(bunga.value)
            total.value = new Intl.NumberFormat('en-ID', {minimumFractionDigits: 2,}).format(newTotal.toFixed(2))
            calc_4(index)
        }

        function calc_4(index){
            var cicilan = document.getElementById("cicilan["+index+"]")
            var perc    = document.getElementById("percent["+index+"]")
            var bunga   = document.getElementById("bunga["+index+"]")
            var total   = document.getElementById("total_bayar["+index+"]")
            for (var i = index + 1; i <= {{$loan->period}}; i++) {
                var i_cicilan = document.getElementById("cicilan["+i+"]")
                var i_perc    = document.getElementById("percent["+i+"]")
                var i_bunga   = document.getElementById("bunga["+i+"]")
                var i_total   = document.getElementById("total_bayar["+i+"]")

                i_perc.value = perc.value
                var newbunga = (toNum(i_perc.value) / 100) * toNum(i_cicilan.value)
                i_bunga.value  = new Intl.NumberFormat('en-ID', {minimumFractionDigits: 2,}).format(newbunga.toFixed(2))
                i_cicilan.value = new Intl.NumberFormat('en-ID', {minimumFractionDigits: 2,}).format(toNum(i_cicilan.value).toFixed(2))
                var newTotal = toNum(i_cicilan.value) + toNum(i_bunga.value)
                i_total.value = new Intl.NumberFormat('en-ID', {minimumFractionDigits: 2,}).format(newTotal.toFixed(2))
            }
        }

        $(document).ready(function(){
            $("table.display").DataTable({
                pageLength: 100
            })
            $(".kt_inputmask_6").inputmask('decimal', {
                rightAlignNumerics: false
            })
            $(".number").number(true, 2)
        })
    </script>
@endsection
