@extends('layouts.template')
@section('content')
    <div class="d-flex flex-row">
        <div class="flex-row-auto offcanvas-mobile w-250px w-xxl-350px" id="kt_profile_aside">
            <!--begin::Profile Card-->
            <div class="card card-custom card-stretch">
                <!--begin::Body-->
                <div class="card-body pt-4">
                    <!--begin::User-->
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
                            <div class="symbol-label" style="background-image:url('{{asset('/hrd/uploads')}}/{{$emp_detail->picture}}')"></div>
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
                        <li class="nav-item mb-2">
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
                                            <div class="image-input-wrapper" style="background-image: url('{{asset('/hrd/uploads')}}/{{$emp_detail->picture}}')"></div>
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
                                        @switch($emp_detail->bank_code)
                                            @case('002')
                                                BRI
                                                @break
                                            @case('008')
                                                Mandiri
                                                @break
                                            @case('009')
                                                BNI
                                                @break
                                            @case('014')
                                                BCA
                                                @break
                                            @case('120')
                                                Bank Sumsel
                                                @break
                                        @endswitch
                                            &#13;&#10;{{"- [".$emp_detail->bank_code."]".$emp_detail->bank_acct}}
                                    </label>
                                </div>
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
                                        <select class="form-control" name="bankCode">
                                            <option value='002' @if($emp_detail->bank_code == '002') SELECTED @endif>BRI</option>
                                            <option value='008' @if($emp_detail->bank_code == '008') SELECTED @endif>Mandiri</option>
                                            <option value='009' @if($emp_detail->bank_code == '009') SELECTED @endif>BNI</option>
                                            <option value="014" @if($emp_detail->bank_code == '014') SELECTED @endif>BCA</option>
                                            <option value="120" @if($emp_detail->bank_code == '120') SELECTED @endif>BANK SUMSEL</option>
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
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Employmee Type</label>
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
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Rank</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control" name="emp_type">
                                            <option value="">- Select Employee Type -</option>
                                            <option value="staff" @if($emp_detail->emp_type == 'staff') SELECTED @endif>Staff</option>
                                            <option value="field" @if($emp_detail->emp_type == 'field') SELECTED @endif>Field</option>
                                            <option value="whbin" @if($emp_detail->emp_type == 'whbin') SELECTED @endif>Warehouse Bintaro</option>
                                            <option value="whcil" @if($emp_detail->emp_type == 'whcil') SELECTED @endif>Warehouse Cileungsi</option>
                                            <option value="manager" @if($emp_detail->emp_type == 'manager') SELECTED @endif>Manager</option>
                                            <option value="bod" @if($emp_detail->emp_type == 'bod') SELECTED @endif>BOD</option>
                                            <option value="konsultan" @if($emp_detail->emp_type == 'konsultan') SELECTED @endif>Konsultan</option>
                                            <option value="local" @if($emp_detail->emp_type == 'local') SELECTED @endif>Local Crew</option>
                                            <option value="marketing" @if($emp_detail->emp_type == 'projects') SELECTED @endif>Marketing</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Position</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="text" class="form-control" name="emp_position" value="{{$emp_detail->emp_position}}" />
                                    </div>
                                </div>
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
                            <div class="row">
                                <div class="card-title align-items-start flex-column col-md-10">
                                    <h3 class="card-label font-weight-bolder text-dark">Attachment Management</h3>
                                </div>
                                <div class="card-toolbar">
                                    <button type="button" id="btnSave" name="submit" class="btn btn-primary mr-2" onclick="submitForm()">Save Changes</button>
                                </div>
                            </div>
                        </div>
                        <form class="form" method="post" id="form-attach" action="{{route('employee.updateAttach',['id'=>$emp_detail->id])}}" enctype="multipart/form-data">
                            @csrf
                            <div class="row m-5">
                                <div class="col-md-4">
                                    <div class="form-group align-items-center">
                                        <center><label>Profile photo</label></center>
                                        @if(!empty($emp_detail->picture))
                                            <center>
                                                <img src="{{asset('/hrd/uploads')}}/{{$emp_detail->picture}}" id="prev_eq1" alt="Photo" class="img-thumbnail img-responsive center-block">
                                            </center>
                                            <input type="file" class="form-control" name="picture" id="picture1" multiple accept='image/*' placeholder="">
                                            <br>
                                            <label class="checkbox">
                                                <input type="checkbox" name="delete_picture" value="on" />
                                                <span></span> &nbsp;Check to delete the picture
                                            </label>
                                        @else
                                            <center><img src="" id="prev_eq1" alt="Photo" class="img-thumbnail img-responsive center-block" ></center>
                                            <input type="hidden" name="MAX_FILE_SIZE" value="200000" />
                                            <input type="file" class="form-control" name="picture" id="picture1" multiple accept='image/*' placeholder="">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group align-items-center">
                                        <center><label>Identity Card</label></center>
                                        @if(!empty($emp_detail->ktp))
                                            <center>
                                                <img src="{{asset('/hrd/uploads')}}/{{$emp_detail->ktp}}" id="prev_eq1" alt="Photo" class="img-thumbnail img-responsive center-block">
                                            </center>
                                            <input type="file" class="form-control" name="ktp" id="picture2" multiple accept='image/*' placeholder="">
                                            <br>
                                            <label class="checkbox">
                                                <input type="checkbox" name="delete_ktp" value="on" />
                                                <span></span> &nbsp;Check to delete identity
                                            </label>
                                        @else
                                            <center><img src="" id="prev_eq2" alt="Photo" class="img-thumbnail img-responsive center-block" ></center>
                                            <input type="hidden" name="MAX_FILE_SIZE" value="200000" />
                                            <input type="file" class="form-control" name="ktp" id="picture2" multiple accept='image/*' placeholder="">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group align-items-center">
                                        <center><label>Certificate</label></center>
                                        @if(!empty($emp_detail->serti1))
                                            <center>
                                                <img src="{{asset('/hrd/uploads')}}/{{$emp_detail->serti1}}" id="prev_eq1" alt="Photo" class="img-thumbnail img-responsive center-block">
                                            </center>
                                            <input type="file" class="form-control" name="serti1" id="picture3" multiple accept='image/*' placeholder="">
                                            <br>
                                            <label class="checkbox">
                                                <input type="checkbox" name="delete_sertif" value="on" />
                                                <span></span> &nbsp;Check to delete certificate
                                            </label>
                                        @else
                                            <center><img src="" id="prev_eq3" alt="Photo" class="img-thumbnail img-responsive center-block" ></center>
                                            <input type="hidden" name="MAX_FILE_SIZE" value="200000" />
                                            <input type="file" class="form-control" name="serti1" id="picture3" multiple accept='image/*' placeholder="">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>
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
                                    <label class="col-sm-9 control-label font-weight-bolder">{{date('d F Y', strtotime($emp_detail_history->act_date))}}</label>
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
                                        <button type="submit" class="btn btn-primary" name="edit_date">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class ="tab-pane fade" id="financial-management" role="tabpanel">

                    </div>
                </div>
            </div>
        </div>
        <!--end::Content-->
    </div>
@endsection
@section('custom_script')
    <script>
        $(document).ready(function () {
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
        });
        function submitForm(){
            $("#form-attach").submit();
        }
    </script>
@endsection
