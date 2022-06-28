@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">List Item Assembly - {{ $_item->name }}</h3>
            <div class="card-toolbar">
                <a href="{{ route('items.assembly.index') }}" class="btn btn-sm btn-icon btn-success"><i class="fa fa-arrow-left"></i></a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-6 mx-auto border p-5">
                    <h3>Add Item</h3>
                    <hr>
                    <div class="form-group">
                        <label class="col-form-label">Item Name <span class="text-danger">*</span></label>
                        <select name="item_name" class="form-control select2" data-placeholder="Select Item">
                            <option value=""></option>
                            @foreach ($all_item as $items)
                                <option value="[{{ $items->item_code }}] {{ $items->name }}" data-id="{{ $items->id }}">[{{ $items->item_code }}] {{ $items->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Item Quantity <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" min="0" name="item_quantity">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Item Type <span class="text-danger">*</span></label>
                        <select name="item_type" class="form-control select2" data-placeholder="Select Type">
                            <option value=""></option>
                            <option value="Consumed">Consumed</option>
                            <option value="Attached">Attached</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="button" id="btn-add" class="btn btn-primary"><i class="fa fa-plus"></i> Add</button>
                    </div>
                </div>
            </div>
            <form action="{{ route('items.assembly.add_list') }}" method="post">
                @csrf
                <input type="hidden" name="item" value="{{ $_item->id }}">
                <div class="row mt-5">
                    <div class="col-8 mx-auto">
                        <hr>
                        <h3>List Item</h3>
                        <table class="table table-bordered table-hover" id="table-list">
                            <thead>
                                <tr>
                                    <th class="text-center">Item Name</th>
                                    <th class="text-center">Item Type</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-center">
                                        <button type="submit" id="btn-save" class="btn btn-primary">Save</button>
                                        <input type="hidden" name="type" value="{{ $type }}">
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($_list as $i => $item)
                                    <tr>
                                        <td class="text-nowrap">
                                            <span class='is-item'>{{ $item->item_name }}</span> <input type='hidden' class='item_post' name='item_id[]' value='{{ $item->item_id }}'>
                                        </td>
                                        <td align="center">
                                            <span>{{ $item->type }}</span> <input type='hidden' name='item_type[]' value='{{ $item->type }}'>
                                        </td>
                                        <td align="center">
                                            <span>{{ $item->qty }}</span> <input type='hidden' name='item_qty[]' value='{{ $item->qty }}'>
                                        </td>
                                        <td align="center">
                                            <button onclick='_remove(this)' type='button' class='btn btn-icon btn-xs btn-danger'><i class='fa fa-trash'></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        var table_list = $("#table-list").DataTable({
            columnDefs : [
                {"targets" : [0], "className" : "text-nowrap"},
                {"targets" : "_all", "className" : "text-center"}
            ]
        })

        function _remove(btn){
            var tr = $(btn).parents('tr')
            table_list.row(tr)
                .remove()
                .draw()
        }

        $(document).ready(function(){
            $("select.select2").select2({
                width : "100%"
            })

            var item_name = $("select[name=item_name]")
            var item_quantity = $("input[name=item_quantity]")
            var item_type = $("select[name=item_type]")

            var tr = $("tbody").find("tr")

            $("#btn-add").click(function(){
                var _list_item = $(".is-item")

                var _is_item = []

                _list_item.each(function(){
                    _is_item.push($(this).text())
                })

                if($.inArray(item_name.val(), _is_item) >= 0){
                    return Swal.fire("Item listed", 'This item is already listed', 'warning')
                }

                if(item_name.val() == "" || item_name.val() == null){
                    return Swal.fire("Field Required", "Please select Item ", 'warning').then((result) => {
                        if(result.value){
                            item_name.focus()
                        }
                    })
                }

                if(item_quantity.val() == "" || item_quantity.val() == null){
                    return Swal.fire("Field Required", "Please fill Item Quantity", 'warning').then((result) => {
                        if(result.value){
                            item_quantity.focus()
                        }
                    })
                }

                if(item_quantity.val() == 0 || item_quantity.val() < 0){
                    return Swal.fire("Field Required", "Quantity can not be zero or minus", 'warning').then((result) => {
                        if(result.value){
                            item_quantity.focus()
                        }
                    })
                }

                if(item_type.val() == "" || item_type.val() == null){
                    return Swal.fire("Field Required", "Please select Item Type", 'warning').then((result) => {
                        if(result.value){
                            item_type.focus()
                        }
                    })
                }

                var _item_id = item_name.find('option:selected').data('id')

                $.ajax({
                    url : "{{ route('items.assembly.list', $_item->id) }}?item=" + _item_id + "&q=" + item_quantity.val(),
                    type : "get",
                    dataType : "json",
                    success : function(response){
                        if(!response){
                            return Swal.fire('Item Exceed Quantity', 'The quantity you request is exceed the quantity in storages', 'error')
                        } else {
                            var _item_name = "<span class='is-item'>"+item_name.val()+"</span> <input type='hidden' class='item_post' name='item_id[]' value='"+_item_id+"'>"
                            var _item_quantity = "<span>"+item_quantity.val()+"</span> <input type='hidden' name='item_qty[]' value='"+item_quantity.val()+"'>"
                            var _item_type = "<span>"+item_type.val()+"</span> <input type='hidden' name='item_type[]' value='"+item_type.val()+"'>"
                            var _btn = "<button onclick='_remove(this)' type='button' class='btn btn-icon btn-xs btn-danger'><i class='fa fa-trash'></i></button>"

                            table_list.row.add([
                                _item_name,
                                _item_type,
                                _item_quantity,
                                _btn
                            ]).draw(true)


                            item_name.val('').trigger('change')
                            item_quantity.val('')
                            item_type.val('').trigger('change')
                        }
                    }
                })
            })

            $("#btn-save").click(function(e){
                e.preventDefault()
                var _list_item = $(".item_post")

                var form = $(this).parents('form')

                if(_list_item.length == 0){
                    return Swal.fire('List Required', 'Select at least 1 item', 'error')
                } else {
                    form.submit()
                }
            })

            @if(\Session::get('msg'))
                Swal.fire("{{ \Session::get('msg') }}", "", "{{ (\Session::get('msg') == "Success") ? "success"  : "error"}}")
            @endif
        })
    </script>
@endsection
