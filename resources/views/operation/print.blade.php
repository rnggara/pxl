<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
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
    <style>
        @media print {
            .pagebreak {
                page-break-after: always;
            }

            .pageborder {
                height: 100vh;
                border: 3px solid black;
                border-block-end: 5px solid black;
            }
        }

    </style>
</head>
<body style="background-color: #fff" id="kt_body" class="header-fixed header-mobile-fixed page-loading-enabled">
    <div class="row">
        <div class="col-md-11 mx-auto p-5">
            @foreach ($layout_order as $n => $item)
                <div class="pageborder">
                    <div class="row pt-40">
                        @foreach ($_header as $head)
                            @switch($head)
                                @case('title')
                                    <div class="col-md-4 mx-auto text-center" style="padding: 4rem 2rem">
                                        <h1 class="text-center">{{ $title ?? "FIELD REPORT" }}</h1>
                                    </div>
                                    @break
                                @case("logo_1")
                                    <div class="col-md-4 mx-auto text-center">
                                        @if ($logo_1 == "1")
                                            <img class="max-w-150px" src="{{ str_replace("public", "public_html", asset($setting->left_logo)) }}">
                                        @endif
                                    </div>
                                    @break
                                @case('logo_2')
                                    <div class="col-md-4 mx-auto text-center">
                                        @if ($logo_2 == "1")
                                            <img class="max-w-150px" src="{{ str_replace("public", "public_html", asset($setting->right_logo)) }}">
                                        @endif
                                    </div>
                                    @break
                            @endswitch
                        @endforeach
                    </div>
                    <div class="row m-10">
                        <table class="table table-bordered font-weight-bold">
                            <tr>
                                <td style="width: 50%">
                                    <div class="row">
                                        <span class="col-form-label col-3">Report No.</span>
                                        <span class="col-form-label col-9">: {{ $report->report_no }}</span>
                                    </div>
                                    <div class="row">
                                        <span class="col-form-label col-3">Division</span>
                                        <span class="col-form-label col-9">: {{ strtoupper($project->prefix) }}</span>
                                    </div>
                                    <div class="row">
                                        <span class="col-form-label col-3">Date</span>
                                        <span class="col-form-label col-9">: {{ date("d F Y", strtotime($report->report_date)) }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="row">
                                        <span class="col-form-label col-3">Report By</span>
                                        <span class="col-form-label col-9">: {{ strtoupper($report->created_by) }}</span>
                                    </div>
                                    <div class="row">
                                        <span class="col-form-label col-3">Location</span>
                                        <span class="col-form-label col-9">: {{ strtoupper($report->location) }}</span>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="row m-10 pagebreak">
                        @switch(strtolower($item))
                            @case("record")
                                <div class="col-md-12">
                                    @foreach ($category as $keycat => $cat)
                                        @if (isset($rowdetails[$keycat]))
                                            <h1>{{ strtoupper($cat) }}</h1>
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th class="text-center">Name</th>
                                                    @if ($keycat != "sum")
                                                        <th class="text-center">{{ ($keycat == "truck") ? "Truck Details" : "Description" }}</th>
                                                    @endif
                                                    <th class="text-center">{{ ($keycat == "safety") ? "Remark" : (($keycat == "truck") ? "Transfer Details" : "Value") }}</th>
                                                    @if ($keycat != "truck")
                                                        <th class="text-center">{{ ($keycat == "safety") ? "UoM" : "" }}</th>
                                                        <th class="text-center">Status</th>
                                                    @endif
                                                </tr>
                                                @foreach ($rowdetails[$keycat] as $det)
                                                    @php
                                                        $detdata = $det['data'];
                                                    @endphp
                                                    <tr>
                                                        <td align="center">{!! $det['item_name'] !!}</td>
                                                        @if ($keycat != "sum")
                                                            <td align="center">
                                                                @if ($keycat == "truck")
                                                                    <span>License Plate : {{ $detdata['license_plate'] }}</span> <br>
                                                                    <span>Capacity : {{ $detdata['capacity'] }}</span> <br>
                                                                    <span>Company : {{ $detdata['company'] }}</span>
                                                                @else
                                                                    {!! $det['description'] !!}
                                                                @endif
                                                            </td>
                                                        @endif
                                                        <td align="center">
                                                            @if (in_array($keycat, ["tank", "pump", "sum"]))
                                                                {{ $detdata['value'] }}
                                                            @elseif($keycat == "truck")
                                                                <span>Start ({{ $det['uom'] }}) : {{ $detdata['start'] }}</span> <br>
                                                                <span>Stop ({{ $det['uom'] }}) : {{ $detdata['stop'] }}</span> <br>
                                                                <span>Total ({{ $det['uom'] }}) : {{ $detdata['total'] }}</span>
                                                            @endif
                                                        </td>
                                                        @if ($keycat != "truck")
                                                            <td align="center">{{ $det['uom'] }}</td>
                                                            <td align="center">
                                                                {{ $detdata['status'] }}
                                                            </td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                            </table>
                                        @endif
                                    @endforeach
                                </div>
                                @break
                            @case("activity")
                                <div class="col-md-12">
                                    <h1>ACTIVITY</h1>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th class="text-center" style="width: 50%">FROM - TO</th>
                                            <th class="text-center">ACTIVITY DESCRIPTION</th>
                                        </tr>
                                        @foreach ($activity as $item)
                                            <tr>
                                                <td align="center">{{ $item->_from }} - {{ $item->_to }}</td>
                                                <td>{!! $item->description !!}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                                @break
                            @case('inventory')
                                <div class="col-md-12">
                                    <h1>INVENTORY RECORD</h1>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Item Name</th>
                                            <th class="text-center">Start</th>
                                            <th class="text-center">In</th>
                                            <th class="text-center">Out</th>
                                            <th class="text-center">Balance</th>
                                        </tr>
                                        @foreach ($inventory as $iinvt => $item)
                                            <tr>
                                                <td align="center">{{ $iinvt +1 }}</td>
                                                <td>{{ $item->item_name }}</td>
                                                <td align="center">{{ number_format($item->qty, 2) }}</td>
                                                <td align="center">{{ number_format($item->in, 2) }}</td>
                                                <td align="center">{{ number_format($item->out, 2) }}</td>
                                                <td align="center">{{ number_format(($item->qty + $item->in - $item->out), 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                                @break
                        @endswitch
                    </div>
                </div>
            @endforeach
            <div class="pageborder">
                <div class="row">
                    @if ($logo_1 == "1")
                        <div class="col-md-4 mx-auto text-center">
                            <img class="max-w-150px" src="{{ str_replace("public", "public_html", asset($setting->left_logo)) }}">
                        </div>
                    @endif
                    <div class="col-md-4 mx-auto text-center" style="padding: 4rem 2rem">
                        <h1 class="text-center">{{ $title ?? "FIELD REPORT" }}</h1>
                    </div>
                    @if ($logo_2 == "1")
                        <div class="col-md-4 mx-auto text-center">
                            <img class="max-w-150px" src="{{ str_replace("public", "public_html", asset($setting->right_logo)) }}">
                        </div>
                    @endif
                </div>
                <div class="row m-10">
                    <table class="table table-bordered font-weight-bold">
                        <tr>
                            <td style="width: 50%">
                                <div class="row">
                                    <span class="col-form-label col-3">Report No.</span>
                                    <span class="col-form-label col-9">: {{ $report->report_no }}</span>
                                </div>
                                <div class="row">
                                    <span class="col-form-label col-3">Division</span>
                                    <span class="col-form-label col-9">: {{ strtoupper($project->prefix) }}</span>
                                </div>
                                <div class="row">
                                    <span class="col-form-label col-3">Date</span>
                                    <span class="col-form-label col-9">: {{ date("d F Y", strtotime($report->report_date)) }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="row">
                                    <span class="col-form-label col-3">Report By</span>
                                    <span class="col-form-label col-9">: {{ strtoupper($report->created_by) }}</span>
                                </div>
                                <div class="row">
                                    <span class="col-form-label col-3">Location</span>
                                    <span class="col-form-label col-9">: {{ strtoupper($report->location) }}</span>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="row m-10">
                    <div class="col-md-12">
                        <h1>ATTACHMENTS</h1>
                        <div class="row">
                            @foreach ($file as $item)
                                <div class="col-md-4">
                                    <img width="100%" src="{{ str_replace("public", "public_html", asset($item->file_name)) }}">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
