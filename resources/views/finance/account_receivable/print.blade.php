@extends('layouts.template')

@section('content')
    <style type="text/css">
        @media print
        {
            body * { visibility: hidden; color: black;}
            .print * { visibility: visible; }
            .notprint * { visibility: hidden; }
            #need-border {
                border: black 1px solid;
            }
        }
    </style>
    <?php
        $curr = 'Rp. ';
        if ($inv->currency == 'USD') {
            $curr = 'US$ ';
        }
    ?>
<div class="print">
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container">
            <!-- begin::Card-->
            <div class="card card-custom overflow-hidden" id="need-border">
                <div class="card-body p-0">
                    <!-- begin: Invoice-->
                    <!-- begin: Invoice header-->
                    <div class="row justify-content-center py-8 px-8 py-md-27 px-md-0">
                        <div class="col-md-9">
                            <div class="d-flex justify-content-between pb-10 pb-md-20 flex-column flex-md-row">
                                <h1 class="display-4 font-weight-boldest mb-10">INVOICE</h1>
                                <div class="d-flex flex-column align-items-md-end px-0">
                                    <!--begin::Logo-->
                                    <a href="#" class="mb-5">
                                        <img src="{{str_replace("public", "public_html", asset('images/'.\Session::get('company_app_logo')))}}" class="h-md-100px h-sm-100px" alt="" />
                                    </a>
                                    <!--end::Logo-->
                                    <span class="d-flex flex-column align-items-md-end">
                                        <span style="font-size: 18px;">{{Session::get('company_name_parent')}}</span>
                                        <span  style="font-size: 15px;">{!! $company->address !!}</span>
                                        <span  style="font-size: 15px;">{{(empty($company->city)) ? "Jakarta"  : $company->city }} |&nbsp;Phone:{{Session::get('company_phone')}}</span>
                                    </span>
                                </div>
                            </div>
                            <div class="separator separator-solid separator-border-2 separator-dark"></div>
                            <div class="d-flex justify-content-between pt-6">
                                <div class="d-flex flex-column flex-root">
                                    <span class="font-weight-bolder mb-2">INVOICE NO.</span>
                                    <span class=""><b>{{$inv_detail->no_inv}}</b></span>
                                    <br><br>
                                    <span class="font-weight-bolder mb-2">Issue Date</span>
                                    <span class="">{{date('d M Y', strtotime($inv_detail->date))}}</span>
                                </div>
                                <div class="d-flex flex-column flex-root">
                                    <span class="font-weight-bolder mb-2">CONTRACT NO.</span>
                                    <span class=""><b>{!! $prj->agreement_number !!}</b></span>
                                    <br><br>
                                    <span class="font-weight-bolder mb-2">PROJECT</span>
                                    <span class="">{!!$prj->agreement_title!!}</span>
                                </div>
                                <div class="d-flex flex-column flex-root">
                                    <span class="font-weight-bolder mb-2">INVOICE TO.</span>
                                    <span class="">{{(!empty($data_client)) ? strtoupper($data_client->company_name) : ""}}
                                    <br />
                                    {!! (!empty($data_client)) ? $data_client->address : "" !!}</span>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between pt-6">
                                <div class="d-flex flex-column flex-root">

                                </div>
                                <div class="d-flex flex-column flex-root">

                                </div>

                                <div class="d-flex flex-column flex-root">

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end: Invoice header-->
                    <!-- begin: Invoice body-->
                    <div class="row justify-content-center py-8 px-8 py-md-10 px-md-0">
                        <div class="col-md-9">

                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr style="border: 2px solid black">
                                        <th class="pl-0 text-center font-weight-boldest text-uppercase" style="font-size: 14px; border-right: 2px solid black;" width="45%">Description</th>
                                        <th class="text-center font-weight-boldest text-uppercase" style="font-size: 14px; border-right: 2px solid black;">Quantity</th>
                                        <!-- <th class="text-right font-weight-boldest text-uppercase" style="font-size: 14px">UoM</th> -->
                                        <th class="text-center font-weight-boldest text-uppercase" style="font-size: 14px;"></th>
                                        <th class="text-center font-weight-boldest text-uppercase" style="font-size: 14px; border-right: 2px solid black;">Unit Price </th>
                                        <th class="text-center font-weight-boldest text-uppercase" style="font-size: 14px;"></th>
                                        <th class="text-center pr-0 font-weight-boldest text-uppercase" style="font-size: 14px">Total Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($inv_prints as $key => $item)
                                        <tr class="font-weight-bold font-size-lg" style="border: 2px solid black; ">
                                            <td class="pl-0 pt-7" style="border-right: 2px solid black; ">
                                                <div class="ml-5">
                                                    {!! $item->description !!}
                                                </div>
                                            </td>
                                            <td class="text-right pt-7" style="border-right: 2px solid black; ">{{$item->qty}}&nbsp;{{$item->uom}}</td>
                                            <!-- <td class="text-right pt-7"></td> -->
                                            <td class="text-right pt-7">{{$curr}}</td>
                                            <td class="text-right pt-7" style="border-right: 2px solid black; ">{{number_format($item->unit_price)}}</td>
                                            <td class="text-right pt-7">{{$curr}}</td>
                                            <td class="pr-0 pt-7 text-right">
                                                <label for="" class="amount mr-2">{{number_format($item->qty * $item->unit_price)}}</label>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr class="font-weight-bold font-size-lg" style="border: 2px solid black">
                                        <td colspan="4" class="text-right" style="border-right: 2px solid black; ">Sub Total</td>
                                        <td class="text-right">{{$curr}}</td>
                                        <td class="text-right pr-0" style="border-right: 2px solid black; ">
                                            <label id="sub-total" class="mr-2">{{number_format(0)}}</label>
                                        </td>
                                    </tr>
                                    <tr class="font-weight-bold font-size-lg" id="row_discount" style="border: 2px solid black">
                                        <td colspan="4" class="text-right" style="border-right: 2px solid black; ">
                                            <label for="" class="">Discount</label>
                                        </td>
                                        <td class="text-right">{{$curr}}</td>

                                        <td class="text-right pr-0" style="border-right: 2px solid black; ">
                                            <label id="discount" class="mr-2">{{$inv_detail->discount}}</label>
                                        </td>
                                    </tr>
                                    <tr class="font-weight-bold font-size-lg" id="row_total" style="border: 2px solid black">
                                        <td colspan="4" class="text-right" style="border-right: 2px solid black; ">Total</td>
                                        <td class="text-right">{{$curr}}</td>

                                        <td class="text-right pr-0" style="border-right: 2px solid black; ">
                                            <label id="total-net" class="mr-2 col-form-label">{{number_format(0)}}</label>
                                        </td>
                                    </tr>
                                    @if(is_array(json_decode($inv_detail->taxes)))
                                    @foreach(json_decode($inv_detail->taxes) as $key => $tax)
                                        @if($isPrint[$tax] == 1)
                                        <tr class="font-weight-bold font-size-lg" style="border: 2px solid black">
                                            <td colspan="4" class="text-right" style="border-right: 2px solid black; ">{{$tax_name[$tax]}}
                                            </td>
                                            <td class="text-right">{{$curr}}</td>

                                            <td align="right" class="pr-0" style="border-right: 2px solid black; ">
                                                <label id="tax{{$tax}}" class="mr-2">{{number_format(0, 2)}}</label>
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                    @endif
                                    <tr class="font-weight-boldest font-size-lg" style="border: 2px solid black">
                                        <td colspan="4" class="text-right" style="font-size: 16px; border-right: 2px solid black;">GRAND TOTAL</td>
                                        <td class="text-right" style="font-size: 16px;">{{$curr}}</td>
                                        <td class="text-right pr-0" style="border-right: 2px solid black; ">
                                            <label id="payable" class="mr-2" style="font-size: 16px;">{{number_format(0)}}</label>
                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- end: Invoice body-->
                    <!-- begin: Invoice footer-->
                    <div class="row justify-content-center">
                        <div class="col-md-9">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <thead>
                                    <tr style="border-bottom: 0px">
                                        <th class="font-weight-bold text-uppercase">PAYMENT METHOD</th>
                                        <th class="font-weight-bold text-uppercase"></th>
                                        <th class="font-weight-bold text-uppercase"></th>

                                        <th class="font-weight-bold text-uppercase"></th><!--
                                        <th class="font-weight-bold text-uppercase">DUE DATE</th> -->
                                        <th class="font-weight-bold text-uppercase" colspan="2"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="font-weight-bolder">
                                        <td>

                                        {{(!empty($payment_account)) ? $payment_account->source : ""}}
                                            <br>
                                            {{(!empty($payment_account)) ? $payment_account->account_name : ""}}

                                        <br>
                                        Account Number:<br>
                                        {{(!empty($payment_account)) ? $payment_account->account_number : ""}}
                                        <br>

                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td> </td>
                                        <!-- <td>{{date("d M Y", strtotime(date("Y-m-d", strtotime($inv_detail->date)) . "+1 month"))}}</td> -->
                                        <td class="font-size-h3 font-weight-boldest">
                                            <!-- <span class="font-size-h5 font-weight-boldest" ></span><span class="font-size-h5 font-weight-boldest mb-1" id="payable"></span> -->

                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php
                        $presdir = "";
                        if (Session::get('company_id') == 1) {
                            $presdir = "_____________";

                        } elseif (Session::get('company_id') == 17) {
                            $presdir = "Fika Muti Putri";

                        } elseif (Session::get('company_id') == 19) {
                            $presdir = "Amalia Inami Putri";

                        } elseif (Session::get('company_id') == 20) {
                            $presdir = "Amalia Inami Putri";

                        } elseif (Session::get('company_id') == 21) {
                            $presdir = "Ari Pamudji";

                        } elseif (Session::get('company_id') == 22) {
                            $presdir = "Tri Sunarji";

                        }elseif (Session::get('company_id') == 23) {
                            $presdir = "Yosafat Eden WIjang Perkasa";

                        }
                    ?>
                   <!--  <table width="75%" border="1" style="margin-left: 100px;">
                        <tr>
                            <td width="55%"></td>
                            <td width="15%" height="100%">


                            </td>
                        </tr>
                    </table> -->
                    <table width="100%" border="0" style="margin-top: 70px">

                        <tr>
                            <th width="70%">
                            </th>
                            <th width="30%" class="font-weight-boldest font-size-lg">
                                <div><br><br><br>
                                    <p class="font-header" style="font-size: 14px;"><strong>{{$presdir}}</strong></p>
                                    <p class="font-header" style=" margin-top: -10px; font-size: 12px;">President Director</p>
                                </div>
                            </th>
                        </tr>
                    </table>
                    <!-- end: Invoice footer-->
                    <!-- begin: Invoice action-->
                    <div class="row justify-content-center py-8 px-8 py-md-10 px-md-0">
                        <div class="col-md-10">
                            <div class="d-flex justify-content-between notprint">
                                <button type="button" class="btn btn-primary font-weight-bold" onclick="window.print();">Print Invoice</button>
                            </div>
                        </div>
                    </div>
                    <!-- end: Invoice action-->
                    <!-- end: Invoice-->
                </div>
            </div>
            <!-- end::Card-->
        </div>
        <!--end::Container-->
    </div>
</div>

@endsection
@section('custom_script')
<script src="{{ asset('assets/jquery-number/jquery.number.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            sum_amount()
        })
        function sum_amount(){
            var jsontaxformula = "{{json_encode($tax_formula)}}".replaceAll("&quot;", "\"")
            var taxformula = JSON.parse(jsontaxformula)


            var amount = $(".amount").toArray()
            var sub = 0;
            var am = 0;
            for (let i = 0; i < amount.length; i++) {
                sub += parseInt(amount[i].innerHTML.replaceAll(",", ""))
            }

            var disc = $("#discount").text()
            if (disc == 0) {
                $("#row_discount").remove()
                $("#row_total").remove()
            }

            $("#sub-total").text(sub.toFixed(2))

            am = sub - disc
            var net = sub - disc

            $("#total-net").text(net.toFixed(2))

            @if(!empty($inv_detail->taxes))
            var _jsontax = "{{$inv_detail->taxes}}".replaceAll("&quot;", "\"")
            var _tax = JSON.parse(_jsontax)

            var jsonwapu = "{{json_encode($isWapu)}}".replaceAll("&quot;", "\"")
            var wapu = JSON.parse(jsonwapu)
            var jsonprint = "{{json_encode($isPrint)}}".replaceAll("&quot;", "\"")
            var print = JSON.parse(jsonprint)

            for (let i = 0; i < _tax.length; i++) {
                if (print[_tax[i]] == 1){
                    var tx = document.getElementById("tax"+_tax[i])
                    var $sum = net
                    var tax_val = eval(taxformula[_tax[i]])
                    tx.innerHTML = tax_val.toFixed(2)
                    $("#tax"+_tax[i]).number(tax_val)
                    am += tax_val
                }

            }

            @endif

            $("#payable").number(am.toFixed(2))
            $("#sub-total").number(sub)
            $("#total-net").number(net.toFixed(2))
            $("#discount").number($("#discount").html())
        }
    </script>
@endsection
