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
<body style="background-color: #fff" id="kt_body" class="header-fixed header-mobile-fixed page-loading-enabled">
<div class="row">
    <div class="col-md-6">

    </div>
    <div class="col-md-6">
        <img alt="Logo" src="{{asset('assets/images/'.$dashboard_logo)}}" class="max-h-90px" style="margin-left: -50px"  />
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <h2 class="text-center">{{strtoupper(\Session::get('company_name_parent'))}}</h2>
        <h4 class="text-center">POLICY &nbsp; {{$detail->id_detail}}/{{strtoupper(\Session::get('company_tag'))}}-POLICY/{{date('m/y',strtotime($detail->date_detail))}}</h4>
        <h4 class="text-center">TOPIC: {{strtoupper($main->topic)}}</h4>
    </div>
</div>
<br>
<hr>

<table>
    <tbody>
    <tr>
        <td width="100%">
            <p class="ml-30">
                {!! $detail->content !!}
            </p>
        </td>
    </tr>
    </tbody>
</table>
<br><br><br>
@if (!empty($detail->attachment))
<div class="row">
    <div class="col-md-12">
        <center>
            <h4>ATTACHMENT</h4>
        </center>
        <div class="col-md-12 text-center">
            <img src="{{str_replace('public','public_html',asset('/media/policy_attachment/'))}}/{{$detail->attachment}}" class="img-responsive center-block">
        </div>
    </div>
</div>
@endif

<br><br><br>
<hr>
<table>
    <thead>
    <tr>
        <th width="33%"><center> <p>Prepared By</p> </center></th>
        <th width="34%"><center> <p>Acknowledged By</p> </center></th>
        <th width="33%"><center> <p>Approved By</p> </center></th>
    </tr>
    <br />
    </thead>
    <tbody>
    <tr>
        <td width="33%"><center> <p>{{$detail->created_by}}</p> </center></td>
        <td width="34%"><center> <p>{{$detail->acknowledge_by}}</p> </center></td>
        <td width="33%"><center> <p>{{$detail->approved_by}}</p> </center></td>
    </tr>
    </tbody>
</table>
<br>
<hr>

</body>
</html>
