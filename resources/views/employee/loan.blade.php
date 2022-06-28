@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                Employee Loan
            </div>
            <div class="card-toolbar">
                @actionStart('emp_loan', 'create')
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addEmployee"><i class="fa fa-plus"></i>Add New Loan</button>
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
                        <th nowrap="nowrap" class="text-center">Loan ID#</th>
                        <th nowrap="nowrap" style="width: 30%">Employee Name</th>
                        <th nowrap="nowrap" class="text-right">Loan Amount</th>
                        <th nowrap="nowrap" class="text-center">Loan Start</th>
                        <th nowrap="nowrap" class="text-center">Loan End</th>
                        <th nowrap="nowrap" class="text-center">Unpaid</th>
                        <th nowrap="nowrap" class="text-center" data-priority=1>#</th>
                    </tr>
                    </thead>
                    <tbody>
                        @actionStart('emp_loan', 'read')
                    @foreach($loans as $key => $loan)
                        <?php
                        /** @var TYPE_NAME $loan */
                        /** @var TYPE_NAME $payments */
                            // if (empty($loan->old_id)){
                            //     $paid = (isset($payments[$loan->company_id][$loan->id])) ? array_sum($payments[$loan->company_id][$loan->id]) : 0;
                            //     $unpaid = $loan->loan_amount - $paid;
                            // } else {
                            //     $paid = (isset($payments[$loan->company_id][$loan->id])) ? array_sum($payments[$loan->company_id][$loan->id]) : 0;
                            //     $unpaid = $loan->loan_amount - $paid;
                            // }

                            $paid = (isset($payments[$loan->company_id][$loan->id])) ? array_sum($payments[$loan->company_id][$loan->id]) : 0;
                            $unpaid = $loan->loan_amount - $paid;
                        ?>
                            <tr>
                                <td>{{($key+1)}}</td>
                                <td class="text-center"><a href="{{route('employee.loan.detail',['id' => $loan->id])}}" class="btn btn-link"><i class="fa fa-search"></i>{{$loan->loan_id}}</a></td>
                                <td class="text-left">
                                    {{$data_emp[$loan->emp_id]->emp_name}}
                                </td>
                                <td class="text-right">{{number_format($loan->loan_amount,2)}}</td>
                                <td class="text-center">{{date('d F Y',strtotime($loan->loan_start))}}</td>
                                <td class="text-center">{{date('d F Y',strtotime($loan->loan_end))}}</td>
                                <td class="text-right">
                                    {{number_format($unpaid, 2)}}
                                </td>
                                <td class="text-center">
                                    @actionStart('emp_loan', 'delete')
                                    <form method="post" action="{{route('employee.loan.delete',['id' => $loan->id])}}">
                                        @csrf
                                        <button type="submit" class="btn btn-xs btn-icon btn-default" onclick="return confirm('Hapus data pinjaman?');">
                                            <i class="fa fa-trash text-danger"></i>
                                        </button>
                                    </form>
                                    @actionEnd
                                </td>
                            </tr>
{{--                        @endif--}}
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
                    <h5 class="modal-title" id="exampleModalLabel">Add Employee Loan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('employee.loan.store')}}" >
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="form col-md-6">
                                <div class="form-group">
                                    <label>Choose Employee</label>
                                    <select class="form-control select2" id="employee" name="employee">
                                        <option></option>
                                        @foreach($employees as $key => $emp)
                                            <option value="{{$emp->id}}">{{$emp->emp_name}}</option>
                                        @endforeach

                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Loan Start</label>
                                    <input type="date" required class="form-control" name="start" placeholder="Loan Start" />
                                </div>
                                <div class="form-group">
                                    <label>Loan End</label>
                                    <input type="date" required class="form-control" name="end" placeholder="Loan End" />
                                </div>
                                <div class="form-group">
                                    <label>Loan Amount</label>
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
                                        Check if it will be automated loan payments.
                                        This process will add data payment as much as the number of installment months every 17th
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
            $("table.display").DataTable({
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            })

            $("select.select2").select2({
                width: "100%"
            })
        });
    </script>
@endsection
