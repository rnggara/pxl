@extends('layouts.template')
@section('content')
    <!--begin::Subheader-->
    <!--end::Subheader-->
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                Employee
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addEmployee"><i class="fa fa-plus"></i>New Record</button>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th nowrap="nowrap" style="width: 30%">Name</th>
                        <th nowrap="nowrap" class="text-center">ID</th>
                        <th nowrap="nowrap" class="text-center">Level</th>
{{--                        <th nowrap="nowrap" class="text-center">Position</th>--}}
                        <th nowrap="nowrap" class="text-center">Contract Status</th>
                        <th nowrap="nowrap" class="text-center">CV</th>
                        <th nowrap="nowrap" class="text-center">Document</th>
                        <th nowrap="nowrap" class="text-center">Quit</th>
                        <th nowrap="nowrap">Mandatory Training point</th>
                        <th nowrap="nowrap" data-priority=1>#</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $i = 0 @endphp
                    @foreach($employees as $key => $value)
                        @if($value->company_id == \Session::get('company_id'))
                            @php $i++ @endphp
                        <tr>
                            <td>{{$i}}</td>
                            <td nowrap="nowrap"><a href="{{route('employee.detail',['id'=>$value->id])}}" class="btn btn-primary btn-sm">
                                    {{$value->emp_name}}</a></td>
                            <td nowrap="nowrap">{{$value->emp_id}}</td>
                            <td nowrap="nowrap" class="text-center">{{$value->emp_position}}</td>
{{--                            <td nowrap="nowrap">{{$value->emp_type}}</td>--}}
                            <td nowrap="nowrap">
                                @if(!is_file(public_path('hrd\\uploads\\').$value->contract_file))
                                    <center>
                                        <button type="button" data-target="#modal-{{$value->id}}" data-toggle="modal" class="btn btn-sm btn-success">
                                            <i class="fa fa-plus icon-nm"></i> [add contract]
                                        </button>
                                    </center>
                                @else
                                    <center>
                                        <label class="text-danger font-weight-bolder">exp: {{$value->expire}}</label>
                                        <button type="button" class="btn btn-xs btn-icon btn-outline-success" data-target="#modal-download-{{$value->id}}" data-toggle="modal"><i class="fa fa-download"></i></button>
                                    </center>
                                    <br>
                                    <div class="form-group">
                                        <button type="button" class="btn btn-sm btn-light-success" data-target="#modal-{{$value->id}}" data-toggle="modal"><i class="fa fa-history icon-nm"></i> Renew Contract</button>
                                    </div>
                                @endif
                            </td>
                            <td class="text-center" nowrap="nowrap">
                                <a href="{{route('employee.detail',['id'=>$value->id])}}" class="btn btn-success btn-sm"><i class="fa fa-cog icon-nm"></i> manage</a>
                            </td>
                            <td class="text-center" nowrap="nowrap">
                                <a href="{{route('employee.detail',['id'=>$value->id])}}" class="btn btn-success btn-sm"><i class="fa fa-cog icon-nm"></i> manage</a>
                            </td>
                            <td nowrap="nowrap">
                                <a href="{{route('employee.expel',['id' =>$value->id])}}" class="btn btn-sm btn-danger" onclick="return confirm('Pegawai ini DIPECAT?'); "><i class="fa fa-times icon-nm"></i> Fired</a>
                            </td>
                            <td class="text-center">
                                <a href="" class="btn btn-light-dark btn-icon btn-sm"><i class="fa fa-eye text-white icon-nm"></i></a>
                            </td>
                            <td nowrap="nowrap">
                                <form method="post" action="{{route('employee.delete',['id'=>$value->id])}}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-icon btn-default" onclick="return confirm('Hapus data pegawai?');">
                                        <i class="fa fa-trash text-danger"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addEmployee" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Employee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{URL::route('employee.add')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <br>
                        <h4>Setup Profile</h4><hr>
                        <div class="row">
                            <div class="form col-md-6">
                                <div class="form-group">
                                    <label>Full Name</label>
                                    <input type="text" class="form-control" name="full_name" placeholder="Full Name" required />
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" class="form-control" name="email" placeholder="Email" required />
                                </div>
                                <div class="form-group">
                                    <label>Employee Status</label>
                                    <select class="form-control" id="emp_status" name="emp_status" required>
                                        <option value="">- Select Employee Status -</option>
                                        <option value="kontrak">Contract</option>
                                        <option value="konsultan">Consultant</option>
                                        <option value="tetap">Permanent</option>
                                        <option value="probation">Probation</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Address</label>
                                    <textarea class="form-control" name="address" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Religion</label>
                                    <select class="form-control" id="kt_select2_religion" name="religion" required>
                                        <option value="">- Select Religion -</option>
                                        <option value="islam">Islam</option>
                                        <option value="kristen_protestan">Kristen Protestan</option>
                                        <option value="kristen_katholik">Kristen Katholik</option>
                                        <option value="hindu">Hindu</option>
                                        <option value="buddha">Buddha</option>
                                        <option value="konghuchu">Kong Hu Chu</option>
                                        <option value="lain">Lain-lain</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Phone 1</label>
                                    <input type="text" class="form-control" name="phone_1" placeholder="Phone 1" required/>
                                </div>
                                <div class="form-group">
                                    <label>Phone 2</label>
                                    <input type="text" class="form-control" name="phone_2" placeholder="Phone 2" />
                                </div>

                            </div>
                            <div class="form col-md-6">
                                <div class="form-group">
                                    <label>Phone Home</label>
                                    <input type="text" class="form-control" name="phonehome_" placeholder="Phone Home" required/>
                                </div>
                                <div class="form-group">
                                            <label>Date Birth</label>
                                    <input type="date" class="form-control" name="date_birth" placeholder="Date Birth" required />
                                </div>
                                <div class="form-group">
                                    <label>Employee ID (NIK)</label>
                                    <input type="text" class="form-control" name="emp_id" id="emp_id" placeholder="Employee ID (NIK)" readonly=""/>
                                </div>
                                <div class="form-group">
                                    <label>Employee Type</label>
                                    <select class="form-control" id="emp_type" name="emp_type" required>
                                        <option value="">- Select Employee Type -</option>
                                        @foreach($emptypes as $key => $val)
                                            <option value="{{$val->id}}">{{$val->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Position</label>
                                    <input type="text" class="form-control" id="position" name="position" placeholder="Position" readonly/>
                                </div>
                                <div class="form-group">
                                    <label>Bank</label>
                                    <select class="form-control" id="bankCode" name="bankCode" required>
                                        <option value=""></option>
                                        <option value='002'>BRI</option>
                                        <option value='008'>Mandiri</option>
                                        <option value='009'>BNI</option>
                                        <option value="014">BCA</option>
                                        <option value="120">SUMSEL</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Account Number</label>
                                    <input type="text" class="form-control" name="account" placeholder="Account Number" required/>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <br>
                                <h4>Detail Information</h4><hr>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Profile photo</label>
                                            <img src="" id="prev_eq1" alt="Photo" class="img-thumbnail img-responsive center-block" >
                                            <input type="hidden" name="MAX_FILE_SIZE" value="200000" />
                                            <input type="file" class="form-control" name="picture" id="picture1" multiple accept='image/*' placeholder="" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Identity Card</label>
                                            <img src="" id="prev_eq2" alt="Photo" class="img-thumbnail img-responsive center-block" >
                                            <input type="hidden" name="MAX_FILE_SIZE" value="200000" />
                                            <input type="file" class="form-control" name="ktp" id="picture2" multiple accept='image/*' placeholder="" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Certificate</label>
                                            <img src="" id="prev_eq3" alt="Photo" class="img-thumbnail img-responsive center-block" >
                                            <input type="hidden" name="MAX_FILE_SIZE" value="200000" />
                                            <input type="file" class="form-control" name="serti1" id="picture3" multiple accept='image/*' placeholder="" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <br>
                                <h4>Salary</h4><hr>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Take Home Pay</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" name="thp" id="thp" placeholder="" required>
                                        <div id="breakdown"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Position Allowance</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" name="pa" id="pa" placeholder="" value="0">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Health Insurance</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" name="hi" id="hi" placeholder="" value="0">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Jamsostek</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" name="jam" id="jam" placeholder="" value="0">
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Pension</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" name="pensi" id="pensi" placeholder="" value="0">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Perfomance Bonus</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" name="yb" id="yb" placeholder="" value="0">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Over Time</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" name="overtime" id="overtime" placeholder="" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script>
        $(document).ready(function () {

            $("table.display").DataTable({
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                },
                pageLength: 100
            })

            $('#emp_type').change(function () {
                $('#position').val($( "#emp_type option:selected" ).text());
            });
            function myNewFunction(sel) {
                $('#position').val(sel.options[sel.selectedIndex].text)
            }
            $("#emp_status").change(function(){
                var status = $("#emp_status").val();
                // console.log(status);
                $.ajax({
                    url: "{{ route('employee.nik') }}",
                    type: 'GET',
                    data: {
                        emp_status: status,
                    },
                    success: function(response){
                        var res = JSON.parse(response);
                        $("#emp_id").val(res.data);
                    }
                });
            });
            $('#thp').bind('keypress keyup', function() {
                var nilai = $(this).val();
                $.ajax({
                    url: "{{ route('employee.thp') }}",
                    type: 'GET',
                    data: {
                        thp: nilai,
                    },
                    success: function(response){
                        var res = JSON.parse(response);

                        $("#breakdown").html(res.data);
                    }
                });
            });
            $('#emp_type').change(function () {
                var typer = $("#emp_type").val();
                var a = ''
                switch (typer){
                    case "president_director":
                        a="President Director"
                        break;
                    case "president_commisioner":
                        a="President Commisioner";
                        break;
                    case "director":
                        a="Director"
                        break;
                    case "commisioner":
                        a="Commisioner"
                        break;
                    case "corp_secretary":
                        a="Corporate Secretary"
                        break;
                    case "fm_partner":
                        a="Founder & Managing Partner";
                        break;
                    case "senior_partner":
                        a="Senior Partner"
                        break;
                    case "partner":
                        a="Partner"
                        break;
                    case "associate":
                        a="Associate"
                        break;
                     case "senior_associate":
                        a="Senior Associate"
                        break;
                     case "junior_associate":
                        a="Junior Associate"
                        break;
                    case "supporting_staff":
                        a="Supporting Staff"
                        break;
                }
                $('#position').val(a)
            })
            $("#fld_bonus").change(function(){
                var nilaiODO = $("#fld_bonus").val();
                var rateODO  = $("#odo_rate").val();
                var rateWH  = 0.33;

                var odo_bonus_calc  = nilaiODO * rateODO;
                var wh_bonus_calc  = nilaiODO * rateWH;

                $("#odo_bonus").val(odo_bonus_calc);
                $("#wh_bonus").val(wh_bonus_calc);
            });
            $("#emp_type").change(function(){
                var typer = $("#emp_type").val();
                var a     = [];
                switch(typer){
                    case "whbin":
                    case "whcil":
                    case "staff":
                        a[0]  = 40000; // meal
                        a[1]  = 75000; // spending
                        a[2]  = 250000; // overnight
                        a[3]  = 10; // ovs_meal
                        a[4]  = 10; // ovs_spending
                        a[5]  = 50; // ovs_overnight
                        a[6]  = 200000; // dom_airport
                        a[7]  = 200000; // dom_bus
                        a[8]  = 200000; // dom_train
                        a[9]  = 100000; // dom_cileungsi
                        a[10] = 15; // ovs_airport
                        a[11] = 15; // ovs_bus
                        a[12] = 15; // ovs_train
                        a[13] = 10; // ovs_cileungsi
                        break;
                    case "manager":
                        a[0]  = 55000; // meal
                        a[1]  = 100000; // spending
                        a[2]  = 400000; // overnight
                        a[3]  = 15; // ovs_meal
                        a[4]  = 15; // ovs_spending
                        a[5]  = 100; // ovs_overnight
                        a[6]  = 200000; // dom_airport
                        a[7]  = 200000; // dom_bus
                        a[8]  = 200000; // dom_train
                        a[9]  = 100000; // dom_cileungsi
                        a[10] = 15; // ovs_airport
                        a[11] = 15; // ovs_bus
                        a[12] = 15; // ovs_train
                        a[13] = 10; // ovs_cileungsi
                        break;
                    case "bod":
                        a[0]  = 75000; // meal
                        a[1]  = 150000; // spending
                        a[2]  = 500000; // overnight
                        a[3]  = 20; // ovs_meal
                        a[4]  = 20; // ovs_spending
                        a[5]  = 130; // ovs_overnight
                        a[6]  = 200000; // dom_airport
                        a[7]  = 200000; // dom_bus
                        a[8]  = 200000; // dom_train
                        a[9]  = 100000; // dom_cileungsi
                        a[10] = 15; // ovs_airport
                        a[11] = 15; // ovs_bus
                        a[12] = 15; // ovs_train
                        a[13] = 10; // ovs_cileungsi
                        break;
                    case "field":
                    case "konsultan":
                        a[0]  = 0; // meal
                        a[1]  = 0; // spending
                        a[2]  = 0; // overnight
                        a[3]  = 0; // ovs_meal
                        a[4]  = 0; // ovs_spending
                        a[5]  = 0; // ovs_overnight
                        a[6]  = 0; // dom_airport
                        a[7]  = 0; // dom_bus
                        a[8]  = 0; // dom_train
                        a[9]  = 0; // dom_cileungsi
                        a[10] = 0; // ovs_airport
                        a[11] = 0; // ovs_bus
                        a[12] = 0; // ovs_train
                        a[13] = 0; // ovs_cileungsi
                        break;
                }
                $("#dom_meal").val(a[0]); $("#dom_spending").val(a[1]); $("#dom_overnight").val(a[2]);
                $("#ovs_meal").val(a[3]); $("#ovs_spending").val(a[4]); $("#ovs_overnight").val(a[5]);
                $("#dom_transport_airport").val(a[6]); $("#dom_transport_bus").val(a[7]); $("#dom_transport_train").val(a[8]);  $("#dom_transport_cil").val(a[9]);
                $("#ovs_transport_airport").val(a[10]); $("#ovs_transport_bus").val(a[11]); $("#ovs_transport_train").val(a[12]); $("#ovs_transport_cil").val(a[13]);
            });
            $("#prev_eq1").hide();
            $("#prev_eq2").hide();
            $("#prev_eq3").hide();

            $("#picture1").change(function(){
                console.log($(this).val());
                if ($(this).val()) {
                    readURL(this, 1);
                    $("#prev_eq1").show();
                } else {
                    $("#prev_eq1").hide();
                }
            });

            $("#picture2").change(function(){
                if ($(this).val()) {
                    readURL(this, 2);
                    $("#prev_eq2").show();
                } else {
                    $("#prev_eq2").hide();
                }
            });

            $("#picture3").change(function(){
                if ($(this).val()) {
                    readURL(this, 3);
                    $("#prev_eq3").show();
                } else {
                    $("#prev_eq3").hide();
                }
            });
            function readURL(input, sec) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#prev_eq' + sec).attr('src', e.target.result);
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            }
        });
    </script>
@endsection
