@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Utilization</h3><br>

            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-secondary mr-2" data-toggle="modal" data-target="#addCriteria"><i class="fa fa-cog"></i>Criteria</button>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addItem"><i class="fa fa-plus"></i>Add Utilization</button>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            {{--            <h5><span class="span">This page contains a list of Travel Order which has been formed.</span></h5>--}}
            <table class="table table-bordered table-hover" id="table-util">
                <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-left">Utilization Name</th>
                    <th class="text-center">Category</th>
                    <th class="text-center">Amount</th>
                    <th class="text-center">Type</th>
                    <th class="text-center">Recurrent Date</th>
                    <th class="text-center">Status</th>
                    <th class="text-center"></th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="addItem" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Utilization</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{URL::route('util.add')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h3>Basic Info</h3>
                                <hr>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Utilization Name</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" placeholder="Utilization Name" name="util_name" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Description</label>
                                    <div class="col-md-9">
                                        <textarea name="description" class="form-control" id="" cols="30" rows="10"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Utilization</label>
                                    <div class="col-md-9">
                                        <select name="utilization" class="form-control select2" id="" required>
                                            <option value="">Select Utilization Type</option>
                                            <option value="FIXED">FIXED</option>
                                            <option value="VARIABLE">VARIABLE</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Category</label>
                                    <div class="col-md-9">
                                        <select name="type" class="form-control select2" id="" required>
                                            <option value="">Select Category</option>
                                            @foreach($criteria as $value)
                                                <option value="{{$value->id}}">{{$value->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">{{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}</label>
                                    <div class="col-md-9">
                                        <select name="tc_id" class="form-control select2" required>
                                            <option value="">Choose here</option>
                                            @foreach($type as $value)
                                                <option value="{{$value->id}}">[{{ $value->code }}] {{$value->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h3>Detail Utilization</h3>
                                <hr>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Recurent Date</label>
                                    <div class="col-md-5">
                                        <select name="rmonth" id="mnth" class="form-control select2" required>
                                            <option value="">Select Month</option>
                                            @foreach($months as $key => $value)
                                                <option value="{{$key}}">{{$value}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <select name="rdate" id="dte" class="form-control select2" required>
                                            <option value="">Select Date</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Recurent Type</label>
                                    <div class="col-md-9">
                                        <select name="rtype" class="form-control select2" required id="rtype">
                                            <option value="">Select Type</option>
                                            <option value="monthly">MONTHLY</option>
                                            <option value="yearly">YEARLY</option>
                                            <option value="custom">CUSTOM</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" id="custom-field">
                                    <label class="col-md-3 col-form-label text-right"></label>
                                    <div class="col-md-5">
                                        <input type="number" class="form-control" name="cmonth">
                                    </div>
                                    <label class="col-md-3 col-form-label text-right">month(s)</label>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Amount</label>
                                    <div class="col-md-9">
                                        <input type="text" value="0" class="form-control number" placeholder="Amount" name="amount" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Currency</label>
                                    <div class="col-md-9">
                                        <select name="currency" class="form-control select2" required>
                                            @foreach(json_decode($list_currency) as $key => $value)
                                                <option value="{{$key}}" {{($key == "IDR") ? "selected" : ""}}>{{strtoupper($key."-".$value)}}</option>
                                            @endforeach
                                        </select>
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

    <div class="modal fade" id="addCriteria" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Criteria</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{URL::route('util.add.criteria')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table display table-responsive-xl">
                                    <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Category Name</th>
                                        <th>Description</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($criteria as $key => $value)
                                        <tr>
                                            <td align="center">{{$key + 1}}</td>
                                            <td>{{$value->name}}</td>
                                            <td>{{$value->content}}</td>
                                            <td align="center">
                                                <a href="{{URL::route('util.delete.criteria', $value->id)}}" class="btn btn-xs btn-icon btn-danger"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="separator separator-solid separator-border-2 separator-primary mt-5 mb-5"></div>
                        <h3>Add Criteria</h3>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label for="" class="col-md-3 col-form-label">Category Name</label>
                                    <div class="col-md-9">
                                        <input type="text" name="category_name" class="form-control" required>
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
    <script src="{{asset('theme/assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js?v=7.0.5')}}"></script>
    <script src="{{asset('assets/jquery-number/jquery.number.js')}}"></script>
    <script>
        function button_ready(x){
            $.ajax({
                url: "{{URL::route('util.update_status')}}/" + x,
                type: "get",
                dataType: "json",
                cache: false,
                success: function(response){
                    if (response.error == 0){
                        location.reload()
                    } else {
                        Swal.fire("Error occured", "Please contact your administrator", 'error')
                    }
                }
            })
        }

        function button_change_amount(x){
            console.log('clicked')
            var amount = document.getElementById("amount" + x).value
            console.log(amount)
            $.ajax({
                url: "{{URL::route('util.change_amount')}}",
                type: "post",
                dataType: "json",
                data: {
                    '_token' : '{{csrf_token()}}',
                    'id' : x,
                    'amount' : amount
                },
                cache: false,
                success: function(response){
                    if (response.error == 0){
                        location.reload()
                    } else {
                        Swal.fire("Error occured", "Please contact your administrator", 'error')
                    }
                }
            })
        }
        function button_delete(x){
            Swal.fire({
                title: "Delete",
                text: "Are you sure you want to delete?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Delete",
                cancelButtonText: "Cancel",
                reverseButtons: true,
            }).then(function(result){
                if(result.value){
                    $.ajax({
                        url: "{{URL::route('util.delete')}}",
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
                                Swal.fire('Error occured', 'Please contact your system administrator')
                            }
                        }
                    })
                }
            })
        }
        function button_edit(x){
            $.ajax({
                url: "{{URL::route('treasury.find')}}",
                type: "POST",
                dataType: "json",
                data: {
                    '_token' : '{{csrf_token()}}',
                    'val' : x
                },
                cache: false,
                success: function(response){
                    $("#bank_name").val(response.source)
                    $("#branch_name").val(response.branch)
                    $("#account_name").val(response.account_name)
                    $("#account_number").val(response.account_number)
                    $("#currency").val(response.currency).trigger('change')
                    $("#id_tre").val(response.id)
                }
            })
        }
        function update_detail(){
            $.ajax({
                url: "{{ route('util.update_detail') }}",
                type: "get",
            })
        }
        $(document).ready(function(){
            update_detail()
            $("#table-util").DataTable({
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                },
                pageLength: 25,
                ajax: {
                    url: "{{ route('util.lists') }}",
                    type: "get"
                },
                columns : [
                    {"data":"i"},
                    {"data":"name"},
                    {"data":"category"},
                    {"data":"amount"},
                    {"data":"type"},
                    {"data":"reccurent_date"},
                    {"data":"status"},
                    {"data":"btn"},
                ]
            })
            $("#btn-submit-edit").hide()
            $("#btn-submit").hide()
            $("#btn-deposit").click(function(){
                Swal.fire({
                    title: "Add Deposit",
                    text: "Are you sure you want to submit this data?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Submit",
                    cancelButtonText: "Cancel",
                    reverseButtons: true,
                }).then(function(result){
                    if(result.value){
                        $("#btn-submit").click()
                    }
                })
            })

            $(".number").number(true, 2)

            $("table.display").DataTable({
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            })
            $("select.select2").select2({
                width: "100%"
            })

            $("#custom-field").hide()

            $("#rtype").change(function(){
                var v = this.value
                if (v == "custom"){
                    $("#custom-field").show()
                    var input = $("#custom-field").find('input')
                    input.attr('required', true)
                } else {
                    $("#custom-field").hide()
                    var input = $("#custom-field").find('input')
                    input.attr('required', false)
                }
            })

            $("#mnth").change(function(){
                var m = this.value
                console.log("change")
                $("#dte").val(null).trigger("change");
                $("#dte").empty()
                $("#dte").append("<option value=''>Select Date</option>")
                $.ajax({
                    url: "{{URL::route('util.getdate')}}/" + m,
                    type: "GET",
                    dataType: "json",
                    success: function(response){
                        $("#dte").select2({
                            data: response.results
                        })
                    }
                })
            })
            console.log("month " + mnth)
        })
    </script>
@endsection

