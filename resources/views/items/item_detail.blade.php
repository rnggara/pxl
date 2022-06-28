@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">Item Detail</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <a href="" class="btn btn-icon btn-success btn-sm"><i class="fa fa-arrow-left"></i></a>
                    @actionStart('inventory', 'approvedir')
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addQtyModal">Add Quantity</button>
                    @actionEnd
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-3">
                    <h3 class="card-title">Basic Information</h3>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right">Item Name</label>
                                <label class="col-md-9 font-weight-bold col-form-label">: {{ $item->name }}</label>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right">Item Code</label>
                                <label class="col-md-9 font-weight-bold col-form-label">: {{ $item->item_code }}</label>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right">Brand Name</label>
                                <label class="col-md-9 font-weight-bold col-form-label">: {{ $item->item_series }}</label>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right">Item Category</label>
                                <label class="col-md-9 font-weight-bold col-form-label">: {{ $item->cat_name }}</label>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right">Item Classification</label>
                                <label class="col-md-9 font-weight-bold col-form-label">: {{ $item->class_name }}</label>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right">Serial Number</label>
                                <label class="col-md-9 font-weight-bold col-form-label">: {{ $item->serial_number }}</label>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right">Type</label>
                                <label class="col-md-9 font-weight-bold col-form-label">: {{ ($item->type_id == 1) ? "Consumable" : "Non Consumable" }}</label>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right">Minimal Stock</label>
                                <label class="col-md-9 font-weight-bold col-form-label">: {{ $item->minimal_stock }}</label>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right">UoM</label>
                                <label class="col-md-9 font-weight-bold col-form-label">: {{ $item->uom }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <h3 class="card-title">Detail Information</h3>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right">Picture</label>
                                <div class="col-md-9">
                                    <div class="col-lg-9 col-xl-6">
                                        <div class="image-input image-input-outline" id="app_logo" style="{{ (!empty($item->picture)) ? "background-image : url('".str_replace("public", "public_html", asset('media/asset/'.$item->picture))."')" : "" }}">
                                            <div class="image-input-wrapper"></div>
                                            <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel">
                                                <i class="ki ki-bold-close icon-xs text-muted"></i>
                                            </span>
                                        </div>
                                        <span class="form-text text-muted"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right">Notes</label>
                                <div class="col-md-9">
                                    <textarea name="notes" class="form-control" id="notes" cols="30" rows="10" readonly>{!! $item->notes !!}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right">Specification</label>
                                <div class="col-md-9">
                                    <textarea name="specification" class="form-control" id="specification" cols="30" rows="10" readonly>{!! $item->specification !!}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <h3 class="card-title">Quantity & Transaction</h3>
                    <hr>
                    <div class="row">
                        <div class="col-4">
                            <div id="wh-item"></div>
                            <div class="form-group row">
                                <label for="" class="col-4 col-form-label">Total Stock</label>
                                <div class="col-8">
                                    <input type="text" class="form-control" readonly value="" id="total_stock">
                                </div>
                            </div>
                        </div>
                        <div class="col-8">
                            <table class="table table-bordered table-hover table-striped" id="table-transaction">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Description</th>
                                        <th class="text-center">Paper</th>
                                        <th class="text-center">Warehouse</th>
                                        <th class="text-center"></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @actionStart('inventory', 'approvedir')
    <div class="modal fade" id="addQtyModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('itemsInventory.add.qty') }}" method="post">
                    <div class="modal-header">
                        <h1 class="modal-title">Add Quantity</h1>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="" class="col-form-label col-md-3 col-sm-12">Storage</label>
                            <div class="col-md-9 col-sm-12">
                                <select name="_storage" class="form-control select2 required" aria-placeholder="Storage" data-placeholder="Select Storage">
                                    <option value=""></option>
                                    @foreach ($wh as $id_wh => $wh_data)
                                        <option value="{{ $id_wh }}">{{ $wh_data }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-form-label col-md-3 col-sm-12">Quantity</label>
                            <div class="col-md-9 col-sm-12">
                                <input type="number" name="_qty" class="form-control required" aria-placeholder="Quantity">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-form-label col-md-3 col-sm-12">Notes</label>
                            <div class="col-md-9 col-sm-12">
                                <textarea name="_notes" class="form-control required" aria-placeholder="Notes" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @csrf
                        <input type="hidden" name="_item_id" value="{{ $item->id }}">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" id="btn-add" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @actionEnd
@endsection

@section('custom_script')
    <script src="{{ asset('theme/tinymce/tinymce.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            $.ajax({
                url: "{{route('items.find_transaction', $item->id)}}",
                type: "GET",
                dataType: "json",
                cache: false,
                success: function (resp) {
                    var t = $("#table-transaction").DataTable({
                        responsive: true,
                        paging: false,
                        bInfo: false,
                        searching: false,
                        data: resp.data,
                        columns: [
                            {"data" : "no"},
                            {"data" : "date"},
                            {"data" : "description"},
                            {"data" : "paper"},
                            {"data" : "warehouse"},
                            {"data" : "amount"},
                        ],
                        columnDefs: [
                            {"className" : "amount", "targets" : [5]},
                            {"className" : "dt-center", "targets" : "_all"}
                        ]
                    })

                    t.on( 'order.dt search.dt', function () {
                        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                            cell.innerHTML = i+1;
                        } );
                    } ).draw();

                    $(".amount").each(function(){
                        if (parseInt($(this).text()) > 0){
                            var num = "+"+$(this).text()
                            $(this).text(num)
                        }
                    })
                }
            })

            $.ajax({
                url: '{{URL::route('itemsInventory.find')}}',
                data: {
                    '_token': '{{csrf_token()}}',
                    'id': {{ $item->id }}
                },
                type: "POST",
                cache: false,
                dataType: 'json',
                success : function(response){
                    var wh = response.qtywh
                    console.log(wh)
                    var content = ""
                    var total_stock = 0
                    for (const i in wh) {
                        if(wh[i]['qty'] > 0){
                            content += '<div class="form-group row"><label for="" class="col-4 col-form-label">'+wh[i]['name']+'</label><div class="col-8"><input type="text" class="form-control" readonly value="'+wh[i]['qty']+'"></div></div>'
                            total_stock += wh[i]['qty']
                        }
                    }

                    $("#wh-item").html(content)
                    $("#total_stock").val(total_stock)
                }
            })
            $("select.select2").select2({
                width: "100%"
            })

            tinymce.init({
                selector : "#addQtyModal textarea",
                menubar : false
            })

            @actionStart('inventory', 'approvedir')
            $("#btn-add").click(function(e){
                e.preventDefault()
                var form = $(this).parents('form')
                var req = form.find(".required")
                var reqval = []
                req.each(function(){
                    var val
                    if ($(this).is('textarea')) {
                        var id = $(this).attr('id')
                        val = tinymce.get(id).getContent()
                    } else {
                        val = $(this).val()
                    }

                    console.log(val)
                    if(val == ''){
                        console.log(val)
                        reqval.push($(this).attr('aria-placeholder'))
                    }
                })

                if(reqval.length > 0){
                    var msg = ""
                    for (let index = 0; index < reqval.length; index++) {
                        msg += reqval[index]
                        if ((index + 1) < reqval.length) {
                            msg += "<br>"
                        }
                    }
                    Swal.fire('Required', 'Field<br>'+msg+' <br> is required', 'warning')
                } else {
                    _post()
                    form.submit()
                }
            })
            @actionEnd

            @if (\Session::get('success'))
                Swal.fire('Success', '{{ \Session::get('success') }}', 'success')
            @endif

            @if (\Session::get('error'))
                Swal.fire('Error', '{{ \Session::get('error') }}', 'error')
            @endif
        })
    </script>
@endsection
