@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Purchase Order</h3><br>
            </div>
            <div class="card-toolbar">
                @actionStart('po', 'create')
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addInstant"><i class="fa fa-plus"></i>Add PO Instant</button>
                </div>
                @actionEnd
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-5 mt-5">
                <div class="col-md-12">
                    <img src="{{asset('media/po.png')}}" style="width: 35%">
                </div>
            </div>
            <span class="form-text text-muted">Total PO (Not yet goods received) : 0.00 <button type="button" class="btn btn-secondary btn-xs"><i class="fa fa-check"></i> ppn</button></span>
            <hr>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#all">
                        <span class="nav-icon">
                            <i class="flaticon-folder-1"></i>
                        </span>
                        <span class="nav-text">[PO] Purchase Order Waiting</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#sales" aria-controls="profile">
                        <span class="nav-icon">
                            <i class="flaticon-folder-2"></i>
                        </span>
                        <span class="nav-text">[PO] Purchase Order Bank</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#cost" aria-controls="profile">
                        <span class="nav-icon">
                            <i class="flaticon-folder-3"></i>
                        </span>
                        <span class="nav-text">[PO] Purchase Order Rejected</span>
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
                                <th class="text-center">Reference</th>
                                <th class="text-center">PO#</th>
                                <th class="text-center">PO Type</th>
                                <th class="text-center">Request by</th>
                                <th class="text-center">Request Date</th>
                                <th class="text-center">Project</th>
                                <th class="text-center">Company</th>
                                <th class="text-center">Supplier</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Approved</th>
                                <th class="text-center"></th>
                            </tr>
                            </thead>
                            <tbody>
                                @actionStart('po', 'read')
                                <?php $i = 1; ?>
                                @foreach($po as $key => $po_item)
                                    @if($po_item->rejected_by == null & $po_item->approved_by == null)
                                    <tr>
                                        <td align="center">{{$i++}}</td>
                                        <td align="center">{{$po_item->reference}}</td>
                                        <td align="center">
                                            <a href="{{URL::route('po.view', $po_item->id)}}" class="text-hover-danger">{{$po_item->po_num}}</a>
                                        </td>
                                        <td align="center">{{$po_item->po_type}}</td>
                                        <td align="center">{{$po_item->request_by}}</td>
                                        <td align="center">{{date('d F Y', strtotime($po_item->po_date))}}</td>
                                        <td align="center">{{(isset($pro_name[$po_item->project])) ? $pro_name[$po_item->project] : "N/A"}}</td>
                                        <td align="center">{{$view_company[$po_item->company_id]->tag}}</td>
                                        <td align="center">{{isset($vendor_name[$po_item->supplier_id])?$vendor_name[$po_item->supplier_id] : ''}}</td>
                                        <td align="center">
                                            <?php
                                            $amount =0;
                                                if(isset($qty_det[$po_item->id])){
                                                foreach ($qty_det[$po_item->id] as $k => $v){
                                                    $amount += $v * $price_det[$po_item->id][$k];
                                                }
                                                if ($po_item->ppn != null){
                                                    $ppns = json_decode($po_item->ppn);
                                                    $ppn_sum = 0;
                                                    if (is_array($ppns)) {
                                                        foreach ($ppns as $p){
                                                            $sum = $amount;
                                                            $ma ='$sum * 0.1';
                                                            $p = eval('return '.$formula[$p].';');
                                                            $ppn_sum += $p;
    //                                                        $amount += $ppn_sum;
                                                        }
                                                        $amount += $ppn_sum;
                                                    }
                                                    
                                                }
                                            }
                                            ?>
                                            {{number_format($amount, 2)}}
                                        </td>
                                        <td align="center">
                                            @if($po_item->approved_by == null)
                                                <a href="{{URL::route('po.appr', $po_item->id)}}" class="btn btn-link btn-xs">waiting <i class="fa fa-clock"></i></a>
                                            @else
                                                approved at {{date('Y-m-d', strtotime($po_item->approved_time))}}
                                            @endif
                                        </td>
                                        <td align="center">
                                            @actionStart('po', 'delete')
                                            <button onclick='delete_po({{$po_item->id}})' class="btn btn-icon btn-xs btn-danger"><i class="fa fa-trash"></i></button>
                                            @actionEnd
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                                @actionEnd
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
                                <th class="text-center">Reference</th>
                                <th class="text-center">PO#</th>
                                <th class="text-center">PO Type</th>
                                <th class="text-center">Request by</th>
                                <th class="text-center">Request Date</th>
                                <th class="text-center">Project</th>
                                <th class="text-center">Company</th>
                                <th class="text-center">Supplier</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Approved</th>
                                <th class="text-center"></th>
                            </tr>
                            </thead>
                            <tbody>
                                @actionStart('po', 'read')
                            @foreach($po as $key => $po_item)
                                @if($po_item->approved_by != null)
                                    <tr>
                                        <td align="center">{{$key + 1}}</td>
                                        <td align="center">{{$po_item->reference}}</td>
                                        <td align="center">
                                            <a href="{{URL::route('po.view', $po_item->id)}}" class="text-hover-danger">{{$po_item->po_num}}</a>
                                        </td>
                                        <td align="center">{{$po_item->po_type}}</td>
                                        <td align="center">{{$po_item->request_by}}</td>
                                        <td align="center">{{date('d F Y', strtotime($po_item->po_date))}}</td>
                                        <td align="center">{{(isset($pro_name[$po_item->project])) ? $pro_name[$po_item->project] : "N/A"}}</td>
                                        <td align="center">{{$view_company[$po_item->company_id]->tag}}</td>
                                        <td align="center">{{isset($vendor_name[$po_item->supplier_id]) ? $vendor_name[$po_item->supplier_id]:''}}</td>
                                        <td align="center">
                                            <?php
                                            $amount =0;
                                            if (isset($qty_det[$po_item->id])) {
                                                foreach ($qty_det[$po_item->id] as $k => $v){
                                                $amount += $v * $price_det[$po_item->id][$k];
                                            }
                                            if ($po_item->ppn != null){
                                                $ppns = json_decode($po_item->ppn);
                                                $ppn_sum = 0;
                                                if (!empty($ppns)) {
                                                    foreach ($ppns as $p){
                                                        $sum = $amount;
                                                        $p = eval('return '.$formula[$p].';');
                                                        $ppn_sum += $p;
    //                                                        $amount += $ppn_sum;
                                                    }
                                                    $amount += $ppn_sum;
                                                }
                                            }    
                                            }
                                            
                                            ?>
                                            {{number_format($amount, 2)}}
                                        </td>
                                        <td align="center">
                                            @if($po_item->approved_by == null)
                                                <a href="{{URL::route('po.appr', $po_item->id)}}" class="btn btn-link btn-xs">waiting <i class="fa fa-clock"></i></a>
                                            @else
                                                approved at {{date('Y-m-d', strtotime($po_item->approved_time))}} by {{$po_item->approved_by}}
                                            @endif
                                        </td>
                                        <td align="center">
                                            @actionStart('po', 'delete')
                                            <button onclick='delete_po({{$po_item->id}})' class="btn btn-icon btn-xs btn-danger"><i class="fa fa-trash"></i></button>
                                            @actionEnd
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            @actionEnd
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="cost" role="tabpanel" aria-labelledby="contact-tab">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                            <thead class="table-danger">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Reference</th>
                                <th class="text-center">PO#</th>
                                <th class="text-center">PO Type</th>
                                <th class="text-center">Request by</th>
                                <th class="text-center">Request Date</th>
                                <th class="text-center">Project</th>
                                <th class="text-center">Company</th>
                                <th class="text-center">Supplier</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Approved</th>
                                <th class="text-center"></th>
                            </tr>
                            </thead>
                            <tbody>
                                @actionStart('po', 'read')
                            @foreach($po as $key => $po_item)
                                @if($po_item->rejected_by != null )
                                    <tr>
                                        <td align="center">{{$key + 1}}</td>
                                        <td align="center">{{$po_item->reference}}</td>
                                        <td align="center">
                                            <a href="{{URL::route('po.view', $po_item->id)}}" class="text-hover-danger">{{$po_item->po_num}}</a>
                                        </td>
                                        <td align="center">{{$po_item->po_type}}</td>
                                        <td align="center">{{$po_item->request_by}}</td>
                                        <td align="center">{{date('d F Y', strtotime($po_item->po_date))}}</td>
                                        <td align="center">{{(isset($pro_name[$po_item->project])) ? $pro_name[$po_item->project] : "N/A"}}</td>
                                        <td align="center">{{$view_company[$po_item->company_id]->tag}}</td>
                                        <td align="center">{{(isset($vendor_name[$po_item->project])) ? $vendor_name[$po_item->project] : ""}}</td>
                                        <td align="center">
                                            <?php
                                            $amount =0;
                                            foreach ($qty_det[$po_item->id] as $k => $v){
                                                $amount += $v * $price_det[$po_item->id][$k];
                                            }
                                            if ($po_item->ppn != null){
                                                $ppns = json_decode($po_item->ppn);
                                                $ppn_sum = 0;
                                                if (!empty($ppns)) {
                                                    foreach ($ppns as $p){
                                                        $sum = $amount;
                                                        $p = eval('return '.$formula[$p].';');
                                                        $ppn_sum += $p;
    //                                                        $amount += $ppn_sum;
                                                    }
                                                    $amount += $ppn_sum;
                                                }
                                            }
                                            ?>
                                            {{number_format($amount, 2)}}
                                        </td>
                                        <td align="center">
                                            rejected at {{$po_item->rejected_time}} by {{$po_item->rejected_by}}
                                        </td>
                                        <td align="center">
                                            @actionStart('po', 'read')
                                            <button onclick='delete_po({{$po_item->id}})' class="btn btn-icon btn-xs btn-danger"><i class="fa fa-trash"></i></button>
                                            @actionEnd
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            @actionEnd
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addInstant" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">PO Instant Form <span id="modal-label"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="POST" action="{{route('po.addInstant')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h3>Form</h3>
                                <hr>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">PO Date</label>
                                    <div class="col-md-8">
                                        <input type="date" class="form-control" name="date" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Project</label>
                                    <div class="col-md-8">
                                        <select name="project" class="form-control select2" id="" required>
                                            <option value="">Select Project</option>
                                            @foreach($pro_name as $key => $item)
                                                <option value="{{$key}}">{{$item}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Category</label>
                                    <div class="col-md-8">
                                        <select class="form-control select2" name="category">
                                            <option value="">Select Category</option>
                                            <option value="bd">Back Date</option>
                                            <option value="ru">Paid by CEO</option>
                                            <option value="psi">Paid by Company</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">PO Type</label>
                                    <div class="col-md-8">
                                        <select class="form-control select2" name="po_type" required>
                                            <option value="">Select Type</option>
                                            @foreach($po_type as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Supplier</label>
                                    <div class="col-md-8">
                                        <select class="form-control select2" name="supplier">
                                            <option value="">Select Supplier</option>
                                            @foreach($vendor_name as $key => $item)
                                                <option value="{{$key}}">{{$item}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Deliver To</label>
                                    <div class="col-md-8">
                                        <textarea name="d_to" class="form-control" id="" cols="30" rows="10"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Deliver Time</label>
                                    <div class="col-md-8">
                                        <textarea name="d_time" class="form-control" id="" cols="30" rows="10"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Currency</label>
                                    <div class="col-md-8">
                                        <select name="currency" class="form-control select2" required>
                                            @foreach(json_decode($list_currency) as $key => $value)
                                                <option value="{{$key}}" {{($key == "IDR") ? "selected" : ""}}>{{strtoupper($key."-".$value)}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Payment Terms</label>
                                    <div class="col-md-8">
                                        <textarea name="p_terms" class="form-control" id="" cols="30" rows="10"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Terms</label>
                                    <div class="col-md-8">
                                        <textarea name="terms" class="form-control" id="" cols="30" rows="10"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Notes</label>
                                    <div class="col-md-8">
                                        <textarea name="notes" class="form-control" id="" cols="30" rows="10"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Validation Code</label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="paper_code" id="paper_code" placeholder=""/>
                                            <div class="input-group-append">
                                                <button class="btn btn-secondary" type="button" id="btn-times"><i class="fa fa-times"></i></button>
                                                <button class="btn btn-primary" type="button" id="btn-validate"><i class="fa fa-search"></i> Validate</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h3>Request Item</h3>
                                <hr>
                                <div class="form-group row">
                                    <table class="table table-responsive-xl col-md-12">
                                        <thead>
                                        <tr class="bg-secondary">
                                            <th class="text-center" nowrap="nowrap">Item Code/Name</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-center">Price</th>
                                            <th class="text-right" nowrap="nowrap">Total Price</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody id="list-item">
                                        <tr>
                                            <td>
                                                <input type="text" class="form-control" placeholder="Item Code/Name" id="search">
                                                <input type="hidden" id="id" />
                                                <input type="hidden" id="code" />
                                                <input type="hidden" id="name" />
                                                <input type="hidden" id="uom" />
                                                <div id="autocomplete-div"></div>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" placeholder="Quantity" id="qty">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" placeholder="Price" id="price">
                                            </td>
                                            <td></td>
                                            <td align="center">
                                                <button type="button" class="btn btn-xs btn-primary btn-icon col-form-label" onclick="addInput('list-item')"><i class="fa fa-plus"></i></button>
                                            </td>
                                        </tr>
                                        </tbody>
                                        <tfoot>
                                        <tr class="bg-secondary">
                                            <th colspan="3" class="text-right">
                                                <b>SUB TOTAL</b>
                                            </th>
                                            <td align="right">
                                                <span id="sub-total"></span>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr class="bg-secondary">
                                            <th colspan="3" class="text-right">
                                                <label for="" class="col-form-label"><b>Discount</b></label>
                                            </th>
                                            <td align="right">
                                                <input type="number" class="form-control" placeholder="Discount" id="discount" name="discount">
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr class="bg-secondary">
                                            <th colspan="3" class="text-right">
                                                <label for="" class="col-form-label"><b>TOTAL INC. DISCOUNT</b></label>
                                            </th>
                                            <td align="right">
                                                <span id="inc-discount"></span>
                                            </td>
                                            <td></td>
                                        </tr>
                                        @foreach($tax as $item)
                                            <tr class="bg-secondary">
                                                <th colspan="3" class="text-right">
                                                    <label for="" class="col-form-label"><b>{{strtoupper($item->tax_name)}}</b></label>&nbsp;
                                                    <input type="checkbox" class="bg-white ck-tax" onchange="sum_sub_total()" value="{{$item->id}}" name="tax[{{$item->id}}]"/>
                                                </th>
                                                <td align="right">
                                                    <span class="tax-val">0</span>
                                                </td>
                                                <td></td>
                                            </tr>
                                        @endforeach
                                        <tr class="bg-secondary">
                                            <th colspan="3" class="text-right">
                                                <label for="" class="col-form-label"><b>DOWN PAYMENT</b></label>
                                            </th>
                                            <td align="right">
                                                <input type="number" class="form-control" placeholder="Down Payment" id="dp" name="dp">
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr class="bg-secondary">
                                            <th colspan="3" class="text-right">
                                                <label for="" class="col-form-label"><b>TOTAL DUE</b></label>
                                            </th>
                                            <td align="right">
                                                <span id="total"></span>
                                            </td>
                                            <td></td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" id="btn-save-instant" class="btn btn-primary font-weight-bold disabled">
                            <i class="fa fa-check"></i>
                            Save</button>
                    </div>
                </form>
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

        function delete_po(x){
            Swal.fire({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
              if (result.isConfirmed) {
                $.ajax({
                    url: "{{route('po.delete')}}/"+x,
                    type: "GET",
                    dataType: "json",
                    success: function(response){
                        if (response.delete === 1) {
                            location.reload()
                        } else {
                            Swal.fire('Error occured', 'Please contact your system administrator', 'error')
                        }
                    }
                })
              }
            })
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
