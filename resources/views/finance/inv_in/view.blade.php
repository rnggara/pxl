@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Payment History</h3><br>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{URL::route('inv_in.index')}}" class="btn btn-success btn-xs"><i class="fa fa-arrow-circle-left"></i></a>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div class="card card-custom bg-primary m-5">
                <div class="separator separator-solid separator-white opacity-20"></div>
                <div class="card-body text-white">
                    <table class="text-white">
                        <tr>
                            <td><b>ORDER NUMBER</b></td>
                            <td>:</td>
                            <td><b>{{(isset($paper['paper_num'][$inv->paper_type][$inv->paper_id])) ? $paper['paper_num'][$inv->paper_type][$inv->paper_id] : ""}}</b></td>
                        </tr>
                        <tr>
                            <td>Total Payment</td>
                            <td>:</td>
                            <td>{{number_format($inv->amount, 2)}}</td>
                        </tr>
                        <tr>
                            <td>Payment Credit</td>
                            <td>:</td>
                            <td>{{number_format($inv->amount - $paid, 2)}}</td>
                        </tr>
                        <tr>
                            <td>Transaction Code</td>
                            <td>:</td>
                            <td>
                                <a href="{{ route('coa.assign', ['type' => "invoice-in", 'id' => $inv->id]) }}" class="btn btn-light-primary btn-sm"><i class="fa fa-edit"></i> Set</a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="separator separator-dashed separator-border-2 separator-primary"></div>
            <div class="m-5">
                <table class="table display">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-right">Amount Paid</th>
                        <th class="text-center">Payment Date</th>
                        <th class="text-center">Description</th>
                        <th class="text-center">
                            @if($inv->amount - $paid > 0)
                                <button class="btn btn-icon btn-xs btn-primary" data-toggle="modal" data-target="#addItem"><i class="fa fa-plus"></i></button>
                            @endif
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($ipay as $key => $value)
                            <tr>
                                <td align="center">{{$key + 1}}</td>
                                <td align="right">{{number_format($value->amount)}}</td>
                                <td align="center">{{date("d F Y", strtotime($value->pay_date))}}</td>
                                <td align="center">{{strip_tags($value->description)}}</td>
                                <td align="center">
                                    @if ($value->paid == 0)
                                    <button type="button" onclick="button_delete({{$value->id}})" class="btn btn-danger btn-icon btn-xs"><i class="fa fa-trash"></i></button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal fade" id="addItem" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Payment </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                    </div>
                    <form action="{{URL::route('inv_in.pay')}}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group row">
                                <label for="" class="col-form-label col-md-3 text-right">Amount</label>
                                <div class="col-md-9">
                                    <input type="text" min="0" id="amount" max="{{$inv->amount}}" class="form-control number" placeholder="{{$inv->amount - $paid}}" name="amount" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-form-label col-md-3 text-right">Payment Date</label>
                                <div class="col-md-9">
                                    <input type="date" class="form-control" name="pay_date" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-form-label col-md-3 text-right">Description</label>
                                <div class="col-md-9">
                                    <textarea name="description" class="form-control" cols="30" rows="10"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="pay_num" value="{{count($ipay) + 1}}">
                            <input type="hidden" name="id" value="{{$inv->id}}">
                            <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                            <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                                <i class="fa fa-check"></i>
                                Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="assignTC" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Payment </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                    </div>
                    <form action="{{URL::route('inv_in.assign_tc')}}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group row">
                                <label for="" class="col-form-label col-md-3 text-right">Transaction Code</label>
                                <div class="col-md-9">
                                    <select name="_tc" class="form-control select2" data-placeholder="Select Transaction Code" required>
                                        <option value=""></option>
                                        @foreach ($tc as $item)
                                            <option value="{{ $item->id }}">[{{ $item->code }}] {{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="id" value="{{$inv->id}}">
                            <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                            <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                                <i class="fa fa-check"></i>
                                Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
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
                        url: "{{URL::route('inv_in.delete_pay')}}",
                        type: "POST",
                        dataType: "json",
                        data: {
                            '_token' : '{{csrf_token()}}',
                            'id' : x
                        },
                        cache: false,
                        success: function(response){
                            if (response.error == 0) {
                                location.reload()
                            } else {
                                Swal.fire({
                                    title: "Cannot delete",
                                    text: "Entry has been paid",
                                    icon: "error"
                                })
                            }
                        }
                    })
                }
            })
        }

        function _show_alert(){
            Swal.fire("TC Required", "Please assign TC for this paper!", 'warning')
        }
        $(document).ready(function(){
            $("#amount").change(function(){
                if ($(this).val() > {{$inv->amount}}){
                    Swal.fire('Alert', 'Max amount paid is {{number_format($inv->amount)}}', 'warning')
                    $(this).val({{$inv->amount}})
                }
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
                width : "100%"
            })
        })
    </script>
@endsection
