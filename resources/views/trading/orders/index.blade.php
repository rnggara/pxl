@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Orders</h3><br>

            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addItem"><i class="fa fa-plus"></i>Order Request</button>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">No Order</th>
                        <th class="text-center">Supplier</th>
                        <th class="text-center">Client</th>
                        <th class="text-center">Request Date</th>
                        <th class="text-center">Due Date</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Final</th>
                        <th class="text-center"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $i => $order)
                        <tr>
                            <td align="center">{{$i + 1}}</td>
                            <td align="center">
                                <span class="label label-inline label-primary">{{$order->no_order}}</span>
                            </td>
                            <td align="center">
                                {{$data_supplier[$order->supplier]->name}}
                            </td>
                            <td align="center">
                                {{$data_client[$order->client]->company_name}}
                            </td>
                            <td align="center">
                                {{date('d F Y', strtotime($order->request_date))}}
                            </td>
                            <td align="center">
                                {{date('d F Y', strtotime($order->due_date))}}
                            </td>
                            <td align="center">
                                <?php
                                /** @var TYPE_NAME $order */
                                /** @var TYPE_NAME $details */
                                $modifier = json_decode($order->modifiers);
                                $val = array_sum($details[$order->id]);
                                $tMod = 0;
                                if (!empty($modifier)){
                                    foreach ($modifier as $mod){
//                                        dd($mod->amount);
                                        $tMod += intval($mod->amount);
                                    }
                                }
                                ?>
                                {{number_format($val + $tMod, 2)}}
                            </td>
                            <td align="center">
                                @if(empty($order->allocation_fee))
                                    <button type="button" onclick="modalFinal('{{$order->id}}')" class="btn btn-xs btn-icon btn-info"><i class="fa fa-upload"></i></button>
                                @else
                                    <button type="button" onclick="modalFinal('{{$order->id}}')" class="btn btn-xs btn-icon btn-success"><i class="fa fa-check"></i></button>
                                @endif
                            </td>
                            <td align="center">
                                <button type="button" class="btn btn-xs btn-icon btn-primary"><i class="fa fa-edit"></i></button>
                                <button type="button" class="btn btn-xs btn-icon btn-danger"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
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
                <form method="post" id="form-add" action="{{URL::route('trading.orders.add')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-5">
                                <h4>Request By</h4>
                                <hr>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Supplier</label>
                                    <div class="col-md-9">
                                        <select name="supplier" id="supplier" class="form-control">
                                            <option value="">Select Supplier</option>
                                            @foreach($suppliers as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Client</label>
                                    <div class="col-md-9">
                                        <select name="client" class="form-control">
                                            <option value="">Select Client</option>
                                            @foreach($clients as $item)
                                                <option value="{{$item->id}}">{{$item->company_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Request Date</label>
                                    <div class="col-md-9">
                                        <input type="date" name="request_date" id="request_time" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Due Date</label>
                                    <div class="col-md-9">
                                        <input type="date" name="due_date" id="due_date" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row" id="opt">
                                    <label class="col-md-3 col-form-label text-right">Description</label>
                                    <div class="col-md-9">
                                        <textarea name="description" id="fr_note" cols="30" rows="10" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Notes</label>
                                    <div class="col-md-9">
                                        <textarea name="notes" id="fr_note" cols="30" rows="10" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <h4>Request Item</h4>
                                <hr>
                                <div class="form-group row">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Serial Number</th>
                                            <th>Product Name</th>
                                            <th>UoM</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Sub Total</th>
                                            <th>Action</th>
                                        </tr>
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
                                            <td class="text-center" colspan="2"><input type="number" size="2" id="price" placeholder="Price" class="form-control" /></td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-primary btn-sm btn-icon" onClick="addInput('list_item');"><i class="fa fa-plus"></i></button>
                                            </td>
                                        </tr>
                                        </thead>
                                        <tbody id="list_item">
                                        </tbody>
                                        <tbody id="modifier">
                                            <tr>
                                                <td>
                                                    <button type="button" id="btn-modifier" class="btn btn-xs btn-primary" data-toggle="collapse" data-target="#collapseOne2"><i class="fa fa-plus"></i>Modifier</button>
                                                </td>
                                                <td colspan="6">
                                                    <div class="accordion accordion-light accordion-toggle-arrow" id="accordionExample2">
                                                        <div class="card">
                                                            <div id="collapseOne2" class="collapse" data-parent="#accordionExample2">
                                                                <div class="card-body">
                                                                    <div id="form-modifier">
                                                                        <div class="form-group row">
                                                                            <label for="" class="col-md-3 col-form-label">Modifier Name</label>
                                                                            <div class="col-md-9">
                                                                                <input type="text" class="form-control" id="modifier-name">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <label for="" class="col-md-3 col-form-label">Subject</label>
                                                                            <div class="col-md-9">
                                                                                <select name="" class="" id="modifier-subject">

                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <label for="" class="col-md-3 col-form-label">Fixed Amount</label>
                                                                            <div class="col-md-9">
                                                                                <input type="text" class="form-control" id="modifier-fixed-amount">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <label for="" class="col-md-3 col-form-label">Multiplier</label>
                                                                            <div class="col-md-9">
                                                                                <input type="text" class="form-control" id="modifier-multiplier">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <label for="" class="col-md-3 col-form-label"></label>
                                                                            <div class="col-md-9">
                                                                                <button type="button" onclick="addModifier()" class="btn btn-xs btn-success" id="modifier-btn-add">Add</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="5">Total</th>
                                                <td>
                                                    <span id="g-total">{{number_format(0, 2)}}</span>
                                                </td>
                                                <td>
                                                    <input type="hidden" id="subtotal">
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <span class="form-text text-muted">* UoM is Unit of Measurement</span>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Letter of intent</label>
                                    <div class="col-md-9 custom-file">
                                        <input type="file" name="loi" class="custom-file-input">
                                        <span class="custom-file-label">Choose File</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Proof of funds</label>
                                    <div class="col-md-9 custom-file">
                                        <input type="file" name="pof" class="custom-file-input">
                                        <span class="custom-file-label">Choose File</span>
                                    </div>
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
    <div class="modal fade" id="uploadFinal" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Request Form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" id="form-add" action="{{URL::route('trading.orders.final')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-3">MoU</label>
                                    <div class="col-md-9 custom-file">
                                        <input type="file" name="mou" class="custom-file-input">
                                        <span class="custom-file-label">Choose File</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-3">Commitment</label>
                                    <div class="col-md-9 custom-file">
                                        <input type="file" name="commitment" class="custom-file-input">
                                        <span class="custom-file-label">Choose File</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-3">Allocation Fee</label>
                                    <div class="col-md-9">
                                        <input type="number" class="form-control" name="fee" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="id_order" name="id_order">
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

        function modalFinal(x) {
            $("#uploadFinal").modal('show')
            $("#id_order").val(x)
        }

        function deleteRow(o){
            var p = o.parentNode.parentNode;
            p.parentNode.removeChild(p);
            sum_calc()
        }

        var subject = [
            {
                id : "subtotal",
                text: "Total"
            }
        ]

        function subject_modifier() {
            $("#modifier-subject").select2({
                width: "100%",
                data: subject
            })
        }

        function addInput(trName) {
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
                "<input type='hidden' name='price[]' value='" + $("#price").val() + "'>" + $("#price").val() +
                "</td>" +
                "<td align='center'>" +
                "<span class='gtotal'>" + $("#price").val() * $("#qty").val() + "</span>" +
                "</td>" +
                "<td align='center'>" +
                "<button type='button' onClick='deleteRow(this)' class='btn btn-icon btn-xs btn-danger'><i class='fa fa-trash'></i></button>" +
                "</td>";
            document.getElementById(trName).appendChild(newrow);
            $("#item").val("");
            $("#uom").html("");
            $("#qty").val("");
            $("#price").val("");
            sum_calc()
        }

        function addModifier() {
            var newrow = document.createElement('tr')
            var target = $("#modifier-subject option:selected").val()
            console.log(target)
            var valtarget = document.getElementById(target).value.replaceAll(",","")
            console.log(valtarget)
            var val = 0
            var sum = 0
            if($("#modifier-fixed-amount").val() === "" || $("#modifier-fixed-amount").val() === undefined){
                var multiplier = $("#modifier-multiplier").val()
                console.log(multiplier)
                val = valtarget * multiplier
                sum = parseInt(valtarget) + parseInt(val)
            } else {
                val = $("#modifier-fixed-amount").val()
                sum = parseInt(valtarget) + parseInt(val)
            }
            var id = $("#modifier-name").val().toLowerCase().replaceAll(" ", "_")
            newrow.innerHTML = "<tr>" +
                "<td colspan='5'><input type='hidden' name='name_mod[]' value='"+id+"'> " + $("#modifier-name").val() + " </td>" +
                "<td align='right'><input type='hidden' name='val_mod[]' value='"+val+"'><span id='"+id+"' class='modifier-val'> " + val + "</span> </td>" +
                "<td><input type='hidden' id='"+id+"Sum' value='"+ sum +"'></td>" +
                "</tr>"
            document.getElementById('modifier').appendChild(newrow)
            $("#modifier-name").val("");
            $("#modifier-subject").val("").trigger('change');
            $("#modifier-fixed-amount").val("");
            $("#modifier-multiplier").val("");
            $("#btn-modifier").click()
            subject.push(id)
            subject.push(id+"Sum")
            subject_modifier()
            sum_calc()
        }

        function sum_calc(){
            var sub = $(".gtotal").toArray()
            var modifier = $(".modifier-val").toArray()
            console.log(sub)
            var gtotal = 0
            var subtotal = 0
            if (sub.length > 0){
                for (const subKey in sub) {
                    console.log(sub[subKey].innerHTML)
                    gtotal += parseInt(sub[subKey].innerHTML)
                    subtotal += parseInt(sub[subKey].innerHTML)
                }
                if (modifier.length > 0){
                    for (const key in modifier) {
                        gtotal += parseInt(modifier[key].innerHTML)
                    }
                }
            } else {
                gtotal = 0
            }

            $("#g-total").text(gtotal)
            $("#subtotal").val(subtotal)
        }
        $(document).ready(function(){
            subject_modifier()
            var link = "{{route('trading.products.autocomplete')}}/"
            $("#supplier").on('change', function () {
                link = "{{route('trading.products.autocomplete')}}/" + this.value
                $("#item").autocomplete({
                    source: link,
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
            })

            $("select.form-control").select2({
                width: "100%"
            })

            $("table.display").DataTable({
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            });
        });


    </script>
@endsection
