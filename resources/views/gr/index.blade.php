@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Good Received</h3><br>
            </div>
            <div class="card-toolbar">

                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#all">
                        <span class="nav-icon">
                            <i class="flaticon-folder-1"></i>
                        </span>
                        <span class="nav-text">[GR] Good Received Waiting</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#sales" aria-controls="profile">
                        <span class="nav-icon">
                            <i class="flaticon-folder-2"></i>
                        </span>
                        <span class="nav-text">[GR] Good Received Bank</span>
                    </a>
                </li>

            </ul>
            <div class="tab-content mt-5" id="myTabContent">
                <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="home-tab">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                            <thead>
                            <tr>
                                <th class="text-center">#</th>
{{--                                <th class="text-center">Reference</th>--}}
                                <th class="text-center">PO#</th>
                                <th class="text-center">PO Type</th>
                                <th class="text-center">Request by</th>
                                <th class="text-center">Request Date</th>
                                <th class="text-center">Project</th>
                                <th class="text-center">Company</th>
                                <th class="text-center">Supplier</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">GR Date</th>
                                <th class="text-center"></th>
                            </tr>
                            </thead>
                            <tbody>
                                @php
                                    $num = 1;
                                @endphp
                            @foreach($po as $key => $po_item)
                                @if($po_item->rejected_by == null & $po_item->gr_date == null)
                                    <tr>
                                        <td align="center">{{$num++}}</td>
{{--                                        <td align="center">{{$po_item->reference}}</td>--}}
                                        <td align="center">
                                            <a href="{{URL::route('gr.detail', ['id' =>$po_item->id])}}" class="text-hover-danger">{{$po_item->po_num}}</a>
                                        </td>
                                        <td align="center">{{$po_item->po_type}}</td>
                                        <td align="center">{{$po_item->request_by}}</td>
                                        <td align="center">{{date('d F Y', strtotime($po_item->po_date))}}</td>
                                        <td align="center">{{(isset($pro_name[$po_item->project]) ? $pro_name[$po_item->project] : "")}}</td>
                                        <td align="center">{{$view_company[$po_item->company_id]->tag}}</td>
                                        <td align="center">{{(isset($vendor_name[$po_item->supplier_id])) ? $vendor_name[$po_item->supplier_id] : ""}}</td>
                                        <td align="center">
                                            <?php
                                            $amount = 0;
                                            /** @var TYPE_NAME $qty_det */
                                            /** @var TYPE_NAME $po_item */
                                            if (isset($qty_det[$po_item->id])){
                                                foreach ($qty_det[$po_item->id] as $k => $v){
                                                    /** @var TYPE_NAME $price_det */
                                                    $amount += $v * $price_det[$po_item->id][$k];
                                                }
                                            }

                                            if ($po_item->ppn != null){
                                                $ppns = json_decode($po_item->ppn);
                                                $ppn_sum = 0;
                                                if (is_array($ppns)){
                                                    foreach ($ppns as $p){
                                                        $sum = $amount;
                                                        $ma ='$sum * 0.1';
                                                        /** @var TYPE_NAME $formula */
                                                        $p = eval('return '.$formula[$p].';');
                                                        $ppn_sum += $p;
//                                                        $amount += $ppn_sum;
                                                    }
                                                }

                                                $amount += $ppn_sum;
                                            }
                                            ?>
                                            {{number_format($amount, 2)}}
                                        </td>
                                        <td align="center">
                                            @if($po_item->gr_date == null)
                                                <a href="{{URL::route('gr.detail', ['id' =>$po_item->id,'type'=>'appr'])}}" class="btn btn-link btn-xs">waiting <i class="fa fa-clock"></i></a>
                                            @else
                                                {{date('Y-m-d', strtotime($po_item->gr_date))}}
                                            @endif
                                        </td>
                                        <td align="center">
                                            @actionStart('gr', 'delete')
                                            <button class="btn btn-icon btn-xs btn-danger"><i class="fa fa-trash"></i></button>
                                            @actionEnd
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="sales" role="tabpanel" aria-labelledby="profile-tab">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                            <thead class="table-success">
                            <tr>
                                <th class="text-center">#</th>
{{--                                <th class="text-center">Reference</th>--}}
                                <th class="text-center">PO#</th>
                                <th class="text-center">PO Type</th>
                                <th class="text-center">Request by</th>
                                <th class="text-center">Request Date</th>
                                <th class="text-center">Project</th>
                                <th class="text-center">Company</th>
                                <th class="text-center">Supplier</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">GR Date</th>
                                {{-- <th class="text-center"></th> --}}
                            </tr>
                            </thead>
                            <tbody>
                                @php
                                    $num = 1;
                                @endphp
                            @foreach($po as $key => $po_item)
                                @if($po_item->gr_date != null)
                                    <tr>
                                        <td align="center">{{$num++}}</td>
{{--                                        <td align="center">{{$po_item->reference}}</td>--}}
                                        <td align="center">
                                            <a href="{{URL::route('gr.detail', ['id' =>$po_item->id])}}" class="text-hover-danger">{{$po_item->po_num}}</a>
                                        </td>
                                        <td align="center">{{$po_item->po_type}}</td>
                                        <td align="center">{{$po_item->request_by}}</td>
                                        <td align="center">{{date('d F Y', strtotime($po_item->po_date))}}</td>
                                        <td align="center">{{(isset($pro_name[$po_item->project])) ? $pro_name[$po_item->project] : ""}}</td>
                                        <td align="center">{{$view_company[$po_item->company_id]->tag}}</td>
                                        <td align="center">{{(isset($vendor_name[$po_item->supplier_id])) ? $vendor_name[$po_item->supplier_id] : ""}}</td>
                                        <td align="center">
                                            <?php
                                            $amount =0;
                                            if (isset($qty_det[$po_item->id])){
                                                foreach ($qty_det[$po_item->id] as $k => $v){
                                                    $amount += $v * $price_det[$po_item->id][$k];
                                                }
                                            }

                                            if ($po_item->ppn != null){
                                                $ppns = json_decode($po_item->ppn);
                                                $ppn_sum = 0;
                                                if (is_array($ppns)){
                                                    foreach ($ppns as $p){
                                                        $sum = $amount;
                                                        $ma ='$sum * 0.1';
                                                        $p = eval('return '.$formula[$p].';');
                                                        $ppn_sum += $p;
                                                        //                                                        $amount += $ppn_sum;
                                                    }
                                                }
                                                $amount += $ppn_sum;
                                            }

                                            ?>
                                            {{number_format($amount, 2)}}
                                        </td>
                                        <td align="center">
                                            @if($po_item->gr_date == null)
                                                <a href="{{URL::route('gr.detail', ['id' =>$po_item->id,'type'=>'appr'])}}" class="btn btn-link btn-xs">waiting <i class="fa fa-clock"></i></a>
                                            @else
                                                {{date('Y-m-d', strtotime($po_item->gr_date))}}
                                            @endif
                                        </td>
                                        {{-- <td align="center">
                                            <button class="btn btn-icon btn-xs btn-danger"><i class="fa fa-trash"></i></button>
                                        </td> --}}
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <link href="{{asset('theme/jquery-ui/jquery-ui.css')}}" rel="Stylesheet">
    <script src="{{asset('theme/jquery-ui/jquery-ui.js')}}"></script>
    <script>

        function sum_sub_total() {
            var items = $(".total-item").toArray()
            var dp = $("#dp").val()
            console.log(dp)
            var discount = $("#discount").val()

            var sum = 0;
            for (let i = 0; i < items.length; i++) {
                sum += parseInt(items[i].innerText)
            }

            var json_formula = "{{json_encode($formula)}}".replaceAll("&quot;", "\"")
            var formula = JSON.parse(json_formula)
            console.log(formula)

            //TAX

            var ck_tax = $(".ck-tax").toArray()
            var val_tax = $(".tax-val").toArray()
            var $sum = sum;
            var sum_tax = 0;
            for (let i = 0; i < ck_tax.length; i++) {
                var tax = 0
                if (ck_tax[i].checked){
                    tax = eval(formula[ck_tax[i].value])
                    val_tax[i].innerText = tax
                } else {
                    tax = 0
                    val_tax[i].innerText = tax
                }

                sum_tax += parseInt(val_tax[i].innerText)
            }

            $("#sub-total").text(sum)
            var incdiscount = sum - discount
            $("#inc-discount").text(incdiscount)
            var total = incdiscount - dp + sum_tax
            $("#total").text(total)
            // TAX
        }

        function addInput(trName) {
            var newrow = document.createElement('tr');
            var total = (parseInt($("#qty").val()) * parseInt($("#price").val()))
            newrow.innerHTML = "<td align='center'>" +
                "<input type='hidden' name='id_item[]' value='" + $("#id").val() + "'>" +
                "<input type='hidden' name='code[]' value='" + $("#code").val() + "'><b>" + $("#code").val() + "</b><br />" +
                "<input type='hidden' name='name[]' value='" + $("#name").val() + "'><b>" + $("#name").val() + "</b><br />" +
                "<input type='hidden' name='uom[]' value='" + $("#uom").val() + "'>(" + $("#uom").val() + ")" +
                "</td>" +
                "<td align='center'>" +
                "<input type='hidden' name='qty[]' value='" + $("#qty").val() + "'>" + $("#qty").val() +
                "</td>" +
                "<td align='center'>" +
                "<input type='hidden' name='price[]' value='" + $("#price").val() + "'>" + $("#price").val() +
                "</td>" +
                "<td align='right'>" +
                "<span class='total-item'>"+total+"</span>" +
                "</td>" +
                "<td align='center'>" +
                "<button type='submit' onClick='deleteRow(this)' class='btn btn-xs btn-danger btn-icon'><i class='fa fa-trash'></i></button>" +
                "</td>";
            document.getElementById(trName).appendChild(newrow);
            $("#search").val("");
            $("#qty").val("");
            $("#price").val("");
            sum_sub_total()
        }

        function deleteRow(o){
            var p = o.parentNode.parentNode;
            p.parentNode.removeChild(p);
        }

        $(document).ready(function(){

            $("#btn-times").hide()

            $("#discount").on('keyup',function(){
                sum_sub_total()
            })

            $("#btn-validate").click(function () {
                var code = $("#paper_code").val()
                $.ajax({
                    url: "{{route('ha.powoval.find')}}/po/" + code,
                    type: "get",
                    dataType: "json",
                    cache: false,
                    success: function(response){
                        if (response.data == 1){
                            Swal.fire('Found', 'Validation Code Found', 'success')
                            $("#btn-save-instant").removeClass('disabled')
                            $("#btn-validate").addClass('disabled')
                            $("#paper_code").attr('readonly', 'readonly')
                            $("#btn-times").show()
                        } else if (response.data == 0){
                            Swal.fire('Not Found', 'Validation Code Not Found', 'warning')
                            $("#btn-save-instant").addClass('disabled', true)
                        } else if (response.data == 2) {
                            Swal.fire('Found', 'Validation Code has been used', 'warning')
                            $("#btn-save-instant").addClass('disabled', true)
                        }
                    }
                })
            })

            $("#btn-times").click(function(){
                $("#paper_code").val('')
                $("#paper_code").attr('readonly', false)
                $("#btn-validate").removeClass('disabled')
                $("#btn-save-instant").addClass('disabled', true)
                $("#btn-times").hide()
            })

            $("#dp").on('keyup',function(){
                sum_sub_total()
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

            $("#search").autocomplete({
                source: "{{route('fr.getItems')}}",
                minLength: 1,
                appendTo: "#autocomplete-div",
                select: function(event, ui) {
                    $('#category').val(ui.item.item_category);
                    $('#id').val(ui.item.item_id);
                    $('#code').val(ui.item.item_code);
                    $('#name').val(ui.item.item_name);
                    $('#uom').val(ui.item.item_uom);
                }
            })
        })
    </script>
@endsection
