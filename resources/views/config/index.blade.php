@extends('config.layout')

@section('content')
{{--    {{$_SESSION['errors']}}--}}
{{$message}}
<div class="card card-custom">
    <div class="card-body p-0">
        <div class="row justify-content-center py-10 px-8 py-lg-12 px-lg-10">
            <div class="col-xl-12 col-xxl-12">
                <!--begin: Wizard Form-->
                <form class="form fv-plugins-bootstrap fv-plugins-framework" action="{{URL::route('install.submit')}}" id="kt_form" method="post" enctype="multipart/form-data">
                    @csrf
                    <!--begin: Wizard Step 1-->
                    <div class="pb-5" data-wizard-type="step-content" data-wizard-state="current">
                        <h2 class="mb-10 font-weight-bold text-dark">Welcome to Cypher</h2>
                        <h4 class="mb-10 font-weight-bold text-dark">We will walk you through the installation process.</h4>
                        <p>This installation wizard will provide the necessary data to complete your brand-new Cypher software.</p>
                        <p>By continuing this process, you are agree to our Terms and Condition.</p>
                        <div class="separator separator-dashed my-5"></div>
                        <?php
                        //isset($_GET['e'] ? $_GET['e'] : "")
                        if(!empty($message)) {
                            ?>
                            <div class="alert alert-custom alert-outline-danger fade show mb-5" role="alert">
                                <div class="alert-icon"><i class="flaticon-warning"></i></div>
                                <div class="alert-text">{{$message}}</div>
                                <div class="alert-close">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true"><i class="ki ki-close"></i></span>
                                    </button>
                                </div>
                            </div>
                            <?php
                        } ?>
                        <h4>Please fill out these data below.</h4>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="mb-10 font-weight-bold text-dark">General Information</h4>
                                <div class="form-group fv-plugins-icon-container">
                                    <label>Company Name</label>
                                    <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Company Name" required>
                                    <span class="form-text text-muted">Please enter your company name.</span>
                                    <div class="fv-plugins-message-container"></div>
                                </div>
                                <div class="form-group fv-plugins-icon-container">
                                    <label>Company Tag (3 characters)</label>
                                    <p>Your company tag is the identifier of your company, with 3 charactes only.</p>
                                    <input type="text" class="form-control" id="company_tag" name="company_tag" placeholder="Company Tag" required>
                                    <span class="form-text text-muted">This tag will be used as code for your mailing number, and other documents number.</span>
                                    <div class="fv-plugins-message-container"></div>
                                </div>
                                <div class="form-group fv-plugins-icon-container">
                                    <label>Database Root Username</label>
                                    <input type="text" class="form-control" id="root_username" name="root_username" placeholder="root" required>
                                    <span class="form-text text-muted">We need your root account to create necessary databases for Cypher.</span>
                                    <div class="fv-plugins-message-container"></div>
                                </div>
                                <div class="form-group fv-plugins-icon-container">
                                    <label>Database Root Password</label>
                                    <input type="text" class="form-control" id="root_password" name="root_password" placeholder="password here">
                                    <span class="form-text text-muted">This box will not be masked.</span>
                                    <div class="fv-plugins-message-container"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4 class="mb-10 font-weight-bold text-dark">Company Logo</h4>
                                <!--begin::Select-->
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Printed Logo</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <div class="image-input image-input-outline" id="printed_logo">
                                            <div class="image-input-wrapper"></div>
                                            <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change">
                                                <i class="fa fa-pen icon-sm text-muted"></i>
                                                <input type="file" name="p_logo" id="p_logo" accept=".png, .jpg, .jpeg" required/>
                                                <input type="hidden" name="p_logo_remove"  />
                                            </label>
                                            <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel">
                                                                                <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                                            </span>
                                        </div>
                                        <span class="form-text text-muted">This logo will be used when you print a document from Cypher. <br />
                                                                        Allowed file types: png, jpg, jpeg.</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Application Logo</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <div class="image-input image-input-outline" id="app_logo">
                                            <div class="image-input-wrapper"></div>
                                            <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change">
                                                <i class="fa fa-pen icon-sm text-muted"></i>
                                                <input type="file" name="ap_logo" id="ap_logo" accept=".png, .jpg, .jpeg" required/>
                                                <input type="hidden" name="ap_logo_remove" />
                                            </label>
                                            <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel">
                                                                                <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                                            </span>
                                        </div>
                                        <span class="form-text text-muted">This logo will be displayed in the Cypher application, we recommend using a square shaped logo. <br />
                                                                        Allowed file types: png, jpg, jpeg.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <h4 class="mb-10 font-weight-bold text-dark">Company Details</h4>
                                <div class="form-group col-md-12 fv-plugins-icon-container">
                                    <label>Address</label>
                                    <input type="text" class="form-control" id="address" name="address" placeholder="Address" required>
                                    <span class="form-text text-muted">Please enter your Address.</span>
                                    <div class="fv-plugins-message-container"></div>
                                </div>
                                <div class="form-group col-md-12 fv-plugins-icon-container">
                                    <label>NPWP</label>
                                    <input type="text" class="form-control" id="npwp" name="npwp" placeholder="NPWP" required>
                                    <span class="form-text text-muted">Please enter your NPWP.</span>
                                    <div class="fv-plugins-message-container"></div>
                                </div>
                                <div class="form-group row fv-plugins-icon-container">
                                    <div class="col-md-6">
                                        <label>Phone</label>
                                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone" required>
                                        <span class="form-text text-muted">Please enter your Phone.</span>
                                        <div class="fv-plugins-message-container"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Email</label>
                                        <input type="text" class="form-control" id="email" name="email" placeholder="Email" required>
                                        <span class="form-text text-muted">Please enter your Email.</span>
                                        <div class="fv-plugins-message-container"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex pb-5 col-md-12 justify-content-between border-top mt-5 pt-10">
                                <label class="col-md-8"></label>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-success font-weight-bold text-uppercase px-9 py-4" data-wizard-type="action-submit" id="submit" name="submit">Submit</button>
                                </div>
                            </div>
                 </div>
             </div>
         </form>
     </div>
 </div>

 {{--<form action="{{URL::route('install.submit')}}" method="post">--}}
    {{--    @csrf--}}
    {{--    <input type="text" name="name_company" placeholder="Company Name"> <br>--}}
    {{--    <input type="text" name="tag_company" placeholder="Company Tag"> <br>--}}
    {{--    <input type="text" name="name_database" placeholder="Database Name"> <br>--}}
    {{--    <input type="text" name="username" placeholder="Database Username"> <br>--}}
    {{--    <input type="text" name="password" placeholder="Database Password"> <br>--}}
    {{--    <button type="submit"> Submit</button>--}}
{{--</form>--}}
@endsection

@section('scripts')
<script>
    function readURL(input, div) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#'+div).attr('src', e.target.result);
                $('#'+div+"s").attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $(document).ready(function(){
        $("#p_logo").change(function() {
            readURL(this, 'blah');
        });
        $("#ap_logo").change(function() {
            readURL(this, 'blah2');
        });
    });
</script>
@endsection
