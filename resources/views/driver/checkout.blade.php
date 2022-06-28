<!DOCTYPE html>

<html lang="en">

	<head><base href="../../../../">
		<meta charset="utf-8" />
		<title>Cypher | Driver Registration</title>
		<meta name="description" content="Login page example" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<!--begin::Fonts-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
		<!--end::Fonts-->

		<link href="{{ asset('assets/css/pages/login/classic/login-4.css?v=7.0.5')}}" rel="stylesheet" type="text/css" />
		<!--end::Page Custom Styles-->
		<!--begin::Global Theme Styles(used by all pages)-->
		<link href="{{ asset('assets/plugins/global/plugins.bundle.css?v=7.0.5') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('assets/plugins/custom/prismjs/prismjs.bundle.css?v=7.0.5') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('assets/css/style.bundle.css?v=7.0.5') }}" rel="stylesheet" type="text/css" />
		<!--end::Global Theme Styles-->
		<!--begin::Layout Themes(used by all pages)-->
		<!--end::Layout Themes-->
		<link rel="shortcut icon" href="{{($accounting_mode == 1) ? asset('/assets/images/icon_1.png') : asset('/assets/images/icon.png')}}" />
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled page-loading">
		<!--begin::Main-->
		<div class="d-flex flex-column flex-root">
			<!--begin::Login-->
			<div class="login login-4 login-signin-on d-flex flex-row-fluid" id="kt_login">
				<div class="d-flex flex-center flex-row-fluid bgi-size-cover bgi-position-top bgi-no-repeat" style="background-image: url('{{ asset('assets/media/bg/bg-3.jpg') }}');">
					<div class="login-form text-left p-7 position-relative overflow-hidden" style="max-width: 100%">
						<!--begin::Login Header-->
						{{-- <div class="d-flex flex-center mb-15">
							<a href="#">
								<img src="{{ asset('theme/assets/media/logos/default.png') }}" class="max-h-30px" alt="" />
							</a>
						</div> --}}
						<!--end::Login Header-->
						<!--begin::Login Sign in form-->
						<div class="login-signin">
                            <div id="log"></div>
							<div class="mb-10 text-center">
								<h2>Checkout Delivery Order {{ $do->no_do ?? "N/A" }}</h2>
							</div>
                            <hr>

							<form method="post" action="{{route('driver.checkout_post')}}" >
								@csrf
								<div class="row" name="full_name">
                                    <div class="col-sm-12">
                                        <h2>Informasi Pengiriman</h2>
                                    </div>
									<div class="col-md-4 col-sm-12">
										<div class="form-group mb-5">
											<label class="col-form-label font-weight-bold font-size-h3" teksalign="left" >Dari</label>
											<input class="form-control h-auto form-control-solid" value="{{ $do->whFrom ?? "N/A" }}" readonly type="text" autocomplete="off" />
										</div>
									</div>
									<div class="col-md-4 col-sm-12">
										<div class="form-group mb-5">
											<label class="col-form-label font-weight-bold font-size-h3">Tujuan</label>
											<input class="form-control h-auto form-control-solid" value="{{ $do->whTo ?? "N/A" }}" readonly type="text" autocomplete="off" />
										</div>
									</div>
									<div class="col-md-4 col-sm-12">
										<div class="form-group mb-5">
											<label class="col-form-label font-weight-bold font-size-h3">Tanggal Pengiriman</label>
											<input class="form-control h-auto form-control-solid" value="{{ date("d F Y", strtotime($do->deliver_date)) ?? "N/A" }}" readonly type="text" autocomplete="off" />
										</div>
									</div>
                                    <div class="col-md-6 col-sm-12">
										<div class="form-group mb-5">
											<label class="col-form-label font-weight-bold font-size-h3">Notes</label>
											<textarea name="" class="form-control form-control-solid" readonly id="" cols="30" rows="10">{!! $do->notes ?? "N/A" !!}</textarea>
										</div>
									</div>
								</div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h2>Informasi Barang</h2>
                                    </div>
                                    <div class="col-sm-12">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center">Item Name</th>
                                                    <th class="text-center">Quantity</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($detail as $i => $item)
                                                    <tr>
                                                        <td align="center">{{ $i+1 }}</td>
                                                        <td>{{ $item->itemName }}</td>
                                                        <td align="center">{{ $item->qty }}{{ (!empty($item->itemUom) ? " ".$item->itemUom : " ea") }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <hr>
								<div class="row">
									<div class="col-sm-12">
                                        <input type="hidden" name="id_driver" value="{{ $do->driver_id }}">
                                        <input type="hidden" name="id_do" value="{{ $do->id }}">
                                        <input type="hidden" name="os_name" id="os-name">
                                        <input type="hidden" name="browser_name" id="browser-name">
                                        <input type="hidden" name="ip_address" id="ip-address">
                                        <button type="submit" name="submit" onclick="return confirm('Chekout?')" class="btn btn-block btn-primary font-weight-bold">
                                            <i class="fa fa-check"></i>
                                            Checkout</button>
                                    </div>
								</div>
							</form>

						</div>
						<!--end::Login Sign in form-->
						<!--begin::Login Sign up form-->
						<!--end::Login Sign up form-->
						<!--begin::Login forgot password form-->
						<!--end::Login forgot password form-->
					</div>
				</div>
			</div>
			<!--end::Login-->
		</div>
		<!--end::Main-->
		<script>var HOST_URL = "https://keenthemes.com/metronic/tools/preview";</script>
		<!--begin::Global Config(global config for global JS scripts)-->
		<script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1200 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#6993FF", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#F3F6F9", "dark": "#212121" }, "light": { "white": "#ffffff", "primary": "#E1E9FF", "secondary": "#ECF0F3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#212121", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#ECF0F3", "gray-300": "#E5EAEE", "gray-400": "#D6D6E0", "gray-500": "#B5B5C3", "gray-600": "#80808F", "gray-700": "#464E5F", "gray-800": "#1B283F", "gray-900": "#212121" } }, "font-family": "Poppins" };</script>
		<!--end::Global Config-->
		<!--begin::Global Theme Bundle(used by all pages)-->
		<script src="{{ asset('theme/assets/plugins/global/plugins.bundle.js?v=7.0.5') }}"></script>
		<script src="{{ asset('theme/assets/plugins/custom/prismjs/prismjs.bundle.js?v=7.0.5') }}"></script>
		<script src="{{ asset('theme/assets/js/scripts.bundle.js?v=7.0.5') }}"></script>
		<!--end::Global Theme Bundle-->
		<!--begin::Page Scripts(used by this page)-->
		<script src="{{ asset('theme/assets/js/pages/custom/login/login-general.js?v=7.0.5') }}"></script>
		<!--end::Page Scripts-->
        <script src="{{asset('theme/assets/js/pages/features/miscellaneous/sweetalert2.js?v=7.0.5')}}"></script>
        <script>
            (function () {
                'use strict';

                var module = {
                    options: [],
                    header: [navigator.platform, navigator.userAgent, navigator.appVersion, navigator.vendor, window.opera],
                    dataos: [
                        { name: 'Windows Phone', value: 'Windows Phone', version: 'OS' },
                        { name: 'Windows', value: 'Win', version: 'NT' },
                        { name: 'iPhone', value: 'iPhone', version: 'OS' },
                        { name: 'iPad', value: 'iPad', version: 'OS' },
                        { name: 'Kindle', value: 'Silk', version: 'Silk' },
                        { name: 'Android', value: 'Android', version: 'Android' },
                        { name: 'PlayBook', value: 'PlayBook', version: 'OS' },
                        { name: 'BlackBerry', value: 'BlackBerry', version: '/' },
                        { name: 'Macintosh', value: 'Mac', version: 'OS X' },
                        { name: 'Linux', value: 'Linux', version: 'rv' },
                        { name: 'Palm', value: 'Palm', version: 'PalmOS' }
                    ],
                    databrowser: [
                        { name: 'Chrome', value: 'Chrome', version: 'Chrome' },
                        { name: 'Firefox', value: 'Firefox', version: 'Firefox' },
                        { name: 'Safari', value: 'Safari', version: 'Version' },
                        { name: 'Internet Explorer', value: 'MSIE', version: 'MSIE' },
                        { name: 'Opera', value: 'Opera', version: 'Opera' },
                        { name: 'BlackBerry', value: 'CLDC', version: 'CLDC' },
                        { name: 'Mozilla', value: 'Mozilla', version: 'Mozilla' }
                    ],
                    init: function () {
                        var agent = this.header.join(' '),
                            os = this.matchItem(agent, this.dataos),
                            browser = this.matchItem(agent, this.databrowser);

                        return { os: os, browser: browser };
                    },
                    matchItem: function (string, data) {
                        var i = 0,
                            j = 0,
                            html = '',
                            regex,
                            regexv,
                            match,
                            matches,
                            version;

                        for (i = 0; i < data.length; i += 1) {
                            regex = new RegExp(data[i].value, 'i');
                            match = regex.test(string);
                            if (match) {
                                regexv = new RegExp(data[i].version + '[- /:;]([\\d._]+)', 'i');
                                matches = string.match(regexv);
                                version = '';
                                if (matches) { if (matches[1]) { matches = matches[1]; } }
                                if (matches) {
                                    matches = matches.split(/[._]+/);
                                    for (j = 0; j < matches.length; j += 1) {
                                        if (j === 0) {
                                            version += matches[j] + '.';
                                        } else {
                                            version += matches[j];
                                        }
                                    }
                                } else {
                                    version = '0';
                                }
                                return {
                                    name: data[i].name,
                                    version: parseFloat(version)
                                };
                            }
                        }
                        return { name: 'unknown', version: 0 };
                    }
                };

                var e = module.init(),
                    debug = '';
                $("#os-name").val(e.os.name)
                $("#browser-name").val(e.browser.name)
            }());


			function hanyaAngka(evt) {
				var charCode = (evt.which) ? evt.which : event.keyCode
				if (charCode > 31 && (charCode < 48 || charCode > 57))

				return false;
				return true;
			}

            $(document).ready(function(){
                @if(\Session::get('success'))
                    Swal.fire('Success', "{{ \Session::get('success') }}", 'success')
                @endif

                $.getJSON("http://jsonip.com/?callback=?", function (data) {
                    $("#ip-address").val(data.ip)
                });
            })
		</script>
	</body>
	<!--end::Body-->
</html>
