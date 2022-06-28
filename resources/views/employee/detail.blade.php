@extends('layouts.template')
@section('content')
    @if(session()->has('message_needsec_fail'))
        <div class="alert alert-danger">
            {!! session()->get('message_needsec_fail') !!}
        </div>
        @php session()->forget('message_needsec_fail') @endphp
    @endif
    @if(session()->has('message_needsec_success'))
        <div class="alert alert-success">
            {!! session()->get('message_needsec_success') !!}
        </div>
        @php session()->forget('message_needsec_success') @endphp
    @endif
    <noscript>
        <div class="alert alert-custom alert-light-danger fade show mb-5" role="alert">
            <div class="alert-icon"><i class="flaticon-warning"></i></div>
            <div class="alert-text">Your browser does not support JavaScript! Please enable the Javascript!</div>
            <div class="alert-close">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"><i class="ki ki-close"></i></span>
                </button>
            </div>
        </div>
    </noscript>
    <div class="d-flex flex-row">
        <div class="flex-row-auto offcanvas-mobile w-250px w-xxl-350px" id="kt_profile_aside">
            <!--begin::Profile Card-->
            <div class="card card-custom card-stretch">
                <!--begin::Body-->
                <div class="card-body pt-4">
                    <!--begin::User-->
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
                            <div class="symbol-label" style="background-image:url('{{str_replace('public', 'public_html', asset('/media/employee_attachment/'.$emp_detail->picture))}}')"></div>
                        </div>
                        <div>
                            <a href="#" class="font-weight-bolder font-size-h5 text-dark-75 text-hover-primary">{{$emp_detail->emp_name}}</a>
                            <div class="text-muted">{{strtoupper($emp_detail->emp_type."-".$emp_detail->emp_position)}}</div>
                        </div>
                    </div>
                    <!--end::User-->
                    <div class="py-4">
                        <!-- <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="font-weight-bold mr-2">Email:</span>
                            <a href="#" class="text-muted text-hover-primary">matt@fifestudios.com</a>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="font-weight-bold mr-2">Phone:</span>
                            <span class="text-muted">44(76)34254578</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="font-weight-bold mr-2">Location:</span>
                            <span class="text-muted">Melbourne</span>
                        </div> -->
                    </div>
                    <!--begin::Nav-->
                    <ul class="nav nav-tabs nav-tabs-line">
                        <li class="nav-item mb-2 active">
                            <a href="#personal-information" data-toggle="tab" class="nav-link py-4 active">
							<span class="nav-icon mr-2">
								<span class="svg-icon">
									<!--begin::Svg Icon | path:assets/media/svg/icons/General/User.svg-->
									<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
										<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											<polygon points="0 0 24 0 24 24 0 24" />
											<path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
											<path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
										</g>
									</svg>
                                    <!--end::Svg Icon-->
								</span>
							</span>
                                <span class="nav-text font-size-lg">Personal Information</span>
                            </a>
                        </li>
                        @actionStart('employee', 'update')
                        <li class="nav-item mb-2">
                            <a href="#profile-management" data-toggle="tab" class="nav-link py-4">
							<span class="nav-icon mr-2">
								<span class="svg-icon">
									<!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg-->
									<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
										<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											<rect x="0" y="0" width="24" height="24" />
											<path d="M2.56066017,10.6819805 L4.68198052,8.56066017 C5.26776695,7.97487373 6.21751442,7.97487373 6.80330086,8.56066017 L8.9246212,10.6819805 C9.51040764,11.267767 9.51040764,12.2175144 8.9246212,12.8033009 L6.80330086,14.9246212 C6.21751442,15.5104076 5.26776695,15.5104076 4.68198052,14.9246212 L2.56066017,12.8033009 C1.97487373,12.2175144 1.97487373,11.267767 2.56066017,10.6819805 Z M14.5606602,10.6819805 L16.6819805,8.56066017 C17.267767,7.97487373 18.2175144,7.97487373 18.8033009,8.56066017 L20.9246212,10.6819805 C21.5104076,11.267767 21.5104076,12.2175144 20.9246212,12.8033009 L18.8033009,14.9246212 C18.2175144,15.5104076 17.267767,15.5104076 16.6819805,14.9246212 L14.5606602,12.8033009 C13.9748737,12.2175144 13.9748737,11.267767 14.5606602,10.6819805 Z" fill="#000000" opacity="0.3" />
											<path d="M8.56066017,16.6819805 L10.6819805,14.5606602 C11.267767,13.9748737 12.2175144,13.9748737 12.8033009,14.5606602 L14.9246212,16.6819805 C15.5104076,17.267767 15.5104076,18.2175144 14.9246212,18.8033009 L12.8033009,20.9246212 C12.2175144,21.5104076 11.267767,21.5104076 10.6819805,20.9246212 L8.56066017,18.8033009 C7.97487373,18.2175144 7.97487373,17.267767 8.56066017,16.6819805 Z M8.56066017,4.68198052 L10.6819805,2.56066017 C11.267767,1.97487373 12.2175144,1.97487373 12.8033009,2.56066017 L14.9246212,4.68198052 C15.5104076,5.26776695 15.5104076,6.21751442 14.9246212,6.80330086 L12.8033009,8.9246212 C12.2175144,9.51040764 11.267767,9.51040764 10.6819805,8.9246212 L8.56066017,6.80330086 C7.97487373,6.21751442 7.97487373,5.26776695 8.56066017,4.68198052 Z" fill="#000000" />
										</g>
									</svg>
                                    <!--end::Svg Icon-->
								</span>
							</span>
                                <span class="nav-text font-size-lg">Profile Management</span>
                            </a>
                        </li>
                        @actionEnd
                        <li class="nav-item mb-2 {!! (\Session::get('tab') == "attachment") ? "active" : "" !!}">
                            <a href="#attachment-management" data-toggle="tab" class="nav-link py-4">
							<span class="nav-icon mr-2">
								<span class="svg-icon">
									<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Shield-user.svg-->
									<i class="flaticon-attachment"></i>
                                    <!--end::Svg Icon-->
								</span>
							</span>
                                <span class="nav-text font-size-lg">Attachment Management</span>
                            </a>
                        </li>
                        <li class="nav-item mb-2 {!! (\Session::get('tab') == "attachment") ? "active" : "" !!}">
                            <a href="#cv-management" data-toggle="tab" class="nav-link py-4">
							<span class="nav-icon mr-2">
								<span class="svg-icon">
									<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Shield-user.svg-->
									<i class="flaticon2-open-text-book"></i>
                                    <!--end::Svg Icon-->
								</span>
							</span>
                                <span class="nav-text font-size-lg">CV Management</span>
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="#join-management" data-toggle="tab" class="nav-link py-4">
							<span class="nav-icon mr-2">
								<span class="svg-icon">
									<!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo7\dist/../src/media/svg/icons\Shopping\Money.svg-->
									<i class="fa fa-calendar-alt"></i>
								</span>
							</span>
                                <span class="nav-text font-size-lg">Join Date Management</span>
                            </a>
                        </li>
                        @actionStart('employee', 'update')
                        <li class="nav-item mb-2">
                            <a href="#financial-management" data-toggle="tab" class="nav-link py-4">
							<span class="nav-icon mr-2">
								<span class="svg-icon">
									<!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo7\dist/../src/media/svg/icons\Shopping\Money.svg-->
									<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
								    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
								        <rect x="0" y="0" width="24" height="24"/>
								        <path d="M2,6 L21,6 C21.5522847,6 22,6.44771525 22,7 L22,17 C22,17.5522847 21.5522847,18 21,18 L2,18 C1.44771525,18 1,17.5522847 1,17 L1,7 C1,6.44771525 1.44771525,6 2,6 Z M11.5,16 C13.709139,16 15.5,14.209139 15.5,12 C15.5,9.790861 13.709139,8 11.5,8 C9.290861,8 7.5,9.790861 7.5,12 C7.5,14.209139 9.290861,16 11.5,16 Z" fill="#000000" opacity="0.3" transform="translate(11.500000, 12.000000) rotate(-345.000000) translate(-11.500000, -12.000000) "/>
								        <path d="M2,6 L21,6 C21.5522847,6 22,6.44771525 22,7 L22,17 C22,17.5522847 21.5522847,18 21,18 L2,18 C1.44771525,18 1,17.5522847 1,17 L1,7 C1,6.44771525 1.44771525,6 2,6 Z M11.5,16 C13.709139,16 15.5,14.209139 15.5,12 C15.5,9.790861 13.709139,8 11.5,8 C9.290861,8 7.5,9.790861 7.5,12 C7.5,14.209139 9.290861,16 11.5,16 Z M11.5,14 C12.6045695,14 13.5,13.1045695 13.5,12 C13.5,10.8954305 12.6045695,10 11.5,10 C10.3954305,10 9.5,10.8954305 9.5,12 C9.5,13.1045695 10.3954305,14 11.5,14 Z" fill="#000000"/>
								    </g>
								</svg>
								</span>
							</span>
                                <span class="nav-text font-size-lg">Financial Management</span>
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="#insurance-management" data-toggle="tab" class="nav-link py-4">
                            <span class="nav-icon mr-2">
                                <span class="svg-icon">
                                    <!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo7\dist/../src/media/svg/icons\Shopping\Money.svg-->
                                    <i class="fa fa-money-check"></i>
                                </span>
                            </span>
                                <span class="nav-text font-size-lg">Insurance Management</span>
                            </a>
                        </li>
                        @actionEnd
                        <li class="nav-item mb-2">
                            <a href="{{route('employee.index')}}" class="nav-link py-4">
							<span class="nav-icon mr-2">
								<span class="svg-icon">
									<!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo7\dist/../src/media/svg/icons\Shopping\Money.svg-->
								<i class="fa fa-backspace"></i>
                                </span>
							</span>
                                <span class="nav-text font-size-lg">Back</span>
                            </a>
                        </li>
                    </ul>
                    <!--end::Nav-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::Profile Card-->
        </div>
        <!--begin::Content-->
        <div class="flex-row-fluid ml-lg-8">
            <!--begin::Card-->
            <div class="card card-custom card-stretch">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="personal-information" role="tabpanel">
                        <!--begin::Header-->
                        <div class="card-header py-3">
                            <div class="card-title align-items-start flex-column">
                                <h3 class="card-label font-weight-bolder text-dark">Personal Information</h3>
                            </div>
                        </div>
                        <!--end::Header-->
                        <!--begin::Form-->
                        <form class="form">
                            <!--begin::Body-->
                            <div class="card-body">
                                <div class="row">
                                    <label class="col-xl-3"></label>
                                    <div class="col-lg-9 col-xl-6">
                                        <!-- <h5 class="font-weight-bold mb-6">Customer Info</h5> -->
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Avatar</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <div class="image-input image-input-outline" id="kt_profile_avatar">
                                            <div class="image-input-wrapper" style="background-image: url('{{str_replace('public', 'public_html', asset('/media/employee_attachment/'.$emp_detail->picture))}}')"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-xl-3"></label>
                                    <div class="col-lg-9 col-xl-6">
                                        <h5 class="font-weight-bold mb-6">Personal Data</h5>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Address</label>
                                    <label class="col-lg-9 col-xl-6 col-form-label font-weight-bold">{{$emp_detail->address}}</label>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Religion</label>
                                    <label class="col-xl-9 col-lg-6 col-form-label font-weight-bold">{{$emp_detail->religion}}</label>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Date of Birth</label>
                                    <label class="col-lg-9 col-xl-6 col-form-label font-weight-bold">
                                        {{date('d F Y',strtotime($emp_detail->emp_lahir))}}
                                    </label>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Mobile Phone</label>
                                    <label class="col-lg-9 col-xl-6 col-form-label font-weight-bold">
                                        {{$emp_detail->phone}} @if($emp_detail->phone2!='') / {{$emp_detail->phone2}}@endif
                                    </label>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Phone</label>
                                    <label class="col-lg-9 col-xl-6 col-form-label font-weight-bold">
                                        {{$emp_detail->phoneh}}
                                    </label>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Bank Account</label>
                                    <label class="col-lg-9 col-xl-6 col-form-label font-weight-bold">
                                        {{ isset($master_banks[$emp_detail->bank_code]) ? $master_banks[$emp_detail->bank_code] : "" }} {{"- [".$emp_detail->bank_code."]".$emp_detail->bank_acct}}
                                    </label>
                                </div>
                                <div class="row">
                                    <label class="col-xl-3"></label>
                                    <label class="col-lg-9 col-xl-6">
                                        <h5 class="font-weight-bold mt-10 mb-6">Attendance Data</h5>
                                    </label>
                                </div>
                                @if (!empty($user_emp))
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Attendance Password</label>
                                    <label class="col-lg-9 col-xl-6 col-form-label font-weight-bold">
                                        [{{ $user_emp->attend_code ?? "Please re-assign account for this employee" }}]
                                    </label>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right"></label>
                                    <label class="col-lg-9 col-xl-6 col-form-label font-weight-bold">
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
                                    </label>
                                </div>
                                @else
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Attendance Password</label>
                                    <label class="col-lg-9 col-xl-6 col-form-label font-weight-bold">
                                        Assign account for this employee in Profile Management
                                    </label>
                                </div>
                                @endif
                                <div class="row">
                                    <label class="col-xl-3"></label>
                                    <label class="col-lg-9 col-xl-6">
                                        <h5 class="font-weight-bold mt-10 mb-6">Employee Detail</h5>
                                    </label>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">NIK</label>
                                    <label class="col-lg-9 col-xl-6 col-form-label font-weight-bold">
                                        {{$emp_detail->emp_id}}
                                    </label>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Employmeent</label>
                                    <label class="col-lg-9 col-xl-6 col-form-label font-weight-bold">
                                        @switch(substr($emp_detail->emp_id,4,1))
                                            @case('K')
                                                Contract
                                                @break
                                            @case('C')
                                                Consultant
                                                @break
                                            @default
                                                Permanent
                                        @endswitch
                                    </label>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Rank</label>
                                    <label class="col-lg-9 col-xl-6 col-form-label font-weight-bold">
                                        {{$emp_detail->emp_type}}
                                    </label>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Position</label>
                                    <label class="col-lg-9 col-xl-6 col-form-label font-weight-bold">
                                        {{$emp_detail->emp_position}}
                                    </label>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Tax Status</label>
                                    <label class="col-lg-9 col-xl-6 col-form-label font-weight-bold">
                                        {{$emp_detail->tax_status}}
                                    </label>
                                </div>
                                @foreach ($variables as $item)
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">{{ $item->parameter_name }}</label>
                                    <label class="col-lg-9 col-xl-6 col-form-label font-weight-bold">
                                        {{ (isset($var_val[$item->id])) ? $var_val[$item->id] : "" }}
                                    </label>
                                </div>
                                @endforeach
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Send PPE</label>
                                    <div class="col-lg-9 col-xl-6 {{ (!empty($do) && !empty($do->approved_time)) ? 'col-form-label' : "" }} font-weight-bold">
                                        @if (empty($do) || empty($do->approved_time))
                                            <button type="button" id="btn-send-ppe" class="btn btn-sm btn-primary">Share Link</button>
                                            @if (!empty($ppe))
                                            <button type="button" id="btn-disable-ppe" class="btn btn-sm {{ ($ppe->enable == 1) ? "btn-danger" : "btn-success" }}">{{ ($ppe->enable == 1) ? "Disable Request" : "Enable Request" }}</button>
                                            @endif
                                        @else
                                            Delivered
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!--end::Body-->
                        </form>
                        <!--end::Form-->
                    </div>
                    <div class ="tab-pane fade" id="profile-management" role="tabpanel">
                        <div class="card-header py-3">
                            <div class="card-title align-items-start flex-column">
                                <h3 class="card-label font-weight-bolder text-dark">Profile Management</h3>
                            </div>
                        </div>
                        <form class="form" method="post" action="{{route('employee.update',['id'=>$emp_detail->id])}}">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <label class="col-xl-3"></label>
                                    <div class="col-lg-9 col-xl-6">
                                        <!-- <h5 class="font-weight-bold mb-6">Customer Info</h5> -->
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-xl-3"></label>
                                    <div class="col-lg-9 col-xl-6">
                                        <h5 class="font-weight-bold mb-6">{{ucwords($emp_detail->emp_name)}}</h5>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Full Name</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" type="text" name="emp_name" value="{{ucwords($emp_detail->emp_name)}}" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Email</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" type="email" name="email" value="{{$emp_detail->email}}" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Address</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <textarea name="address" class="form-control">{{$emp_detail->address}}</textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Religion</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control" name="religion">
                                            <option value="">- Select Religion -</option>
                                            <option value="islam" @if($emp_detail->religion == 'islam') SELECTED @endif>Islam</option>
                                            <option value="katolik" @if($emp_detail->religion == 'katolik') SELECTED @endif>Katolik</option>
                                            <option value="protestan" @if($emp_detail->religion == 'protestan') SELECTED @endif>Protestan</option>
                                            <option value="hindu" @if($emp_detail->religion == 'hindu') SELECTED @endif>Hindu</option>
                                            <option value="budha" @if($emp_detail->religion == 'budha') SELECTED @endif>Budha</option>
                                            <option value="lain" @if($emp_detail->religion == 'lain') SELECTED @endif>Lain-lain</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Date of Birth</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" type="date" name="lahir" value="{{$emp_detail->emp_lahir}}" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Mobile Phone 1</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" type="text" name="phone" value="{{$emp_detail->phone}}" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Mobile Phone 2</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" type="text" name="phone2" value="{{$emp_detail->phone2}}" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Phone</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" type="text" name="phoneh" value="{{$emp_detail->phoneh}}" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Bank Account</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control select2" name="bankCode">
                                            @foreach ($master_banks as $kode_bank => $nama_bank)
                                                <option value="{{ $kode_bank }}" {{ ($emp_detail->bank_code == $kode_bank) ? "SELECTED" : "" }}>[{{ $kode_bank }}] {{ $nama_bank }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Account Number</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" type="text" name="bank_acct" value="{{$emp_detail->bank_acct}}" />
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-xl-3"></label>
                                    <div class="col-lg-9 col-xl-6">
                                        <h5 class="font-weight-bold mt-10 mb-6">Employee Detail</h5>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">NIK</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="text" class="form-control" name="emp_id" id="emp_id" value="{{$emp_detail->emp_id}}" readonly="" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Employment Status</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control status" id="emp_status" name="emp_status" required>
                                            <option value=''>- Select Employee Status -</option>
                                            <option value='kontrak' @if($status == 'K') SELECTED @endif>Contract</option>
                                            <option value='konsultan' @if($status == 'C') SELECTED @endif >Consultant</option>
                                            <option value='tetap' @if($status != 'K' && $status != 'C') SELECTED @endif>Permanent</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Employee Type</label>
                                    <div class="col-lg-9 col-xl-6">
                                            <select class="form-control" name="emp_type" id="emp_type" required>
                                                <option value="">- Select Employee Type -</option>
                                                @foreach($emptypes as $key => $val)
                                                    <option value="{{$val->id}}" @if($emp_detail->emp_type == $val->id) selected @endif>{{$val->name}}</option>
                                                @endforeach
                                            </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Division</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control status" id="division" name="division" required>
                                            <option value=''>- Select Division -</option>
                                            @foreach($divisions as $key => $val)
                                                <option value="{{$val->id}}" @if($val->id == $emp_detail->division) SELECTED @endif>{{$val->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Position</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="text" class="form-control" name="emp_position" value="{{$emp_detail->emp_position}}" id="position" readonly />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Office</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select name="office" class="form-control select2">
                                            <option value="">- Select Office -</option>
                                            @foreach ($office as $wh_id => $wh_name)
                                                <option value="{{ $wh_id }}" {{ ($emp_detail->id_wh == $wh_id) ? "SELECTED" : "" }}>{{ $wh_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">User</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select name="user_emp" class="form-control select2" id="user-emp">
                                            <option value="">- Select User -</option>
                                            @foreach ($user_list as $usercomp)
                                                <option value="{{ $usercomp->id }}" {{ ($emp_detail->id == $usercomp->emp_id) ? "SELECTED" : "" }}>{{ $usercomp->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">From Sister Company</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select name="company_sister" id="company-sister" class="form-control select2">
                                            <option value="">- Select Company -</option>
                                            @foreach ($child_comp as $id_comp => $name_comp)
                                                <option value="{{ $id_comp }}" {{ (!empty($emp_sister) && $emp_sister->company_id == $id_comp) ? "SELECTED" : "" }}>{{ $name_comp }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" id="emp-div">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Employee Name</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select name="emp_sister" id="emp-sister" class="form-control" data-placeholder="Select Employee">
                                            @if (!empty($emp_sister))
                                                <option value="{{ $emp_sister->id }}">{{ $emp_sister->emp_name }}</option>
                                            @else
                                                <option value=""></option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                @foreach ($variables as $item)
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">{{ $item->parameter_name }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        @if ($item->parameter_type == "string")
                                            <input value="{{ (isset($var_val[$item->id])) ? $var_val[$item->id] : "" }}" type="text" class="form-control" maxlength="{{ $item->parameter_length }}" name="param[{{ $item->id }}]">
                                        @elseif ($item->parameter_type == "decimal")
                                            <input value="{{ (isset($var_val[$item->id])) ? $var_val[$item->id] : "" }}" type="text" class="form-control number-decimal" maxlength="{{ ($item->parameter_length) + (round($item->parameter_length) / 3) + 2 }}" name="param[{{ $item->id }}]">
                                        @elseif ($item->parameter_type == "integer")
                                            <input value="{{ (isset($var_val[$item->id])) ? $var_val[$item->id] : "" }}" type="text" class="form-control number-int" maxlength="{{ $item->parameter_length }}" name="param[{{ $item->id }}]">
                                        @elseif ($item->parameter_type == "date")
                                            <input value="{{ (isset($var_val[$item->id])) ? $var_val[$item->id] : "" }}" type="date" class="form-control" name="param[{{ $item->id }}]">
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right"></label>
                                    <div class="col-lg-9 col-xl-6">
                                        <button type="submit" name="editProfile" class="btn btn-primary"><i class="fa fa-pencil-alt"></i> Edit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class ="tab-pane fade" id="attachment-management" role="tabpanel">
                        <div class="card-header py-3">
                            <h3 class="card-title font-weight-bolder text-dark">Attachment Management</h3>
                        </div>
                        <form class="form" method="post" id="form-attach" action="{{route('employee.updateAttach',['id'=>$emp_detail->id])}}" enctype="multipart/form-data">
                            @csrf
                            <div class="row m-5">
                                <div class="col-md-4">
                                    <div class="form-group align-items-center">
                                        <center><label>Profile photo</label></center>
                                        @if(!empty($emp_detail->picture))
                                            <center>
                                                <img src="{{str_replace("public", "public_html", asset("media/employee_attachment/".$emp_detail->picture))}}" id="prev_eq1" alt="Photo" class="img-thumbnail img-responsive center-block">
                                            </center>
                                            @actionStart('employee', 'update')
                                            <input type="file" class="form-control" name="picture" id="picture1" multiple accept='image/*' placeholder="">
                                            @actionEnd
                                            <br>
                                            <label class="checkbox">
                                                <input type="checkbox" name="delete_picture" value="on" />
                                                <span></span> &nbsp;Check to delete the picture
                                            </label>
                                        @else
                                            <center><img src="" id="prev_eq1" alt="Photo" class="img-thumbnail img-responsive center-block" ></center>
                                            @actionStart('employee', 'update')
                                            <input type="file" class="form-control" name="picture" id="picture1" multiple accept='image/*' placeholder="">
                                            @actionEnd
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group align-items-center">
                                        <center><label>Identity Card</label></center>
                                        @if(!empty($emp_detail->ktp))
                                            <center>
                                                <img src="{{str_replace("public", "public_html", asset("media/employee_attachment/".$emp_detail->ktp))}}" id="prev_eq2" alt="Photo" class="img-thumbnail img-responsive center-block">
                                            </center>
                                            @actionStart('employee', 'update')
                                            <input type="file" class="form-control" name="ktp" id="picture2" multiple accept='image/*' placeholder="">
                                            @actionEnd
                                            <br>
                                            <label class="checkbox">
                                                <input type="checkbox" name="delete_ktp" value="on" />
                                                <span></span> &nbsp;Check to delete identity
                                            </label>
                                        @else
                                            <center><img src="" id="prev_eq2" alt="Photo" class="img-thumbnail img-responsive center-block" ></center>
                                            @actionStart('employee', 'update')
                                            <input type="file" class="form-control" name="ktp" id="picture2" multiple accept='image/*' placeholder="">
                                            @actionEnd
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group align-items-center">
                                        <center><label>Certificate</label></center>
                                        @if(!empty($emp_detail->serti1))
                                            <center>
                                                <img src="{{str_replace("public", "public_html", asset("media/employee_attachment/".$emp_detail->serti1))}}" id="prev_eq3" alt="Photo" class="img-thumbnail img-responsive center-block">
                                            </center>
                                            @actionStart('employee', 'update')
                                            <input type="file" class="form-control" name="serti1" id="picture3" multiple accept='image/*' placeholder="">
                                            @actionEnd
                                            <br>
                                            <label class="checkbox">
                                                <input type="checkbox" name="delete_sertif" value="on" />
                                                <span></span> &nbsp;Check to delete certificate
                                            </label>
                                        @else
                                            <center><img src="" id="prev_eq3" alt="Photo" class="img-thumbnail img-responsive center-block" ></center>
                                            @actionStart('employee', 'update')
                                            <input type="file" class="form-control" name="serti1" id="picture3" multiple accept='image/*' placeholder="">
                                            @actionEnd
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row m-5">
                                <div class="col-md-12 text-right">
                                @actionStart('employee', 'update')
                                    <button type="submit" id="btnSave" name="submit" class="btn btn-primary mr-2" onclick="submitForm()">Save Changes</button>
                                @actionEnd
                                </div>
                            </div>
                        </form>
                        <hr>
                        <div class="modal fade" id="addDocument" tabindex="-1" role="dialog" aria-labelledby="addDocument" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Add Document</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <i aria-hidden="true" class="ki ki-close"></i>
                                        </button>
                                    </div>
                                    <form method="post" action="{{route('employee.storeCV')}}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <input type="hidden" name="id_emp" value="{{$emp_detail->id}}">
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group row">
                                                        <label class="col-md-2 col-form-label text-right">Add File</label>
                                                        <div class="col-md-6">
                                                            <input type="file" class="form-control" placeholder="Document" multiple name="document[]">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                                            <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                                                <i class="fa fa-check"></i>
                                                Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="addVaccin" tabindex="-1" role="dialog" aria-labelledby="addVaccin" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Add Vaccine</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <i aria-hidden="true" class="ki ki-close"></i>
                                        </button>
                                    </div>
                                    <form method="post" action="{{route('employee.storeVaccine')}}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <input type="hidden" name="id_emp" value="{{$emp_detail->id}}">
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group row">
                                                        <label class="col-md-3 col-form-label text-right">Vaccine date</label>
                                                        <div class="col-md-9">
                                                            <input type="date" class="form-control" name="_date" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-3 col-form-label text-right">Vaccine Type</label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" name="_type" required placeholder="Vaccine Type">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-3 col-form-label text-right">Vaccine count</label>
                                                        <div class="col-md-9">
                                                            <input type="number" class="form-control" placeholder="Vaccine count" name="_count" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-3 col-form-label text-right">Add File</label>
                                                        <div class="col-md-9">
                                                            <input type="file" class="form-control" placeholder="Document" accept="image/*" name="document" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                                            <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                                                <i class="fa fa-check"></i>
                                                Add</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-10">
                                    <h3>Other Files</h3>
                                </div>
                                <div class="col-md-2">
                                    @actionStart('employee', 'update')
                                    <button type="button" class="btn btn-primary mr-2" data-toggle="modal" data-target="#addDocument">Add New</button>
                                    @actionEnd
                                </div>
                            </div>

                            <br><br>
                            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                <table class="table table-bordered table-hover display font-size-sm data_emp" style="margin-top: 13px !important; width: 100%;">
                                    <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th nowrap="nowrap">File Name</th>
                                        <th nowrap="nowrap" class="text-center">Upload Date</th>
                                        <th nowrap="nowrap" class="text-center">Upload By</th>
                                        <th nowrap="nowrap" class="text-center">Download</th>
                                        @actionStart('employee', 'delete')
                                        <th nowrap="nowrap" class="text-center">#</th>
                                        @actionEnd
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($emp_cv as $keyCV => $valCV)
                                    @php
                                        $cv_name = $valCV->cv_name;
                                        $link = route('download', $valCV->cv_address);
                                        $file = false;
                                        if (empty($valCV->cv_name)) {
                                            $address = explode("/", $valCV->cv_address);
                                            $cv_name = end($address);
                                            $link = str_replace("public", 'public_html', asset('media/employee_attachment/'.$cv_name));
                                            $handle = @fopen($link, 'r');
                                            if($handle){
                                                $file = true;
                                            }
                                        } else {
                                            $f = "";
                                            if(isset($file_name[$valCV->cv_address])){
                                                $_file = str_replace("public", 'public_html', asset('media/employee_attachment/'.$file_name[$valCV->cv_address]));
                                                $handle = @fopen($link, 'r');
                                                if($handle){
                                                    $file = true;
                                                }
                                            }
                                        }


                                    @endphp
                                    @if ($file)
                                    <tr>
                                        <td class="text-center">{{($keyCV+1)}}</td>
                                        <td nowrap="nowrap">{{$cv_name}}</td>
                                        <td nowrap="nowrap" class="text-center">{{date('d-m-Y',strtotime($valCV->date_time))}}</td>
                                        <td nowrap="nowrap" class="text-center">{{$valCV->whom}}</td>
                                        <td nowrap="nowrap" class="text-center">
                                            <a href="{{ $link }}" target="_blank" class="btn btn-sm btn-success"><i class="fa fa-download">
                                                    &nbsp; Download</i>
                                            </a>
                                        </td>
                                        @actionStart('employee', 'delete')
                                        <td nowrap="nowrap" class="text-center">
                                            <a href="{{route('employee.deleteCV',['id'=> $valCV->id])}}" onclick="return confirm('Delete File?')" class="btn btn-sm btn-icon btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                        @actionEnd
                                    </tr>
                                    @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <hr>
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-10">
                                    <h3>Vaccination files</h3>
                                </div>
                                <div class="col-md-2">
                                    @actionStart('employee', 'update')
                                    <button type="button" class="btn btn-primary mr-2" data-toggle="modal" data-target="#addVaccin">Add New</button>
                                    @actionEnd
                                </div>
                            </div>

                            <br><br>
                            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                <table class="table table-bordered table-hover display font-size-sm data_emp" style="margin-top: 13px !important; width: 100%;">
                                    <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th nowrap="nowrap">File Name</th>
                                        <th nowrap="nowrap" class="text-center">Upload Date</th>
                                        <th nowrap="nowrap" class="text-center">Upload By</th>
                                        <th nowrap="nowrap" class="text-center">Download</th>
                                        @actionStart('employee', 'delete')
                                        <th nowrap="nowrap" class="text-center">#</th>
                                        @actionEnd
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($vaccine as $keyCV => $valCV)
                                    @php
                                        $cv_name = $valCV->cv_name;
                                        $link = route('download', $valCV->cv_address);
                                        if (empty($valCV->cv_name)) {
                                            $address = explode("/", $valCV->cv_address);
                                            $cv_name = end($address);
                                            $link = str_replace("public", 'public_html', asset('media/employee_attachment/'.$cv_name));
                                        }
                                    @endphp
                                        <tr>
                                            <td class="text-center">{{($keyCV+1)}}</td>
                                            <td nowrap="nowrap">{{$cv_name}}</td>
                                            <td nowrap="nowrap" class="text-center">{{date('d-m-Y',strtotime($valCV->date_time))}}</td>
                                            <td nowrap="nowrap" class="text-center">{{$valCV->created_by}}</td>
                                            <td nowrap="nowrap" class="text-center">
                                                <a href="{{ $link }}" target="_blank" class="btn btn-sm btn-success"><i class="fa fa-download">
                                                        &nbsp; Download</i>
                                                </a>
                                            </td>
                                            @actionStart('employee', 'delete')
                                            <td nowrap="nowrap" class="text-center">
                                                <a href="{{route('employee.deleteCV',['id'=> $valCV->id])}}" onclick="return confirm('Delete File?')" class="btn btn-sm btn-icon btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                            @actionEnd
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="cv-management" role="tabpanel">
                        <div class="card card-custom gutter-b">
                            <div class="card-header">
                                <h3 class="card-title card-label font-weight-bolder text-dark">CV Management</h3>
                                <div class="card-toolbar btn-group">
                                    <a href="javascript:framePrint('cvPrint')" class="btn btn-info mr-2">Print CV</a>
                                    @actionStart('employee', 'update')
                                    <button type="button" data-toggle="modal" data-target="#cvModal" class="btn btn-primary mr-2">Add CV</button>
                                    @actionEnd
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-hover table-bordered display">
                                            <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th class="text-center">Description</th>
                                                <th class="text-center">Start Date</th>
                                                <th class="text-center">End Date</th>
                                                <th class="text-center">Download</th>
                                                <th class="text-center"></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($cv as $i => $item)
                                                <tr>
                                                    <td align="center">{{$i+1}}</td>
                                                    <td align="center">{{$item->description}}</td>
                                                    <td align="center">{{date('d F Y', strtotime($item->start_date))}}</td>
                                                    <td align="center">{{date('d F Y', strtotime($item->end_date))}}</td>
                                                    <td align="center">
                                                        @if(!empty($item->document))
                                                            <a href="{{route('download', $item->document)}}" class="btn btn-xs btn-icon btn-primary"><i class="fa fa-download"></i></a>
                                                        @endif
                                                    </td>
                                                    <td align="center">
                                                        <a href="{{route('employee.cv_delete', $item->id)}}" class="btn btn-xs btn-icon btn-danger"><i class="fa fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <iframe src="{{route('employee.cv_print', $emp_detail->id)}}" id="cvPrint" name="cvPrint" height="0" width="0"></iframe>
                        <!-- Modal-->
                        <div class="modal fade" id="cvModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title">Add CV</h3>
                                    </div>
                                    <form action="{{route('employee.cv')}}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-group row">
                                                <label for="" class="col-form-label col-md-3">Description</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" name="description" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="" class="col-form-label col-md-3">Type</label>
                                                <div class="col-md-9">
                                                    <select name="type" class="form-control select2" id="">
                                                        <option value="1">Education</option>
                                                        <option value="2">Job Experience</option>
                                                        <option value="3">Certificate</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="" class="col-form-label col-md-3">Start Date</label>
                                                <div class="col-md-9">
                                                    <input type="date" class="form-control" name="start_date" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="" class="col-form-label col-md-3">End Date</label>
                                                <div class="col-md-9">
                                                    <input type="date" class="form-control" name="end_date" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="" class="col-form-label col-md-3">Document</label>
                                                <div class="col-md-9">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" name="document">
                                                        <span class="custom-file-label">Choose File</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <input type="hidden" name="emp_id" value="{{$emp_detail->id}}">
                                            <button type="button" class="btn btn-light-primary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Add</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class ="tab-pane fade" id="join-management" role="tabpanel">
                        <div class="card-header py-3">
                            <div class="row">
                                <div class="card-title align-items-start flex-column col-md-10">
                                    <h3 class="card-label font-weight-bolder text-dark">Join Date Management</h3>
                                </div>
                            </div>
                        </div>
                        <form method="post" action="{{route('employee.updateJoinDate',['id' => $emp_detail->id])}}">
                            @csrf
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-3 control-label text-right">Join Date</label>
                                    <label class="col-sm-9 control-label font-weight-bolder">{{(isset($emp_detail_history->act_date))? date('d F Y', strtotime($emp_detail_history->act_date)) : ''}}</label>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 control-label text-right">New Join Date</label>
                                    <div class="col-sm-4">
                                        <input type="date" name="date" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 control-label text-right"></label>
                                    <div class="col-sm-3">
                                        @actionStart('employee', 'update')
                                        <button type="submit" class="btn btn-primary" name="edit_date">Submit</button>
                                        @actionEnd
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class ="tab-pane fade" id="financial-management" role="tabpanel">
                        @if(!(session()->has('seckey_empfin')) || (session()->has('seckey_empfin') < 10))
                            @include('ha.needsec.index', ["type" => "empfin"])
                        @else
                            <form method="post" action="{{route('employee.updateFinMan',['id' =>$emp_detail->id])}}">
                                <div class="card-header py-3">
                                    <div class="row">
                                        <div class="card-title align-items-start flex-column col-md-10">
                                            <h3 class="card-label font-weight-bolder text-dark">Financial Management</h3>
                                        </div>
                                    </div>
                                </div>
                                @csrf
                                <input type="hidden" name="id" value="{{$emp_detail->id}}">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            @php
                                                /** @var TYPE_NAME $emp_detail */
                                                $SAL       = base64_decode($emp_detail->salary);
                                                $TRANSPORT = base64_decode($emp_detail->transport);
                                                $MEAL      = base64_decode($emp_detail->meal);
                                                $HOUSE     = base64_decode($emp_detail->house);
                                                $HEALTH    = base64_decode($emp_detail->health);

                                             $thp = $SAL+$TRANSPORT+$MEAL+$HOUSE+$HEALTH
                                            @endphp
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">Take Home Pay</label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" name="thp" id="thp" placeholder="" required value="{{$thp}}">
                                                    <div id="breakdown"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">Position Allowance</label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" name="pa" id="pa" placeholder="" value="{{$emp_detail->allowance_office}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">Health Insurance</label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" name="hi" id="hi" placeholder="" value="{{$emp_detail->health_insurance}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">Jamsostek</label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" name="jam" id="jam" placeholder="" value="{{$emp_detail->jamsostek}}">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">Pension</label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" name="pensi" id="pensi" placeholder="" value="{{$emp_detail->pension}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">Perfomance Bonus</label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" name="yb" id="yb" placeholder="" value="{{$emp_detail->yearly_bonus}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">Over Time</label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" name="overtime" id="overtime" placeholder="" value="{{$emp_detail->overtime}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">Voucher</label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" name="voucher" id="voucher" placeholder="" value="{{$emp_detail->voucher}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="card-header py3">
                                    <div class="row">
                                        <div class="card-title align-items-start flex-column col-md-10">
                                            <h3 class="card-label font-weight-bolder text-dark">Field, Warehouse, ODO Rate</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="" class="col-form-label">Field</label>
                                                <input type="text" class="form-control number" id="fld-rate" name="field_rate" value="{{ $emp_detail->fld_bonus }}">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="" class="col-form-label">Warehouse</label>
                                                <input type="text" class="form-control number" id="wh-rate" name="wh_rate" value="{{ $emp_detail->wh_bonus }}">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="" class="col-form-label">ODO</label>
                                                <input type="text" class="form-control number" id="odo-rate" name="odo_rate" value="{{ $emp_detail->odo_bonus }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-header py-3">
                                    <div class="row">
                                        <div class="card-title align-items-start flex-column col-md-10">
                                            <h3 class="card-label font-weight-bolder text-dark">Financial Travel</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <h3 class="card-label font-weight-bolder text-dark text-center mr-48">Domestic</h3>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">Meal</label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" name="dom_meal" id="dom_meal" placeholder="" required value="{{$emp_detail->dom_meal}}">
                                                    <div id="breakdown"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">Spending</label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" name="dom_spending" id="dom_spending" placeholder="" value="{{$emp_detail->dom_spending}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">Overnight</label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" name="dom_overnight" id="dom_overnight" placeholder="" value="{{$emp_detail->dom_overnight}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <h3 class="card-label font-weight-bolder text-dark text-center mr-48">Overseas</h3>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">Meal</label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" name="ovs_meal" id="ovs_meal" placeholder="" required value="{{$emp_detail->ovs_meal}}">
                                                    <div id="breakdown"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">Spending</label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" name="ovs_spending" id="ovs_spending" placeholder="" value="{{$emp_detail->ovs_spending}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">Overnight</label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" name="ovs_overnight" id="ovs_overnight" placeholder="" value="{{$emp_detail->ovs_overnight}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="card-header py-3">
                                    <div class="row">
                                        <div class="card-title align-items-start flex-column col-md-10">
                                            <h3 class="card-label font-weight-bolder text-dark">Local Transport</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <h3 class="card-label font-weight-bolder text-dark text-center mr-48">Domestic</h3>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">Airport</label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" name="dom_transport_airport" id="dom_transport_airport" placeholder="" required value="{{$emp_detail->dom_transport_airport}}">
                                                    <div id="breakdown"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">Train</label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" name="dom_transport_train" id="dom_transport_train" placeholder="" value="{{$emp_detail->dom_transport_train}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">Bus</label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" name="dom_transport_bus" id="dom_transport_bus" placeholder="" value="{{$emp_detail->dom_transport_bus}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">WH Cileungsi</label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" name="dom_transport_cil" id="dom_transport_cil" placeholder="" value="{{$emp_detail->dom_transport_cil}}">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <h3 class="card-label font-weight-bolder text-dark text-center mr-48">Overseas</h3>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">Airport</label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" name="ovs_transport_airport" id="ovs_transport_airport" placeholder="" required value="{{$emp_detail->ovs_transport_airport}}">
                                                    <div id="breakdown"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">Train</label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" name="ovs_transport_train" id="ovs_transport_train" placeholder="" value="{{$emp_detail->ovs_transport_train}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">Bus</label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" name="ovs_transport_bus" id="ovs_transport_bus" placeholder="" value="{{$emp_detail->ovs_transport_bus}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">WH Cileungsi</label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" name="ovs_transport_cil" id="ovs_transport_cil" placeholder="" value="{{$emp_detail->ovs_transport_cil}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" name="submit" class="btn btn-success font-weight-bold">
                                        <i class="fa fa-check"></i>
                                        Update</button>
                                </div>
                            </form>
                        @endif
                    </div>
                    <div class ="tab-pane fade" id="insurance-management" role="tabpanel">
                        @if(!(session()->has('seckey_insurance')) || (session()->has('seckey_insurance') < 10))
                            @include('ha.needsec.index', ["type" => "insurance"])
                        @else
                            <div class="card-header py-3">
                                <div class="row">
                                    <div class="card-title align-items-start flex-column col-md-10">
                                        <h3 class="card-label font-weight-bolder text-dark">Insurance Management</h3>
                                    </div>
                                </div>
                            </div>
                            <form method="post" action="{{route('employee.updateInsurance',['id' =>$emp_detail->id])}}">
                                @csrf
                                <input type="hidden" name="id" value="{{$emp_detail->id}}">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="ml-48">
                                                <h4><b>Allowance</b></h4>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">BPJS TK</label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" name="allow_bpjs_tk" id="allow_bpjs_tk" placeholder="" required value="{{$emp_detail->allow_bpjs_tk}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">BPJS KES</label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" name="allow_bpjs_kes" id="allow_bpjs_kes" placeholder="" value="{{$emp_detail->allow_bpjs_kes}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">JSHK</label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" name="allow_jshk" id="allow_jshk" placeholder="" value="{{$emp_detail->allow_jshk}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">PPH21</label>
                                                <div class="col-sm-8">

                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="ml-48">
                                                <h4><b>Deduction</b></h4>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label"></label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" name="deduc_bpjs_tk" id="deduc_bpjs_tk" placeholder="" value="{{$emp_detail->deduc_bpjs_tk}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label"></label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" name="deduc_bpjs_kes" id="deduc_bpjs_kes" placeholder="" value="{{$emp_detail->deduc_bpjs_kes}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label"></label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" name="deduc_jshk" id="deduc_jshk" placeholder="" value="{{$emp_detail->deduc_jshk}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label"></label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" name="deduc_pph21" id="deduc_pph21" placeholder="" value="{{$emp_detail->deduc_pph21}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" name="submit" class="btn btn-success font-weight-bold">
                                        <i class="fa fa-check"></i>
                                        Update</button>
                                </div>
                            </form>
                        @endif

                    </div>
                </div>
            </div>
        </div>
        <!--end::Content-->
    </div>
@endsection
@section('custom_script')
    <script src="{{ asset('assets/jquery-number/jquery.number.js') }}"></script>
    <script>
        function change_pos(){
            var position = $("#emp_type option:selected").html();
            var division = $("#division option:selected").html();

            $('#position').val(position+" "+division)
        }
        function framePrint(whichFrame) {
            window.frames[whichFrame].focus();
            window.frames[whichFrame].print();
        }
        $(document).ready(function () {
            $(".number").number(true, 2, '.', '')

            @if (empty($emp_detail->emp_id_sister))
                $("#emp-div").hide()
            @endif

            $("#fld-rate").on('keyup', function(){
                var fld = this.value
                console.log(fld)
                var wh = (1/3) * fld
                $("#wh-rate").val(wh)
                var odo = fld * 1.5
                $("#odo-rate").val(odo)
            })
            $("select.select2").select2({
                width: '100%'
            })
             var hash = location.hash.replace(/^#/, '');  // ^ means starting, meaning only match the first hash
            if (hash) {
                $('.nav-tabs a[href="#' + hash + '"]').tab('show');
            }

            // Change hash for page-reload
            $('.nav-tabs a').on('shown.bs.tab', function (e) {
                window.location.hash = e.target.hash;
            })
            $("table.display").DataTable({
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            })

            $('#emp_type').change(function () {
                change_pos()
            })
            $("#division").change(function() {
                change_pos()
            })
            $('#thp').bind('keypress keyup', function() {
                var nilai = $(this).val();
                $.ajax({
                    url: "{{ route('employee.thp') }}",
                    type: 'GET',
                    data: {
                        thp: nilai,
                    },
                    success: function(response){
                        var res = JSON.parse(response);

                        $("#breakdown").html(res.data);
                    }
                });
            });
            $("#emp_status").change(function(){
                var status = $("#emp_status").val();
                // console.log(status);
                $.ajax({
                    url: "{{ route('employee.nik') }}",
                    type: 'GET',
                    data: {
                        emp_status: status,
                    },
                    success: function(response){
                        var res = JSON.parse(response);
                        $("#emp_id").val(res.data);
                    }
                });
            });
            if ($("#prev_eq1").src < 0) {
                this.hide();
            }

            if ($("#prev_eq2").src < 0) {
                this.hide();
            }

            if ($("#prev_eq3").src < 0) {
                this.hide();
            }

            $("#picture1").change(function(){
                console.log($(this).val());
                if ($(this).val()) {
                    readURL(this, 1);
                    $("#prev_eq1").show();
                } else {
                    $("#prev_eq1").hide();
                }
            });

            $("#picture2").change(function(){
                if ($(this).val()) {
                    readURL(this, 2);
                    $("#prev_eq2").show();
                } else {
                    $("#prev_eq2").hide();
                }
            });

            $("#picture3").change(function(){
                if ($(this).val()) {
                    readURL(this, 3);
                    $("#prev_eq3").show();
                } else {
                    $("#prev_eq3").hide();
                }
            });
            function readURL(input, sec) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#prev_eq' + sec).attr('src', e.target.result);
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#company-sister").change(function(){
                if($(this).val() != ""){
                    $("#emp-div").show()
                    $("#emp-sister").prop('required', true)
                    $("#emp-sister").val('')

                    $("#emp-sister").select2({
                        ajax : {
                            url : "{{ route('employee.comp') }}/" + $("#company-sister").val(),
                            dataType : "json"
                        }
                    })
                } else {
                    $("#emp-div").hide()
                    $("#emp-sister").val('')
                    $("#emp-sister").prop('required', false)
                }
            })

            $("#btn-send-ppe").click(function(){
                $.ajax({
                    url : "{{ route('employee.hrd.generate_ppe') }}",
                    type : "post",
                    dataType : "json",
                    data : {
                        _token : "{{ csrf_token() }}",
                        emp_id : '{{ $emp_detail->id }}'
                    },
                    beforeSend : function(){
                        $("#btn-send-ppe").prop('disabled', true).text("Loading...").addClass("spinner spinner-left")
                    },
                    success : function(response){
                        $("#btn-send-ppe").prop('disabled', false).text("Send").removeClass("spinner spinner-left")
                        if(response.success){
                            var link = response.link
                            Swal.fire("Share this link to the employee", link, "success")
                        } else {
                            Swal.fire("Error", "", "error")
                        }
                    }
                })
            })

            $("#btn-disable-ppe").click(function(){
                $.ajax({
                    url : "{{ route('employee.hrd.disable_ppe') }}",
                    type : "post",
                    dataType : "json",
                    data : {
                        _token : "{{ csrf_token() }}",
                        emp_id : '{{ $emp_detail->id }}'
                    },
                    beforeSend : function(){
                        $("#btn-disable-ppe").prop('disabled', true).text("Loading...").addClass("spinner spinner-left")
                    },
                    success : function(response){
                        var btn_bg = ""
                        if(response.success){
                            var enable = (response.enable == 1) ? "enable" : "disable"
                            if(response.enable == 0){
                                btn_bg = "Enable Request"
                                $("#btn-disable-ppe").removeClass("btn-danger")
                                $("#btn-disable-ppe").addClass("btn-success")
                            } else {
                                btn_bg = "Disable Request"
                                $("#btn-disable-ppe").removeClass("btn-success")
                                $("#btn-disable-ppe").addClass("btn-danger")
                            }
                            Swal.fire("Disabled", "Request "+enable, "success")
                        } else {
                            Swal.fire("Error", "", "error")
                        }

                        $("#btn-disable-ppe").prop('disabled', false).text(btn_bg).removeClass("spinner spinner-left")
                    }
                })
            })
        });
        function submitForm(){
            $("#form-attach").submit();
        }
    </script>
@endsection
