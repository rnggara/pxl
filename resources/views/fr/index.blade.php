@extends('layouts.template')
@section('content')
@actionStart('fr', 'access')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>List Item Request</h3><br>

            </div>
            @actionStart('fr', 'create')
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addItem"><i class="fa fa-plus"></i>Item Request</button>
                </div>
                <!--end::Button-->
            </div>
            @actionEnd
        </div>
        <div class="card-body">
            <div class="row mb-5 mt-5">
                <div class="col-md-12">
                    <img src="{{asset('media/ir.png')}}" alt="" style="width: 35%">
                </div>
            </div>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                @actionStart('frwaiting', 'access')
                <li class="nav-item">
                    <a class="nav-link active" onclick="reload_source('waiting')" id="home-tab" data-toggle="tab" href="#all">
                        <span class="nav-icon">
                            <i class="flaticon-folder-1"></i>
                        </span>
                        <span class="nav-text">IR Waiting</span>
                    </a>
                </li>
                @actionEnd
                @actionStart('frbank', 'access')
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" onclick="reload_source('bank')" data-toggle="tab" href="#sales" aria-controls="profile">
                        <span class="nav-icon">
                            <i class="flaticon-folder-2"></i>
                        </span>
                        <span class="nav-text">IR Bank</span>
                    </a>
                </li>
                @actionEnd
                @actionStart('frrejected', 'access')
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" onclick="reload_source('reject')" data-toggle="tab" href="#cost" aria-controls="profile">
                        <span class="nav-icon">
                            <i class="flaticon-folder-3"></i>
                        </span>
                        <span class="nav-text">IR Rejected</span>
                    </a>
                </li>
                @actionEnd
            </ul>
            <div class="tab-content mt-5" id="myTabContent">
                <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="home-tab">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <table class="table table-bordered table-responsive-xl table-hover display font-size-sm" id="table-list" style="margin-top: 13px !important; width: 100%;">
                            <thead>
                            <tr id="table-head">
                                <th class="text-center">#</th>
                                <th class="text-left">Request Date</th>
                                <th class="text-center">IR Code</th>
                                <th class="text-center">Request by</th>
                                <th class="text-center">Division</th>
                                <th class="text-left">Project</th>
                                <th class="text-center">Company</th>
                                <th class="text-center">Item(s)</th>
                                <th class="text-center">Division Approval</th>
                                <th class="text-center">Asset Approval</th>
                                <th class="text-center">Delivery Status</th>
                                <th class="text-center"></th>
                            </tr>
                            </thead>

                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="salest" role="tabpanel" aria-labelledby="profile-tab">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <table class="table table-bordered table-hover display font-size-sm frbank" style="margin-top: 13px !important; width: 100%;">
                            <thead class="table-success">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-left">Request Date</th>
                                <th class="text-center">IR Code</th>
                                <th class="text-center">Request by</th>
                                <th class="text-center">Division</th>
                                <th class="text-left">Project</th>
                                <th class="text-center">Company</th>
                                <th class="text-center">Item(s)</th>
                                <th class="text-center">Division Approval</th>
                                <th class="text-center">Asset Approval</th>
                                <th class="text-center">Delivery Status</th>
                                <th class="text-center"></th>
                            </tr>
                            </thead>

                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="costt" role="tabpanel" aria-labelledby="contact-tab">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <table class="table table-bordered table-hover display font-size-sm frreject" style="margin-top: 13px !important; width: 100%;">
                            <thead class="table-danger">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-left">Request Date</th>
                                <th class="text-center">IR Code</th>
                                <th class="text-center">Request by</th>
                                <th class="text-center">Division</th>
                                <th class="text-left">Project</th>
                                <th class="text-center">Company</th>
                                <th class="text-center">Item(s)</th>
                                <th class="text-center">Division Reject</th>
                                <th class="text-center">Asset Reject</th>
                                <th class="text-center"></th>
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
                <form method="post" id="form-add" action="{{URL::route('fr.add')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <span class="form-text text-dark">Please kindly fill in the form below for your requested asset.The form will be used by Asset Division to check for the availability in the warehouse.</span>
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
                                    <label class="col-md-2 col-form-label text-right">Division Type</label>
                                    <div class="col-md-6">
                                        @if (!empty($division))
                                            <input type="text" name="division" id="division" class="form-control" readonly value="{{ $division->name }}" required>
                                        @else
                                            <select name="division" class="form-control select2" id="division" required>
                                                <option value="">Select Division</option>
                                                @foreach ($div as $divs)
                                                    <option value="{{ $divs->name }}">{{ $divs->name }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">FR Type</label>
                                    <div class="col-md-6">
                                        <select name="fr_type" class="form-control select2" required>
                                            <option value="">Choose here</option>
                                            @foreach ($po_type as $item)
                                                <option value="{{ $item->name }}">[{{ $item->code }}] {{ strtoupper($item->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Request Date</label>
                                    <div class="col-md-6">
                                        <input type="date" name="request_date" id="request_time" value="{{ date("Y-m-d") }}" readonly class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Due Date</label>
                                    <div class="col-md-6">
                                        <input type="date" name="due_date" id="due_date" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Project Category</label>
                                    <div class="col-md-6">
                                        <select name="category" id="pr_cat" class="form-control select2">
                                            <option value="">Choose</option>
                                            <option value="cost">cost</option>
                                            <option value="sales">sales</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" id="opt">
                                    <label class="col-md-2 col-form-label text-right">Project</label>
                                    <div class="col-md-6">
                                        <select name="project" id="project" class="form-control" required>

                                        </select>
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
                                    <label class="col-md-2 col-form-label text-right">Purpose & Notes</label>
                                    <div class="col-md-6">
                                        <textarea name="notes" id="fr_note" cols="30" rows="10" class="form-control" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4>Request Item</h4>
                                <hr>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group row">
                                            <table class="table table-bordered" id="list_item">
                                                <thead>
                                                <tr>
                                                    <th>Item Code</th>
                                                    <th>Item Name</th>
                                                    <th>UoM</th>
                                                    <th>Quantity</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tr>
                                                    <td colspan="2"  width="450">
                                                        <div class="form-group" id="div-target">
                                                            <form class="eventInsForm" action="#" method="post">
                                                                <input type="text" style="width:100%" class="form-control" id="item" placeholder="Item Name/Code" />
                                                            </form>
                                                            <input type="hidden" id="id" />
                                                            <input type="hidden" id="code" />
                                                            <input type="hidden" id="name" />
                                                            <input type="hidden" id="category" />
                                                            <div id="autocomplete-div"></div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <span id="uom"></span>
                                                    </td>
                                                    <td class="text-center"><input type="number" size="2" id="qty" placeholder="Qty" class="form-control" /></td>
                                                    <td class="text-center">
                                                        <input type="button" class="btn btn-primary btn-md" value="Add" onClick="addInput('list_item');"/>
                                                    </td>
                                                </tr>
                                            </table>
                                            <span class="form-text text-muted">* UoM is Unit of Measurement</span>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-4 col-form-label text-right">Justification</label>
                                            <div class="col-md-6">
                                                <input type="file" name="justification" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-12">
                                        <label for="" class="label label-inline label-light-info">If you cannot find the item, insert the item you want below</label>
                                    </div>
                                    <hr>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="" class="col-form-label col-sm-3">Item Name</label>
                                            <div class="col-sm-12">
                                                <input type="text" id="item-name" class="form-control form-item-wrapper" name="new_item_name">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="" class="col-form-label col-sm-3">Quantity</label>
                                                    <div class="col-sm-12">
                                                        <input type="number" id="item-qty" min="0" class="form-control form-item-wrapper" name="new_item_qty">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="" class="col-form-label col-sm-3">UoM</label>
                                                    <div class="col-sm-12">
                                                        <select name="new_item_uom" id="item-uom" class="form-control select2 form-item-wrapper">
                                                            <option value="">Select UoM</option>
                                                            @foreach ($uom as $item)
                                                                <option value="{{ $item }}">{{ $item }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-12 text-right">
                                                <button type="button" class="btn btn-success" onclick="add_new_item()"><i class="fa fa-plus"></i> Add Item</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="scroll scroll-pull" data-scroll="true" data-wheel-propagation="true" style="height: 200px">
                                            <table class="table table-bordered table-hover" id="table-add-item">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">#</th>
                                                        <th class="text-center">Item Name</th>
                                                        <th class="text-center">UoM</th>
                                                        <th class="text-center">Quantity</th>
                                                        <th class="text-center"></th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" id="btn-submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Save</button>
                        <button type="submit" id="btn-submit-post" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            </button>
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

        function deleteRow(o){
            var p = o.parentNode.parentNode;
            p.parentNode.removeChild(p);
        }
        function addInput(trName) {
            var newrow = document.createElement('tr');
            if ($("#code").val() === '') {
                Swal.fire('Not found', 'There is no item selected', 'error')
            } else {
                newrow.innerHTML = "<td align='center'>" +
                "<input type='hidden' name='id_item[]' value='" + $("#id").val() + "'>" +
                "<input type='hidden' class='input_code' name='code[]' value='" + $("#code").val() + "'>" + $("#code").val() +
                "</td>" +
                "<td align='center'>" +
                "<input type='hidden' name='name[]' value='" + $("#name").val() + "'><b>" + $("#name").val() + "</b><br /><em style='font-size:9px'>" + $("#category").val() + "</em>" +
                "</td>" +
                "<td align='center'>" +
                "<input type='hidden' name='uom[]' value='" + $("#uom").val() + "'>" + $("#uom").val() +
                "</td>" +
                "<td align='center'>" +
                "<input type='hidden' name='qty[]' value='" + $("#qty").val() + "'>" + $("#qty").val() +
                "</td>" +
                "<td align='center'>" +
                "<button type='submit' onClick='deleteRow(this)' class='btn btn-xs btn-danger'><i class='fa fa-trash'></i></button>" +
                "</td>";
            document.getElementById(trName).appendChild(newrow);
            }

            $("#item").val("");
            $("#uom").html("");
            $("#qty").val("");
        }

        var type = "waiting"

        function reload_source(x){
            type = x
            var route = "{{ route('pre.list') }}/fr/"+type
            $("#table-list").DataTable().destroy()
            table_list()
        }

        function table_list(){
            var route = "{{ route('pre.list') }}/fr/"+type

            var th = $("#table-head")
            if(type == "waiting"){
                $(th).css('background-color', '#96caff')
            } else if (type == "bank"){
                $(th).css('background-color', '#88e1dd')
            } else {
                $(th).css('background-color', '#faa3ac')
            }

            table = $("#table-list").DataTable({
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                },
                pageLength: 25,
                ajax : {
                    url : route,
                    type : "get"
                },
                columns : [
                    { "data" : "i"},
                    { "data" : "date"},
                    { "data" : "paper"},
                    { "data" : "req_by"},
                    { "data" : "division"},
                    { "data" : "project"},
                    { "data" : "company"},
                    { "data" : "items"},
                    { "data" : "div_appr"},
                    { "data" : "asset_appr"},
                    { "data" : "delivery_status"},
                    { "data" : "action"},
                ],
                columnDefs : [
                    {"targets" :"_all", "className" : "text-center"}
                ],
                initComplete: function(settings, json){

                }
            })
        }

        var table_item = $("#table-add-item").DataTable({
            paging : false,
            searching : false,
            bInfo : false,
            lengthChange: false,
            columnDefs : [
                {targets : [0, 2, 3, 4], className : "text-center"}
            ]
        })

        var new_item = []

        var counter = 1;
        function add_new_item(){
            var inputs = $(".form-item-wrapper")
            var msg = ""
            var item_name = $("#item-name").val()
            var uom = $("#item-uom").val()
            var qty = $("#item-qty").val()
            inputs.each(function(){
                if(this.value == "" || this.value == 0 || this.value === '0' ){
                    var name = $(this).attr('name').replaceAll("_", " ")
                    msg += name+" cannot be empty<br>"
                }
            })

            if(msg != ""){
                Swal.fire('Alert', msg, 'warning')
            } else {
                var item = []
                item['name'] = $("#item-name").val()
                item['uom'] = $("#item-uom").val()
                item['qty'] = $("#item-qty").val()
                new_item.push(item)
                table_counter = counter
                table_name = item_name + "<input type='hidden' name='item_n_name[]' value='"+item_name+"'>"
                table_uom = uom + "<input type='hidden' name='item_n_uom[]' value='"+uom+"'>"
                table_qty = qty + "<input type='hidden' name='item_n_qty[]' value='"+qty+"'>"
                table_action = '<button type="button" class="btn btn-xs btn-icon btn-danger" onclick="remove_item(this)"><i class="fa fa-trash"></i></button>'
                table_item.row.add([
                    table_counter,
                    table_name,
                    table_uom,
                    table_qty,
                    table_action
                ]).draw(false)

                counter++

                $("#item-name").val('')
                $("#item-uom").val('').trigger('change')
                $("#item-qty").val('')
                $("#item-name").focus()
            }
        }

        function remove_item(btn){
            table_item.row($(btn).parents('tr')).remove().draw()
            counter--;
            console.log(counter)
        }

        $(document).ready(function(){

            $("select.select2").select2({
                width : "100%"
            })

            table_list()
            $("#btn-submit-post").hide()
            $("#item").autocomplete({
                source: "{{route('fr.getItems')}}",
                minLength: 1,
                appendTo: "#autocomplete-div",
                select: function(event, ui) {
                    $('#category').val(ui.item.item_category);
                    $('#id').val(ui.item.item_id);
                    $('#code').val(ui.item.item_code);
                    $('#name').val(ui.item.item_name);
                    $('#uom').val(ui.item.item_uom);
                    $('#uom').html(ui.item.item_uom);
                }
            });

            $("#btn-submit").click(function(e){
                e.preventDefault()
                $(this).prop('disabled', true)
                var submit = true
                Swal.fire({
                    title: "Are you sure?",
                    text: "Save it!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes"
                }).then(function(result) {
                    if (result.value) {
                        var division = $.trim($('#division').val());
                        var request_time = $.trim($('#request_time').val());
                        var due_date = $.trim($('#due_date').val());
                        var pr_cat = $.trim($('#pr_cat').val());
                        var project = $.trim($('#project').val());
                        var fr_note = $.trim($('#fr_note').val());
                        var inp_code = $('.input_code').toArray();


                        if (division  === '') { alert('Division is mandatory.'); submit = false; }
                        if (request_time  === '') { alert('Request Date is mandatory.'); submit = false; }
                        if (due_date  === '') { alert('Due Date is mandatory.'); submit = false; }
                        if (pr_cat  === '') { alert('Project Category is mandatory.'); submit = false; }
                        if (project  === '') { alert('Project is mandatory.'); submit = false; }
                        if (fr_note  === '') { alert('Note is mandatory.'); submit = false; }
                        if(inp_code.length === 0){
                            if(counter === 1){
                                alert('Items is mandatory.');
                                submit = false;
                            } else {
                                submit = true
                            }
                        }
                        console.log(submit)
                        if(submit === false){
                            $("#btn-submit").prop('disabled', false)
                        } else {
                            $("#btn-submit-post").click()
                            document.getElementById('form-add').submit()
                        }
                    } else {
                        $(this).prop('disabled', false)
                    }
                });
            })
        });

        $("#pr_cat").change(function(){
            cat = $("#pr_cat").val();
            $('#opt').show();
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
@actionEnd
