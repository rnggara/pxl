@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Purchase Evaluation - {{strtoupper($status)}}</h3><br>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{URL::route('pe.index')}}" class="btn btn-success btn-xs"><i class="fa fa-arrow-circle-left"></i></a>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div class="card card-custom bg-primary m-5">
                <div class="separator separator-solid separator-white opacity-20"></div>
                <div class="card-body text-white">
                    <div class="row">
                        <table class="text-white font-size-sm" style="margin-right: 100px">
                            <tbody>
                            <tr>
                                <td>PEV#</td>
                                <td>:</td>
                                <td>
                                    <b>{{$pev->pev_num}}</b>
                                </td>
                            </tr>
                            <tr>
                                <td>PEV Date</td>
                                <td>:</td>
                                <td>
                                    {{date('d M Y', strtotime($pev->pre_approved_at))}}
                                </td>
                            </tr>
                            <tr>
                                <td>PRE#</td>
                                <td>:</td>
                                <td>
                                    {{$pev->pre_num}}
                                </td>
                            </tr>
                            <tr>
                                <td>PRE Date</td>
                                <td>:</td>
                                <td>
                                    {{date('d M Y', strtotime($pev->fr_approved_at))}}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <table class="text-white font-size-sm">
                            <tbody>
                            <tr>
                                <td>Division</td>
                                <td>:</td>
                                <td>
                                    <b>{{$pev->division}}</b>
                                </td>
                            </tr>
                            <tr>
                                <td>Created By</td>
                                <td>:</td>
                                <td>
                                    {{$pev->request_by}}
                                </td>
                            </tr>
                            <tr>
                                <td>Created Date</td>
                                <td>:</td>
                                <td>
                                    {{date('d M Y', strtotime($pev->fr_date))}}
                                </td>
                            </tr>
                            <tr>
                                <td>Project</td>
                                <td>:</td>
                                <td>
                                    {{$pro[$pev->project]}}
                                </td>
                            </tr>
                            <tr>
                                <td>Note</td>
                                <td>:</td>
                                <td>
                                    {{$pev->pre_approved_notes}}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="separator separator-dashed separator-border-2 separator-primary"></div>
            <div class="m-5">
                <form action="{{$link_post}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="status" value="{{$status}}">
                    <table class="table display table-responsive-xl table-bordered">
                        <thead>
                        <tr>
                            <th class="text-center" rowspan="2">No</th>
                            <th class="text-center" rowspan="2">Item Name</th>
                            <th class="text-center" rowspan="2">Qty</th>
                            <th class="text-center" rowspan="2">Previous Price Per Unit</th>
                            @php
                                $valSup = [];
                                if(!empty($pev->suppliers)){
                                    $valSup = json_decode($pev->suppliers, true);
                                }
                            @endphp
                            @for($i=0; $i<3; $i++)
                                <th class="text-center" colspan="2">
                                    @php
                                        $num = $i;
                                    @endphp
                                    Alternative: {{$num + 1}}
                                    <div>
                                        <select name="vendor[{{$i}}]" class="form-control select2" id="" {{($i == 0 && $pev->pev_date != null) ? "required" : ""}}>
                                            <option value="">Select Vendor</option>
                                            <?php $SELECTED = "" ?>
                                            @foreach($vendors as $vendor)
                                                @if($pev->suppliers != null || $pev->suppliers != "")
                                                    <?php $valSup = json_decode($pev->suppliers);
                                                    if (is_array($valSup)) {
                                                        if($valSup[$i] == $vendor->id){
                                                            $SELECTED = "SELECTED";
                                                        } else {
                                                            $SELECTED = "";
                                                        }
                                                    } else {
                                                        $SELECTED = "";
                                                    }

                                                    ?>
                                                @endif
                                                <option value="{{$vendor->id}}" {{$SELECTED}}>{{$vendor->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </th>
                            @endfor
                        </tr>
                        <tr>
                            @for($i=0; $i<3; $i++)
                                <th class="text-center">
                                    Unit Price
                                </th>
                                <th class="text-center">
                                    Total
                                </th>
                            @endfor
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $sumprice = array();
                        ?>
                            @foreach($items as $key => $item)
                                <tr>
                                    <td align="center">{{$key + 1}}</td>
                                    <td>
                                        {{"[".$item->item_id."] "}}{{(isset($item_name[$item->item_id]))?$item_name[$item->item_id]:""}}
                                        <input type="hidden" name="id_item[]" value="{{$item->id}}">
                                    </td>
                                    <td align="center">
                                        {{$item->qty_appr." "}}{{(isset($uom[$item->item_id]))?$uom[$item->item_id]:""}}
                                        <input type="hidden" name="qty[{{$item->id}}]" id="qty_{{$item->id}}" value="{{$item->qty_appr}}">
                                    </td>
                                    <td align="center">
                                        @if(!empty($prices[$item->item_id]))
                                            {{number_format($prices[$item->item_id][0], 2)}}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    @for($i=0; $i<3; $i++)
                                        <td class="text-center">
                                            <div class="radio-inline">
                                                <label class="radio radio-outline radio-success">
                                                    <input type="radio" {{($i == 0 && $pev->pev_date != null) ? "required" : ""}} name="radio[{{$item->id}}]" {{($item->supp_idx == $i && isset($item->supp_idx)) ? "CHECKED" : ""}} value="{{"idx-".$i}}"/>
                                                    <span></span>
                                                </label>
                                                <?php
                                                    $iprice = json_decode($item->price);
                                                    $pr_i = 0;
                                                    if (is_array($iprice)) {
                                                        if (isset($iprice[$i]) && !empty($iprice[$i])) {
                                                            $pr_i = $iprice[$i];
                                                        }
                                                    }
                                                 ?>
                                                <input type="text" step=".01" onchange="calc('{{$item->id}}', '{{$i}}')" name="unit_price[{{$item->id}}][{{$i}}]" id="unit_price_{{$item->id}}_{{$i}}" value="{{$pr_i}}" class="form-control number">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $sum = 0;
                                            $price = $pr_i * $item->qty_appr;
                                            $sumprice[$i][$item->id] = $price;
                                            ?>
                                            <input type="text" readonly value="{{$price}}" id="total_price_{{$item->id}}_{{$i}}" class="form-control total_{{$i}}">
                                        </td>
                                    @endfor
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" align="right">
                                    <b>Total </b>
                                </td>
                                <td></td>
                                @for($i=0; $i<3; $i++)
                                    <td class="text-center">
                                    </td>
                                    <td class="text-center">
                                        <input type="text" value="{{(isset($sumprice[$i]))?($sumprice[$i] != null) ? round(array_sum($sumprice[$i])) : 0: 0}}" id="total_{{$i}}" class="form-control">

                                    </td>
                                @endfor
                            </tr>
                            <tr>
                                <td colspan="3" align="right">
                                    <b>Discount</b>
                                </td>
                                <td></td>
                                @for($i=0; $i<3; $i++)
                                    <td class="text-center">
                                    </td>
                                    <td class="text-center">
                                        <input type="text" step=".01" onchange="sum_calc('{{$i}}')" value="{{(is_array(json_decode($pev->discs)))?(!empty(json_decode($pev->discs)[$i])) ? json_decode($pev->discs)[$i] : 0 : 0}}" min="0" name="discount[{{$i}}]" id="discount_{{$i}}" class="form-control number">

                                    </td>
                                @endfor
                            </tr>
                        @foreach($taxes as $keyTax => $value)
                            <tr>
                                <td colspan="3" align="right">
                                    <b>{{$value->tax_name}}</b>
                                </td>
                                <td></td>
                                @for($i=0; $i<3; $i++)
                                    <td class="text-center" align="center">
                                        <div class="checkbox-inline justify-content-center">
                                            <label class="checkbox checkbox-outline checkbox-success" id="lbl_check_{{$value->id}}_{{$i}}">
                                                <?php
                                                $PPN = "";
                                                if (is_array(json_decode($pev->ppns))) {
                                                    if (!empty(json_decode($pev->ppns)[$i])){
                                                        foreach (json_decode($pev->ppns)[$i] as $valPpn){
                                                            if ($value->id == $valPpn){
                                                                $PPN = "CHECKED";
                                                            }
                                                        }
                                                    }
                                                }

                                                ?>
                                                <input type="checkbox" name="tax[{{$i}}][{{$keyTax}}]" value="{{$value->id}}" onclick="isChecked('{{$value->id}}', '{{$i}}')" id="check_{{$value->id}}_{{$i}}" {{$PPN}}/>
                                                <span></span>
                                            </label>
                                        </div>
                                    </td>
                                    <td align="center">
                                        <input type="text" value="0" id="tax_{{$value->id}}_{{$i}}" class="form-control tax_{{$i}}">
                                    </td>
                                @endfor
                            </tr>
                        @endforeach
                            <tr>
                                <td colspan="3" align="right">
                                    <b>Down Payment</b>
                                </td>
                                <td></td>
                                @for($i=0; $i<3; $i++)
                                    <td class="text-center">
                                    </td>
                                    <td class="text-center">
                                        <input type="text" onchange="sum_calc('{{$i}}')" name="dp[{{$i}}]" value="{{(is_array(json_decode($pev->dps)))?(!empty(json_decode($pev->dps)[$i])) ? json_decode($pev->dps)[$i] : 0 : 0}}" id="dp_{{$i}}" class="form-control number">

                                    </td>
                                @endfor
                            </tr>
                            <tr>
                                <td colspan="3" align="right">
                                    <b>Grand Total</b>
                                </td>
                                <td></td>
                                @for($i=0; $i<3; $i++)
                                    <td class="text-center">
                                    </td>
                                    <td class="text-center">
                                        <input type="text" readonly value="0" id="g_total_{{$i}}" class="form-control">
                                    </td>
                                @endfor
                            </tr>
                            <tr>
                                <td colspan="3" align="right">
                                    <b>Currency</b>
                                </td>
                                <td></td>
                                 @for($i=0; $i<3; $i++)
                                <td class="text-center" colspan="2">
                                    <?php
                                    if (!empty($pev->currencies)){
                                        if (is_array(json_decode($pev->currencies))) {
                                        $cur[$i] = json_decode($pev->currencies)[$i];


                                        } else {
                                            $cur[$i] = "IDR";
                                        }
                                    } else {
                                        $cur[$i] = "IDR";
                                    } ?>
                                    <select name="currency[{{$i}}]" class="form-control select2" required>
                                        @foreach(json_decode($list_currency) as $key => $value)
                                            <option value="{{$key}}" {{($cur[$i] == $key) ? "selected" : ""}}>{{strtoupper($key."-".$value)}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            @endfor
                            </tr>
                            <tr>
                                <td colspan="3" align="right">
                                    <b>Deliver To</b>
                                </td>
                                <td></td>
                                @for($i=0; $i<3; $i++)
                                <td class="text-center" colspan="2">
                                    <textarea name="d_to[{{$i}}]" id="" style="height: 50px" class="form-control">{{(is_array(json_decode($pev->delivers)))?(!empty(json_decode($pev->delivers)[$i])) ? json_decode($pev->delivers)[$i] : null : null}}</textarea>
                                </td>
                            @endfor
                            </tr>
                            <tr>
                                <td colspan="3" align="right">
                                    <b>Deliver Time</b>
                                </td>
                                <td></td>
                               @for($i=0; $i<3; $i++)
                                <td class="text-center" colspan="2">
                                    <textarea name="d_time[{{$i}}]" id="" style="height: 50px" class="form-control">{{(is_array(json_decode($pev->deliver_times)))?(!empty(json_decode($pev->deliver_times)[$i])) ? json_decode($pev->deliver_times)[$i] : null:null}}</textarea>
                                </td>
                            @endfor
                            </tr>
                            <tr>
                                <td colspan="3" align="right">
                                    <b>Terms</b>
                                </td>
                                <td></td>
                                @for($i=0; $i<3; $i++)
                                <td class="text-center" colspan="2">
                                    <textarea name="terms[{{$i}}]" id="" style="height: 50px" class="form-control">{{(is_array(json_decode($pev->terms)))?(!empty(json_decode($pev->terms)[$i])) ? json_decode($pev->terms)[$i] : null:null}}</textarea>
                                </td>
                            @endfor
                            </tr>
                            <tr>
                                <td colspan="3" align="right">
                                    <b>Terms of Payment</b>
                                </td>
                                <td></td>
                                @for($i=0; $i<3; $i++)
                                <?php
                                if (!empty($pev->tops)){
                                    if (is_array(json_decode($pev->tops))) {
                                    $tops[$i] = json_decode($pev->tops)[$i];


                                    } else {
                                        $tops[$i] = "";
                                    }
                                } else {
                                    $tops[$i] = "";
                                }
                                ?>
                                <td class="text-center" colspan="2">
                                    <select name="terms_pay[{{$i}}]" class="form-control select2" id="">
                                        <option value=""></option>
                                        <option value="Cash On Delivery" {{($tops[$i] == "Cash On Delivery") ? "SELECTED" : ""}}>Cash On Delivery</option>
                                        <option value="1 week" {{($tops[$i] == "1 week") ? "SELECTED" : ""}}>1 week</option>
                                        <option value="2 weeks" {{($tops[$i] == "2 weeks") ? "SELECTED" : ""}}>2 weeks</option>
                                        <option value="1 month" {{($tops[$i] == "1 month") ? "SELECTED" : ""}}>1 month</option>
                                        <option value="2 months" {{($tops[$i] == "2 months") ? "SELECTED" : ""}}>2 months</option>
                                        <option value="3 months" {{($tops[$i] == "3 months") ? "SELECTED" : ""}}>3 months</option>
                                    </select>
                                </td>
                            @endfor
                            </tr>
                            <tr>
                                <td colspan="3" align="right">
                                    <b>Notes</b>
                                </td>
                                <td></td>
                                @for($i=0; $i<3; $i++)
                                <td class="text-center" colspan="2">
                                    <textarea name="notes[{{$i}}]" id="" style="height: 50px" class="form-control">{{(is_array(json_decode($pev->pev_notes)))?(!empty(json_decode($pev->pev_notes)[$i])) ? json_decode($pev->pev_notes)[$i] : null:null}}</textarea>
                                </td>
                            @endfor
                            </tr>
                            <tr>
                                <td colspan="3" align="right">
                                    <b>Quotation</b>
                                </td>
                                <td></td>
                                @for($i=0; $i<3; $i++)
                                    <td class="text-center" colspan="2">
                                        <?php
                                    $attach = json_decode($pev->attach1);

                                     ?>
                                     @if(isset($attach[$i]))
                                        <a href="{{ str_replace("public", "public_html", asset('media/asset/'.$attach[$i])) }}" class="btn btn-xs btn-icon btn-success" target="_blank" download><i class="fa fa-download"></i></a>
                                     @else
                                     <input type="file" name="file_quot[{{$i}}]">
                                     @endif
                                    </td>
                                @endfor
                            </tr>
                        </tfoot>
                    </table>
                    @if($status == "dir")
                        <hr>
                        <h3 class="m-5">Confirmation</h3>
                        <div class="col-md-6">
                            <textarea name="pev_notes" class="form-control"></textarea>
                        </div>
                        <hr>
                        <button type="buttton" onclick="button_reject({{$pev->id}})" name="reject" id="btn-reject" class="btn btn-danger btn-xs"> Reject</button>
                    @endif
                    <input type="hidden" name="id_fr" value="{{$pev->id}}">
                    <button type="submit" class="btn btn-success btn-xs pull-right"> {{($status == "input") ? "Save" : "Approve"}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <!--begin::Page Vendors(used by this page)-->
    <script src="{{asset('theme/tinymce/tinymce.min.js')}}"></script>
    <!--end::Page Vendors-->
    <!--begin::Page Scripts(used by this page)-->
    <script src="{{asset('assets/jquery-number/jquery.number.js')}}"></script>
    <script>
        function button_reject(x){
            Swal.fire({
                title: "Reject",
                text: "Are you sure you want to reject?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Reject",
                cancelButtonText: "Cancel",
                reverseButtons: true,
            }).then(function(result){
                if(result.value){
                    $.ajax({
                        url: '{{URL::route('pe.reject')}}',
                        data: {
                            '_token': '{{csrf_token()}}',
                            'id': x
                        },
                        type: "POST",
                        cache: false,
                        dataType: 'json',
                        success : function(response){
                            if (response.del = 1){
                                window.location = "{{URL::route('pe.index')}}"
                            } else {
                                Swal.fire({
                                    title: "Reject Error",
                                    icon: "error"
                                })
                            }
                        }
                    })
                }
            })
        }

        function calc(id, index){
            var qty = "#qty_" + id
            var qty_val = $(qty).val()
            var up = "#unit_price_" + id + "_" + index
            var up_val = $(up).val()
            var tot_up = "#total_price_" + id + "_" + index
            var tot = parseFloat(up_val) * parseFloat(qty_val)
            $(tot_up).val(tot.toFixed(2))
            var tot_up_class = "total_" + index
            var tot_up_class = document.getElementsByClassName(tot_up_class)
            var tot = 0
            for (let j = 0; j < tot_up_class.length; j++) {
                tot += parseFloat(tot_up_class[j].value)
            }

            console.log("total " + index + " = " + tot)

            var to = "#total_" + index
            $(to).val(tot.toFixed(2))
            sum_calc(index)
        }

        function sum_calc(index){
            var to = "#total_" + index
            var disc = "#discount_" + index
            var gt = "#g_total_" + index
            var dp = "#dp_" + index
            if ($(disc).val() == "") {
                $(disc).val('0')
            }
            var taxes = "tax_" + index
            var taxes_val = document.getElementsByClassName(taxes)
            var tax = 0
            for (let i = 0; i < taxes_val.length ; i++) {
                tax += parseFloat(taxes_val[i].value)
            }
            var grand = parseFloat($(to).val()) - parseFloat($(disc).val()) + tax - parseFloat($(dp).val())
            $(gt).val(grand.toFixed(2))
        }

        function isChecked(id, index){
            var conflict = '{{$conflict}}'.replaceAll("&quot;", "\"")
            var formula = '{{$formula}}'.replaceAll("&quot;", "\"")
            var jsonConflict = JSON.parse(conflict)
            var jsonFormula = JSON.parse(formula)
            var check = "#check_" + id + "_" + index
            if ($(check).prop("checked") == true){
                var to = "#total_" + index
                var tax = "#tax_" + id + "_" + index
                var disc = "#discount_" + index
                var $sum = parseFloat($(to).val()) - parseFloat($(disc).val())
                var tax_val = eval(jsonFormula[id])
                $(tax).val(tax_val.toFixed(2))
                console.log(jsonConflict[id])
                if (jsonConflict[id] != null){
                    for (let i = 0; i < jsonConflict[id].length; i++) {
                        var oc = "#check_" + jsonConflict[id][i] + "_" + index
                        var lbl = "#lbl_check_" + jsonConflict[id][i] + "_" + index
                        if ($(oc).prop("checked") == true) {
                            $(oc).prop("checked", false)
                            var otax = "#tax_" + jsonConflict[id][i] + "_" + index
                            $(otax).val("0")
                        }
                        $(oc).attr("disabled", true)
                        $(lbl).addClass("checkbox-disabled")
                        console.log(oc + " disabled")
                    }
                }
            } else {
                var tax = "#tax_" + id + "_" + index
                $(tax).val("0")
                if (jsonConflict[id] != null){
                    for (let i = 0; i < jsonConflict[id].length; i++) {
                        var oc = "#check_" + jsonConflict[id][i] + "_" + index
                        var lbl = "#lbl_check_" + jsonConflict[id][i] + "_" + index
                        $(oc).attr("disabled", false)
                        $(lbl).removeClass("checkbox-disabled")
                        console.log(oc + " enabled")
                    }
                }
            }

            sum_calc(index)
        }

        $(document).ready(function(){
            $("#btn-reject").click(function(event){
                event.preventDefault()
            })

            for (let i = 0; i < 3; i++) {
                sum_calc(i)
            }

            tinymce.init({
                editor_selector : "textarea.form-control",
                selector:'textarea',
                mode : "textareas",
                menubar: false,
                toolbar: false
            });

            var jsonidtax = '{{json_encode($id_tax)}}'.replaceAll("&quot;", "\"")
            var id_tax = JSON.parse(jsonidtax)
            console.log(id_tax)

            for (let index = 0; index < 3; index++) {
                for (let i = 0; i < id_tax.length; i++) {
                    var conflict = '{{$conflict}}'.replaceAll("&quot;", "\"")
                    var formula = '{{$formula}}'.replaceAll("&quot;", "\"")
                    var jsonConflict = JSON.parse(conflict)
                    var jsonFormula = JSON.parse(formula)
                    var check = "#check_" + id_tax[i] + "_" + index
                    var to = "#total_" + index
                    var tax = "#tax_" + id_tax[i] + "_" + index
                    var disc = "#discount_" + index
                    if ($(check).is(":checked")){
                        var $sum = parseFloat($(to).val()) - parseFloat($(disc).val())
                        var tax_val = eval(jsonFormula[id_tax[i]])
                        $(tax).val(tax_val)
                        console.log(jsonConflict[id_tax[i]])
                        if (jsonConflict[id_tax[i]] != null){
                            for (let j = 0; j < jsonConflict[id_tax[i]].length; j++) {
                                var oc = "#check_" + jsonConflict[id_tax[i]][j] + "_" + index
                                var lbl = "#lbl_check_" + jsonConflict[id_tax[i]][j] + "_" + index
                                $(oc).attr("disabled", true)
                                $(lbl).addClass("checkbox-disabled")
                                console.log(oc + " disabled")
                            }
                        }

                        sum_calc(index)
                    }
                }
            }

            $("select.select2").select2({
                width: 200
            })
            $("table.display").DataTable({
                "searching": false,
                "lengthChange": false,
                "ordering": false,
                "aaSorting": [],
                "paging":   false,
                "info":     false,
            })

        })
        $(".number").number(true, 2)
    </script>
@endsection
