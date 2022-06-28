<!doctype html>
<html lang="en">
<head>
    <base href="">
    <meta charset="utf-8" />
    <meta name="description" content="Updates and statistics" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!--begin::Fonts-->
    <link href="{{asset('theme/assets/css/pages/wizard/wizard-2.css?v=7.0.5')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Page Vendors Styles(used by this page)-->
    <link href="{{asset('theme/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css?v=7.0.5')}}" rel="stylesheet" type="text/css" />
    <!--end::Page Vendors Styles-->
    <!--begin::Global Theme Styles(used by all pages)-->
    <link href="{{asset('theme/assets/plugins/global/plugins.bundle.css?v=7.0.5')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('theme/assets/plugins/custom/prismjs/prismjs.bundle.css?v=7.0.5')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('theme/assets/css/style.bundle.css?v=7.0.5')}}" rel="stylesheet" type="text/css" />
    <!--end::Global Theme Styles-->
    <!--begin::Layout Themes(used by all pages)-->
    <!--end::Layout Themes-->
    <link rel="shortcut icon" href="{{($accounting_mode == 1) ? asset('/assets/images/icon_1.png') : asset('/assets/images/icon.png')}}" />
    <link href="{{asset('theme/assets/plugins/custom/datatables/datatables.bundle.css?v=7.0.5')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('theme/assets/plugins/custom/uppy/uppy.bundle.css?v=7.0.5')}}" rel="stylesheet" type="text/css" />

    <style type="text/css">
        .borderless td, .borderless th{
            border: solid, black, 5px;
            border-width: unset;
        }
    </style>
</head>
<body style="background-color: #fff">
<div class="row">
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-12">
                <table width="100%">
                    <tr>
                        <td align="center">
                            <div class="symbol symbol-150 mr-3">
                                <img alt="Pic" src="{{str_replace("public", "public_html", asset('images/'.$comp->app_logo))}}"/>
                            </div>
                        </td>
                        <td>
                            <strong>{{$comp->company_name}}</strong><br>
                            {{strip_tags($comp->address)}} <br>
                            Phone : {{$comp->phone}}<br>
                            Fax : {{$comp->fax}}<br>
                            Email : {{$comp->email}}<br>
                            NPWP : {{$comp->npwp}}
                        </td>
                        <td align="right">
                            <h3 class="text-right">Work Order</h3>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table width="100%">
                    <tr valign="top">
                        <td width="33%">
                            <table border='0' width="100%" >
                                <tr valign='top'>
                                    <td>Supplier:</td>
                                    <td>
                                        {{(isset($arrSup[$wo->supplier_id])) ? $arrSup[$wo->supplier_id]->name : "-"}}
                                    </td>
                                </tr>

                                <tr valign='top'>
                                    <td>Address:</td>
                                    <td>
                                        {{(isset($arrSup[$wo->supplier_id])) ? strip_tags($arrSup[$wo->supplier_id]->address) : "-"}}
                                    </td>
                                </tr>
                                <tr valign='top'>
                                    <td>Telephone:</td>
                                    <td>
                                        {{(isset($arrSup[$wo->supplier_id])) ? $arrSup[$wo->supplier_id]->telephone : "-"}}
                                    </td>
                                </tr>
                                <tr valign='top'>
                                    <td>Fax:</td>
                                    <td>
                                        {{(isset($arrSup[$wo->supplier_id])) ? $arrSup[$wo->supplier_id]->fax : "-"}}
                                    </td>
                                </tr>
                                <tr valign='top'>
                                    <td>Web:</td>
                                    <td>
                                        {{(isset($arrSup[$wo->supplier_id])) ? $arrSup[$wo->supplier_id]->web : "-"}}
                                    </td>
                                </tr>
                                <tr valign='top'>
                                    <td>PIC:</td>
                                    <td>
                                        {{(isset($arrSup[$wo->supplier_id])) ? $arrSup[$wo->supplier_id]->pic : "-"}}
                                    </td>
                                </tr>
                                <tr valign='top'>
                                    <td>Bank Acct:</td>
                                    <td>
                                        {!! (isset($arrSup[$wo->supplier_id])) ? $arrSup[$wo->supplier_id]->bank_acct : "-" !!}
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td width="33%">
                            <label for="">Deliver To:</label><br>
                        {{strip_tags($wo->deliver_to)}}
                        <td width="33%">
                            <table border="0" width="100%">
                                <tr>
                                    <td width='90'>WO Number</td>
                                    <td>
                                        : {{$wo->wo_num}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Request Date</td>
                                    <td>
                                        : {{date('d/m/Y', strtotime($wo->req_date))}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Division</td>
                                    <td>
                                        : {{$wo->division}}
                                    </td>
                                </tr>

                                <tr>
                                    <td>Reference</td>
                                    <td>: {{$wo->reference}}</td>
                                </tr>
                                <tr>
                                    <td>Project</td>
                                    <td>: {{$wo->project}}


                                    </td>
                                </tr>
                                <tr>
                                    <td>Currency</td>
                                    <td>: {{$wo->currency}}
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td>Notes</td>
                                    <td>: {!! $wo->notes !!}

                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row mt-5">
            <style type="text/css">
                #table-view table tr th {
                    border: 1px solid black;
                }
                #table-view td {
                    border-left: 1px solid black;
                    border-right: 1px solid black;
                }
                #table-view .term {
                    border-top : 1px solid black;
                }
                #table-view .foot {
                    border: 1px solid black;
                }
            </style>
            <div class="col-md-12" id="table-view">
                <table class="table table-borderless">
                    <tr>
                        <th class="text-center  text-primary">No</th>
                        <th class="text-center  text-primary">Items</th>
                        <th class="text-center  text-primary">Qty</th>
                        <th class="text-center  text-primary" nowrap="nowrap">Unit Price</th>
                        <th class="text-center  text-primary">Amount</th>
                    </tr>
                    {{--Items--}}
                    <?php $sumAmount = 0; ?>
                    @foreach($details as $i => $item)
                        <?php
                        /** @var TYPE_NAME $item */
                        $amount = $item->qty * $item->unit_price;
                        ?>
                        <tr>
                            <td class="" align="center">{{$i+1}}</td>
                            <td class="" align="left">{!! $item->job_desc !!}</td>
                            <td class="" align="center">{{$item->qty}}</td>
                            <td class="" align="right">{{number_format($item->unit_price, 2)}}</td>
                            <td class="" align="right">{{number_format($amount, 2)}}</td>
                        </tr>
                        <?php
                        $sumAmount += $amount;
                        ?>
                    @endforeach
                    {{----}}
                    <tr>
                        <td></td>
                        <td class="term">
                            <em>Persyaratan untuk pembayaran, harap dilampirkan:
                                <ol>
                                    <li>Original Work Order that has been sign and stamped by The Company.</li>
                                    <li>Payment account number</li>
                                    <li>Report / Time sheet job &amp; tools usage</li>
                                    <li>Original Tax Invoice</li>
                                    <li>Consumables and materials should be suitable with user specification. If not, it will be affected to project value</li>
                                    <li>All works should meet specified schedule. If there is a delay, then all costs incurred will be charged to the vendor</li>
                                    <li>All works must be done with good and specified result. If not, then all costs incurred will be charged to the vendor</li>
                                </ol>
                            </em>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php
                    /** @var TYPE_NAME $wo */
                    $rowspan = 0;
                    if (!empty($wo->ppn)){
                        if (is_array(json_decode($wo->ppn))){
                            $rowspan = count(json_decode($wo->ppn));
                        }
                    }
                    ?>
                    <tr class="term">
                        <td rowspan="{{6+$rowspan}}"></td>
                        <td rowspan="{{6+$rowspan}}" colspan="2">
                            A. Term Condition<br>

                            {!! $wo->terms !!}
                            <br><br>

                            B. Term of Payment<br>

                            {!! $wo->terms_payment !!}
                            <br><br>
                        </td>
                        <td align="right" nowrap="nowrap">Sub Total:</td>
                        <td align="right">{{number_format($sumAmount, 2)}}</td>
                    </tr>
                    <tr class="term">
                        <td align="right" nowrap="nowrap">Discount:</td>
                        <td align="right">{{number_format($wo->discount, 2)}}</td>
                    </tr>
                    <?php /** @var TYPE_NAME $wo */
                    $net = $sumAmount - $wo->discount; ?>
                    <tr class="term">
                        <td align="right" nowrap="nowrap">Net include discount:</td>
                        <td align="right">{{number_format($net, 2)}}</td>
                    </tr>
                    {{--TAXES--}}
                    <?php $taxes = 0; ?>
                    @if(!empty($wo->ppn))
                        @if(is_array(json_decode($wo->ppn)))
                            @foreach(json_decode($wo->ppn) as $iTax)
                                <?php
                                /** @var TYPE_NAME $iTax */
                                $tax_name = "-";
                                $tax_val = 0;
                                $sum = $net;
                                if (isset($tax[$iTax])){
                                    $tax_name = $tax[$iTax]->tax_name;
                                    $tax_val = eval("return ".$tax[$iTax]->formula.";");
                                    $tax_val = round($tax_val);
                                    $taxes += $tax_val;
                                }
                                ?>
                                <tr class="term">
                                    <td align="right" nowrap="nowrap">{{$tax_name}}</td>
                                    <td align="right">{{number_format($tax_val, 2)}}</td>
                                </tr>
                            @endforeach
                        @endif
                    @endif
                    <tr class="term">
                        <td align="right" nowrap="nowrap">Total after Tax:</td>
                        <td align="right">{{number_format($net + $taxes, 2)}}</td>
                    </tr>
                    <tr class="term">
                        <td align="right" nowrap="nowrap">Down Payment:</td>
                        <td align="right">{{number_format($wo->dp, 2)}}</td>
                    </tr>
                    <tr class="term">
                        <td align="right" nowrap="nowrap">Total Price:</td>
                        <td align="right">{{number_format(($net + $taxes) - $wo->dp, 2)}}</td>
                    </tr>
                    <tr class="foot">
                        <td colspan="5">
                            Terbilang : {{\App\Helpers\Functions::terbilang(($net + $taxes) - $wo->dp)}}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row mt-10">
            <div class="col-md-12">
                <table width="100%">
                    <tr>
                        <td align="center">
                            <label for="" class="font-weight-bold">[Materai 6000]</label>
                            <br><br><br><br><br>
                            <label for="" class="font-weight-bold">{{(isset($arrSup[$wo->supplier_id])) ? $arrSup[$wo->supplier_id]->pic : ".................."}}</label>
                        </td>
                        <td align="{{($img != "") ? "right" : "center"}}">
                            @if($img != "")
                            <img src="{{$img}}" style="max-width: 200px">
                            @else
                            <label for="" class="font-weight-bold">ttd</label>
                            <br><br><br><br><br>
                            <label for="" class="font-weight-bold">..................</label>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>
