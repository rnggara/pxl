@php
    $file_name = "pressure_vessel_".date("Y_m_d_H_i").".xls";
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=$file_name");
@endphp
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
        table, td, th {
            border: 1px solid black;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
    </style>
</head>
<body style="background-color: #fff" id="kt_body" class="header-fixed header-mobile-fixed page-loading-enabled">
    <div class="row">
        <div class="col-12">
            <h3 class="text-center">Pressure Vessel</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <table class="table" border="1">
                <thead>
                    <tr>
                        <td class="text-center" style="vertical-align: middle">No</td>
                        {{-- <td class="text-center" style="vertical-align: middle">ID</td> --}}
                        @foreach ($columns as $item)
                            @if (!in_array($item, array('id','created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'company_id')))
                                <td class="text-center" style="vertical-align: middle">{{ ucwords(str_replace("_", " ", $item)) }}</td>
                            @endif
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $key => $item)
                        <tr>
                            <td align="center">{{ $key + 1 }}</td>
                            {{-- <td align="center" class="text-nowrap">
                                ID - {{ sprintf("%03d", $item->id) }}
                            </td> --}}
                            @foreach ($columns as $col)
                                @if (!in_array($col, array('id','created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'company_id')))
                                    <td align="center">{{ $item[$col] }}</td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
