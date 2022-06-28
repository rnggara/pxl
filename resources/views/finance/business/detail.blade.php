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
                <a href="{{route('business.index')}}" class="btn btn-xs btn-icon btn-success"><i class="fa fa-arrow-left"></i></a>
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
                        <div class="col-md-4">
                            <div class="form-group text-right">
                                <div class="btn-group">
                                    <button data-toggle="modal" data-target="#modalField" class="btn btn-sm btn-icon btn-light-dark"><i class="fa fa-cog"></i></button>
                                    <button type="button" onclick="btn_print()" class="btn btn-sm btn-icon btn-primary"><i class="fa fa-print"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="btn-group">
                                    <a href="#summary" class="btn btn-sm btn-primary"><i class="fa fa-arrow-down"></i> See Summary</a>
                                    <a href="{{route('business.investors.list', $business->id)}}" class="btn btn-sm btn-warning"><i class="fa fa-briefcase"></i>Investors</a>
                                    <a href="{{route('business.investor', $business->id)}}" class="btn btn-sm btn-secondary"><i class="fa fa-briefcase"></i> Old Format Investors</a>
                                </div>
                            </div>
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
            <div class="row mx-auto">
                <div class="col-md-3 mx-auto">
                    <div class="card card-custom gutter-b bg-secondary">
                        <div class="card-header">
                            <div class="card-title">
                                <h3>Amount Returned</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <h3>{{$business->currency}}. {{number_format($rPaid, 2)}}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mx-auto">
                    <div class="card card-custom gutter-b bg-secondary">
                        <div class="card-header">
                            <div class="card-title">
                                <h3>Profit Returned</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <h3>{{$business->currency}}. {{number_format($rProfit, 2)}}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mx-auto">
                    <div class="card card-custom gutter-b bg-secondary">
                        <div class="card-header">
                            <div class="card-title">
                                <h3>Amount Balance</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <h3>{{$business->currency}}. {{number_format($business->value - $rPaid, 2)}}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-10" id="summary">
                <div class="col-md-12">
                    <h3>Business Detail <a href="{{ route('business.detail_edit', $business->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a></h3>
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
                            <th class="text-center">Paid</th>
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
                                <td align="right">{{number_format($balance,2)}}</td>
                                <td align="right">{{number_format($cicilan,2)}}</td>
                                <td align="right">{{number_format($item->bunga,2)}}</td>
                                <td align="right">{{number_format($penalty,2)}}</td>
                                <td align="right">{{number_format($item->cicilan + $item->bunga,2)}}</td>
                                <td align="right">{{number_format($gtotal, 2)}}</td>
                                <td align="right">{{number_format($administration, 2)}}</td>
                                <td align="center">{{$item->status}}</td>
                                <td>
                                    @if($item->status == "Paid")
                                        <span class="text-right">
                                            @php
                                                $nPaidRow += (intval($item->cicilan) + intval($item->bunga))
                                            @endphp
                                            {{number_format($item->cicilan + $item->bunga, 2)}}</span>
                                    @else
                                        <div class="text-center">
                                            <button onclick="button_pay({{$item->id}})" class="btn btn-xs btn-success">Pay</button>
                                            <button onclick="btn_close({{$item->id}})" class="btn btn-xs btn-danger">Close</button>
                                        </div>
                                    @endif
                                </td>
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
                                <th class="text-right">{{number_format($nCicilan, 2)}}</th>
                                <th class="text-right">{{number_format($nProfit, 2)}}</th>
                                <th class="text-right">{{number_format($nPenalty, 2)}}</th>
                                <th class="text-right">{{number_format($gtotal, 2)}}</th>
                                <th></th>
                                <th class="text-right">{{number_format($nAdmin, 2)}}</th>
                                <th></th>
                                <th class="text-right">{{number_format($nPaid, 2)}}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="row mt-10">
                <div class="col-md-12">
                    <table class="table table-hover table-bordered">
                        <tr>
                            <td bgcolor="#5dfdcb">{{$partner[$business->partner]}}</td>
                            <td>Total</td>
                            <td>Profit</td>
                        </tr>
                        <tr>
                            <td bgcolor="#5dfdcb">{{number_format($business->value, 2)}}</td>
                            <td>{{number_format($gtotal, 2)}}</td>
                            <td>{{number_format($gtotal - $business->value, 2)}}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="payModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" id="pay-content">
            </div>
        </div>
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
                                    @foreach($fields as $key => $field)
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
                    <button type="button" id="btn-close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                    <button type="button" id="btn-set-field" name="submit" class="btn btn-xs btn-primary font-weight-bold">
                        <i class="fa fa-check"></i>
                        Save</button>
                </div>
            </div>
        </div>
    </div>

    <div id="print-section" style="visibility: hidden;">
        <div class="row m-10 border border-dark">
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
                        <td><b>{{$partner[$business->partner]}}</b></td>
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
        <div class="row m-10">
            <div class="col-md-12">
                <h3>Business Detail</h3>
                <hr>
                <table class="table table-bordered table-hover" id="table-print" data-page-length="100">
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
                            <td align="right">{{number_format($balance,2)}}</td>
                            <td align="right">{{number_format($cicilan,2)}}</td>
                            <td align="right">{{number_format($bunga1,2)}}</td>
                            <td align="right">{{number_format($penalty,2)}}</td>
                            <td align="right">{{number_format($item->cicilan + $item->bunga,2)}}</td>
                            <td align="right">{{number_format($gtotal, 2)}}</td>
                            <?php
                            /** @var TYPE_NAME $item */
                            $balance -= $cicilan;
                            $nCicilan += $cicilan;
                            $nProfit += $bunga1;
                            if ($item->status == "Paid"){
                                $nPaid += $item->cicilan + $item->bunga;
                            }
                            ?>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr class="bg-secondary">
                        <th colspan="4" class="text-right">Total</th>
                        <th class="text-right">{{number_format($nCicilan, 2)}}</th>
                        <th class="text-right">{{number_format($nProfit, 2)}}</th>
                        <th class="text-right">{{number_format($nPenalty, 2)}}</th>
                        <th class="text-right">{{number_format($gtotal, 2)}}</th>
                        <th></th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('custom_script')
    <script src="{{asset('theme/assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js?v=7.0.5')}}"></script>
    <script>
        function button_pay(x){
            $("#payModal").modal('show')
            $.ajax({
                url: "{{URL::route('business.pay')}}/" + x,
                type: "GET",
                cache: false,
                success: function(response){
                    $("#pay-content").append(response)
                    $("#pay-content select.select2").select2({
                        width: "100%"
                    })
                }
            })
        }

        function btn_print() {
            $("#print-section").removeClass("collapse")
            print()
            $("#print-section").addClass("collapse")
        }

        function set_field(){
            $("#btn-close").click()
            var fields = $(".field").toArray()
            var field = []
            for (const fieldsKey in fields) {
                if (fields[fieldsKey].checked){
                    field.push(fields[fieldsKey].value)
                }
            }
            var c = btoa(JSON.stringify(field))
            $("#frame_print").attr('src', "{{route('business.print', $business->id)}}?c="+c)
        }

        function btn_close(x){
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, close it!"
            }).then(function(result) {
                if (result.value) {
                    window.location = '{{ route('business.detail_close') }}/'+x
                }
            });
        }

        $(document).ready(function(){
            $("#btn-close").hide()
            $("#print-section").addClass("collapse")
            set_field()
            $('#payModal').on('hidden.bs.modal', function () {
                $("#pay-content").html('')
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

            var t = $("#table-print").DataTable({
                paging: false,
                bInfo: false,
                searching: false,
                sorting: false
            })

            $("#btn-set-field").click(function(){
                $("#btn-close").click()
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

            @if (\Session::get('msg'))
                Swal.fire('Closed', 'Business has been closed', 'success')
            @endif

            @if (\Session::get('error'))
                Swal.fire('Not found', 'Data not found', 'error')
            @endif
        })
    </script>
@endsection
