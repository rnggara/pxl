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
						<div class="d-flex flex-center mb-15">
							<a href="#">
                                @php
                                    $comp_id = 1;
                                    if(empty($comp)){
                                        $img = asset('theme/assets/media/logos/default.png');
                                    } else {
                                        $comp_id = $comp->id;
                                        $img = str_replace("public", "public_html", asset("images/".$comp->app_logo));
                                    }
                                @endphp
								<img src="{{ $img }}" class="max-w-150px" alt="" />
							</a>
						</div>
						<!--end::Login Header-->
						<!--begin::Login Sign in form-->
						<div class="login-signin">
							<div class="mb-20 text-center">
								<h2>Pendaftaran Driver</h2>
								<div class="text-muted font-weight-bold font-size-h4">Harap diisi dengan data yang sebenarnya</div>
							</div>

							<form method="post" action="{{route('driver.add')}}" enctype="multipart/form-data">
								@csrf
								<div class="row" name="full_name">
									<div class="col-md-4 col-sm-12">
										<div class="form-group mb-5">
											<label class="col-form-label font-weight-bold font-size-h2" teksalign="left" >Nama Depan <span class="text-danger">*</span></label>
											<input class="form-control h-auto form-control-solid" type="text" name="namadepan" required autocomplete="off" />
										</div>
									</div>
									<div class="col-md-4 col-sm-12">
										<div class="form-group mb-5">
											<label class="col-form-label font-weight-bold font-size-h2">Nama Tengah (Jika ada)</label>
											<input class="form-control h-auto form-control-solid" type="text" name="namatengah" autocomplete="off" />
										</div>
									</div>
									<div class="col-md-4 col-sm-12">
										<div class="form-group mb-5">
											<label class="col-form-label font-weight-bold font-size-h2">Nama Belakang (Jika ada)</label>
											<input class="form-control h-auto form-control-solid" type="text" name="namabelakang" autocomplete="off" />
										</div>
									</div>
								</div>
								<div class= "row">
									<div class="col-md-6 col-sm-12">
										<div class="form-group mb-5">
											<label class="col-form-label font-weight-bold font-size-h2">Nomor Plat Kendaraan <span class="text-danger">*</span></label>
											<input class="form-control h-auto form-control-solid" type="text" name="nopol_kendaraan" required />
											<label class="col-form-label"><font size="1">Contoh : B 8212 OFD</font></label>
										</div>
									</div>
									<div class="col-md-6 col-sm-12">
										<div class="form-group mb-5">
											<label class="col-form-label font-weight-bold font-size-h2">Jenis Kendaraan <span class="text-danger">*</span></label>
											<select class="form-control h-auto form-control-solid" align="right" type="text" placeholder="Please select" name="jenis_kendaraan">
												<option value="Online Transportation">Online Transportation</option>
                                                <option value="Personal Transportation">Personal Transportation</option>
                                                <option value="Truck Lowbed ">Truck Lowbed </option>
                                                <option value="Truck Trailer">Truck Trailer</option>
                                                <option value="Truck Tronton losbak">Truck Tronton losbak</option>
                                                <option value="Truck Fuso">Truck Fuso</option>
                                                <option value="Truck Colt Diesel ">Truck Colt Diesel </option>
                                                <option value="Truck Colt Diesel Dobel">Truck Colt Diesel Dobel</option>
                                                <option value="Pick Up">Pick Up</option>
                                                <option value="Engkel Box ">Engkel Box </option>
                                                <option value="Tronton">Tronton</option>
											</select>
										</div>
									</div>
								</div>
								<div class= "row">
									<div class="col-md-6 col-sm-12">
										<div class="form-group mb-5">
											<label class="col-form-label font-weight-bold font-size-h2">Perusahaan <span class="text-danger">*</span></label>
											<input class="form-control h-auto form-control-solid" type="text" name="perusahaan" required />
											<label class="col-form-label"><font size="1">(nama perusahaan anda bekerja)</font></label>
										</div>
									</div>
									<div class="col-md-6 col-sm-12">
										<div class="form-group mb-5">
											<label class="col-form-label font-weight-bold font-size-h2">Email</label>
											<input class="form-control h-auto form-control-solid" type="email" name="email" placeholder ="ex: myname@example.com"/>
											<label class="col-form-label"><font size="1">isi bila ada</font></label>
										</div>
									</div>
								</div>
								<div class= "row">
									<div class="col-md-6 col-sm-12">
										<div class="form-group mb-5">
											<label class="col-form-label font-weight-bold font-size-h2">Nomor Telepon <span class="text-danger">*</span></label>
											<input class="form-control h-auto form-control-solid" required type="text" onkeypress="return hanyaAngka(event)" maxlength="13" name="no_telpon" />
										</div>
									</div>
									<div class="col-md-6 col-sm-12">
										<div class="form-group mb-5">
											<label class="col-form-label font-weight-bold font-size-h2">Nomor WhatsApp <span class="text-danger">*</span></label>
											<input class="form-control h-auto form-control-solid" required type="text" onkeypress="return hanyaAngka(event)" maxlength="13" name="no_wa"/>
										</div>
									</div>
								</div>
								<div class= "row">
									<div class="col-md-6 col-sm-12">
										<div class="form-group mb-5">
											<label class="col-form-label font-weight-bold font-size-h2">Nomor KTP <span class="text-danger">*</span></label>
											<input class="form-control h-auto form-control-solid" required type="text" onkeypress="return hanyaAngka(event)" name="no_id" />
										</div>
									</div>
									<div class="col-md-6 col-sm-12">
										<div class="form-group mb-5">
											<label class="col-form-label font-weight-bold font-size-h2">Nomor SIM <span class="text-danger">*</span></label>
											<input class="form-control h-auto form-control-solid" type="text" onkeypress="return hanyaAngka(event)" name="no_sim"/>
										</div>
									</div>
								</div>
								<div class="form-group col-md-12 col-sm-12">
									<label class="col-form-label font-weight-bold font-size-h2">Unggah Foto KTP/SIM <span class="text-danger">*</span></label>
									<div></div>
									<div class="custom-file">
										<input type="file" class="custom-file-input" id="customFile" name="file_upload" accept="image/*;capture=camera" required/>
										<label class="custom-file-label" for="customFile">Pilih Berkas</label>
									</div>
								</div>
								<div>

                                    <input type="hidden" name="comp_id" value="{{ $comp_id }}">
									<button type="submit" name="submit" class="btn btn-primary font-weight-bold">
									<i class="fa fa-check"></i>
									Simpan</button>
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
            })
		</script>
	</body>
	<!--end::Body-->
</html>
