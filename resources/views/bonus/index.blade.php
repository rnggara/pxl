@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                Bonus
            </div>
            <div class="card-toolbar">
                @actionStart('bonus', 'create')
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addEmployee"><i class="fa fa-plus"></i>Add Bonus</button>
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
                        <th>#</th>
                        <th nowrap="nowrap" class="text-center">ID#</th>
                        <th nowrap="nowrap" style="width: 20%">Name</th>
                        <th nowrap="nowrap" class="text-right">Bonus Amount</th>
                        <th nowrap="nowrap" class="text-center">Bonus Start</th>
                        <th nowrap="nowrap" class="text-center">First Payment</th>
                        <th nowrap="nowrap" class="text-center">Bonus End</th>
                        <th nowrap="nowrap" class="text-right">Balance</th>
                        <th nowrap="nowrap" class="text-center">Payment</th>
                        <th nowrap="nowrap" data-priority=1>#</th>
                    </tr>
                    </thead>
                    <tbody>
                        @actionStart('bonus', 'read')
                        @foreach($bonus as $key => $value)
                            <tr>
                                <td>{{($key+1)}}</td>
                                <td class="text-center">{{$value->bonusID}}</td>
                                <td>{{$value->emp_name}}</td>
                                <td class="text-right">{{number_format($value->bonus_amount,2)}}</td>
                                <td class="text-center">{{date('d F Y',strtotime($value->bonus_start))}}</td>
                                @php
                                    $date_pay = '';
                                @endphp
                                @foreach($payments as $key3 => $datepay)
                                    @if($datepay->bonus_id == $value->id)
                                        @php
                                            $date_pay = date('d F Y',strtotime($datepay->date_of_payment));
                                        @endphp
                                    @endif
                                @endforeach
                                <td class="text-center">
                                    {{$date_pay}}
                                </td>
                                <td class="text-center">{{date('d F Y',strtotime($value->bonus_end))}}</td>
                                @php
                                    $balance = intval($value->bonus_amount);
                                @endphp
                                @foreach($payments as $key2 => $payment)
                                    @if($value->id == $payment->bonus_id)
                                        @php
                                            $balance-= intval($payment->amount);
                                        @endphp
                                    @endif
                                @endforeach
                                @if($balance < 100)
                                    @php
                                        $balance = 0;
                                    @endphp
                                @endif
                                <td class="text-right">
                                    {{number_format($balance,2)}}
                                </td>
                                <td class="text-center">
                                    <a href="{{route('subsidies.payment',['id' => $value->id])}}" class="btn btn-primary btn-xs">
                                        <i class="fa fa-money-bill-wave"></i>&nbsp;&nbsp;Payment
                                    </a>
                                </td>
                                <td class="text-center">
                                    <form method="post" action="{{route('subsidies.delete',['id' => $value->id])}}">
                                        @csrf
                                        @actionStart('bonus', 'delete')
                                        <button type="submit" class="btn btn-xs btn-icon btn-default" onclick="return confirm('Hapus data pegawai?');">
                                            <i class="fa fa-trash text-danger"></i>
                                        </button>
                                        @actionEnd
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        @actionEnd
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addEmployee" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Bonus</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('subsidies.store')}}" >
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="form col-md-6">
                                <div class="form-group">
                                    <label>Choose Employee</label>
                                    <select class="form-control select2" id="emp_name" name="emp_name">
                                        @foreach($employees as $key => $value)
                                            <option value="{{$value->id}}">{{$value->emp_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Subsidi Start</label>
                                    <input type="date" class="form-control" name="start" placeholder="Subsidi Start" />
                                </div>
                                <div class="form-group">
                                    <label>Subsidi End</label>
                                    <input type="date" class="form-control" name="end" placeholder="Subsidi End" />
                                </div>
                                <div class="form-group">
                                    <label>Subsidi Amount</label>
                                    <input type="number" class="form-control" name="amount" />
                                </div>
                            </div>
                            <div class="form col-md-6">
                                <div class="form-group">
                                    <label>Notes</label>
                                    <textarea class="form-control" name="notes"></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="checkbox-inline">
                                        <p>
                                            <input type="checkbox" name="autopay" id="" value="on">
                                            &nbsp;&nbsp;&nbsp;&nbsp; Auto payment
                                        </p>

                                    </label>
                                    <p>
                                        Check if it will be automated payments.
                                        This process will add data payment as much as the number of installment months
                                    </p>
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
            $('.display').DataTable({
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            });

            $("select.select2").select2({
                width: "100%"
            })
        });
    </script>
@endsection
