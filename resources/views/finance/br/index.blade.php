@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <div class="row">
                    Budget Request
                </div>
            </div>
            <div class="card-toolbar">
                <div class="input-group">
                    <select name="" class="form-control select2" id="search">
                        <option value="all">All BR</option>
                        <option value="waiting">Waiting Approval BR</option>
                        <option value="monthly">Monthly BR</option>
                        @foreach($divisions as $div)
                            <option value="{{$div->id}}" {!! (\Session::get('select') == $div->id) ? "SELECTED" : "" !!}>Cost {{$div->name}}</option>
                        @endforeach
                    </select>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-primary" id="btn-search"><i class="fa fa-search"></i></button>
                    </div>
                </div>

                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div class="row" id="search-form">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="" class="col-md-3 col-form-label">Search FROM</label>
                        <div class="col-md-3">
                            <select name="" class="form-control select2" id="mnth">
                                @foreach($mnths as $i => $val)
                                    <option value="{{$i}}" {{($i == date('m')) ? "SELECTED" : ""}}>{{$val}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="number" class="form-control" id="year" value="{{date('Y')}}">
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-success" id="btn-search-from"><i class="fa fa-search"></i> Search</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title" id="title-br">Budget Request List</h3>
            <div class="card-toolbar">
                <div id="request-form">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#requestForm"><i class="fa fa-plus"></i>Add Request</button>
                    </div>
                    <div class="modal fade" id="requestForm" tabindex="-1" role="dialog" aria-labelledby="requestForm" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Add Budget Request - <span id="modal-title-request"></span> </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <i aria-hidden="true" class="ki ki-close"></i>
                                    </button>
                                </div>
                                <form method="post" action="{{route('finance.br.post_request')}}" >
                                    @csrf
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                    <label for="" class="col-form-label col-md-3">Request Date</label>
                                                    <div class="col-md-9">
                                                        <input type="date" class="form-control" name="input_date" required>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="" class="col-form-label col-md-3">Due Date</label>
                                                    <div class="col-md-9">
                                                        <input type="date" class="form-control" name="due_date" required>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="" class="col-form-label col-md-3">Subject</label>
                                                    <div class="col-md-9">
                                                        <input type="text" class="form-control" name="subject" required>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="" class="col-form-label col-md-3">Currency</label>
                                                    <div class="col-md-9">
                                                        <select name="currency" class="form-control select2" id="" style="width: 100%" required>
                                                            <option value="IDR" selected>IDR - Indonesian Rupiah</option>
                                                            <option value="USD">USD - American Dollar</option>
                                                            <option value="SGD">SGD - Singapore Dollar</option>
                                                            <option value="EUR">EUR - Euro</option>
                                                            <option value="GBP">GBP - Great Britain Pondsterling</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="hidden" name="company_id" value="{{\Session::get('company_id')}}">
                                        <input type="hidden" name="division" id="division">
                                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                                            <i class="fa fa-check"></i>
                                            Next</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <table class="table table-bordered table-hover font-size-sm" id="table-br" style="margin-top: 13px !important; width: 100%;">
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
                        <th class="text-center"></th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script>
        var sel, type = "";
        function delete_item(x) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{route('asset.legal.delete')}}/"+x,
                        type: "get",
                        dataType: "json",
                        success: function (response) {
                            if (response.delete === 1){
                                location.reload()
                            } else {
                                Swal.fire('Error occured', "Please contact your system administration", 'error')
                            }
                        }
                    })
                }
            })
        }
        function form_div(){
            var searchForm = ["all", "waiting", "monthly"]
            var val = $("#search option:selected").val()
            var search = $("#search option:selected").html()
            if (searchForm.includes(val)){
                $("#search-form").show()
                $("#request-form").hide()
                $("#title-br").html('Budget Request List')
                $("#modal-title-request").html("")
                $("#division").val("")
            } else {
                $.ajax({
                    url: "{{route('finance.br.check')}}/"+val,
                    type: "get",
                    dataType: "json",
                    success: function (response) {
                        if (response.error === 0){
                            var data = response.data
                            if (data.unlocked === 1){
                                $("#request-form").show()
                            } else {
                                $("#request-form").hide()
                            }
                        } else {
                            $("#request-form").hide()
                        }
                    }
                })
                $("#search-form").hide()
                $("#title-br").html('Budget Cost Requisition - <span class="text-primary">' + search.replaceAll('Cost', '') + '</span>')
                $("#modal-title-request").html(search.replaceAll('Cost', ''))
                $("#division").val(val)
            }
            sel = val
        }

        function list_table(){
            $("#table-br").DataTable().destroy()
            var division = sel
            $('#table-br').DataTable({
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                },
                ajax: {
                    url:"{{route('finance.br.list')}}",
                    type: "post",
                    data: {
                        _token: "{{csrf_token()}}",
                        division: division,
                        month: $("#mnth").val(),
                        year: $("#year").val(),
                        _type: type
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
                    { "data" : "action" },
                ],
                columnDefs: [
                    { targets: '_all', className: "text-center" }
                ]
            });
        }

        $(document).ready(function () {
            form_div()
            $("#btn-search").click(function () {
                type = ""
                form_div()
                list_table()
            })

            $("#btn-search-from").click(function(){
                type = "from"
                list_table()
            })

            $("select.select2").select2()
            list_table()
        });
    </script>
@endsection
