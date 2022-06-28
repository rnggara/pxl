@extends('layouts.template')
@section('content')
<div class="subheader py-2 py-lg-4 subheader-transparent" id="kt_subheader">
    <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <!--begin::Info-->
        <div class="d-flex align-items-center flex-wrap mr-1">
            <!--begin::Mobile Toggle-->
            <button class="burger-icon burger-icon-left mr-4 d-inline-block d-lg-none" id="kt_subheader_mobile_toggle">
            <span></span>
            </button>
            <!--end::Mobile Toggle-->
            <!--begin::Page Heading-->
            <div class="d-flex align-items-baseline flex-wrap mr-5">
                <!--begin::Page Title-->
                <h5 class="text-dark font-weight-bold my-1 mr-5">{{$user->name}}</h5>
                <!--end::Page Title-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                    <li class="breadcrumb-item">
                        <a href="" class="text-muted">Account Information</a>
                    </li>
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page Heading-->
        </div>
        <!--end::Info-->
    </div>
</div>
<!--end::Subheader-->
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Profile Account Information-->
        <div class="d-flex flex-row">
            <!--begin::Aside-->
            <div class="flex-row-auto offcanvas-mobile w-250px w-xxl-350px" id="kt_profile_aside">
                <!--begin::Profile Card-->
                <div class="card card-custom card-stretch">
                    <!--begin::Body-->
                    <div class="card-body pt-4">
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end">
                            <div class="dropdown dropdown-inline">
                                <a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="ki ki-bold-more-hor"></i>
                                </a>
                            </div>
                        </div>
                        <!--end::Toolbar-->
                        <!--begin::User-->
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
                                <div class="symbol-label" style="@if($user->user_img == null) background-image:url('{{asset('theme/assets/media/users/default.jpg')}}') @else background-image:url('{{asset('theme/assets/media/users')}}/{{$user->user_img}}') @endif"></div>
                                <i class="symbol-badge bg-success"></i>
                            </div>
                            <div>
                                <a href="#" class="font-weight-bolder font-size-h5 text-dark-75 text-hover-primary">{{$user->username}}</a>
                                <div class="text-muted">{{$user->position}}</div>
                            </div>
                        </div>
                        <!--end::User-->
                        <!--begin::Contact-->
                        <div class="py-9">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="font-weight-bold mr-2">Email:</span>
                                <a href="#" class="text-muted text-hover-primary">{{$user->email}}</a>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="font-weight-bold mr-2">Phone:</span>
                                <span class="text-muted">44(76)34254578</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="font-weight-bold mr-2">Location:</span>
                                <span class="text-muted">Melbourne</span>
                            </div>
                        </div>
                        <!--end::Contact-->
                        <!--begin::Nav-->
                        <div class="navi navi-bold navi-hover navi-active navi-link-rounded">
                            <div class="navi-item mb-2">
                                <a href="#" class="navi-link py-4 active">
                                    <span class="navi-icon mr-2">
                                        <span class="svg-icon">
                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg-->
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24"></rect>
                                                <path d="M2.56066017,10.6819805 L4.68198052,8.56066017 C5.26776695,7.97487373 6.21751442,7.97487373 6.80330086,8.56066017 L8.9246212,10.6819805 C9.51040764,11.267767 9.51040764,12.2175144 8.9246212,12.8033009 L6.80330086,14.9246212 C6.21751442,15.5104076 5.26776695,15.5104076 4.68198052,14.9246212 L2.56066017,12.8033009 C1.97487373,12.2175144 1.97487373,11.267767 2.56066017,10.6819805 Z M14.5606602,10.6819805 L16.6819805,8.56066017 C17.267767,7.97487373 18.2175144,7.97487373 18.8033009,8.56066017 L20.9246212,10.6819805 C21.5104076,11.267767 21.5104076,12.2175144 20.9246212,12.8033009 L18.8033009,14.9246212 C18.2175144,15.5104076 17.267767,15.5104076 16.6819805,14.9246212 L14.5606602,12.8033009 C13.9748737,12.2175144 13.9748737,11.267767 14.5606602,10.6819805 Z" fill="#000000" opacity="0.3"></path>
                                            <path d="M8.56066017,16.6819805 L10.6819805,14.5606602 C11.267767,13.9748737 12.2175144,13.9748737 12.8033009,14.5606602 L14.9246212,16.6819805 C15.5104076,17.267767 15.5104076,18.2175144 14.9246212,18.8033009 L12.8033009,20.9246212 C12.2175144,21.5104076 11.267767,21.5104076 10.6819805,20.9246212 L8.56066017,18.8033009 C7.97487373,18.2175144 7.97487373,17.267767 8.56066017,16.6819805 Z M8.56066017,4.68198052 L10.6819805,2.56066017 C11.267767,1.97487373 12.2175144,1.97487373 12.8033009,2.56066017 L14.9246212,4.68198052 C15.5104076,5.26776695 15.5104076,6.21751442 14.9246212,6.80330086 L12.8033009,8.9246212 C12.2175144,9.51040764 11.267767,9.51040764 10.6819805,8.9246212 L8.56066017,6.80330086 C7.97487373,6.21751442 7.97487373,5.26776695 8.56066017,4.68198052 Z" fill="#000000"></path>
                                        </g>
                                    </svg>
                                    <!--end::Svg Icon-->
                                </span>
                            </span>
                            <span class="navi-text font-size-lg">Account Information</span>
                        </a>
                    </div>
                </div>
                <!--end::Nav-->
            </div>
            <!--end::Body-->
        </div>
        <!--end::Profile Card-->
    </div>
    <!--end::Aside-->
    <!--begin::Content-->
    <div class="flex-row-fluid ml-lg-8">
        <!--begin::Card-->
        <!--begin::Form-->
        <form class="form" action="{{route('account.update.info')}}" id="kt_form" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="{{$user->id}}">
            @csrf
            <input type="hidden" name="act" value="account_update">
            <div class="card card-custom">
                <!--begin::Header-->
                <div class="card-header py-3">
                    <div class="card-title align-items-start flex-column">
                        <h3 class="card-label font-weight-bolder text-dark">Account Information</h3>
                        <span class="text-muted font-weight-bold font-size-sm mt-1">Change your account settings</span>
                    </div>
                    <div class="card-toolbar">
                        <button type="submit" id="submit_update_account" name="submit_update_account" class="btn btn-success mr-2">Save Changes</button>
                        {{--                                    <button type="reset" class="btn btn-secondary">Cancel</button>--}}
                    </div>
                </div>
                <!--end::Header-->
                <div class="card-body">
                    <!--begin::Heading-->
                    <div class="row">
                        <label class="col-xl-3"></label>
                        <div class="col-lg-9 col-xl-6">
                            <h5 class="font-weight-bold mb-6">Account:</h5>
                        </div>
                    </div>
                    <!--begin::Form Group-->
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">Username</label>
                        <div class="col-lg-9">
                            <input type="text" class="form-control form-control-lg" disabled id="username" name="username" value="{{$user->username}}">
                        </div>
                    </div>
                    <!--begin::Form Group-->
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">Position</label>
                        <div class="col-lg-9">
                            <input class="form-control form-control-lg" type="text" id="position" name="position" value="{{($user->position != null)?$user->position:'SYSTEM'}}" disabled>
                        </div>
                    </div>
                    <!--begin::Form Group-->
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">My Profile Picture</label>
                        <div class="col-lg-9 col-xl-6">
                            <div class="image-input image-input-outline" id="printed_logo">
                                <div class="image-input-wrapper" style='background-image: url("@if($user->user_img == null) {{asset('theme/assets/media/users')}}/default.jpg @else {{asset('theme/assets/media/users')}}/{{$user->user_img}} @endif");'></div>
                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change">
                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                    <input type="file" name="user_img" id="p_logo" accept=".png, .jpg, .jpeg" />
                                    <input type="hidden" name="p_logo_remove" />
                                </label>
                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel">
                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                </span>
                            </div>
                            <span class="form-text text-muted">
                            Allowed file types: png, jpg, jpeg.</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">Attendance Password</label>
                        @if (!empty($userComp->emp_id))
                        <div class="col-lg-6">
                            <input class="form-control form-control-lg" type="text" data-id="{{ $userComp->id }}" id="attend_code" name="attend_code" value="{{ $userComp->attend_code }}" disabled>
                        </div>
                        <div class="col-lg-3">
                            <button type="button" id="btn-random" class="btn btn-primary">
                                <i class="fa fa-random"></i>
                                Randomize
                            </button>
                        </div>
                        @else
                        <div class="col-lg-9">
                            <input class="form-control form-control-lg" type="text" id="attend_code" name="attend_code" value="Please contact HR Department to connect your account to employee database" disabled>
                        </div>
                        @endif
                    </div>
                    @if (!empty($userComp->emp_id))
                        <div class="form-group row">
                            <div class="col-md-3"></div>
                            <div class="col-md-9">
                                <table class="table table-bordered">
                                    <tr>
                                        <th class="text-center">Session</th>
                                        <th class="text-center">Time</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">Clock In</th>
                                        <td class="text-center">
                                            {{ (empty($clockin)) ? "N/A" : date("d/m/Y H:i", strtotime($clockin)) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-center">Clock Out</th>
                                        <td class="text-center">
                                            {{ (empty($clockout)) ? "N/A" : date("d/m/Y H:i", strtotime($clockout)) }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    @endif
                    <div class="form-grou"></div>
                </div>
            </div>
        </form>
        <!--end::Form-->
        <!--end::Card-->
        <br />
        <form class="form" action="{{route('account.update.password')}}" id="kt_form" method="post">
            <input type="hidden" name="id" value="{{$user->id}}">
            @csrf
            {{--                        <input type="hidden" name="act" value="password_update">--}}
            <div class="card card-custom">
                <!--begin::Header-->
                <div class="card-header py-3">
                    <div class="card-title align-items-start flex-column">
                        <h3 class="card-label font-weight-bolder text-dark">Change Password</h3>
                        <span class="text-muted font-weight-bold font-size-sm mt-1">Change your account password</span>
                    </div>
                    <div class="card-toolbar">
                        <input type="submit" name="submit_update_password" id="submit_update_password" value="Save Changes" class="btn btn-success mr-2">
                        {{--                                    <button type="reset" class="btn btn-secondary">Cancel</button>--}}
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Form-->
                <div class="card-body">
                    <!--begin::Alert-->
                    <div class="alert alert-custom alert-light-danger fade show mb-10" role="alert">
                        <div class="alert-icon">
                            <span class="svg-icon svg-icon-3x svg-icon-danger">
                                <!--begin::Svg Icon | path:assets/media/svg/icons/Code/Info-circle.svg-->
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"></rect>
                                        <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10"></circle>
                                        <rect fill="#000000" x="11" y="10" width="2" height="7" rx="1"></rect>
                                        <rect fill="#000000" x="11" y="7" width="2" height="2" rx="1"></rect>
                                    </g>
                                </svg>
                                <!--end::Svg Icon-->
                            </span>
                        </div>
                        <div class="alert-text font-weight-bold">Configure user passwords to expire periodically. Users will need warning that their passwords are going to expire,
                        <br>or they might inadvertently get locked out of the system!</div>
                        <div class="alert-close">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">
                                <i class="ki ki-close"></i>
                            </span>
                            </button>
                        </div>
                    </div>
                    <!--end::Alert-->
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label text-alert">New Password</label>
                        <div class="col-lg-9 col-xl-6">
                            <input type="password" id="password" name="password" class="form-control form-control-lg form-control-solid" required placeholder="New password">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label text-alert">Verify Password</label>
                        <div class="col-lg-9 col-xl-6">
                            <input type="password" id="confirm_password" class="form-control form-control-lg form-control-solid" required placeholder="Verify password">
                        </div>
                    </div>
                </div>
            </div>
        </form>
        @if (!empty($emp))
        <div class="card card-custom gutter-b mt-5">
            <div class="card-header">
                <div class="card-title">My Slip</div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <table class="table table-bordered table-hover display table-responsive-xs">
                            <thead>
                                <tr>
                                    <th class="text-center">Month</th>
                                    <th class="text-center">File</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mnths as $i => $record)
                                <tr>
                                    <td align="center">{{ $record." ".date('Y') }}</td>
                                    <td align="center">
                                        @if (isset($arch[$i."-".date('Y')]) && ($arch[$i."-".date('Y')][0] + 1) == ($i + 1))
                                            <a target="_blank" href="{{ route('payroll.slip.print', ['id' => $emp->id, 'period' => date('Y')."-".$i]) }}" class="btn btn-sm btn-icon btn-primary"><i class="fa fa-print"></i></a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif


        <div class="row">
            <div class="col-6">
                <div class="card card-custom gutter-b mt-5">
                    <div class="card-header py-3">
                        <div class="card-title align-items-start flex-column">
                            <h3 class="card-label font-weight-bolder text-dark">Your Signature</h3>
                            <span class="text-muted font-weight-bold font-size-sm mt-1">Please sign for the system to sample your signature.</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 text-center">
                                <div class="symbol symbol-150 mr-3">
                                    @php
                                        $file_sign = (!empty($user->file_signature)) ? str_replace("public", "public_html", asset('media/user/signature/'.$user->file_signature)) : asset('theme/assets/media/users/1-profile.jpg');
                                    @endphp
                                    <img alt="Pic" src="{{ $file_sign }}"/>
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <button type="button" onclick="modal_sign('Signature')" data-toggle="modal" data-target="#modalSign" class="btn btn-success">Create/Upload your signature</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card card-custom gutter-b mt-5">
                    <div class="card-header py-3">
                        <div class="card-title align-items-start flex-column">
                            <h3 class="card-label font-weight-bolder text-dark">Your Paraf</h3>
                            <span class="text-muted font-weight-bold font-size-sm mt-1">Please sign for the system to sample your paraf.</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 text-center">
                                <div class="symbol symbol-150 mr-3">
                                    @php
                                        $file_par = (!empty($user->file_paraf)) ? str_replace("public", "public_html", asset('media/user/paraf/'.$user->file_paraf)) : asset('theme/assets/media/users/1-profile.jpg');
                                    @endphp
                                    <img alt="Pic" src="{{ $file_par }}"/>
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <button type="button" onclick="modal_sign('Paraf')" data-toggle="modal" data-target="#modalSign" class="btn btn-success">Create/Upload your paraf</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Form-->
    </div>
    <div class="modal fade" id="modalSign" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title">Create/Upload your <span id="sign-title"></span></h1>
                </div>
                <form action="{{ route('account.sign.add', $user->id) }}" id="form-sign" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group row">
                                    <div class="col-12">
                                        <div class="radio-inline">
                                            <label class="radio radio-rounded">
                                                <input type="radio" name="rb_sign" required class="rb-sign" value="1"/>
                                                <span></span>
                                                Create
                                            </label>
                                            <label class="radio radio-rounded">
                                                <input type="radio" name="rb_sign" required class="rb-sign" value="2"/>
                                                <span></span>
                                                Upload
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div id="sign-upload">
                                    <div class="form-group row">
                                        <label for="" class="col-form-label col-3">
                                            Upload File
                                        </label>
                                        <div class="col-9">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="input-file" name="file_upload">
                                                <span class="custom-file-label">Choose File</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="sign-create">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="wrapper">
                                                <canvas class="signature-pad border"></canvas>
                                            </div>
                                            <br>
                                            <button type="button" class="btn btn-primary btn-xs clear">Clear</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="type" id="sign-type">
                        <button type="button" class="btn btn-light-primary" data-dismiss="modal">Close</button>
                        <button type="button" name="submit_sign" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--end::Content-->
</div>
<!--end::Profile Account Information-->
</div>
<!--end::Container-->
</div>
@endsection
@section('custom_script')
<script src="{{asset('theme/assets/js/signature_pad.js')}}"></script>
<script async>
    var avatar1 = new KTImageInput('printed_logo');

    function modal_sign(x){
        $("#sign-title").text(x)
        $("#sign-type").val(x)
    }

    var type

    $(document).ready(function (params) {
        var wrapper     = document.getElementById("form-sign"),
            saveButton  = wrapper.querySelector("[name=submit_sign]"),
            canvas      = wrapper.querySelector("canvas"),
            signaturePad;

        signaturePad    = new SignaturePad(canvas);

        saveButton.addEventListener('click',function (event){
            event.preventDefault();
            if(type == 2) {
                $.ajax({
                    url         : '{{route('account.sign.add', $user->id)}}',
                    type        : 'POST',
                    data        : new FormData(wrapper),
                    contentType : false,
                    cache       : false,
                    processData : false,
                    dataType: "json",
                    success      : function(result) {
                        if (result.success === true) {
                            Swal.fire({
                                title: "Success",
                                text: result.message,
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "ok",
                                customClass: {
                                confirmButton: "btn btn-primary"
                                }
                            }).then(function(res) {
                                if(res.value){
                                    location.reload()
                                }
                            });
                        } else {
                            Swal.fire({
                                title: "Failed",
                                text: result.message,
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "ok",
                                customClass: {
                                confirmButton: "btn btn-primary"
                                }
                            }).then(function(res) {
                                if(res.value){
                                    location.reload()
                                }
                            });
                        }
                    }
                });
                return false;

            } else {
                var dataUrl      = signaturePad.toDataURL();
                ardata       = {
                    imageData: dataUrl,
                    _token: '{{csrf_token()}}',
                    rb_sign: $("input[name=rb_sign]").val(),
                    type: $("#sign-type").val()
                };
                $.ajax({
                    type    : 'POST',
                    url     : '{{route('account.sign.add', $user->id)}}',
                    data    : ardata,
                    dataType: "json",
                    success : function(result) {
                        console.log(result)
                        if (result.success === true) {
                            Swal.fire({
                                title: "Success",
                                text: result.message,
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "ok",
                                customClass: {
                                confirmButton: "btn btn-primary"
                                }
                            }).then(function(res) {
                                if(res.value){
                                    location.reload()
                                }
                            });
                        } else {
                            Swal.fire({
                                title: "Failed",
                                text: result.message,
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "ok",
                                customClass: {
                                confirmButton: "btn btn-primary"
                                }
                            }).then(function(res) {
                                if(res.value){
                                    location.reload()
                                }
                            });
                        }
                    }
                });
                return false;
            }
        })

        $("#sign-create").hide()
        $("#sign-upload").hide()

        $(".rb-sign").click(function(){
            if (this.value == 1) {
                $("#sign-create").show()
                $("#sign-upload").hide()
                signaturePad.clear();
                $("#input-file").val("")
                $(".custom-file-label").text("Choose File")
                type = 1
            } else {
                $("#sign-create").hide()
                $("#sign-upload").show()
                signaturePad.clear();
                type = 2
            }
        })

        $('.clear').click(function() {
            signaturePad.clear();
        });


        $("#btn-random").click(function(){
            var att = $("#attend_code")
            $.ajax({
                url : "{{ route('account.randomize') }}",
                type : "POST",
                dataType : "json",
                data : {
                    _token : "{{ csrf_token() }}",
                    id : att.data("id")
                },
                beforeSend : function(){
                    $("#btn-random").addClass("spinner spinner-right").prop('disabled', true)
                },
                success : function(response){
                    if(response.success){
                        window.location.reload()
                    }
                    $("#btn-random").removeClass("spinner spinner-right").prop('disabled', false)
                }
            })
        })

        $("#p_logo").change(function() {
        readURL(this, 'blah');
        });
    });
</script>
@endsection
