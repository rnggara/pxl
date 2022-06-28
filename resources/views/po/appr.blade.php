@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Purchase Order Detail</h3><br>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{URL::route('po.index')}}" class="btn btn-success btn-xs"><i class="fa fa-arrow-circle-left"></i></a>
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
                                <td>PO#</td>
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
                                    {{(isset($vendor_name[$po->supplier_id]))?$vendor_name[$po->supplier_id]:''}}
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
                                <td>Notes</td>
                                <td>:</td>
                                <td>
                                    {{strip_tags($po->notes)}}
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
                                    {{strip_tags($po->deliver_to)}}
                                </td>
                            </tr>
                            <tr>
                                <td>Deliver Time</td>
                                <td>:</td>
                                <td>
                                    {{strip_tags($po->deliver_time)}}
                                </td>
                            </tr>
                            <tr>
                                <td>Terms</td>
                                <td>:</td>
                                <td>
                                    {{strip_tags($po->terms)}}
                                </td>
                            </tr>
                            <tr>
                                <td>Terms of Payment</td>
                                <td>:</td>
                                <td>
                                    {{strip_tags($po->payment_terms)}}
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
                            <th class="text-left">Item Code</th>
                            <th class="text-left">Item Name</th>
                            <th class="text-center">Qty</th>
                            <th class="text-center">UoM</th>
                            <th class="text-right">Price per Unit</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $total =0; $iKey = 1; ?>
                        @foreach($po_detail as $key => $item)
                            @if($item->po_num == $po->id)
                                <tr>
                                    <td align="center">{{$iKey++}}</td>
                                    <td>{{(isset($item_name[$item->item_id]))?$item_name[$item->item_id]:''}}</td>
                                    <td>{{(isset($item_code[$item->item_id]))?$item_code[$item->item_id]:''}}</td>
                                    <td align="center">{{$item->qty}}</td>
                                    <td align="center">{{(isset($item_uom[$item->item_id]))?$item_uom[$item->item_id]:''}}</td>
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
                        @if(!empty($po->ppn))
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
                                        {{number_format($ppn_sum, 2)}}
                                    </td>
                                </tr>
                            @endforeach
                            @endif
                        @endif
                        <tr>
                            <td colspan="5" align="right">Total After Tax</td>
                            <td></td>
                            <td align="right">{{number_format($tot, 2)}}</td>
                        </tr>
                        <tr>
                            <td colspan="5" align="right">Down Payment</td>
                            <td></td>
                            <td align="right">{{number_format($po->dp, 2)}}</td>
                        </tr>
                        <tr>
                            <td colspan="5" align="right">Total Due</td>
                            <td></td>
                            <td align="right">{{number_format($tot - $po->dp, 2)}}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <hr>
            <div>
                <h3>Confirmation</h3>
                <hr>
                <div class="col-md-6">
                    <input type="hidden" value="{{$tot}}" id="total">
                    <textarea name="note" id="note" cols="30" rows="10" class="form-control">{!! $po->notes !!}</textarea>
                </div>
                <div class="col-md-6 mt-5">
                    <button class="btn btn-xs btn-success" onclick="button_approve('{{$po->id}}')"><i class="fa fa-check"></i> Approve</button>
                    @actionStart('po_wo', 'approvedir')
                    <button class="btn btn-xs btn-warning" onclick="button_revise('{{$po->id}}')"><i class="fa fa-pencil-alt"></i> Revise</button>
                    @actionEnd
                    <button class="btn btn-xs btn-danger" onclick="button_reject('{{$po->id}}')"><i class="fa fa-times"></i> Reject</button>
                </div>
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
        function button_approve(x){
            Swal.fire({
                title: "Approve",
                text: "Are you sure you want to Approve?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Approve",
                cancelButtonText: "Cancel",
                reverseButtons: true,
            }).then(function(result){
                if(result.value){
                    $.ajax({
                        url: '{{URL::route('po.approve')}}',
                        data: {
                            '_token': '{{csrf_token()}}',
                            'id': x,
                            'notes': $("#note").val(),
                            'val': $("#total").val()
                        },
                        type: "POST",
                        cache: false,
                        dataType: 'json',
                        success : function(response){
                            if (response.error == 0){
                                window.location = '{{URL::route('po.index')}}'
                            } else {
                                Swal.fire('Error', '', 'error')
                            }
                        }
                    })
                }
            })
        }

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
                        url: '{{URL::route('po.reject')}}',
                        data: {
                            '_token': '{{csrf_token()}}',
                            'id': x,
                            'notes': $("#note").val(),
                            'val': $("#total").val()
                        },
                        type: "POST",
                        cache: false,
                        dataType: 'json',
                        success : function(response){
                            if (response.error == 0){
                                window.location = '{{URL::route('po.index')}}'
                            } else {
                                Swal.fire('Error', '', 'error')
                            }
                        }
                    })
                }
            })
        }

        function button_revise(x){
            Swal.fire({
                title: "Revise",
                text: "Are you sure you want to revise?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Revise",
                cancelButtonText: "Cancel",
                reverseButtons: true,
            }).then(function(result){
                if(result.value){
                    $.ajax({
                        url: '{{URL::route('po.revise')}}',
                        data: {
                            '_token': '{{csrf_token()}}',
                            'id': x,
                            'notes': $("#note").val(),
                            'val': $("#total").val()
                        },
                        type: "POST",
                        cache: false,
                        dataType: 'json',
                        success : function(response){
                            if (response.error == 0){
                                window.location = '{{URL::route('po.index')}}'
                            } else {
                                Swal.fire('Error', '', 'error')
                            }
                        }
                    })
                }
            })
        }

        $(document).ready(function(){

            // tinymce.init({
            //     editor_selector : ".form-control",
            //     selector:'textarea',
            //     mode : "textareas",
            //     menubar: false,
            //     toolbar: false
            // });

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
    </script>
@endsection
