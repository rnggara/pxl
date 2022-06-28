@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Invoice Entry of <span class="text-primary">
                    {{ (isset($prj_name[$inv->id_project])) ? $prj_name[$inv->id_project] : $prj_name[json_decode($inv->title)->id] }}
                </span></h3><br>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{ route('coa.assign', ['type' => "invoice-out", 'id' => $inv->id_inv]) }}" class="btn btn-light-primary"><i class="fa fa-edit"></i>Set Transaction Code</a>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addEntry"><i class="fa fa-plus"></i>Add Entry</button>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#all">
                        <span class="nav-icon">
                            <i class="flaticon-folder-1"></i>
                        </span>
                        <span class="nav-text">Account Receivable List</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#revise" aria-controls="profile">
                        <span class="nav-icon">
                            <i class="flaticon-folder-2"></i>
                        </span>
                        <span class="nav-text">Request Revise</span>
                    </a>
                </li>
            </ul>
            <div class="tab-content mt-5" id="myTabContent">
                <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="home-tab">
                    <div class="m-5">
                        <table class="table display">
                            <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-left">Activity</th>
                                <th class="text-center">Invoice Date</th>
                                <th class="text-center">Invoice No</th>
                                <th class="text-center">Total Invoice Value (IDR)</th>
                                <th class="text-center">Tax (IDR)</th>
                                <th class="text-center">Payment Account</th>
                                <th class="text-center">Created by</th>
                                <th class="text-center">Manager Approval</th>
                                <th class="text-center">Payment Receive</th>
                                <th class="text-center">Rev#</th>
                                <th class="text-center"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($details as $key => $item)
                                <tr>
                                    <td align="center">{{$key + 1}}</td>
                                    <td>{{$item->activity}}</td>
                                    <td align="center">{{date("d M Y",strtotime($item->date))}}</td>
                                    <td align="center" nowrap="nowrap">
                                        <a href="{{($item->value_d == 0) ? route('ar.input_entry', $item->id) : route('ar.view_entry', ['id'=>$item->id, 'act'=>'view'])}}" class="text-hover-danger">
                                            <i class="fa fa-search text-hover-danger text-primary"></i> {{$item->no_inv}}
                                        </a>
                                    </td>
                                    <td align="right">{{number_format($item->value_d, 2)}}</td>
                                    <td align="right">
                                        @if($item->value_d > 0)
                                            <table>
                                                @if(is_array(json_decode($item->taxes)))
                                                @foreach(json_decode($item->taxes) as $value)
                                                    <tr>
                                                        <td nowrap>{{$tax[$value]->tax_name}} {!! ($tax[$value]->is_wapu == 1 && $item->wapu == "on") ? "<br><span class='label label-inline label-warning'>WAPU</span>" : "" !!}</td>
                                                        <td>:</td>
                                                        <td>
                                                            <?php
                                                            $sum = $item->value_d;
                                                            $v = eval("return ".$tax[$value]->formula.";");
                                                            ?>
                                                            {{number_format($v, 2)}}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                @endif
                                            </table>
                                        @else
                                            {{number_format(0,2)}}
                                        @endif
                                    </td>
                                    <td align="center">{{(isset($bank_name[$item->payment_account])) ? $bank_name[$item->payment_account] : ""}}</td>
                                    <td align="center">{{$item->created_by}}</td>
                                    <td align="center">
                                        @if($item->value_d == 0)
                                            <span class="text-warning">Waiting for value</span>
                                        @else
                                            @if($item->fin_approved_by == null)
                                                <a href="{{route('ar.view_entry', ['id'=>$item->id, 'act'=>'appr'])}}" class="text-hover-danger">Waiting</a>
                                            @else
                                                approved by {{$item->fin_approved_by}} at {{date('d M Y', strtotime($item->fin_approved_date))}}
                                            @endif
                                        @endif
                                    </td>
                                    <td align="center">@if($item->value_d == 0)
                                            <span class="text-warning">Waiting for value</span>
                                        @else
                                            @if($item->ceo_app_by == null)
                                                @if($item->fin_approved_by == null)
                                                    Waiting
                                                @else
                                                    <a href="{{route('ar.view_entry', ['id'=>$item->id, 'act'=>'appr'])}}" class="text-hover-danger">Waiting</a>
                                                @endif
                                            @else
                                                approved by {{$item->ceo_app_by}} at {{date('d M Y', strtotime($item->ceo_app_date))}}
                                            @endif
                                        @endif
                                    </td>
                                    <td align="center">
                                        -
                                    </td>
                                    <td align="center" nowrap>
                                        <button type="button" data-toggle="modal" onclick="find_item({{ $item->id }})" data-target="#editEntry" class="btn btn-xs btn-primary btn-icon"><i class="fa fa-edit"></i></button>
                                        <button class="btn btn-xs btn-danger btn-icon" onclick="button_delete({{$item->id}})"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="revise" role="tabpanel" aria-labelledby="home-tab">
                    <div class="m-5">
                        <table class="table display">
                            <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-left">Activity</th>
                                <th class="text-center">Invoice Date</th>
                                <th class="text-center">Invoice No</th>
                                <th class="text-center">Total Invoice Value (IDR)</th>
                                <th class="text-center">Tax (IDR)</th>
                                <th class="text-center">Payment Account</th>
                                <th class="text-center">Request by</th>
                                <th class="text-center">Request Date</th>
                                <th class="text-center">Request Note</th>
                                <th class="text-center">Approve CEO</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($details as $key => $item)
                                @if($item->req_revise_by != null)
                                <tr>
                                    <td align="center">{{$key + 1}}</td>
                                    <td>{{$item->activity}}</td>
                                    <td align="center">{{date("d M Y",strtotime($item->date))}}</td>
                                    <td align="center" nowrap="nowrap">
                                        <a href="{{($item->value_d == 0) ? route('ar.input_entry', $item->id) : route('ar.view_entry', ['id'=>$item->id, 'act'=>'view'])}}" class="text-hover-danger">
                                            <i class="fa fa-search text-hover-danger text-primary"></i> {{$item->no_inv}}
                                        </a>
                                    </td>
                                    <td align="right">{{number_format($item->value_d, 2)}}</td>
                                    <td align="right">
                                        @if($item->value_d > 0)
                                            <table>
                                                @if(is_array(json_decode($item->taxes)))
                                                @foreach(json_decode($item->taxes) as $value)
                                                    <tr>
                                                        <td>{{$tax[$value]->tax_name}} {!! ($tax[$value]->is_wapu == 1) ? "<span class='label label-inline label-warning'>WAPU</span>" : "" !!}</td>
                                                        <td>:</td>
                                                        <td>
                                                            <?php
                                                            $sum = $item->value_d;
                                                            $v = eval("return ".$tax[$value]->formula.";");
                                                            ?>
                                                            {{number_format($v, 2)}}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                @endif
                                            </table>
                                        @else
                                            {{number_format(0,2)}}
                                        @endif
                                    </td>
                                    <td align="center">{{(isset($bank_name[$item->payment_account])) ? $bank_name[$item->payment_account] : ""}}</td>
                                    <td align="center">{{$item->req_revise_by}}</td>
                                    <td align="center">
                                        {{date('d M Y', strtotime($item->req_revise_date))}}
                                    </td>
                                    <td align="center">
                                        {{strip_tags($item->req_revise_note)}}
                                    </td>
                                    <td align="center">
                                        <button class="btn btn-xs btn-success" onclick="revise_approve({{$item->id}})"><i class="fa fa-check"></i> Approve</button>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="addEntry" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Payment </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                    </div>
                    <form action="{{URL::route('ar.addEntry')}}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group row">
                                <label for="" class="col-form-label col-md-3 text-right">Activity</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="activity" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-form-label col-md-3 text-right">Date</label>
                                <div class="col-md-9">
                                    <input type="date" class="form-control" name="date" required>
                                </div>
                            </div>
                            {{-- <div class="form-group row">
                                <label for="" class="col-form-label col-md-3 text-right">Due Date</label>
                                <div class="col-md-9">
                                    <input type="date" class="form-control" name="due_date" readonly required>
                                </div>
                            </div> --}}
                            <div class="form-group row">
                                <label for="" class="col-form-label col-md-3 text-right">Payment Account</label>
                                <div class="col-md-9">
                                    <select name="bank_src" class="form-control select2" id="" required>
                                        <option value="">Select Bank</option>
                                        @foreach($banks as $bank)
                                            <option value="{{$bank->id}}">{{"[".$bank->currency."] ".$bank->source}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-form-label col-md-3 text-right">Taxes</label>
                                <div class="col-md-9">
                                    <select name="tax[]" multiple class="form-control select2" id="">
                                        @foreach($taxes as $tax)
                                            <option value="{{$tax->id}}">{{$tax->tax_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 text-right">{{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}</label>
                                <div class="col-9">
                                    <select name="tc_id" class="form-control select2" id="" required>
                                        <option value="">Choose here</option>
                                        @foreach ($coa as $item)
                                            <option value="{{ $item->id }}">[{{ $item->code }}] {{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-form-label col-md-3 text-right">WAPU</label>
                                <div class="col-md-9">
                                    <label class="col-form-label checkbox checkbox-outline checkbox-outline-2x checkbox-success">
                                        <input type="checkbox" name="wapu"/>
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <div class="alert alert-primary">
                                        <i class="fa fa-info-circle text-white"></i> If WAPU is selected, the invoice will not receive additional amount for Ppn 10%
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="id_inv" value="{{$inv->id_inv}}">
                            <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                            <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                                <i class="fa fa-check"></i>
                                Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editEntry" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content" id="editEntryContent">

                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{ asset("assets/jquery-number/jquery.number.js") }}"></script>
    <script>
        function find_item(x){
            $.ajax({
                url : "{{ route('ar.find') }}/"+x,
                type: "get",
                success: function(response){
                    $("#editEntryContent").html(response)
                    $("select.select2").select2({
                        width: "100%"
                    })
                }
            })
        }
        function revise_approve(x){
            Swal.fire({
                title: "Approve",
                text: "Are you sure you want to delete?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Approve",
                cancelButtonText: "Cancel",
                reverseButtons: true,
            }).then(function(result){
                if(result.value){
                    $.ajax({
                        url: "{{route('ar.revise.approve')}}/" + x,
                        type: "get",
                        dataType: "json",
                        cache: false,
                        success: function(response){
                            if (response.error == 0) {
                                location.reload()
                            } else {
                                Swal.fire({
                                    title: "Error Occured",
                                    text: "Please contact your administrator",
                                    icon: "error"
                                })
                            }
                        }
                    })
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
                        url: "{{URL::route('ar.delete_entry')}}/" + x,
                        type: "get",
                        dataType: "json",
                        cache: false,
                        success: function(response){
                            if (response.error == 0) {
                                location.reload()
                            } else {
                                Swal.fire({
                                    title: "Error Occured",
                                    text: "Please contact your administrator",
                                    icon: "error"
                                })
                            }
                        }
                    })
                }
            })
        }

        function pad(num, size) {
            num = num.toString();
            while (num.length < size) num = "0" + num;
            return num;
        }

        $(document).ready(function(){
            $("select.select2").select2({
                width: "100%"
            })
            $("table.display").DataTable({
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            })

            $("input[name=date]").change(function(){
                var due_date = $("input[name=due_date]")
                var date = new Date($(this).val())
                var new_date = new Date(date.setMonth(date.getMonth() + 3))
                var due_date_val = new_date.getFullYear()+"-"+pad(new_date.getMonth(), 2)+"-"+pad(new_date.getDate(), 2)
                console.log(due_date_val)
                due_date.prop('readonly', false)
                due_date.attr('min', $(this).val())
                due_date.val(due_date_val)
            })
        })
    </script>
@endsection
