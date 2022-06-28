@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">Covid Protocol</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <a href="{{ route('general.covid.setting') }}" class="btn btn-icon btn-secondary"><i class="fa fa-cog"></i></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-xl-8">
                    <!--begin::Engage Widget 7-->
                    <div class="card card-custom card-stretch gutter-b">
                        <div class="card-body d-flex p-0">
                            <div class="flex-grow-1 p-12 card-rounded bgi-no-repeat d-flex flex-column justify-content-center align-items-start" style="background-color: #FFF4DE; background-position: right bottom; background-size: auto 100%; background-image: url(assets/media/svg/humans/custom-8.svg)">
                                <h4 class="text-danger font-weight-bolder m-0">COVID 19 Health Tips</h4>
                                <p class="text-dark-50 my-5 font-size-xl font-weight-bold">Protect yourself, in the same time, caring for others.
                                <br />Here you can see all the tips related to COVID 19</p>
                                <a href="#" class="btn btn-danger font-weight-bold py-2 px-6">See more</a>
                            </div>
                        </div>
                    </div>
                    <!--end::Engage Widget 7-->
                </div>
                <div class="col-xl-4">
                    <!--begin::Engage Widget 8-->
                    <div class="card card-custom card-stretch gutter-b">
                        <div class="card-body p-0 d-flex">
                            <div class="d-flex align-items-start justify-content-start flex-grow-1 bg-light-warning p-8 card-rounded flex-grow-1 position-relative">
                                <div class="d-flex flex-column align-items-start flex-grow-1 h-100">
                                    <div class="p-1 flex-grow-1">
                                        <h4 class="text-warning font-weight-bolder">Prevent COVID<br /> and help end the pandemic</h4>
                                        <p class="text-dark-50 font-weight-bold mt-3">Wear a mask<br />Wash your hand<br />Maintain safe distance & limit mobility<br />Get vaccinated</p>
                                    </div>
                                    <a href='#' class="btn btn-link btn-link-warning font-weight-bold">Create Report
                                    <span class="svg-icon svg-icon-lg svg-icon-warning">
                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Arrow-right.svg-->
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <polygon points="0 0 24 0 24 24 0 24" />
                                                <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-90.000000) translate(-12.000000, -12.000000)" x="11" y="5" width="2" height="14" rx="1" />
                                                <path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997)" />
                                            </g>
                                        </svg>
                                        <!--end::Svg Icon-->
                                    </span></a>
                                </div>
                                <div class="position-absolute right-0 bottom-0 mr-5 overflow-hidden">
                                    <img src="assets/media/svg/humans/custom-13.svg" class="max-h-200px max-h-xl-275px mb-n20" alt="" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Engage Widget 8-->
                </div>
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
