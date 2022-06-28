@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Loan</h3><br>

            </div>
            @actionStart('loan','create')
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addItem"><i class="fa fa-plus"></i>Add Loan</button>
                </div>
                <!--end::Button-->
            </div>
            @actionEnd
        </div>
        <div class="card-body">
            {{--            <h5><span class="span">This page contains a list of Travel Order which has been formed.</span></h5>--}}
            <table class="table display">
                <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-left">Bank</th>
                    <th class="text-center">Description</th>
                    <th class="text-center">Currency</th>
                    <th class="text-center">Total Amount</th>
                    <th class="text-center">Payment</th>
                    <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>
                @actionStart('loan','read')
                    @foreach($loans as $key => $loan)

                        <div class="modal fade" id="editItem{{$key}}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel{{$key}}">Edit Bank Loan</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <i aria-hidden="true" class="ki ki-close"></i>
                                        </button>
                                    </div>
                                    <form method="post" action="{{URL::route('loan.add')}}" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="edit" value="{{$loan->id}}">
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h3>Basic Info</h3>
                                                    <hr>
                                                    <div class="form-group row">
                                                        <label class="col-md-3 col-form-label text-right">Bank</label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" placeholder="Bank Name" name="bank_name" value="{{$loan->bank}}" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-3 col-form-label text-right">Description</label>
                                                        <div class="col-md-9">
                                                            <textarea name="description" class="form-control" id="" cols="30" rows="10">{{$loan->description}}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-3 col-form-label text-right">Loan Type</label>
                                                        <div class="col-md-9">
                                                            <select name="loan_type" class="form-control select2" id="" {{(isset($plan_date[$loan->id]))?'disabled':''}} required>
                                                                <option value="">Select Loan Type</option>
                                                                <option value="KI" @if($loan->type=="KI") SELECTED @endif >Investment Credit</option>
                                                                <option value="KMK" @if($loan->type=="KMK") SELECTED @endif >Working Capital Credit</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-3 col-form-label text-right">Project</label>
                                                        <div class="col-md-9">
                                                            <select name="project" class="form-control select2" id="">
                                                                <option value="">No Project</option>
                                                                @foreach ($projects as $item)
                                                                    <option value="{{ $item->id }}" {{ ($loan->project == $item->id) ? "SELECTED" : "" }}>{{ "[$item->id] $item->prj_name" }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-3 col-form-label text-right">{{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}</label>
                                                        <div class="col-md-9">
                                                            <select name="tc_id" class="form-control select2" id="">
                                                                <option value="">Choose here</option>
                                                                @foreach ($coa as $item)
                                                                    <option value="{{ $item->id }}" {{ ($item->id == $loan->tc_id) ? "SELECTED" : "" }}>[{{ $item->code }}] {{ $item->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <h3>Detail Loan</h3>
                                                    <hr>
                                                    <div class="form-group row">
                                                        <label class="col-md-3 col-form-label text-right">Currency</label>
                                                        <div class="col-md-9">
                                                            <select name="currency" class="form-control select2" required {{(isset($plan_date[$loan->id]))?'disabled':''}}>
                                                                @foreach(json_decode($list_currency) as $key2 => $value)
                                                                    <option value="{{$key2}}" {{($key2 == $loan->currency) ? "selected" : ""}}>{{strtoupper($key2."-".$value)}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-3 col-form-label text-right">Loan Amount</label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control number" placeholder="Loan Amount" name="loan_amount" {{(isset($plan_date[$loan->id]))?'readonly':''}} value="{{$loan->value}}" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-3 col-form-label text-right">Interest Percentage</label>
                                                        <div class="col-md-7">
                                                            <input type="number" {{(isset($plan_date[$loan->id]))?'readonly':''}} step=".01" class="form-control" placeholder="Interest Percentage" value="{{$loan->bunga}}" name="int_percentage" required>
                                                            <span class="text-primary">(Fill with percent per month. If interest is 1% per year, and there are 2 years, then fill with 2%)</span>
                                                        </div>
                                                        <label class="col-md-2 col-form-label text-right">%</label>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-3 col-form-label text-right">Loan Duration</label>
                                                        <div class="col-md-7">
                                                            <input type="text" class="form-control number" {{(isset($plan_date[$loan->id]))?'readonly':''}} placeholder="Loan Duration" name="loan_duration" value="{{$loan->period}}" required>
                                                        </div>
                                                        <label class="col-md-2 col-form-label text-right">month(s)</label>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-3 col-form-label text-right">Loan Start</label>
                                                        <div class="col-md-9">
                                                            <input type="date" class="form-control" name="loan_start" value="{{date('Y-m-d',strtotime($loan->start))}}" {{(isset($plan_date[$loan->id]))?'disabled':''}} required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-3 col-form-label text-right">Installment Period <button class="btn btn-icon btn-dark btn-xs btn-circle" data-toggle="tooltip" title="What period do installments begin to pay?"><i class="fa fa-question icon-xs text-white"></i></button></label>
                                                        <div class="col-md-9">
                                                            <input type="number" class="form-control" {{(isset($plan_date[$loan->id]))?'readonly':''}}  placeholder="Installment Period" value="{{$loan->cicil_start}}" name="loan_installment" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                                            <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                                                <i class="fa fa-check"></i>
                                                Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <tr>
                            <td align="center">{{$key + 1}}</td>
                            <td>
                                <a href="{{URL::route('loan.detail', $loan->id)}}" class="text-hover-danger"><i class="fa fa-search text-primary icon-sm"></i> {{$loan->bank}}</a>
                                &nbsp; &nbsp;

                            </td>
                            <td align="center">{{strip_tags($loan->description)}}</td>
                            <td align="center">{{$loan->currency}}</td>
                            <td align="center">{{number_format($loan->value)}}</td>
                            <td align="center">
                                @if(!isset($plan_date[$loan->id]))
                                    <span class="label label-inline label-danger">Loan has not been planned</span>
                                @else
                                    @if(isset($plan_date[$loan->id]['paid']) && count($plan_date[$loan->id]['paid']) == $loan->period)
                                        <span class="label label-inline label-success">Loan Finished</span>
                                    @elseif(count($plan_date[$loan->id]['planned']) == $loan->period)
                                        <span class="label label-inline label-info">Waiting for the first payment</span>
                                    @else
                                        <span class="label label-inline">{{end($plan_date[$loan->id]['paid'])}}</span>
                                    @endif
                                @endif
                            </td>
                            <td align="center">
                                <button class="btn btn-icon btn-primary btn-xs" data-toggle="modal" data-target="#editItem{{$key}}"><i class="fa fa-pencil-alt"></i></button>
                                @actionStart('loan','delete')
                                <button class="btn btn-icon btn-danger btn-xs" onclick="button_delete({{$loan->id}})"><i class="fa fa-trash"></i></button>
                                @actionEnd
                            </td>
                        </tr>
                    @endforeach
                @actionEnd
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="addItem" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Bank Loan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{URL::route('loan.add')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h3>Basic Info</h3>
                                <hr>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Bank</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" placeholder="Bank Name" name="bank_name" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Description</label>
                                    <div class="col-md-9">
                                        <textarea name="description" class="form-control" id="" cols="30" rows="10"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Loan Type</label>
                                    <div class="col-md-9">
                                        <select name="loan_type" class="form-control select2" id="" required>
                                            <option value="">Select Loan Type</option>
                                            <option value="KI">Investment Credit</option>
                                            <option value="KMK">Working Capital Credit</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Project</label>
                                    <div class="col-md-9">
                                        <select name="project" class="form-control select2" id="">
                                            <option value="">No Project</option>
                                            @foreach ($projects as $item)
                                                <option value="{{ $item->id }}">{{ "[$item->id] $item->prj_name" }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">{{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}</label>
                                    <div class="col-md-9">
                                        <select name="tc_id" class="form-control select2" id="" required>
                                            <option value="">Choose here</option>
                                            @foreach ($coa as $item)
                                                <option value="{{ $item->id }}">[{{ $item->code }}] {{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h3>Detail Loan</h3>
                                <hr>
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
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Loan Amount</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control number" placeholder="Loan Amount" name="loan_amount" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Interest Percentage</label>
                                    <div class="col-md-7">
                                        <input type="number" step=".01" class="form-control" placeholder="Interest Percentage" name="int_percentage" required>
                                        <span class="text-primary">(Fill with percent per month. If interest is 1% per year, and there are 2 years, then fill with 2%)</span>
                                    </div>
                                    <label class="col-md-2 col-form-label text-right">%</label>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Loan Duration</label>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control number" placeholder="Loan Duration" name="loan_duration" required>
                                    </div>
                                    <label class="col-md-2 col-form-label text-right">month(s)</label>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Loan Start</label>
                                    <div class="col-md-9">
                                        <input type="date" class="form-control" name="loan_start" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Installment Period <button class="btn btn-icon btn-dark btn-xs btn-circle" data-toggle="tooltip" title="What period do installments begin to pay?"><i class="fa fa-question icon-xs text-white"></i></button></label>
                                    <div class="col-md-9">
                                        <input type="number" class="form-control" placeholder="Installment Period" value="0" name="loan_installment" required>
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
        function submit_edit_form(){
            Swal.fire({
                title: "Update",
                text: "Are you sure you want to update this data?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Submit",
                cancelButtonText: "Cancel",
                reverseButtons: true,
            }).then(function(result){
                if(result.value){
                    $("#btn-submit-edit").click()
                }
            })
        }
        function button_delete(x){
            Swal.fire({
                title: "Reject",
                text: "Are you sure you want to delete?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Delete",
                cancelButtonText: "Cancel",
                reverseButtons: true,
            }).then(function(result){
                if(result.value){
                    $.ajax({
                        url: "{{URL::route('loan.delete')}}",
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
        $(document).ready(function(){
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
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            })
            $("select.select2").select2({
                width: "100%"
            })
        })
    </script>
@endsection
