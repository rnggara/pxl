@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Invoice In</h3><br>

            </div>
            <div class="card-toolbar">
                <div class="row">
                    <div class="col-md-7">
                        <div class="input-group">
                            <input type="text" class="form-control" id="search-paper" placeholder="Search paper">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-info btn-sm" id="btn-search-paper"><i class="fa fa-search"></i>Search</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button type="button" class="btn btn-primary btn-md" data-toggle="modal" data-target="#addItem"><i class="fa fa-plus"></i>Add Invoice</button>
                        </div>
                    </div>
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
                        <span class="nav-text">List</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#po" aria-controls="profile">
                        <span class="nav-icon">
                            <i class="flaticon-folder-2"></i>
                        </span>
                        <span class="nav-text">PO</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#wo" aria-controls="profile">
                        <span class="nav-icon">
                            <i class="flaticon-folder-3"></i>
                        </span>
                        <span class="nav-text">WO</span>
                    </a>
                </li>
            </ul>
            <div class="tab-content mt-5" id="myTabContent">
            <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="home-tab">
                <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
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
                            <th class="text-center"></th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($inv_in as $key => $value)
                                <tr>
                                    <td align="center">{{$key + 1}}</td>
                                    <td>{{(isset($paper['paper_num'][$value->paper_type][$value->paper_id])) ? $paper['paper_num'][$value->paper_type][$value->paper_id] : ""}}</td>
                                    <td align="center">
                                    {{(isset($paper['supplier'][$value->paper_type][$value->paper_id]) && isset($supplier[$paper['supplier'][$value->paper_type][$value->paper_id]])) ? $supplier[$paper['supplier'][$value->paper_type][$value->paper_id]]['name'] : ""}}</td>
                                    <td align="center">{!!(isset($paper['supplier'][$value->paper_type][$value->paper_id]) && isset($supplier[$paper['supplier'][$value->paper_type][$value->paper_id]])) ? $supplier[$paper['supplier'][$value->paper_type][$value->paper_id]]['bank_acct'] : ""!!}</td>
                                    <td align="center">{{(isset($paper['currency'][$value->paper_id])) ? $paper['currency'][$value->paper_id] : ""}}</td>
                                    <td align="right">{{number_format($value->amount, 2)}}</td>
                                    <td align="center">{{date("d F Y", strtotime($value->app_date))}}</td>
                                    <td align="center">
                                        {{(isset($paper['gr_date'][$value->paper_id]) && $paper['gr_date'][$value->paper_id] != null) ? date('d F Y', strtotime($paper['gr_date'][$value->paper_id])) : "N/A"}}
                                    </td>
                                    <td align="center">
                                        @if($value->pay_date != null)
                                            {{date('d F Y', strtotime($value->pay_date))}}
                                        @else
                                            <form method="post" action="{{URL::route('inv_in.duedate')}}">
                                                @csrf
                                                <div class="row input-group">
                                                <input name='id' type='hidden' id='id' value='{{$value->id}}' />
                                                <input name='tgl' type='hidden' id='tgl' value='{{$value->app_date}}' /><br />
                                                <input type="text" class="form-control" size='3' name="select" value="COD">
                                                <div class="input-group-append">
                                                    <button type="submit" class="btn btn-xs btn-success"><i class="fa fa-save"></i></button>
                                                </div>
                                            </div>
                                            </form>
                                        @endif
                                    </td>
                                    <td align="center">
                                        @if($value->plan_date == null)
                                            @if(isset($pay[$value->id]))
                                                {{date("d F Y", strtotime($pay[$value->id][count($pay[$value->id]) - 1]->pay_date))}}
                                            @else
                                                N/A
                                            @endif
                                        @else
                                            {{date('d F Y', strtotime($value->plan_date))}}
                                        @endif
                                    </td>
                                    <td align="center">
                                        @if($value->pay_date != null)
                                            <a href="{{URL::route('inv_in.view', $value->id)}}" class="btn btn-xs btn-primary"><i class="fa fa-search"></i> View</a>
                                        @else
                                            <i class="font-size-sm">Please insert the payment date first.</i>
                                        @endif
                                    </td>
                                    <td align="center">
                                        <button type="button" onclick="button_delete({{$value->id}})" class="btn btn-xs btn-icon btn-danger"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade " id="po" role="tabpanel" aria-labelledby="home-tab">
                <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Paper Number</th>
                            <th class="text-center">Supplier</th>
                            <th class="text-center">Bank Account</th>
                            <th class="text-center">Currency</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center">Input Date</th>
                            <th class="text-center">GR Date</th>
                            <th class="text-center">Due Date</th>
                            <th class="text-center">Payment Date</th>
                            <th class="text-center">Payment History</th>
                            <th class="text-center"></th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php $i1 = 0; ?>
                            @foreach($inv_in as $key => $value)
                                @if($value->paper_type == "PO")
                                    <tr>
                                    <td align="center">{{$i1 + 1}} <?php $i1++; ?></td>
                                    <td>{{(isset($paper['paper_num'][$value->paper_type][$value->paper_id])) ? $paper['paper_num'][$value->paper_type][$value->paper_id] : ""}}</td>
                                    <td align="center">{{(isset($paper['supplier'][$value->paper_id]) && isset($supplier[$paper['supplier'][$value->paper_id]])) ? $supplier[$paper['supplier'][$value->paper_id]]['name'] : ""}}</td>
                                    <td align="center">{{(isset($paper['supplier'][$value->paper_id]) && isset($supplier[$paper['supplier'][$value->paper_id]])) ? $supplier[$paper['supplier'][$value->paper_id]]['bank_acct'] : ""}}</td>
                                    <td align="center">{{(isset($paper['currency'][$value->paper_id])) ? $paper['currency'][$value->paper_id] : ""}}</td>
                                    <td align="right">{{number_format($value->amount, 2)}}</td>
                                    <td align="center">{{date("d F Y", strtotime($value->app_date))}}</td>
                                    <td align="center">
                                        {{(isset($paper['gr_date'][$value->paper_id]) && $paper['gr_date'][$value->paper_id] != null) ? date('d F Y', strtotime($paper['gr_date'][$value->paper_id])) : "N/A"}}
                                    </td>
                                    <td align="center">
                                        @if($value->pay_date != null)
                                            {{date('d F Y', strtotime($value->pay_date))}}
                                        @else
                                            <form method="post" action="{{URL::route('inv_in.duedate')}}">
                                                @csrf
                                                <div class="row input-group">
                                                <input name='id' type='hidden' id='id' value='{{$value->id}}' />
                                                <input name='tgl' type='hidden' id='tgl' value='{{$value->app_date}}' /><br />
                                                <input type="text" class="form-control" size='3' name="select" value="COD">
                                                <div class="input-group-append">
                                                    <button type="submit" class="btn btn-xs btn-success"><i class="fa fa-save"></i></button>
                                                </div>
                                            </div>
                                            </form>
                                        @endif
                                    </td>
                                    <td align="center">
                                        @if($value->plan_date == null)
                                            @if(isset($pay[$value->id]))
                                                {{date("d F Y", strtotime($pay[$value->id][count($pay[$value->id]) - 1]->pay_date))}}
                                            @else
                                                N/A
                                            @endif
                                        @else
                                            date('d F Y', strtotime($value->plan_date))
                                        @endif
                                    </td>
                                    <td align="center">
                                        @if($value->pay_date != null)
                                            <a href="{{URL::route('inv_in.view', $value->id)}}" class="btn btn-xs btn-primary"><i class="fa fa-search"></i> View</a>
                                        @else
                                            <i class="font-size-sm">Please insert the payment date first.</i>
                                        @endif
                                    </td>
                                    <td align="center">
                                        <button type="button" onclick="button_delete({{$value->id}})" class="btn btn-xs btn-icon btn-danger"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade " id="wo" role="tabpanel" aria-labelledby="home-tab">
                <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Paper Number</th>
                            <th class="text-center">Supplier</th>
                            <th class="text-center">Bank Account</th>
                            <th class="text-center">Currency</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center">Input Date</th>
                            <th class="text-center">GR Date</th>
                            <th class="text-center">Due Date</th>
                            <th class="text-center">Payment Date</th>
                            <th class="text-center">Payment History</th>
                            <th class="text-center"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i2 = 0; ?>
                        @foreach($inv_in as $key => $value)
                            @if($value->paper_type == "WO")
                                <tr>
                                    <td align="center">{{$i2 + 1}} <?php $i2++; ?></td>
                                    <td>{{(isset($paper['paper_num'][$value->paper_type][$value->paper_id])) ? $paper['paper_num'][$value->paper_type][$value->paper_id] : ""}}</td>
                                    <td align="center">{{(isset($paper['supplier'][$value->paper_id]) && isset($supplier[$paper['supplier'][$value->paper_id]])) ? $supplier[$paper['supplier'][$value->paper_id]]['name'] : ""}}</td>
                                    <td align="center">{{(isset($paper['supplier'][$value->paper_id]) && isset($supplier[$paper['supplier'][$value->paper_id]])) ? $supplier[$paper['supplier'][$value->paper_id]]['bank_acct'] : ""}}</td>
                                    <td align="center">{{(isset($paper['currency'][$value->paper_id])) ? $paper['currency'][$value->paper_id] : ""}}</td>
                                    <td align="right">{{number_format($value->amount, 2)}}</td>
                                    <td align="center">{{date("d F Y", strtotime($value->app_date))}}</td>
                                    <td align="center">
                                        {{(isset($paper['gr_date'][$value->paper_id]) && $paper['gr_date'][$value->paper_id] != null) ? date('d F Y', strtotime($paper['gr_date'][$value->paper_id])) : "N/A"}}
                                    </td>
                                    <td align="center">
                                        @if($value->pay_date != null)
                                            {{date('d F Y', strtotime($value->pay_date))}}
                                        @else
                                            <form method="post" action="{{URL::route('inv_in.duedate')}}">
                                                @csrf
                                                <div class="row input-group">
                                                <input name='id' type='hidden' id='id' value='{{$value->id}}' />
                                                <input name='tgl' type='hidden' id='tgl' value='{{$value->app_date}}' /><br />
                                                <input type="text" class="form-control" size='3' name="select" value="COD">
                                                <div class="input-group-append">
                                                    <button type="submit" class="btn btn-xs btn-success"><i class="fa fa-save"></i></button>
                                                </div>
                                            </div>
                                            </form>
                                        @endif
                                    </td>
                                    <td align="center">
                                        {{($value->plan_date == null) ? "N/A" : date('d F Y', strtotime($value->plan_date))}}
                                    </td>
                                    <td align="center">
                                        @if($value->pay_date != null)
                                            <a href="{{URL::route('inv_in.view', $value->id)}}" class="btn btn-xs btn-primary"><i class="fa fa-search"></i> View</a>
                                        @else
                                            <i class="font-size-sm">Please insert the payment date first.</i>
                                        @endif
                                    </td>
                                    <td align="center">
                                        <button type="button" onclick="button_delete({{$value->id}})" class="btn btn-xs btn-icon btn-danger"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            </div>
            {{--            <h5><span class="span">This page contains a list of Travel Order which has been formed.</span></h5>--}}

        </div>
    </div>
    <div class="modal fade" id="addItem" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Invoice </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="">Insert a paper number for invoicing - <span class="text-primary">You must insert full paper code. e.g 020/PSI/WO/X/11</span></label>
                    <div class="alert alert-primary">
                        <i class="fa fa-info-circle text-white"></i>
                        <label for="">The number you inserted may result in paper that are already inserted to invoice in or not yet inserted.</label>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="txt-search" placeholder="e.g 020/PSI/WO/X/11" name="search" required>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-success btn-xs" id="btn-search"><i class="fa fa-search"></i> Search</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalResult" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Paper Result - <span id="paper-title" class="text-primary"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-primary">
                        <button type="button" class="btn btn-success btn-xs"> Insert new one</button>
                    </div>
                    <hr>
                    <div class="alert alert-secondary">
                        <h3 id="type"></h3>
                        <div class="m-5 row">
                            <div class="col-md-6">
                                <table>
                                    <tr>
                                        <td>Supplier</td>
                                        <td>:</td>
                                        <td><b><span id="supplier"></span></b></td>
                                    </tr>
                                    <tr>
                                        <td>Address</td>
                                        <td>:</td>
                                        <td><span id="address"></span></td>
                                    </tr>
                                    <tr>
                                        <td>Telephone</td>
                                        <td>:</td>
                                        <td><span id="telephone"></span></td>
                                    </tr>
                                    <tr>
                                        <td>Web</td>
                                        <td>:</td>
                                        <td><span id="web"></span></td>
                                    </tr>
                                    <tr>
                                        <td>PIC</td>
                                        <td>:</td>
                                        <td><span id="pic"></span></td>
                                    </tr>
                                    <tr>
                                        <td>Bank Account</td>
                                        <td>:</td>
                                        <td><span id="bank_acct"></span></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table>
                                    <tr>
                                        <td>Deliver To</td>
                                        <td>:</td>
                                        <td><b><span id="deliver_to"></span></b></td>
                                    </tr>
                                    <tr>
                                        <td>Paper Number</td>
                                        <td>:</td>
                                        <td><span id="num"></span></td>
                                    </tr>
                                    <tr>
                                        <td>Request Date</td>
                                        <td>:</td>
                                        <td><span id="request_date"></span></td>
                                    </tr>
                                    <tr>
                                        <td>Division</td>
                                        <td>:</td>
                                        <td><span id="division"></span></td>
                                    </tr>
                                    <tr>
                                        <td>Reference</td>
                                        <td>:</td>
                                        <td><span id="reference"></span></td>
                                    </tr>
                                    <tr>
                                        <td>Project</td>
                                        <td>:</td>
                                        <td><span id="project"></span></td>
                                    </tr>
                                    <tr>
                                        <td>Currency</td>
                                        <td>:</td>
                                        <td><span id="currency"></span></td>
                                    </tr>
                                    <tr>
                                        <td>Notes</td>
                                        <td>:</td>
                                        <td><span id="notes"></span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="m-5 row" id="detail">
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="alert alert-primary">
                        <i class="fa fa-info-circle text-white"></i> Select between two button below to insert the paper code to invoice in or to cash on delivery invoice.
                        <div class="row mt-2">
                            <form method="post" action="{{URL::route('inv_in.add')}}">
                                @csrf
                                <input type="hidden" id="id_p" name="id_p">
                                <input type="hidden" id="t" name="t">
                                <input type="hidden" id="p" name="p">
                                <input type="hidden" id="dp_val" name="dp">
                                <input type="hidden" id="amount" name="amount">
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <select name="_tc" class="form-control my-2" id="sel-tc" data-placeholder="Transaction Code" required>
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" name="in" class="btn btn-success btn-xs mr-2"><i class="fa fa-check"></i> Insert to INVOICE IN</button>
                                <button type="submit" name="cod" value="1" class="btn btn-info btn-xs"><i class="fa fa-check"></i> Insert as Cash On Delivery (COD) invoice</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="jsvendor" value="{{json_encode($jsonvendor)}}">
    <input type="hidden" id="jsprjname" value="{{json_encode($jsonprjname)}}">
@endsection

@section('custom_script')
    <script src="{{asset('theme/assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js?v=7.0.5')}}"></script>
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
                        url: "{{URL::route('inv_in.delete')}}",
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
                                    title: "Error Occured",
                                    icon: "error"
                                })
                            }
                        }
                    })
                }
            })
        }
        $(document).ready(function(){
            var jsonvendor = JSON.parse($("#jsvendor").val())
            var jsonprj = JSON.parse($("#jsprjname").val())
            var prjname = JSON.parse(jsonprj)
            var vendordata = JSON.parse(jsonvendor)
            // console.log(vendordata)

            $("#btn-search-paper").click(function(){
                $.ajax({
                    url: "{{URL::route('inv_in.search')}}",
                    type: 'post',
                    dataType: 'json',
                    cache: false,
                    data: {
                        '_token': '{{csrf_token()}}',
                        'key': $("#search-paper").val()
                    },
                    success: function(response){
                        if (response.status === 2){
                            location.href = "{{route('inv_in.view')}}/"+response.id
                        } else {
                            Swal.fire('Not found', "The paper is not found", "error")
                        }
                    }
                })
            })

            $("#btn-search").click(function(){
                $.ajax({
                    url: "{{URL::route('inv_in.search')}}",
                    type: 'post',
                    dataType: 'json',
                    cache: false,
                    data: {
                        '_token': '{{csrf_token()}}',
                        'key': $("#txt-search").val()
                    },
                    success: function(response){
                        if (response.status == 1){
                            var jsondata = JSON.parse(response.data)
                            $("#addItem").modal('hide')
                            $('#modalResult').modal({backdrop: 'static', keyboard: false})
                            $("#modalResult").modal('show')
                            switch (response.type) {
                                case "WO" :
                                    var type = "WORK ORDER"
                                    var title = jsondata.wo_num
                                    var req_date = jsondata.req_date
                                    break;
                                case "PO" :
                                    var type = "PURCHASE ORDER"
                                    var title = jsondata.po_num
                                    var req_date = jsondata.po_date
                            }
                            $("#id_p").val(jsondata.id)
                            $("#t").val(response.type)
                            $("#p").val(jsondata.project)
                            $("#dp_val").val(jsondata.dp)
                            $("#amount").val(response.amount)

                            $("#type").text(type)
                            $("#paper-title").text(title)
                            $("#supplier").text(vendordata.name[jsondata.supplier_id])
                            $("#address").html(vendordata.address[jsondata.supplier_id])
                            $("#telephone").text(vendordata.telephone[jsondata.supplier_id])
                            $("#web").text(vendordata.web[jsondata.supplier_id])
                            $("#pic").text(vendordata.pic[jsondata.supplier_id])
                            $("#bank_acct").html(vendordata.bank_acct[jsondata.supplier_id])

                            $("#deliver_to").text(removeTags(jsondata.deliver_to))
                            $("#num").text(title)
                            $("#request_date").text(req_date)
                            $("#division").text(jsondata.division)
                            $("#reference").text(jsondata.reference)
                            $("#project").text(prjname[jsondata.project])
                            $("#currency").text(jsondata.currency)
                            $("#notes").text(removeTags(jsondata.notes))
                            $("div[id=detail]").html(response.table)
                            $("#sel-tc").select2({
                                width : "100%",
                                ajax : {
                                    url : "{{ route('inv_in.tc') }}/"+response.type,
                                    dataType : "json",
                                    type : "get"
                                }
                            })

                            if(response.tc_id != null){
                                var newOption = new Option(response.tc_text, response.tc_id, true);
                                $("#sel-tc").append(newOption).trigger('change')
                            }
                        } else {
                            var title = 'Error('+response.status+')'
                            Swal.fire(title, response.messages, 'error')
                        }
                    }
                })
            })

            function removeTags(str) {
                if ((str===null) || (str===''))
                    return false;
                else
                    str = str.toString();

                // Regular expression to identify HTML tags in
                // the input string. Replacing the identified
                // HTML tag with a null string.
                return str.replace( /(<([^>]+)>)/ig, '');
            }

            $("table.display").DataTable({
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                },
                pageLength: 50
            })
            $("select.select2").select2({
                width: "100%"
            })
        })
    </script>
@endsection
