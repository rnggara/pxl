@extends('layouts.template')
@section('content')
    <div class="d-flex flex-row">
        <div class="flex-row-auto offcanvas-mobile w-250px w-xxl-350px" id="kt_profile_aside">
            <div class="card card-custom card-stretch">
                <div class="card-body pt-4">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
                            <div class="symbol-label" style="background-image: url('{{(empty($company->app_logo)) ? asset('theme/assets/media/logos/default-c.png') : asset('images/'.$company->app_logo)}}')"></div>
                        </div>
                        <div>
                            <a href="#" class="font-weight-bolder font-size-h5 text-dark-75 text-hover-primary">{{$company->company_name}}</a>
                        </div>
                    </div>
                    <div class="py-4">

                    </div>
                    <ul class="nav nav-tabs nav-tabs-line">
                        <li class="nav-item mb-2 mr-30 active">
                            <a href="#payroll" data-toggle="tab" class="nav-link py-4 active">
							<span class="nav-icon mr-2">
								<span class="svg-icon">
									<!--begin::Svg Icon | path:assets/media/svg/icons/General/User.svg-->
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
                                <span class="nav-text font-size-lg">Human Resource</span>
                            </a>
                        </li>
                        <li class="nav-item mb-2 mr-30 active">
                            <a href="#working_environment" data-toggle="tab" class="nav-link py-4">
							<span class="nav-icon mr-2">
								<span class="svg-icon">
									<!--begin::Svg Icon | path:assets/media/svg/icons/General/User.svg-->
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
                                <span class="nav-text font-size-lg">Working Environment</span>
                            </a>
                        </li>
                        <li class="nav-item mb-2 mr-30">
                            <a href="#br" data-toggle="tab" class="nav-link py-4">
							<span class="nav-icon mr-2">
								<span class="svg-icon">
									<!--begin::Svg Icon | path:assets/media/svg/icons/General/User.svg-->
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
                                <span class="nav-text font-size-lg">BR Setting</span>
                            </a>
                        </li>
                        <li class="nav-item mb-2 mr-30">
                            <a href="#pr" data-toggle="tab" class="nav-link py-4">
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
                                <span class="nav-text font-size-lg">Performance Review</span>
                            </a>
                        </li>
                        <li class="nav-item mb-2 mr-30">
                            <a href="#signature" data-toggle="tab" class="nav-link py-4">
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
                                <span class="nav-text font-size-lg">Paper Signatures</span>
                            </a>
                        </li>
                        <li class="nav-item mb-2 mr-30">
                            <a href="#accounting" data-toggle="tab" class="nav-link py-4">
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
                                <span class="nav-text font-size-lg">Accounting</span>
                            </a>
                        </li>
                        <li class="nav-item mb-2 mr-30">
                            <a href="#tax" data-toggle="tab" class="nav-link py-4">
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
                                <span class="nav-text font-size-lg">Tax</span>
                            </a>
                        </li>
                        <li class="nav-item mb-2 mr-30">
                            <a href="#ac" data-toggle="tab" class="nav-link py-4">
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
                                <span class="nav-text font-size-lg">Activity Config</span>
                            </a>
                        </li>
                        <li class="nav-item mb-2 mr-30">
                            <a href="{{ route('coa.index') }}" class="nav-link py-4">
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
                                <span class="nav-text font-size-lg">{{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}</span>
                            </a>
                        </li>
                        <li class="nav-item mb-2 mr-30">
                            <a href="{{route('company.detail',['id' => base64_encode($company->id)])}}" class="nav-link py-4">
							<span class="nav-icon mr-2">
								<span class="svg-icon">
									<i class="fa fa-backspace"></i>
                                    <!--end::Svg Icon-->
								</span>
							</span>
                                <span class="nav-text font-size-lg">&nbsp;&nbsp;Back</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="flex-row-fluid ml-lg-8">
            <div class="card card-custom card-stretch">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade" id="br" role="tabpanel">
                        @include('preference.br')
                    </div>
                    <div class="tab-pane fade show active" id="payroll" role="tabpanel">
                        <div class="card-header py-3">
                            <div class="row">
                                <div class="card-title align-items-start flex-column col-md-10">
                                    <h3 class="card-label font-weight-bolder text-dark">Variables</h3>
                                </div>
                                <div class="card-toolbar text-right">

                                </div>
                            </div>
                        </div> <!--Human Resources-->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-6 col-lg-6">
                                    <a href="{{route ('employee_variables', $company->id) }}" class="btn btn-primary btn-lg btn-block">Employee Variables</a>
                                </div>
                                {{-- <div class="col-xl-6 col-lg-6">
                                    <a href="" class="btn btn-primary btn-lg btn-block">...</a>
                                </div> --}}
                            </div>
                            <div class="row mt-10">
                                {{-- <div class="col-xl-6 col-lg-6">
                                    <a href="" class="btn btn-primary btn-lg btn-block">...</a>
                                </div>
                                <div class="col-xl-6 col-lg-6">
                                    <a href="" class="btn btn-primary btn-lg btn-block">...</a>
                                </div> --}}
                            </div>
                        </div>
                        <div class="card-header py-3">
                            <div class="row">
                                <div class="card-title align-items-start flex-column col-md-10">
                                    <h3 class="card-label font-weight-bolder text-dark">Contract Template</h3>
                                </div>
                                <div class="card-toolbar text-right">

                                </div>
                            </div>
                        </div> <!--Human Resources-->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-6 col-lg-6">
                                    <a href="{{route ('hrd.contract.index') }}" class="btn btn-primary btn-lg btn-block">Contract Template</a>
                                </div>
                                {{-- <div class="col-xl-6 col-lg-6">
                                    <a href="" class="btn btn-primary btn-lg btn-block">...</a>
                                </div> --}}
                            </div>
                            <div class="row mt-10">
                                {{-- <div class="col-xl-6 col-lg-6">
                                    <a href="" class="btn btn-primary btn-lg btn-block">...</a>
                                </div>
                                <div class="col-xl-6 col-lg-6">
                                    <a href="" class="btn btn-primary btn-lg btn-block">...</a>
                                </div> --}}
                            </div>
                        </div>
                        <div class="card-header py-3">
                            <div class="row">
                                <div class="card-title align-items-start flex-column col-md-10">
                                    <h3 class="card-label font-weight-bolder text-dark">Payroll Period</h3>
                                </div>
                                <div class="card-toolbar text-right">

                                </div>
                            </div>
                        </div> <!--payroll-->
                        <form class="form" method="POST" action="{{route('pref.save')}}">
                            @csrf
                            <input type="hidden" name="id_company" value="{{\Session::get('company_id')}}">
                            <input type="hidden" name="id" value="{{($preferences != null) ?$preferences->id : ''}}">
                            <div class="card-body">
                                <div class="row">
                                    <label class="col-xl-3"></label>
                                    <div class="col-lg-9 col-xl-6">
                                        <!-- <h5 class="font-weight-bold mb-6">Customer Info</h5> -->
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Start Date</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" type="number" name="period_start" value="{{($preferences != null) ? $preferences->period_start : ''}}" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">End Date</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" type="number" name="period_end" value="{{($preferences != null) ? $preferences->period_end : ''}}" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Archive Date</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" type="number" name="period_archive" value="{{($preferences != null) ? (empty($preferences->period_archive) ? 10 : $preferences->period_archive) : ''}}" />
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right"></label>
                                    <div class="col-lg-9 col-xl-6">
                                        <button type="submit" name="savePayrollPeriod" value="save" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <hr>
                        <div class="card-header py-3">
                            <div class="row">
                                <div class="card-title align-items-start flex-column col-md-10">
                                    <h3 class="card-label font-weight-bolder text-dark">Attendance</h3>
                                </div>
                                <div class="card-toolbar text-right">

                                </div>
                            </div>

                        </div> <!--attendance-->
                        <form class="form" method="POST" action="{{route('pref.save')}}">
                            @csrf
                            <input type="hidden" name="id_company" value="{{\Session::get('company_id')}}">
                            <input type="hidden" name="id" value="{{($preferences != null) ?$preferences->id : ''}}">
                            <div class="card-body">
                                <div class="row">
                                    <label class="col-xl-3"></label>
                                    <div class="col-lg-9 col-xl-6">
                                        <!-- <h5 class="font-weight-bold mb-6">Customer Info</h5> -->
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Penalty (IDR)</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" type="number" name="penalty_amt" value="{{($preferences != null) ? $preferences->penalty_amt : ''}}" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Per Minutes Late</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" type="number" name="penalty_period" value="{{($preferences != null) ? $preferences->penalty_period : ''}}" />
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Start working on</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" type="time" name="penalty_start" value="{{($preferences != null) ? $preferences->penalty_start : ''}}" />
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Start overtime on</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" type="time" name="penalty_stop" value="{{($preferences != null) ? $preferences->penalty_stop : ''}}" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right"></label>
                                    <div class="col-lg-9 col-xl-6">
                                        <button type="submit" name="saveAttendance" value="save" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <hr>
                        <div class="card-header py-3">
                            <div class="row">
                                <div class="card-title align-items-start flex-column col-md-10">
                                    <h3 class="card-label font-weight-bolder text-dark">Deduction</h3>
                                </div>
                                <div class="card-toolbar text-right">

                                </div>
                            </div>
                        </div> <!--deduction-->
                        <form class="form" method="POST" action="{{route('pref.save')}}">
                            @csrf
                            <input type="hidden" name="id_company" value="{{\Session::get('company_id')}}">
                            <input type="hidden" name="id" value="{{($preferences != null) ?$preferences->id : ''}}">
                            <div class="card-body">
                                <div class="row">
                                    <label class="col-xl-3"></label>
                                    <div class="col-lg-9 col-xl-6">
                                        <!-- <h5 class="font-weight-bold mb-6">Customer Info</h5> -->
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Deduction (%)</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" type="number" name="absence_deduction" value="{{($preferences != null) ? $preferences->absence_deduction : ''}}" />
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right"></label>
                                    <div class="col-lg-9 col-xl-6">
                                        <button type="submit" name="saveDeduction" value="save" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <hr>
                        @include('preference.absent')
                        <hr>
                        @include('preference.thr')
                        <hr>
                        @include('preference.pb')
                        <hr>
                        @include('preference.odo')
                        <hr>
                        @include('preference.ppe')
                    </div>
                    <div class="tab-pane fade" id="working_environment" role="tabpanel">
                        @include('preference.working_environment')
                    </div>
                    <div class="tab-pane fade" id="pr" role="tabpanel">
                        @include('preference.pr')
                    </div>
                    <div class="tab-pane fade" id="signature" role="tabpanel">
                        @include('preference.signature')
                    </div>
                    <div class="tab-pane fade" id="accounting" role="tabpanel">
                        @include('preference.accounting')
                    </div>
                    <div class="tab-pane fade" id="tax" role="tabpanel">
                        @include('preference.tax')
                    </div>
                    <div class="tab-pane fade" id="ac" role="tabpanel">
                        @include('preference.ac')
                    </div>
                    <div class="tab-pane fade" id="files" role="tabpanel">
                        <div class="card-header py-3">
                            <div class="row">
                                <div class="card-title align-items-start flex-column col-md-10">
                                    <h3 class="card-label font-weight-bolder text-dark">Template Files</h3>
                                </div>
                                <div class="card-toolbar text-right">
                                    <button type="button" class="btn btn-light-primary btn-xs mr-2" data-toggle="modal" data-target="#uploadFileModal">
                                        <span class="navi-icon">
                                            <i class="flaticon-upload"></i>
                                        </span>
                                        <span class="navi-text">Add New File</span>
                                    </button>
                                </div>
                                <div class="modal fade" id="uploadFileModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered " role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Add New File</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <i aria-hidden="true" class="ki ki-close"></i>
                                                </button>
                                            </div>
                                            <form method="POST" action="{{URL::route('pref.file.save')}}" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="id_company" value="{{$company->id}}">
                                                <div class="modal-body">
                                                    <div class="form-group row">
                                                        <label class="col-form-label text-right col-lg-3 col-sm-12">File Name</label>
                                                        <div class="col-lg-6 col-md-9 col-sm-12">
                                                            <input type="text" class="form-control" name="file_name" id="file_name" placeholder="File Name" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-form-label text-right col-lg-3 col-sm-12">File</label>
                                                        <div class="col-lg-6 col-md-9 col-sm-12">
                                                            <div class="custom-file">
                                                                <input type="file" name="file" class="custom-file-input" id="customFile" />
                                                                <label class="custom-file-label" for="customFile">Choose file</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary font-weight-bold">
                                                        <i class="flaticon-upload"></i>
                                                        Upload</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <table class="table table-bordered display">
                                <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-left">Name </th>
                                    <th class="text-center">&nbsp;Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($template_files as $key => $file)
                                    <tr>
                                        <td class="text-center">{{($key+1)}}</td>
                                        <td>{{$file->name}}</td>
                                        <td class="text-center">
                                            <a href="{{URL::route('download', $file->file_code)}}" target="_blank" class="btn btn-light-success btn-xs btn-icon" title="Download Files">
                                                <i class="fa fa-download"></i> &nbsp;
                                            </a> |&nbsp;
                                            <a href="{{route('pref.file.del',['id'=>$file->id,'id_company' => $file->company_id])}}" onclick="return confirm('Delete this file?')" class="btn btn-light-danger btn-xs btn-icon" title="Delete Files">
                                                <i class="fa fa-trash"></i>
                                            </a>
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
@endsection
@section('custom_script')
	<script src="{{asset('assets/jquery-number/jquery.number.js') }}"></script>
    <script>
        function delete_item(x){
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    location.href = x
                }
            })
        }

        function edit_item(url, target, modal){
            $.ajax({
                url: url,
                type: "get",
                cache: false,
                success: function (response) {
                    $(modal).modal('show')
                    $(target).append(response)
                }
            })
        }
        $(document).ready(function(){
        	$(".number").number(true, 2)
            $("table.display").DataTable()
            $("select.select2").select2({
                width: "100%"
            })

            $("#editModalEnvironment").on('hidden.bs.modal', function () {
                $("#editModalContent").html("")
            })

            $(".item-ppe").select2({
                width : "100%",
                ajax : {
                    url : "{{ route('items.js') }}",
                    dataType : "json"
                }
            })
        })
    </script>
@endsection
