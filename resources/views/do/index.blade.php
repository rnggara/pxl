@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
               <h3>List Delivery Order</h3> &nbsp;&nbsp;
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#modalreport"><i class="fa fa-chart-line"></i>&nbsp;Report</button>
                </div>
                <br>

            </div>
            @actionStart('do', 'create')
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{ route('general.driver.index') }}" class="btn btn-info btn-xs"><i class="fa fa-car"></i>Drivers</a>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addItem"><i class="fa fa-plus"></i>Add Delivery Order</button>
                </div>
                <!--end::Button-->
            </div>
            @actionEnd
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#all">
                        <span class="nav-icon">
                            <i class="flaticon-folder-1"></i>
                        </span>
                        <span class="nav-text">[DO] Delivery Order</span>
                    </a>
                </li>

{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#cost" aria-controls="profile">--}}
{{--                        <span class="nav-icon">--}}
{{--                            <i class="flaticon-folder-3"></i>--}}
{{--                        </span>--}}
{{--                        <span class="nav-text">Report</span>--}}
{{--                    </a>--}}
{{--                </li>--}}
            </ul>
            <div class="tab-content mt-5" id="myTabContent">
                <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="home-tab">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <table class="table table-bordered table-hover display font-size-sm " style="margin-top: 13px !important; width: 100%;">
                            <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">DO#</th>
                                <th class="text-center">Division</th>
                                <th class="text-center">From</th>
                                <th class="text-center">To</th>
                                <th class="text-center">Location</th>
                                <th class="text-center">Company</th>
                                <th class="text-center">Item(s)</th>
                                <th class="text-center">Delivery Date</th>
                                <th class="text-center">Delivery By</th>
                                <th class="text-center">Received By</th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @actionStart('do', 'read')
                            @foreach($dos as $key => $val)
                                <tr>
                                    <td class="text-center">{{$key+1}}</td>
                                    <td class="text-center"> <a href="{{route('do.detail',['id' => $val->id, 'type' => 'view'])}}" class="btn btn-link btn-xs"><i class="fa fa-search"></i>{{$val->no_do}}</a></td>
                                    <td class="text-center">{{$val->division}}</td>
                                    <td class="text-center">{{$val->whFromName}}</td>
                                    <td class="text-center">{{$val->whToName}}</td>
                                    <td class="text-center">{{$val->location}}</td>
                                    <td align="center">{{$view_company[$val->company_id]->tag}}</td>
                                    <td class="text-center">{{$val->items}}</td>
                                    <td class="text-center">{{date('d F Y', strtotime($val->deliver_date))}}</td>
                                    <td class="text-center">{{$val->deliver_by}}</td>
                                    <td class="text-center">
                                        @if($val->approved_by == null && $val->approved_time == null)
                                            <a href="{{route('do.detail',['id' => $val->id,'type' => 'appr'])}}" class="btn btn-link btn-xs"><i class="fa fa-clock"></i>waiting</a>
                                        @else
                                            {{$val->approved_by}} <br> {{date('d F Y', strtotime($val->approved_time))}}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @actionStart('do', 'update')
                                        <a href="{{route('do.detail',['id' => $val->id, 'type' => 'edit'])}}" class="btn btn-icon btn-primary btn-xs"><i class="fa fa-edit"></i></a>
                                        @actionEnd
                                        @actionStart('do', 'delete')
                                        <a href="{{route('do.delete',['id'=>$val->id])}}" class="btn btn-icon btn-danger btn-xs"  title="Delete" onclick="return confirm('Are you sure you want to delete?')"><i class="fa fa-trash"></i></a>
                                        @actionEnd
                                    </td>
                                </tr>
                            @endforeach
                            @actionEnd
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="cost" role="tabpanel" aria-labelledby="contact-tab">
                    <form class="form" action="{{route('bs.find')}}" method="post">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4">
                                <input type="date" name="from_date" id="start-date" class="form-control mr-3" value="{{date('Y')."-".date('m')."-01"}}">
                            </div>
                            <div class="col-md-4">
                                <input type="date" name="to_date" id="end-date" class="form-control" value="{{date('Y')."-".date('m')."-".date('t')}}">
                            </div>
                            <div class="col-md-2">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <button type="submit" id="btn-search" class="btn btn-primary" ><i class="fa fa-search"></i>Search</button>
                                    </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addItem" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Delivery Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" id="form-add" action="{{URL::route('do.add')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Detail</h4>
                                <hr>
                                <input type="hidden" name="deliver_by" value="{{Auth::user()->username}}">
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">From</label>
                                    <div class="col-md-6">
                                        <select name="from" id="from" class="form-control">

                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">To</label>
                                    <div class="col-md-6">
                                        <select name="to" id="to" class="form-control">

                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Location</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="location">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Division</label>
                                    <div class="col-md-6">
                                        <select name="division" id="division" class="form-control">
                                            <option value="">-Choose-</option>
                                            @foreach($divisions as $item)
                                                <option value="{{$item->name}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Delivery Time</label>
                                    <div class="col-md-6">
                                        <input type="date" name="delivery_time" id="delivery_time" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Notes</label>
                                    <div class="col-md-6">
                                        <textarea name="notes" id="fr_note" cols="30" rows="10" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4>Moving Item</h4>
                                <hr>
                                <div class="form-group row">
                                    <table class="table table-bordered" id="list_item">
                                        <thead>
                                        <tr>
                                            <th>Item Code</th>
                                            <th>Item Name</th>
                                            <th>UoM</th>
                                            <th>Quantity</th>
                                            <th>Transfer Type</th>
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
                                                <select class="form-control" name="transfer_type" id="transfer_type">
                                                    <option value="Transfer">Transfer</option>
                                                    <option value="Transfer & Use">Transfer & Use</option>
                                                </select>
                                                {{--                                                <input type="number" size="2" id="qty" placeholder="Qty" class="form-control" />--}}
                                            </td>
                                            <td class="text-center">
                                                <input type="button" class="btn btn-primary btn-md" value="Add" onClick="addInput('list_item');"/>
                                            </td>
                                        </tr>
                                    </table>
                                    <span class="form-text text-muted">* UoM is Unit of Measurement</span>
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
    <div class="modal fade" id="modalreport" tabindex="-1" role="dialog" aria-labelledby="modalreport" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Select Warehouse</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        @php
                            $count = 1;
                        @endphp
                        @for($i = 0; $i<count($wh_merge); $i++)
                            @php
                                /** @var TYPE_NAME $count */
                                /** @var TYPE_NAME $i */
                                $count += $i;
                            @endphp
                            @if(isset($wh_merge[$i]))
                            <a href="{{route('do.getWarehouseReport',['id_wh' =>$wh_merge[$i]])}}" class="btn btn-info p-lg-10"><i class="fa fa-warehouse"></i>{{(isset($wh_name[$wh_merge[$i]]))?$wh_name[$wh_merge[$i]]:''}}</a> &nbsp;&nbsp;
                            @if($count % 6 == 0)
                                <br>
                            @endif
                            @endif
                        @endfor
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                </div>

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
    {{-- <script src="{{asset('theme/assets/js/pages/crud/forms/widgets/typeahead.js?v=7.0.5')}}"></script> --}}
    <link href="{{asset('theme/jquery-ui/jquery-ui.css')}}" rel="Stylesheet"></link>
    <script src="{{asset('theme/jquery-ui/jquery-ui.js')}}"></script>
    <script>
        $('#form-add').submit(function () {
            var division = $.trim($('#division').val());
            var request_time = $.trim($('#delivery_time').val());
            var due_date = $.trim($('#from').val());
            var pr_cat = $.trim($('#to').val());

            if (division  === '') { alert('Division is mandatory.'); return false; }
            if (request_time  === '') { alert('Request Date is mandatory.'); return false; }
            if (due_date  === '') { alert('Due Date is mandatory.'); return false; }
            if (pr_cat  === '') { alert('Project Category is mandatory.'); return false; }
        });

        $(document).ready(function(){
            var wh_id
            $("#from").change(function(){
                wh_id = $(this).val()
                var url = "{{route('fr.getItems')}}?wh="+wh_id

                $("#item").autocomplete({
                    source: url,
                    minLength: 1,
                    appendTo: "#autocomplete-div",
                    select: function(event, ui) {
                        $.ajax({
                            url: "{{route('do.check_item')}}",
                            type: "post",
                            dataType: "json",
                            data: {
                                _token: "{{csrf_token()}}",
                                wh_id: $("#from option:selected").val(),
                                item: ui.item.item_id
                            },
                            success:function(response){
                                if (response.item == null){
                                    Swal.fire('Error', "Item is not found in the selected storage", 'error')
                                    $("#item").val('')
                                    $("#from").focus()
                                } else {
                                    $('#category').val(ui.item.item_category);
                                    $('#id').val(ui.item.item_id);
                                    $('#code').val(ui.item.item_code);
                                    $('#name').val(ui.item.item_name);
                                    $('#uom').val(ui.item.item_uom);
                                    $('#uom').html(ui.item.item_uom);
                                }
                            }
                        })
                    }
                });
            })


        });
        function deleteRow(o){
            var p = o.parentNode.parentNode;
            p.parentNode.removeChild(p);
        }
        function addInput(trName) {
            $.ajax({
                url: "{{route('do.check_item')}}",
                type: "post",
                dataType: "json",
                data: {
                    _token: "{{csrf_token()}}",
                    wh_id: $("#from option:selected").val(),
                    item: $('#id').val()
                },
                success:function(response){
                    if ($("#qty").val() > response.qty){
                        Swal.fire('Error', "Item quantity is exceed the maximum limit", 'error')
                        $("#qty").val('')
                        $("#qty").focus()
                    } else {
                        var newrow = document.createElement('tr');
                        newrow.innerHTML = "<td align='center'>" +
                            "<input type='hidden' name='id_item[]' value='" + $("#id").val() + "'>" +
                            "<input type='hidden' name='code[]' value='" + $("#code").val() + "'>" + $("#code").val() +
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
                            "<input type='hidden' name='transfer_type[]' value='" + $("#transfer_type").val() + "'>" + $("#transfer_type").val() +
                            "</td>" +
                            "<td align='center'>" +
                            "<button type='submit' onClick='deleteRow(this)' class='btn btn-xs btn-danger'><i class='fa fa-trash'></i></button>" +
                            "</td>";
                        document.getElementById(trName).appendChild(newrow);
                        $("#item").val("");
                        $("#uom").html("");
                        $("#qty").val("");
                    }
                }
            })
        }
        function getURLProject(){
            var url = "{{URL::route('do.getWh')}}";
            return url;
        }
        $(document).ready(function(){
            $("table.display").DataTable({
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            })
            $('#from').select2({
                ajax: {
                    url: function (params) {
                        return getURLProject()
                    },
                    type: "GET",
                    placeholder: 'From',
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
            $('#to').select2({
                ajax: {
                    url: function (params) {
                        return getURLProject()
                    },
                    type: "GET",
                    placeholder: 'To',
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
        });
    </script>
@endsection
