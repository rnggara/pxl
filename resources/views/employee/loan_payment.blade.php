@extends('layouts.template')
@section('content')
    <div class="row">
        <div class="card card-custom gutter-b col-md-5">
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bold text-dark">Employee Loan - <span class="text-primary">{{$loan->loan_id}}</span></h3>
            </div>
            <div class="card-body pt-2">
                <!--begin::Form-->
                <form class="form" id="kt_form_1">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" class="form-control border-0" name="name" value="Name" disabled/>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control border-0" name="name" value="{{$emp->emp_name}}" disabled/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" class="form-control border-0" name="name" value="Loan Amount" disabled/>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control border-0" name="name" value="IDR {{number_format($loan->loan_amount,2)}}" disabled/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" class="form-control border-0" name="name" value="Loan Start" disabled/>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control border-0" name="name" value="{{date('d F Y', strtotime($loan->loan_start))}}" disabled/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" class="form-control border-0" name="name" value="Loan End" disabled/>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control border-0" name="name" value="{{date('d F Y', strtotime($loan->loan_end))}}" disabled/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" class="form-control border-0" name="name" value="Paid" disabled/>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control border-0" name="name" value="IDR {{number_format(intval($loan->loan_amount) - intval($balance),2)}}" disabled/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" class="form-control border-0" name="name" value="Unpaid" disabled/>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control border-0" name="name" value="IDR {{number_format($balance,2)}}" disabled/>
                            </div>
                        </div>
                    </div>
                </form>
                <!--end::Form-->
            </div>
        </div>

        <div class="col-md-1"></div>
        @actionStart('emp_loan', 'create')
        <div class="card card-custom gutter-b col-md-6">
            <!--begin::Header-->
            <div class="card-header border-0">
                <h5 class="card-title text-dark">Payment</h5>
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body pt-2">
                <!--begin::Form-->
                <form class="form" id="kt_form_1" method="post" action="{{route('employee.loan.payment')}}">
                    @csrf
                    <input type="hidden" name="loan_id" value="{{$loan->id}}">
                    <div class="form-group">
                        <input type="number" class="form-control " name="amount" placeholder="Amount" />
                    </div>
                    <div class="form-group">
                        <input type="date" class="form-control " name="date_of_payment" placeholder="Date" />
                    </div>
                    <div class="form-group">
                        <textarea class="form-control " name="memo" rows="4" placeholder="Write here . ." id="kt_forms_widget_1_input"></textarea>
                    </div>
                    <div class="mt-10">
                        <input type="submit" name="submit" id="submit" class="btn btn-primary font-weight-bold note-icon-pull-right" value="Send">
                    </div>
                </form>
                <!--end::Form-->
            </div>
            <!--end::Body-->
        </div>
        @actionEnd
    </div>
    <div class="row">
        <div class="card card-custom gutter-b col-md-12">
            <!--begin::Header-->
            <div class="card-header border-0">
                <h5 class="card-title text-dark">Payment History</h5>
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body pt-2">
                <!--begin::Form-->
                <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th nowrap="nowrap" class="text-left">Payment#</th>
                                <th nowrap="nowrap" class="text-center">Payment Date</th>
                                <th nowrap="nowrap" class="text-right">Amount</th>
                                <th nowrap="nowrap" class="text-right">Balance</th>
                                <th nowrap="nowrap" class="text-center">Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                        @php
                            $balance_num = intval($loan->loan_amount);
                        @endphp
                        @foreach($payments as $key => $value)
                            <tr>
                                <th>{{($key+1)}}</th>
                                <th>{{$value->payment_id}}</th>
                                <th class="text-center">{{date('d F Y', strtotime($value->date_of_payment))}}</th>
                                <th class="text-right">IDR {{number_format($value->amount,2)}}</th>
                                @php
                                    $balance_num -= intval($value->amount);
                                @endphp
                                <th class="text-right">IDR {{number_format($balance_num,2)}}</th>
                                <th class="text-center">{{$value->remark}}</th>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!--end::Form-->
            </div>
            <!--end::Body-->
    </div>



@endsection
@section('custom_script')
            <script>
                $(document).ready(function () {
                    $('.display').DataTable({
                        responsive: true,
                        fixedHeader: {
                            headerOffset: 90
                        },
                        pageLength: 100
                    })
                });
            </script>
@endsection
