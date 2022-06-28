<div class="container-fluid">

        @actionStart('general', 'access')
        <div class="row">
            <div class="subheader subheader-transparent " id="kt_subheader">
                <div class=" container  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center flex-wrap mr-1">
                        <!--begin::Page Heading-->
                        <div class="d-flex align-items-baseline flex-wrap mr-5">
                            <!--begin::Page Title-->
                            <h5 class="text-dark font-weight-bold my-1 mr-5">General</h5>
                            <!--end::Page Title-->
                            <!--end::Page Heading-->
                        </div>
                        <!--end::Info-->
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row mb-5">
            <div class="col-lg-2 col-xl-2 draggable-zone">
                <!--begin::Iconbox-->
                <div class="card card-custom wave gutter-b draggable wave-animate-slow wave-danger mb-8 mb-lg-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center p-5">
                            <div class="">
                                    <span class="svg-icon svg-icon-4x">
                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg-->
                                        <div class="symbol symbol-danger">
                                            <span class="symbol-label font-size-h5">
                                                <a href="{{route('fr.index')}}" class="btn btn-icon btn-lg draggable-handle">
                                                    <i class="fa fa-cube text-white"></i>
                                                </a>
                                            </span>
                                        </div>
                                        <!--end::Svg Icon-->
                                    </span>
                            </div>
                        </div>
                        <div class="d-flex flex-column text-center">
                            <a href="{{route('fr.index')}}"><span class="text-dark font-weight-bold mb-3">Item Request</span></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-xl-2 draggable-zone">
                <!--begin::Iconbox-->
                <div class="card card-custom wave gutter-b draggable wave-animate-slow wave-info mb-8 mb-lg-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center p-5">
                            <div class="">
                                    <span class="svg-icon svg-icon-4x">
                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg-->
                                        <div class="symbol symbol-info">
                                            <span class="symbol-label font-size-h5">
                                                <a href="{{route('pr.index')}}" class="btn btn-icon btn-lg draggable-handle">
                                                    <i class="fa fa-cash-register text-white"></i>
                                                </a>
                                            </span>
                                        </div>
                                        <!--end::Svg Icon-->
                                    </span>
                            </div>
                        </div>
                        <div class="d-flex flex-column text-center">
                            <a href="{{route('pr.index')}}" class="text-dark font-weight-bold mb-3">Purchase Request</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-xl-2 draggable-zone">
                <!--begin::Iconbox-->
                <div class="card card-custom wave gutter-b draggable wave-animate-slow wave-warning mb-8 mb-lg-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center p-5">
                            <div class="">
                                    <span class="svg-icon svg-icon-4x">
                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg-->
                                        <div class="symbol symbol-warning">
                                            <span class="symbol-label font-size-h5">
                                                <a href="{{route('pe.index')}}" class="btn btn-icon btn-lg draggable-handle">
                                                    <i class="fa fa-search-dollar text-white"></i>
                                                </a>
                                            </span>
                                        </div>
                                        <!--end::Svg Icon-->
                                    </span>
                            </div>
                        </div>
                        <div class="d-flex flex-column text-center">
                            <a href="{{route('pe.index')}}" class="text-dark font-weight-bold mb-3">Purchase Evaluation</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-xl-2 draggable-zone">
                <!--begin::Iconbox-->
                <div class="card card-custom wave gutter-b draggable wave-animate-slow mb-8 mb-lg-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center p-5">
                            <div class="">
                                    <span class="svg-icon svg-icon-4x">
                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg-->
                                        <div class="symbol symbol-dark">
                                            <span class="symbol-label font-size-h5">
                                                <a href="{{route('po.index')}}" class="btn btn-icon btn-lg draggable-handle">
                                                    <i class="fa fa-cart-arrow-down text-white"></i>
                                                </a>
                                            </span>
                                        </div>
                                        <!--end::Svg Icon-->
                                    </span>
                            </div>
                        </div>
                        <div class="d-flex flex-column text-center">
                            <a href="{{route('po.index')}}" class="text-dark font-weight-bold mb-3">Purchase Order</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-xl-2 draggable-zone">
                <!--begin::Iconbox-->
                <div class="card card-custom wave gutter-b draggable wave-animate-slow wave-info mb-8 mb-lg-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center p-5">
                            <div class="">
                                    <span class="svg-icon svg-icon-4x">
                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg-->
                                        <div class="symbol symbol-info">
                                            <span class="symbol-label font-size-h5">
                                                <a href="{{route('cashbond.index')}}" class="btn btn-icon btn-lg draggable-handle">
                                                    <i class="fa fa-money-bill-wave text-white"></i>
                                                </a>
                                            </span>
                                        </div>
                                        <!--end::Svg Icon-->
                                    </span>
                            </div>
                        </div>
                        <div class="d-flex flex-column text-center">
                            <a href="{{route('cashbond.index')}}" class="text-dark font-weight-bold mb-3">Cashbond</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-lg-2 col-xl-2 draggable-zone">
                <!--begin::Iconbox-->
                <div class="card card-custom wave gutter-b draggable wave-animate-slow wave-danger mb-8 mb-lg-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center p-5">
                            <div class="">
                                    <span class="svg-icon svg-icon-4x">
                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg-->
                                        <div class="symbol symbol-danger">
                                            <span class="symbol-label font-size-h5">
                                                <a href="{{route('general.so')}}" class="btn btn-icon btn-lg draggable-handle">
                                                    <i class="fa fa-clipboard-list text-white"></i>
                                                </a>
                                            </span>
                                        </div>
                                        <!--end::Svg Icon-->
                                    </span>
                            </div>
                        </div>
                        <div class="d-flex flex-column text-center">
                            <a href="{{route('general.so')}}" class="text-dark font-weight-bold mb-3">Service Order</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-xl-2 draggable-zone">
                <!--begin::Iconbox-->
                <div class="card card-custom wave gutter-b draggable wave-animate-slow wave-danger mb-8 mb-lg-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center p-5">
                            <div class="">
                                    <span class="svg-icon svg-icon-4x">
                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg-->
                                        <div class="symbol symbol-danger">
                                            <span class="symbol-label font-size-h5">
                                                <a href="{{route('sr.index')}}" class="btn btn-icon btn-lg draggable-handle">
                                                    <i class="fa fa-headset text-white"></i>
                                                </a>
                                            </span>
                                        </div>
                                        <!--end::Svg Icon-->
                                    </span>
                            </div>
                        </div>
                        <div class="d-flex flex-column text-center">
                            <a href="{{route('sr.index')}}" class="text-dark font-weight-bold mb-3">Service Request</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-xl-2 draggable-zone">
                <!--begin::Iconbox-->
                <div class="card card-custom wave gutter-b draggable wave-animate-slow wave-warning mb-8 mb-lg-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center p-5">
                            <div class="">
                                    <span class="svg-icon svg-icon-4x">
                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg-->
                                        <div class="symbol symbol-warning">
                                            <span class="symbol-label font-size-h5">
                                                <a href="{{route('se.index')}}" class="btn btn-icon btn-lg draggable-handle">
                                                    <i class="fa fa-edit text-white"></i>
                                                </a>
                                            </span>
                                        </div>
                                        <!--end::Svg Icon-->
                                    </span>
                            </div>
                        </div>
                        <div class="d-flex flex-column text-center">
                            <a href="{{route('se.index')}}" class="text-dark font-weight-bold mb-3">Service Evaluation</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-xl-2 draggable-zone">
                <!--begin::Iconbox-->
                <div class="card card-custom wave gutter-b draggable wave-animate-slow wave-success mb-8 mb-lg-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center p-5">
                            <div class="">
                                    <span class="svg-icon svg-icon-4x">
                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg-->
                                        <div class="symbol symbol-success">
                                            <span class="symbol-label font-size-h5">
                                                <a href="{{route('po.index')}}" class="btn btn-icon btn-lg draggable-handle">
                                                    <i class="fa fa-users-cog text-white"></i>
                                                </a>
                                            </span>
                                        </div>
                                        <!--end::Svg Icon-->
                                    </span>
                            </div>
                        </div>
                        <div class="d-flex flex-column text-center">
                            <a href="{{route('po.index')}}" class="text-dark font-weight-bold mb-3">Work Order</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-xl-2 draggable-zone">
                <!--begin::Iconbox-->
                <div class="card card-custom wave gutter-b draggable wave-animate-slow wave-primary mb-8 mb-lg-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center p-5">
                            <div class="">
                                    <span class="svg-icon svg-icon-4x">
                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg-->
                                        <div class="symbol symbol-primary">
                                            <span class="symbol-label font-size-h5">
                                                <a href="{{route('reimburse.index')}}" class="btn btn-icon btn-lg draggable-handle">
                                                    <i class="fa fa-file-invoice-dollar text-white"></i>
                                                </a>
                                            </span>
                                        </div>
                                        <!--end::Svg Icon-->
                                    </span>
                            </div>
                        </div>
                        <div class="d-flex flex-column text-center">
                            <a href="{{route('reimburse.index')}}" class="text-dark font-weight-bold mb-3 text-nowrap">Reimburse</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @actionEnd
        <hr>
        <div class="row">
            <div class="subheader subheader-transparent " id="kt_subheader">
                <div class=" container  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center flex-wrap mr-1">
                        <!--begin::Page Heading-->
                        <div class="d-flex align-items-baseline flex-wrap mr-5">
                            <!--begin::Page Title-->
                            <h5 class="text-dark font-weight-bold my-1 mr-5">Asset & Procurement</h5>
                            <!--end::Page Title-->
                            <!--end::Page Heading-->
                        </div>
                        <!--end::Info-->
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row mb-5">
            <div class="col-lg-2 col-xl-2 draggable-zone">
                <!--begin::Iconbox-->
                <div class="card card-custom wave gutter-b draggable wave-animate-slow wave-warning mb-8 mb-lg-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center p-5">
                            <div class="">
                                    <span class="svg-icon svg-icon-4x">
                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg-->
                                        <div class="symbol symbol-warning">
                                            <span class="symbol-label font-size-h5">
                                                <a href="{{route('items.index')}}" class="btn btn-icon btn-lg draggable-handle">
                                                    <i class="fa la-cube text-white"></i>
                                                </a>
                                            </span>
                                        </div>
                                        <!--end::Svg Icon-->
                                    </span>
                            </div>
                        </div>
                        <div class="d-flex flex-column text-center">
                            <a href="{{route('items.index')}}" class="text-dark font-weight-bold mb-3">Items</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-xl-2 draggable-zone">
                <!--begin::Iconbox-->
                <div class="card card-custom wave gutter-b draggable wave-animate-slow wave-primary mb-8 mb-lg-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center p-5">
                            <div class="">
                                    <span class="svg-icon svg-icon-4x">
                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg-->
                                        <div class="symbol symbol-primary">
                                            <span class="symbol-label font-size-h5">
                                                <a href="#" class="btn btn-icon btn-lg draggable-handle">
                                                    <i class="fa fa-warehouse text-white"></i>
                                                </a>
                                            </span>
                                        </div>
                                        <!--end::Svg Icon-->
                                    </span>
                            </div>
                        </div>
                        <div class="d-flex flex-column text-center">
                            <a href="#" class="text-dark font-weight-bold mb-3">Storages</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-xl-2 draggable-zone">
                <!--begin::Iconbox-->
                <div class="card card-custom wave gutter-b draggable wave-animate-slow wave-danger mb-8 mb-lg-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center p-5">
                            <div class="">
                                    <span class="svg-icon svg-icon-4x">
                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg-->
                                        <div class="symbol symbol-danger">
                                            <span class="symbol-label font-size-h5">
                                                <a href="{{route('vendor.index')}}" class="btn btn-icon btn-lg draggable-handle">
                                                    <i class="fa fa-handshake text-white"></i>
                                                </a>
                                            </span>
                                        </div>
                                        <!--end::Svg Icon-->
                                    </span>
                            </div>
                        </div>
                        <div class="d-flex flex-column text-center">
                            <a href="{{route('vendor.index')}}" class="text-dark font-weight-bold mb-3">Vendors</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-xl-2 draggable-zone">
                <!--begin::Iconbox-->
                <div class="card card-custom wave gutter-b draggable wave-animate-slow wave-info mb-8 mb-lg-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center p-5">
                            <div class="">
                                    <span class="svg-icon svg-icon-4x">
                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg-->
                                        <div class="symbol symbol-info">
                                            <span class="symbol-label font-size-h5">
                                                <a href="#" class="btn btn-icon btn-lg draggable-handle">
                                                    <i class="fa fa-money-check-alt text-white"></i>
                                                </a>
                                            </span>
                                        </div>
                                        <!--end::Svg Icon-->
                                    </span>
                            </div>
                        </div>
                        <div class="d-flex flex-column text-center">
                            <a href="#" class="text-dark font-weight-bold mb-3">Price Lists</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="subheader subheader-transparent " id="kt_subheader">
                <div class=" container  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center flex-wrap mr-1">
                        <!--begin::Page Heading-->
                        <div class="d-flex align-items-baseline flex-wrap mr-5">
                            <!--begin::Page Title-->
                            <h5 class="text-dark font-weight-bold my-1 mr-5">Marketing</h5>
                            <!--end::Page Title-->
                            <!--end::Page Heading-->
                        </div>
                        <!--end::Info-->
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row mb-5">
            <div class="col-lg-2 col-xl-2 draggable-zone">
                <!--begin::Iconbox-->
                <div class="card card-custom wave gutter-b draggable wave-animate-slow wave-primary mb-8 mb-lg-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center p-5">
                            <div class="">
                                    <span class="svg-icon svg-icon-4x">
                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg-->
                                        <div class="symbol symbol-primary">
                                            <span class="symbol-label font-size-h5">
                                                <a href="{{route('marketing.project')}}" class="btn btn-icon btn-lg draggable-handle">
                                                    <i class="fa fa-folder text-white"></i>
                                                </a>
                                            </span>
                                        </div>
                                        <!--end::Svg Icon-->
                                    </span>
                            </div>
                        </div>
                        <div class="d-flex flex-column text-center">
                            <a href="{{route('marketing.project')}}" class="text-dark font-weight-bold mb-3">Projects</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-xl-2 draggable-zone">
                <!--begin::Iconbox-->
                <div class="card card-custom wave gutter-b draggable wave-animate-slow wave-danger mb-8 mb-lg-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center p-5">
                            <div class="">
                                    <span class="svg-icon svg-icon-4x">
                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg-->
                                        <div class="symbol symbol-danger">
                                            <span class="symbol-label font-size-h5">
                                                <a href="{{route('marketing.client.index')}}" class="btn btn-icon btn-lg draggable-handle">
                                                    <i class="fa fa-users text-white"></i>
                                                </a>
                                            </span>
                                        </div>
                                        <!--end::Svg Icon-->
                                    </span>
                            </div>
                        </div>
                        <div class="d-flex flex-column text-center">
                            <a href="{{route('marketing.client.index')}}" class="text-dark font-weight-bold mb-3">Clients</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="subheader subheader-transparent " id="kt_subheader">
                <div class=" container  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center flex-wrap mr-1">
                        <!--begin::Page Heading-->
                        <div class="d-flex align-items-baseline flex-wrap mr-5">
                            <!--begin::Page Title-->
                            <h5 class="text-dark font-weight-bold my-1 mr-5">HRD</h5>
                            <!--end::Page Title-->
                            <!--end::Page Heading-->
                        </div>
                        <!--end::Info-->
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row mb-5">
            <div class="col-lg-2 col-xl-2 draggable-zone">
                <!--begin::Iconbox-->
                <div class="card card-custom wave gutter-b draggable wave-animate-slow wave-success mb-8 mb-lg-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center p-5">
                            <div class="">
                                    <span class="svg-icon svg-icon-4x">
                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg-->
                                        <div class="symbol symbol-success">
                                            <span class="symbol-label font-size-h5">
                                                <a href="{{route('employee.index')}}" class="btn btn-icon btn-lg draggable-handle">
                                                    <i class="fa fa-users text-white"></i>
                                                </a>
                                            </span>
                                        </div>
                                        <!--end::Svg Icon-->
                                    </span>
                            </div>
                        </div>
                        <div class="d-flex flex-column text-center">
                            <a href="{{route('employee.index')}}" class="text-dark font-weight-bold mb-3">Employee</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-xl-2 draggable-zone">
                <!--begin::Iconbox-->
                <div class="card card-custom wave gutter-b draggable wave-animate-slow wave-warning mb-8 mb-lg-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center p-5">
                            <div class="">
                                    <span class="svg-icon svg-icon-4x">
                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg-->
                                        <div class="symbol symbol-warning">
                                            <span class="symbol-label font-size-h5">
                                                <a href="{{route('overtime.index')}}" class="btn btn-icon btn-lg draggable-handle">
                                                    <i class="fa fa-moon text-white"></i>
                                                </a>
                                            </span>
                                        </div>
                                        <!--end::Svg Icon-->
                                    </span>
                            </div>
                        </div>
                        <div class="d-flex flex-column text-center">
                            <a href="{{route('overtime.index')}}" class="text-dark font-weight-bold mb-3">Overtime</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-xl-2 draggable-zone">
                <!--begin::Iconbox-->
                <div class="card card-custom wave gutter-b draggable wave-animate-slow wave-primary mb-8 mb-lg-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center p-5">
                            <div class="">
                                    <span class="svg-icon svg-icon-4x">
                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg-->
                                        <div class="symbol symbol-primary">
                                            <span class="symbol-label font-size-h5">
                                                <a href="{{route('employee.loan')}}" class="btn btn-icon btn-lg draggable-handle">
                                                    <i class="fa fa-newspaper text-white"></i>
                                                </a>
                                            </span>
                                        </div>
                                        <!--end::Svg Icon-->
                                    </span>
                            </div>
                        </div>
                        <div class="d-flex flex-column text-center">
                            <a href="{{route('employee.loan')}}" class="text-dark font-weight-bold mb-3">Employee Loan</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-xl-2 draggable-zone">
                <!--begin::Iconbox-->
                <div class="card card-custom wave gutter-b draggable wave-animate-slow wave-warning mb-8 mb-lg-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center p-5">
                            <div class="">
                                    <span class="svg-icon svg-icon-4x">
                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg-->
                                        <div class="symbol symbol-warning">
                                            <span class="symbol-label font-size-h5">
                                                <a href="{{route('leave.request')}}" class="btn btn-icon btn-lg draggable-handle">
                                                    <i class="fa fa-luggage-cart text-white"></i>
                                                </a>
                                            </span>
                                        </div>
                                        <!--end::Svg Icon-->
                                    </span>
                            </div>
                        </div>
                        <div class="d-flex flex-column text-center">
                            <a href="{{route('leave.request')}}" class="text-dark font-weight-bold mb-3">Leave Request</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-xl-2 draggable-zone">
                <!--begin::Iconbox-->
                <div class="card card-custom wave gutter-b draggable wave-animate-slow mb-8 mb-lg-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center p-5">
                            <div class="">
                                    <span class="svg-icon svg-icon-4x">
                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg-->
                                        <div class="symbol symbol-dark">
                                            <span class="symbol-label font-size-h5">
                                                <a href="{{route('leave.index')}}" class="btn btn-icon btn-lg draggable-handle">
                                                    <i class="fa fa-luggage-cart text-white"></i>
                                                </a>
                                            </span>
                                        </div>
                                        <!--end::Svg Icon-->
                                    </span>
                            </div>
                        </div>
                        <div class="d-flex flex-column text-center">
                            <a href="{{route('leave.index')}}" class="text-dark font-weight-bold mb-3">Leave Approval</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-lg-2 col-xl-2 draggable-zone">
                <!--begin::Iconbox-->
                <div class="card card-custom wave gutter-b draggable wave-animate-slow wave-danger mb-8 mb-lg-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center p-5">
                            <div class="">
                                    <span class="svg-icon svg-icon-4x">
                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg-->
                                        <div class="symbol symbol-danger">
                                            <span class="symbol-label font-size-h5">
                                                <a href="{{route('payroll.index')}}" class="btn btn-icon btn-lg draggable-handle">
                                                    <i class="fa fa-award text-white"></i>
                                                </a>
                                            </span>
                                        </div>
                                        <!--end::Svg Icon-->
                                    </span>
                            </div>
                        </div>
                        <div class="d-flex flex-column text-center">
                            <a href="{{route('payroll.index')}}" class="text-dark font-weight-bold mb-3">Payroll</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-xl-2 draggable-zone">
                <!--begin::Iconbox-->
                <div class="card card-custom wave gutter-b draggable wave-animate-slow wave-success mb-8 mb-lg-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center p-5">
                            <div class="">
                                    <span class="svg-icon svg-icon-4x">
                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg-->
                                        <div class="symbol symbol-success">
                                            <span class="symbol-label font-size-h5">
                                                <a href="{{route('to.index')}}" class="btn btn-icon btn-lg draggable-handle">
                                                    <i class="fa fa-plane-departure text-white"></i>
                                                </a>
                                            </span>
                                        </div>
                                        <!--end::Svg Icon-->
                                    </span>
                            </div>
                        </div>
                        <div class="d-flex flex-column text-center">
                            <a href="{{route('to.index')}}"><a class="text-dark font-weight-bold mb-3">Travel Order</a></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-xl-2 draggable-zone">
                <!--begin::Iconbox-->
                <div class="card card-custom wave gutter-b draggable wave-animate-slow wave-info mb-8 mb-lg-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center p-5">
                            <div class="">
                                    <span class="svg-icon svg-icon-4x">
                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg-->
                                        <div class="symbol symbol-info">
                                            <span class="symbol-label font-size-h5">
                                                <a href="{{route('subsidies.index')}}" class="btn btn-icon btn-lg draggable-handle">
                                                    <i class="fa fa-hand-holding-usd text-white"></i>
                                                </a>
                                            </span>
                                        </div>
                                        <!--end::Svg Icon-->
                                    </span>
                            </div>
                        </div>
                        <div class="d-flex flex-column text-center">
                            <a href="{{route('subsidies.index')}}" class="text-dark font-weight-bold mb-3">Subsidies</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>