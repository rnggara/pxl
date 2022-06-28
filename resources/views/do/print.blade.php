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

</head>
<body style="background-color: #fff" id="kt_body" class="header-fixed header-mobile-fixed page-loading-enabled pt-30">

<table width="100%" border="0" cellpadding="2" cellspacing="1" align="center">
    <tr>
        <td><table width="100%" border="0" cellpadding="2" cellspacing="1">
                <tr>
                    <td width="50%" align="center"><img src="{{str_replace("public", "public_html", asset('images/'.$company->p_logo))}}" height="55px" /><br />
                        <h2>{{strtoupper($company->company_name)}}</h2>
                        <div>
                            <p style="font-size:8px">
                                {{$company->address}}
                                <br />
                                Phone : {{$company->phone}}<br />
                                Email : {{$company->email}}<br />
                            </p>
                            <h1>DELIVERY ORDER</h1>
                            No.: {{$do->no_do}}
                        </div>
                    </td>
                    <td align="center">
                        {!! QrCode::size(150)->generate(route('do.detail',['id' => $do->id,'type' => 'receive'])) !!}
                    </td>
                </tr>

            </table></td>
    </tr>
    <tr>
        <td><table width="100%" border="0" cellpadding="2" cellspacing="1">
                <tr valign="top">
                    <td width="50%">
                        <table width="100%" border="1" cellpadding="2" cellspacing="1" style="border-collapse:collapse">
                            <tr>
                                <td width="100">From: </td>
                                <td>{{$do->whFromName}}&nbsp;</td>
                            </tr>
                            <tr>
                                <td>Address:</td>
                                <td>
                                    {{strip_tags($do->whFromAddress)}}</td>
                            </tr>
                            <tr>
                                <td>Telp:</td>
                                <td>{{$do->whFromTelp}}</td>
                            </tr>
                            <tr>
                                <td>Delivery Date: </td>
                                <td><?php echo date("d M Y", strtotime($do->deliver_date)); ?>&nbsp;</td>
                            </tr>
                        </table></td>
                    <td><table width="100%" border="1" cellpadding="2" cellspacing="1" style="border-collapse:collapse">
                            <tr>
                                <td width="100">Delivery To: </td>
                                <td>{{$do->whToName}}&nbsp;</td>
                            </tr>
                            <tr>
                                <td>Address:</td>
                                <td>{{strip_tags($do->whToAddress)}}</td>
                            </tr>
                            <tr>
                                <td>Telp:</td>
                                <td>{{$do->whToTelp}}</td>
                            </tr>
                            <tr>
                                <td>Notes: </td>
                                <td>{{$do->notes}}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table></td>
    </tr>
    <tr>
        <td>

            <table width="100%" border="1" cellpadding="2" cellspacing="1" style="border-collapse:collapse">
                <thead>
                <tr bgcolor="#999999">
                    <th width="30">No.</th>
                    <th width="50">Qty</th>
                    <th width="50">Uom</th>
                    <th>Description</th>
                    <th width="200">Remark</th>
                </tr>
                </thead>
                <tbody>
                @foreach($do_detail as $key => $detail)
                <tr valign="top">
                    <td align="right"><strong>{{($key+1)}}</strong>&nbsp;</td>
                    <td align="center"><strong>{{$detail->qty}}</strong>&nbsp;</td>
                    <td align="center"><strong>{{$detail->itemUom}}</strong>&nbsp;</td>
                    <td>
                        <strong>{{$detail->itemName}}</strong>
                        <?php if ($detail->specification) { ?>
                        <br />
                        <span style="font-size:12px;">
					<?php echo stripslashes(nl2br($detail->specification)); ?></span>
                        <?php } ?>

                    </td>
                    <td>&nbsp;</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan='5'>
                        <b>Notes:</b>
                        <?php if ($do->notes) { ?>
                            <br /><span style="font-size:12px;"><?php echo stripslashes(nl2br($do->notes)); ?></span>
                        <?php } ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table width="100%" border="1" cellpadding="2" cellspacing="1" style="border-collapse:collapse">
                <tr valign="top">
                    <td width="33%" align="center">
                        @if (!empty($do->driver_id))
                        {!! QrCode::size(100)->generate(route('do.dispatch',$do->id)) !!}
                        @endif
                    </td>
                    <td width="33%">
                        Prepared By<br />
                        <br />
                        <br />
                        <span>{{$do->deliver_by}}</span>
                    </td>
                    <td>
                        Recieved By<br />
                        <br />
                        <br />
                        <span>{{$do->departure_by}}</span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
