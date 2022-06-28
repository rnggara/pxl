<div id="kt_header" class="header flex-column header-fixed">
    <!--begin::Top-->
    <div class="header-top">
        <!--begin::Container-->
        <div class="container-fluid" style="background-color: {{$comp->bgcolor}}">
            <!--begin::Left-->
            <div class="d-none d-lg-flex align-items-center mr-3">
                <!--begin::Logo-->
                <a href="{{route('home')}}" class="">
                    @php
                        $img = "";
                        if(!empty($app_comp->p_logo_white)){
                            $img = str_replace("public", "public_html", asset("images/".$app_comp->p_logo_white));
                        } else {
                            if($accounting_mode == 1){
                                $img = asset('assets/images/logo_1.png');
                            } else {
                                $img = asset('assets/images/logo.png');
                            }
                        }
                    @endphp
                    @if($accounting_mode == 1)
                        <img alt="Logo" src="{{ $img }}" class="max-h-35px"  />
                    @else
                        <img alt="Logo" src="{{ $img }}" class="max-h-20px"  />
                    @endif
                </a>

                <!--end::Logo-->
                <!--begin::Tab Navs(for desktop mode)-->
                <ul class="header-tabs nav align-self-end font-size-lg ml-20" role="tablist">
                    <!--begin::Item-->
                    <li class="nav-item">
                        <a href="{{route('home')}}" class="nav-link py-4 px-6 active" data-toggle="tab" data-target="#kt_header_tab_1" role="tab">
                            {{ strtoupper($comp->company_name) }}
                        </a>
                    </li>
                    <!--end::Item-->
                </ul>
                <!--begin::Tab Navs-->
            </div>
            <!--end::Left-->
            <!--begin::Topbar-->
            <div class="topbar" style="background-color: {{$comp->bgcolor}}">
                <!--begin::Search-->
                <div class="dropdown">
                    <!--begin::Toggle-->
                    {{--<div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px">
                        <div class="btn btn-icon btn-hover-transparent-white btn-lg btn-dropdown mr-1">
								<span class="svg-icon svg-icon-xl">
									<!--begin::Svg Icon | path:assets/media/svg/icons/General/Search.svg-->
									<i class="fa fa-search"></i>
                                    <!--end::Svg Icon-->
								</span>
                        </div>
                    </div>--}}
                    <!--end::Toggle-->
                    <!--begin::Dropdown-->
                    <div class="dropdown-menu p-0 m-0 dropdown-menu-right dropdown-menu-anim-up dropdown-menu-lg">
                        <div class="quick-search quick-search-dropdown" id="kt_quick_search_dropdown">
                            <!--begin:Form-->
                            <form method="get" class="quick-search-form">
                                <div class="input-group">
                                    <div class="input-group-prepend">
											<span class="input-group-text">
												<span class="svg-icon svg-icon-lg">
													<!--begin::Svg Icon | path:assets/media/svg/icons/General/Search.svg-->
													<i class="fa fa-search"></i>
                                                    <!--end::Svg Icon-->
												</span>
											</span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Search..." />
                                    <div class="input-group-append">
											<span class="input-group-text">
												<i class="quick-search-close ki ki-close icon-sm text-muted"></i>
											</span>
                                    </div>
                                </div>
                            </form>
                            <!--end::Form-->
                            <!--begin::Scroll-->
                            <div class="quick-search-wrapper scroll" data-scroll="true" data-height="325" data-mobile-height="200"></div>
                            <!--end::Scroll-->
                        </div>
                    </div>
                    <!--end::Dropdown-->
                </div>
                {{--<div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px">
                    <div class="btn btn-icon btn-hover-transparent-white btn-lg btn-dropdown mr-1">
								<span class="svg-icon svg-icon-xl">
									<!--begin::Svg Icon | path:assets/media/svg/icons/General/Search.svg-->
									<i class="fa fa-arrow-left"></i>
                                    <!--end::Svg Icon-->
								</span>
                    </div>
                </div>--}}
                <!--end::Search-->
                <!--begin::Notifications-->
                <div class="dropdown">
                    <!--begin::Toggle-->
                    <div class="topbar-item" data-offset="10px,0px">
                        <div class="btn btn-icon btn-hover-transparent-white btn-dropdown btn-lg mr-1 pulse pulse-white">
                            <!-- <span class="svg-icon svg-icon-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <path d="M2.56066017,10.6819805 L4.68198052,8.56066017 C5.26776695,7.97487373 6.21751442,7.97487373 6.80330086,8.56066017 L8.9246212,10.6819805 C9.51040764,11.267767 9.51040764,12.2175144 8.9246212,12.8033009 L6.80330086,14.9246212 C6.21751442,15.5104076 5.26776695,15.5104076 4.68198052,14.9246212 L2.56066017,12.8033009 C1.97487373,12.2175144 1.97487373,11.267767 2.56066017,10.6819805 Z M14.5606602,10.6819805 L16.6819805,8.56066017 C17.267767,7.97487373 18.2175144,7.97487373 18.8033009,8.56066017 L20.9246212,10.6819805 C21.5104076,11.267767 21.5104076,12.2175144 20.9246212,12.8033009 L18.8033009,14.9246212 C18.2175144,15.5104076 17.267767,15.5104076 16.6819805,14.9246212 L14.5606602,12.8033009 C13.9748737,12.2175144 13.9748737,11.267767 14.5606602,10.6819805 Z" fill="#000000" opacity="0.3" />
                                        <path d="M8.56066017,16.6819805 L10.6819805,14.5606602 C11.267767,13.9748737 12.2175144,13.9748737 12.8033009,14.5606602 L14.9246212,16.6819805 C15.5104076,17.267767 15.5104076,18.2175144 14.9246212,18.8033009 L12.8033009,20.9246212 C12.2175144,21.5104076 11.267767,21.5104076 10.6819805,20.9246212 L8.56066017,18.8033009 C7.97487373,18.2175144 7.97487373,17.267767 8.56066017,16.6819805 Z M8.56066017,4.68198052 L10.6819805,2.56066017 C11.267767,1.97487373 12.2175144,1.97487373 12.8033009,2.56066017 L14.9246212,4.68198052 C15.5104076,5.26776695 15.5104076,6.21751442 14.9246212,6.80330086 L12.8033009,8.9246212 C12.2175144,9.51040764 11.267767,9.51040764 10.6819805,8.9246212 L8.56066017,6.80330086 C7.97487373,6.21751442 7.97487373,5.26776695 8.56066017,4.68198052 Z" fill="#000000" />
                                    </g>
                                </svg>
                            </span> -->
                            <span class="symbol symbol-35">
								<span class="symbol-label font-size-h5 font-weight-bold text-white bg-white">
                                    <img src='{{str_replace("public", "public_html", asset('images/'.$comp->app_logo))}}' height='30px' alt="Company Logo"/>
                                </span>

							</span>
                            <span class="pulse-ring"></span>
                        </div>
                    </div>
                    <!--end::Toggle-->
                    <!--begin::Dropdown-->
                    {{-- <div class="dropdown-menu p-0 m-0 dropdown-menu-right dropdown-menu-anim-up dropdown-menu-lg">
                        <form>
                            <!--begin::Content-->
                            <div class="tab-content">
                                <!--begin::Tabpane-->
                                <div class="tab-pane active show p-8" id="topbar_notifications_notifications" role="tabpanel">
                                    <!--begin::Scroll-->
                                    <div class="scroll pr-7 mr-n7" data-scroll="true" data-height="300" data-mobile-height="200">
                                        <h5>Company Selector</h5><br />
                                        <!--begin::Item-->
                                    <!--end::Item-->
                                    </div>
                                    <!--end::Scroll-->
                                    <!--begin::Action-->
                                    <div class="d-flex flex-center pt-7">
                                        <a href="#" class="btn btn-light-primary font-weight-bold text-center">See All</a>
                                    </div>
                                    <!--end::Action-->
                                </div>
                                <!--end::Tabpane-->
                            </div>
                            <!--end::Content-->
                        </form>
                    </div> --}}
                    <!--end::Dropdown-->
                </div>
                <!--end::Notifications-->
                <!--begin::Quick panel-->
                @actionStart('preferences', 'access')
                {{-- <div class="topbar-item">
                    <div class="btn btn-icon btn-hover-transparent-white btn-lg mr-1" id="kt_server_config">
                        <a href='{{route('company.index')}}'>
							<span class="svg-icon svg-icon-xl">
								<!--begin::Svg Icon | path:assets/media/svg/icons/Layout/Layout-4-blocks.svg-->
								<i class="fa fa-cog"></i>
                                <!--end::Svg Icon-->
							</span>
                        </a>
                    </div>
                </div> --}}
                @actionEnd
                <!--end::Quick panel-->
                <!--begin::User-->
            <!-- <div class="topbar-item">
						<div class="btn btn-icon btn-hover-transparent-white w-auto d-flex align-items-center btn-lg px-2">
							<span class="symbol symbol-35">
								<span class="symbol-label font-size-h5 font-weight-bold text-white bg-white"><img src='' height='30px'/></span>
							</span>
						</div>
					</div> -->
                <!--begin::User-->
                <div class="topbar-item">
                    {{-- <div class="btn btn-icon btn-hover-transparent-white w-auto d-flex align-items-center btn-lg px-2" id="kt_quick_user_toggle">
                        <div class="d-flex flex-column text-right pr-3">
                            <span class="text-white opacity-50 font-weight-bold font-size-sm d-none d-md-inline">{{$emp->emp_name}}</span>
                            <span class="text-white font-weight-bolder font-size-sm d-none d-md-inline">{{($emp->position == null)?'SYSTEM':$emp->position}}</span>
                        </div>
                        @php
                            $str = $emp->emp_name;
                        @endphp
                        <span class="symbol symbol-35">
                                        <span class="symbol-label font-size-h5 font-weight-bold text-white bg-white-o-30">{{strtoupper($str[0])}}</span>
                                    </span>
                    </div> --}}
                </div>
                <!--end::User-->
                <!--end::User-->
            </div>
            <!--end::Topbar-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Top-->
    <!--begin::Bottom-->
    <div class="header-bottom">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Header Menu Wrapper-->
            <div class="header-navs header-navs-left" id="kt_header_navs">
                <!--begin::Tab Content-->
                <div class="tab-content">
                    <!--begin::Tab Pane-->
                    <div class="tab-pane py-5 p-lg-0 show active" id="kt_header_tab_1">
                        <!--begin::Menu-->
                        <div id="kt_header_menu" class="header-menu header-menu-mobile header-menu-layout-default">
                            <!--begin::Nav-->
                            <ul class="menu-nav">

                            </ul>
                            <!--end::Nav-->
                        </div>
                        <!--end::Menu-->
                    </div>
                    <!--begin::Tab Pane-->
                </div>
                <!--end::Tab Content-->
            </div>
            <!--end::Header Menu Wrapper-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Bottom-->
</div>
<!--end::Header
