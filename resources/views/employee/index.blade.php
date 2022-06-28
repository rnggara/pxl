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
                    <a href="{{ route('employee.activity.index') }}" class="btn btn-success mr-5">Employee Activity</a>
                    @actionStart('employee', 'create')
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addEmployee"><i class="fa fa-plus"></i>New Record</button>
                    @actionEnd
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <button type="button" onclick="getData(0)" class="btn btn-light-info font-weight-bold type btn-type" id="type0" name="type" style="width: 190px" value="0">
                All
            </button>
            <button type="button" onclick="getData(-1)" class="btn btn-light-info font-weight-bold type btn-type" id="typebank" name="type" style="width: 190px" value="-1">
                Expeled
            </button>
            <br><br>
            <div class="row">
            @foreach($emptypes as $key => $value)
                <div class="col-md-1 col-sm-12">
                    <button type="button" onclick="getData({{$value->id}})" class="btn btn-block btn-light-info font-weight-bold type btn-type" id="type{{$value->id}}" name="type" value="{{$value->id}}">
                        {{$value->name}}
                    </button>
                </div>
            @endforeach
            </div>

        </div>
        <div class="card-body">
            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <table class="table table-bordered table-hover display font-size-sm data_emp" style="margin-top: 13px !important; width: 100%;">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th nowrap="nowrap" style="width: 25%">Name</th>
                        <th nowrap="nowrap" class="text-center">Type</th>
                        <th nowrap="nowrap" class="text-center">ID</th>
                        <th nowrap="nowrap" class="text-center">Level</th>
                        <th nowrap="nowrap" class="text-center">Division</th>
                        @actionStart('employee', 'update')
                        <th nowrap="nowrap" class="text-center">Contract Status</th>
                        <th nowrap="nowrap" class="text-center">CV</th>
                        <th nowrap="nowrap" class="text-center">Document</th>
                        @actionEnd
                        @actionStart('employee', 'approvedir')
                        <th nowrap="nowrap" class="text-center">Quit</th>
                        @actionEnd
                        <th nowrap="nowrap" class="text-center">Mandatory Training point</th>
                        @actionStart('employee', 'delete')
                        <th nowrap="nowrap" class="text-center">#</th>
                        @actionEnd
                    </tr>
                    </thead>
                    <tbody></tbody>
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
                                    <label>Division</label>
                                    <select class="form-control" id="division" name="division" required>
                                        <option value="">- Select Division -</option>
                                        @foreach($divisions as $key => $val)
                                            <option value="{{$val->id}}">{{$val->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Bank</label>
                                    <select class="form-control select2" id="bankCode" name="bankCode" data-placeholder="Select Bank" required>
                                        <option value=""></option>
                                        @foreach ($master_banks as $kode_bank => $nama_bank)
                                            <option value="{{ $kode_bank }}">[{{ $kode_bank }}] {{ $nama_bank }}</option>
                                        @endforeach
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
                                            <label>Profile photo <span class="text-danger">* Image File</span></label>
                                            <img src="" id="prev_eq1" alt="Photo" class="img-thumbnail img-responsive center-block" >
                                            <input type="file" class="form-control" name="picture" id="picture1" accept='image/*' placeholder="" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Identity Card <span class="text-danger">* Image File</span></label>
                                            <img src="" id="prev_eq2" alt="Photo" class="img-thumbnail img-responsive center-block" >
                                            <input type="file" class="form-control" name="ktp" id="picture2" accept='image/*' placeholder="" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Certificate <span class="text-danger">* Image File</span></label>
                                            <img src="" id="prev_eq3" alt="Photo" class="img-thumbnail img-responsive center-block" >
                                            <input type="file" class="form-control" name="serti1" id="picture3" accept='image/*' placeholder="" required>
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
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Voucher</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" name="voucher" id="voucher" placeholder="" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <br>
                                <h4>Field, Warehouse, ODO Rate</h4>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="" class="col-form-label">Field</label>
                                            <input type="number" class="form-control" min="0" name="fld_bonus" value="0">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="" class="col-form-label">Warehouse</label>
                                            <input type="number" class="form-control" min="0" name="wh_bonus" value="0">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="" class="col-form-label">ODO</label>
                                            <input type="number" class="form-control" min="0" name="odo_bonus" value="0">
                                        </div>
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

    <div class="modal fade" id="modalGenerate" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content" id="modal-content-fld">

            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script src="{{ asset("assets/jquery-number/jquery.number.js") }}"></script>
    <script src="{{asset('theme/assets/js/signature_pad.js')}}"></script>
    <script>

        function _contract(x){
            $("#modal-content-fld").html("")
            $.ajax({
                url : "{{ route('hrd.contract.indexPost') }}",
                type : "post",
                data : {
                    _token : "{{ csrf_token() }}",
                    id : x,
                    type : "modal"
                },
                success : function(response){
                    $("#modal-content-fld").html(response)
                    $(".number").number(true, 2)
                    $("select.select2").select2({
                        width : "100%"
                    })

                    $("#btn-generate").prop("disabled", true)

                    $("#emp-name").change(function(){
                        $.ajax({
                            url : "{{ route('hrd.contract.indexPost') }}",
                            type : "post",
                            dataType : "json",
                            data : {
                                _token : "{{ csrf_token() }}",
                                id : $(this).val(),
                                type : "emp"
                            },
                            success : function(data){
                                $("#emp-name").prop('disabled', true)
                                var nik = $("#modal-content-fld").find("input[name=nik]")
                                var address = $("#modal-content-fld").find("textarea[name=address]")
                                nik.val(data.nik)
                                $("#jk").val(data.gender).trigger('change')
                                $("#tmpt").val(data.emp_tmpt_lahir)
                                $("#tgl").val(data.emp_lahir)
                                address.val(data.address)
                                $("#template-id").change(function(){
                                    $.ajax({
                                        url : "{{ route('hrd.contract.indexPost') }}",
                                        type : "post",
                                        data : {
                                            _token : "{{ csrf_token() }}",
                                            id_tp : $(this).val(),
                                            id : $("#emp-name").val(),
                                            type : "modal-content"
                                        },
                                        success : function(response){
                                            $("#mdl-content").html(response)
                                            $(".number").number(true, 2)
                                            $("select.select2").select2({
                                                width : "100%"
                                            })
                                            $("#btn-generate").prop("disabled", false)
                                            // $(".field_emp").each(function(){
                                            //     var id = $(this).attr('id')
                                            //     var _val = data[id]
                                            //     if(id == "salary"){
                                            //         _val = parseFloat(atob(data[id])) + parseFloat(atob(data['transport'])) + parseFloat(atob(data['meal'])) + parseFloat(atob(data['house'])) + parseFloat(atob(data['transport']))
                                            //     }
                                            //     console.log(_val)
                                            //     $(this).val(_val)
                                            //     $("#emp-type").val(data.emp_type).trigger('change')
                                            //     $("#emp-div").val(data.division).trigger('change')
                                            // })

                                            var wrapper     = document.getElementById("form-sign"),
                                                saveButton  = wrapper.querySelector("[name=submit_sign]"),
                                                canvas      = wrapper.querySelector("canvas"),
                                                signaturePad;

                                            signaturePad    = new SignaturePad(canvas);


                                            $('#btn-sign-clear').click(function() {
                                                signaturePad.clear();
                                            });

                                            $("#btn-generate").click(function(e){
                                                var isEmpty = signaturePad.isEmpty()
                                                var signUrl = signaturePad.toDataURL();
                                                $("#sign-url").val(signUrl)
                                                if(isEmpty){
                                                    e.preventDefault()
                                                    return Swal.fire("Signature Required", "Please draw your signature", 'warning')
                                                }
                                            })
                                        }
                                    })
                                })
                            }
                        })
                    })

                    $("#emp-name").val(x).trigger('change')
                    $("#emp-id").val(x)
                }
            })
        }

        function _link(x){
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

            var link = $(x).data('link')

            var $tempElement = $("<input>");
            $("body").append($tempElement);
            $tempElement.val($(x).data('link')).select();
            document.execCommand("Copy");
            $tempElement.remove();


            toastr.success(link + " copied to the clipboard");
        }

        $(document).ready(function () {

            @if (\Session::has('link'))
                @if (\Session::get('link') == "error")
                    Swal.fire("Error", "", "error")
                @else
                    Swal.fire("{{ \Session::get('link') }}", "Share the link above to the Employee for signing", "success")
                @endif
            @endif

            var _table = $("table.data_emp").DataTable({
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                },
                pageLength: 100,
                @actionStart('employee', 'read')
                ajax : {
                    url: "{{route('employee.getdata_post')}}?type=0",
                    type: "post",
                    data: {
                        _token: "{{csrf_token()}}",
                    },
                },
                columns : [
                    { "data": "no" },
                    { "data": "emp_name" },
                    { "data": "emp_type" },
                    { "data": "emp_id" },
                    { "data": "emp_position" },
                    { "data": "division" },
                    @actionStart('employee', 'update'){ "data": "status" },@actionEnd
                    @actionStart('employee', 'update'){ "data": "cv" },@actionEnd
                    @actionStart('employee', 'update'){ "data": "document" },@actionEnd
                    @actionStart('employee', 'approvedir'){ "data": "quit" },@actionEnd
                    { "data": "training_point" },
                    @actionStart('employee', 'delete'){ "data": "action" },@actionEnd
                ],
                columnDefs: [
                    {
                        "targets": [1],
                        "className": "text-left",
                    },
                    {
                        "targets": "_all",
                        "className": "text-center",
                    }
                ],
                @actionEnd
            })

            $(".btn-type").click(function(){
                _table.clear().draw()
                _table.ajax.url("{{route('employee.getdata_post')}}?type="+$(this).val()).load()
            })

            $("select.select2").select2({
                width : "100%"
            })

            // getData(0)


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
                var position = $("#emp_type option:selected").html();

                $('#position').val(position)
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
