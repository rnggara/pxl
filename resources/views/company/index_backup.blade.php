@extends('layouts.template')
@section('content')
    <!--begin::Subheader-->
    <div class="subheader py-2 py-lg-4 subheader-transparent" id="kt_subheader">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex align-items-center flex-wrap mr-1">
                <!--begin::Mobile Toggle-->
                <button class="burger-icon burger-icon-left mr-4 d-inline-block d-lg-none" id="kt_subheader_mobile_toggle">
                    <span></span>
                </button>
                <!--end::Mobile Toggle-->
                <!--begin::Page Heading-->
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <!--begin::Page Title-->
                    <h5 class="text-dark font-weight-bold my-1 mr-5"><?= " Corporate"; ?></h5>
                    <!--end::Page Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                        <li class="breadcrumb-item">
                            <a href="" class="text-muted"> Management</a>
                        </li>
                    </ul>
                    <!--end::Breadcrumb-->
                </div>
                <!--end::Page Heading-->
            </div>
            <!--end::Info-->
        </div>
    </div>
    <!--end::Subheader-->
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCompany">New</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <table class="table table-bordered table-checkable" id="dt" style="width:100%">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Company Name</th>
                        <th>Parent</th>
                        <th>Tag</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Npwp</th>
                        <th>#</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($company as $key => $value)
                            <tr>
                                <td>{{$key + 1}}</td>
                                <td><a href="{{URL::route('company.detail', ['id' => base64_encode($value->id)])}}"> {{$value->company_name}}</a></td>
                                <td>
                                    @if(empty($value->id_parent))
                                        {{"-"}}
                                    @else
                                        @foreach($company as $vv)
                                            @if($value->id_parent == $vv->id)
                                                {{$vv->company_name}}
                                            @endif
                                        @endforeach
                                    @endif
                                </td>
                                <td>{{strtoupper($value->tag)}}</td>
                                <td>{{$value->address}}</td>
                                <td>{{$value->phone}}</td>
                                <td>{{$value->email}}</td>
                                <td>{{$value->npwp}}</td>
                                <td>
                                    @if($value->id > 1)
                                        <input type="hidden" id="coid{{$key}}" value="{{$value->id}}" title="Edit">
                                        <button type="button" id="btnDel{{$key}}" class="btn btn-xs btn-icon btn-danger" title="Delete"><i class="fa fa-trash"></i></button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addCompany" tabindex="-1" role="dialog" aria-labelledby="addCompany" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Company</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{URL::route('company.add')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="mb-10 font-weight-bold text-dark">General Information</h4>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Company Name</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="company_name">
                                        <span class="form-text text-muted">Please enter your company name.</span>
                                        <div class="fv-plugins-message-container"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Company Tag</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="company_tag">
                                        <span class="form-text text-muted">This tag will be used as code for your mailing number, and other documents number.</span>
                                        <div class="fv-plugins-message-container"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Company Parent</label>
                                    <div class="col-md-6">
                                        <select name="parent" class="form-control">
                                            @foreach($company as $value)
                                                <option value="{{$value->id}}">{{$value->company_name}}</option>
                                            @endforeach
                                        </select>
                                        <span class="form-text text-muted">Please select parent company.</span>
                                        <div class="fv-plugins-message-container"></div>
                                    </div>
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
                                                <input type="file" name="p_logo" id="p_logo" accept=".png, .jpg, .jpeg" />
                                                <input type="hidden" name="p_logo_remove" />
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
                                                <input type="file" name="app_logo" id="app_logo" accept=".png, .jpg, .jpeg" />
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
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Background Color</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="color" class="form-control" name="bgcolor">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <h4 class="mb-10 font-weight-bold text-dark">Company Details</h4>
                                <div class="form-group col-md-12 fv-plugins-icon-container">
                                    <label>Address</label>
                                    <input type="text" class="form-control" id="address" name="address" placeholder="Address">
                                    <span class="form-text text-muted">Please enter your Address.</span>
                                    <div class="fv-plugins-message-container"></div>
                                </div>
                                <div class="form-group col-md-12 fv-plugins-icon-container">
                                    <label>NPWP</label>
                                    <input type="text" class="form-control" id="npwp" name="npwp" placeholder="NPWP">
                                    <span class="form-text text-muted">Please enter your NPWP.</span>
                                    <div class="fv-plugins-message-container"></div>
                                </div>
                                <div class="form-group row fv-plugins-icon-container">
                                    <div class="col-md-6">
                                        <label>Phone</label>
                                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone">
                                        <span class="form-text text-muted">Please enter your Phone.</span>
                                        <div class="fv-plugins-message-container"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Email</label>
                                        <input type="text" class="form-control" id="email" name="email" placeholder="Email">
                                        <span class="form-text text-muted">Please enter your Email.</span>
                                        <div class="fv-plugins-message-container"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" name="add" class="btn btn-primary font-weight-bold">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        $(document).ready(function () {
            $('#dt').DataTable({
                responsive: true,
                "searching": false
            });


                @if(count($company) > 0)
            for (let i = 0; i < {{count($company)}}; i++) {
                $("#btnDel" + i).click(function(){
                    console.log(i)
                    Swal.fire({
                        title: "Delete",
                        text: "Delete this company?",
                        icon: "error",
                        showCancelButton: true,
                        confirmButtonText: "Delete",
                        cancelButtonText: "Cancel",
                        reverseButtons: true,
                    }).then(function(result){
                        if(result.value){
                            var id = $("#coid"+i).val()
                            $.ajax({
                                url: '{{URL::route('company.delete')}}',
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
        });
    </script>
@endsection
