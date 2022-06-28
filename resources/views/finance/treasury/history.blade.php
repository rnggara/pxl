@extends('layouts.template')

@section('content')
    <style>
        .hidden {
            display: none;
        }
    </style>
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                {{-- <h3>Treasury Record - {{strtoupper("[".$treasury->currency."] ".$treasury->source)}}</h3><br> --}}
                <h3>Transaction Record - {{ $user->username }}</h3><br>
            </div>
            {{-- <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    @if($accounting_mode == 1)
                        <a href="{{URL::route('treasury.coa', $treasury->id)}}" class="btn btn-primary btn-xs mr-2"><i class="fa fa-list"></i> CoA Assignment</a>
                    @endif
                    <a href="{{URL::route('treasury.coa', $treasury->id)}}" class="btn btn-primary btn-xs mr-2"><i class="fa fa-list"></i> {{ !empty(\Session::get('company_tc_initial')) ? strtoupper(\Session::get('company_tc_initial')) : "TC" }} Assignment - {{strtoupper("[".$treasury->currency."] ".$treasury->source)}} Assignment</a>
                    <a href="{{URL::route('treasure.sp.index', $treasury->id)}}" class="btn btn-warning btn-xs">SP</a>
                    <a href="{{URL::route('treasury.index')}}" class="btn btn-success btn-xs"><i class="fa fa-arrow-circle-left"></i></a>
                </div>
                <!--end::Button-->
            </div> --}}
        </div>
        <div class="card-body">
            {{-- <div class="card card-custom m-5">
                <div class="separator separator-solid separator-white opacity-20"></div>
                <div class="card-body">
                    <table>
                        <tr>
                            <td><label for="">Cash In</label></td>
                            <td>&nbsp;<label for="">:</label>&nbsp;</td>
                            <td width="70%">
                                <label class="text-success">
                                    {{(empty($cashIn[$treasury->id])) ? number_format(0, 2) : number_format(array_sum($cashIn[$treasury->id]), 2)}}
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="">Cash Out</label></td>
                            <td>&nbsp;<label for="">:</label>&nbsp;</td>
                            <td>
                                <label class="text-danger">
                                    {{(empty($cashOut[$treasury->id])) ? number_format(0, 2) : number_format(array_sum($cashOut[$treasury->id]), 2)}}
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="">Only Show Year</label>
                            </td>
                            <td></td>
                            <td>
                                <div class="row">
                                    <div class="col-md-6">
                                        <select name="year" id="y_sel" class="form-control">
                                            @foreach($y as $value)
                                                <option value="{{$value}}" {{($value == $year) ? "selected" : ""}}>{{$value}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="button" id="btn-set-year" class="btn btn-success btn-xs">Set Year</button>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="separator separator-dashed separator-border-2 separator-primary"></div>
            <div class="card card-custom m-5">
                <div class="separator separator-solid separator-white opacity-20"></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for=""><b>Filter</b></label>
                            </div>
                            <div class="form-group">
                                <label >Transaction Type</label>
                                <div class="checkbox-inline">
                                    <label class="checkbox checkbox-success">
                                        <input type="checkbox" name="Checkboxes5"/>
                                        <span></span>
                                        PO
                                    </label>
                                    <label class="checkbox checkbox-success">
                                        <input type="checkbox" name="Checkboxes5"/>
                                        <span></span>
                                        WO
                                    </label>
                                    <label class="checkbox checkbox-success">
                                        <input type="checkbox" name="Checkboxes5"/>
                                        <span></span>
                                        BR
                                    </label>
                                    <label class="checkbox checkbox-success">
                                        <input type="checkbox" name="Checkboxes5"/>
                                        <span></span>
                                        Utilization
                                    </label>
                                    <label class="checkbox checkbox-success">
                                        <input type="checkbox" name="Checkboxes5"/>
                                        <span></span>
                                        Loan
                                    </label>
                                    <label class="checkbox checkbox-success">
                                        <input type="checkbox" name="Checkboxes5"/>
                                        <span></span>
                                        Leasing
                                    </label>
                                    <label class="checkbox checkbox-success">
                                        <input type="checkbox" name="Checkboxes5"/>
                                        <span></span>
                                        Salary
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label >Date Range :</label>
                                <div class="checkbox-inline">
                                    <label class="checkbox checkbox-success">
                                        <input type="checkbox" name="Checkboxes5" checked/>
                                        <span></span>
                                        All
                                    </label>
                                </div>
                                <div>
                                    from : &nbsp;<input type="date" class="form-control col-md-3" width="30%">
                                    &nbsp;to : &nbsp;<input type="date" class="form-control col-md-3" width="30%">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">Vendor</label>
                                <div>
                                    <select name="" class="form-control" id="">
                                        <option value="">Select Vendor</option>
                                        @foreach($vendors as $vendor)
                                            <option value="{{$vendor->id}}">{{$vendor->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="button" class="btn btn-success btn-xs">Submit</button>
                            </div>
                        </div>
                        <!-- <div class="col-md-4">
                            <h3>Schedule Payment</h3>
                            <hr>
                            <div class="form-group">
                                <input type="date" id="date-search" class="form-control" value="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group row">
                                <div class="col-md-8">
                                    <button type="button" id="btn-search" class="btn btn-success btn-xs">Search</button>
                                </div>
                                <div class="col-md-4 text-right btn-group" >
                                    <button class="btn btn-warning btn-xs" onclick="addSP()" id="new-sp">New SP</button>
                                    <button class="btn btn-danger ml-2 btn-xs" id="btn-cancel">Cancel</button>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <div id="label-default">
                                    <label for="" class="text-danger" id="">
                                        ATTENTION!! BEFORE CREATING NEW SP MAKE SURE TO PRINT OUT BANK HISTORY FIRST AND FINALIZE BANK RECORD TO BE THE SAME AS ACTUAL TREASURY RECORD PRINTED FROM BANK.
                                    </label>
                                    <label for="">
                                        To create new SP, please search the date first to check whether the SP is already made or not.
                                    </label>
                                </div>
                                <div id="label-not-found">
                                    <label for="" id="">
                                        No data found.
                                    </label>
                                    <label for="">
                                        Please choose entries below to be inserted to the new SP, and then click New SP above.
                                    </label>
                                </div>
                                <div id="label-found">
                                    <label for="" id="">
                                        Data found.
                                        <div id="data-found"></div>
                                    </label>
                                    <label for="" class="text-danger">
                                        ATTENTION!! BEFORE CREATING NEW SP MAKE SURE TO PRINT OUT BANK HISTORY FIRST AND FINALIZE BANK RECORD TO BE THE SAME AS ACTUAL TREASURY RECORD PRINTED FROM BANK.
                                    </label>
                                    <label for="">
                                        To create new SP, please search the date first to check whether the SP is already made or not.
                                    </label>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
            <div class="separator separator-dashed separator-border-2 separator-primary"></div> --}}
            <div class="m-5">
                <table class="table table-bordered table-hover" id="table-his">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Activity</th>
                        <th class="text-center">Credit</th>
                        <th class="text-center">Debit</th>
                        <th class="text-center">Balance</th>
                        <th class="text-center">SP</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="changeDateModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changeDateModalLabel">Edit Date</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{URL::route('treasury.change.date')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-md-3 col-form-label">Date</label>
                            <div class="col-md-12">
                                <input type="date" id="date-form" class="form-control" name="new_date" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-form-label">Type</label>
                            <div class="col-md-12">
                                <select class="form-control" id="bank-adm" name="bank_adm">
                                    <option value="others">Other</option>
                                    <option value="bunga">Bank Interest</option>
                                    <option value="pajak">Bank Tax</option>
                                    <option value="adm">Bank Administration</option>
                                    <option value="charge">Bank Charge</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="hist-date" name="id_his">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Change</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        var sp = []
        var spdate = []
        function change_date(x, y, z){
            $("#changeDateModal").modal('show')
            $("#hist-date").val(x)
            $("#date-form").val(y)
            $("#bank-adm").val(z)
        }
        function addToSP(x) {
            var val = x.value.split(" ")
            if (x.checked == true){
                sp.push(val[0])
                spdate.push(val[1])
            } else {
                var index = sp.indexOf(val[0])
                var indexsp = spdate.indexOf(val[1])
                if (index >= 0){
                    sp.splice(index, 1)
                }
                if (indexsp >= 0){
                    spdate.splice(indexsp, 1)
                }
            }

            console.log(sp)
            console.log(spdate)
        }

        function addSP() {
            console.log(sp)
            if (sp.length == 0){
                Swal.fire('No entries choosen', 'Please choose entries from the history', 'warning')
            } else {
                $.ajax({
                    url: "{{route('treasury.addsp')}}",
                    type: "post",
                    dataType: "json",
                    cache: false,
                    data: {
                        _token: "{{csrf_token()}}",
                        sp: sp,
                        spdate: spdate,
                        date: $("#date-search").val(),
                        treasure: "{{\Auth::id()}}"
                    },
                    success: function (response) {
                        if (response.error == 0){
                            Swal.fire({
                                title: 'Success',
                                text: "SP Created",
                                icon: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload()
                                }
                            })
                        } else {
                            Swal.fire('Error occured', 'Please contact your administrator', 'error')
                        }
                    }
                })
            }
        }

        function button_approve(x){
            Swal.fire({
                title: "Approve",
                text: "Are you sure you want to approve?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Submit",
                cancelButtonText: "Cancel",
                reverseButtons: true,
            }).then(function(result){
                if(result.value){
                    $.ajax({
                        url: "{{URL::route('treasury.approve')}}",
                        type: "POST",
                        dataType: "json",
                        data: {
                            '_token' : '{{csrf_token()}}',
                            'val' : x
                        },
                        cache: false,
                        success: function(response){
                            if (response.error == 0) {
                                location.reload()
                            } else {
                                Swal.fire({
                                    title: "Error Occured",
                                    icon: "error"
                                })
                            }
                        }
                    })
                }
            })
        }
        function button_reject(x){
            Swal.fire({
                title: "Reject",
                text: "Are you sure you want to reject?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Submit",
                cancelButtonText: "Cancel",
                reverseButtons: true,
            }).then(function(result){
                if(result.value){
                    $.ajax({
                        url: "{{URL::route('treasury.reject')}}",
                        type: "POST",
                        dataType: "json",
                        data: {
                            '_token' : '{{csrf_token()}}',
                            'val' : x
                        },
                        cache: false,
                        success: function(response){
                            if (response.error == 0) {
                                location.reload()
                            } else {
                                Swal.fire({
                                    title: "Error Occured",
                                    icon: "error"
                                })
                            }
                        }
                    })
                }
            })
        }
        function history(){
            $("#table-his").DataTable().destroy()
            var table = $("#table-his").DataTable({
                pageLength: 100,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                },
                ajax : {
                    url : "{{route('treasury.historyjs')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        _token: "{{csrf_token()}}",
                        hist : "{{$user->id}}",
                        from : $("#f_date").val(),
                        to : $("#t_date").val(),
                        filter : $("#filter").val(),
                        year : {{ $year }},
                        _action: "ajax",
                        type : "{{ $type }}"
                    },
                },
                columns : [
                    {"data" : "i"},
                    {"data" : "date"},
                    {"data" : "activity"},
                    {"data" : "credit"},
                    {"data" : "debit"},
                    {"data" : "balance"},
                    {"data" : "sp"},
                ],
                columnDefs : [
                    {targets: [3,4,5], className: "text-right"},
                    {targets: [0,1,6], className: "text-center"},
                ]
            })
        }
        $(document).ready(function(){
            history()
            $("table.display").DataTable({
                pageLength: 100,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            })

            $("#label-found").hide()
            $("#label-not-found").hide()
            $("#new-sp").hide()
            $("#btn-cancel").hide()
            $(".check-sp").hide()

            $("#btn-set-year").click(function(){
                var y = $("#y_sel").val()
                var uri = window.location.href.split("?")

                window.location.href = uri[0] + "?year="+y
            })

            $("#btn-search").click(function(){
                $.ajax({
                    url: "{{route('treasury.findsp')}}",
                    type: "post",
                    dataType: "json",
                    cache: false,
                    data: {
                        _token: "{{csrf_token()}}",
                        date: $("#date-search").val(),
                        treasure: "{{\Auth::id()}}"
                    },
                    success: function (response) {
                        $(".check-sp").show()
                        if (response.sp == null){
                            $("#label-default").hide()
                            $("#label-not-found").show()
                            $("#label-found").hide()
                            $("#new-sp").show()
                            $("#btn-cancel").show()
                            $("#data-found").html('')
                        } else {
                            $("#label-not-found").hide()
                            $("#label-default").hide()
                            $("#label-found").show()
                            $("#btn-cancel").show()
                            $("#new-sp").hide()
                            $("#data-found").html('')
                            for (const argumentsKey in response.sp) {
                                var link = "<a href=\"\" class=\"label label-inline label-success\">"+response.sp[argumentsKey].num+"</a>"
                                $("#data-found").append(link)
                            }
                        }
                    }
                })
            })

            $("#btn-cancel").click(function () {
                $("#label-default").show()
                $("#label-found").hide()
                $("#label-not-found").hide()
                $("#new-sp").hide()
                $("#btn-cancel").hide()
                $(".check-sp").hide()
                $("#data-found").html('')
            })
        })
    </script>
@endsection
