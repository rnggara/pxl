@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Purchase Order Detail</h3><br>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" onclick="framePrint('print-po')" class="btn btn-primary"><i class="fa fa-print"></i></button>
                    <a href="{{URL::route('po.index')}}" class="btn btn-success btn-xs"><i class="fa fa-arrow-circle-left"></i></a>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <iframe src="{{route('po.print', $po->id)}}" name="print-po" id="print-po" height="0" width="0" frameborder="0"></iframe>
            <div class="card card-custom bg-primary m-5">
                <div class="separator separator-solid separator-white opacity-20"></div>
                <div class="card-body text-white">
                    <div class="row">
                        <table class="text-white font-size-sm" style="margin-right: 100px">
                            <tbody>
                            <tr>
                                <td>PO Number #</td>
                                <td>:</td>
                                <td>
                                    <b>{{$po->po_num}}</b>
                                </td>
                            </tr>
                            <tr>
                                <td>Request Date</td>
                                <td>:</td>
                                <td>
                                    {{date('d M Y', strtotime($po->created_at))}}
                                </td>
                            </tr>
                            <tr>
                                <td>Division</td>
                                <td>:</td>
                                <td>
                                    {{$po->division}}
                                </td>
                            </tr>
                            <tr>
                                <td>Reference</td>
                                <td>:</td>
                                <td>
                                    {{$po->reference}}
                                </td>
                            </tr>
                            <tr>
                                <td>Supplier</td>
                                <td>:</td>
                                <td>
                                    {{(isset($vendor_name[$po->supplier_id])) ? $vendor_name[$po->supplier_id] : ""}}
                                </td>
                            </tr>
                            <tr>
                                <td>Currency</td>
                                <td>:</td>
                                <td>
                                    {{$po->currency}}
                                </td>
                            </tr>
                            <tr>
                                <td>Notes <button data-toggle="modal" data-target="#modalNotes" type="button" class="btn btn-xs btn-icon btn-light-primary"><i class="fa fa-pencil-alt"></i></button></td>
                                <td>:</td>
                                <td>
                                    {!! $po->notes !!}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <table class="text-white font-size-sm">
                            <tbody>
                            <tr>
                                <td>Project</td>
                                <td>:</td>
                                <td>
                                    <b>{{(isset($pro_name[$po->project]))?$pro_name[$po->project]:''}}</b>
                                </td>
                            </tr>
                            <tr>
                                <td>Deliver To</td>
                                <td>:</td>
                                <td>
                                    {!! $po->deliver_to !!}
                                </td>
                            </tr>
                            <tr>
                                <td>Deliver Time</td>
                                <td>:</td>
                                <td>
                                    {!! $po->deliver_time !!}
                                </td>
                            </tr>
                            <tr>
                                <td>Terms</td>
                                <td>:</td>
                                <td>
                                    {!! $po->terms !!}
                                </td>
                            </tr>
                            <tr>
                                <td>Terms of Payment</td>
                                <td>:</td>
                                <td>
                                    {!! $po->payment_term !!}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="separator separator-dashed separator-border-2 separator-primary"></div>
            <div class="m-5">
                <table class="table display table-bordered" width="100%">
                    <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-left">Item Name</th>
                        <th class="text-left">Item Code</th>
                        <th class="text-center">Qty</th>
                        <th class="text-center">UoM</th>
                        <th class="text-right">Price per Unit</th>
                        <th class="text-right">Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $total =0; ?>
                    <?php $total =0; $num = 1; ?>
                    @foreach($po_detail as $key => $item)
                        @if($item->po_num == $po->id)
                            @php
                                $link = null;
                                $bg = "";
                                $id = (isset($item_id[$item->item_id])) ? $item_id[$item->item_id] : null;
                                if(isset($item_type[$item->item_id]) && $item_type[$item->item_id] == 2){
                                    $link = route('finance.dp.index')."?i=$id";
                                    $bg = "btn-info";
                                    if(isset($dep[$id])){
                                        $link = route('finance.dp.detail', $dep[$id]);
                                        $bg = "btn-outline-info";
                                    }
                                }
                                $edit = true;
                                if(isset($item_name[$item->item_id])){
                                    if (isset($item_deleted[$item->item_id]) && empty($item_deleted[$item->item_id])) {
                                        $edit = false;
                                    }
                                }
                            @endphp
                            <tr>
                                <td align="center">{{$num++}}</td>
                                <td>
                                    {{(isset($item_name[$item->item_id])) ? $item_name[$item->item_id] : $item->item_id}}
                                    &nbsp;
                                    @if ($edit)
                                        <button type="button" onclick="_change({{ $item->id }})" class="btn btn-xs btn-icon btn-primary" data-toggle="modal" data-target="#modalEdit"><i class="far fa-edit"></i></button>
                                    @endif
                                    @if (!empty($link))
                                    <a href="{{ $link }}" target="_blank" class="btn {{ $bg }} btn-icon btn-xs" data-toggle="tooltip" title="Depreciation"><i class="fa fa-compress-alt"></i></a>
                                    @endif
                                </td>
                                <td>{{(isset($item_code[$item->item_id])) ? $item_code[$item->item_id] : $item->item_id}}</td>
                                <td align="center">{{$item->qty}}</td>
                                <td align="center">{{(isset($item_uom[$item->item_id])) ? $item_uom[$item->item_id] : ""}}</td>
                                <td align="right">{{number_format($item->price, 2)}}</td>
                                <td align="right">{{number_format($item->price * $item->qty, 2)}}</td>
                                <?php
                                $total += ($item->price * $item->qty);
                                ?>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="5" align="right">Sub Total</td>
                        <td></td>
                        <td align="right">{{number_format($total, 2)}}</td>
                    </tr>
                    <tr>
                        <td colspan="5" align="right">Discount</td>
                        <td></td>
                        <td align="right">{{number_format($po->discount, 2)}}</td>
                    </tr>
                    <tr>
                        <td colspan="5" align="right">Net include Discount</td>
                        <td></td>
                        <td align="right">{{number_format($total - $po->discount, 2)}}</td>
                    </tr>
                    <?php $tot = $total - $po->discount; ?>
                    @if($po->ppn != null)
                        @if(is_array(json_decode($po->ppn)))
                        @foreach(json_decode($po->ppn) as $kppn => $vppn)
                            <tr>
                                <td colspan="5" align="right">{{$tax_name[$vppn]}}</td>
                                <td></td>
                                <td align="right">
                                    <?php
                                    $sum = $total - $po->discount;
                                    $p = eval('return '.$formula[$vppn].';');
                                    $ppn_sum = $p;
                                    $tot += $ppn_sum;
                                    ?>
                                    {{number_format(round($ppn_sum), 2)}}
                                </td>
                            </tr>
                        @endforeach
                        @endif
                    @endif
                    <tr>
                        <td colspan="5" align="right">Total After Tax</td>
                        <td></td>
                        <td align="right">{{number_format(round($tot), 2)}}</td>
                    </tr>
                    <tr>
                        <td colspan="5" align="right">Down Payment</td>
                        <td></td>
                        <td align="right">{{number_format($po->dp, 2)}}</td>
                    </tr>
                    <tr>
                        <td colspan="5" align="right">Total Due</td>
                        <td></td>
                        <td align="right">{{number_format(round($tot - $po->dp), 2)}}</td>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <hr>
        </div>
    </div>
    <div class="modal fade" id="modalNotes" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Edit Notes</h3>
                </div>
                <form action="{{ route('po.edit.notes') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <textarea name="notes" class="" id="txtNotes" cols="30" rows="10">
                                    {{ $po->notes }}
                                </textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_po" value="{{ $po->id }}">
                        <button type="button" class="btn btn-light-primary btn-sm" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalEdit" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Change Item</h3>
                </div>
                <form action="{{ route('po.item.update') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-3 col-form-label">Category</label>
                            <div class="col-9">
                                <select id="_category" class="form-control select2">
                                    <option value="">Select Category</option>
                                    @foreach ($cat as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row hide-div" id="_class_div">
                            <label class="col-3 col-form-label">Classificication</label>
                            <div class="col-9">
                                <select id="_classification" class="form-control">

                                </select>
                            </div>
                        </div>
                        <div class="form-group row hide-div" id="_item_div">
                            <label class="col-3 col-form-label">Items</label>
                            <div class="col-9">
                                <select id="_items" name="_item" required class="form-control">

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_detail" id="_id_detail">
                        <button type="button" class="btn btn-light-primary btn-sm" data-dismiss="modal">Close</button>
                        <button type="submit" onclick="_post()" class="btn btn-primary btn-sm">Save</button>
                    </div>
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
    <script>
        function framePrint(whichFrame) {
            window.frames[whichFrame].focus();
            window.frames[whichFrame].print();
        }

        function _change(id){
            $("#_id_detail").val(id)
            $(".hide-div").hide()
            $("#modalEdit").find('button[type=submit]').prop('disabled', true)
        }

        $(document).ready(function(){

            // tinymce.init({
            //     editor_selector : ".form-control",
            //     selector:'.form-control',
            //     mode : "textarea",
            //     menubar: false,
            //     toolbar: false
            // });

            tinymce.init({
                editor_selector : "#txtNotes",
                selector:'#txtNotes',
                menubar: false
            });

            $("select.select2").select2({
                width: "100%"
            })
            $("table.display").DataTable({
                "searching": false,
                "lengthChange": false,
                "ordering": false,
                "aaSorting": [],
                "paging":   false,
                "info":     false,
            })


            $("#_category").change(function(){
                var cat = $(this).val()
                $("#_class_div").show()
                $("#_classification").find('option').remove()
                $("#_item_div").hide()
                $("#_classification").select2({
                    ajax : {
                        url : "{{ route('items.approval.class.get') }}/" + cat,
                        dataType : 'json'
                    },
                    placeholder : "Select Classification",
                })
            })

            $("#_classification").change(function(){
                var cl = $(this).val()
                $("#_item_div").show()
                $("#_items").select2({
                    ajax : {
                        url : "{{ route('items.list.class') }}/" + cl,
                        dataType : 'json'
                    },
                    placeholder : "Select Items",
                })
            })

            $("#_items").change(function(){
                $("#modalEdit").find('button[type=submit]').prop('disabled', false)
            })
        })
    </script>
@endsection

