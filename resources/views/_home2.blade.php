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
        <div class="col-md-4 col-sm-4">
            <div class="card card-custom gutter-b card-stretch border border-primary border-5">
                <div class="card-body">
                    <div class="row bg-primary p-5">
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
                        <div class="col-12">
                            <div class="d-flex justify-content-around bg-primary">
                                <img src='https://eironjayasejati.com/cypherApp/public_html/media/asset/byr_logo.png' height='125px'  />
                                <div class="d-flex flex-column justify-content-between">
                                    <button type="button" class="btn btn-sm btn-facebook h-25" data-toggle="modal" data-target="#modalHtp">
                                        <i class="fa fa-question-circle"></i>
                                        How to Play
                                    </button>
                                    <button type="button" class="btn btn-sm btn-facebook h-25" id="btn-metamask">
                                        <i class="fab fa-ethereum"></i>Whitelist
                                    </button>
                                    <button type="button" onclick="javascript:location.href='https://www.ageofreign.com'" class="btn btn-sm btn-facebook h-25">
                                        <i class="fa fa-link"></i>
                                        AoR Site
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card card-custom gutter-b card-stretch bgi-no-repeat" style="background-position: right top; background-size: 120px; background-image: url(assets/media/svg/shapes/abstract-2.svg)">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h2>Norus Crystal <img src='images/n_c_icon.png' height='40px' /></h2><h2> {{ number_format(\Auth::user()->do_code) }} Nc  </h2>
                    </div>
                    <div class="d-flex align-items-center h-50">
                        <span>This currency will be converted to AoR P2E game. </span>
                        <a href="{{ route('treasury.history', ['type' => 'crystal', 'id' => base64_encode(\Auth::id())]) }}" class="btn ml-3 py-5 btn-outline-primary">View your Ledger</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card card-custom gutter-b card-stretch bgi-no-repeat" style="background-position: right top; background-size: 120px; background-image: url(assets/media/svg/shapes/abstract-2.svg)">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h2>Energy</h2><h2> <span id="label-energy">{{ number_format(\Auth::user()->attend_code) }}</span> / {{ Session::get("company_period_end") }} <i class="fas fa-bolt font-size-h2 text-warning h-40px"></i> </h2>
                    </div>
                    <div class="d-flex align-items-center h-50">
                        <span>Re-fill everyday for {{ Session::get("company_period_start") }} <i class="fas fa-bolt font-size-p text-warning"></i> with max of {{ Session::get("company_period_end") }}.</span>
                        @if (\Auth::user()->daily_seed > 0)
                        <button type="button" id="btn-claim-daily" class="btn btn-outline-danger btn-icon icon-2x ml-3 pulse pulse-danger">
                            <i class="fas fa-box icon-2x"></i>
                            <span class="pulse-ring"></span>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-sm-12">
            <div class="card card-custom gutter-b card-stretch bgi-no-repeat" style="background-position: right top; background-size: 120px; background-image: url(assets/media/svg/shapes/abstract-2.svg)">
                @if ($currentCity->id == Auth::user()->home_id)
                <div class="card-header">
                    <h3 class="card-title"><i class="fa fa-home mr-3 icon-3x text-primary"></i> Hometown</h3>
                </div>
                @endif
                <div class="card-body">
                    <div class="row" id="div-farm">
                        @if ($currentCity->id == Auth::user()->home_id)
                            @if (empty(\Auth::user()->item_ripe_id))
                                <div class="col-12 text-center bgi-no-repeat" style="top: 50%; background-image:url('images/land.png'); background-size: 100px; background-position: center bottom;">
                                    <div class="symbol symbol-150 mr-3">
                                        <button class="btn btn-outline-primary btn-block p-md-20" onclick="_list_plants()" data-toggle="modal" data-target="#modalCities">
                                            <img src='images/planted.png' height='40px' />
                                            Grow something
                                        </button>
                                    </div>
                                </div>
                            @else

                                <div class="col-12 text-center bgi-no-repeat" style="top: 50%; background-image:url('images/land.png'); background-size: 100px; background-position: center bottom;">
                                    <img src="{{ asset("assets/images/event_bug.png") }}" class="w-80px position-absolute mt-15 cursor-pointer" style="z-index: 99; display: none;" data-isEvent="{{ $currentEvent }}" id="event" onclick="_trigger_event({{ $currentEvent }})">
                                    <div class="symbol symbol-150 mr-3" >
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
                                    <div id="btn-farm" style="display: none">
                                        <form action="{{ route("home.plants.farm") }}" method="post">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm px-20">Farm!</button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @else
                        <div class="col-12">
                            <table class="table table-bordered table-hover table-striped" id="table-city">
                                <thead>
                                    <tr>
                                        <th>My Home</th>
                                        <th>Crops</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <img alt='{{$mycity->name}}' src='{{ $mycity->address }}' width='100px'>&nbsp;&nbsp;{{$mycity->name}}
                                        </td>
                                        <td>
                                            @if (!empty(\Auth::user()->item_ripe_id))
                                            <div class="col-12 text-center" style="top: 50%">
                                                <div class="symbol symbol-50 mr-3">
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
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2">
                                            <form action="{{ route("home.city.back") }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-primary btn-block">
                                                    Go back Home
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!--<div class="col-12 text-center" style="top: 50%">-->
                        <!--    <div class="container">-->
                        <!--        <img src="{{ $currentCity->address }}" alt="" class="w-100">-->
                        <!--        <div style="position: absolute; right: 43px; bottom: 7px;">-->
                        <!--            <span class="label label-inline label-lg font-weight-bold label-white font-size-h2 py-5 px-5">-->
                        <!--                {{ $currentCity->name }}-->
                        <!--            </span>-->
                        <!--        </div>-->
                        <!--    </div>-->
                        <!--</div>-->
                        @endif
                    </div>
                </div>
                <div class="card-footer"></div>
                <div class="card-header">
                    <h3 class="card-title">
                        <img src='images/basket.png' height='40px' />
                        Your Bag
                    </h3>
                    <div class="card-toolbar">

                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <ul class="nav nav-tabs nav-tabs-line">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#products">Products</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#seeds">Seeds</a>
                                </li>
                            </ul>
                            <div class="tab-content mt-5" id="myTabContent">
                                <div class="tab-pane fade show active" id="products" role="tabpanel" aria-labelledby="products">
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
                                <div class="tab-pane fade" id="seeds" role="tabpanel" aria-labelledby="seeds">
                                    <table class="table table-bordered table-hover table-striped" id="table-seeds">
                                        <thead>
                                            <tr>
                                                <th>Seeds</th>
                                                <th>Quantity</th>
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
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="card card-custom gutter-b card-stretch bgi-no-repeat" style="background-position: right top; background-size: 120px; background-image: url(assets/media/svg/shapes/abstract-2.svg)">
                <div class="card-header">
                    <h3 class="card-title">
                        <img src='images/map.png' height='40px' />
                        Your Current Location
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 text-center" style="top: 50%">
                            <div class="container">
                                <img src="{{ $currentCity->address }}" alt="" class="w-100">
                                <div style="position: absolute; right: 43px; bottom: 7px;">
                                    <span class="label label-inline label-lg font-weight-bold label-white font-size-h2 py-5 px-5">
                                        {{ $currentCity->name }} &nbsp;
                                        <span class="font-size-h5">
                                            (<img src='assets/byr/danger.png' height='20px' />level {{ $currentCity->longitude }} - {{ $currentCity->latitude }} )
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-10">
                        <div class="col-12">
                            <h3>Activities</h3>
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="card card-custom gutter-b card-stretch" style="background: linear-gradient(35deg, #E1F0FF  50%, #f1f1f1 50%)">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center flex-lg-wrap flex-xl-nowrap justify-content-between">
                                                <div class="d-flex flex-column mr-5">
                                                    <span class="h6 text-dark mb-5">
                                                    Shops
                                                    </span>
                                                    <p class="text-dark-50">
                                                        You must visit the
                                                        shop before selling
                                                    </p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <button type="button" onclick="_open_city({{ $currentCity->id }})" data-target="#modalCities" data-toggle="modal" class="btn font-weight-bolder text-uppercase btn-primary">
                                                        Visit
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="card card-custom gutter-b card-stretch" style="background: linear-gradient(35deg, #fff4e1  50%, #f1f1f1 50%)">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center flex-lg-wrap flex-xl-nowrap justify-content-between">
                                                <div class="d-flex flex-column mr-5">
                                                    <span class="h6 text-dark mb-5">
                                                    Teleport
                                                    </span>
                                                    <p class="text-dark-50">
                                                        Go to another town
                                                        via teleport gate
                                                    </p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <button type="button" onclick="_show_city()" data-target="#modalCities" data-toggle="modal" class="btn font-weight-bolder text-uppercase btn-primary">
                                                        Go (1 <i class="fas fa-bolt text-warning"></i>)
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <div class="card card-custom gutter-b card-stretch" style="background: linear-gradient(35deg, #ffe1e1  50%, #f1f1f1 50%)">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center flex-lg-wrap flex-xl-nowrap justify-content-between">
                                                <div class="d-flex flex-column mr-5">
                                                    <span class="h6 text-dark mb-5">
                                                    Tavern
                                                    </span>
                                                    <p class="text-dark-50">
                                                        Have a drink or buy
                                                        one for your friend
                                                    </p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <button type="button" data-toggle="modal" data-target="#modalTavern" class="btn font-weight-bolder text-uppercase btn-primary">
                                                        Visit
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if ($currentCity->id != Auth::user()->home_id)
                                <div class="col-md-6 col-sm-12">
                                    <div class="card card-custom gutter-b card-stretch" style="background: linear-gradient(35deg, #b1dbc1  50%, #f1f1f1 50%)">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center flex-lg-wrap flex-xl-nowrap justify-content-between">
                                                <div class="d-flex flex-column mr-5">
                                                    <span class="h6 text-dark mb-5">
                                                    Move here
                                                    </span>
                                                    <p class="text-dark-50">
                                                        Change your hometown to this place.
                                                    </p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <form action="{{ route('home.city.move') }}" method="post">
                                                        <input type="hidden" name="wh_id" value="{{ $currentCity->id }}">
                                                        <button type="submit"  class="btn font-weight-bolder text-uppercase btn-warning">
                                                            Move <br>(-10 Nc)
                                                        </button>
                                                        {{-- <button type="submit" class="btn btn-warning btn-sm">
                                                            Move Home here (-10 Nc)
                                                        </button> --}}
                                                        @csrf
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="col-md-6 col-sm-12">
                                    <div class="card card-custom gutter-b card-stretch" style="background: linear-gradient(35deg, #FFD2FC  50%, #f1f1f1 50%)">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center flex-lg-wrap flex-xl-nowrap justify-content-between">
                                                <div class="d-flex flex-column mr-5">
                                                    <span class="h6 text-dark mb-5">
                                                    Town Hall
                                                    </span>
                                                    <p class="text-dark-50">
                                                        Where fine adventurers gathers around
                                                    </p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <button type="button" data-toggle="modal" data-target="#modalGuild" class="btn font-weight-bolder text-uppercase btn-primary">
                                                        Visit
                                                    </button>
                                                </div>
                                                <div class="modal fade" id="modalGuild" tabindex="-1" role="dialog" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="label-city"></h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <i aria-hidden="true" class="ki ki-close"></i>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-12 text-center">
                                                                        <img src="{{ str_replace("public", "public_html", asset("media/asset/cities/townhall_banner.png")) }}" class="w-100" alt="">
                                                                    </div>
                                                                    <div class="col-12 my-10 text-center">
                                                                        <span class="font-weight-bold font-size-h3">
                                                                            Town Hall
                                                                        </span>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <div class="row mb-10">
                                                                            <div class="col-md-6 col-sm-12">
                                                                                <div class="card card-custom gutter-b card-stretch bg-warning">
                                                                                    <div class="card-body d-flex align-items-center">
                                                                                        <img src="{{ asset("assets/media/svg/avatars/029-boy-13.svg") }}" class="align-self-end h-100px" alt="">
                                                                                        <div class="d-flex flex-column flex-grow-1">
                                                                                            <span class="font-weight-bolder text-white">
                                                                                                Good day,
                                                                                            </span>
                                                                                            <span class="font-weight-bolder text-white">
                                                                                                I am the support officer here.<br />
                                                                                                What can I help you with?
                                                                                            </span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6 col-sm-12">
                                                                                <div class="card card-custom gutter-b card-stretch border border-4 border-primary">
                                                                                    <div class="card-body d-flex justify-content-center align-items-center">
                                                                                        @if (count($roster) == 5)
                                                                                        <span>Sorry mate, You alread have 5 Roster. Dismiss 1 so you can hire another adventurer</span>
                                                                                        @else
                                                                                        <button type="button" id="btn-find-char" onclick="_find_char(this)" class="btn btn-primary">
                                                                                            <i class="fa fa-search"></i> Look for Adventurers
                                                                                        </button>
                                                                                        <div class="row">
                                                                                            <div class="col-md-12">
                                                                                                <div id="char-div"></div>
                                                                                            </div>
                                                                                        </div>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            </div>
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
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            {{-- <table class="table table-bordered table-hover table-striped" id="table-city">
                                <thead>
                                    <tr>
                                        <th>Current town</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <img alt='{{$currentCity->name}}' src='{{ $currentCity->address }}' width='100px'>&nbsp;&nbsp;{{$currentCity->name}}
                                        </td>
                                        <td>
                                            <button type="button" onclick="_open_city({{ $currentCity->id }})" data-target="#modalCities" data-toggle="modal" class="btn btn-primary btn-sm">Visit Shops</button>
                                            <br>
                                            @if ($currentCity->id != Auth::user()->home_id)
                                                <form action="{{ route('home.city.move') }}" method="post">
                                                    <input type="hidden" name="wh_id" value="{{ $currentCity->id }}">
                                                    <input type="hidden" name="amount" value="10">
                                                    <button type="submit" class="btn btn-warning btn-sm">
                                                        Move Home here (10Nc)
                                                    </button>
                                                    @csrf
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2">
                                            <button type="button" class="btn btn-primary btn-block" onclick="_show_city()" data-target="#modalCities" data-toggle="modal">
                                                Go to another Town ( 1 <i class="fas fa-bolt"></i>)
                                            </button>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table> --}}
                        </div>
                    </div>
                </div>
                <div class="card-footer"></div>
                @if ($currentCity->id != Auth::user()->home_id)
                <!--<div class="card-body">-->
                <!--    <div class="row">-->
                <!--        <div class="col-12 text-center" style="top: 50%">-->
                <!--            <div class="container">-->
                <!--                <img src="{{ $currentCity->address }}" alt="" class="w-100">-->
                <!--                <div style="position: absolute; right: 43px; bottom: 7px;">-->
                <!--                    <span class="label label-inline label-lg font-weight-bold label-white font-size-h2 py-5 px-5">-->
                <!--                        {{ $currentCity->name }}-->
                <!--                    </span>-->
                <!--                </div>-->
                <!--            </div>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->
                @endif
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="card card-custom gutter-b card-stretch card-stretch-half bgi-no-repeat" style="background-position: right top; background-size: 120px; background-image: url(assets/media/svg/shapes/abstract-2.svg)">
                <div class="card-header">
                    <h3 class="card-title">
                        <img src="{{ asset("assets/byr/caravan.png") }}" class="h-40px" alt=""> Caravan
                    </h3>
                </div>
                <div class="card-body">
                    @if (\Auth::user()->own_caravan == 0)
                    <div class="d-flex align-items-center justify-content-center h-100">
                        <form action="{{ route('home.caravan.buy') }}" id="form-caravan" method="post">
                            @csrf
                            <button type="button" id="btn-caravan" class="btn  btn-primary py-5">
                                <img src="{{ asset("assets/byr/horse.png") }}" class="h-40px" alt="">
                                Buy a Caravan (200 Nc)
                            </button>
                        </form>
                    </div>
                    @else
                        @if (\Auth::user()->roster_driver == 0)
                        <div class="d-flex align-items-center justify-content-center h-100">
                            <div class="border border-primary border-3 p-5 d-flex flex-column h-50 justify-content-center">
                                <h3>Caravan Detail</h3>
                                <table>
                                    <tr>
                                        <th>Trips Left</th>
                                        <td>:</td>
                                        <td>N/A</td>
                                    </tr>
                                    <tr>
                                        <th>Travel Time</th>
                                        <td>:</td>
                                        <td>N/A</td>
                                    </tr>
                                    <tr>
                                        <th>Success Rate</th>
                                        <td>:</td>
                                        <td>N/A</td>
                                    </tr>
                                </table>
                            </div>
                            <button type="button" class="btn btn-primary p-5 h-50" data-toggle="modal" data-target="#modalRoster" onclick="list_roster('driver')">
                                <img src="{{ asset("assets/byr/driver.png") }}" class="h-40px" alt="">
                                <br>Pick a Driver <br> from your Roster
                            </button>
                        </div>
                        @else
                        <div class="d-flex flex-column h-100 align-items-center">
                            <div class="d-flex align-items-center justify-content-center h-100">
                                <div class="border border-primary border-3 p-5 d-flex flex-column h-50 justify-content-center">
                                    <h3>Caravan Detail</h3>
                                    @php
                                        $notes_driver = json_decode($driver_caravan->notes, true);
                                    @endphp
                                    <table>
                                        <tr>
                                            <th>Trips Left</th>
                                            <td>:</td>
                                            <td>{{ Auth::user()->trip_credit }}/{{ $notes_driver['vit'] }}</td>
                                        </tr>
                                        <tr>
                                            <th>Travel Time</th>
                                            <td>:</td>
                                            <td>{{ (30 - $notes_driver['spd']) }} seconds</td>
                                        </tr>
                                        <tr>
                                            <th>Success Rate</th>
                                            <td>:</td>
                                            <td>{{ (90 + $notes_driver['luc']) }}%</td>
                                        </tr>
                                    </table>
                                </div>
                                <button type="button" class="btn  btn-primary p-5 h-50">
                                    <img src="{{ asset("assets/byr/driver.png") }}" class="h-40px" alt="">
                                    <br> {{ $driver_caravan->name }}
                                </button>
                            </div>
                            @if (empty(\Auth::user()->trip_destination))
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTrip" onclick="_trip_location()">
                                Go somewhere by Caravan
                            </button>
                            @else
                            <span id="text-cd-caravan">-- seconds</span>
                            <div class="row w-75" id="pg-div">
                                <div class="col-md-12">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped bg-success" id="pg-trip" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                            <form action="{{ route('home.trip.checkin') }}" method="post" id="form-check-in" style="display: none">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    Check-in on town's gate
                                </button>
                            </form>
                            @endif
                            @if (\Auth::user()->trip_credit == $notes_driver['vit'])
                                <button type="button" class="btn btn-warning mt-5" data-toggle="modal" data-target="#modalRoster" {{ (\Auth::user()->trip_credit == $notes_driver['vit']) ? "onclick=list_roster('driver')" : "" }}>
                                    Change Driver
                                </button>
                            @endif
                        </div>
                        <div class="modal fade" id="modalTrip" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title">Select Destination</h1>
                                        <button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
                                    </div>
                                    <div class="modal-body">
                                        <div id="trip-town-list"></div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light-primary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endif
                </div>
            </div>
            <div class="card card-custom gutter-b card-stretch card-stretch-half bgi-no-repeat" style="background-position: right top; background-size: 120px; background-image: url(assets/media/svg/shapes/abstract-2.svg)">
                <div class="card-header">
                    <h3 class="card-title">
                        <img src="{{ asset("assets/byr/knight.png") }}" class="h-40px" alt=""> Champion
                    </h3>
                </div>
                <div class="card-body">
                    @if (\Auth::user()->roster_champion == 0)
                    <div class="d-flex align-items-center justify-content-center h-100">
                        <button type="button" class="btn  btn-outline-primary py-5" data-toggle="modal" data-target="#modalRoster" onclick="list_roster('champion')">
                            <img src="{{ asset("assets/byr/knight.png") }}" class="h-40px" alt="">
                            Pick a Champion
                        </button>
                    </div>
                    @else
                    <div class="col-md-12 mb-5 border border-2 border-primary p-4">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center">
                                @php
                                    $notes = json_decode($champion->notes, true);
                                    $spec = json_decode($champion->specification, true);
                                @endphp
                                <div class="symbol symbol-100 symbol-circle mr-3">
                                    <img alt="Pic" src="{{ asset("characters/images/$champion->picture") }}"/>
                                </div>
                                <div class="d-flex flex-column mr-3">
                                    <span class="font-weight-bolder font-size-h4">{{ $champion->name }}</span>
                                    <div class="d-flex justify-content-between">
                                        <table>
                                            <tr><td>AGE</td> <td>:</td> <td>{{ $champion->price2 }}</td></tr>
                                            <tr><td>VIT</td> <td>:</td> <td>{{ $notes['vit'] }}</td></tr>
                                            <tr><td>SPD</td> <td>:</td> <td>{{ $notes['spd'] }}</td></tr>
                                            <tr><td>LUC</td> <td>:</td> <td>{{ $notes['luc'] }}</td></tr>
                                        </table>
                                        <table>
                                            <tr>
                                                <td>
                                                    <button type="button" class="btn btn-outline-primary btn-sm" data-container="body" data-toggle="popover" data-placement="top" data-content="Health Points. 1 Point in HP = 10 Health in battle">
                                                        <img src='images/hp.png' height='20px' />
                                                        Hp : {{ $spec['hp'] }}
                                                    </button>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-outline-danger btn-sm" data-container="body" data-toggle="popover" data-placement="top" data-content="Strength. Raw damage in battle.">
                                                        <img src='images/str.png' height='20px' />
                                                        Str : {{ $spec['str'] }}
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <button type="button" class="btn btn-outline-success btn-sm" data-container="body" data-toggle="popover" data-placement="bottom" data-content="Agility. Determines which side moves first.">
                                                        <img src='images/agi.png' height='20px' />
                                                        Agi : {{ $spec['agi'] }}
                                                    </button>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-outline-warning btn-sm" data-container="body" data-toggle="popover" data-placement="bottom" data-content="Ki. Additional damage for a certain moveset.">
                                                        <img src='images/ki.png' height='20px' />
                                                        Ki : {{ $spec['ki'] }}
                                                    </button>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-column justify-content-around">
                                @php
                                    $last_hunt = \Auth::user()->last_hunt;
                                    $now = date("Y-m-d H:i:s");
                                    $resting = 0;
                                    $rest = 5;
                                    if(!empty($last_hunt)){
                                        $diff = strtotime($now) - strtotime($last_hunt);
                                        $m = $diff / 60;
                                        if($m <= 5){
                                            $resting = 1;
                                            $rest -= $m;
                                        }
                                    }
                                @endphp
                                @if (!empty(\Auth::user()->last_hunt) && $resting)
                                    <button type="button" class="btn w-100px btn-success btn-sm mb-3" data-container="body" data-toggle="popover" data-placement="top" data-content="Resting for {{ round($rest, 2) }} minutes. Refresh the browser to check the timer."><i class="fa fa-bed"></i> Resting..</button>
                                @else
                                    @if ($champion->price2 <= $champion->price)
                                    <button type="button" class="btn w-100px btn-primary btn-sm mb-3" data-toggle="modal" data-target="#modalTrain">
                                        Train
                                    </button>
                                    @endif
                                    <button type="button" class="btn w-100px btn-google btn-sm mb-3" onclick="_hunt()">Hunt</button>
                                    <button type="button" class="btn w-100px btn-facebook btn-sm" data-toggle="modal" data-target="#modalRoster" onclick="list_roster('champion')">Change</button>
                                @endif
                            </div>
                            <div class="modal fade" id="modalTrain" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title">Train you champion</h1>
                                            <button class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
                                        </div>
                                        <form action="{{ route("home.train.char") }}" method="post">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6 col-sm-12 text-center">
                                                        <button type="submit" name="train" value="punch" class="btn btn-primary p-5">
                                                            Punching Bag <br> (STR++, HP+, KI-)
                                                        </button>
                                                    </div>
                                                    <div class="col-md-6 col-sm-12 text-center">
                                                        <button type="submit" name="train" value="run" class="btn btn-success p-5">
                                                            Running <br> (AGI++, STR+, HP-)
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="row mt-5">
                                                    <div class="col-md-6 col-sm-12 text-center">
                                                        <button type="submit" name="train" value="meditate" class="btn btn-info p-5">
                                                            Meditate <br> (KI++, HP+, STR-)
                                                        </button>
                                                    </div>
                                                    <div class="col-md-6 col-sm-12 text-center">
                                                        <button type="submit" name="train" value="weight" class="btn btn-google p-5">
                                                            Weight Lift <br> (HP++, STR+, AGI-)
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light-primary" data-dismiss="modal">Close</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="modalHunt" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <form action="{{ route("home.hunt.done") }}" method="post">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-12 text-center">
                                                        <h3 class="font-weigh-bolder">Hunting Report</h3>
                                                    </div>
                                                </div>
                                                <div id="hunt-report-div">

                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-sm-12">
            <div class="card card-custom gutter-b card-stretch bgi-no-repeat" style="background-position: right top; background-size: 120px; background-image: url(assets/media/svg/shapes/abstract-2.svg)">
                <div class="card-header">
                    <h3 class="card-title">Roster</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if(count($roster) == 0)
                        <div class="d-flex justify-content-center">
                            <span>No adventurer hired. Go to your local Guild and hire some.</span>
                        </div>
                        @endif
                        @php
                            $total_wage = 0;
                        @endphp
                        @foreach ($roster as $char)
                            @php
                                $notes = json_decode($char->notes, true);
                                $spec = json_decode($char->specification, true);
                                $amount = $char->wage_day;
                                if($char->conversion == 1){
                                    $amount = 0;
                                }
                                $total_wage += $amount;
                            @endphp
                            <div class="col-md-12 mb-5 border border-2 border-primary p-4">
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-100 symbol-circle mr-3">
                                            <img alt="Pic" src="{{ asset("characters/images/$char->picture") }}"/>
                                        </div>
                                        <div class="d-flex flex-column mr-3">
                                            <span class="font-weight-bolder font-size-h4">{{ $char->name }}</span>
                                            <div class="d-flex justify-content-between">
                                                <table>
                                                    <tr><td>AGE</td> <td>:</td> <td>{{ $char->price2 }}</td></tr>
                                                    <tr><td>VIT</td> <td>:</td> <td>{{ $notes['vit'] }}</td></tr>
                                                    <tr><td>SPD</td> <td>:</td> <td>{{ $notes['spd'] }}</td></tr>
                                                    <tr><td>LUC</td> <td>:</td> <td>{{ $notes['luc'] }}</td></tr>
                                                </table>
                                                <table>
                                                    <tr>
                                                        <td>
                                                            <button type="button" class="btn btn-outline-primary btn-sm" data-container="body" data-toggle="popover" data-placement="top" data-content="Health Points. 1 Point in HP = 10 Health in battle">
                                                                <img src='images/hp.png' height='20px' />
                                                                Hp : {{ $spec['hp'] }}
                                                            </button>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-outline-danger btn-sm" data-container="body" data-toggle="popover" data-placement="top" data-content="Strength. Raw damage in battle.">
                                                                <img src='images/str.png' height='20px' />
                                                                Str : {{ $spec['str'] }}
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <button type="button" class="btn btn-outline-success btn-sm" data-container="body" data-toggle="popover" data-placement="bottom" data-content="Agility. Determines which side moves first.">
                                                                <img src='images/agi.png' height='20px' />
                                                                Agi : {{ $spec['agi'] }}
                                                            </button>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-outline-warning btn-sm" data-container="body" data-toggle="popover" data-placement="bottom" data-content="Ki. Additional damage for a certain moveset.">
                                                                <img src='images/ki.png' height='20px' />
                                                                Ki : {{ $spec['ki'] }}
                                                            </button>
                                                        </td>
                                                    </tr>

                                                    <!--<tr><td><small>Hp</small></td> <td>:</td> <td>{{ $spec['hp'] }}</td></tr>-->
                                                    <!--<tr><td><small>Str</small></td> <td>:</td> <td>{{ $spec['str'] }}</td></tr>-->
                                                    <!--<tr><td><small>Agi</small></td> <td>:</td> <td>{{ $spec['agi'] }}</td></tr>-->
                                                    <!--<tr><td><small>Ki</small></td> <td>:</td> <td>{{ $spec['ki'] }}</td></tr>-->
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column justify-content-around">
                                        @if (empty($char->uom2))
                                        <button type="button" class="btn w-100px btn-primary btn-sm mb-3" onclick="_pay_char('{{ $char->name }}', {{ $char->id }}, {{ $amount }})">
                                            @if ($char->conversion == 1)
                                            Pay Wage <br>
                                            -0 Nc/day
                                            @else
                                            Pay Wage <br>
                                            -{{ $char->wage_day }} Nc/day
                                            @endif
                                        </button>
                                        @else
                                        <button type="button" class="btn w-100px btn-success btn-sm mb-3">
                                            Wage Paid
                                        </button>
                                        @endif
                                        @if ($char->id != \Auth::user()->roster_driver && $char->id != \Auth::user()->roster_champion)
                                        <button type="button" class="btn w-100px btn-google btn-sm" onclick="_dismiss_char('{{ $char->name }}',{{ $char->id }})">Dismiss</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <span class="font-size-h4 font-weight-bolder">Total Wage</span>
                        <span class="font-size-h4 font-weight-bolder">{{ $total_wage }} Nc/day</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="card card-custom gutter-b card-stretch bgi-no-repeat" style="background-position: right top; background-size: 120px; background-image: url(assets/media/svg/shapes/abstract-2.svg)">
                <div class="card-header">
                    <h3 class="card-title"><img src='images/king.png' height='40px' /> Leaderboard</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped display">
                        <thead>
                            <tr>
                            <th class="text-center">No</th>
                            <!--<th>Username</th>-->
                            <th>Username / Hometown</th>
                            <th class="text-center">Nc</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leaderboards as $i => $item)
                                @if($item->do_code > 0)
                                @php
                                    $rank = "";
                                    if($i == 0){ $rank = "<img src='images/first.png' height='40px' />"; }
                                    if($i == 1){ $rank = "<img src='images/second.png' height='40px' />"; }
                                    if($i == 2){ $rank = "<img src='images/third.png' height='40px' />"; }
                                @endphp
                                <tr>
                                    <td align="center">{{ $i+1 }}</td>
                                    <td>
                                        @php echo $rank; @endphp
                                        <a href="{{ route('home.user.profile', base64_encode($item->id)) }}">
                                            {{ $item->name }} of {{ $item->hometown ?? "N/A" }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ number_format($item->do_code) ?? 0 }} &nbsp; @if($item->do_code > 1)<img src='images/n_c_icon.png' height='15px' />@endif
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="card card-custom gutter-b card-stretch bgi-no-repeat" style="background-position: right top; background-size: 120px; background-image: url(assets/media/svg/shapes/abstract-2.svg)">
                <div class='card-header'>
                    <h3 class='card-title'>Changelogs</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-bordered table-striped" id="tbl-changelogs">
                                <thead>
                                    <tr class="bg-primary text-white">
                                        <th>Ver/Date</th>
                                        <th>Changes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($changelogs as $item)
                                        <tr>
                                            <td>
                                                {{ $item->nama_topik }} <br>
                                                {{ date("d/n/y", strtotime($item->date_topik)) }}
                                            </td>
                                            <td>
                                                {!! $item->desc_topik !!}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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

    <div class="modal fade" id="modalRoster" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">List Roster</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div id="list-roster"></div>
            </div>
        </div>
    </div>

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

    <div class="modal fade" id="modalTavern" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="label-city"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 text-center">
                            <img src="{{ str_replace("public", "public_html", asset("media/asset/cities/tavern_banner.png")) }}" class="w-100" alt="">
                        </div>
                        <div class="col-12 mt-10 text-center">
                            <span class="font-weight-bold font-size-h3">
                                Tavern
                            </span>
                        </div>
                        <div class="col-12">
                            <div class="row mb-10">
                                <div class="col-md-6 col-sm-12">
                                    <div class="card card-custom gutter-b card-stretch bg-primary">
                                        <div class="card-body d-flex align-items-center">
                                            <img src="{{ asset("assets/media/svg/avatars/029-boy-11.svg") }}" class="align-self-end h-100px" alt="">
                                            <div class="d-flex flex-column flex-grow-1">
                                                 @if ( \Auth::user()->rumor_credit == 1)
                                                    <span class="font-weight-bolder text-white">
                                                        Hey mate,
                                                    </span>
                                                    <span class="font-weight-bolder text-white">
                                                        What do you need?
                                                    </span>
                                                @else
                                                    <span class="font-weight-bolder text-white">
                                                        Psst. Hear me up
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="card card-custom gutter-b card-stretch border border-4 border-primary">
                                        <div class="card-body d-flex justify-content-center align-items-center">
                                             @if ( \Auth::user()->rumor_credit == 1)
                                                <button type="button" id="btn-rumors" class="btn btn-primary">
                                                    I need information (-10 NC)
                                                </button>
                                            @elseif(isset($rumors->description))
                                                <span class="font-size-h5 font-weight-bolder" id="share-content">{{ $rumors->description }}</span>
                                            @else
                                                <span class="font-size-h5 font-weight-bolder" id="share-content">Ah, I forgot what to tell you. Sorry</span>
                                            @endif
                                        </div>
                                        @if (!empty($rumors))
                                        <div class="card-footer text-right">
                                            <button class="btn btn-primary btn-sm" type="button" {{ (\Auth::user()->share_rumor_credit == 0) ? "disabled" : 'onclick=share_to_discord(this)' }}><i class="fab fa-discord"></i> Share to discord</button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if (\Auth::user()->beer_credit > 0)
                                <form action="{{ route("home.beer.buy") }}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12">
                                            <div class="card card-custom card-stretch gutter-b">
                                                <button type="submit" name="submit" value="me" class="btn btn-success btn-block h-100">
                                                    <div class="d-flex align-items-center justify-content-center">
                                                        <div class="symbol symbol-100 mr-3">
                                                            <img src="{{ str_replace("public", "public_html", asset("media/asset/beer-mug.svg")) }}" alt="">
                                                        </div>
                                                        <span>
                                                            Buy beer (-30 Nc) <br> (+1 <i class="fas fa-bolt text-warning"></i>)
                                                        </span>
                                                    </div>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12 text-center">
                                            <div class="card card-custom card-stretch gutter-b border border-4 border-success">
                                                <div class="card-body text-center">
                                                    <span class="font-size-h3">Buy beer for a friend (-30 Nc)</span>
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-7">
                                                                <select name="friend" class="form-control select2 w-100" data-placeholder="Select a friend" id="sl-tavern">
                                                                    <option value=""></option>
                                                                    @if (count($friends) > 0)
                                                                        @foreach ($friends as $fr_id => $item)
                                                                            <option value="{{ $fr_id }}">{{ $item }}</option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                            </div>
                                                            <div class="col-5">
                                                                <button type="submit" name="submit" value="friend" id="btn-fr" disabled class="btn btn-warning">Send beer (-30 Nc)</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="row">
                                    <div class="col-12 mt-10">
                                        <div class="alert alert-secondary" role="alert">
                                            * Excess energy from maximum value will get changed to maximum energy value everyday. Make sure to use your excess energy before daily refresh.
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="row ">
                                    <div class="col-6 mx-auto">
                                        <div class="alert alert-secondary text-center font-size-h3 font-weight-bold" role="alert">
                                            @if (empty(\Auth::user()->beer_to))
                                            Drink more Tomorrow!
                                            @else
                                            Beer sent to {{ (isset($friends[\Auth::user()->beer_to])) ? $friends[\Auth::user()->beer_to] : "N/A" }}
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            @endif
                        </div>
                    </div>
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
                <!--<div class="modal-footer">-->
                <!--    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>-->
                <!--</div>-->
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

        $("#btn-claim-daily").click(function(){
            Swal.fire({
                title: "Daily Seed",
                // text: "Claim your daily free seed!",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Claim your daily free seed!"
            }).then(function(result) {
                if (result.value) {
                    // $("#form-caravan").submit()
                    $.ajax({
                        url : "{{ route('home.daily.seed') }}",
                        type : "post",
                        dataType : "json",
                        data : {
                            _token : "{{ csrf_token() }}"
                        },
                        beforeSend : function(){
                            Swal.fire({
                                title: "Claiming",
                                allowOutsideClick : false,
                                onOpen: function() {
                                    Swal.showLoading()
                                }
                            })
                        },
                        success : function(res){
                            swal.close()
                            var _m = ""
                            var _t = ""
                            var _c = ""
                            if(res.success){
                                _m = res.message
                                _t = "Success"
                                _c = "success"
                            } else {
                                _m = res.message
                                _t = "Error"
                                _c = "error"
                            }

                            Swal.fire(_t, _m, _c).then((result) => {
                                if(result){
                                    location.reload()
                                }
                            })
                        }
                    })
                }
            });
        })

        $("#btn-caravan").click(function(e){
            Swal.fire({
                title: "Are you sure?",
                text: "Buy a caravan for 200 Nc",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Yes"
            }).then(function(result) {
                if (result.value) {
                    $("#form-caravan").submit()
                }
            });
        })

        function _hunt(){
            Swal.fire({
                title: "Are you sure?",
                text: "You need 1 Energy to Hunt",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, - 1 <i class='fas fa-bolt text-warning'>"
            }).then(function(result) {
                if (result.value) {
                    //open modal hunt
                    $("#hunt-report-div").html("")
                    $.ajax({
                        url : "{{ route('home.hunt') }}",
                        type : "post",
                        dataType : "json",
                        data : {
                            _token : "{{ csrf_token() }}"
                        },
                        success : function(response){
                            // $("#hunt-report-div").html(response)
                            if(response.success){
                                $("#modalHunt").modal('show')
                                var champion = response.champion
                                var first_row = '<div class="alert alert-primary" role="alert">'
                                first_row += '<div class="d-flex align-items-center">'
                                first_row += '<div class="symbol symbol-circle p-1 bg-white symbol-50 mr-3">'
                                first_row += '<img alt="Pic" src="{{ asset("characters/images/") }}/'+champion.picture+'"/>'
                                first_row += '</div>'
                                first_row += '<span>'
                                first_row += '<span class="font-weight-bolder">'+ champion.name + ' </span> went to hunt at the <span class="font-weight-bolder">' + response.location + ' </span>'
                                first_row += '</span>'
                                first_row += '</div>'
                                first_row += '</div>'

                                $("#hunt-report-div").append(first_row)

                                var encounter = response.encounter

                                var i = 0
                                var x = setInterval(() => {
                                    var monster = encounter[i]

                                    var monres = ""
                                    var _form = ""
                                    var _nc = ""
                                    if(monster.res == 1){
                                        monres = '<span class="font-weight-bolder">'+ champion.name + ' </span> killed it! You obtained ' + monster.drop + " Nc"
                                    } else if (monster.res == 2){
                                        monres = '<span class="font-weight-bolder">'+ champion.name + ' </span> won, but got <span class="font-weight-bolder text-danger">WOUNDED!</span> You obtained ' + monster.drop + " Nc"
                                    } else if (monster.res == 3){
                                        monres = '<span class="font-weight-bolder">'+ champion.name + ' </span> ran away from it!'
                                    }

                                    _form = '<input type="hidden" name="encounter[]" value="'+monster.res+'">'
                                    _nc = '<input type="hidden" name="nc[]" value="'+monster.drop+'">'
                                    var monster_row = '<div class="alert alert-info" role="alert">'
                                    monster_row += '<div class="d-flex align-items-center">'
                                    monster_row += '<div class="symbol symbol-circle p-1 bg-white symbol-50 mr-3">'
                                    monster_row += '<img alt="Pic" src="'+monster.image+'"/>'
                                    monster_row += '</div>'
                                    monster_row += '<span>'
                                    monster_row += '<span class="font-weight-bolder">' + monster.monsters + ' </span> has appeared. ' + monres
                                    monster_row += '</span>'
                                    monster_row += _form + _nc
                                    monster_row += '</div>'
                                    monster_row += '</div>'

                                    $("#hunt-report-div").append(monster_row)
                                    i++
                                    if(i == encounter.length){
                                        clearInterval(x)
                                        setTimeout(() => {
                                            var seed = response.seed

                                            _form = '<input type="hidden" name="seed" value="'+seed.id+'">'
                                            var seed_row = '<div class="alert alert-success" role="alert">'
                                            seed_row += '<div class="d-flex align-items-center">'
                                            seed_row += '<div class="symbol symbol-circle p-1 bg-white symbol-50 mr-3">'
                                            seed_row += '<img alt="Pic" src="{{ asset("images/") }}/'+seed.picture+'"/>'
                                            seed_row += '</div>'
                                            seed_row += '<span>'
                                            seed_row += '<span class="font-weight-bolder">'+ champion.name + ' </span> obtained one <span class="font-weight-bolder">' + seed.name + ' </span> !'
                                            seed_row += '</span>'
                                            seed_row += _form
                                            seed_row += '</div>'
                                            seed_row += '</div>'

                                            $("#hunt-report-div").append(seed_row)
                                        }, 1000);

                                        setTimeout(() => {
                                            var first_row = '<div class="alert alert-primary" role="alert">'
                                            first_row += '<div class="d-flex align-items-center">'
                                            first_row += '<div class="symbol symbol-circle p-1 bg-white symbol-50 mr-3">'
                                            first_row += '<img alt="Pic" src="{{ asset("characters/images/") }}/'+champion.picture+'"/>'
                                            first_row += '</div>'
                                            first_row += '<span>'
                                            first_row += '<span class="font-weight-bolder">'+ champion.name + ' </span> went home safely'
                                            first_row += '</span>'
                                            first_row += '</div>'
                                            first_row += '</div>'

                                            $("#hunt-report-div").append(first_row)
                                        }, 2000);

                                        setTimeout(() => {
                                            var button = '<div class="d-flex justify-content-center"><button type="submit" class="btn btn-primary">Got It</button></div>'
                                            $("#hunt-report-div").append(button)
                                        }, 3000);
                                    }
                                }, 1000);
                                $("#label-energy").text(response.energy)
                            } else {
                                Swal.fire("You ran out of Energy", "Your energy will be filled back by {{ \Session::get("company_period_start") }} everyday", "info")
                            }
                        }
                    })
                }
            });
        }

        function _trip_cd(){
            $.ajax({
                url : "{{ route("home.trip.countdown") }}",
                type : "get",
                dataType : "json",
                success : function(response){
                    if(response.success){
                        var data = response.data
                        var end_date = new Date(data.end).getTime()
                        var dnow = data.now
                        var start_date = new Date(data.now).getTime()
                        var start_trip = new Date(data.start).getTime()
                        var distance = end_date - start_date
                        var trip_duration = end_date - start_trip
                        var trip_pctg = trip_duration - distance
                        var x = setInterval(function() {
                            distance -= 1000
                            trip_pctg += 1000
                            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                            $("#text-cd-caravan").text("You will arrive at "+data.destination+" in " + pad(hours, 2) + ":" + pad(minutes, 2) + ":" + pad(seconds, 2))
                            var pctg = (trip_pctg / trip_duration)  * 100
                            $("#pg-trip").css("width", pctg+"%")

                            if(distance < 0){
                                clearInterval(x)
                                $("#text-cd-caravan").text("You have arrived at "+data.destination)
                                $("#pg-div").hide()
                                $("#form-check-in").css("display", "")
                                // $("#label-countdown").text("RIPED!")
                                // $("#btn-farm").show()
                                // const notification = new Notification("RIPE!", {
                                //     body: "Hey mate! Your crops are ready to be farmed.",
                                //     icon: "https://eironjayasejati.com/cypherApp/public/images/basket.png"
                                // });
                                // showNotification();
                                // console.log("RIPE NOTIFICATIONS");
                            }
                        }, 1000);
                    }
                }
            })
        }

        @if (!empty(\Auth::user()->trip_destination))
            _trip_cd()
        @endif


        function _trip_location(){
            $("#trip-town-list").html("")
            $.ajax({
                url : "{{ route('home.caravan.town') }}",
                type : "get",
                success : function(response){
                    $("#trip-town-list").html(response)
                }
            })
        }

        function _assign_roster(type, id, name){
            Swal.fire({
                title: name,
                text : "Assign as " + type + "?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Yes"
            }).then(function(result) {
                if (result.value) {
                    Swal.fire({
                        allowOutsideClick: false,
                        title: "Loading",
                        onOpen: function() {
                            Swal.showLoading()
                        }
                    })

                    $.ajax({
                        url : "{{ route("home.assign.char") }}",
                        type : "post",
                        dataType : "json",
                        data : {
                            _token : "{{ csrf_token() }}",
                            id : id,
                            type : type
                        },
                        success : function(response){
                            var _head = "Success"
                            var _text = name + " is your "+type+" now"
                            var _type = "success"

                            Swal.fire(_head, _text, _type).then((result) => {
                                if(result){
                                    location.reload()
                                }
                            })
                        }
                    })
                }
            });
        }


        function list_roster(type){
            $("#list-roster").html("")
            $.ajax({
                url : "{{ route('home.list.char') }}",
                type : "post",
                data : {
                    _token : "{{ csrf_token() }}",
                    type : type
                },
                success : function(response){
                    $("#list-roster").html(response)
                }
            })
        }
        // audioElement = document.createElement('audio');

        // audioElement.setAttribute('src', 'https://eironjayasejati.com/cypherApp/public_html/media/asset/Existence.mp3');
        // audioElement.muted = true;
        // $('#ToggleStart').click(function () {
        //     audioElement.play();
        // });

        // $('#ToggleStop').click(function () {
        //     audioElement.pause();
        // });

        function _pay_char(name, id, amount){
            Swal.fire({
                title: name,
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Pay "+amount+" Nc"
            }).then(function(result) {
                if (result.value) {
                    Swal.fire({
                        allowOutsideClick: false,
                        title: "Loading",
                        onOpen: function() {
                            Swal.showLoading()
                        }
                    })

                    $.ajax({
                        url : "{{ route("home.pay.char") }}",
                        type : "post",
                        dataType : "json",
                        data : {
                            _token : "{{ csrf_token() }}",
                            id : id,
                            amount : amount
                        },
                        success : function(response){
                            if(response.success){
                                var _head = "Success"
                                var _text = response.data
                                var _type = "success"
                            } else {
                                var _head = "Insufficient Balance"
                                var _text = response.data
                                var _type = "success"
                            }

                            Swal.fire(_head, _text, _type).then((result) => {
                                if(result){
                                    location.reload()
                                }
                            })
                        }
                    })
                }
            });
        }

        function _dismiss_char(name, id){
            Swal.fire({
                title: "Are you sure?",
                text: "You want to dismiss "+name+"?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Yes"
            }).then(function(result) {
                if (result.value) {
                    Swal.fire({
                        allowOutsideClick: false,
                        title: "Loading",
                        onOpen: function() {
                            Swal.showLoading()
                        }
                    })

                    $.ajax({
                        url : "{{ route("home.dismiss.char") }}",
                        type : "post",
                        dataType : "json",
                        data : {
                            _token : "{{ csrf_token() }}",
                            id : id
                        },
                        success : function(response){
                            if(response){
                                location.reload()
                            } else {
                                Swal.fire("Error", "", "error")
                            }
                        }
                    })
                }
            });
        }

        function _find_char(me){
            var skip = $(me).data('skip')
            $("#char-div").html("")
            $.ajax({
                url : "{{ route("home.find.char") }}",
                type : "post",
                data : {
                    _token : "{{ csrf_token() }}",
                    skip : skip
                },
                success : function(response){
                    $("#btn-find-char").hide()
                    $("#char-div").html(response)
                }
            })
        }

        function _trigger_event(ev){
            if(ev != 0){
                var msg = ""
                var btn = ""
                switch (ev) {
                    case 1:
                        msg = "Remove this bug?"
                        btn = "Yes, get rid of it (free, -5 minutes ripe timer)"
                        break;
                    case 2:
                        msg = "Accept the fairy’s blessing?"
                        btn = "Yes, please (-1 energy, instant ripe)"
                        break;
                    case 3:
                        msg = "Do you want to water the plant?"
                        btn = "Yes, please (-10 Nc, -15 minutes ripe timer)"
                        break;
                }
                Swal.fire({
                    title: msg,
                    icon: "question",
                    showCloseButton: true,
                    confirmButtonText: btn
                }).then(function(result) {
                    if (result.value) {
                        window.location.href = "{{ route('home.event.trigger') }}?ev=" + ev
                    }
                });
            }
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
            $("#label-sell").text("Select Town to sell your "+plant)
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
                                span_plant.text(sum + " Nc")
                            }
                        })
                    })
                }
            })
        }

        function _select_city_trip(id){
            Swal.fire({
                title: "Are you sure?",
                text: "One trip credit of the driver will be consumed",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Yes!"
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url : "{{ route("home.caravan.town_confirmation") }}",
                        type : "post",
                        dataType : "json",
                        data : {
                            _token : "{{ csrf_token() }}",
                            city_id : id
                        },
                        beforeSend : function(){
                            Swal.fire({
                                title: "Loading...",
                                onOpen: function() {
                                    Swal.showLoading()
                                }
                            })
                        },
                        success : function (response) {
                            swal.close()
                            if(response.success){
                                Swal.fire("Success", response.message, 'success').then((result) => {
                                    location.reload()
                                })
                            }
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            KTApp.unblock('#list-city')
                        }
                    })
                }
            });
        }

        function _select_city(id){
            Swal.fire({
                title: "Are you sure?",
                text: "One Energy will be consumed on confirmation",
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
                                location.reload()
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
            //addtimer
            setInterval(redirectTimerPlanted, 3000);

            function redirectTimerPlanted() {
              location.reload
            }
        }

        function share_to_discord(me) {
            var username = "{{ \Auth::user()->username }}"
            var content = username+" shared an info that the bartender said : `"+$("#share-content").text()+"`!"
            var channel = "general"
            $(me).addClass("spinner spinner-right disabled")
            _to_discord(channel, username, content)
            window.location.href = "{{ route('home.rumors.share') }}"

        }

        function _to_discord(channel, username, content) {
            //DISCORD WEBHOOK
            const request = new XMLHttpRequest();
            if(channel == "general"){
                request.open("POST", "https://discord.com/api/webhooks/959440700200611870/D4Wm7hSkQA7DKB_cRGUaZmycBM_1YoqyxB4-EmkI96_gKzIaNaP3Jf3FmIFFYhP7vVCW");
            }

            request.setRequestHeader('Content-type', 'application/json');

            const params = {
                username: "Before You Rule",
                avatar_url: "https://eironjayasejati.com/cypherApp/public_html/media/asset/byr_logo.png",
                content: content
            }

            request.send(JSON.stringify(params))

            //   request.send(JSON.stringify(params));
            //END OF DISCORD WEBHOOK
        }

        function _show_city(){
            $("#label-city").text("Where do you want to go?")
            $.ajax({
                url : "{{ route('home.city') }}",
                type : "get",
                success : function(response){
                    $("#list-city").html(response);

                    //DISCORD WEBHOOK
                    const request = new XMLHttpRequest();
                    request.open("POST", "https://discord.com/api/webhooks/955088933757288569/JVafjdlczKWQvShI70yve35Pn2alzioBIFkiQrVPePA7yyyQBilCV1p4Qibv58O93MqW");

                    request.setRequestHeader('Content-type', 'application/json');

                    const params = {
                        username: "Webhook BYR",
                        avatar_url: "https://eironjayasejati.com/cypherApp/public_html/media/asset/byr_logo.png",
                        content: "test again"
                    }

                    //   request.send(JSON.stringify(params));
                    //END OF DISCORD WEBHOOK
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

        function _check_balance(amount){
            $.ajax({
                url : "{{ route("home.balance.check") }}",
                type : "post",
                dataType : "json",
                data : {
                    _token : "{{ csrf_token() }}",
                    balance : amount
                },
                success : function(response){
                    bl = response
                }
            })

            return bl
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
                        var isEvent = $("#event").data("isevent")
                        if(isEvent != 0){
                            $("#event").css("display", "")
                            var eventImg = ""
                            if(isEvent == 1){
                                eventImg = "{{ asset("assets/images/event_bug.png") }}"
                            } else if(isEvent == 2){
                                eventImg = "{{ asset("assets/images/event_fairy.png") }}"
                            } else if(isEvent == 3){
                                eventImg = "{{ asset("assets/images/event_watering.png") }}"
                            }
                            $("#event").attr("src", eventImg)
                        }
                        $("#img-plant").attr("src", data.img_thumb)
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

        var table_seeds = $("#table-seeds").DataTable({
            responsive: true,
            columnDefs : [
                {"targets" : [1], "className" : "text-center"}
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

        function _under_construction(){
            swal.fire("Under Construction", "", "info")
        }

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

        function _load_seed_user(){
            table_seeds.clear().draw()
            $.ajax({
                url : "{{ route('home.seeds.table') }}",
                type : "get",
                dataType : "json",
                beforeSend : function(){
                    KTApp.block('#table-seeds', {})
                },
                success : function(response){
                    KTApp.unblock('#table-seeds')
                    for (let i = 0; i < response.length; i++) {
                        var data = response[i]
                        table_seeds.row.add([
                            data['plant'],
                            data['qty']
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
                                                $("#btn-metamask").html('<i class="fab fa-ethereum"></i>Connected')
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

        // notif section

        @if(!empty(\Session::get("first_login")))
            $("#modalHtp").modal("show")
            @php
                \Session::put("first_login", 0)
            @endphp
        @endif

        @if(!empty(\Session::get('hunt')))
            Swal.fire({
                title : "Success",
                text : "Hunt reward collected",
                icon : "success",
                allowOutsideClick : false,
            }).then((result) => {
                if(result){
                    location.reload()
                }
            })
        @endif

        @if(!empty(\Session::get('reset')))
            Swal.fire("Success", "Reset daily success", "success")
        @endif

        @if(!empty(\Session::get('char')))
            Swal.fire("Success", "10 Characters successfully generated", "success")
        @endif

        @if(!empty(\Session::get('farm')))
            // Swal.fire("Success", "Planting success. You can farm in {{ \Session::get('farm') }} minutes", "success")

            Swal.fire({
                title: "Success",
                text: "Planting success. You can farm in {{ \Session::get('farm') }} minutes",
                icon: "success",
                showConfirmButton: false
            }).then((result) => {
                if(result.value){
                    location.reload()
                }
            })

            setInterval(redirectTimer, 3000);

            function redirectTimer() {
              location.reload()
            }
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

        @if(!empty(\Session::get('balance')))
            Swal.fire("Insufficient Balance", "You need ${{ \Session::get("balance") }} balance to complete the request", "info")
        @endif

        @if(!empty(\Session::get('event')))
            Swal.fire("Event", "{{ \Session::get("event") }}", "success").then((result) => {
                if(result.value){
                    location.reload()
                }
            })
            setInterval(redirectTimer, 3000);

            function redirectTimer() {
              location.reload()
            }
        @endif

        @if(!empty(\Session::get('home')))
            Swal.fire("Success", "Your home is {{ $mycity->name }}", "info")
        @endif

        @if(!empty(\Session::get('beer')))
            Swal.fire("Success", "Successfully buy a beer for {{ \Session::get('beer') }}", "success")
        @endif

        @if(!empty(\Session::get('open_shop')))
            $("#modalCities").modal("show")
            _open_city({{ \Session::get('open_shop') }})
        @endif

        @if(!empty(\Session::get('rumors')))
            $("#modalTavern").modal("show")
        @endif

        @if(!empty(\Session::get('share_rumor')))
            Swal.fire("Shared", "Shared to discord", "success")
        @endif

        @if(!empty(\Session::get('train')))
            Swal.fire("Training", "", "success")
        @endif

        @if(!empty(\Session::get('hired')))
            Swal.fire("Success", "{{ \Session::get('hired') }} is joining your Roster", "success")
        @endif

        @if(!empty(\Session::get('checkin')))
            Swal.fire("Success", "Welcome to {{ \Session::get('checkin') }}", "success")
        @endif

        _countdown()

        _load_plant_user()
        _load_seed_user()
        // _load_city_user()

        $(document).ready(function(){
            // audioElement.play();

            $("#btn-rumors").click(function(){
                Swal.fire({
                    title: "Are you sure?",
                    text: "You will be paying 10 NC",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Yes"
                }).then(function(result) {
                    window.location.href = "{{ route("home.rumors.get") }}";
                });
            })

            $("#sl-tavern").select2({
                width : "100%",
                allowClear : true
            })

            $("#sl-tavern").trigger("change")

            $("#sl-tavern").change(function(){
                if($(this).val() != "" && $(this).val() != null && $(this).val() != undefined){
                    $("#btn-fr").attr("disabled", false)
                } else {
                    $("#btn-fr").attr("disabled", true)
                }
            })

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

            $("table.display").DataTable()

            $("#tbl-changelogs").DataTable({
                paging : false,
                ordering : false,
                searching : false,
                bInfo : false
            })

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
