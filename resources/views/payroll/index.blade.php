@extends('layouts.template')
@section('css')
<style type="text/css">
    @media print {
        @page {
            size: landscape;
        }

        body * {
            visibility: hidden;
        }

        #table-payroll, #table-payroll * {
            visibility: visible;
            font-weight: bold;
        }

        #table-payroll {
            position: absolute;
            left: 0;
            top: 0;
            page-break-before: avoid;
            font-weight: bold;
        }
    }
</style>
@endsection

@section('content')
    @if(session()->has('message_needsec_fail'))
        <div class="alert alert-danger">
            {!! session()->get('message_needsec_fail') !!}
        </div>
    @endif
    @if(session()->has('message_needsec_success'))
        <div class="alert alert-success">
            {!! session()->get('message_needsec_success') !!}
        </div>
    @endif
    @if(!(session()->has('seckey_payroll')) || (session()->has('seckey_payroll') < 10))
        @include('ha.needsec.index', ["type" => "payroll"])
    @else
        <div class="card card-custom gutter-b">
            <div class="card-header">
                <div class="card-title">
                    <h3>Payroll</h3><br>

                </div>
                <div class="card-toolbar">

                    <!--end::Button-->
                </div>
            </div>
            <div class="card-body">
                <div class="row" id="show-total">

                </div>
                <div class="col-md-8 mx-auto">
                    <form method="post" action="{{URL::route('payroll.export')}}" id="form-export" target="_blank">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-2">
                                <select name="type" id="type" class="form-control">
                                    @actionStart('payroll_all', 'access')
                                    <option value="all">All</option>
                                    @actionEnd
                                    @foreach($type as $key => $value)
                                        @if (isset($emp_type[$value->id]))
                                            @php
                                                $action = (isset($mod[$value->rms_id])) ? $mod[$value->rms_id] : "payroll_".str_replace(" ","_", strtolower($value->name));
                                            @endphp
                                                @actionStart($action, 'access')
                                                <option value="{{$value->id}}">{{$value->name}}</option>
                                            @actionEnd
                                        @endif
                                    @endforeach
                                    {{--  @if(Auth::user()->id_rms_roles_divisions == 1)


                                    @else
                                        @actionStart('payroll_staff', 'access')
                                        <option value="1">Staff</option>
                                        @actionEnd
                                        @actionStart('payroll_manager', 'access')
                                        <option value="5">Manager</option>
                                        @actionEnd
                                        @actionStart('payroll_marketing', 'access')
                                        <option value="9">Marketing</option>
                                        @actionEnd
                                        @actionStart('payroll_bod', 'access')
                                        <option value="6">BOD</option>
                                        @actionEnd
                                        @actionStart('payroll_field_e', 'access')
                                        <option value="2">Field Engineer</option>
                                        @actionEnd
                                        @actionStart('payroll_wh_bintaro', 'access')
                                        <option value="3">WH Bintaro</option>
                                        @actionEnd
                                        @actionStart('payroll_wh_cil', 'access')
                                        <option value="4">WH Cileungsi</option>
                                        @actionEnd
                                        @actionStart('payroll_konsultan', 'access')
                                        <option value="7">Konsultan</option>
                                        @actionEnd
                                        @actionStart('payroll_local', 'access')
                                        <option value="8">Local</option>
                                        @actionEnd
                                    @endif  --}}

                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="month" id="month" class="form-control">
                                    @foreach($month as  $key => $value)
                                        <option value="{{$key}}" {{($key == date('m')) ? "selected" : ""}}>{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="years" id="year" class="form-control">
                                    @foreach($years as $value)
                                        <option value="{{$value}}" {{($value == date('Y')) ? "selected" : ""}}>{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                @actionStart('payroll', 'read')
                                <button type="button" id="btnSeacrh" class="btn btn-primary btn-xs"><i class="fa fa-search"></i> Search</button>
                                @actionEnd
                            </div>
                        </div>
                        <div class="form-group row">
                            @actionStart('payroll', 'read')
                            <button type="button" id="btnPdf" onclick="window.print()" class="btn btn-primary btn-xs"><i class="fa fa-file-pdf"></i> Print</button>
                            <button id="btnExport" class="btn btn-primary ml-5 btn-xs"><i class="fa fa-file-export"></i> Export</button>
                            <a id="btnPrint" class="btn btn-info ml-5 btn-xs"><i class="fa fa-print"></i> Print Bank Transfer</a>
                            <a id="btnExportBtl" class="btn btn-info ml-5 btn-xs"><i class="fa fa-file-export"></i> Export Bank Transfer</a>
                            <button type="button" id="btnUpdateArch" class="btn ml-5 btn-danger btn-xs"><i class="fa flaticon-refresh"></i> Refresh</button>

                            @actionEnd
                        </div>
                    </form>
                </div>

                <!-- Table Payroll -->
                <div id="table-payroll">
                    <div class="row">
                        <div class="col-12 text-center font-weight-bold">
                            <b>PAYROLL {{ strtoupper(\Session::get('company_name_parent')) }}</b> <br>
                            <span id="position"></span><br>
                            <b>PERIODE :</b> <span id="periode"></span>
                        </div>
                    </div>
                    <table class="table table-bordered table-responsive-xl">
                        <tr>
                            <th class="text-center" colspan="31">
                                Data Source : <span id="title-table"></span>
                            </th>
                        </tr>
                    </table>
                    <table id="table-display" class="table table-bordered table-responsive-lg table-responsive-sm" style="width: 100%">
                        <thead>
                            <tr>
                                <th class="text-center" rowspan="2">No.</th>
                                <th rowspan="2">Name/Position</th>
                                <th class="text-center" rowspan="2">Salary *)</th>
                                <th class="text-center" colspan="3">Overtime </th>
                                <th class="text-center" colspan="3">Field</th>
                                <th class="text-center" colspan="3">Warehouse</th>
                                <th class="text-center" colspan="3">ODO</th>
                                <th class="text-center" colspan="3">Allowance</th>
                                <th class="text-center" rowspan="2">Voucher</th>
                                <th class="text-center" rowspan="2">Total<br>Salary</th>
                                <th class="text-center" colspan="6" id="col-deduc">Deduction</th>
                                <th class="text-center" rowspan="2">Bonus</th>
                                <th class="text-center" rowspan="2">THR</th>
                                <th class="text-center" rowspan="2">PPH21</th>
                                <th class="text-center" rowspan="2">Proportional</th>
                                <th class="text-center" rowspan="2">THP</th>
                            </tr>
                            <tr>
                                <th class="text-center">Rate</th>
                                <th class="text-center">Hours</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Rate</th>
                                <th class="text-center">Days</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Rate</th>
                                <th class="text-center">Days</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Rate</th>
                                <th class="text-center">Days</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">BPJS-TK</th>
                                <th class="text-center">BPJS-KES</th>
                                <th class="text-center">JSHK</th>
                                <th class="text-center">Sanction</th>
                                <th class="text-center">Absence</th>
                                <th class="text-center">Loan</th>
                                <th class="text-center">BPJS-TK</th>
                                <th class="text-center">BPJS-KES</th>
                                <th class="text-center">JSHK</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tr id="footer">
                            <td colspan="2" align="right">
                                <b>Total :</b>
                            </td>
                            <td align="right"></td>
                            <td align="right"></td>
                            <td align="right"></td>
                            <td align="right"></td>
                            <td align="right"></td>
                            <td align="right"></td>
                            <td align="right"></td>
                            <td align="right"></td>
                            <td align="right"></td>
                            <td align="right"></td>
                            <td align="right"></td>
                            <td align="right"></td>
                            <td align="right"></td>
                            <td align="right"></td>
                            <td align="right"></td>
                            <td align="right"></td>
                            <td align="right"></td>
                            <td align="right"></td>
                            <td align="right"></td>
                            <td align="right"></td>
                            <td align="right"></td>
                            <td align="right"></td>
                            <td align="right"></td>
                            <td align="right"></td>
                            <td align="right"></td>
                            <td align="right"></td>
                            <td align="right"></td>
                            <td align="right"></td>
                            <td align="right"></td>
                        </tr>
                        <tr>
                            <td colspan="31">
                                <div class='alert alert-warning'>
                                    Note:
                                    <ul>
                                        <li><sup><small>*)</small></sup>Salary column is sum of basic salary basic salary + health allowance + transport allowance + meal allowance + house allowance + position allowance. </li>
                                        <li>The calculation of basic salary is started from the 16th to the 15th of the following month</li>
                                        <li>ODO (One Day Off) On at Off time</li>
                                        <li>The amount of overtime is the result of rounding up per hour</li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="27">
                                <div id="table-signature"></div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    @endif


@endsection

@section('custom_script')
    <script>
        function get_total_payroll(){
            $.ajax({
                url : "{{ route('payroll.get.total') }}",
                type : "post",
                dataType : "json",
                data : {
                    _token : "{{ csrf_token() }}",
                    period : "{{ date('n')."-".date("Y") }}"
                },
                cache : false,
                success : function(respose){
                    console.log(response)
                }
            })
        }
        $(document).ready(function(){
            get_total_payroll()
            $("#table-payroll").hide()
            $("#btnPrint").hide()
            $("#btnPdf").hide()
            $("#btnUpdateArch").hide()
            $("#btnExport").hide();
            $("#btnExportBtl").hide();

            $("#btnExport").click(function(){
                $("#form-export").submit()
            })

            $("#btnUpdateArch").click(function () {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // do the remove
                        var t = $("#type option:selected").val()
                        var m = $("#month option:selected").val()
                        var y = $("#year option:selected").val()
                        $.ajax({
                            url: "{{route('payroll.update')}}",
                            type: "post",
                            dataType: "json",
                            data: {
                                "_token" : "{{csrf_token()}}",
                                'type': t,
                                'month': m,
                                'years': y,
                            },
                            cache: false,
                            success: function(response){
                                if (response.error == 0){
                                    $("#btnSeacrh").click()
                                } else {
                                    Swal.fire('Error Occured', 'Please contact your system administrator', 'error')
                                }
                            }
                        })
                    }
                })
            })

            $("#btnSeacrh").click(function(){
                $(this).addClass('spinner spinner-white spinner-right')
                // $(this).prop('disabled', true)
                var t = $("#type option:selected").val()
                var m = $("#month option:selected").val()
                var y = $("#year option:selected").val()
                $.ajax({
                    url: "{{URL::route('payroll.show')}}",
                    type: 'POST',
                    dataType: 'json',
                    startTime: performance.now(),
                    cache: false,
                    data: {
                        'type': t,
                        'month': m,
                        'years': y,
                        '_token': '{{csrf_token()}}',
                    },
                    success: function(response){
                        //Calculate the difference in milliseconds.
                        var time = performance.now() - this.startTime;

                        //Convert milliseconds to seconds.
                        var seconds = time / 1000;

                        //Round to 3 decimal places.
                        seconds = seconds.toFixed(3);
                        Swal.fire({
                            title: "Loading Data",
                            timer: 1500 + time,
                            onOpen: function() {
                                Swal.showLoading()
                            }
                        }).then(function(result) {
                            if (result.dismiss === "timer") {
                                $("#btnSeacrh").removeClass('spinner spinner-white spinner-right')
                                $("#btnSeacrh").prop('disabled', false)
                                if (response.error == 0) {
                                    var datafoot = response.footer;
                                    $("#periode").text(response.periode)
                                    $("#position").text(response.position)
                                    $("#btnPdf").show()
                                    $("#btnExport").show();
                                    $("#btnExportBtl").show();
                                    $("#btnExportBtl").attr('href', "{{route('payroll.print_btl')}}?act=print&t="+t+"&m="+m+"&y="+y+"&export=on")
                                    $("#table-payroll").fadeIn()
                                    $("#btnPrint").show()
                                    $("#btnPrint").attr('href', "{{route('payroll.print_btl')}}?act=remarks&t="+t+"&m="+m+"&y="+y+"")
                                    $('#table-display').DataTable().clear();
                                    $('#table-display').DataTable().destroy();
                                    $("#footer").find("td").show()
                                    var col_hide = []
                                    var minColspan = 0;

                                    if (datafoot.sum_ovt == 0) {
                                        col_hide.push(5)
                                        col_hide.push(4)
                                        col_hide.push(3)
                                    }

                                    if (datafoot.sum_fld == 0) {
                                        col_hide.push(8)
                                        col_hide.push(7)
                                        col_hide.push(6)
                                    }

                                    if (datafoot.sum_wh == 0) {
                                        col_hide.push(11)
                                        col_hide.push(10)
                                        col_hide.push(9)
                                    }

                                    if (datafoot.sum_odo == 0) {
                                        col_hide.push(14)
                                        col_hide.push(13)
                                        col_hide.push(12)
                                    }

                                    if (datafoot.sum_bonus == 0) {
                                        col_hide.push(26)
                                    }

                                    if (datafoot.sum_thr == 0) {
                                        col_hide.push(27)
                                    }

                                    if (datafoot.sum_prop == 0) {
                                        col_hide.push(29)
                                    }

                                    if (datafoot.sum_sanction == 0){
                                        col_hide.push(20)
                                        minColspan += 1;
                                    }

                                    if (datafoot.sum_loan == 0){
                                        col_hide.push(22)
                                        minColspan += 1;
                                    }

                                    if (datafoot.sum_pph21 == 0){
                                        col_hide.push(28)
                                    }

                                    col_hide.push(21)

                                    // if (datafoot.sanction == 0){
                                    //     col_hide.push(20)
                                    //     minColspan += 1;
                                    // }

                                    if (response.source == "Archive" && response.btnArch == true){
                                        $("#btnUpdateArch").show()
                                    } else {
                                        $("#btnUpdateArch").hide()
                                    }
                                    $("#title-table").text(response.source)
                                    $("#table-signature").html(response.table_signature)

                                    if(response.data.length != null){
                                        $("#table-display").DataTable({
                                            "data" : response.data,
                                            paging: false,
                                            searching: false,
                                            ordering: false,
                                            paging: false,

                                            'footerCallback' : function(tfoot, data, start, end, display) {
                                                var resp = datafoot
                                                if(response.data.length > 0){
                                                    if (resp) {
                                                        var td = $("#footer").find('td')
                                                        td.eq(1).html(currencyFormat(resp.sum_salary))
                                                        td.eq(4).html(currencyFormat(resp.sum_ovt))
                                                        td.eq(7).html(currencyFormat(resp.sum_fld))
                                                        td.eq(10).html(currencyFormat(resp.sum_wh))
                                                        td.eq(13).html(currencyFormat(resp.sum_odo))
                                                        td.eq(14).html(currencyFormat(resp.sum_tk))
                                                        td.eq(15).html(currencyFormat(resp.sum_ks))
                                                        td.eq(16).html(currencyFormat(resp.sum_jshk))
                                                        td.eq(17).html(currencyFormat(resp.sum_voucher))
                                                        td.eq(18).html(currencyFormat(resp.sum_tot_salary))
                                                        td.eq(19).html(currencyFormat(resp.sum_sanction))
                                                        td.eq(21).html(currencyFormat(resp.sum_loan))
                                                        td.eq(22).html(currencyFormat(resp.sum_ded_tk))
                                                        td.eq(23).html(currencyFormat(resp.sum_ded_ks))
                                                        td.eq(24).html(currencyFormat(resp.sum_ded_jshk))
                                                        td.eq(25).html(currencyFormat(resp.sum_bonus))
                                                        td.eq(26).html(currencyFormat(resp.sum_thr))
                                                        td.eq(27).html(currencyFormat(resp.sum_pph21))
                                                        td.eq(28).html(currencyFormat(resp.sum_prop))
                                                        td.eq(29).html(currencyFormat(resp.sum_thp))
                                                        if (resp.sum_ovt == 0) {
                                                            td.eq(4).hide()
                                                            td.eq(3).hide()
                                                            td.eq(2).hide()
                                                        } else {
                                                            td.eq(4).show()
                                                            td.eq(3).show()
                                                            td.eq(2).show()
                                                        }

                                                        if (resp.sum_fld == 0) {
                                                            td.eq(7).hide()
                                                            td.eq(6).hide()
                                                            td.eq(5).hide()
                                                        } else {
                                                            td.eq(7).show()
                                                            td.eq(6).show()
                                                            td.eq(5).show()
                                                        }

                                                        if (resp.sum_wh == 0) {
                                                            td.eq(10).hide()
                                                            td.eq(9).hide()
                                                            td.eq(8).hide()
                                                        } else {
                                                            td.eq(10).show()
                                                            td.eq(9).show()
                                                            td.eq(8).show()
                                                        }

                                                        if (resp.sum_odo == 0) {
                                                            td.eq(13).hide()
                                                            td.eq(12).hide()
                                                            td.eq(11).hide()
                                                        } else {
                                                            td.eq(13).show()
                                                            td.eq(12).show()
                                                            td.eq(11).show()
                                                        }

                                                        if (resp.sum_bonus == 0) {
                                                            td.eq(25).hide()
                                                        } else {
                                                            td.eq(25).show()
                                                        }

                                                        if (resp.sum_thr == 0) {
                                                            td.eq(26).hide()
                                                        } else {
                                                            td.eq(26).show()
                                                        }

                                                        if (resp.sum_pph21 == 0) {
                                                            td.eq(27).hide()
                                                        } else {
                                                            td.eq(27).show()
                                                        }

                                                        if (resp.sum_prop == 0) {
                                                            td.eq(28).hide()
                                                        } else {
                                                            td.eq(28).show()
                                                        }

                                                        if (resp.sum_loan == 0) {
                                                            td.eq(21).hide()
                                                        } else {
                                                            td.eq(21).show()
                                                        }

                                                        if (resp.sum_sanction == 0) {
                                                            td.eq(19).hide()
                                                        } else {
                                                            td.eq(19).show()
                                                        }


                                                        td.eq(20).hide()
                                                    }
                                                } else {
                                                    var td = $("#footer").find('td')
                                                    td.eq(1).html(currencyFormat(resp.sum_salary))
                                                    td.eq(4).html(currencyFormat(resp.sum_ovt))
                                                    td.eq(7).html(currencyFormat(resp.sum_fld))
                                                    td.eq(10).html(currencyFormat(resp.sum_wh))
                                                    td.eq(13).html(currencyFormat(resp.sum_odo))
                                                    td.eq(14).html(currencyFormat(resp.sum_tk))
                                                    td.eq(15).html(currencyFormat(resp.sum_ks))
                                                    td.eq(16).html(currencyFormat(resp.sum_jshk))
                                                    td.eq(17).html(currencyFormat(resp.sum_voucher))
                                                    td.eq(18).html(currencyFormat(resp.sum_tot_salary))
                                                    td.eq(19).html(currencyFormat(resp.sum_sanction))
                                                    td.eq(21).html(currencyFormat(resp.sum_loan))
                                                    td.eq(22).html(currencyFormat(resp.sum_ded_tk))
                                                    td.eq(23).html(currencyFormat(resp.sum_ded_ks))
                                                    td.eq(24).html(currencyFormat(resp.sum_ded_jshk))
                                                    td.eq(25).html(currencyFormat(resp.sum_bonus))
                                                    td.eq(26).html(currencyFormat(resp.sum_thr))
                                                    td.eq(27).html(currencyFormat(resp.sum_pph21))
                                                    td.eq(28).html(currencyFormat(resp.sum_prop))
                                                    td.eq(29).html(currencyFormat(resp.sum_thp))
                                                }

                                            },

                                            "columnDefs": [{
                                                targets : [2, 3, 5, 6, 8, 9, 11, 12, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23,24,25,26,27,28,29, 30],
                                                className: 'text-right',
                                            },
                                            {targets : col_hide, visible : false, searchable : false}
                                            ],
                                            fixedHeader: true,
                                            fixedHeader: {
                                                headerOffset: 90
                                            },
                                        })
                                    } else {
                                        var td = $("#footer").find("td")
                                    }
                                } else {
                                    $("#table-payroll").fadeIn()
                                    $('#table-display').DataTable().clear();
                                    $('#table-display').DataTable().destroy();
                                    $("#table-display").DataTable()
                                    $("#footer").find("td").hide()
                                }
                            }
                        })
                    }
                })

            })
        })
        function currencyFormat(num) {
            return num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        }
    </script>
@endsection

