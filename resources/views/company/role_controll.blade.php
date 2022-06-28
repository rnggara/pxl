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
                        <li class="nav-item mb-2 mr-30">
                            <a href="#position-management" data-toggle="tab" class="nav-link py-4 active">
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
                                <span class="nav-text font-size-lg">Positions</span>
                            </a>
                        </li>
                        <li class="nav-item mb-2 mr-30">
                            <a href="#role-management" data-toggle="tab" class="nav-link py-4">
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
                                <span class="nav-text font-size-lg">Roles</span>
                            </a>
                        </li>
                        <li class="nav-item mb-2 mr-30">
                            <a href="#division-management" data-toggle="tab" class="nav-link py-4">
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
                                <span class="nav-text font-size-lg">Divisions</span>
                            </a>
                        </li>
                        <li class="nav-item mb-2 mr-30">
                            <a href="#module-management" data-toggle="tab" class="nav-link py-4">
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
                                <span class="nav-text font-size-lg">Modules</span>
                            </a>
                        </li>
                        <li class="nav-item mb-2 mr-30">
                            <a href="#employee-type-management" data-toggle="tab" class="nav-link py-4">
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
                                <span class="nav-text font-size-lg">Employee Type</span>
                            </a>
                        </li>
                        <li class="nav-item mb-2 mr-30">
                            <a href="#action-management" data-toggle="tab" class="nav-link py-4">
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
                                <span class="nav-text font-size-lg">Actions</span>
                            </a>
                        </li>
                        <li class="nav-item mb-2 mr-30">
                            <a href="{{route('company.detail', base64_encode($company->id))}}"  class="nav-link py-4">
                            <span class="nav-icon mr-2">
                                <span class="svg-icon">
                                    <i class="fa fa-backward"></i>
                                </span>
                            </span>
                                <span class="nav-text font-size-lg">Back</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="flex-row-fluid ml-lg-8">
            <div class="card card-custom card-stretch">
                <div class="tab-content" id="myTabContent">
                    <div class ="tab-pane fade show active" id="position-management" role="tabpanel">
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

                            <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 50%;" data-page-length="100">
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
                                            <div class="form-group">
                                                <div class="col-9 col-form-label">
                                                    <div class="checkbox-inline">
                                                        <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                                            <input type="checkbox" name="no_probation"/>
                                                            <span></span>
                                                            No Probation
                                                        </label>
                                                    </div>
                                                </div>
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

                            <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 50%;" data-page-length="100">
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
                                                    <div class="form-group">
                                                        <div class="col-9 col-form-label">
                                                            <div class="checkbox-inline">
                                                                <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                                                    <input type="checkbox" {{ ($role->no_probation == 1) ? "checked" : "" }} name="no_probation"/>
                                                                    <span></span>
                                                                    No Probation
                                                                </label>
                                                            </div>
                                                        </div>
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

                            <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 50%;" data-page-length="100">
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

                            <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 50%;" data-page-length="100">
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
                    <div class ="tab-pane fade" id="employee-type-management" role="tabpanel">
                        <div class="card-header py-3">
                            <div class="row">
                                <div class="card-title align-items-start flex-column col-md-10">
                                    <h3 class="card-label font-weight-bolder text-dark">Employee Type</h3>
                                </div>
                                <div class="card-toolbar">
                                    <a href="#addEmpType" class="btn btn-primary font-weight-bolder" data-toggle="modal">
                                        <span class="svg-icon svg-icon-md">
                                            <i class="fa fa-plus"></i>
                                        </span>New Record
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="addEmpType" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                            <div class="modal-dialog">
                                <form class="form" action="{{URL::route('rc.emp.store')}}" method="POST">
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
                                            {{--  @for ($i = 1; $i <= 3; $i++)
                                                <div class="form-group">
                                                    <label>Signature {{ $i }}</label>
                                                    <select name="sign_{{ $i }}" class="form-control select2" id="">
                                                        <option value="">Select Signature</option>
                                                        @foreach($roleDivsList as $key => $value)
                                                            <option value="{{ $value->id }}">{{ $value->roleName." ".$value->divName }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endfor  --}}
                                            <div class="form-group">
                                                <div class="col-9 col-form-label">
                                                    <div class="checkbox-inline">
                                                        <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                                            <input type="checkbox" name="no_probation"/>
                                                            <span></span>
                                                            No Probation
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-9 col-form-label">
                                                    <div class="checkbox-inline">
                                                        <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                                            <input type="checkbox" name="with_voucher"/>
                                                            <span></span>
                                                            With Voucher
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-9 col-form-label">
                                                    <div class="checkbox-inline">
                                                        <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                                            <input type="checkbox" name="with_bonus"/>
                                                            <span></span>
                                                            With Bonus
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-9 col-form-label">
                                                    <div class="checkbox-inline">
                                                        <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                                            <input type="checkbox" name="disable_thr"/>
                                                            <span></span>
                                                            Disable THR
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>{{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}</label>
                                                <select name="tc_id" id="" class="form-control select2" aria-placeholder="Select {{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}">
                                                    <option value="">Select {{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}</option>
                                                    @foreach ($coa as $item)
                                                        <option value="{{ $item->id }}">[{{ $item->code }}] {{ $item->name }}</option>
                                                    @endforeach
                                                </select>
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

                            <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 50%;" data-page-length="100">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th nowrap="nowrap" style="width: 40%">Name</th>
                                    <th nowrap="nowrap" class="text-center">No Probation</th>
                                    <th nowrap="nowrap" class="text-center">With Voucher</th>
                                    <th nowrap="nowrap" class="text-center">With Bonus</th>
                                    <th nowrap="nowrap" class="text-center">Disable THR</th>
                                    <th nowrap="nowrap" class="text-center">{{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}</th>
                                    <th nowrap="nowrap" data-priority=1></th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($emp_type as $i => $role)
                                    @php
                                        $probation = json_decode($role->no_probation, true);
                                        $voucher = json_decode($role->with_voucher, true);
                                        $bonus = json_decode($role->with_bonus, true);
                                        $thr = json_decode($role->disable_thr, true);
                                        $tc = json_decode($role->tc_id, true);
                                        $no_probation = (isset($probation[$company->id])) ? $probation[$company->id] : ((isset($probation[$company->id_parent])) ? $probation[$company->id_parent] : 0);
                                        $with_voucher = (isset($voucher[$company->id])) ? $voucher[$company->id] : ((isset($voucher[$company->id_parent])) ? $voucher[$company->id_parent] : 0);
                                        $with_bonus = (isset($bonus[$company->id])) ? $bonus[$company->id] : ((isset($bonus[$company->id_parent])) ? $bonus[$company->id_parent] : 0);
                                        $disable_thr = (isset($thr[$company->id])) ? $thr[$company->id] : ((isset($thr[$company->id_parent])) ? $thr[$company->id_parent] : 0);
                                        $tc_id = (isset($tc[$company->id])) ? $tc[$company->id] : ((isset($tc[$company->id_parent])) ? $tc[$company->id_parent] : 0);
                                    @endphp
                                    <tr>
                                        <td>{{$i+1}}</td>
                                        <td>{{$role->name}}</td>
                                        <td class="text-center"><label for="" class="label label-inline label-{{ ($no_probation == 1) ? "success" : "danger" }}"><i class="fa fa-{{ ($no_probation == 1) ? "check" : "times" }} text-white"></i></label></td>
                                        <td class="text-center"><label for="" class="label label-inline label-{{ ($with_voucher == 1) ? "success" : "danger" }}"><i class="fa fa-{{ ($with_voucher == 1) ? "check" : "times" }} text-white"></i></label></td>
                                        <td class="text-center"><label for="" class="label label-inline label-{{ ($with_bonus == 1) ? "success" : "danger" }}"><i class="fa fa-{{ ($with_bonus == 1) ? "check" : "times" }} text-white"></i></label></td>
                                        <td class="text-center"><label for="" class="label label-inline label-{{ ($disable_thr == 1) ? "success" : "danger" }}"><i class="fa fa-{{ ($disable_thr == 1) ? "check" : "times" }} text-white"></i></label></td>
                                        <td class="text-center">
                                            @if (isset($coa_detail['code'][$tc_id]))
                                                [{{ $coa_detail['code'][$tc_id] }}] {{ $coa_detail['name'][$tc_id] }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            {{-- @actionStart('position', 'edit') --}}
                                            <a href="#editEmpType{{$role->id}}" data-toggle="modal" class="btn btn-sm btn-primary btn-icon btn-icon-md" title="Edit"><i class="la la-edit"></i></a>
                                            {{-- @actionEnd --}}

                                            {{-- @actionStart('position', 'delete') --}}
                                            <a href="#deleteEmpType{{$role->id}}" class="btn btn-sm btn-google btn-icon btn-icon-md" title="Delete" data-toggle="modal">
                                                <i class="la la-trash-o"></i>
                                            </a>
                                            {{-- @actionEnd --}}
                                        </td>
                                    </tr>

                                    {{-- BEGIN MODAL EDIT --}}
                                    <div class="modal fade" id="editEmpType{{$role->id}}" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                        <div class="modal-dialog">
                                            {!! Form::open(array('route' => ['rc.emp.update', $role->id], 'method' => 'POST'))!!}
                                            {{ csrf_field() }}
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Edit Employee Type</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Name</label>
                                                        <input type="text" name="name" class="form-control"  value="{{$role->name}}" {{ ($company->id != $role->company_id) ? "readonly" : "" }}>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-9 col-form-label">
                                                            <div class="checkbox-inline">
                                                                <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                                                    <input type="checkbox" {{ ($no_probation == 1) ? "checked" : "" }} name="no_probation"/>
                                                                    <span></span>
                                                                    No Probation
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-9 col-form-label">
                                                            <div class="checkbox-inline">
                                                                <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                                                    <input type="checkbox" {{ ($with_voucher == 1) ? "checked" : "" }} name="with_voucher"/>
                                                                    <span></span>
                                                                    With Voucher
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-9 col-form-label">
                                                            <div class="checkbox-inline">
                                                                <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                                                    <input type="checkbox" {{ ($with_bonus == 1) ? "checked" : "" }} name="with_bonus"/>
                                                                    <span></span>
                                                                    With Bonus
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-9 col-form-label">
                                                            <div class="checkbox-inline">
                                                                <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                                                    <input type="checkbox" {{ ($disable_thr == 1) ? "checked" : "" }} name="disable_thr"/>
                                                                    <span></span>
                                                                    Disable THR
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>{{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}</label>
                                                        <select name="tc_id" id="" class="form-control select2" aria-placeholder="Select {{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}">
                                                            <option value="">Select {{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}</option>
                                                            @foreach ($coa as $item)
                                                                <option value="{{ $item->id }}" {{ ($item->id == $tc_id) ? "SELECTED" : "" }}>[{{ $item->code }}] {{ $item->name }}</option>
                                                            @endforeach
                                                        </select>
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
                                    <div class="modal fade" id="deleteEmpType{{$role->id}}" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                        <div class="modal-dialog">
                                            {!! Form::open(array('route' => ['rc.emp.delete', $role->id], 'method' => 'delete'))!!}
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
        function show_toast(msg){
            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": "toast-bottom-center",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };

            toastr.warning(msg);
        }

        $(document).ready(function(){
            @if (\Session::get('msg'))
                show_toast("There is still employee that use this type. Please move the employee first!")
            @endif

            $("select.select2").select2({
                width: "100%"
            })
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
