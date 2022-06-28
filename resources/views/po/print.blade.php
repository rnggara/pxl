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
                            <h3 class="text-right">Purchase Order</h3>
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
                            <table border='0' width="100%">
                                <tr valign='top'>
                                    <td>Supplier:</td>
                                    <td>
                                        {{(isset($arrSup[$po->supplier_id])) ? $arrSup[$po->supplier_id]->name : "-"}}
                                    </td>
                                </tr>

                                <tr valign='top'>
                                    <td>Address:</td>
                                    <td>
                                        {{(isset($arrSup[$po->supplier_id])) ? strip_tags($arrSup[$po->supplier_id]->address) : "-"}}
                                    </td>
                                </tr>
                                <tr valign='top'>
                                    <td>Telephone:</td>
                                    <td>
                                        {{(isset($arrSup[$po->supplier_id])) ? $arrSup[$po->supplier_id]->telephone : "-"}}
                                    </td>
                                </tr>
                                <tr valign='top'>
                                    <td>Fax:</td>
                                    <td>
                                        {{(isset($arrSup[$po->supplier_id])) ? $arrSup[$po->supplier_id]->fax : "-"}}
                                    </td>
                                </tr>
                                <tr valign='top'>
                                    <td>Web:</td>
                                    <td>
                                        {{(isset($arrSup[$po->supplier_id])) ? $arrSup[$po->supplier_id]->web : "-"}}
                                    </td>
                                </tr>
                                <tr valign='top'>
                                    <td>PIC:</td>
                                    <td>
                                        {{(isset($arrSup[$po->supplier_id])) ? $arrSup[$po->supplier_id]->pic : "-"}}
                                    </td>
                                </tr>
                                <tr valign='top'>
                                    <td>Bank Acct:</td>
                                    <td>
                                        {!! (isset($arrSup[$po->supplier_id])) ? $arrSup[$po->supplier_id]->bank_acct : "-" !!}
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td width="33%">
                            <label for="">Deliver To:</label><br>
                            {{strip_tags($po->deliver_to)}}
                        <td width="33%">
                            <table border="0" width="100%">
                                <tr>
                                    <td width='90'>PO Number</td>
                                    <td>
                                        : {{$po->po_num}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Request Date</td>
                                    <td>
                                        : {{date('d/m/Y', strtotime($po->po_date))}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Division</td>
                                    <td>
                                        : {{$po->division}}
                                    </td>
                                </tr>

                                <tr>
                                    <td>Reference</td>
                                    <td>: {{$po->reference}}</td>
                                </tr>
                                <tr>
                                    <td>Project</td>
                                    <td>: {{$po->project}}


                                    </td>
                                </tr>
                                <tr>
                                    <td>Currency</td>
                                    <td>: {{$po->currency}}
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td>Notes</td>
                                    <td>: {!! $po->notes !!}

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
                #table-view table, #table-view tr, #table-view td {
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
                        <td class="text-center font-weight-bold text-primary">No</td>
                        <td class="text-center font-weight-bold text-primary">Items</td>
                        <td class="text-center font-weight-bold text-primary">Qty</td>
                        <td class="text-center font-weight-bold text-primary">Unit Price</td>
                        <td class="text-center font-weight-bold text-primary">Amount</td>
                    </tr>
                    {{--Items--}}
                    <?php $sumAmount = 0; ?>
                    @foreach($details as $i => $item)
                        <?php
                        /** @var TYPE_NAME $item */
                        $amount = $item->qty * $item->price;
                        ?>
                        <tr>
                            <td align="center">{{$i+1}}</td>
                            <td align="left">{{(isset($arrItem[$item->item_id])) ? $arrItem[$item->item_id]->name : ""}}</td>
                            <td align="center">{{$item->qty}} {{(isset($arrItem[$item->item_id])) ? $arrItem[$item->item_id]->uom : ""}}</td>
                            <td align="right">{{number_format($item->price, 2)}}</td>
                            <td align="right">{{number_format($amount, 2)}}</td>
                        </tr>
                        <?php
                        $sumAmount += $amount;
                        ?>
                    @endforeach
                    <tr>
                        <td></td>
                        <td>
                            <em>Persyaratan untuk pembayaran, harap dilampirkan:
                                <ol>
                                    <li>Original Purchase Order that has been sign and stamped by The Company.</li>
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
                    /** @var TYPE_NAME $po */
                    $rowspan = 0;
                    if (!empty($po->ppn)){
                        if (is_array(json_decode($po->ppn))){
                            $rowspan = count(json_decode($po->ppn));
                        }
                    }
                    ?>
                    <tr>
                        <td rowspan="{{6+$rowspan}}"></td>
                        <td rowspan="{{6+$rowspan}}" colspan="2">
                            A. Term Condition<br>
                             {!! $po->terms !!}
                            <br><br>

                            B. Term of Payment<br>
                            {!! $po->payment_term !!}

                        </td>
                        <td align="right">Sub Total:</td>
                        <td align="right">{{number_format($sumAmount, 2)}}</td>
                    </tr>
                    <tr>
                        <td align="right">Discount:</td>
                        <td align="right">{{number_format($po->discount, 2)}}</td>
                    </tr>
                    <?php /** @var TYPE_NAME $po */
                    $net = $sumAmount - $po->discount; ?>
                    <tr>
                        <td align="right">Net include discount:</td>
                        <td align="right">{{number_format($net, 2)}}</td>
                    </tr>
                    {{--TAXES--}}
                    <?php $taxes = 0; ?>
                    @if(!empty($po->ppn))
                        @if(is_array(json_decode($po->ppn)))
                            @foreach(json_decode($po->ppn) as $iTax)
                                <?php
                                /** @var TYPE_NAME $iTax */
                                $tax_name = "-";
                                $tax_val = 0;
                                $sum = $net;
                                if (isset($tax[$iTax])){
                                    $tax_name = $tax[$iTax]->tax_name;
                                    $tax_val = eval("return ".$tax[$iTax]->formula.";");
                                    $taxes += $tax_val;
                                }
                                ?>
                                <tr>
                                    <td align="right">{{$tax_name}}</td>
                                    <td align="right">{{number_format($tax_val, 2)}}</td>
                                </tr>
                            @endforeach
                        @endif
                    @endif
                    {{----}}
                    <tr>
                        <td align="right">Total after Tax:</td>
                        <td align="right">{{number_format($net + $taxes, 2)}}</td>
                    </tr>
                    <tr>
                        <td align="right">Down Payment:</td>
                        <td align="right">{{number_format($po->dp, 2)}}</td>
                    </tr>
                    <tr>
                        <td align="right">Total Price:</td>
                        <td align="right">{{number_format(($net + $taxes) - $po->dp, 2)}}</td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            Terbilang : {{\App\Helpers\Functions::terbilang(($net + $taxes) - $po->dp)}}
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
                            <label for="" class="font-weight-bold">..................</label>
                        </td>
                        <td align="right">
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
