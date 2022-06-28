@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>List Item Request</h3><br>

            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addItem"><i class="fa fa-plus"></i>Form Request</button>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-5 mt-5">
                <div class="col-md-12">
                    <img src="{{asset('media/so.png')}}" style="width: 35%">
                </div>
            </div>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#all">
                        <span class="nav-icon">
                            <i class="flaticon-folder-1"></i>
                        </span>
                        <span class="nav-text">SO Waiting</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#sales" aria-controls="profile">
                        <span class="nav-icon">
                            <i class="flaticon-folder-2"></i>
                        </span>
                        <span class="nav-text">SO Bank</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#cost" aria-controls="profile">
                        <span class="nav-icon">
                            <i class="flaticon-folder-3"></i>
                        </span>
                        <span class="nav-text">SO Rejected</span>
                    </a>
                </li>
            </ul>
            <div class="tab-content mt-5" id="myTabContent">
                <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="home-tab">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <table class="table table-bordered table-hover display font-size-sm sowaiting" style="margin-top: 13px !important; width: 100%;">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th nowrap="nowrap" class="text-center">SO #</th>
                                <th nowrap="nowrap" class="text-center">SO Date</th>
                                <th nowrap="nowrap" class="text-center">SO Type</th>
                                <th nowrap="nowrap" class="text-center">Request By</th>
                                <th nowrap="nowrap" class="text-center">Division</th>
                                <th nowrap="nowrap" class="text-center">Project</th>
                                <th class="text-center">Company</th>
                                <th nowrap="nowrap" class="text-center">Item(s)</th>
                                <th nowrap="nowrap" class="text-left">Notes</th>
                                <th nowrap="nowrap" class="text-center">Div. Manager Approval</th>
                                <th nowrap="nowrap" class="text-center"></th>
                            </tr>
                            </thead>

                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="sales" role="tabpanel" aria-labelledby="profile-tab">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <table class="table table-bordered table-hover display font-size-sm sobank" style="margin-top: 13px !important; width: 100%;">
                            <thead class="table-success">
                            <tr>
                                <th>#</th>
                                <th nowrap="nowrap" class="text-center">SO #</th>
                                <th nowrap="nowrap" class="text-center">SO Date</th>
                                <th nowrap="nowrap" class="text-center">SO Type</th>
                                <th nowrap="nowrap" class="text-center">Request By</th>
                                <th nowrap="nowrap" class="text-center">Division</th>
                                <th nowrap="nowrap" class="text-center">Project</th>
                                <th class="text-center">Company</th>
                                <th nowrap="nowrap" class="text-center">Item(s)</th>
                                <th nowrap="nowrap" class="text-center">Notes</th>
                                <th nowrap="nowrap" class="text-center">Div. Manager Approval</th>
                                <th nowrap="nowrap" class="text-center"></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="cost" role="tabpanel" aria-labelledby="contact-tab">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <table class="table table-bordered table-hover display font-size-sm soreject" style="margin-top: 13px !important; width: 100%;">
                            <thead class="table-danger">
                            <tr>
                                <th>#</th>
                                <th nowrap="nowrap" class="text-center">SO #</th>
                                <th nowrap="nowrap" class="text-center">SO Date</th>
                                <th nowrap="nowrap" class="text-center">SO Type</th>
                                <th nowrap="nowrap" class="text-center">Request By</th>
                                <th nowrap="nowrap" class="text-center">Division</th>
                                <th nowrap="nowrap" class="text-center">Project</th>
                                <th class="text-center">Company</th>
                                <th nowrap="nowrap" class="text-center">Item(s)</th>
                                <th nowrap="nowrap" class="text-center">Notes</th>
                                <th nowrap="nowrap" class="text-center">Div. Manager Approval</th>
                                <th nowrap="nowrap" class="text-center"></th>
                            </tr>
                            </thead>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addItem" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Request Form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" id="form-add" action="{{URL::route('so.add')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <span class="form-text text-muted">Please kindly fill in the form below for your requested asset.The form will be used by Asset Division to check for the availability in the warehouse.</span>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Request By</h4>
                                <hr>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Name</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="Name" name="request_by" value="{{Auth::user()->username}}" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">SO Type</label>
                                    <div class="col-md-6">
                                        <select name="so_type" id="so_type" class="form-control select2">
                                            <option value="">Select Type</option>
                                            @foreach($type_wo as $value)
                                                <option value="{{$value->name}}">{{$value->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Request Date</label>
                                    <div class="col-md-6">
                                        <input type="date" name="request_date" id="request_time" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Payment Method</label>
                                    <div class="col-md-6 col-form-label">
                                        <div class="checkbox-inline">
                                            <label class="checkbox checkbox-outline checkbox-success">
                                                <input type="checkbox" name="payment_method"/>
                                                <span></span>
                                                BACK DATE
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Division</label>
                                    <div class="col-md-6">
                                        <select name="division" id="division" class="form-control">
                                            <!-- CONSULTANT 13-09-2018 Aldi-->
                                            <!-- ========================================================================================================== -->
                                            <!-- ========================================================================================================== -->

                                            <option value="">Select Division</option>
                                            <option value="Asset">Asset</option>
                                            <option value="Consultant">Consultant</option>
                                            <option value="Finance">Finance</option>
                                            <option value="GA">GA</option>
                                            <option value="HRD">HRD</option>
                                            <option value="IT">IT</option>
                                            <option value="Laboratory">Laboratory</option>
                                            <option value="Maintenance">Maintenance</option>
                                            <option value="Marketing">Marketing</option>
                                            <option value="Operation">Operation</option>
                                            <option value="Procurement">Procurement</option>
                                            <option value="Production">Production</option>
                                            <option value="QC">QC</option
                                            ><option value="QHSSE">QHSSE</option>
                                            <option value="Receiptionist">Receiptionist</option>
                                            <option value="Secretary">Secretary</option>
                                            <option value="Technical">Technical</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Reference</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="reference" name="reference">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Notes</label>
                                    <div class="col-md-6">
                                        <textarea name="notes" id="fr_note" cols="30" rows="10" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Project</label>
                                    <div class="col-md-6">
                                        <select name="project" id="project" class="form-control">
                                            <option value="">Select Project</option>
                                            @foreach($project as $pro)
                                                <option value="{{$pro->id}}">{{$pro->prj_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Deliver To</label>
                                    <div class="col-md-6">
                                        <textarea name="d_to" cols="30" rows="10" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Deliver Time</label>
                                    <div class="col-md-6">
                                        <textarea name="d_time" cols="30" rows="10" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4>Request Item</h4>
                                <hr>
                                <div class="form-group row">
                                    <table class="table table-bordered" id="list_item">
                                        <thead>
                                        <tr>
                                            <th>Job Description</th>
                                            <th>Quantity</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tr>
                                            <td width="450">
                                                <input type="text" style="width:100%" class="form-control" id="item" placeholder="Job Description" />
                                            </td>
                                            <td class="text-center"><input type="number" size="2" id="qty" placeholder="Qty" class="form-control" /></td>
                                            <td class="text-center">
                                                <input type="button" class="btn btn-primary btn-md" value="Add" onClick="addInput('list_item');"/>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
<style>
    .select2-results__options {
        max-height: 500px;
    }
</style>
@section('custom_script')
    <script src="{{asset('theme/assets/js/pages/crud/forms/widgets/typeahead.js?v=7.0.5')}}"></script>
    <link href="{{asset('theme/jquery-ui/jquery-ui.css')}}" rel="Stylesheet"></link>
    <script src="{{asset('theme/jquery-ui/jquery-ui.js')}}"></script>
    <script>
        $('#opt').hide();
        var cat;
        var srcItem = [];
        $('#form-add').submit(function () {
            var division = $.trim($('#division').val());
            var request_time = $.trim($('#request_time').val());
            var so_type = $.trim($('#so_type').val());
            var project = $.trim($('#project').val());
            var fr_note = $.trim($('#fr_note').val());
            var inp_code = $(".input_code").toArray();

            if (division  === '') { alert('Division is mandatory.'); return false; }
            if (so_type  === '') { alert('SO Type is mandatory.'); return false; }
            if (request_time  === '') { alert('Request Date is mandatory.'); return false; }
            if (project  === '') { alert('Project is mandatory.'); return false; }
            if (fr_note  === '') { alert('Note is mandatory.'); return false; }
            if (inp_code.length  === 0) { alert('Item is mandatory.'); return false; }
        });

        $(document).ready(function(){
            $("select.form-control").select2({
                width: '100%'
            })
        });

        function deleteRow(o){
            var p = o.parentNode.parentNode;
            p.parentNode.removeChild(p);
        }
        function addInput(trName) {
            var newrow = document.createElement('tr');
            newrow.innerHTML = "<td align='center'>" +
                "<input type='hidden' name='name[]' class='input_code' value='" + $("#item").val() + "'><b>" + $("#item").val() + "</b><br />" +
                "</td>" +
                "<td align='center'>" +
                "<input type='hidden' name='qty[]' value='" + $("#qty").val() + "'>" + $("#qty").val() +
                "</td>" +
                "<td align='center'>" +
                "<button type='submit' onClick='deleteRow(this)' class='btn btn-xs btn-danger'><i class='fa fa-trash'></i></button>" +
                "</td>";
            document.getElementById(trName).appendChild(newrow);
            $("#item").val("");
            $("#qty").val("");
        }

        $(document).ready(function(){
            $("table.sowaiting").DataTable({
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                },
                'ajax': '{{route('so.waiting')}}',
                'type': 'GET',
                dataSrc: 'responseData',
                'columns' :[
                    { "data": "no" },
                    { "data": "so_num" },
                    { "data": "so_date" },
                    { "data": "so_type" },
                    { "data": "created_by" },
                    { "data": "division" },
                    { "data": "project" },
                    { "data": "company" },
                    { "data": "items" },
                    { "data": "notes" },
                    { "data": "appr" },
                    { "data": "action" },
                ],
                'columnDefs': [
                    {
                        "targets": 1,
                        "className": "text-center",
                    },
                    {
                        "targets": 2,
                        "className": "text-center",
                    },
                    {
                        "targets": 3,
                        "className": "text-center",
                    },
                    {
                        "targets": 4,
                        "className": "text-center",
                    },
                    {
                        "targets": 5,
                        "className": "text-center",
                    },
                    {
                        "targets": 6,
                        "className": "text-center",
                    },
                    {
                        "targets": 7,
                        "className": "text-center",
                    },
                    {
                        "targets": 8,
                        "className": "text-center",
                    },
                    {
                        "targets": 10,
                        "className": "text-center",
                    },
                    {
                        "targets": 11,
                        "className": "text-center",
                    },

                ],
            });
            $("table.sobank").DataTable({
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                },
                'ajax': '{{route('so.bank')}}',
                'type': 'GET',
                dataSrc: 'responseData',
                'columns' :[
                    { "data": "no" },
                    { "data": "so_num" },
                    { "data": "so_date" },
                    { "data": "so_type" },
                    { "data": "created_by" },
                    { "data": "division" },
                    { "data": "project" },
                    { "data": "company" },
                    { "data": "items" },
                    { "data": "notes" },
                    { "data": "appr" },
                    { "data": "action" },
                ],
                'columnDefs': [
                    {
                        "targets": 1,
                        "className": "text-center",
                    },
                    {
                        "targets": 2,
                        "className": "text-center",
                    },
                    {
                        "targets": 3,
                        "className": "text-center",
                    },
                    {
                        "targets": 4,
                        "className": "text-center",
                    },
                    {
                        "targets": 5,
                        "className": "text-center",
                    },
                    {
                        "targets": 6,
                        "className": "text-center",
                    },
                    {
                        "targets": 7,
                        "className": "text-center",
                    },
                    {
                        "targets": 8,
                        "className": "text-center",
                    },
                    {
                        "targets": 10,
                        "className": "text-center",
                    },
                    {
                        "targets": 11,
                        "className": "text-center",
                    },

                ],
            });
            $("table.soreject").DataTable({
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                },
                'ajax': '{{route('so.rejected')}}',
                'type': 'GET',
                dataSrc: 'responseData',
                'columns' :[
                    { "data": "no" },
                    { "data": "so_num" },
                    { "data": "so_date" },
                    { "data": "so_type" },
                    { "data": "created_by" },
                    { "data": "division" },
                    { "data": "project" },
                    { "data": "company" },
                    { "data": "items" },
                    { "data": "notes" },
                    { "data": "appr" },
                    { "data": "action" },
                ],
                'columnDefs': [
                    {
                        "targets": 1,
                        "className": "text-center",
                    },
                    {
                        "targets": 2,
                        "className": "text-center",
                    },
                    {
                        "targets": 3,
                        "className": "text-center",
                    },
                    {
                        "targets": 4,
                        "className": "text-center",
                    },
                    {
                        "targets": 5,
                        "className": "text-center",
                    },
                    {
                        "targets": 6,
                        "className": "text-center",
                    },
                    {
                        "targets": 7,
                        "className": "text-center",
                    },
                    {
                        "targets": 8,
                        "className": "text-center",
                    },
                    {
                        "targets": 10,
                        "className": "text-center",
                    },
                    {
                        "targets": 11,
                        "className": "text-center",
                    },

                ],
            });
        });

        function getURLProject(){
            var url = "{{URL::route('fr.getProject',['cat' => ':id1'])}}";
            url = url.replace(':id1', cat);
            return url;
        }

        $('#project').select2({
            ajax: {
                url: function (params) {
                    return getURLProject()
                },
                type: "GET",
                placeholder: 'Choose Project',
                allowClear: true,
                dataType: 'json',
                data: function (params) {
                    return {
                        searchTerm: params.term,
                        "_token": "{{ csrf_token() }}",
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: false
            },
            width:"100%"
        })

    </script>
@endsection
