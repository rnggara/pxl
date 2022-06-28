@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Invoice Detail</h3><br>
            </div>

        </div>
        <div class="card-body">
            <div class="card card-custom bg-primary m-5">
                <div class="separator separator-solid separator-white opacity-20"></div>
                <div class="card-body text-white">
                    <table class="text-white">
                        <tr>
                            <td><b>INVOICE NUMBER #</b></td>
                            <td>:</td>
                            <td><b>{{$inv_detail->no_inv}}</b></td>
                        </tr>
                        <tr>
                            <td>Invoice Date</td>
                            <td>:</td>
                            <td>{{date('d F Y', strtotime($inv_detail->date))}}</td>
                        </tr>
                        <tr>
                            <td>Contact</td>
                            <td>:</td>
                            <td>{{$client_pic}}</td>
                        </tr>
                        <tr>
                            <td>Project / Leads</td>
                            <td>:</td>
                            <td>{{$title_name}}</td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td>:</td>
                            <td>{{$client_address}}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="separator separator-dashed separator-border-2 separator-primary"></div>
            <div class="row m-5">
                <div class="col-md-4 mx-auto">
                    <form action="" method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-9">
                                <select name="tax[]" multiple class="form-control select2" id="">
                                    @foreach($taxes as $tax)
                                        <option value="{{$tax->id}}" {{(!empty($inv_detail->taxes) && in_array($tax->id, json_decode($inv_detail->taxes))) ? "SELECTED" : "" }}>{{$tax->tax_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-xs btn-primary">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="separator separator-dashed separator-border-2 separator-primary"></div>
            <div class="row mt-5">
                <div class="col-md-12">
                    <form action="{{route('ar.edit_input')}}" method="POST" id="form-entry">
                        @csrf
                        <table class="table table-responsive-xl">
                            <thead>
                            <tr>
                                <td colspan="5" align="right">
                                    <button type="button" class="btn btn-danger btn-xs" id="btn-remove-list"><i class="fa fa-times"></i> Remove last List</button>
                                    <button type="button" class="btn btn-primary btn-xs" id="btn-list"><i class="fa fa-plus"></i> Add List</button>
                                </td>
                            </tr>
                            <tr class="border border-top-light">
                                <th class="text-center">Description</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Uom</th>
                                <th class="text-center">Unit Price</th>
                                <th class="text-center">Amount</th>
                            </tr>
                            </thead>
                            <tbody class="border border-light" id="tbody_clone">
                            @foreach ($invPrint as $print)
                                <tr class="tr_clone">
                                    <td>
                                        <textarea name="description[]" id="description" class="form-control description" cols="30" rows="10">{{ $print->description }}</textarea>
                                    </td>
                                    <td align="center">
                                        <input type="number" onkeyup="calc()" name="qty[]" value="{{ $print->qty }}" class="form-control number qty" style="width: 50%" required>
                                    </td>
                                    <td align="center">
                                        <select name="uom[]" class="form-control select2" style="width: 50%">>
                                            <option value="">Select Uom</option>
                                            @foreach($uom as $item)
                                                <option value="{{$item}}" {{ ($item == $print->uom) ? "SELECTED" : "" }} >{{$item}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td align="center">
                                        <input type="text" onkeyup="calc()" name="price[]" value="{{ number_format($print->unit_price, 2) }}" class="form-control number price" style="width: 50%" required>
                                    </td>
                                    <td align="right">
                                        <label class="amount col-form-label">{{number_format($print->qty * $print->unit_price, 2)}}</label>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" align="right">Sub Total</td>
                                    <td></td>
                                    <td align="right">
                                        <label id="sub-total" class="col-form-label">{{number_format(0, 2)}}</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" align="right">
                                        <label for="" class="col-form-label">Discount</label>
                                    </td>
                                    <td align="center">
                                        <input type="number" step=".01" value="0" class="form-control" style="width: 30%; text-align: end" id="disc-perc">
                                    </td>
                                    <td align="right">
                                        <input type="number" step=".01" value="0" name="discount" class="form-control" style="width: 30%;text-align: end" id="discount">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" align="right">Total</td>
                                    <td></td>
                                    <td align="right">
                                        <label id="total-net" class="col-form-label">{{number_format(0, 2)}}</label>
                                    </td>
                                </tr>
                                @if(!empty(json_decode($inv_detail->taxes)))
                                    @foreach(json_decode($inv_detail->taxes) as $key => $tax)
                                        <tr>
                                            <td colspan="3" align="right"><label for="" class="col-form-label">{{$tax_name[$tax]}}</label></td>
                                            <td></td>
                                            <td align="right">
                                                <label id="tax{{$tax}}" class="col-form-label">{{number_format(0, 2)}}</label>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                                <tr>
                                    <td colspan="3" align="right"><label for="" class="col-form-label">Amount Payable</label></td>
                                    <td></td>
                                    <td align="right">
                                        <label id="payable" class="col-form-label">{{number_format(0, 2)}}</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5">
                                        <input type="hidden" name="id_detail" value="{{$inv_detail->id}}">
                                        <button type="button" id="btn-submit" class="btn btn-success btn-xs"><i class="fa fa-check"></i> Update</button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('custom_script')
<script src="{{asset('assets/jquery-number/jquery.number.js')}}"></script>
<script type="text/javascript">
   // $(document).ready(function(){
     //   $("input.number").number(true, 2)
    //})
</script>
    <script src="{{asset('theme/tinymce/jquery.tinymce.min.js')}}"></script>
    <script src="{{asset('theme/tinymce/tinymce.min.js')}}"></script>
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
                        url: "{{URL::route('inv_in.delete_pay')}}",
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
            init_tinymce("#description")
            $("select.select2").select2({
                width: "100%"
            })

            $("#btn-submit").click(function(){
                Swal.fire({
                    title: "Submit",
                    text: "Are you sure you want to submit?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Submit",
                    cancelButtonText: "Cancel",
                    reverseButtons: true,
                }).then(function(result){
                    if(result.value){
                        $("#form-entry").submit()
                    }
                })
            })

            $("#btn-remove-list").click(function(){
                var lastTr = $(".tr_clone").toArray()
                if (lastTr.length <= 1) {
                    Swal.fire('1 item', 'At least 1 Item', 'warning')
                } else {
                    var trLast = lastTr[lastTr.length - 1]
                    trLast.remove()
                }
            })

            $("#btn-list").click(function(){
                $("select.select2").select2("destroy")
                tinymce.remove()
                var textareas = $("textarea.form-control").toArray()
                var tbody = $("#tbody_clone")
                var trLast = tbody.find("tr:last")
                var trNew = trLast.clone()
                trNew.find('input[type=number]').val('')
                trNew.find('input[type=text]').val('')
                trNew.find('input[type=number]').val('')
                trNew.find(".amount").number(0, 2)
                var textarea = trNew.find('textarea')
                textarea.attr("id", "description"+textareas.length)
                trLast.after(trNew)
                $("select.select2").select2({
                    width: "100%"
                })
                $("textarea.form-control").each(function(){
                    init_tinymce("#"+$(this).attr('id'))
                })
            })


            $("table.display").DataTable()

            $("#disc-perc").keyup(function(){
                var disc = ($("#disc-perc").val()/100) * $("#sub-total").text()
                $("#discount").val(disc.toFixed(2))
                sum_amount()
            })

            $("#discount").keyup(function(){
                var disc = ($(this).val() / $("#sub-total").text()) * 100
                $("#disc-perc").val(disc.toFixed(1))
                sum_amount()
            })
        })

        function init_tinymce(description) {
            tinymce.init({
                selector:description,
                mode : "textarea",
                menubar: false,
                toolbar: false
            });
        }

        function calc(){
            var qty = $(".qty").toArray()
            var price = $(".price").toArray()
            var amount = $(".amount").toArray()
            for (let i = 0; i < qty.length; i++) {
                var sum = parseFloat(qty[i].value) * parseFloat(price[i].value.replaceAll(",", ""))
                amount[i].innerHTML = sum.toFixed(2)
            }
            sum_amount()
        }

        function sum_amount(){
            var jsontaxformula = "{{json_encode($tax_formula)}}".replaceAll("&quot;", "\"")
            var taxformula = JSON.parse(jsontaxformula)
            var _jsontax = "{{$inv_detail->taxes}}".replaceAll("&quot;", "\"")
            var _tax = JSON.parse(_jsontax)

            var amount = $(".amount").toArray()
            var sub = 0;
            var am = 0;
            for (let i = 0; i < amount.length; i++) {
                sub += parseFloat(amount[i].innerHTML.replaceAll(",", ""))
            }

            var disc = $("#discount").val()

            $("#sub-total").text(sub.toFixed(2))

            am = sub - disc
            var net = sub - disc

            $("#total-net").text(net.toFixed(2))

            if (taxformula != "") {
                for (let i = 0; i < _tax.length; i++) {
                    var tx = document.getElementById("tax"+_tax[i])
                    var $sum = am
                    var tax_val = eval(taxformula[_tax[i]])
                    $("#tax"+_tax[i]).number(tax_val, 2)
                    am += tax_val
                }
            }


            $("#payable").number(am.toFixed(2), 2)
            $("#total-net").number(net, 2)
            $("#sub-total").number(sub, 2)
            $(".amount").each(function(){
                $(this).number($(this).text(), 2)
            })
        }

        $(document).ready(function(){
            sum_amount()
        })
    </script>
@endsection
