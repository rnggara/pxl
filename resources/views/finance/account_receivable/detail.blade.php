@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Invoice Detail</h3><br>
            </div>
            <div class="card-toolbar btn-group">
                <a href="{{ route('ar.view', $inv_detail->id_inv) }}" class="btn btn-success"><i class="fa fa-arrow-left"></i></a>
                @if($inv_detail->fin_approved_by != null)
                    <a href="{{route('ar.view_entry',['id' => $inv_detail->id,'act' => 'print'])}}" target="_blank" class="btn btn-primary"><i class="fa fa-print"></i> Print Invoice</a>
                @endif
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
                            <td><b>Revise NUMBER</b></td>
                            <td>:</td>
                            <td><b>{{sprintf("%03d", $inv_detail->revise_number)}}</b></td>
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
                            <td>Project </td>
                            <td>:</td>
                            <td>{!! $title_name !!}</td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td>:</td>
                            <td>{{$client_address}}</td>
                        </tr>
                        <tr>
                            <td>Transaction Code</td>
                            <td>:</td>
                            <td>
                                <a href="{{ route('coa.assign', ['type' => "invoice-out", 'id' => $inv_detail->id]) }}" class="btn btn-light-primary"><i class="fa fa-edit"></i>Set Transaction Code</a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="separator separator-dashed separator-border-2 separator-primary"></div>
            @if($act != "view")
            <div class="row m-5">
                <div class="col-md-4 mx-auto">
                    <form action="" method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-9">
                                <select name="tax[]" multiple class="form-control select2" id="">
                                    @foreach($taxes as $tax)
                                        <option value="{{$tax->id}}" {{(is_array(json_decode($inv_detail->taxes)) && in_array($tax->id, json_decode($inv_detail->taxes))) ? "SELECTED" : "" }}>{{$tax->tax_name}}</option>
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
            @endif
            <div class="separator separator-dashed separator-border-2 separator-primary"></div>
            <div class="row mt-5">
                <div class="col-md-12">
                    <form action="{{($act == "appr") ? ($inv_detail->fin_approved_by == null) ? route('ar.appr_manager') : route('ar.appr_finance') : route('ar.revise')}}" method="POST" id="form-entry">
                        @csrf
                        <table class="table table-responsive-xl">
                            <thead>
                            <tr class="border border-top-light">
                                <th class="text-center">
                                    @if(empty($inv_detail->fin_approved_date))
                                    <a href="{{ route('ar.edit_entry', $inv_detail->id) }}" class="btn btn-xs btn-primary btn-icon"><i class="fa fa-edit"></i></a>
                                    @endif
                                </th>
                                <th class="text-center">Description</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Uom</th>
                                <th class="text-right">Unit Price</th>
                                <th class="text-right">Amount</th>
                            </tr>
                            </thead>
                            <tbody class="border border-light" id="tbody_clone">
                                <?php $sub_total = 0; $am_payable = 0; ?>
                            @foreach($inv_prints as $key => $item)
                                <tr>
                                    <td align="center">{{ $key + 1 }}</td>
                                    <td>{!! $item->description !!}</td>
                                    <td align="center">{{$item->qty}}</td>
                                    <td align="center">{{$item->uom}}</td>
                                    <td align="right">{{number_format($item->unit_price, 2)}}</td>
                                    <td align="right">
                                        <label for="" class="amount">{{number_format($item->qty * $item->unit_price, 2)}}</label>
                                    </td>
                                </tr>
                                <?php $sub_total += $item->qty * $item->unit_price ?>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="5" align="right">Sub Total</td>
                                <td align="right">
                                    <label id="sub-total" class="col-form-label">{{number_format($sub_total, 2)}}</label>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5" align="right">
                                    <label for="" class="col-form-label">Discount</label>
                                </td>
                                <td align="right">
                                    <input type="number" step=".01" value="{{$inv_detail->discount}}" readonly name="discount" class="form-control" style="width: 30%;text-align: end" id="discount">
                                </td>
                            </tr>
                            <?php $total = $sub_total - $inv_detail->discount; $am_payable = $total; ?>
                            <tr>
                                <td colspan="5" align="right">Total</td>
                                <td align="right">
                                    <label id="total-net" class="col-form-label">{{number_format($total, 2)}}</label>
                                </td>
                            </tr>
                            @if(is_array(json_decode($inv_detail->taxes)))
                            @foreach(json_decode($inv_detail->taxes) as $key => $tax)
                            @if(isset($tax_name[$tax]))
                                <?php
                                $sum = $total;
                                $formula = eval("return $tax_formula[$tax];");
                                if ($inv_detail->wapu == "on" && $isWapu[$tax] == 1) {
                                    # code...
                                } else {
                                    $am_payable += $formula;
                                }

                                 ?>
                                <tr>
                                    <td colspan="5" align="right">
                                        <label for="" class="col-form-label">{{$tax_name[$tax]}}</label>
                                        @if($inv_detail->wapu == "on" && $isWapu[$tax] == 1)
                                            <span class="label label-inline label-warning">WAPU</span>
                                        @endif
                                    </td>
                                    <td align="right">
                                        <label id="tax{{$tax}}" class="col-form-label tax-number">{{number_format($formula, 2)}}</label>
                                        @if($inv_detail->wapu == "on" && $isWapu[$tax] == 1)
                                            <input type="hidden" name="isWapu" id="isWapu">
                                        @endif
                                    </td>
                                </tr>
                            @endif
                            @endforeach
                            @endif
                            <tr>
                                <td colspan="5" align="right"><label for="" class="col-form-label">Amount Payable</label></td>
                                <td align="right">
                                    <label id="payable" class="col-form-label number">{{number_format($am_payable, 2)}}</label>
                                </td>
                            </tr>
                            @if($act == "appr")
                                <tr>
                                    <td colspan="5">
                                        <label for="" class="col-form-label">Notes</label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <textarea name="notes" id="notes" cols="30" rows="10"></textarea>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @if($inv_detail->fin_approved_by != null)
                                <tr>
                                    <td colspan="5">
                                        <div class="alert alert-warning col-md-6">
                                            <i class="fa fa-info-circle text-white"></i> Approval of this invoice will result in addition of funds in the treasury
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5">
                                        <input type="hidden" name="id_detail" value="{{$inv_detail->id}}">
                                        <button type="button" data-toggle="modal" onclick="app_fin()" data-target="#approveInv" class="btn btn-success btn-xs"><i class="fa fa-check"></i> Approve</button>
                                    </td>
                                </tr>
                                @else
                                    @if ($inv_detail->company_id == \Session::get('company_id'))
                                        <tr>
                                            <td colspan="5">
                                                <input type="hidden" name="id_detail" value="{{$inv_detail->id}}">
                                                <button type="button" id="btn-submit" class="btn btn-success btn-xs"><i class="fa fa-check"></i> Approve</button>
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="5">
                                                <div class="alert alert-custom alert-danger">
                                                    <div class="alert-icon">
                                                        <i class="flaticon2-warning"></i>
                                                    </div>
                                                    <div class="alert-text">
                                                        Your company ID is not match with this Invoice Out
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endif
                            @else
                                @if($inv_detail->fin_approved_by != null && $inv_detail->req_revise_date == null)
                                    <tr>
                                        <td colspan="5">
                                            <label for="" class="col-form-label">Notes</label>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <textarea name="notes" id="notes" cols="30" rows="10"></textarea>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5">
                                            <input type="hidden" name="id_detail" value="{{$inv_detail->id}}">
                                            <button type="button" id="btn-submit" class="btn btn-success btn-xs"><i class="fa fa-recycle"></i> Revise</button>
                                        </td>
                                    </tr>
                                @endif
                            @endif
                            </tfoot>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @if(!empty($inv_detail->fin_approved_by))
        <div class="modal fade" id="approveInv" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="approveInvLabel">Approval</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                    </div>
                    <form method="post" action="{{($act == "appr") ? ($inv_detail->fin_approved_by == null) ? route('ar.appr_manager') : route('ar.appr_finance') : route('ar.revise')}}">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12">
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                        <tr>
                                            <td>INVOICE NUMBER #</td>
                                            <td>{{$inv_detail->no_inv}}</td>
                                        </tr>
                                        <tr>
                                            <td>INVOICE Date</td>
                                            <td>{{date('d F Y', strtotime($inv_detail->date))}}</td>
                                        </tr>
                                        <tr>
                                            <td>Sub Total</td>
                                            <td align="right"><b id="appr-sub">{{number_format($sub_total, 2)}}</b></td>
                                        </tr>
                                        <tr>
                                            <td>Discount</td>
                                            <td align="right"><b>{{number_format($inv_detail->discount, 2)}}</b></td>
                                        </tr>
                                        <tr>
                                            <td>Total</td>
                                            <td align="right"><b id="appr-net">{{number_format($total, 2)}}</b></td>
                                        </tr>
                                        @if(is_array(json_decode($inv_detail->taxes)))
                                            @foreach(json_decode($inv_detail->taxes) as $key => $tax)
                                            @if(isset($tax_name[$tax]))
                                            <?php
                                                $sum = $total;
                                                $formula = eval("return $tax_formula[$tax];");
                                             ?>
                                                <tr>
                                                    <td>
                                                        <label for="" class="col-form-label">{{$tax_name[$tax]}}</label>
                                                        @if($inv_detail->wapu == "on" && $isWapu[$tax] == 1)
                                                            <span class="label label-inline label-warning">WAPU</span>
                                                        @endif
                                                    </td>
                                                    <td align="right">
                                                        <label id="appr-tax{{$tax}}" class="col-form-label">{{number_format($formula, 2)}}</label>
                                                    </td>
                                                </tr>
                                            @endif
                                            @endforeach
                                        @endif
                                        <tr>
                                            <td>Amount Payable</td>
                                            <td align="right"><b id="appr-pay">{{number_format($am_payable, 2)}}</b></td>
                                        </tr>
                                        <tr>
                                            <td>Notes</td>
                                            <td id="appr-notes"></td>
                                        </tr>
                                        <tr>
                                            <td>Select Source</td>
                                            <td>
                                                <select name="source" class="form-control select2" id="" required>
                                                    <option value="">Select Source</option>
                                                    @foreach($src as $item)
                                                        <option value="{{$item->id}}" {{($inv_detail->payment_account == $item->id) ? "SELECTED" : ""}}>{{$item->source}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="app_notes" id="hid-notes">
                            <input type="hidden" name="id_detail" value="{{$inv_detail->id}}">
                            <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                            <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                                <i class="fa fa-check"></i>
                                Change</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('custom_script')
    <script src="{{asset('theme/tinymce/jquery.tinymce.min.js')}}"></script>
    <script src="{{asset('theme/tinymce/tinymce.min.js')}}"></script>
    <script src="{{ asset('assets/jquery-number/jquery.number.js') }}"></script>
    <script>
        function app_fin(){
            $("#appr-notes").html(tinyMCE.editors[$('#notes').attr('id')].getContent())
            $("#hid-notes").val(tinyMCE.editors[$('#notes').attr('id')].getContent())
        }
        $(document).ready(function(){
            $(".number").number(true, 2)

            init_tinymce("#description")
            init_tinymce("#notes")
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

            // sum_amount()
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
                var sum = qty[i].value * price[i].value
                amount[i].innerHTML = sum.toFixed(2)
            }
            sum_amount()
        }

        function sum_amount(){
            var jsontaxformula = "{{json_encode($tax_formula)}}".replaceAll("&quot;", "\"")
            var taxformula = JSON.parse(jsontaxformula)

            var act = "{{$inv_detail->fin_approved_date}}"

            var amount = $(".amount").toArray()
            var sub = 0;
            var am = 0;
            for (let i = 0; i < amount.length; i++) {
                sub += parseInt(amount[i].innerHTML.replaceAll(",", ""))
            }

            var disc = $("#discount").val()

            $("#sub-total").text(sub.toFixed(2))

            am = sub - disc
            var net = sub - disc

            $("#total-net").text(net.toFixed(2))
            // console.log(act)

            @if (!empty($inv_detail->taxes))
            var _jsontax = "{{$inv_detail->taxes}}".replaceAll("&quot;", "\"")
            var _tax = JSON.parse(_jsontax)

            var jsonwapu = "{{json_encode($isWapu)}}".replaceAll("&quot;", "\"")
            var wapu = JSON.parse(jsonwapu)

            for (let i = 0; i < _tax.length; i++) {
                if (act == "" || act == undefined) {
                    var tx = document.getElementById("tax"+_tax[i])
                    var $sum = net
                    var tax_val = eval(taxformula[_tax[i]])
                    $("#tax"+_tax[i]).number(tax_val, 2)
                    $("#appr-tax"+_tax[i]).number(tax_val, 2)
                    am += tax_val
                } else {
                    if (wapu[_tax[i]] == 1 && "{{$inv_detail->wapu}}" == "on") {
                        var tx = document.getElementById("tax"+_tax[i])
                        var $sum = net
                        var tax_val = eval(taxformula[_tax[i]])
                        $("#tax"+_tax[i]).number(tax_val, 2)
                        $("#appr-tax"+_tax[i]).number(tax_val, 2)
                    } else {
                        var tx = document.getElementById("tax"+_tax[i])
                        var $sum = net
                        var tax_val = eval(taxformula[_tax[i]])
                        $("#tax"+_tax[i]).number(tax_val, 2)
                        $("#appr-tax"+_tax[i]).number(tax_val, 2)
                        am += tax_val
                    }

                }
                // $("#tax"+_tax[i]).number($("#tax"+_tax[i]).val(), 2)
            }
            @endif



            // console.log(format.format(am))
            $("#payable").number(am.toFixed(2), 2)
            $("#sub-total").number(sub.toFixed(2), 2)
            $("#total-net").number(net.toFixed(2), 2)
            $(".tax-number").number(true, 2)
            $("#appr-sub").number(sub, 2)
            $("#appr-net").number(net, 2)
            $("#appr-pay").number(am, 2)
        }
    </script>
@endsection
