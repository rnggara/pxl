@extends('layouts.template')

@section('css')
    <style>
        #chartdiv {
        width: 100%;
        height: 500px;
        }

    </style>
    <link href="{{ asset('assets/plugins/custom/leaflet/leaflet.bundle.css?v=7.0.5') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<!--begin::Row-->
@if (!empty(\Auth::user()->discord_id))
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="card card-custom gutter-b card-stretch">
            <div class="card-body">
                <div class="d-flex justify-content-center">
                    <h2><img src='images/castle.png' height='40px' /> BEFORE YOU RULE <img src='images/castle.png' height='40px' /></h2>
                </div>
                <div class="row h-50">
                    <div class="col-12" style="top: 50%">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4 col-sm-6">
        <div class="card card-custom gutter-b card-stretch">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h2>Balance <img src='images/gold_coins.png' height='40px' /></h2><h2> ${{ number_format(\Auth::user()->do_code) }}</h2>
                </div>
                <div class="row h-50">
                    <div class="col-12" style="top: 50%">
                        <p>This currency will be converted to AoR P2E game.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-6">
        <div class="card card-custom gutter-b card-stretch">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h2>Energy </h2><h2> <span id="label-energy">{{ number_format(\Auth::user()->attend_code) }}</span> / {{ Session::get("company_period_end") }} <i class="fas fa-bolt font-size-h2 text-primary"></i> </h2>
                </div>
                <div class="row h-50">
                    <div class="col-12" style="top: 50%">
                        <p>Re-fill everyday at 2am UTC+0 for 7 <i class="fas fa-bolt font-size-p text-primary"></i> with max of 15. </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 text-center">
                        <button type="button" id="btn-metamask" class="btn btn-primary">
                            <i class="fab fa-ethereum"></i>
                            Login to Metamask (for AoR Whitelist purposes - You don't have to connect it)
                        </button>
                    </div>
                    <div class="col-12 text-center">
                        <label class="col-form-label">Account ID : <span id="label-metamask">-</span></label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4 col-sm-12">
        <div class="card card-custom gutter-b card-stretch">
            <div class="card-body">
                <div class="row" id="div-farm">
                    @if (empty(\Auth::user()->item_ripe_id))
                        <div class="col-12 text-center" style="top: 50%">
                            <div class="symbol symbol-150 mr-3">
                                <button class="btn btn-outline-primary btn-block p-md-15" onclick="_list_plants()" data-toggle="modal" data-target="#modalCities">
                                    <img src='images/planted.png' height='40px' />
                                    Grow Something
                                    <br>
                                    1 <i class="fas fa-bolt"></i>
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="col-12 text-center" style="top: 50%">
                            <div class="symbol symbol-150 mr-3">
                                <img src="{{ asset('theme/assets/media/misc/bg-2.jpg') }}" id="img-plant" alt="">
                            </div>
                        </div>
                        <div class="col-12 text-center" style="top: 50%">
                            <h3 class="font-size-h3">
                                {{ $plant->name }}
                            </h3>
                        </div>
                        <div class="col-12 text-center" style="top: 50%">
                            <span class="font-weight-bold" id="label-countdown">
                            </span>
                        </div>
                        <div class="col-12 text-center" style="top: 50%">
                            <div id="btn-farm">
                                <form action="{{ route("home.plants.farm") }}" method="post">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm px-20">Farm!</button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="card-footer"></div>
            <div class="card-header">
                <h3 class="card-title">
                    <img src='images/basket.png' height='40px' />
                    Your Bag
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <table class="table table-bordered table-hover table-striped" id="table-plant">
                            <thead>
                                <tr>
                                    <th>Plants</th>
                                    <th>Quantity</th>
                                    <th>Sell?</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="card card-custom gutter-b card-stretch">
            <div class="card-header">
                <h3 class="card-title">
                    <img src='images/map.png' height='40px' />
                    Your Current Location
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <table class="table table-bordered table-hover table-striped" id="table-city">
                            <thead>
                                <tr>
                                    <th>Current city</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2">
                                        <button type="button" class="btn btn-primary btn-block" onclick="_show_city()" data-target="#modalCities" data-toggle="modal">
                                            Go to another City ( 1 <i class="fas fa-bolt"></i>)
                                        </button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="card card-custom gutter-b card-stretch">
            
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4 col-sm-12">
        <div class="card card-custom gutter-b card-stretch">
            <div class="card-header">
                <h3 class="card-title"><img src='images/king.png' height='40px' /> Leaderboard</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped Leaderboard">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Username</th>
                            <th class="text-center">$</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($leaderboards as $i => $item)
                            <tr>
                                <td align="center">{{ $i+1 }}</td>
                                <td>
                                    {{ $item->name }}
                                </td>
                                <td>
                                    {{ $item->do_code ?? 0 }} &nbsp; @if($item->do_code > 1)<img src='images/gold_coins.png' height='15px' />@endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@else
    <div class="row">
        <div id="is-mobile"></div>
        <div class="col-xl-4">
            <!--begin::Engage Widget 7-->
            <div class="card card-custom card-stretch gutter-b">
                <div class="card-body d-flex p-0">
                    <div class="flex-grow-1 p-12 card-rounded bgi-no-repeat d-flex flex-column justify-content-center align-items-start" style="background-color: #FFF4DE; background-position: right bottom; background-size: auto 100%; background-image: url(assets/media/svg/humans/custom-8.svg)">
                        <h4 class="text-danger font-weight-bolder m-0">COVID 19 Health Tips</h4>
                        <p class="text-dark-50 my-5 font-size-xl font-weight-bold">Protect yourself, in the same time, caring for others.
                        <br />Here you can see all the tips related to COVID 19</p>
                        <a href="{{ route('general.covid.index') }}" class="btn btn-danger font-weight-bold py-2 px-6">See more</a>
                    </div>
                </div>
            </div>
            <!--end::Engage Widget 7-->
        </div>
        <div class="col-xl-4">
            <!--begin::Engage Widget 8-->
            <div class="card card-custom card-stretch gutter-b">
                <div class="card-body p-0 d-flex">
                    <div class="d-flex align-items-start justify-content-start bgi-no-repeat flex-grow-1 bg-light-warning p-8 card-rounded flex-grow-1 position-relative" style="background-color: #FFF4DE; background-position: right center; background-size: auto 90%; background-image: url(assets/media/svg/humans/sick1.png)">
                        <div class="d-flex flex-column align-items-start flex-grow-1 h-100">
                            @if (!empty($covid))
                            <div class="p-1 flex-grow-1">
                                <h4 class="text-warning font-weight-bolder">{{ $covid->nama_emp }}</h4>
                                @php
                                    $date1 = date_create($covid->tanggal_infeksi);
                                    $date2 = date_create(date("Y-m-d"));
                                    $diff = date_diff($date1, $date2);
                                @endphp
                                <p class="text-dark-50 font-weight-bold mt-3">
                                    {{ (isset($ccomp[$covid->perusahaan])) ? $ccomp[$covid->perusahaan] : "N/A" }}
                                    <br>
                                    {{ date("m/d/Y", strtotime($covid->tanggal_infeksi)) }} ({{ $diff->format("%m Bulan %d Hari") }})
                                    <br>
                                    Kondisi saat ini : {!! $covid->kondisi ?? "-" !!}
                                </p>
                            </div>
                            @else
                            <div class="p-1 flex-grow-1">
                                <h4 class="text-warning font-weight-bolder">No recent employee positive</h4>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Engage Widget 8-->
        </div>
        <div class="col-xl-4">
            <!--begin::Engage Widget 8-->
            @if (strtolower($div_name) == "admin" ||strtolower($div_name) == "hrd")
            <div class="card card-custom card-stretch gutter-b" style="background-color: #FFF4DE">
                <div class="card-body p-0">
                    <div class="row p-5">
                        <div class="col-12">
                            <h4 class="text-warning font-weight-bolder">Crew Operation Notificaton</h4>
                            <div class="scroll scroll-pull" data-scroll="true" style="height: 150px">
                                @for ($i = count($_to) - 1; $i >= 0 ; $i--)
                                    <div class="d-flex align-items-center mb-5">
                                        <div class="symbol symbol-40 symbol-white mr-5">
                                            <span class="symbol-label">
                                                {{ $i + 1 }}
                                            </span>
                                        </div>
                                        <div class="d-flex flex-column font-weight-bold">

                                            <span class="text-dark mb-1 font-size-lg">{{ $_to[$i]['emp_name'] }} <span class="text-muted text-right">{{ ($_to[$i]['month'] > 0) ? $_to[$i]['month']." months" : "" }} {{  $_to[$i]['days'] }} days</span></span>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="card card-custom card-stretch gutter-b">
                <div class="card-body p-0 d-flex">
                    <div class="d-flex align-items-start bgi-no-repeat justify-content-start flex-grow-1 bg-light-warning p-8 card-rounded flex-grow-1 position-relative" style="background-color: #FFF4DE; background-position: right bottom; background-size: auto 100%; background-image: url(assets/media/svg/humans/custom-13.svg)">
                        <div class="d-flex flex-column align-items-start flex-grow-1 h-100">
                            <div class="p-1 flex-grow-1">
                                <h4 class="text-warning font-weight-bolder">Prevent COVID<br /> and help end the pandemic</h4>
                                <p class="text-dark-50 font-weight-bold mt-3">Wear a mask<br />Wash your hand<br />Maintain safe distance & limit mobility<br />Get vaccinated</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <!--end::Engage Widget 8-->
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6">
            <!--begin::List Widget 10-->
            <div class="card card-custom  card-stretch gutter-b">
                <!--begin::Header-->
                @empty(Auth::user()->file_signature)
                    <div class="card-header">
                        <h3 class="card-title font-weight-bolder">
                            <i class="fa fa-info-circle font-size-h3 text-danger"></i> &nbsp; No signature. You have to register your digital signature&nbsp;<a href="{{route('account.info',['id'=>Auth::user()->id])}}">here</a>
                        </h3>
                    </div>
                @endempty
                <div class="card-header border-0">
                    <h3 class="card-title font-weight-bolder text-dark">Notifications</h3>
                    <div class="card-toolbar">
                        <form action="{{ route('home.reset') }}" method="post">
                            @csrf
                            <button type="submit" id="btn-sb" style="display: none">
                            </button>
                            <button type="button" onclick="_reset_daily()" class="btn btn-warning btn-sm">
                                Daily Manual Reset
                            </button>
                        </form>
                    </div>
                </div>
                <!--end::Header-->

                <!--begin::Body-->
                <div class="card-body pt-0">
                    <div class="scroll scroll-pull" data-scroll="true" data-wheel-propagation="true" style="height: 400px">
                        @if(count($rNotif) > 0)
                            @foreach($rNotif as $item)
                                <!--begin::Item-->
                                <div class="">
                                    <!--begin::Content-->
                                    <div class="d-flex align-items-center flex-grow-1">
                                        <!--begin::Checkbox-->
                                        <label class="col-form-label">
                                            <i class="fa fa-dot-circle text-primary"></i>
                                        </label>
                                        <!--end::Checkbox-->

                                        <!--begin::Section-->
                                        <div class="d-flex flex-wrap align-items-center justify-content-between w-100 ml-5">
                                            <!--begin::Info-->
                                            <div class="d-flex flex-column align-items-cente py-3 w-75">
                                                <!--begin::Title-->
                                                <label>
                                                    <a href="{{$item['url']}}" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">
                                                        {{$item['text']}}
                                                    </a>
                                                    <a href="{{ route('notif.clear', ["type" => base64_encode('clear'), 'id' => base64_encode($item['id'])]) }}" onclick="return confirm('are you sure?')" class="mb-1 ml-5 text-hover-danger">
                                                        <i class="fa fa-times"></i>
                                                    </a>
                                                </label>
                                                <!--end::Title-->

                                                <!--begin::Data-->
                                                {{--                            <span class="text-muted font-weight-bold">--}}
                                                {{--                            since 13/11/2020 08:00--}}
                                                {{-- </span> --}}
                                                <!--end::Data-->
                                            </div>
                                            <!--end::Info-->

                                            <!--begin::Label-->
                                        {{--                                    <span class="label label-lg label-{{$value['bg']}} label-inline font-weight-bold py-4">{{$value['count']}}</span>--}}
                                        <!--end::Label-->
                                        </div>
                                        <!--end::Section-->
                                    </div>
                                    <!--end::Content-->
                                </div>
                                <!--end::Item-->
                            @endforeach
                        @else
                            <div class="mb-6">
                                <!--begin::Content-->
                                <div class="d-flex align-items-center flex-grow-1">
                                    <!--begin::Checkbox-->
                                    <label class="checkbox checkbox-lg checkbox-lg flex-shrink-0 mr-4">
                                        <input type="checkbox" value="1"/>
                                        <span></span>
                                    </label>
                                    <!--end::Checkbox-->

                                    <!--begin::Section-->
                                    <div class="d-flex flex-wrap align-items-center justify-content-between w-100">
                                        <!--begin::Info-->
                                        <div class="d-flex flex-column align-items-cente py-2 w-75">
                                            <!--begin::Title-->
                                            <span class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">No data available</span>
                                            <!--end::Title-->

                                            <!--begin::Data-->
                                            {{--                            <span class="text-muted font-weight-bold">--}}
                                            {{--                            since 13/11/2020 08:00--}}
                                            </span>
                                            <!--end::Data-->
                                        </div>
                                        <!--end::Info-->

                                    </div>
                                    <!--end::Section-->
                                </div>
                                <!--end::Content-->
                            </div>
                        @endif
                    </div>
                </div>
                <!--end: Card Body-->
            </div>
            <!--end: Card-->
            <!--end: List Widget 10-->
        </div>
        <div class="col-xl-6" id="meetingdiv">
            <!--begin::List Widget 11-->
            <div class="card card-custom card-stretch gutter-b">
                <!--begin::Header-->
                <div class="card-header border-0">
                    <h3 class="card-title font-weight-bolder text-dark">Meetings</h3>
                    <div class="card-toolbar">
                        <button type="button" data-toggle="modal" data-target="#addMeetingZoom" class="btn btn-sm btn-primary btn-icon">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
                <!--end::Header-->

                <!--begin::Body-->
                <div class="card-body pt-0">
                    <div class="modal fade" id="addMeetingZoom" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title">Add Meeting</h1>
                                    <button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
                                </div>
                                <form action="{{ route("mz.store") }}" method="post">
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label class="col-form-label col-3">Subject Meeting</label>
                                            <div class="col-9">
                                                <input type="text" class="form-control" name="description" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-3">Date Meeting</label>
                                            <div class="col-9">
                                                <input type="date" class="form-control" name="date" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-3">Start Time</label>
                                            <div class="col-9">
                                                <input type="time" class="form-control" name="start_time" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-3">Link Zoom</label>
                                            <div class="col-9">
                                                <input type="text" class="form-control" name="link" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        @csrf
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Add</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="scroll scroll-pull" data-scroll="true" data-wheel-propagation="true" style="height: 400px" id="meeting-show">

                    </div>
                </div>
                <!--end::Body-->
            </div>
            <!--end::List Widget 11-->
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 col-sm-12">
            <div class="card card-custom gutter-b card-stretch bgi-no-repeat" style="background-position: right top; background-size: 30% auto; background-image: url({{ asset("assets/media/svg/shapes/abstract-4.svg") }})">
                <div class="card-body">
                    <h3 class="card-title text-muted">Minutes Of Meeting</h3>
                    @if (!empty($mom))
                    <div class="font-weight-bold text-success mb-5"><a href="{{ route('mom.detail', $mom->id_main) }}">{{ $mom->id_main."/".$view_company[$mom->company_id]['tag']."-MOM/".date('m', strtotime($mom->date_main))."/".date('y', strtotime($mom->date_main)) }}</a></div>
                    <div class="font-weight-bold font-size-h3">
                        {!! $mom->topic !!}
                    </div>
                    <div class="font-weight-bold text-success mb-5">{{ date("d F Y H:i", strtotime($mom->date_main)) }} - {{ date("d F Y H:i", strtotime($mom->date_end)) }}</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="card card-custom gutter-b card-stretch bgi-no-repeat" style="background-position: right top; background-size: 30% auto; background-image: url({{ asset("assets/media/svg/shapes/abstract-2.svg") }})">
                <div class="card-body">
                    <h3 class="card-title text-muted">Daily Report</h3>
                    <div class="font-weight-bold text-success mb-5">Last 3 daily report</div>
                    <table class="table table-borderless">
                        @foreach ($report as $item)
                            <tr>
                                <td><a href="{{ route('general.dr.view', $item->id) }}">{{ $item->rpt_subject }}</a></td>
                                <td>{{ (isset($div[$item->rpt_wh])) ? $div[$item->rpt_wh] : "" }}</td>
                                <td>{{ date('d F Y', strtotime($item->rpt_time)) }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="card card-custom gutter-b card-stretch bgi-no-repeat" style="background-position: right top; background-size: 30% auto; background-image: url({{ asset("assets/media/svg/shapes/abstract-1.svg") }})">
                <div class="card-body">
                    <h3 class="card-title text-muted">Upcoming Leaves</h3>
                    <div class="row">
                        <div class="col-12">
                            <div class="scroll scroll-pull" data-scroll="true" style="height: 150px">
                                <table class="table table-borderless">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Employee Name</th>
                                            <th class="text-center">Start Leave</th>
                                            <th class="text-center">Leave Duration(s)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($leave as $i => $item)
                                            @if (isset($employee[$item->id_emp]))
                                            @php
                                                $emp = $employee[$item->id_emp];
                                                $date_awal = date_create($item->awal);
                                                $date_akhir = date_create($item->akhir);
                                                $diff = date_diff($date_awal, $date_akhir);
                                                $duration = $diff->format('%a');
                                            @endphp
                                            <tr>
                                                <td align="center">{{ $i+1 }}</td>
                                                <td align="center">
                                                    {{ $emp['emp_name'] }}
                                                    <span class="text-muted">{{ (isset($div[$emp['division']])) ? $div[$emp['division']] : "" }}</span>
                                                </td>
                                                <td align="center">{{ date("d F Y", strtotime($item->awal)) }}</td>
                                                <td align="center">{{ $duration.' days' }}</td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-custom gutter-b card-stretch bgi-no-repeat" style="background-position: right top; background-size: 30% auto; background-image: url({{ asset("assets/media/svg/shapes/abstract-3.svg") }})">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <h3 class="card-title text-muted">Your session in hours</h3>
                            <div class="row mx-auto" id="spinner-div">
                                <div class="mx-auto text-center">
                                    <h3><div class="spinner spinner-primary spinner-lg mr-15"></div></h3>
                                </div>
                            </div>
                            <div id="chartdiv"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
<!--end::Row-->



    {{-- <div class="subheader py-2 py-lg-4 subheader-transparent" id="kt_subheader">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex align-items-center flex-wrap mr-1">
                <!--begin::Page Heading-->
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <!--begin::Page Title-->
                    <h5 class="text-dark font-weight-bold my-1 mr-5">Menu</h5>
                    <!--end::Page Title-->
                    <!--begin::Breadcrumb-->

                    <!--end::Breadcrumb-->
                </div>
                <!--end::Page Heading-->
            </div>
            <!--end::Info-->
            <!--begin::Toolbar-->
            <div class="d-flex align-items-center">
                <!--begin::Actions-->
                <button data-toggle="modal" type="button" data-target="#modalGuide" class="btn btn-light font-weight-bold btn-sm">Guide</button>
                <!--end::Actions-->
                <!--begin::Dropdown-->
                <!--end::Dropdown-->
            </div>
            <!--end::Toolbar-->
        </div>
    </div> --}}

    {{-- <div class="separator separator-solid separator-border-2 separator-primary mt-5 mb-5"></div> --}}

    <div class="modal fade" id="modalGuide" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Guide</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <img src="{{asset('media/Flowchart.png')}}" style="width: 100%" alt="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalAnnouncement" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                @if ($business)
                <div class="modal-header table-bs">
                    <h1 class="modal-title">Business Payment</h1>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body table-bs">
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-bordered display" >
                                <thead>
                                    <tr>
                                        <th class="text-center" rowspan="2">#</th>
                                        <th class="text-center" rowspan="2">Business Name</th>
                                        <th class="text-center" rowspan="2">Payment Date</th>
                                        <th class="text-center" colspan="3">Penalty</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">per day</th>
                                        <th class="text-center">day(s)</th>
                                        <th class="text-center">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bs_detail as $i => $item)
                                        @php
                                            $d1 = date_create(date("Y-m-d"));
                                            $d2 = date_create($item->plan_date);
                                            $diff = date_diff($d1, $d2);
                                            $days = $diff->format("%a");
                                            $ppday = $bs_penalty[$item->id_business];
                                        @endphp
                                        <tr>
                                            <td align="center">{{ $i+1 }}</td>
                                            <td>
                                                {{ $bs_name[$item->id_business] }}
                                            </td>
                                            <td align="center">
                                                {{ $item->plan_date }}
                                            </td>
                                            <td align="right">
                                                {{ number_format($ppday) }}
                                            </td>
                                            <td align="center">
                                                {{ $days }}
                                            </td>
                                            <td align="right">
                                                {{ number_format($ppday * $days) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <hr>
                        </div>
                    </div>
                </div>
                @endif
                <div class="modal-header">
                    @php
                        $ann = [];
                        $modal_title = "";
                        $description = "";
                        if(!empty(\Session::get('company_announcement'))){
                            $ann = \Session::get('company_announcement');
                        }

                        if (!empty($ann)){
                            $modal_title = $ann->title;
                            $description = $ann->description;
                        }
                    @endphp
                    <h1 class="modal-title" id="view-title">{!! $modal_title !!}</h1>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div id="view-description">
                                {!! $description !!}
                            </div>
                        </div>
                        <div class="col-12">
                            @if ($div_name == "Operation")
                            <hr>
                            <h3>Crew Location</h3>
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <div id="kt_leaflet_6" style="height:800px;"></div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary btn-sm" data-dismiss="modal">Accept</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalCities" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="label-city"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="list-city"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalPlant" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="label-plant"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="list-plant"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalSell" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="label-sell"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="list-sell"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @if ($div_name == "Operation")
    <div class="modal fade" id="modalCrewLoc" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" id="crewList">

            </div>
        </div>
    </div>
    @endif
@endsection
@section('custom_script')
    <script src='{{asset('theme/assets/plugins/custom/draggable/draggable.bundle.js')}}'></script>
    <script src='{{asset('theme/assets/js/pages/features/cards/draggable.js')}}'></script>
    <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
    <script src="{{ asset('theme/assets/plugins/custom/leaflet/leaflet.bundle.js') }}"></script>
    <script src="{{ url('../node_modules/web3/dist/web3.min.js') }}"></script>
    <script>

        console.log("Run Permission Check:"+Notification.permission);
        if(Notification.permission !== "denied"){
            Notification.requestPermission().then(permission=>{
                console.log(permission);
            })
        }


        function _reset_daily(){
            Swal.fire({
                title: "Are you sure?",
                text: "",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes!"
            }).then(function(result) {
                if (result.value) {
                    $("#btn-sb").click()
                }
            });
        }

        function _list_plants(){
            $("#label-city").text("")
            $("#list-city").html("")
            $.ajax({
                url : "{{ route('home.plants') }}",
                type : "get",
                success : function(response){
                    $("#list-city").html(response)
                    $(".tmz").each(function(){
                        var tmz = Intl.DateTimeFormat().resolvedOptions().timeZone
                        $(this).val(tmz)
                    })
                }
            })
        }

        function _sell_plant(me){
            var plant = $(me).data("plant")
            var id = $(me).data('id')
            $("#label-sell").text("Select city to sell your "+plant)
            $("#list-sell").html("")
            $("#modalSell").modal("show")
            $.ajax({
                url : "{{ route('home.plants.sell_form') }}/"+id,
                type : "get",
                success : function(response){
                    $("#list-sell").html(response)
                    $(".plant-qty").each(function(){
                        $(this).on('keyup change', function(){
                            var qty = $(this).val()
                            var pps = $(this).data('pps')
                            if(pps > 0){
                                if(parseInt(qty) > $(this).attr('max')){
                                    $(this).val($(this).attr('max'))
                                    return Swal.fire("Insuficient quantity", "", "warning")
                                }
                                var head_plant = $(this).parents("div.head-plant")
                                var span_plant = $(head_plant).find("span.total-price")
                                var sum = parseInt(qty) * pps
                                span_plant.text(sum)
                            }
                        })
                    })
                }
            })
        }

        function _select_city(id){
            Swal.fire({
                title: "Are you sure?",
                text: "You can\'t change your selection before reset",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes!"
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url : "{{ route("home.city.store") }}",
                        type : "post",
                        dataType : "json",
                        data : {
                            _token : "{{ csrf_token() }}",
                            city_id : id
                        },
                        beforeSend : function(){
                            KTApp.block('#list-city', {})
                        },
                        success : function (response) {
                            KTApp.unblock('#list-city')
                            $("#modalCities").modal("hide")
                            if(response != -1){
                                if(response > 0){
                                    _show_city()
                                    _load_city_user()
                                    $("#label-energy").text(response)
                                }
                            } else {
                                Swal.fire("You ran out of Energy", "Your energy will be filled back to {{ \Session::get("company_period_start") }} each day at 2am UTC+0", "info")
                            }
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            KTApp.unblock('#list-city')
                        }
                    })
                }
            });
        }

        function _plant_farmed(id){
            $("#label-plant").text("")
            $("#modalPlant").modal("show")
            $.ajax({
                url : "{{ route('home.plants.farmed') }}/"+id,
                type : "get",
                success : function(response){
                    $("#list-plant").html(response)
                }
            })
        }

        function _show_city(){
            $("#label-city").text("Where do you want to go?")
            $.ajax({
                url : "{{ route('home.city') }}",
                type : "get",
                success : function(response){
                    $("#list-city").html(response)
                }
            })
        }

        function _show_city_sell(){
            $("#modalSell").modal('hide')
            _show_city()
        }

        function pad(num, size) {
            num = num.toString();
            while (num.length < size) num = "0" + num;
            return num;
        }

        $("#btn-farm").hide()

        function _cd(end_date){
            var start_date = new Date().getTime()
            var distance = end_date - start_date
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            $("#label-countdown").text("Ripe in " + pad(hours, 2) + ":" + pad(minutes, 2) + ":" + pad(seconds, 2))

            if(distance < 0){
                clearInterval(x)
                $("#label-countdown").text("RIPE!")
                const notification = new Notification("RIPE!", {
                    body: "Hey mate! Your crops are ready to be farmed.",
                    icon: "https://eironjayasejati.com/cypherApp/public/images/basket.png"
                });
                showNotification();
                console.log("RIPE NOTIFICATION");
                $("#btn-farm").show()
            }
        }

        function _countdown(){
            $("#label-countdown").text("Ripe in -")
            $.ajax({
                url : "{{ route('home.plants.countdown') }}",
                type : "get",
                dataType : "json",
                beforeSend : function(){
                    KTApp.block("#div-farm", {})
                },
                success : function(response){
                    if(response.success){
                        var data = response.data
                        $("#img-plant").attr("src", data.img)
                        var end_date = new Date(data.end).getTime()
                        var dnow = data.now
                        var start_date = new Date(data.now).getTime()
                        var distance = end_date - start_date
                        var x = setInterval(function() {
                            distance -= 1000
                            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                            $("#label-countdown").text("Ripe in " + pad(hours, 2) + ":" + pad(minutes, 2) + ":" + pad(seconds, 2))

                            if(distance < 0){
                                clearInterval(x)
                                $("#label-countdown").text("RIPED!")
                                $("#btn-farm").show()
                                const notification = new Notification("RIPE!", {
                                    body: "Hey mate! Your crops are ready to be farmed.",
                                    icon: "https://eironjayasejati.com/cypherApp/public/images/basket.png"
                                });
                                showNotification();
                                console.log("RIPE NOTIFICATIONS");
                            }
                        }, 1000);
                    }
                    KTApp.unblock("#div-farm")
                }
            })
        }

        function _open_city(id){
            $.ajax({
                url : "{{ route('home.city.open') }}/"+id,
                type : "get",
                success : function(response){
                    $("#list-city").html(response)
                    $("#table-plants").DataTable()
                }
            })
        }

        var table_plant = $("#table-plant").DataTable({
            responsive: true,
            columnDefs : [
                {"targets" : [1, 2], "className" : "text-center"}
            ]
        })

        var table_city = $("#table-city").DataTable({
            paging : false,
            bInfo : false,
            ordering : false,
            searching : false,
            responsive: true,
            columnDefs : [
                {"targets" : [1], "className" : "text-center"}
            ]
        })

        function _load_city_user(){
            table_city.clear().draw()
            $.ajax({
                url : "{{ route('home.city.user') }}",
                type : "get",
                dataType : "json",
                beforeSend : function(){
                    KTApp.block('#table-city', {})
                },
                success : function(response){
                    KTApp.unblock('#table-city')
                    for (let i = 0; i < response.length; i++) {
                        var data = response[i]
                        table_city.row.add([
                            data[0],
                            data[1]
                        ]).draw()
                    }
                }
            })
        }

        function _load_plant_user(){
            table_plant.clear().draw()
            $.ajax({
                url : "{{ route('home.plants.table') }}",
                type : "get",
                dataType : "json",
                beforeSend : function(){
                    KTApp.block('#table-plant', {})
                },
                success : function(response){
                    KTApp.unblock('#table-plant')
                    for (let i = 0; i < response.length; i++) {
                        var data = response[i]
                        table_plant.row.add([
                            data['plant'],
                            data['qty'],
                            data['sell']
                        ]).draw()
                    }
                }
            })
        }

        function zoom_join(cb){
            var id = $(cb).data("id")
            var checked = cb.checked
            var _checked = 0
            if(checked){
                _checked = 1
            } else {
                _checked = 0
            }
            var div = $(cb).parents("div.meeting-div")
            var span = div.find("a.meeting-span")
            $.ajax({
                url : "{{ route("mz.join") }}",
                type : "post",
                dataType : "json",
                data : {
                    _token : "{{ csrf_token() }}",
                    id_meeting : id,
                    user_id : {{ \Auth::id() }},
                    checked : _checked
                },
                beforeSend : function(){
                },
                success : function(response){
                    if(response.success){
                        load_meeting()
                    }
                },
                onCompleted : function(){
                    KTApp.unblock("#meeting-show")
                }
            })
        }

        function listCrew(x){
            $.ajax({
                url : "{{ route('crewloc.crew') }}/" + x,
                type : "get",
                cache : false,
                success : function(response){
                    $("#crewList").html(response)
                    $("table.display").DataTable()
                    $("#modalAnnouncement").modal('hide')

                    $("#modalCrewLoc").on('hidden.bs.modal', function(){
                        console.log("hide")
                        $("#modalAnnouncement").modal('show')
                    })
                }
            })
        }

        function load_meeting(){
            KTApp.block("#meeting-show", {})

            $.ajax({
                url : "{{ route("home") }}?t=meeting",
                type : "get",
                success : function(response){
                    $("#meeting-show").html(response)
                }
            })
        }

        ETHAppDeploy = {
            loadEtherium : async () => {
                if(typeof window.ethereum !== "undefined"){
                    // do the auth
                    ETHAppDeploy.web3Provider = ethereum
                    ETHAppDeploy.requestAccount(ethereum)
                } else {
                    Swal.fire("Metamask required", "Please install Metamask!!!", "warning")
                }
            },
            requestAccount: async (ethereum) => {
                ethereum
                    .request({
                        method: 'eth_requestAccounts'
                    })
                    .then((resp) => {
                        $.ajax({
                            url : "{{ route("check.metamask") }}",
                            type : "POST",
                            dataType : "json",
                            data : {
                                _token : "{{ csrf_token() }}",
                                metamask : resp[0],
                            },
                            success : function(result){
                                if(result){
                                    $.ajax({
                                        url : "{{ route("update.metamask") }}",
                                        type : "POST",
                                        dataType : "json",
                                        data : {
                                            _token : "{{ csrf_token() }}",
                                            id : {{ \Auth::id() }},
                                            metamask : resp[0],
                                            type : "store"
                                        },
                                        success : function(result){
                                            if(result){
                                                $("#label-metamask").text(resp[0])
                                                $("#btn-metamask").text('Connected')
                                            }
                                        }
                                    })
                                } else {
                                    Swal.fire("Cannot Connect to Metamask", "Metamask ID is connected to another cypher user", "error")
                                }
                            }
                        })
                    })
                    .catch((err) => {
                        // Some unexpected error.
                        console.log(err);
                    });
            },
        }

        if(typeof window.ethereum !== "undefined"){
                    // do the auth
            window.ethereum.on("accountsChanged", function (accounts){
                $.ajax({
                    url : "{{ route("update.metamask") }}",
                    type : "POST",
                    dataType : "json",
                    data : {
                        _token : "{{ csrf_token() }}",
                        id : {{ \Auth::id() }},
                        type : "disconnect"
                    },
                    success : function(result){
                        if(result){
                            $("#label-metamask").text('-')
                            $("#btn-metamask").html("<i class='fab fa-ethereum'></i>Login to Metamask")
                        }
                    }
                })
            })
        }

        @if(!empty(\Session::get('reset')))
            Swal.fire("Success", "Reset daily success", "success")
        @endif

        @if(!empty(\Session::get('farm')))
            Swal.fire("Success", "Planting success. You can farm in {{ \Session::get('farm') }} minutes", "success")
        @endif

        @if(!empty(\Session::get('riped')))
            _plant_farmed({{ \Session::get('riped') }})
        @endif

        @if(!empty(\Session::get('error')))
            Swal.fire("Error", "{{ \Session::get('error') }}", "error")
        @endif

        @if(!empty(\Session::get('sell')))
            Swal.fire("Success", "{{ \Session::get('sell') }}", "success")
        @endif

        @if(!empty(\Session::get('energy')))
            Swal.fire("You ran out of Energy", "Your energy will be filled back to {{ \Session::get("company_period_start") }} each day at 2am UTC+0", "info")
        @endif

        @if(!empty(\Session::get('open_shop')))
            $("#modalCities").modal("show")
            _open_city({{ \Session::get('open_shop') }})
        @endif

        _countdown()

        _load_plant_user()
        _load_city_user()

        $(document).ready(function(){
            console.log("test")
            var meta_id = "{{ \Auth::user()->metamask_id }}"
            if(meta_id != ""){
                ETHAppDeploy.loadEtherium()
            }

            $("#btn-metamask").click(function(){
                ETHAppDeploy.loadEtherium()
            })

            load_meeting()
            @if(!empty(\Session::get('company_announcement')))
                $("#modalAnnouncement").modal('show')
            @php
                \Session::put('company_announcement', null)
            @endphp
            @endif

            if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
                // true for mobile device
                console.log("mobile device");
                var divmeeting = $("#meetingdiv")
                $("#is-mobile").html(divmeeting)
                $(".table-bs").hide()
                var mdialog = $("#modalAnnouncement").find("div.modal-dialog")
                mdialog.removeClass("modal-xl")
                mdialog.addClass("modal-sm")
            }else{
                // false for not mobile device
                console.log("not mobile device");
            }

            $("table.display").DataTable({
                bInfo:false,
                lengthChange:false,
                searching:false
            })

            // Themes begin
            am4core.useTheme(am4themes_animated);
                    // Themes end

                    // Create chart instance
            var chart = am4core.create("chartdiv", am4charts.XYChart);

            // Create axes
            var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
            dateAxis.renderer.grid.template.location = 0;
            dateAxis.renderer.minGridDistance = 30;

            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

            $("table.Leaderboard").DataTable()

            $.ajax({
                url : "{{ route('activity.log') }}",
                type: "get",
                dataType: "json",
                cache: false,
                success: function (response) {
                    var data = response.data

                    chart.data = data

                    // Create series
                    function createSeries(field, name) {
                        var series = chart.series.push(new am4charts.LineSeries());
                        series.dataFields.valueY = field;
                        series.dataFields.dateX = "date";
                        series.name = name;
                        series.tooltipText = "{dateX}: [b]{valueY}[/] hours";
                        series.strokeWidth = 2;

                        series.smoothing = "monotoneX";

                        var bullet = series.bullets.push(new am4charts.CircleBullet());
                        bullet.circle.stroke = am4core.color("#fff");
                        bullet.circle.strokeWidth = 2;

                        return series;
                    }

                    createSeries("hours", "Session by Hours")

                    chart.legend = new am4charts.Legend();
                    chart.cursor = new am4charts.XYCursor();

                    $("#spinner-div").hide()

                }
            })

            @if ($div_name == "Operation")

                var dt

                function getRandomColor() {
                    var letters = '0123456789ABCDEF';
                    var color = '#';
                    for (var i = 0; i < 6; i++) {
                    color += letters[Math.floor(Math.random() * 16)];
                    }
                    return color;
                }

                var demo6 = function () {
                    // add sample location data
                    var data = [
                        { "loc": [-6.184182276788618, 106.9958928126219], "title": "black" },
                        { "loc": [-6.737256580538595, 108.53869953165832], "title": "blue" },
                    ];

                    // init leaflet map
                    var leaflet = new L.Map('kt_leaflet_6', {
                        zoomSnap : 0.1,
                        minZoom: 5.6,
                    }).setView([-2.232555671751522, 117.63552256391021], 5);
                    // leaflet.setZoom(5.6)


                    leaflet.addLayer(new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'));

                    // add scale layer
                    L.control.scale().addTo(leaflet);

                    // set markers
                    $.ajax({
                        url: "{{ route('crewloc.markers') }}",
                        type: "get",
                        dataType: "json",
                        cache: false,
                        success: function(response){
                            dt = response
                            if (response.success == false) {
                                // Swal.fire(response.messages, response.data, 'error')
                            } else {
                                var data = response.data
                                data.forEach(function (item) {
                                    var mar_col = ['danger', 'success', 'primary', 'info', 'warning']
                                    var key = Math.floor(Math.random() * mar_col.length)
                                    var class_svg = mar_col[key]
                                    // set custom SVG icon marker
                                    var leafletIcon = L.divIcon({
                                        html: `<span class="svg-icon svg-icon-`+class_svg+` svg-icon-3x"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="24" width="24" height="0"/><path d="M5,10.5 C5,6 8,3 12.5,3 C17,3 20,6.75 20,10.5 C20,12.8325623 17.8236613,16.03566 13.470984,20.1092932 C12.9154018,20.6292577 12.0585054,20.6508331 11.4774555,20.1594925 C7.15915182,16.5078313 5,13.2880005 5,10.5 Z M12.5,12 C13.8807119,12 15,10.8807119 15,9.5 C15,8.11928813 13.8807119,7 12.5,7 C11.1192881,7 10,8.11928813 10,9.5 C10,10.8807119 11.1192881,12 12.5,12 Z" fill="#000000" fill-rule="nonzero"/></g></svg></span>`,
                                        bgPos: [10, 10],
                                        iconAnchor: [20, 37],
                                        popupAnchor: [0, -37],
                                        className: 'leaflet-marker'
                                    });
                                    var marker = L.marker(item.loc, { icon: leafletIcon }).addTo(leaflet);
                                    marker.bindPopup(item.title, { closeButton: false });
                                })
                            }
                        }
                    })

                    $("#modalAnnouncement").on('shown.bs.modal', function(){
                        leaflet.invalidateSize();
                    })
                }

                demo6()
            @endif
        })
    </script>
@endsection
