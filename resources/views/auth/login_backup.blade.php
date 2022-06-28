@extends('layouts.templateauth2')
@section('title','Login | Cypher')
@section('content')
{{--    <div class="mb-20">--}}
{{--        <h2 class="text-primary">{{$company_holding}}</h2>--}}
{{--        <h3>Sign in</h3>--}}
{{--        <div class="text-muted font-weight-bold">Enter your details to login to your account:</div>--}}
{{--    </div>--}}
{{--    <form class="form" id="login" method="POST" action="{{route('login')}}">--}}
{{--        @csrf--}}
{{--        <div class="form-group mb-5">--}}
{{--            <input class="form-control h-auto form-control-solid py-4 px-8" type="text" placeholder="Username" name="username" autocomplete="off" />--}}
{{--        </div>--}}
{{--        <div class="form-group mb-5">--}}
{{--            <input class="form-control h-auto form-control-solid py-4 px-8" type="password" placeholder="Password" name="password" />--}}

{{--        </div>--}}
{{--        <button id="kt_login_signin_submit" type="submit" class="btn btn-primary font-weight-bold px-9 py-4 my-3 mx-4">Sign In</button>--}}
{{--    </form>--}}
<form class="form" novalidate="novalidate" id="kt_login_signin_form" method="POST" action="{{route('login')}}">
    <!--begin::Title-->
    @csrf
    <div class="text-center pb-8">
        <h2 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg">Sign In</h2>
        <span class="text-primary font-weight-bold font-size-h4">
            {{$company_holding}}
        </span>
    </div>
    <div class="d-flex align-items-center mb-6 mx-auto">
        <div class="mx-auto">
            @foreach($companies as $key => $value)
                <div class="symbol symbol-40 symbol-light-primary mr-5">
                    <span class="symbol-label">
                        <span class="svg-icon svg-icon-lg svg-icon-primary">
                            <!--begin::Svg Icon | path:assets/media/svg/icons/Home/Library.svg-->
                            <img src='{{str_replace("public", "public_html", asset('images/'.$value->app_logo))}}' style="align-content: center;" height='30px' alt="{{$value->company_name}}"/> &nbsp;&nbsp;

                            <!--end::Svg Icon-->
                        </span>
                    </span>
                </div>
            @endforeach
        </div>
        <!--begin::Symbol-->



        <!--end::Symbol-->
        <!--begin::Text-->

        <!--end::Text-->
    </div>
    <!--end::Title-->
    <!--begin::Form group-->
    <div class="form-group">
        <label class="font-size-h6 font-weight-bolder text-dark">Username</label>
        <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg" type="text" name="username" autocomplete="off" />
    </div>
    <!--end::Form group-->
    <!--begin::Form group-->
    <div class="form-group">
        <div class="d-flex justify-content-between mt-n5">
            <label class="font-size-h6 font-weight-bolder text-dark pt-5">Password</label>
        </div>
        <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg" type="password" name="password" autocomplete="off" />
    </div>
    <!--end::Form group-->
    <!--begin::Action-->
    <div class="text-center pt-2">
        <button id="kt_login_signin_submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3">Sign In</button>
    </div>
    <!--end::Action-->
</form>
@endsection
