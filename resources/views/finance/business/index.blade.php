@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Business</h3><br>

            </div>
            @actionStart('business', 'create')
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-warning font-weight-bold dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="flaticon2-group"></i>Partners & Investors
                        </button>
                        <div class="dropdown-menu bg-warning" aria-labelledby="btnGroupDrop1">
                            <a class="dropdown-item text-white bg-hover-dark-o-1" href="{{ route('business.partners') }}">Partners</a>
                            <a class="dropdown-item text-white bg-hover-dark-o-1" href="{{route('business.investors')}}">Investors</a>
                        </div>
                    </div>
                     <div class="btn-group" role="group">
                        <button id="btnGroupDrop2" type="button" class="btn btn-info font-weight-bold dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-dollar-sign"></i>Payment Reports
                        </button>
                        <div class="dropdown-menu bg-info" aria-labelledby="btnGroupDrop2">
                            <a class="dropdown-item text-white bg-hover-dark-o-1" href="{{route('business.balance')}}">Balance Investor</a>
                            <a class="dropdown-item text-white bg-hover-dark-o-1" href="{{route('business.balance.partners')}}">Balance Partners</a>
                            <a class="dropdown-item text-white bg-hover-dark-o-1" href="{{route('business.payment_schedule')}}">Payment Schedule</a>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addItem"><i class="fa fa-plus"></i>Add Business</button>
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
                    <th class="text-left" style="width: 15%">Partner Name</th>
                    <th class="text-left">Project Name</th>
                    <th class="text-center">Currency</th>
                    <th class="text-center">Invested Amount</th>
                    <th class="text-center">Start Payment</th>
                    <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>
                @actionStart('business', 'read')
                @foreach($business as $key => $value)
                    <tr>
                        <td align="center">{{$key+1}}</td>
                        <td>
                            {{ (isset($partners[$value->partner])) ? $partners[$value->partner] : "" }}
                        </td>
                        <td>
                            <a href="{{route('business.detail', $value->id)}}" class="label label-inline label-primary btn">{{$value->bank}}</a>
                        </td>
                        <td align="center">{{$value->currency}}</td>
                        <td align="right">{{number_format($value->value, 2)}}</td>
                        <td align="center">{{date('d F Y', strtotime($value->start))}}</td>
                        <td align="center">
                            <button type="button" onclick="button_edit({{$value->id}})" class="btn btn-xs btn-icon btn-primary"><i class="fa fa-edit"></i></button>
                            @actionStart('business', 'delete')
                            <button type="button" onclick="button_delete('{{$value->id}}')" class="btn btn-xs btn-icon btn-danger"><i class="fa fa-trash"></i></button>
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
                    <h5 class="modal-title" id="exampleModalLabel">Add Business</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{URL::route('business.add')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h3>Basic Info</h3>
                                <hr>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Project Name</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" placeholder="Project Name" name="prj_name" required maxlength="15">
                                        <span class="text-danger">Max length is 15</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Partner Name</label>
                                    <div class="col-md-9">
                                        <select name="partner_name" class="form-control select2" id="" required>
                                            <option value="">Select Partner</option>
                                            @foreach ($partners as $id => $partnerName)
                                                <option value="{{ $id }}">{{ $partnerName }}</option>
                                            @endforeach
                                        </select>
                                        {{-- <input type="text" class="form-control" placeholder="Partner Name" name="partner_name" required> --}}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Invested Amount</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control number" placeholder="Invested Amount" name="amount" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Investment Interest Percentage %</label>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" placeholder="Investment Interest Percentage %" name="percentage" required>
                                        <span class="text-primary">(Fill with percent per month.)</span>
                                    </div>
                                    <label class="col-md-2 col-form-label">%</label>
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
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Return Duration</label>
                                    <div class="col-md-7">
                                        <input type="number" class="form-control" placeholder="Return Duration" name="duration" required>
                                    </div>
                                    <label class="col-md-2 col-form-label">Month(s)</label>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Account Information</label>
                                    <div class="col-md-7">
                                        <textarea name="account_info" class="form-control txt-tiny" id="" cols="30" rows="10"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h3>Details</h3>
                                <hr>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Business Investment Given at</label>
                                    <div class="col-md-9">
                                        <input type="date" class="form-control" placeholder="dd-mm-yyy" min="1997-01-01" name="given_at" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Return Payment Start Date</label>
                                    <div class="col-md-9">
                                        <input type="date" class="form-control" name="start_at" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Proportional Type</label>
                                    <div class="col-md-9">
                                        <select name="proportional" class="form-control select2" id="" required>
                                            <option value="">Select proportional Type</option>
                                            <option value="PRO">Proportional</option>
                                            <option value="LUM">Lumpsum</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Penalty</label>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control number" placeholder="Penalty" name="own_amount" required>
                                        <span class="text-primary">(per day)</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Penalty Remarks</label>
                                    <div class="col-md-9">
                                        <textarea name="own_remarks" class="form-control txt-tiny" id="" cols="30" rows="10"></textarea>
                                        <span class="text-primary">if penalty doesn't paid, the consequences are as described here.</span>
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
    <div class="modal fade" id="editBusiness" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content" id="edit-content">
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{asset('theme/assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js?v=7.0.5')}}"></script>
    <script src="{{ asset("theme/tinymce/tinymce.min.js") }}"></script>
    <script src="{{asset('assets/jquery-number/jquery.number.js')}}"></script>
    <script>
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
                        url: "{{URL::route('business.delete')}}/" + x,
                        type: "GET",
                        dataType: "json",
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
            $("#editBusiness").modal('show')
            $.ajax({
                url: "{{URL::route('business.edit')}}/" + x,
                type: "GET",
                cache: false,
                success: function(response){
                    $("#edit-content").html(response)
                    $("#edit-content select.select2").select2({
                        width: "100%"
                    })
                    tinymce.init({
                        selector : "#edit-content .txt-tiny",
                        menubar : false,
                        toolbar : false
                    })
                }
            })
        }

        $(document).ready(function(){

            tinymce.init({
                selector : ".txt-tiny",
                menubar : false,
                toolbar : false
            })

            $('#editBusiness').on('hidden.bs.modal', function () {
                $("#edit-content").html('')
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
