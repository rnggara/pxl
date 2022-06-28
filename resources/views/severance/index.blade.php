@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <a href="#" class="text-black-50">Severance</a>
            </div>
            <div class="card-toolbar">
                @actionStart('severance', 'create')
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addSeverance"><i class="fa fa-plus"></i>New Severance</button>
                </div>
                @actionEnd
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th nowrap="nowrap" class="text-center">Employee Name</th>
                        <th nowrap="nowrap" class="text-center">Severance Date</th>
                        <th nowrap="nowrap" class="text-center">Severance Reason</th>
                        <th nowrap="nowrap" class="text-center">Dir. Approval</th>
                        <th nowrap="nowrap" class="text-center"></th>
                    </tr>
                    </thead>
                    <tbody>
                        @actionStart('severance', 'read')
                        @foreach($data as $key => $item)
                            <tr>
                                <td align="center">{{$key+1}}</td>
                                <td align="center">
                                    <a href="{{(!empty($item->approved_at)) ? "javascript:window.frames['print_severance'].print()" : '#'}}" class="btn btn-primary btn-xs" onmouseover="button_print('{{$item->id}}')">{{$empdata[$item->emp_id]->emp_name}}</a>
                                </td>
                                <td align="center">{{$item->sev_date}}</td>
                                <td align="center">{{$reas[$item->id_reasons]->reason}}</td>
                                <td align="center">
                                    @if($item->approved_at == null)
                                        @actionStart('severance', 'updatediv1')
                                        <button class="btn btn-primary btn-xs" onclick="button_approve('{{$key}}')"><i class="fa fa-check"></i> Approve</button>
                                        @actionEnd
                                    @else
                                        <span class="label label-inline label-success">Approved at {{$item->approved_at}}</span>
                                    @endif
                                </td>
                                <td align="center">
                                    @actionStart('severance', 'read')
                                    @if(!empty($item->approved_at))
                                        <a href="javascript:window.frames['print_severance'].print()" class="btn btn-primary btn-icon btn-xs" onmouseover="button_print('{{$item->id}}')"><i class="fa fa-print"></i></a>
                                    @endif
                                    @actionEnd
                                    @actionStart('severance', 'delete')
                                    <button class="btn btn-danger btn-icon btn-xs" onclick="button_delete('{{$item->id}}')"><i class="fa fa-trash"></i></button>
                                    @actionEnd
                                </td>
                            </tr>
                        @endforeach
                        @actionEnd
                    </tbody>
                </table>
            </div>
            <iframe src="" height="0" weight="0" id="print_severance" name="print_severance" frameborder="0"></iframe>
        </div>
    </div>
    <div class="modal fade" id="addSeverance" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Severance</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="POST" action="{{URL::route('severance.add')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Form</h4><hr>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Employee</label>
                                    <div class="col-md-8">
                                        <select name="emp_id" class="form-control select2" id="emp">
                                            <option value="">EMPTY</option>
                                            @foreach($users as $user)
                                                @if($user->expel == null)
                                                    <option value="{{$user->id}}">{{$user->emp_name}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Employee In Date</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" min="0" name="emp_in" id="emp-in" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Severance Date</label>
                                    <div class="col-md-8">
                                        <input type="date" class="form-control" min="0" id="sev_date" name="sev_date">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Years of Service</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" min="0" id="yos" name="yos" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Severance Reason</label>
                                    <div class="col-md-8">
                                        @foreach($reasons as $item)
                                            <label for="">
                                                <input type="radio" name="reasons" value="{{$item->id}}"> {{$item->reason}}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4"></label>
                                    <div class="col-md-8">
                                        <button type="button" id="btn-next" class="btn btn-primary btn-xs">Next</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" id="div-result">
                                <h3>Result</h3>
                                <hr>
                                <div class="form-group row">
                                    <label class="col-sm-3 control-label">Salary</label>
                                    <div class="col-sm-9">
                                        <input type="number" name="emp_salary" id="emp_sal" readonly="" class="form-control" value="0">
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <label class="col-sm-3 control-label">Severance</label>
                                    <div class="col-sm-9">
                                        <input type="number" name="emp_sev" id="emp_sev" readonly="" class="form-control" value="0">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 control-label">Appreciation</label>
                                    <div class="col-sm-9">
                                        <input type="number" name="emp_app" id="emp_app" readonly="" class="form-control" value="0">
                                    </div>
                                </div>
                                <hr>
                                <h3>Additional</h3>
                                <div class="form-group row">
                                    <label class="col-sm-3 control-label">Outstanding Salary</label>
                                    <div class="col-sm-9">
                                        <input type="number" name="emp_out_salary" id="out_salary" class="form-control" min="0" value="0" onkeyup="to_zero(this)" onchange="addition_calc()">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 control-label">THR</label>
                                    <div class="col-sm-9">
                                        <input type="number" name="emp_thr" id="thr" class="form-control" min="0" value="0" onkeyup="to_zero(this)" onchange="addition_calc()">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 control-label">Bonus</label>
                                    <div class="col-sm-9">
                                        <input type="number" name="emp_bonus" id="bonus" class="form-control" min="0" value="0" onkeyup="to_zero(this)" onchange="addition_calc()">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 control-label">Others</label>
                                    <div class="col-sm-9">
                                        <input type="number" name="emp_others" id="others" class="form-control" min="0" value="0" onkeyup="to_zero(this)" onchange="addition_calc()">
                                    </div>
                                </div>
                                <hr>
                                <h3>Deduction</h3>
                                <div class="form-group row">
                                    <label class="col-sm-3 control-label">Loan</label>
                                    <div class="col-sm-9">
                                        <input type="number" name="emp_loan" id="loan" class="form-control" value="0" onkeyup="to_zero(this)" onchange="addition_calc()">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 control-label">Union Fee</label>
                                    <div class="col-sm-9">
                                        <input type="number" name="emp_union" id="union" class="form-control" value="0" onkeyup="to_zero(this)" onchange="addition_calc()">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 control-label">Others</label>
                                    <div class="col-sm-9">
                                        <input type="number" name="emp_deduc_others" id="deduc_others" class="form-control" value="0" onkeyup="to_zero(this)" onchange="addition_calc()">
                                    </div>
                                </div>
                                <hr>
                                <h3>Total Severance</h3>
                                <div class="form-group row">
                                    <label class="col-sm-3 control-label">Total</label>
                                    <div class="col-sm-9">
                                        <input type="number" name="sev_total" id="sev_total" class="form-control" value="0" readonly="">
                                    </div>
                                </div>
                                <hr>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="reset" class="btn btn-light-primary font-weight-bold" onclick="reset_result()">Reset</button>
                        <button type="submit" id="btn-save-sev" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalApprove" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Approve</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="POST" action="{{URL::route('severance.approve')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Form</h4><hr>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Employee</label>
                                    <div class="col-md-8">
                                        <select name="" class="form-control select2" id="edit-emp">
                                            <option value="">EMPTY</option>
                                            @foreach($users as $user)
                                                <option value="{{$user->id}}">{{$user->emp_name}}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="emp_id" id="emp-id">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Employee In Date</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" min="0" name="emp_in" id="edit-emp-in" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Severance Date</label>
                                    <div class="col-md-8">
                                        <input type="date" class="form-control" min="0" id="edit-sev_date" name="sev_date">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Years of Service</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" min="0" id="edit-yos" name="yos" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Severance Reason</label>
                                    <div class="col-md-8">
                                        @foreach($reasons as $item)
                                            <label for="">
                                                <input type="radio" name="edit_reasons" onchange="do_cal_edit()" value="{{$item->id}}"> {{$item->reason}}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" id="div-result">
                                <h3>Result</h3>
                                <hr>
                                <div class="form-group row">
                                    <label class="col-sm-3 control-label">Salary</label>
                                    <div class="col-sm-9">
                                        <input type="number" name="emp_salary" id="edit-emp_sal" readonly="readonly" class="form-control" value="0">
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <label class="col-sm-3 control-label">Severance</label>
                                    <div class="col-sm-9">
                                        <input type="number" name="emp_sev" id="edit-emp_sev" readonly="readonly" class="form-control" value="0">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 control-label">Appreciation</label>
                                    <div class="col-sm-9">
                                        <input type="number" name="emp_app" id="edit-emp_app" readonly="readonly" class="form-control" value="0">
                                    </div>
                                </div>
                                <hr>
                                <h3>Additional</h3>
                                <div class="form-group row">
                                    <label class="col-sm-3 control-label">Outstanding Salary</label>
                                    <div class="col-sm-9">
                                        <input type="number" name="emp_out_salary" id="edit-out_salary" class="form-control" min="0" value="0" onkeyup="to_zero(this)" onchange="addition_calc_edit()">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 control-label">THR</label>
                                    <div class="col-sm-9">
                                        <input type="number" name="emp_thr" id="edit-thr" class="form-control" min="0" value="0" onkeyup="to_zero(this)" onchange="addition_calc_edit()">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 control-label">Bonus</label>
                                    <div class="col-sm-9">
                                        <input type="number" name="emp_bonus" id="edit-bonus" class="form-control" min="0" value="0" onkeyup="to_zero(this)" onchange="addition_calc_edit()">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 control-label">Others</label>
                                    <div class="col-sm-9">
                                        <input type="number" name="emp_others" id="edit-others" class="form-control" min="0" value="0" onkeyup="to_zero(this)" onchange="addition_calc_edit()">
                                    </div>
                                </div>
                                <hr>
                                <h3>Deduction</h3>
                                <div class="form-group row">
                                    <label class="col-sm-3 control-label">Loan</label>
                                    <div class="col-sm-9">
                                        <input type="number" name="emp_loan" id="edit-loan" class="form-control" value="0" onkeyup="to_zero(this)" onchange="addition_calc_edit()">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 control-label">Union Fee</label>
                                    <div class="col-sm-9">
                                        <input type="number" name="emp_union" id="edit-union" class="form-control" value="0" onkeyup="to_zero(this)" onchange="addition_calc_edit()">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 control-label">Others</label>
                                    <div class="col-sm-9">
                                        <input type="number" name="emp_deduc_others" id="edit-deduc_others" class="form-control" value="0" onkeyup="to_zero(this)" onchange="addition_calc_edit()">
                                    </div>
                                </div>
                                <hr>
                                <h3>Total Severance</h3>
                                <div class="form-group row">
                                    <label class="col-sm-3 control-label">Total</label>
                                    <div class="col-sm-9">
                                        <input type="number" name="sev_total" id="edit-sev_total" class="form-control" value="0" readonly="">
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <label class="col-sm-3 control-label">Source</label>
                                    <div class="col-sm-9">
                                        <select name="treasury" class="form-control select2" id="" required>
                                            <option value="">Select Source</option>
                                            @foreach($treasures as $item)
                                                <option value="{{$item->id}}">{{$item->source}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <input type="hidden" id="id_sev" name="id_sev">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" id="btn-save-leads" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Approve</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script>

        function button_approve(x){
            $("#modalApprove").modal('show')
            var json_data = "{{json_encode($data)}}".replaceAll("&quot;", "\"")
            var data = JSON.parse(json_data)
            console.log(data[x])
            $("#emp-id").val(data[x].emp_id)
            $("#id_sev").val(data[x].id)
            $("#edit-emp").val(data[x].emp_id).trigger('change')
            $("#edit-emp").prop("readonly", true)
            $("#edit-emp-in").val(data[x].act_date)
            $("#edit-sev_date").val(data[x].sev_date)
            var date1 = new Date($("#edit-emp-in").val())
            var date2 = new Date($("#edit-sev_date").val())
            var date_diff = date2.getTime() - date1.getTime()
            var days = date_diff / (1000 * 3600 * 24);
            var years = days / 365;
            $("#edit-yos").val(Math.floor(years) + " year(s)")

            var reason = $("input[name='edit_reasons']").toArray()
            for (let i = 0; i < reason.length; i++) {
                if (data[x].id_reasons == reason[i].value){
                    reason[i].checked = true
                }
            }

            do_cal_edit()

            $("#edit-out_salary").val(data[x].add_out_salary)
            $("#edit-thr").val(data[x].add_thr)
            $("#edit-bonus").val(data[x].add_bonus)
            $("#edit-others").val(data[x].add_others)
            $("#edit-loan").val(data[x].deduc_loan)
            $("#edit-union").val(data[x].deduc_union)
            $("#edit-deduc_others").val(data[x].deduc_others)
            addition_calc_edit()

        }

        function button_print(x) {
            $("#print_severance").attr('src', "{{route('severance.print')}}/"+x)
            $("#btn-print").trigger('click')
            // window.frames['print_severance'].print()
        }


        var total_sev = 0;
        var total_sev_edit = 0;

        function button_delete(x){
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
                    $.ajax({
                        url : "{{URL::route('severance.delete')}}/" + x,
                        type: "get",
                        dataType: "json",
                        cache: "false",
                        success: function(response){
                            if (response.error == 0){
                                location.reload()
                            } else {
                                Swal.fire('Error occured', 'Please contact your administrator!', 'error')
                            }
                        }
                    })
                }
            })
        }

        function validation_form(){
            var emp = $("#emp").val()
            var empIn = $("#emp-in").val()
            var empOut = $("#sev_date").val()
            var empYears = $("#yos").val()
            var empReason = $("input[name='reasons']:checked").val()
            if (emp != '' && empIn != '' && empOut != '' && empYears != '' && empReason != null) {
                return 1
            } else {
                return 0
            }
        }

        function to_zero(num){
            if (num.value == "") {
                num.value = 0
            } else {
                num.value = parseInt(num.value, 10)
            }
        }

        function reset_result(){
            $("#div-result").css('visibility', 'hidden')
            $("#emp").val('').trigger('change')
            $("#btn-save-sev").attr('disabled', true)
        }

        function addition_calc(){
            var total = parseInt(total_sev) + parseInt($("#out_salary").val()) + parseInt($("#thr").val()) + parseInt($("#bonus").val()) + parseInt($("#others").val()) - parseInt($("#loan").val()) - parseInt($("#union").val()) - parseInt($("#deduc_others").val())
            $("#sev_total").val(total)
        }

        function addition_calc_edit(){
            var total = parseInt(total_sev_edit) + parseInt($("#edit-out_salary").val()) + parseInt($("#edit-thr").val()) + parseInt($("#edit-bonus").val()) + parseInt($("#edit-others").val()) - parseInt($("#edit-loan").val()) - parseInt($("#edit-union").val()) - parseInt($("#edit-deduc_others").val())
            $("#edit-sev_total").val(total)
        }


        function do_cal_edit(){
            var emp = $("#edit-emp").val()
            var empIn = $("#edit-emp-in").val()
            var empOut = $("#edit-sev_date").val()
            var empYears = $("#edit-yos").val()
            var empReason = $("input[name='edit_reasons']:checked").val()
            var years = empYears.split(' ')
            var apptimes = ""

            var json_app = "{{json_encode($appreciation)}}".replaceAll('&quot;', "\"")
            var json_sev = "{{json_encode($severance)}}".replaceAll('&quot;', "\"")
            var sal_app = JSON.parse(json_app)
            var sal_sev = JSON.parse(json_sev)
            var json_res = "{{json_encode($reas)}}".replaceAll('&quot;', "\"")
            var res_app = JSON.parse(json_res)

            var json_emp = "{{json_encode($empsal)}}".replaceAll('&quot;', "\"")
            var jsonsal = JSON.parse(json_emp)
            var salary = jsonsal[emp]
            console.log(years)
            console.log(sal_app)
            console.log(sal_sev)
            console.log(res_app[empReason])
            console.log(empReason)
            var apptimes
            var sevtimes
            if (parseInt(years[0]) < parseInt(sal_app[0].min)) {
                apptimes = 0
            } else {
                for (var i = 0; i < sal_app.length; i++) {
                    if (parseInt(years[0]) >= parseInt(sal_app[i].min) && parseInt(years[0]) <= parseInt(sal_app[i].max)) {
                        apptimes = sal_app[i].amount
                        break
                    }
                }
            }

            if (parseInt(years[0]) < parseInt(sal_sev[0].min)) {
                sevtimes = 0
            } else {
                for (var i = 0; i < sal_sev.length; i++) {
                    if (parseInt(years[0]) >= parseInt(sal_sev[i].min) && parseInt(years[0]) <= parseInt(sal_sev[i].max)) {
                        sevtimes = sal_sev[i].amount
                        break
                    }
                }
            }


            var sev = 0
            if (res_app[empReason].severance > 0) {
                sev = (salary * parseInt(res_app[empReason].severance)) * sevtimes
            } else {
                sev = salary * sevtimes
            }

            var app = 0
            if (res_app[empReason]['appreciation'] > 0) {
                app = (salary * parseInt(res_app[empReason]['appreciation'])) * apptimes
            } else {
                app = salary * apptimes
            }

            total_sev_edit = sev + app

            $("#edit-emp_sal").val(salary)
            $("#edit-emp_sev").val(sev)
            $("#edit-emp_app").val(app)
            $("#edit-sev_total").val(total_sev_edit)
            addition_calc_edit()
        }

        function do_cal(){
            var emp = $("#emp").val()
            var empIn = $("#emp-in").val()
            var empOut = $("#sev_date").val()
            var empYears = $("#yos").val()
            var empReason = $("input[name='reasons']:checked").val()
            var years = empYears.split(' ')
            var apptimes = ""

            var json_app = "{{json_encode($appreciation)}}".replaceAll('&quot;', "\"")
            var json_sev = "{{json_encode($severance)}}".replaceAll('&quot;', "\"")
            var sal_app = JSON.parse(json_app)
            var sal_sev = JSON.parse(json_sev)
            var json_res = "{{json_encode($reas)}}".replaceAll('&quot;', "\"")
            var res_app = JSON.parse(json_res)

            var json_emp = "{{json_encode($empsal)}}".replaceAll('&quot;', "\"")
            var jsonsal = JSON.parse(json_emp)
            var salary = jsonsal[emp]
            console.log(years)
            console.log(sal_app)
            console.log(sal_sev)
            console.log(res_app[empReason])
            console.log(empReason)
            var apptimes
            var sevtimes
            if (parseInt(years[0]) < parseInt(sal_app[0].min)) {
                apptimes = 0
            } else {
                for (var i = 0; i < sal_app.length; i++) {
                    if (parseInt(years[0]) >= parseInt(sal_app[i].min) && parseInt(years[0]) <= parseInt(sal_app[i].max)) {
                        apptimes = sal_app[i].amount
                        break
                    }
                }
            }

            if (parseInt(years[0]) < parseInt(sal_sev[0].min)) {
                sevtimes = 0
            } else {
                for (var i = 0; i < sal_sev.length; i++) {
                    if (parseInt(years[0]) >= parseInt(sal_sev[i].min) && parseInt(years[0]) <= parseInt(sal_sev[i].max)) {
                        sevtimes = sal_sev[i].amount
                        break
                    }
                }
            }


            var sev = 0
            if (res_app[empReason].severance > 0) {
                sev = (salary * parseInt(res_app[empReason].severance)) * sevtimes
            } else {
                sev = salary * sevtimes
            }

            var app = 0
            if (res_app[empReason]['appreciation'] > 0) {
                app = (salary * parseInt(res_app[empReason]['appreciation'])) * apptimes
            } else {
                app = salary * apptimes
            }

            total_sev = sev + app

            $("#emp_sal").val(salary)
            $("#emp_sev").val(sev)
            $("#emp_app").val(app)
            $("#sev_total").val(total_sev)
        }

        $(document).ready(function () {
            var json_emp = "{{json_encode($act_date)}}".replaceAll("&quot;", "\"")
            var emp = JSON.parse(json_emp)
            $("select.select2").select2({
                width: "100%"
            })


            $("#btn-next").click(function(){
                do_cal()
                Swal.fire({
                    title: 'Are you sure?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Submit'
                }).then((result) => {
                    if (result.isConfirmed) {
                        //
                        if (validation_form() == 1) {
                            console.log('tes')
                            let timerInterval
                            Swal.fire({
                                title: 'Wait for a second!',
                                html: 'Processing',
                                timer: 1000,
                                allowOutsideClick: false,
                                timerProgressBar: true,
                                willOpen: () => {
                                    Swal.showLoading()
                                    timerInterval = setInterval(() => {
                                        const content = Swal.getContent()
                                        if (content) {
                                            const b = content.querySelector('b')
                                            if (b) {
                                                b.textContent = Swal.getTimerLeft()
                                            }
                                        }
                                    }, 100)
                                },
                                onClose: () => {
                                    clearInterval(timerInterval)
                                    $("#div-result").show()
                                    do_cal()
                                    $("#btn-save-sev").attr('disabled', false)
                                }
                            }).then((result) => {
                                /* Read more about handling dismissals below */
                                if (result.dismiss === Swal.DismissReason.timer) {
                                    console.log($("input[name='reasons']:checked").val())
                                }
                            })
                        } else {
                            Swal.fire('Warning', 'Please completed the form given', 'warning')
                        }
                    }
                })
            })

            $("#emp").on('change', function(){
                var id_emp = $("#emp option:selected").val()
                $("#emp-in").val(emp[id_emp])
                console.log()
                console.log()
            })

            $("#sev_date").on('change', function(){
                var date1 = new Date($("#emp-in").val())
                var date2 = new Date(this.value)
                var date_diff = date2.getTime() - date1.getTime()
                var days = date_diff / (1000 * 3600 * 24);
                var years = days / 365;
                $("#yos").val(Math.floor(years) + " year(s)")
                console.log()
            })

            $("#modalApprove").on('hidden.bs.modal', function () {
                console.log('modal closed')
                $("#informer").val('')
                $("#gp").val('')
                $("#defendant").val('')
                $("#bp").val('')
                $("#id_point").val('')
                $("#notes").val('')
                $("#type").val('')
            })

            $("#div-result").hide()
            $("#btn-save-sev").attr('disabled', true)

            $('.display').DataTable({
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            });
        })

    </script>
@endsection
