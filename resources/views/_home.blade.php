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

<div class="modal fade" id="modalHtp" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">How to Play</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex">
                            <div class="timeline timeline-1">
                                <div class="timeline-sep bg-primary-opacity-20"></div>
                                <div class="timeline-item">
                                    <div class="timeline-label text-dark">Grow </div>
                                    <div class="timeline-badge">
                                        <i class="flaticon-multimedia-3 text-primary "></i>
                                    </div>
                                    <div class="timeline-content text-dark font-weight-normal">
                                        something and wait until you can farm it, and then grow another one.
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-label text-dark">Sun flower </div>
                                    <div class="timeline-badge">
                                        <i class="flaticon-shopping-basket text-success "></i>
                                    </div>
                                    <div class="timeline-content text-dark font-weight-normal">
                                        is always plantable and free. Make some early money with it. A free seed can be claimed once a day on your Energy panel.
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-label text-dark">Hire </div>
                                    <div class="timeline-badge">
                                        <i class="flaticon-users-1 text-warning "></i>
                                    </div>
                                    <div class="timeline-content text-dark font-weight-normal">
                                         an adventurer, the first one is free! Go hunt with it and earn some Nc and seed.
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-label text-dark">Caravan</div>
                                    <div class="timeline-badge">
                                        <i class="flaticon2-shopping-cart text-danger "></i>
                                    </div>
                                    <div class="timeline-content text-dark font-weight-normal">
                                        If you have enough money, buy a Caravan so you can travel without energy.
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-label text-dark">Care</div>
                                    <div class="timeline-badge">
                                        <i class="flaticon2-analytics-1 text-primary "></i>
                                    </div>
                                    <div class="timeline-content text-dark font-weight-normal">
                                        Watch out for bugs, opportunity to water your plant, and even a fairy to bless it!
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-label text-dark">Check</div>
                                    <div class="timeline-badge">
                                        <i class="flaticon2-box-1 text-success "></i>
                                    </div>
                                    <div class="timeline-content text-dark font-weight-normal">
                                         your bag for your ready-to-sell goods.
                                    </div>
                                </div>

                                <div class="timeline-item">
                                    <div class="timeline-label text-dark">Teleport </div>
                                    <div class="timeline-badge">
                                        <i class="flaticon2-map text-primary "></i>
                                    </div>
                                    <div class="timeline-content text-dark font-weight-normal">
                                        to another town, you can't sell at your hometown. Or use the caravan.
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-label text-dark">Visit Shop </div>
                                    <div class="timeline-badge">
                                        <i class="flaticon2-search text-success "></i>
                                    </div>
                                    <div class="timeline-content text-dark font-weight-normal">
                                        To get the price of goods there. On your ACTIVITY window, visit the SHOP according to the goods you want to sell.
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-label text-dark">Travel </div>
                                    <div class="timeline-badge">
                                        <i class="flaticon2-line-chart text-warning "></i>
                                    </div>
                                    <div class="timeline-content text-dark font-weight-normal">
                                         to another town. If the price there doesn't satify you, you can move to another town.
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-label text-dark">Spend</div>
                                    <div class="timeline-badge">
                                        <i class="flaticon-confetti text-danger "></i>
                                    </div>
                                    <div class="timeline-content text-dark font-weight-normal">
                                         some bucks at the tavern for more energy and treat your friend a beer.
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-label text-dark">Aim </div>
                                    <div class="timeline-badge">
                                        <i class="flaticon-medal text-primary "></i>
                                    </div>
                                    <div class="timeline-content text-dark font-weight-normal">
                                        for the Leaderboard high-score, and GLHF!
                                    </div>
                                </div>
                            </div>
                            <!--<p><ul>-->
                            <!--    <li>Grow something and wait until you can farm it, and then grow another one.</li>-->
                            <!--    <li>Sun flower is always plantable and free. Make some early money with it.</li>-->
                            <!--    <li>Hire an adventurer, the first one is free! Go hunt with it and earn some Nc and seed.</li>-->
                            <!--    <li>If you have enough money, buy a Caravan so you can travel without energy.</li>-->
                            <!--    <li>Watch out for bugs, opportunity to water your plant, and even a fairy to bless it!</li>-->
                            <!--    <li>Check your bag for your ready-to-sell goods.</li>-->
                            <!--    <li>Pick another town to go, you can't sell at your hometown.</li>-->
                            <!--    <li>On your ACTIVITY window, you can visit the SHOP according to the goods you want to sell.</li>-->
                            <!--    <li>If you think the price is good, you can sell there. If not, you can move to another town.</li>-->
                            <!--    <li>Spend some bucks at the bar for more energy and treat your friend a beer.</li>-->
                            <!--    <li>Aim for the Leaderboard high-score, and GLHF! </li>-->
                            <!--</ul></p>-->
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 col-sm-12 mt-3 min-h-350px h-md-700px">
        <div class="card card-custom gutter-b card-stretch h-100 text-white bgi-no-repeat bgi-position-center" id="menu-img">
            <div class="card-body">
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-12 mt-3 min-h-350px h-md-700px">
        <div class="card card-custom gutter-b card-stretch h-100">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex font-weight-bolder justify-content-center align-items-center bg-white p-3 rounded text-dark">
                            <label>Xenos : 0 | </label>
                            <label>&nbsp;Crystal : 0 | </label>
                            <label>&nbsp;Gold : 0 | </label>
                            <label>&nbsp;<span id="menu-now"></span></label>
                        </div>
                    </div>
                    <div class="col-12 mt-3">
                        <div id="menu-view"></div>
                    </div>
                </div>
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
                    </div>
                </div>
                <!--end::Header-->

                <!--begin::Body-->
                <div class="card-body pt-0">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="{{ route('home.reset') }}" method="post">
                                @csrf
                                <button type="submit" id="btn-sb" style="display: none">
                                </button>
                                <button type="button" onclick="_reset_daily()" class="btn btn-warning btn-sm">
                                    Daily Manual Reset
                                </button>
                            </form>
                        </div>
                        <div class="col-md-12 mt-5">
                            <form action="{{ route('home.generate.char') }}" method="post">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm">
                                    Generate 10 Characters
                                </button>
                            </form>
                        </div>
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
            <div class="card card-custom gutter-b card-stretch bgi-no-repeat" style="background-position: right top; background-size: 120px; background-image: url({{ asset("assets/media/svg/shapes/abstract-4.svg") }})">
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
            <div class="card card-custom gutter-b card-stretch bgi-no-repeat" style="background-position: right top; background-size: 120px; background-image: url({{ asset("assets/media/svg/shapes/abstract-2.svg") }})">
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
            <div class="card card-custom gutter-b card-stretch bgi-no-repeat" style="background-position: right top; background-size: 120px; background-image: url({{ asset("assets/media/svg/shapes/abstract-1.svg") }})">
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
            <div class="card card-custom gutter-b card-stretch bgi-no-repeat" style="background-position: right top; background-size: 120px; background-image: url({{ asset("assets/media/svg/shapes/abstract-3.svg") }})">
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
    <div id="view-script"></div>
    <script>

        console.log("Run Permission Check:"+Notification.permission);
        if(Notification.permission !== "denied"){
            Notification.requestPermission().then(permission=>{
                console.log(permission);
            })
        }

        function _menu(param){
            $.ajax({
                url : "{{ route("home") }}?v="+param,
                type : "get",
                dataType : "json",
                beforeSend : function(){
                    // do the animation
                },
                success : function(response){
                    $("#menu-now").text(response.title)
                    $("#menu-view").html(response.view)
                    $("#view-script").html(response.img)
                    console.log($("#view-script").html())
                    // $("table.display").DataTable()
                }
            })
        }

        function pad(num, size) {
            num = num.toString();
            while (num.length < size) num = "0" + num;
            return num;
        }

        // notif section

        @if(!empty(\Session::get("first_login")))
            $("#modalHtp").modal("show")
            @php
                \Session::put("first_login", 0)
            @endphp
        @endif

        $(document).ready(function(){
            // audioElement.play();

            _menu("town")

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
        })
    </script>
@endsection
