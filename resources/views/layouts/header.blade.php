<div id="kt_header" class="header flex-column header-fixed">
    <!--begin::Top-->
    <div class="header-top">
        <!--begin::Container-->
        <div class="container-fluid" style="background-color: {{Session::get('company_bgcolor')}}">
            <!--begin::Left-->
            <div class="d-none d-lg-flex align-items-center mr-3">
                <!--begin::Logo-->
                {{-- <a href="{{route('home')}}" class="">
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
                </a> --}}

                <!--end::Logo-->
                <!--begin::Tab Navs(for desktop mode)-->
                <ul class="header-tabs nav align-self-end font-size-lg ml-20" role="tablist">
                    <!--begin::Item-->
                    <li class="nav-item">
                        <a href="{{route('home')}}" class="nav-link py-4 px-6 active" >
                            {{ strtoupper(\Session::get('company_name_parent')) }}
                        </a>
                    </li>
                    <!--end::Item-->
                </ul>
                <!--begin::Tab Navs-->
            </div>
            <!--end::Left-->
            <!--begin::Topbar-->
            <div class="topbar" style="background-color: {{Session::get('company_bgcolor')}}">
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
                    {{-- <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px">
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
                                    <img src='{{str_replace("public", "public_html", asset('images/'.Session::get('company_app_logo')))}}' height='30px' alt="Company Logo"/>
                                </span>

							</span>
                            <span class="pulse-ring"></span>
                        </div>
                    </div> --}}
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
                                        @if(Auth::user()->username != 'cypher')<!--c4only-->
                                            @foreach(Session::get('comp_user') as $k => $valK)
                                                @foreach($comp as $value)
                                                    @if($value->id == $valK)
                                                        <div class="d-flex align-items-center mb-6">
                                                            <div class="symbol symbol-40 symbol-light-primary mr-5">
                                                                <span class="symbol-label">
                                                                    <span class="svg-icon svg-icon-lg svg-icon-primary">
                                                                        <img src='{{str_replace("public", "public_html", asset('images/'.$value->app_logo))}}' height='30px' alt="Company Logo"/>
                                                                    </span>
                                                                </span>
                                                            </div>
                                                            <div class="d-flex flex-column font-weight-bold">
                                                                <a href="{{URL::route('company.switch')."?id=".$value->id}}" class="text-dark text-hover-primary mb-1 font-size-lg">
                                                                    {{$value->company_name}}
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        @else
                                            @foreach($comp as $value)
                                                <div class="d-flex align-items-center mb-6">
                                                    <div class="symbol symbol-40 symbol-light-primary mr-5">
                                                                <span class="symbol-label">
                                                                    <span class="svg-icon svg-icon-lg svg-icon-primary">
                                                                        <img src='{{str_replace("public", "public_html", asset('images/'.$value->app_logo))}}' height='30px' alt="Company Logo"/>
                                                                    </span>
                                                                </span>
                                                    </div>
                                                    <div class="d-flex flex-column font-weight-bold">
                                                        <a href="{{URL::route('company.switch')."?id=".$value->id}}" class="text-dark text-hover-primary mb-1 font-size-lg">
                                                            {{$value->company_name}}
                                                        </a>
                                                    </div>
                                                </div>

                                            @endforeach
                                        @endif

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
                @if (empty(\Auth::user()->discord_id))
                @actionStart('preferences', 'access')
                <div class="topbar-item">
                    <div class="btn btn-icon btn-hover-transparent-white btn-lg mr-1" id="kt_server_config">
                        <a href='{{route('company.index')}}'>
							<span class="svg-icon svg-icon-xl">
								<!--begin::Svg Icon | path:assets/media/svg/icons/Layout/Layout-4-blocks.svg-->
								<i class="fa fa-cog"></i>
                                <!--end::Svg Icon-->
							</span>
                        </a>
                    </div>
                </div>
                @actionEnd
                @endif
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
                    <div class="btn btn-icon btn-hover-transparent-white w-auto d-flex align-items-center btn-lg px-2" id="kt_quick_user_toggle">
                        <div class="d-flex flex-column text-right pr-3">
                            <span class="text-white opacity-50 font-weight-bold font-size-sm d-none d-md-inline">{{Auth::user()->name}}</span>
                            <span class="text-white font-weight-bolder font-size-sm d-none d-md-inline">{{(Auth::user()->position == null)?'SYSTEM':Auth::user()->position}}</span>
                        </div>
                        @php
                            $str = Auth::user()->name;
                        @endphp
                        <span class="symbol symbol-35">
                                        <span class="symbol-label font-size-h5 font-weight-bold text-white bg-white-o-30">{{strtoupper($str[0])}}</span>
                                    </span>
                    </div>
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
    @if (empty(\Auth::user()->discord_id))
    <div class="header-bottom" style="background-color: rgba(39, 53, 74, .7);">
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
                            @if (empty(\Auth::user()->discord_id))
                            <ul class="menu-nav">
                                <li class="menu-item" aria-haspopup="true">
                                    <form action='{{ route('menu.direct') }}' method="post">
                                        @csrf
                                        <div class="typeahead input-group input-group-sm">
                                            <input type="text" id="search-menu" dir="ltr" name="menu" class="form-control" placeholder="Search for..." />
                                            <div class="input-group-append">
                                                <button class="btn btn-secondary" type="submit"><i class="fa fa-search"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </li>
                                @actionStart('general', 'access')
                                <li class="menu-item menu-item-submenu" data-menu-toggle="click" aria-haspopup="true">
                                    <a href="javascript:;" class="menu-link menu-toggle">
                                        <span class="menu-text">General</span>
                                        <span class="menu-desc"></span>
                                        <i class="menu-arrow"></i>
                                    </a>
                                    <div class="menu-submenu menu-submenu-fixed">
                                        <div class="menu-subnav">
                                            <ul class="menu-content">
                                                <li class="menu-item">
                                                    <h3 class="menu-heading menu-toggle">
                                                        <span class="menu-text">Requests</span>
                                                        <i class="menu-arrow"></i>
                                                    </h3>
                                                    <ul class="menu-inner">
                                                        @actionStart('fr', 'access')
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('fr.index')}}" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-line">
                                                                        <span></span>
                                                                    </i>
                                                                <span class="menu-text">[IR] Item Request</span>
                                                            </a>
                                                        </li>
                                                        @actionEnd
                                                        @actionStart('so', 'access')
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('general.so')}}" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-line">
                                                                        <span></span>
                                                                    </i>
                                                                <span class="menu-text">[SO] Service Order</span>
                                                            </a>
                                                        </li>
                                                        @actionEnd
                                                        {{-- @actionStart('do', 'access') --}}
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('do.index')}}" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-line">
                                                                        <span></span>
                                                                    </i>
                                                                <span class="menu-text">[DO] Delivery Order</span>
                                                            </a>
                                                        </li>
                                                        {{-- @actionEnd --}}
                                                        @actionStart('leave_request', 'access')
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{URL::route('leave.request')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">Leave Request</span>
                                                            </a>
                                                        </li>
                                                        @actionEnd
                                                    </ul>
                                                </li>
                                                <li class="menu-item">
                                                    <h3 class="menu-heading menu-toggle">
                                                        <span class="menu-text">Administrations</span>
                                                        <i class="menu-arrow"></i>
                                                    </h3>
                                                    <ul class="menu-inner">
                                                        @actionStart('cashbond', 'access')
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('cashbond.index')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">{{($accounting_mode == 1) ? "Petty Cash" : "Cashbond"}}</span>
                                                            </a>
                                                        </li>
                                                        @actionEnd
                                                        @actionStart('reimburse', 'access')
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('reimburse.index')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">Reimburse</span>
                                                            </a>
                                                        </li>
                                                        @actionEnd
                                                        @actionStart('meeting_scheduler', 'access')
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('ms.index')}}" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-line">
                                                                        <span></span>
                                                                    </i>
                                                                <span class="menu-text">Meeting Scheduler</span>
                                                            </a>
                                                        </li>
                                                        @actionEnd
                                                        @actionStart('mom', 'access')
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('mom.index')}}" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-line">
                                                                        <span></span>
                                                                    </i>
                                                                <span class="menu-text">[MoM] Minutes of Meeting </span>
                                                            </a>
                                                        </li>
                                                        @actionEnd
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('general.covid.index')}}" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-line">
                                                                        <span></span>
                                                                    </i>
                                                                <span class="menu-text">Covid News </span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li class="menu-item">
                                                    <h3 class="menu-heading menu-toggle">
                                                        <span class="menu-text">Operations</span>
                                                        <i class="menu-arrow"></i>
                                                    </h3>
                                                    <ul class="menu-inner">
                                                        @actionStart('to', 'access')
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('to.index')}}" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-line">
                                                                        <span></span>
                                                                    </i>
                                                                <span class="menu-text">Travel Order</span>
                                                            </a>
                                                        </li>
                                                        @actionEnd
                                                        @actionStart('crewloc', 'access')
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('crewloc.index')}}" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-line">
                                                                        <span></span>
                                                                    </i>
                                                                <span class="menu-text">Crew Location</span>
                                                            </a>
                                                        </li>
                                                        @actionEnd
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('forum.index')}}" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">Forum</span>
                                                            </a>
                                                        </li>

                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('general.dr')}}" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-line">
                                                                        <span></span>
                                                                    </i>
                                                                <span class="menu-text">Daily Report </span>
                                                            </a>
                                                        </li>
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('general.operation.index')}}" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-line">
                                                                        <span></span>
                                                                    </i>
                                                                <span class="menu-text">Operation Report </span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li class="menu-item">
                                                    <h3 class="menu-heading menu-toggle">
                                                        <span class="menu-text">Others</span>
                                                        <i class="menu-arrow"></i>
                                                    </h3>
                                                    <ul class="menu-inner">
                                                        @actionStart('performa', 'access')
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('general.pr.index')}}" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-line">
                                                                        <span></span>
                                                                    </i>
                                                                <span class="menu-text">Performa Review</span>
                                                            </a>
                                                        </li>
                                                        @actionEnd
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('general.doc.index')}}" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-line">
                                                                        <span></span>
                                                                    </i>
                                                                <span class="menu-text">Documents </span>
                                                            </a>
                                                        </li>
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('general.maps.index')}}" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-line">
                                                                        <span></span>
                                                                    </i>
                                                                <span class="menu-text">{{ \Session::get('company_tag') }} Map</span>
                                                            </a>
                                                        </li>
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('oletter.index')}}" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-line">
                                                                        <span></span>
                                                                    </i>
                                                                <span class="menu-text">Berita Acara </span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                                @actionEnd
                                @actionStart('asset', 'access')
                                <li class="menu-item menu-item-submenu menu-item-rel" data-menu-toggle="click" aria-haspopup="true">
                                    <a href="javascript:;" class="menu-link menu-toggle">
                                        <span class="menu-text">Asset</span>
                                        <span class="menu-desc"></span>


                                    </a>
                                    <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                        <ul class="menu-subnav">

                                             <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{URL::route('categoryinventory.index')}}" class="menu-link">
                                                        <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">Inventory</span>


                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{URL::route('items.wh')}}" class="menu-link">
                                                        <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">Inventory Storages</span>


                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{URL::route('category.index')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Item Database</span>


                                                </a>
                                            </li>
                                            @actionStart('item_approval', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{URL::route('items.approval')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Item Approval</span>


                                                </a>
                                            </li>
                                            @actionEnd
                                            @actionStart('item_assembly', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{URL::route('items.assembly.index')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Item Assembly</span>
                                                </a>
                                            </li>
                                            @actionEnd
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{URL::route('wh.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
                                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
                                                    </span>
                                                    <span class="menu-text">Storages</span>


                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('general.driver.index')}}" class="menu-link">
                                                        <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">Mobilization</span>
                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('gr.index')}}" class="menu-link">
                                                        <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">[GR] Good Receive</span>


                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('asset.legal.index')}}" class="menu-link">
                                                        <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">Legal Document</span>
                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('items.last.input')}}" class="menu-link">
                                                        <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">Last Input Item</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                @actionEnd
                                @actionStart('procurement', 'access')
                                <li class="menu-item menu-item-submenu menu-item-rel" data-menu-toggle="click" aria-haspopup="true">
                                    <a href="javascript:;" class="menu-link menu-toggle">
                                        <span class="menu-text">Procurement</span>
                                        <span class="menu-desc"></span>


                                    </a>
                                    <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                        <ul class="menu-subnav">
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{URL::route('vendor.index')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Vendor</span>


                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('pricelist.index')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Price List</span>
                                                </a>
                                            </li>

                                        </ul>
                                    </div>
                                </li>
                                @actionEnd
                                @actionStart('po_wo', 'access')
                                <li class="menu-item menu-item-submenu menu-item-rel" data-menu-toggle="click" aria-haspopup="true">
                                    <a href="javascript:;" class="menu-link menu-toggle">
                                        <span class="menu-text">PO & WO</span>
                                        <span class="menu-desc"></span>


                                    </a>
                                    <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                        <ul class="menu-subnav">
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{URL::route('pr.index')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">[PR] Purchase Request</span>


                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{URL::route('pe.index')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">[PE] Purchase Evaluation</span>


                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{URL::route('po.index')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">[PO] Purchase Order</span>


                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{URL::route('sr.index')}}" class="menu-link ">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">[SR] Service Request</span>


                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{URL::route('se.index')}}" class="menu-link ">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">[SE] Service Evaluation</span>


                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('general.wo')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">[WO] Work Order</span>


                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                @actionEnd
                                @actionStart('marketing', 'access')
                                <li class="menu-item menu-item-submenu menu-item-rel" data-menu-toggle="click" aria-haspopup="true">
                                    <a href="javascript:;" class="menu-link menu-toggle">
                                        <span class="menu-text">Marketing</span>
                                        <span class="menu-desc"></span>
                                    </a>
                                    <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                        <ul class="menu-subnav">
                                            @if($accounting_mode == 1)
                                                @actionStart('leads', 'access')
                                                <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                    <a href="{{route('leads.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                        <span class="menu-text">Leads</span>


                                                    </a>
                                                </li>
                                                @actionEnd
                                                @actionStart('leadsmanagement', 'access')
                                                <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                    <a href="{{route('leads.index_management')}}" class="menu-link">
                                                <span class="svg-icon menu-icon">
                                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                    <!--end::Svg Icon-->
                                                    </span>
                                                        <span class="menu-text">Leads Management</span>


                                                    </a>
                                                </li>
                                                @actionEnd
                                            @endif
                                            @actionStart('project', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('marketing.project')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Projects</span>


                                                </a>
                                            </li>
                                            @actionEnd
                                            @actionStart('client', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('marketing.client.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Clients</span>


                                                </a>
                                            </li>
                                            @actionEnd
                                            @actionStart('subcost', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('subcost.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Sub Cost</span>


                                                </a>
                                            </li>
                                            @actionEnd
                                            @actionStart('b_p', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('bp.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Bid & Performance</span>


                                                </a>
                                            </li>
                                            @actionEnd
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('marketing.doc.index')}}" class="menu-link">
                                                        <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">Documents </span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                @actionEnd
                                @actionStart('hrd', 'access')
                                <li class="menu-item menu-item-submenu menu-item-rel" data-menu-toggle="click" aria-haspopup="true">
                                    <a href="javascript:;" class="menu-link menu-toggle">
                                        <span class="menu-text">HRD</span>
                                        <span class="menu-desc"></span>


                                    </a>
                                    <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                        <ul class="menu-subnav">
                                            @actionStart('announcement', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('announcement.index')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Announcement</span>
                                                </a>
                                            </li>
                                            @actionEnd
                                            @actionStart('employee', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('employee.index')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Employee</span>
                                                </a>
                                            </li>
                                            @actionEnd
                                            @actionStart('employee', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('hrd.crewnotif.index')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Crew Operation Notificaton</span>
                                                </a>
                                            </li>
                                            @actionEnd
                                            @actionStart('employee', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('hrd.crewhr.index')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Crew Location HR</span>
                                                </a>
                                            </li>
                                            @actionEnd
                                            @actionStart('overtime', 'access')
                                            <li class="menu-item menu-item-submenu">
                                                <a href="{{route('overtime.index')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Overtime</span>


                                                </a>
                                            </li>
                                            @actionEnd
                                            @actionStart('emp_loan', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('employee.loan')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Employee Loan</span>


                                                </a>
                                            </li>
                                            @actionEnd
                                            @actionStart('payroll', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{URL::route('payroll.index')}}" class="menu-link">
                                                        <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">Payroll</span>


                                                </a>
                                            </li>
                                            @actionEnd
                                            @actionStart('leave_approval', 'access')
                                            <li class="menu-item menu-item-submenu">
                                                <a href="{{URL::route('leave.index')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Leave Approval</span>


                                                </a>
                                            </li>
                                            @actionEnd
                                            @actionStart('bonus', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('subsidies.index')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Bonus</span>


                                                </a>
                                            </li>
                                            @actionEnd
                                            @actionStart('deduction', 'access')
                                             <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('sanction.index')}}" class="menu-link">
                                                        <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">Deduction</span>


                                                </a>
                                            </li>
                                            @actionEnd
                                            @actionStart('point', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{URL::route('point.index')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Point</span>


                                                </a>
                                            </li>
                                            @actionEnd
                                            @actionStart('severance', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{URL::route('severance.index')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Severance</span>


                                                </a>
                                            </li>
                                            @actionEnd
                                            @actionStart('training', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{URL::route('training.index')}}" class="menu-link">
                                                        <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">Training</span>


                                                </a>
                                            </li>
                                            @actionEnd
                                            @actionStart('official_letter', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{URL::route('decree.index')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Official Letter</span>


                                                </a>
                                            </li>
                                            @actionEnd
                                            @actionStart('policy', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{URL::route('policy.index')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Policy</span>


                                                </a>
                                            </li>
                                            @actionEnd
                                            @actionStart('csr', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('csr.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">[CSR] Corporate Social Responsibility</span>
                                                </a>
                                            </li>
                                            @actionEnd
                                            @actionStart('employee', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('hrd.contract.index')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Contract Template</span>
                                                </a>
                                            </li>
                                            @actionEnd
                                        </ul>
                                    </div>
                                </li>
                                @actionEnd
                                @actionStart('finance', 'access')
                                <li class="menu-item menu-item-submenu" data-menu-toggle="click" aria-haspopup="true">
                                    <a href="javascript:;" class="menu-link menu-toggle">
                                        <span class="menu-text">Finance</span>
                                        <span class="menu-desc"></span>
                                        <i class="menu-arrow"></i>
                                    </a>
                                    <div class="menu-submenu menu-submenu-fixed">
                                        <div class="menu-subnav">
                                            <ul class="menu-content">
                                                <li class="menu-item">
                                                    <h3 class="menu-heading menu-toggle">
                                                        <span class="menu-text">Treasury</span>
                                                        <i class="menu-arrow"></i>
                                                    </h3>
                                                    <ul class="menu-inner">
                                                        <li class="menu-item" aria-haspopup="true">
                                                            <a href="{{route('treasury.index')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">Treasury</span>
                                                            </a>
                                                        </li>
                                                        <li class="menu-item" aria-haspopup="true">
                                                            <a href="{{route('inv_in.index')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">
                                                                    @if($accounting_mode == 1)
                                                                        Account Receivable
                                                                    @else
                                                                        Invoice In
                                                                    @endif
                                                                </span>
                                                            </a>
                                                        </li>
                                                        <li class="menu-item" aria-haspopup="true">
                                                            <a href="{{route('ar.index')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">
                                                                    @if($accounting_mode == 1)
                                                                        Account Receivable
                                                                    @else
                                                                        Invoice Out
                                                                    @endif
                                                                </span>
                                                            </a>
                                                        </li>
                                                        <li class="menu-item" aria-haspopup="true">
                                                            <a href="{{route('sp.index')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">Schedule Payment</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li class="menu-item">
                                                    <h3 class="menu-heading menu-toggle">
                                                        <span class="menu-text">Utilization</span>
                                                        <i class="menu-arrow"></i>
                                                    </h3>
                                                    <ul class="menu-inner">
                                                        <li class="menu-item" aria-haspopup="true">
                                                            <a href="{{route('util.index')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">Utilization</span>
                                                            </a>
                                                        </li>
                                                        <li class="menu-item" aria-haspopup="true">
                                                            <a href="{{route('loan.index')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">Loan</span>
                                                            </a>
                                                        </li>
                                                        <li class="menu-item" aria-haspopup="true">
                                                            <a href="{{route('leasing.index')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">Leasing</span>
                                                            </a>
                                                        </li>
                                                        <li class="menu-item" aria-haspopup="true">
                                                            <a href="{{route('bp.index')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">Bid & Performance</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li class="menu-item">
                                                    <h3 class="menu-heading menu-toggle">
                                                        <span class="menu-text">Salary</span>
                                                        <i class="menu-arrow"></i>
                                                    </h3>
                                                    <ul class="menu-inner">
                                                        <li class="menu-item" aria-haspopup="true">
                                                            <a href="{{route('salfin.index')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">Salary Financing</span>
                                                            </a>
                                                        </li>
                                                        <li class="menu-item" aria-haspopup="true">
                                                            <a href="{{route('finance.bpjs')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">BPJS Kesehatan</span>
                                                            </a>
                                                        </li>
                                                        <li class="menu-item" aria-haspopup="true">
                                                            <a href="{{route('finance.bpjs_tk')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">BPJS TK</span>
                                                            </a>
                                                        </li>
                                                        <li class="menu-item" aria-haspopup="true">
                                                            <a href="{{URL::route('payroll.index')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">Payroll</span>
                                                            </a>
                                                        </li>
                                                        <li class="menu-item" aria-haspopup="true">
                                                            <a href="{{URL::route('coa.source.index')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">{{ !empty(\Session::get('company_tc_initial')) ? \Session::get('company_tc_initial') : "TC" }} Assignment</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li class="menu-item">
                                                    <h3 class="menu-heading menu-toggle">
                                                        <span class="menu-text">Others</span>
                                                        <i class="menu-arrow"></i>
                                                    </h3>
                                                    <ul class="menu-inner">
                                                        <li class="menu-item" aria-haspopup="true">
                                                            <a href="{{route('gj.index')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">Input Journal</span>
                                                            </a>
                                                        </li>
                                                        <li class="menu-item" aria-haspopup="true">
                                                            <a href="{{route('fts.index')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">Transaction Summary</span>
                                                            </a>
                                                        </li>
                                                        <li class="menu-item" aria-haspopup="true">
                                                            <a href="{{route('business.index')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                    <span class="menu-text">Business</span>
                                                                </a>
                                                        </li>
                                                        <li class="menu-item" aria-haspopup="true">
                                                            <a href="{{route('finance.fd.index')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">Filling Document</span>
                                                            </a>
                                                        </li>
                                                        <li class="menu-item" aria-haspopup="true">
                                                            <a href="{{route('finance.br.index')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">Budget Request</span>
                                                            </a>
                                                        </li>
                                                        <li class="menu-item" aria-haspopup="true">
                                                            <a href="{{route('finance.dp.index')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">Depreciation </span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                                <li class="menu-item menu-item-submenu menu-item-rel" data-menu-toggle="click" aria-haspopup="true">
                                    <a href="javascript:;" class="menu-link menu-toggle">
                                        <span class="menu-text">Reports</span>
                                        <span class="menu-desc"></span>


                                    </a>
                                    <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                        <ul class="menu-subnav">
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('report.ar.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon"></span>
                                                    <span class="menu-text">Account Receivable</span>
                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('report.ap.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon"></span>
                                                    <span class="menu-text">Account Payable</span>
                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('tax.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
														</span>
                                                    <span class="menu-text">Tax Report</span>
                                                </a>
                                            </li>
                                            <li class="menu-item" aria-haspopup="true">
                                                <a href="{{route('report.journal.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon"></span>
                                                    <span class="menu-text">Sales & Receivables Journal</span>
                                                </a>
                                            </li>
                                            <li class="menu-item" aria-haspopup="true">
                                                <a href="{{route('report.journal_general.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon"></span>
                                                    <span class="menu-text">General Journal</span>
                                                </a>
                                            </li>
                                            <li class="menu-item" aria-haspopup="true">
                                                <a href="{{route('gl.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon"></span>
                                                    <span class="menu-text">General Ledger</span>
                                                </a>
                                            </li>
                                            <li class="menu-item" aria-haspopup="true">
                                                <a href="{{route('report.tb.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon"></span>
                                                    <span class="menu-text">Trial Balance</span>
                                                </a>
                                            </li>
                                            <li class="menu-item" aria-haspopup="true">
                                                <a href="{{route('bs.list')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon"></span>
                                                    <span class="menu-text">Balance Sheet</span>
                                                </a>
                                            </li>
                                            <li class="menu-item" aria-haspopup="true">
                                                <a href="{{route('pl.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon"></span>
                                                    <span class="menu-text">Profit & Loss</span>
                                                </a>
                                            </li>
                                            <li class="menu-item" aria-haspopup="true">
                                                <a href="{{route('finance.cf.list')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon"></span>
                                                    <span class="menu-text">Cash Flow</span>
                                                </a>
                                            </li>
                                            @actionStart('charts', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('chart.custom.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon"></span>
                                                    <span class="menu-text">Custom Charts</span>
                                                </a>
                                            </li>
                                            @actionEnd
                                            @if (\Auth::id() == 1)
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('report.er.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon"></span>
                                                    <span class="menu-text">Exchange Rate</span>
                                                </a>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </li>
                                @actionEnd
                                @if($accounting_mode == 1)
                                    @actionStart('trading', 'access')
                                    <li class="menu-item menu-item-submenu menu-item-rel" data-menu-toggle="click" aria-haspopup="true">
                                        <a href="javascript:;" class="menu-link menu-toggle">
                                            <span class="menu-text">Trading</span>
                                            <span class="menu-desc"></span>
                                        </a>
                                        <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                            <ul class="menu-subnav">
                                                <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                    <a href="{{route('trading.orders.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                        <span class="menu-text">Orders</span>
                                                        <!--client-->

                                                    </a>
                                                </li>
                                                <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                    <a href="{{route('trading.products.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                        <span class="menu-text">Products</span>
                                                        <!--client-->

                                                    </a>
                                                </li>
                                                <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                    <a href="{{route('trading.supplier.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                        <span class="menu-text">Suppliers</span>
                                                        <!--vendor-->

                                                    </a>
                                                </li>
                                                <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                    <a href="{{route('trading.market.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                        <span class="menu-text">Markets</span>
                                                        <!--client-->

                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    @actionEnd
                                @endif

                                @if($accounting_mode != 1)
                                    @actionStart('technical_engineering', 'access')
                                    <li class="menu-item menu-item-submenu menu-item-rel" data-menu-toggle="click" aria-haspopup="true">
                                        <a href="javascript:;" class="menu-link menu-toggle">
                                            <span class="menu-text">Technical Engineering</span>
                                            <span class="menu-desc"></span>


                                        </a>
                                        <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                            <ul class="menu-subnav">
                                                <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                    <a href="{{route('te.el.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                        <span class="menu-text">Equipment List</span>
                                                    </a>
                                                </li>
                                                <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                    <a href="{{route('te.pd.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                        <span class="menu-text">Project Design</span>
                                                    </a>
                                                </li>
                                                <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                    <a href="{{route('te.swt.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                        <span class="menu-text">Surface Welltesting</span>
                                                    </a>
                                                </li>
                                                <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                    <a href="{{route('te.subwt.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                        <span class="menu-text">Subsurface Welltesting</span>
                                                    </a>
                                                </li>
                                                <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                    <a href="{{route('te.slickline.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                        <span class="menu-text">Slickline</span>
                                                    </a>
                                                </li>
                                                <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                    <a href="{{route('te.instrument.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
                                                        </span>
                                                        <span class="menu-text">Instrumentation</span>
                                                    </a>
                                                </li>
                                                <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                    <a href="{{route('te.testeq.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
                                                        </span>
                                                        <span class="menu-text">Test Equipment</span>
                                                    </a>
                                                </li>
                                                <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                    <a href="{{route('te.pv.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
                                                        </span>
                                                        <span class="menu-text">Pressure Vessel</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    @actionEnd
                                @endif
                                @actionStart('h_a', 'access')
                                <li class="menu-item menu-item-submenu menu-item-rel" data-menu-toggle="click" aria-haspopup="true">
                                    <a href="javascript:;" class="menu-link menu-toggle">
                                        <span class="menu-text">Higher Authority</span>
                                        <span class="menu-desc"></span>


                                    </a>
                                    <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                        <ul class="menu-subnav">
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('ha.powoval.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">PO/WO Validation</span>
                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('ha.powotypes.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">PO/WO Types</span>
                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('salarylist.index')}}" class="menu-link">
                                                        <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">Salary List</span>


                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('bankceo.index')}}" class="menu-link">
                                                        <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">CEO Bank</span>


                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('insceo.index')}}" class="menu-link">
                                                        <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">Insurance CEO</span>


                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('ha.password.index')}}" class="menu-link">
                                                        <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">Passwords</span>
                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('ha.ve.index')}}" class="menu-link">
                                                        <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">Asset Vehicles</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                @actionEnd
                                @actionStart('qhse', 'access')
                                <li class="menu-item menu-item-submenu menu-item-rel" data-menu-toggle="click" aria-haspopup="true">
                                    <a href="javascript:;" class="menu-link menu-toggle">
                                        <span class="menu-text">QHSE</span>
                                        <span class="menu-desc"></span>


                                    </a>
                                    <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                        <ul class="menu-subnav">

                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('mcu.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">[MCU] Medical Check Up</span>
                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('csr.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">[CSR] Corporate Social Responsibility</span>
                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('nearmiss.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">Near Miss</span>
                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('miss.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">Miss</span>
                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{URL::route('policy.hse.index')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Policy</span>


                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('sm.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">Safety Meeting</span>
                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('mv.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">Management Visit</span>
                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('sop.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">[SOP] Standard Operating Procedure</span>
                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('qhse.csms.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">CSMS</span>
                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('qhse.tr.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Training Record</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                @actionEnd
                                @if($accounting_mode != 1)
                                {{-- @actionStart('charts', 'access')
                                <li class="menu-item menu-item-submenu menu-item-rel" data-menu-toggle="click" aria-haspopup="true">
                                    <a href="javascript:;" class="menu-link menu-toggle">
                                        <span class="menu-text">Charts</span>
                                        <span class="menu-desc"></span>


                                    </a>
                                    <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                        <ul class="menu-subnav">

                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('chart.custom.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Custom Charts</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                @actionEnd --}}
                                @endif
                            </ul>
                            @endif
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
    @endif
    <!--end::Bottom-->
</div>
<!--end::Header
