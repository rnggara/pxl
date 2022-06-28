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
                <h3>Business Description</h3><br>
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
                                    <td><b>{{ $partner[$business->partner] }}</b></td>
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
                                        {!! $business->own_remarks !!}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            {{--            <h5><span class="span">This page contains a list of Travel Order which has been formed.</span></h5>--}}
            <?php
            /** @var TYPE_NAME $details */
            /** @var TYPE_NAME $business */
            $rPaid = 0;
            $rProfit = 0;
            $rBalance = 0;
            foreach ($details as $key => $item){
                if ($item->status == "Paid"){
                    $rPaid += $item->cicilan;
                    $rProfit += $item->bunga;
                }

                $rBalance += $item->cicilan + $item->bunga;
            }
            ?>
            <div class="row mt-10" id="summary">
                <div class="col-md-12">
                    <h3>Business Detail Edit</h3>
                    <hr>
                    <table class="table display table-bordered table-hover" data-page-length="100">
                        <thead>
                        <tr class="bg-secondary">
                            <th class="text-center">Month#</th>
                            <th class="text-center">Payment Date</th>
                            <th class="text-center">Interest Rate (%)</th>
                            <th class="text-center">Balance</th>
                            <th class="text-center">Installment</th>
                            <th class="text-center">Profit</th>
                            <th class="text-center">Penalty</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Grand Total</th>
                            <th class="text-center">Administration</th>
                            <th class="text-center">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        /** @var TYPE_NAME $business */
                        $balance = $business->value;
                        $gtotal = 0;
                        $nCicilan = 0;
                        $nProfit = 0;
                        $nPaid = 0;
                        $nPenalty = 0;
                        $nPaidRow = 0;
                        $nAdmin = 0;
                        ?>
                        @foreach($details as $key => $item)
                            <?php
                            /** @var TYPE_NAME $item */
                            /** @var TYPE_NAME $key */
                            /** @var TYPE_NAME $details */
                            $gtotal += $item->cicilan + $item->bunga;
                            if ($gtotal >= $business->value){
                                $bgcolor = "#5dfdcb";
                            } else {
                                $bgcolor = "";
                            }
                            $bunga = $item->cicilan + $item->bunga;
                            if ($key == count($details) - 1){
                                $cicilan = $balance;
                            } else {
                                $cicilan = $item->cicilan;
                            }
                            $bunga1 = $bunga - $cicilan;
                            $penalty = (empty($item->penalty)) ? 0 : $item->penalty;
                            $nPenalty += $penalty;
                            ?>
                            <tr bgcolor="{{$bgcolor}}">
                                <td align="center">{{$key + 1}}</td>
                                <td align="center">{{date('d F Y', strtotime($item->plan_date))}}</td>
                                <td align="center">{{$item->bunga_rate}} %</td>
                                <td align="right">
                                    <span class="number balance">{{ $balance }}</span>
                                </td>
                                <td align="right">
                                    <input type="text" data-id="{{ $item->id }}" {{ ($item->status == "Paid") ? "readonly" : "" }} class="form-control number installment text-right" name="installment[{{ $key }}]" value="{{ $cicilan }}">
                                </td>
                                <td align="right">
                                    <span class="number bunga">{{ $item->bunga }}</span>
                                </td>
                                <td align="right">{{number_format($penalty,2)}}</td>
                                <td align="right">
                                    <span class="number total">{{ $cicilan + $item->bunga }}</span>
                                </td>
                                <td align="right">
                                    <span class="number gtotal">{{ $gtotal }}</span>
                                </td>
                                <td align="right">{{number_format($administration, 2)}}</td>
                                <td align="center">{{$item->status}}</td>
                                <?php
                                /** @var TYPE_NAME $item */
                                $balance -= $cicilan;
                                $nCicilan += $cicilan;
                                $nProfit += $item->bunga;
                                if ($item->status == "Paid"){
                                    $nPaid += $item->cicilan + $item->bunga;
                                }
                                $nAdmin += $administration;
                                ?>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-secondary">
                                <th colspan="4" class="text-right">Total</th>
                                <th class="text-right">
                                    <span class="number" id="total_cicilan">{{ $nCicilan }}</span>
                                </th>
                                <th class="text-right">
                                    <span class="number">{{ $nProfit }}</span>
                                </th>
                                <th class="text-right">{{number_format($nPenalty, 2)}}</th>
                                <th class="text-right">
                                    <span class="number" id="total_gtotal">{{ $gtotal }}</span>
                                </th>
                                <th></th>
                                <th class="text-right">{{number_format($nAdmin, 2)}}</th>
                                <th class="text-right">{{number_format($nPaid, 2)}}</th>
                            </tr>
                            <tr>
                                <th colspan="11" class="text-right">
                                    <button type="button" id="btn-update" class="btn btn-primary">Update</button>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('custom_script')
    <script src="{{asset('theme/assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js?v=7.0.5')}}"></script>
    <script src="{{ asset('assets/jquery-number/jquery.number.js') }}"></script>
    <script>

        function calc(){
            var _gtotal = 0
            var _total_gtotal = 0
            $("span.total").each(function(){
                var tr = $(this).parents("tr")
                var gtotal = tr.find("span.gtotal")

                var total_val = parseFloat($(this).text().replaceAll(",", ""))

                _gtotal += total_val
                _total_gtotal += total_val
                gtotal.number(_gtotal, 2)
            })

            $("#total_gtotal").number(_total_gtotal, 2)

            var _total_installment = 0
            var bl = {{ $business->value }}
            $("input.installment").each(function(){
                var installment_val = parseFloat($(this).val().replaceAll(",", ""))
                _total_installment += installment_val
                var tr = $(this).parents("tr")
                var _bl = tr.find("span.balance")
                _bl.number(bl, 2)
                bl -= installment_val
            })

            $("#total_cicilan").number(_total_installment, 2)
        }

        $(document).ready(function(){
            $("input.number").number(true, 2)
            $("span.number").number(true, 2)

            var installment = $(".installment").toArray()
            $(".installment").on('change paste', function(){
                var tr = $(this).parents("tr")
                var bunga = tr.find("span.bunga")
                var total = tr.find("span.total")
                var gtotal = tr.find("span.gtotal")

                var bunga_val = parseFloat(bunga.text().replaceAll(',', ""))
                var total_val = parseFloat(total.text().replaceAll(',', ""))
                var gtotal_val = parseFloat(gtotal.text().replaceAll(',', ""))
                var installment_val = parseFloat($(this).val().replaceAll(",", ""))

                var new_total = installment_val + bunga_val
                total.number(new_total, 2)

                calc()
            })

            $("#btn-update").click(function(){
                var _total_installment = parseFloat($("#total_cicilan").text().replaceAll(",", ""))
                if (_total_installment != {{ $business->value }}) {
                    var _bl = $.number({{ $business->value }}, 2)
                    return Swal.fire("Installment", "Total Installment <> " + _bl, "error")
                } else {
                    Swal.fire({
                        title: "Are you sure?",
                        text: "You want to update this business?",
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonText: "Yes"
                    }).then(function(result) {
                        if (result.value) {
                            // ajax
                            var ival = []
                            var id_detail = []
                            $("input.installment").each(function(){
                                var val = parseFloat($(this).val().replaceAll(",", ""))
                                ival.push(val)
                                id_detail.push($(this).data('id'))
                            })
                            $.ajax({
                                url : "{{ route('business.detail_edit_post', $business->id) }}",
                                type : "post",
                                dataType : "json",
                                data : {
                                    _token : "{{ csrf_token() }}",
                                    installments : ival,
                                    id_detail : id_detail
                                },
                                beforeSend : function(){
                                    $("#btn-update").prop("disabled", true).text("Loading...").addClass("spinner spinner-left")
                                    Swal.fire({
                                        title: "Loading",
                                        onOpen: function() {
                                            Swal.showLoading()
                                        }
                                    })
                                },
                                success : function(response){
                                    swal.close()
                                    $("#btn-update").prop("disabled", false).text("Update").removeClass("spinner spinner-left")
                                    if(response){
                                        Swal.fire("Updated", "", "success").then(function(result){
                                            if(result.value){
                                                location.reload()
                                            }
                                        });
                                    }
                                }
                            })
                        }
                    });
                }
            })

            $("table.display").DataTable({
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                },
                bInfo: false,
                paging: false,
                ordering : false,
            })
            $("select.select2").select2({
                width: "100%"
            })
        })
    </script>
@endsection
