@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">List Drivers <a href="{{ route('general.driver.index') }}{{ (!$bank) ? "?view=bank" : "" }}" class="btn btn-{{ (!$bank) ? "success" : "primary" }} btn-sm ml-5">{{ ($bank) ? "List" : "Bank" }}</a></h3>
            <div class="card-toolbar">
                <div class="btn-group">

                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered table-hover table-responsive-sm table-responsive-md display">
                        <thead>
                            <tr {{ ($bank) ? "class=table-success" : "" }}>
                                <th class="text-center">#</th>
                                <th class="text-center">Driver Name</th>
                                <th class="text-center">NOPOL</th>
                                <th class="text-center">Car type</th>
                                <th class="text-center">Phone Number</th>
                                <th class="text-center">WA Number</th>
                                <th class="text-center">File</th>
                                <th class="text-center">DO#</th>
                                @if (!$bank)
                                    <th class="text-center">QC</th>
                                @endif
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($drivers as $i => $driver)
                                <tr>
                                    <td align="center">{{ $i+1 }}</td>
                                    <td align="center">
                                        <a href="#" data-toggle="modal" data-target="#modalId{{ $driver->id }}">{{ $driver->full_name }}</a>
                                        <div class="modal fade" id="modalId{{ $driver->id }}" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title">Driver Information</h1>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-6 text-left">
                                                                <div class="form-group row">
                                                                    <label for="" class="col-form-label col-md-3 col-sm-12">Nama Driver</label>
                                                                    <label for="" class="col-form-label col-md-9 col-sm-12 font-weight-bold">: {{ $driver->full_name ?? "-"}}</label>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="" class="col-form-label col-md-3 col-sm-12">Nopol Kendaraan</label>
                                                                    <label for="" class="col-form-label col-md-9 col-sm-12 font-weight-bold">: {{ $driver->nopol_kendaraan ?? "-"}}</label>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="" class="col-form-label col-md-3 col-sm-12">Jenis Kendaraan</label>
                                                                    <label for="" class="col-form-label col-md-9 col-sm-12 font-weight-bold">: {{ $driver->jenis_kendaraan ?? "-"}}</label>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="" class="col-form-label col-md-3 col-sm-12">Perusahaan</label>
                                                                    <label for="" class="col-form-label col-md-9 col-sm-12 font-weight-bold">: {{ $driver->perusahaan ?? "-"}}</label>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="" class="col-form-label col-md-3 col-sm-12">Email</label>
                                                                    <label for="" class="col-form-label col-md-9 col-sm-12 font-weight-bold">: {{ $driver->email ?? "-"}}</label>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="" class="col-form-label col-md-3 col-sm-12">Nomor Telpon</label>
                                                                    <label for="" class="col-form-label col-md-9 col-sm-12 font-weight-bold">: {{ $driver->no_telpon ?? "-"}}</label>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="" class="col-form-label col-md-3 col-sm-12">Nomor Whatsapp</label>
                                                                    <label for="" class="col-form-label col-md-9 col-sm-12 font-weight-bold">: {{ $driver->no_wa ?? "-"}}</label>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="" class="col-form-label col-md-3 col-sm-12">Nomor KTP</label>
                                                                    <label for="" class="col-form-label col-md-9 col-sm-12 font-weight-bold">: {{ $driver->no_id ?? "-"}}</label>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="" class="col-form-label col-md-3 col-sm-12">Nomor SIM</label>
                                                                    <label for="" class="col-form-label col-md-9 col-sm-12 font-weight-bold">: {{ $driver->no_sim ?? "-"}}</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                @if (isset($file[$driver->file_upload]))
                                                                    <img src="{{ str_replace("public", "public_html", asset($file[$driver->file_upload])) }}" class="max-w-180px">
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td align="center">{{ $driver->nopol_kendaraan }}</td>
                                    <td align="center">{{ strtoupper($driver->jenis_kendaraan) }}</td>
                                    <td align="center">{{ $driver->no_telpon }}</td>
                                    <td align="center"><a href="https://wa.me/62{{ ltrim($driver->no_wa, 0) }}" target="_blank">{{ $driver->no_wa }}</a></td>
                                    <td align="center">
                                        @if (!empty($driver->file_upload))
                                            <a href="{{ route('download', $driver->file_upload) }}" class="btn btn-primary btn-icon btn-xs"><i class="fa fa-download"></i></a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td align="center" class="text-nowrap">
                                        @if (isset($do[$driver->id]))
                                            <a href="{{ route('general.driver.do.view', $do_id[$driver->id]) }}">{{ $do[$driver->id] }}</a>
                                            @actionStart('do', 'approvedir')
                                                <a href="{{ route('general.driver.do.remove', $driver->id) }}" onclick="return confirm('Remove DO from this driver?')" class="btn btn-icon btn-danger btn-xs btn-circle"><i class="fa fa-times"></i></a>
                                            @actionEnd
                                        @else
                                            <button type="button" data-toggle="modal" onclick="_set_id({{ $driver->id }})" data-target="#modalAdd" class="btn btn-primary btn-sm">Create / Assign DO</button>
                                        @endif
                                    </td>
                                    @if (!$bank)
                                        <td align="center">
                                            @if (isset($do[$driver->id]) && empty($do_dispatch[$driver->id]))
                                                @if (empty($driver_checkout))
                                                    <button type="button"  data-toggle="modal" onclick="_set_id_checkout({{ $driver->id }})" data-target="#modalQCPass" class="btn btn-sm btn-light-success">QC Pass</button>
                                                @else
                                                    @if ($driver_checkout->id == $driver->id)
                                                        <button type="button" onclick="_set_id_checkout({{ $driver->id }})" data-target="#modalCancelPass" data-toggle="modal" class="btn btn-sm btn-light-warning">Cancel</button>
                                                    @else
                                                    N/A
                                                    @endif
                                                @endif
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    @endif
                                    <td align="center">
                                        @if (isset($do_dispatch[$driver->id]))
                                            @if (!empty($do_dispatch[$driver->id]))
                                                @if (!empty($do_received[$driver->id]))
                                                    <button type="button" class="btn btn-sm btn-success">
                                                        Received at {{ date("d F Y", strtotime($do_received[$driver->id])) }} <br>
                                                        by {{ $do_receiver[$driver->id] ?? $do_approver[$driver->id] }}
                                                    </button>
                                                @else
                                                    <button type="button" onclick="change_status({{ $driver->id }}, 'Received')" class="btn btn-sm btn-info">On Delivery</button>
                                                @endif
                                            @else
                                                <button type="button" onclick="change_status({{ $driver->id }}, 'On Delivery')" class="btn btn-sm btn-warning">Ready to deliver</button>
                                            @endif
                                        @else
                                            <button type="button" class="btn btn-sm btn-secondary">Waiting</button>
                                        @endif
                                    </td>
                                    <td align="center">
                                        <a href="{{ route('general.driver.delete', $driver->id) }}" class="btn btn-icon btn-xs btn-danger"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delivery Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mx-auto">
                            <div class="row">
                                <input type="hidden" name="driver-id" id="idd">
                                <div class="col-6 text-center">
                                    <button type="button" onclick="set_id_assign(this)" data-toggle="modal" data-target="#assignDO" class="btn btn-primary">Assign DO</button>
                                </div>
                                <div class="col-6 text-center">
                                    <button type="button" onclick="set_id(this)" data-toggle="modal" data-target="#addItem" class="btn btn-primary">Create DO</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalCancelPass" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cancel Pass</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form action="{{ route('general.driver.checkout') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8 mx-auto text-center">
                                <i class="fa fa-question-circle text-primary icon-10x"></i>
                            </div>
                            <div class="col-md-8 mx-auto mt-10 text-center">
                                <input type="hidden" name="id_driver_checkout">
                                <button type="button" class="btn btn-danger px-10 font-weight-bold" data-dismiss="modal">No</button>
                                <button type="submit" class="btn btn-primary px-10 font-weight-bold">Yes</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalQCPass" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">QC Pass</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form action="{{ route('general.driver.checkout') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8 mx-auto">
                                <div class="form-group">
                                    <label class="col-form-label">Device</label>
                                    <select name="user_device" class="form-control sel2" data-placeholder="SELECT DEVICE" required>
                                        <option value=""></option>
                                        @foreach ($user_device as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }} - {{ $item->dispatch_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_driver_checkout">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Pass</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="assignDO" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Assign Delivery Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form action="{{ route('general.driver.do.assign') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-form-label col-md-3 col-sm-12">DO#</label>
                            <div class="col-md-9 col-sm-12">
                                <select name="do_id" class="form-control select2" id="do-sel" required>
                                    <option value="">Select DO</option>
                                    @foreach ($doAssign as $item)
                                        <option value="{{ $item->id }}">{{ $item->no_do }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_driver" id="id-driver-assign">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Save</button>
                    </div>
                </form>
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
                        <input type="hidden" name="id_driver" id="id-driver">
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

@section('custom_script')
    <script src="{{asset('theme/assets/js/pages/crud/forms/widgets/typeahead.js?v=7.0.5')}}"></script>
    <link href="{{asset('theme/jquery-ui/jquery-ui.css')}}" rel="Stylesheet"></link>
    <script src="{{asset('theme/jquery-ui/jquery-ui.js')}}"></script>
    <script>

        function _checkout(x, y){
            Swal.fire({
                title: y,
                text: "Are you sure?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Yes",
                reverseButtons : true,
            }).then(function(result) {
                if (result.value) {
                    window.location.href = "{{ route('general.driver.checkout') }}/" + x
                }
            });
        }

        function change_status(x, y){
            Swal.fire({
                title: "Are you sure?",
                text: "Change status to '"+y+"'?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Yes",
                reverseButtons : true,
            }).then(function(result) {
                if (result.value) {
                    window.location.href = "{{ route('general.driver.update_status') }}/" + x
                }
            });
        }

        function set_id(x){
            $("#id-driver").val($(x).data('id'))
        }

        function set_id_assign(x){
            $("#id-driver-assign").val($(x).data('id'))
        }

        function _set_id(id){
            $("#modalAdd button").attr('data-id', id)
        }

        function _set_id_checkout(x){
            $("input[name=id_driver_checkout]").val(x)
        }

        $(document).ready(function(){

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
            $("select.sel2").select2({
                width : "100%"
            })

            $("table.display").DataTable({
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            })

            $("select.select2").select2({
                width: "100%"
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

            @if (\Session::get('success'))
                Swal.fire('Success', '{{ \Session::get('success') }}', 'success')
            @endif
        });
    </script>
@endsection
