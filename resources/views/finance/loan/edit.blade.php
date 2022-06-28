@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Loan Description</h3><br>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{URL::route('loan.index')}}" class="btn btn-success btn-xs"><i class="fa fa-arrow-circle-left"></i></a>
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
                                    <td>Bank Name</td>
                                    <td>&nbsp;:&nbsp;</td>
                                    <td>{{$loan->bank}}</td>
                                </tr>
                                <tr>
                                    <td>Currency</td>
                                    <td>&nbsp;:&nbsp;</td>
                                    <td>{{$loan->currency}}</td>
                                </tr>
                                <tr>
                                    <td>Loan Amount</td>
                                    <td>&nbsp;:&nbsp;</td>
                                    <td>{{$loan->value}}</td>
                                </tr>
                                <tr>
                                    <td>Loan Type</td>
                                    <td>&nbsp;:&nbsp;</td>
                                    <td>{{($loan->type == "KI") ? "Investment Credit" : "Working Capital Credit"}}</td>
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
                                    <td>Loan Duration</td>
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
                <form action="{{URL::route('loan.update_plan')}}" method="post">
                    @csrf
                    <h3>Loan Detail - Edit</h3>
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
                            <?php $installment = 0;
                            $total_amount = 0;
                            ?>
                            @foreach($loan_item as $key => $value)
                                <?php $total_amount = $value->cicilan + $value->bunga;
                                if (strtolower($value->status) != "planned"){
                                    $read = "readonly";
                                    $bg = "form-control-solid";
                                } else {
                                    $read = "";
                                    $bg = "";
                                }
                                ?>
                                <tr>
                                    <td align="center">{{$key + 1}}</td>
                                    <td align="center">
                                        {{date('Y-m-d', strtotime($value->plan_date))}}
                                        <input type="hidden" name="plan_date[{{$value->id}}]" value="{{date('Y-m-d', strtotime($value->plan_date))}}">
                                        <input type="hidden" name="id_det[]" value="{{$value->id}}">
                                    </td>
                                    <td align="center">{{number_format($loan->value - $installment, 2)}}</td>
                                    <td align="center">
                                        <input type="text" {{$read}} id="cicilan[{{$value->id}}]" onchange="calc_1({{$value->id}})" class="form-control {{$bg}} number" name="installment[{{$value->id}}]" value="{{$value->cicilan}}">
                                    </td>
                                    <td>
                                        <input type="text" {{$read}} id="percent[{{$value->id}}]" onchange="calc_3({{$value->id}})" onkeydown="calc_3({{$value->id}})" class="form-control {{$bg}} kt_inputmask_6" name="rate[{{$value->id}}]" value="{{$value->bunga_rate}}">
                                    </td>
                                    <td align="right">
                                        <input type="text" onchange="_bunga(this, {{$value->id}})" {{$read}} class="form-control {{$bg}} kt_inputmask_6" id="bunga[{{$value->id}}]" name="interest[{{$value->id}}]" value="{{number_format($value->bunga, 2)}}">
                                    </td>
                                    <td align="right">
                                        <input type="text" {{$read}}  class="form-control {{$bg}} kt_inputmask_6" id="total_bayar[{{$value->id}}]" readonly value="{{number_format($total_amount, 2)}}">
                                    </td>
                                    <td align="center">
                                        {{$value->status}}
                                    </td>
                                </tr>
                                <?php $installment += $value->cicilan ?>
                            @endforeach
                        </tbody>
                    </table>
                    <hr>
                    <div class="row">
                        <span class="col-md-10"></span>
                        <div class="col-md-2">
                            <input type="hidden" name="loan" value="{{$loan->id}}">
                            <button type="submit" class="btn btn-primary btn-xs"><i class="fa fa-check"></i> Update Plan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{ asset('assets/jquery-number/jquery.number.js') }}"></script>
    <script>
        function _bunga(e, id){
            var _cicilan = "cicilan["+id+"]"
            var cicilan = document.getElementById("cicilan["+id+"]")
            var total = document.getElementById("total_bayar["+id+"]")

            var bunga = $(e).val()
            console.log(_cicilan, cicilan.value, bunga)

            var total_val = parseFloat(cicilan.value.replaceAll(',', '')) + parseFloat(bunga.replaceAll(',', ''))

            total.value = $.number(Math.ceil(total_val), 2)
        }
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

        function calc_cicilan(index){
            var cicilan = document.getElementById("cicilan["+index+"]")
            var perc    = document.getElementById("percent["+index+"]")
            var bunga   = document.getElementById("bunga["+index+"]")
            var i_total   = document.getElementById("total_bayar["+index+"]")
            i_total.value = toNum(cicilan.value) + toNum(bunga.value)
        }

        $(document).ready(function(){
            $("table.display").DataTable({
                pageLength: 100
            })

            $(".number").number(true, 2)
            // $(".kt_inputmask_6").inputmask('decimal', {
            //     rightAlignNumerics: false
            // })
        })
    </script>
@endsection
