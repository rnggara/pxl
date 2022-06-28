@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Filling Document</h3><br>

            </div>
            <div class="card-toolbar">
                <div class="input-group">
                    <select name="" id="view" class="form-control select2">
                        <option value="1">Filling BR</option>
                        <option value="2">Filling Invoice In</option>
                    </select>
                    <button type="button" class="btn btn-primary" id="btn-search"><i class="fa fa-search"></i></button>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            {{--table br--}}
            <table class="table table-hover table-bordered" id="table-br">
                <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">BR#</th>
                    <th class="text-center">Subject</th>
                    <th class="text-center">Amount</th>
                    <th class="text-center">Date</th>
                    <th class="text-center">Due Date</th>
                    <th class="text-center">BR Approved</th>
                    <th class="text-center">CEO Approval</th>
                    <th class="text-center">Budget Received</th>
                    <th class="text-center">Balance</th>
                    <th class="text-center">Balance Approved</th>
                    <th class="text-center">Balance Received</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

            {{--table inv in--}}
            <table class="table table-hover table-bordered" id="table-inv">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Paper Number</th>
                        <th class="text-center">Supplier</th>
                        <th class="text-center">Bank Account</th>
                        <th class="text-center">Currency</th>
                        <th class="text-right">Amount</th>
                        <th class="text-center">Input Date</th>
                        <th class="text-center">GR Date</th>
                        <th class="text-center">Due Date</th>
                        <th class="text-center">Payment Date</th>
                        <th class="text-center">Payment History</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{asset('theme/assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js?v=7.0.5')}}"></script>
    <script>

        function list_br(){
            $("#table-inv").DataTable().destroy()
            $("#table-inv").hide()
            $("#table-br").show()
            $("#table-br").DataTable().destroy()
            $('#table-br').DataTable({
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                },
                pageLength: 100,
                ajax: {
                    url:"{{route('finance.br.list')}}",
                    type: "post",
                    data: {
                        _token: "{{csrf_token()}}",
                        division: "all",
                        month: null,
                        year: null,
                        _type: null
                    }
                },
                columns : [
                    { "data" : "key" },
                    { "data" : "no" },
                    { "data" : "subject" },
                    { "data" : "amount" },
                    { "data" : "date" },
                    { "data" : "due_date" },
                    { "data" : "release" },
                    { "data" : "dirut" },
                    { "data" : "budget" },
                    { "data" : "balance_amount" },
                    { "data" : "balance" },
                    { "data" : "balance_recv" },
                ],
                columnDefs: [
                    { targets: '_all', className: "text-center" }
                ]
            });
        }
        function list_inv(){
            $("#table-br").DataTable().destroy()
            $("#table-br").hide()
            $("#table-inv").show()
            $("#table-inv").DataTable().destroy()
            $("#table-inv").DataTable({
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                },
                pageLength: 100,
                ajax: {
                    url:"{{route('inv_in.items')}}",
                    type: "post",
                    data: {
                        _token: "{{csrf_token()}}",
                    }
                },
                columns : [
                    { "data" : "i" }, //0
                    { "data" : "paper" }, //1
                    { "data" : "supplier" }, //2
                    { "data" : "bank_account" }, //3
                    { "data" : "currency" },//4
                    { "data" : "amount" },//5
                    { "data" : "input_date" },//6
                    { "data" : "gr_date" },//7
                    { "data" : "due_date" },//8
                    { "data" : "payment_date" },//9
                    { "data" : "payment_history" },//10
                ],
                columnDefs: [
                    { targets: [0,1,2,3,4,6,7,8,9,10], className: "text-center" },
                    { targets: [5], className: "text-right" }
                ]
            })
        }

        $(document).ready(function(){
            list_br()
            $("#btn-search").click(function () {
                var sel = $("#view option:selected").val()
                if (sel == 1){
                    list_br()
                } else {
                    list_inv()
                }
            })
            $("select.select2").select2()
        })
    </script>
@endsection
