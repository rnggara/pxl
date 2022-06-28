@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <!--begin::Details-->
                <div class="d-flex mb-9">
                    <!--begin: Pic-->
                    <div class="flex-shrink-0 mr-7 mt-lg-0 mt-3">
                        <div class="symbol symbol-50 symbol-lg-120">
                            <img src="{{ $user->user_img }}" alt="image" />
                        </div>
                        <div class="symbol symbol-50 symbol-lg-120 symbol-primary d-none">
                            <span class="font-size-h3 symbol-label font-weight-boldest">JM</span>
                        </div>
                    </div>
                    <!--end::Pic-->
                    <!--begin::Info-->
                    <div class="flex-grow-1">
                        <!--begin::Title-->
                        <div class="d-flex justify-content-between flex-wrap mt-1">
                            <div class="d-flex mr-3">
                                <a href="#" class="text-dark-75 text-hover-primary font-size-h5 font-weight-bold mr-3">{{ $user->name }} of {{ $town->name }}</a>
                                <a href="#">
                                    <i class="flaticon2-correct text-success font-size-h5"></i>
                                </a>
                            </div>
                        </div>
                        <!--end::Title-->
                        <!--begin::Content-->
                        {{-- <div class="d-flex flex-wrap justify-content-between mt-1">
                            <div class="d-flex flex-column flex-grow-1 pr-8">
                                <div class="d-flex flex-wrap mb-4">
                                    <a href="#" class="text-dark-50 text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
                                    <i class="flaticon2-new-email mr-2 font-size-lg"></i></a>
                                    <a href="#" class="text-dark-50 text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
                                    <i class="flaticon2-calendar-3 mr-2 font-size-lg"></i></a>
                                    <a href="#" class="text-dark-50 text-hover-primary font-weight-bold">
                                    <i class="flaticon2-placeholder mr-2 font-size-lg"></i></a>
                                </div>
                                <span class="font-weight-bold text-dark-50">I distinguish three main text objectives could be merely to inform people.</span>
                                <span class="font-weight-bold text-dark-50">A second could be persuade people.You want people to bay objective</span>
                            </div>
                            <div class="d-flex align-items-center w-25 flex-fill float-right mt-lg-12 mt-8">
                                <span class="font-weight-bold text-dark-75">Progress</span>
                                <div class="progress progress-xs mx-3 w-100">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 63%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="font-weight-bolder text-dark">78%</span>
                            </div>
                        </div> --}}
                        <!--end::Content-->
                    </div>
                    <!--end::Info-->
                </div>
                <!--end::Details-->
                <div class="separator separator-solid"></div>
                <!--begin::Items-->
                <div class="d-flex align-items-center flex-wrap mt-8">
                    <!--begin::Item-->
                    <div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
                        <span class="mr-4">
                            <img src='{{ asset('images/n_c_icon.png') }}' height='35px' />
                        </span>
                        <div class="d-flex flex-column text-dark-75">
                            <span class="font-weight-bolder font-size-sm">Earnings</span>
                            <span class="font-weight-bolder font-size-h5">{{ $user->do_code }}
                            <span class="text-dark-50 font-weight-bold">Nc</span></span>
                        </div>
                    </div>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
                        <span class="mr-4">
                            <i class="fas fa-bolt text-warning icon-2x"></i>
                        </span>
                        <div class="d-flex flex-column text-dark-75">
                            <span class="font-weight-bolder font-size-sm">Energy</span>
                            <span class="font-weight-bolder font-size-h5">{{ $user->attend_code }}</span>
                        </div>
                    </div>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
                        <span class="mr-4">
                            <img src='{{ asset("assets/byr/caravan.png") }}' height='35px' />
                        </span>
                        <div class="d-flex flex-column text-dark-75">
                            <span class="font-weight-bolder font-size-sm">Caravan</span>
                            <span class="font-weight-bolder font-size-h5 text-{{ ($user->own_caravan == 1) ? "success" : "danger" }}">
                                {{ ($user->own_caravan == 1) ? "Yes" : "No" }}
                            </span>
                        </div>
                    </div>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
                        <div class="d-flex flex-column flex-lg-fill">
                            <span class="text-dark-75 font-weight-bolder font-size-sm">Current Location</span>
                            <span class="font-weight-bolder font-size-h5">{{ $location->name }}</span>
                        </div>
                    </div>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
                        <a href="{{ route('treasury.history', ['type' => 'crystal', 'id' => base64_encode($user->id)]) }}" class="btn btn-outline-primary">
                            Ledger
                        </a>
                    </div>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <!--end::Item-->
                </div>
                <!--begin::Items-->
            </div>
        </div>
    </div>
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">Rosters</h3>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach ($rosters as $char)
                    @php
                        $notes = json_decode($char->notes, true);
                        $spec = json_decode($char->specification, true);
                        $amount = $char->wage_day;
                        if($char->conversion == 1){
                            $amount = 0;
                        }
                    @endphp
                    <div class="col-md-4 mb-5 border border-2 border-primary p-4">
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
                                                    <img src='{{ asset('images/hp.png') }}' height='20px' />
                                                    Hp : {{ $spec['hp'] }}
                                                </button>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-outline-danger btn-sm" data-container="body" data-toggle="popover" data-placement="top" data-content="Strength. Raw damage in battle.">
                                                    <img src='{{ asset('images/str.png') }}' height='20px' />
                                                    Str : {{ $spec['str'] }}
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <button type="button" class="btn btn-outline-success btn-sm" data-container="body" data-toggle="popover" data-placement="bottom" data-content="Agility. Determines which side moves first.">
                                                    <img src='{{ asset('images/agi.png') }}' height='20px' />
                                                    Agi : {{ $spec['agi'] }}
                                                </button>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-outline-warning btn-sm" data-container="body" data-toggle="popover" data-placement="bottom" data-content="Ki. Additional damage for a certain moveset.">
                                                    <img src='{{ asset('images/ki.png') }}' height='20px' />
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
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        $(document).ready(function(){

        })
    </script>
@endsection
