@extends('layouts.template')

@section('content')
    <div class="d-flex flex-row">
        <div class="flex-row-auto offcanvas-mobile w-250px w-xxl-350px" id="kt_profile_aside">
            <div class="card card-custom card-stretch">
                <div class="card-body pt-4">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
                            <div class="symbol-label" style="background-image: url('{{(empty($company->app_logo)) ? asset('theme/assets/media/logos/default-c.png') : '../../../public_html/images/'.$company->app_logo}}')"></div>
                        </div>
                        <div>
                            <a href="#" class="font-weight-bolder font-size-h5 text-dark-75 text-hover-primary">{{$company->company_name}}</a>
                        </div>
                    </div>
                    <div class="py-4">

                    </div>
                    <ul class="nav nav-tabs nav-tabs-line">
                        <li class="nav-item mb-2 mr-30 active">
                            <a href="#company-profile" data-toggle="tab" class="nav-link py-4 active">
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
                                <span class="nav-text font-size-lg">Profile</span>
                            </a>
                        </li>
                        <li class="nav-item mb-2 mr-30">
                            <a href="#company-management" data-toggle="tab" class="nav-link py-4">
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
                        <li class="nav-item mb-2 mr-30">
                            <a href="{{route('preference',['id_company' => base64_encode($company->id)])}}" class="nav-link py-4">
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
                                <span class="nav-text font-size-lg">Preference</span>
                            </a>
                        </li>
                        <li class="nav-item mb-2 mr-30">
                            <a href="#user-management" data-toggle="tab" class="nav-link py-4">
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
                                <span class="nav-text font-size-lg">Users</span>
                            </a>
                        </li>
                        <li class="nav-item mb-2 mr-30">
                            <a href="{{route('company.role_controll', base64_encode($company->id))}}" class="nav-link py-4">
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
                                <span class="nav-text font-size-lg">Role Control</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="flex-row-fluid ml-lg-8">
            <div class="card card-custom card-stretch">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="company-profile" role="tabpanel">
                        <div class="card-header py-3">
                            <div class="card-title align-items-start flex-column">
                                <h3 class="card-label font-weight-bolder text-dark">Profile</h3>
                            </div>
                        </div>
                        <form class="form">
                            <div class="card-body">
                                <div class="row">
                                    <label class="col-xl-3"></label>
                                    <div class="col-lg-9 col-xl-6">
                                        <!-- <h5 class="font-weight-bold mb-6">Customer Info</h5> -->
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Logo</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <div class="image-input image-input-outline" id="kt_profile_avatar">
                                            <div class="image-input-wrapper" style="background-image:url('{{(empty($company->app_logo)) ? asset('theme/assets/media/logos/default-c.png') : '../../../public_html/images/'.$company->app_logo}}')"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-xl-3"></label>
                                    <div class="col-lg-9 col-xl-6">
                                        <h5 class="font-weight-bold mb-6">{{$company->company_name}}</h5>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Address</label>
                                    <label class="col-lg-9 col-xl-6 col-form-label font-weight-bold">{{$company->address}}</label>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">NPWP</label>
                                    <label class="col-lg-9 col-xl-6 col-form-label font-weight-bold">{{$company->npwp}}</label>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Phone</label>
                                    <label class="col-lg-9 col-xl-6 col-form-label font-weight-bold">{{$company->phone}}</label>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Email</label>
                                    <label class="col-lg-9 col-xl-6 col-form-label font-weight-bold">{{$company->email}}</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="company-management" role="tabpanel">
                        <div class="card-header py-3">
                            <div class="card-title align-items-start flex-column">
                                <h3 class="card-label font-weight-bolder text-dark">Profile Management</h3>
                            </div>
                        </div>
                        <form class="form" method="post" action="{{route('company.edit')}}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{$company->id}}">
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
                                        <h5 class="font-weight-bold mb-6">{{ucwords($company->company_name)}}</h5>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Company Name</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" type="text" name="company_name" value="{{ucwords($company->company_name)}}" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Email</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" type="email" name="email" value="{{$company->email}}" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Address</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <textarea name="address" class="form-control">{{$company->address}}</textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Company Phone</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" type="text" name="phone" value="{{$company->phone}}" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">NPWP</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" type="text" name="npwp" value="{{$company->npwp}}" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">TAG</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" type="text" name="company_tag" value="{{strtoupper($company->tag)}}" />
                                    </div>
                                </div>
                                @if($company->id != 1)
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 col-form-label text-right">Company Parent</label>
                                        <div class="col-lg-9 col-xl-6">
                                            <select name="parent" class="form-control">
                                                @foreach($companies as $value)
                                                    @if($value->id != $company->id)
                                                        <option value="{{$value->id}}" @if($company->id_parent == $value->id) SELECTED @endif>{{$value->company_name}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <span class="form-text text-muted">Please select parent company.</span>
                                            <div class="fv-plugins-message-container"></div>
                                        </div>
                                    </div>
                                @endif
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Printed Logo</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <div class="image-input image-input-outline" id="printed_logo">
                                            <div class="image-input-wrapper" style="background-image: url('{{URL::to('/') }}/images/{{$company->p_logo}}')"></div>
                                            <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change">
                                                <i class="fa fa-pen icon-sm text-muted"></i>
                                                <input type="file" name="p_logo" id="p_logo" accept=".png, .jpg, .jpeg" />
                                                <input type="hidden" name="p_logo_remove" />
                                            </label>
                                            <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel">
                                                <i class="ki ki-bold-close icon-xs text-muted"></i>
                                            </span>
                                        </div>
                                        <span class="form-text text-muted">
                                            This logo will be used when you print a document from Cypher. <br />
                                            Allowed file types: png, jpg, jpeg.
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Application Logo</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <div class="image-input image-input-outline" id="app_logo">
                                            <div class="image-input-wrapper" style="background-image: url('{{URL::to('/') }}/images/{{$company->app_logo}}')"></div>
                                            <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change">
                                                <i class="fa fa-pen icon-sm text-muted"></i>
                                                <input type="file" name="app_logo" id="app_logo" accept=".png, .jpg, .jpeg" />
                                                <input type="hidden" name="ap_logo_remove" />
                                            </label>
                                            <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel">
                                                <i class="ki ki-bold-close icon-xs text-muted"></i>
                                            </span>
                                        </div>
                                        <span class="form-text text-muted">
                                            This logo will be displayed in the Cypher application, we recommend using a square shaped logo. <br />
                                            Allowed file types: png, jpg, jpeg.
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Background Color</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="color" class="form-control" name="bgcolor" value="{{$company->bgcolor}}">
                                        <span class="form-text text-warning">
                                            <i class="fa fa fa-info-circle text-warning"></i>
                                            Caution: need relogin to take the effect
                                        </span>
                                    </div>
                                </div>
                                @if($company->id_parent != null)
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 col-form-label text-right">Inherit to parent</label>
                                        <div class="col-lg-9 col-xl-6">
                                            <div class="checkbox-inline col-form-label">
                                                <label class="checkbox checkbox-outline checkbox-success">
                                                    <input type="checkbox" {{($company->inherit == 1) ? "checked" : ""}} value="1" name="inherit"/>
                                                    <span></span>
                                                    is centralized to parent company.
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right"></label>
                                    <div class="col-lg-9 col-xl-6">
                                        <button type="submit" name="editProfile" class="btn btn-primary"><i class="fa fa-pencil-alt"></i> Edit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class ="tab-pane fade" id="user-management" role="tabpanel">
                        <div class="card-header py-3">
                            <div class="row">
                                <div class="card-title align-items-start flex-column col-md-10">
                                    <h3 class="card-label font-weight-bolder text-dark">Users</h3>
                                </div>
                                <div class="card-toolbar">
                                    <a href="#add" class="btn btn-primary font-weight-bolder" data-toggle="modal">
				                        <span class="svg-icon svg-icon-md">
                                            <i class="fa fa-plus"></i>
                                        </span>New Record
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="add" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                            <div class="modal-dialog">
                                <form class="form" action="{{URL::route('user.add')}}" method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Add New User</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                X
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input type="text" class="form-control" name="name">
                                            </div>
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="text" class="form-control" name="email">
                                            </div>
                                            <div class="form-group">
                                                <label>Username</label>
                                                <input type="text" class="form-control" name="username">
                                            </div>
                                            <div class="form-group">
                                                <label>Password</label>
                                                <input type="password" class="form-control" name="password">
                                            </div>
                                            {{-- <div class="form-group">
                                                <label>Position</label>
                                                <input type="text" class="form-control" name="position">
                                            </div> --}}
                                            <div class="form-group">
                                                <label>Position</label>
                                                <select name="userRoleAdd" class="form-control">
                                                    @foreach($roleDivsList as $key => $value)
                                                        <option value="{{$value->id}}">{{$value->roleName}}&nbsp;{{$value->divName}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <input type="hidden" name="coid" value="{{base64_encode($company->id)}}">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary" name="saveAdd">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card-body">
                            <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 50%;">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th nowrap="nowrap" style="width: 40%">Username</th>
                                    <th nowrap="nowrap" class="text-center">Position</th>
                                    <th nowrap="nowrap" data-priority=1></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $key => $user)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td nowrap="nowrap">{{$user->username}}</td>
                                    {{-- <td nowrap="nowrap" align="center">{{($user->position == null) ? "SYSTEM" : $user->position}}</td> --}}
                                    <td nowrap="nowrap" align="center">{{$user->roleName}}&nbsp;{{$user->divName}}</td>
                                    <td nowrap="nowrap" align="center">
                                        <a href="{{route('user.privilege',['id'=>$user->id])}}" class="btn btn-sm btn-warning btn-icon btn-icon-md" title="Privilege"><i class="la la-key"></i></a>
                                        <a href="#edit{{$user->id}}" data-toggle="modal" class="btn btn-sm btn-primary btn-icon btn-icon-md" title="Edit"><i class="la la-edit"></i></a>
                                        @if($user->id > 1)
                                            <button type="button" id="btnDel{{$key}}" class="btn btn-sm btn-danger btn-icon"><i class="fa fa-trash"></i></button>
                                        @endif
                                    </td>
                                    <div class="modal fade" id="edit{{$user->id}}" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                        <div class="modal-dialog">
                                            <form class="form" action="{{URL::route('user.edit')}}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id_u" id="id_u{{$key}}" value="{{$user->id}}">
                                                <input type="hidden" name="coid" value="{{base64_encode($company->id)}}">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            X
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="hidden" name="old_password" value="{{$user->password}}">
                                                        <div class="form-group">
                                                            <label>Name</label>
                                                            <input type="text" class="form-control" name="name" value="{{$user->name}}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Email</label>
                                                            <input type="text" class="form-control" name="email" value="{{$user->email}}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Username</label>
                                                            <input type="text" class="form-control" name="username" value="{{$user->username}}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Password</label>
                                                            <input type="password" class="form-control" name="password" placeholder="New Password">
                                                        </div>
                                                        {{-- <div class="form-group">
                                                            <label>Position</label>
                                                            <input type="text" class="form-control" name="position" value="{{($user->position != null) ? $user->position : 'SYSTEM'}}">
                                                        </div> --}}
                                                        <div class="form-group">
                                                            <label>Position</label>
                                                            <select name="userRoleEdit" class="form-control">
                                                                @foreach($roleDivsList as $key => $value)
                                                                    @if($value->id == $user->userRoleDivId)
                                                                        <option value="{{$value->id}}" selected="selected">{{$value->roleName}}&nbsp;{{$value->divName}}</option>
                                                                    @else
                                                                        <option value="{{$value->id}}">{{$value->roleName}}&nbsp;{{$value->divName}}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                            <input type="hidden" class="form-control" name="userRoleEditOld" value="{{$user->userRoleDivId}}">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary" name="saveEdit">Save</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class ="tab-pane fade" id="position-management" role="tabpanel">
                        <div class="card-header py-3">
                            <div class="row">
                                <div class="card-title align-items-start flex-column col-md-10">
                                    <h3 class="card-label font-weight-bolder text-dark">Positions</h3>
                                </div>
                                <div class="card-toolbar">
                                    <a href="#addPosition" class="btn btn-primary font-weight-bolder" data-toggle="modal">
                                        <span class="svg-icon svg-icon-md">
                                            <i class="fa fa-plus"></i>
                                        </span>New Record
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="addPosition" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                            <div class="modal-dialog">
                                <form class="form" action="{{URL::route('rolediv.store')}}" method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Add New Position</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                X
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                           <div class="form-group">
                                                <label>Parent Position</label>
                                                <select name="id_rms_roles_divisions_parent" class="form-control">
                                                    <option value=""></option>
                                                    @foreach($parentLists as $keyParentLists => $parentList)
                                                        <option value="{{$parentList->id}}">{{$parentList->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>Role</label>
                                                {{Form::select('id_rms_roles', $roleList, null, array('class' => 'form-control'))}}
                                            </div>

                                            <div class="form-group">
                                                <label>Division</label>
                                                {{ Form::select('id_rms_divisions', $divList, null, array('class' => 'form-control')) }}
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <input type="hidden" name="coid" value="{{base64_encode($company->id)}}">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary" name="saveAdd">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card-body">

                            <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 50%;">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th nowrap="nowrap" class="text-center">Position</th>
                                    <th nowrap="nowrap" style="width: 40%">Parent</th>
                                    <th nowrap="nowrap" data-priority=1></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($roleDivsList as $key => $roleDivList)
                                    @if($level_role[$roleDivList->id] == 1)
                                        <tr>
                                            <td>{{$numberPosition++}}</td>
                                            <td>{{$roleDivList->roleName}} {{$roleDivList->divName}}</td>
                                            <td>{{($parentPosition[$roleDivList->id])? $parentPosition[$roleDivList->id]['name']:''}}</td>
                                            <td>
                                                {{-- @actionStart('rprivilege', 'access') --}}
                                                <a href="{{ URL::route('rprivilege.edit',$roleDivList->id) }}" class="btn btn-sm btn-warning btn-icon btn-icon-md" title="Privilege"><i class="la la-key"></i></a>
                                                {{-- @actionEnd --}}

                                                {{-- @actionStart('position', 'edit') --}}
                                                <a href="#editPosition{{$roleDivList->id}}" data-toggle="modal" class="btn btn-sm btn-primary btn-icon btn-icon-md" title="Edit"><i class="la la-edit"></i></a>
                                                {{-- @actionEnd --}}

                                                {{-- @actionStart('position', 'delete') --}}
                                                <a href="#deletePosition{{$roleDivList->id}}" class="btn btn-sm btn-google btn-icon btn-icon-md" title="Delete" data-toggle="modal">
                                                    <i class="la la-trash-o"></i>
                                                </a>
                                                {{-- @actionEnd --}}
                                            </td>
                                        </tr>
                                    @endif
                                    @foreach($roleDivsList as $h => $n)
                                        @if($level_role[$n->id] == 2 && $n->id_rms_roles_divisions_parent == $roleDivList->id)
                                            <tr>
                                                <td>{{$numberPosition++}}</td>
                                                <td><i class="fa fa-reply fa-rotate-180"></i> {{$n->roleName}} {{$n->divName}}</td>
                                                <td>{{($parentPosition[$n->id])? $parentPosition[$n->id]['name']:''}}</td>
                                                <td>
                                                    {{-- @actionStart('rprivilege', 'access') --}}
                                                    <a href="{{ URL::route('rprivilege.edit',$n->id) }}" class="btn btn-sm btn-warning btn-icon btn-icon-md" title="Privilege"><i class="la la-key"></i></a>
                                                    {{-- @actionEnd --}}

                                                    {{-- @actionStart('position', 'edit') --}}
                                                    <a href="#editPosition{{$n->id}}" data-toggle="modal" class="btn btn-sm btn-primary btn-icon btn-icon-md" title="Edit"><i class="la la-edit"></i></a>
                                                    {{-- @actionEnd --}}

                                                    {{-- @actionStart('position', 'delete') --}}
                                                    <a href="#deletePosition{{$n->id}}" class="btn btn-sm btn-google btn-icon btn-icon-md" title="Delete" data-toggle="modal">
                                                        <i class="la la-trash-o"></i>
                                                    </a>
                                                    {{-- @actionEnd --}}
                                                </td>
                                            </tr>
                                        @endif
                                        @foreach($roleDivsList as $k => $m)
                                            @if($level_role[$m->id] == 3 && $m->id_rms_roles_divisions_parent == $n->id && $n->id_rms_roles_divisions_parent == $roleDivList->id)
                                                <tr>
                                                    <td>{{$numberPosition++}}</td>
                                                    <td><i class="fa fa-reply fa-rotate-180 ml-3"></i> {{$m->roleName}} {{$m->divName}}</td>
                                                    <td>{{($parentPosition[$m->id])? $parentPosition[$m->id]['name']:''}}</td>
                                                    <td>
                                                        {{-- @actionStart('rprivilege', 'access') --}}
                                                        <a href="{{ URL::route('rprivilege.edit',$m->id) }}" class="btn btn-sm btn-warning btn-icon btn-icon-md" title="Privilege"><i class="la la-key"></i></a>
                                                        {{-- @actionEnd --}}

                                                        {{-- @actionStart('position', 'edit') --}}
                                                        <a href="#editPosition{{$m->id}}" data-toggle="modal" class="btn btn-sm btn-primary btn-icon btn-icon-md" title="Edit"><i class="la la-edit"></i></a>
                                                        {{-- @actionEnd --}}

                                                        {{-- @actionStart('position', 'delete') --}}
                                                        <a href="#deletePosition{{$m->id}}" class="btn btn-sm btn-google btn-icon btn-icon-md" title="Delete" data-toggle="modal">
                                                            <i class="la la-trash-o"></i>
                                                        </a>
                                                        {{-- @actionEnd --}}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endforeach
                                    {{-- BEGIN MODAL EDIT --}}
                                    <div class="modal fade" id="editPosition{{$roleDivList->id}}" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                        <div class="modal-dialog">
                                            {!! Form::open(array('route' => ['rolediv.update', $roleDivList->id], 'method' => 'POST'))!!}
                                            {{ csrf_field() }}
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Edit Position</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Parent Position</label>
                                                        <select name="id_rms_roles_divisions_parent" class="form-control">
                                                            <option value=""></option>
                                                            @foreach($parentLists as $keyParentLists => $parentList)
                                                                @if($parentList->id == $roleDivList->id_rms_roles_divisions_parent)
                                                                    <option value="{{$parentList->id}}" selected="selected">{{$parentList->name}}</option>
                                                                @else
                                                                    <option value="{{$parentList->id}}">{{$parentList->name}}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Role</label>
                                                        {{Form::select('id_rms_roles', $roleList, $roleDivList->roleId, array('class' => 'form-control'))}}
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Division</label>
                                                        {{ Form::select('id_rms_divisions', $divList, $roleDivList->divId, array('class' => 'form-control')) }}
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <input type="hidden" name="coid" value="{{base64_encode($company->id)}}">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                </div>
                                            </div>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                    {{-- END MODAL EDIT --}}

                                    {{-- BEGIN MODAL DELETE --}}
                                    <div class="modal fade" id="deletePosition{{$roleDivList->id}}" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                        <div class="modal-dialog">
                                            {!! Form::open(array('route' => ['rolediv.delete', $roleDivList->id], 'method' => 'delete'))!!}
                                            {{ csrf_field() }}
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Delete Data</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure want to delete this data?
                                                </div>
                                                <div class="modal-footer">
                                                    <input type="hidden" name="coid" value="{{base64_encode($company->id)}}">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                                    <button type="submit" class="btn btn-primary">Yes</button>
                                                </div>
                                            </div>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                    {{-- END MODAL DELETE --}}
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class ="tab-pane fade" id="role-management" role="tabpanel">
                        <div class="card-header py-3">
                            <div class="row">
                                <div class="card-title align-items-start flex-column col-md-10">
                                    <h3 class="card-label font-weight-bolder text-dark">Roles</h3>
                                </div>
                                <div class="card-toolbar">
                                    <a href="#addRole" class="btn btn-primary font-weight-bolder" data-toggle="modal">
                                        <span class="svg-icon svg-icon-md">
                                            <i class="fa fa-plus"></i>
                                        </span>New Record
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="addRole" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                            <div class="modal-dialog">
                                <form class="form" action="{{URL::route('role.store')}}" method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Add New Role</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                X
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input type="text" name="name" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Description</label>
                                                <textarea name="desc" class="form-control"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <input type="hidden" name="coid" value="{{base64_encode($company->id)}}">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card-body">

                            <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 50%;">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th nowrap="nowrap" style="width: 40%">Name</th>
                                    <th nowrap="nowrap" class="text-center">Description</th>
                                    <th nowrap="nowrap" data-priority=1></th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($roles as $role)
                                        <tr>
                                            <td>{{$numberRole++}}</td>
                                            <td>{{$role->name}}</td>
                                            <td>{!!$role->desc!!}</td>
                                            <td>
                                                {{-- @actionStart('position', 'edit') --}}
                                                <a href="#editRole{{$role->id}}" data-toggle="modal" class="btn btn-sm btn-primary btn-icon btn-icon-md" title="Edit"><i class="la la-edit"></i></a>
                                                {{-- @actionEnd --}}

                                                {{-- @actionStart('position', 'delete') --}}
                                                <a href="#deleteRole{{$role->id}}" class="btn btn-sm btn-google btn-icon btn-icon-md" title="Delete" data-toggle="modal">
                                                    <i class="la la-trash-o"></i>
                                                </a>
                                                {{-- @actionEnd --}}
                                            </td>
                                        </tr>

                                        {{-- BEGIN MODAL EDIT --}}
                                        <div class="modal fade" id="editRole{{$role->id}}" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                            <div class="modal-dialog">
                                                {!! Form::open(array('route' => ['role.update', $role->id], 'method' => 'POST'))!!}
                                                {{ csrf_field() }}
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Edit Role</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Name</label>
                                                            <input type="text" name="name" class="form-control" value="{{$role->name}}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Description</label>
                                                            <textarea name="desc" class="form-control">{!!$role->desc!!}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="coid" value="{{base64_encode($company->id)}}">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                    </div>
                                                </div>
                                                {!! Form::close() !!}
                                            </div>
                                        </div>
                                        {{-- END MODAL EDIT --}}

                                        {{-- BEGIN MODAL DELETE --}}
                                        <div class="modal fade" id="deleteRole{{$role->id}}" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                            <div class="modal-dialog">
                                                {!! Form::open(array('route' => ['role.delete', $role->id], 'method' => 'delete'))!!}
                                                {{ csrf_field() }}
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Delete Data</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure want to delete this data?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="coid" value="{{base64_encode($company->id)}}">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                                        <button type="submit" class="btn btn-primary">Yes</button>
                                                    </div>
                                                </div>
                                                {!! Form::close() !!}
                                            </div>
                                        </div>
                                        {{-- END MODAL DELETE --}}
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class ="tab-pane fade" id="division-management" role="tabpanel">
                        <div class="card-header py-3">
                            <div class="row">
                                <div class="card-title align-items-start flex-column col-md-10">
                                    <h3 class="card-label font-weight-bolder text-dark">Divisions</h3>
                                </div>
                                <div class="card-toolbar">
                                    <a href="#addDivision" class="btn btn-primary font-weight-bolder" data-toggle="modal">
                                        <span class="svg-icon svg-icon-md">
                                            <i class="fa fa-plus"></i>
                                        </span>New Record
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="addDivision" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                            <div class="modal-dialog">
                                <form class="form" action="{{URL::route('division.store')}}" method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Add New Division</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                X
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input type="text" name="name" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Description</label>
                                                <textarea name="desc" class="form-control"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <input type="hidden" name="coid" value="{{base64_encode($company->id)}}">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card-body">

                            <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 50%;">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th nowrap="nowrap" style="width: 40%">Name</th>
                                    <th nowrap="nowrap" class="text-center">Description</th>
                                    <th nowrap="nowrap" data-priority=1></th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($divisions as $division)
                                        <tr>
                                            <td>{{$numberDivision++}}</td>
                                            <td>{{$division->name}}</td>
                                            <td>{!!$division->desc!!}</td>
                                            <td>
                                                {{-- @actionStart('position', 'edit') --}}
                                                <a href="#editDivision{{$division->id}}" data-toggle="modal" class="btn btn-sm btn-primary btn-icon btn-icon-md" title="Edit"><i class="la la-edit"></i></a>
                                                {{-- @actionEnd --}}

                                                {{-- @actionStart('position', 'delete') --}}
                                                <a href="#deleteDivision{{$division->id}}" class="btn btn-sm btn-google btn-icon btn-icon-md" title="Delete" data-toggle="modal">
                                                    <i class="la la-trash-o"></i>
                                                </a>
                                                {{-- @actionEnd --}}
                                            </td>
                                        </tr>

                                        {{-- BEGIN MODAL EDIT --}}
                                        <div class="modal fade" id="editDivision{{$division->id}}" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                            <div class="modal-dialog">
                                                {!! Form::open(array('route' => ['division.update', $division->id], 'method' => 'POST'))!!}
                                                {{ csrf_field() }}
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Edit Division</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Name</label>
                                                            <input type="text" name="name" class="form-control" value="{{$division->name}}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Description</label>
                                                            <textarea name="desc" class="form-control">{!!$division->desc!!}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="coid" value="{{base64_encode($company->id)}}">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                    </div>
                                                </div>
                                                {!! Form::close() !!}
                                            </div>
                                        </div>
                                        {{-- END MODAL EDIT --}}

                                        {{-- BEGIN MODAL DELETE --}}
                                        <div class="modal fade" id="deleteDivision{{$division->id}}" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                            <div class="modal-dialog">
                                                {!! Form::open(array('route' => ['division.delete', $division->id], 'method' => 'delete'))!!}
                                                {{ csrf_field() }}
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Delete Data</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure want to delete this data?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="coid" value="{{base64_encode($company->id)}}">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                                        <button type="submit" class="btn btn-primary">Yes</button>
                                                    </div>
                                                </div>
                                                {!! Form::close() !!}
                                            </div>
                                        </div>
                                        {{-- END MODAL DELETE --}}
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class ="tab-pane fade" id="module-management" role="tabpanel">
                        <div class="card-header py-3">
                            <div class="row">
                                <div class="card-title align-items-start flex-column col-md-10">
                                    <h3 class="card-label font-weight-bolder text-dark">Modules</h3>
                                </div>
                                <div class="card-toolbar">
                                    <a href="#addModule" class="btn btn-primary font-weight-bolder" data-toggle="modal">
                                        <span class="svg-icon svg-icon-md">
                                            <i class="fa fa-plus"></i>
                                        </span>New Record
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="addModule" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                            <div class="modal-dialog">
                                <form class="form" action="{{URL::route('module.store')}}" method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Add New Module</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                X
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input type="text" name="name" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Description</label>
                                                <textarea name="desc" class="form-control"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <input type="hidden" name="coid" value="{{base64_encode($company->id)}}">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card-body">

                            <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 50%;">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th nowrap="nowrap" style="width: 40%">Name</th>
                                    <th nowrap="nowrap" class="text-center">Description</th>
                                    <th nowrap="nowrap" data-priority=1></th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($modules as $module)
                                        <tr>
                                            <td>{{$numberModule++}}</td>
                                            <td>{{$module->name}}</td>
                                            <td>{!!$module->desc!!}</td>
                                            <td>
                                                {{-- @actionStart('position', 'edit') --}}
                                                <a href="#editModule{{$module->id}}" data-toggle="modal" class="btn btn-sm btn-primary btn-icon btn-icon-md" title="Edit"><i class="la la-edit"></i></a>
                                                {{-- @actionEnd --}}

                                                {{-- @actionStart('position', 'delete') --}}
                                                <a href="#deleteModule{{$module->id}}" class="btn btn-sm btn-google btn-icon btn-icon-md" title="Delete" data-toggle="modal">
                                                    <i class="la la-trash-o"></i>
                                                </a>
                                                {{-- @actionEnd --}}
                                            </td>
                                        </tr>

                                        {{-- BEGIN MODAL EDIT --}}
                                        <div class="modal fade" id="editModule{{$module->id}}" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                            <div class="modal-dialog">
                                                {!! Form::open(array('route' => ['module.update', $module->id], 'method' => 'POST'))!!}
                                                {{ csrf_field() }}
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Edit Module</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Name</label>
                                                            <input type="text" name="name" class="form-control" value="{{$module->name}}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Description</label>
                                                            <textarea name="desc" class="form-control">{!!$module->desc!!}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="coid" value="{{base64_encode($company->id)}}">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                    </div>
                                                </div>
                                                {!! Form::close() !!}
                                            </div>
                                        </div>
                                        {{-- END MODAL EDIT --}}

                                        {{-- BEGIN MODAL DELETE --}}
                                        <div class="modal fade" id="deleteModule{{$module->id}}" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                            <div class="modal-dialog">
                                                {!! Form::open(array('route' => ['module.delete', $module->id], 'method' => 'delete'))!!}
                                                {{ csrf_field() }}
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Delete Data</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure want to delete this data?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="coid" value="{{base64_encode($company->id)}}">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                                        <button type="submit" class="btn btn-primary">Yes</button>
                                                    </div>
                                                </div>
                                                {!! Form::close() !!}
                                            </div>
                                        </div>
                                        {{-- END MODAL DELETE --}}
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class ="tab-pane fade" id="action-management" role="tabpanel">
                        <div class="card-header py-3">
                            <div class="row">
                                <div class="card-title align-items-start flex-column col-md-10">
                                    <h3 class="card-label font-weight-bolder text-dark">Actions</h3>
                                </div>
                                <div class="card-toolbar">
                                    <a href="#addAction" class="btn btn-primary font-weight-bolder" data-toggle="modal">
                                        <span class="svg-icon svg-icon-md">
                                            <i class="fa fa-plus"></i>
                                        </span>New Record
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="addAction" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                            <div class="modal-dialog">
                                <form class="form" action="{{URL::route('action.store')}}" method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Add New Action</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                X
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input type="text" name="name" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Description</label>
                                                <textarea name="desc" class="form-control"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <input type="hidden" name="coid" value="{{base64_encode($company->id)}}">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card-body">

                            <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 50%;">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th nowrap="nowrap" style="width: 40%">Name</th>
                                    <th nowrap="nowrap" class="text-center">Description</th>
                                    <th nowrap="nowrap" data-priority=1></th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($actions as $action)
                                        <tr>
                                            <td>{{$numberAction++}}</td>
                                            <td>{{$action->name}}</td>
                                            <td>{!!$action->desc!!}</td>
                                            <td>
                                                {{-- @actionStart('position', 'edit') --}}
                                                <a href="#editAction{{$action->id}}" data-toggle="modal" class="btn btn-sm btn-primary btn-icon btn-icon-md" title="Edit"><i class="la la-edit"></i></a>
                                                {{-- @actionEnd --}}

                                                {{-- @actionStart('position', 'delete') --}}
                                                <a href="#deleteAction{{$action->id}}" class="btn btn-sm btn-google btn-icon btn-icon-md" title="Delete" data-toggle="modal">
                                                    <i class="la la-trash-o"></i>
                                                </a>
                                                {{-- @actionEnd --}}
                                            </td>
                                        </tr>

                                        {{-- BEGIN MODAL EDIT --}}
                                        <div class="modal fade" id="editAction{{$action->id}}" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                            <div class="modal-dialog">
                                                {!! Form::open(array('route' => ['action.update', $action->id], 'method' => 'POST'))!!}
                                                {{ csrf_field() }}
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Edit Action</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Name</label>
                                                            <input type="text" name="name" class="form-control" value="{{$action->name}}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Description</label>
                                                            <textarea name="desc" class="form-control">{!!$action->desc!!}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="coid" value="{{base64_encode($company->id)}}">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                    </div>
                                                </div>
                                                {!! Form::close() !!}
                                            </div>
                                        </div>
                                        {{-- END MODAL EDIT --}}

                                        {{-- BEGIN MODAL DELETE --}}
                                        <div class="modal fade" id="deleteAction{{$action->id}}" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                            <div class="modal-dialog">
                                                {!! Form::open(array('route' => ['action.delete', $action->id], 'method' => 'delete'))!!}
                                                {{ csrf_field() }}
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Delete Data</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure want to delete this data?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="coid" value="{{base64_encode($company->id)}}">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                                        <button type="submit" class="btn btn-primary">Yes</button>
                                                    </div>
                                                </div>
                                                {!! Form::close() !!}
                                            </div>
                                        </div>
                                        {{-- END MODAL DELETE --}}
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script>
        $(document).ready(function(){
            $("table.display").DataTable({
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            })

            @if(count($users) > 0)
                for (let i = 0; i < {{count($users)}}; i++) {
                    $("#btnDel" + i).click(function(){
                        Swal.fire({
                            title: "Delete",
                            text: "Delete this user?",
                            icon: "error",
                            showCancelButton: true,
                            confirmButtonText: "Delete",
                            cancelButtonText: "Cancel",
                            reverseButtons: true,
                        }).then(function(result){
                            if(result.value){
                                var id = $("#id_u"+i).val()
                                $.ajax({
                                    url: '{{URL::route('user.delete')}}',
                                    data: {
                                        '_token': '{{csrf_token()}}',
                                        'id': id
                                    },
                                    type: "POST",
                                    cache: false,
                                    dataType: 'json',
                                    success : function(response){
                                        if (response.del = 1){
                                            location.reload()
                                        } else {
                                            Swal.fire({
                                                title: "Delete",
                                                text: "Error",
                                                icon: "error"
                                            })
                                        }
                                    }
                                })
                            }
                        })
                    })
                }
            @endif

        })
    </script>
@endsection
