@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Work Order Detail</h3><br>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" onclick="framePrint('print-po')" class="btn btn-primary"><i class="fa fa-print"></i></button>
                    <a href="{{URL::route('general.wo')}}" class="btn btn-success btn-xs"><i class="fa fa-arrow-circle-left"></i></a>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <iframe src="{{route('wo.print', $po->id)}}" name="print-po" id="print-po" height="0" width="0" frameborder="0"></iframe>
            <div class="card card-custom bg-primary m-5">
                <div class="separator separator-solid separator-white opacity-20"></div>
                <div class="card-body text-white">
                    <div class="row">
                        <div class="col-6">
                            <table class="text-white font-size-sm">
                                <tbody>
                                <tr>
                                    <td>WO#</td>
                                    <td>:</td>
                                    <td>
                                        <b>{{$po->wo_num}}</b>
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
                                        <div class="row">
                                            <div class="col-12">
                                                {!! $po->notes !!}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-6">
                            <table class="text-white font-size-sm">
                            <tbody>
                            <tr>
                                <td>Project</td>
                                <td>:</td>
                                <td>
                                    <b>{{(isset($pro_name[$po->project])) ? $pro_name[$po->project] : ""}}</b>
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
                                    {!! $po->terms_payment !!}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        </div>

                    </div>
                </div>
            </div>
            <div class="separator separator-dashed separator-border-2 separator-primary"></div>
            <div class="m-5">
                <table class="table display table-bordered" width="100%">
                    <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-left">Job Description</th>
                        <th class="text-center">Qty</th>
                        <th class="text-right">Price per Unit</th>
                        <th class="text-right">Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $total =0; ?>
                    @foreach($po_detail as $key => $item)
                        <tr>
                            <td align="center">{{$key + 1}}</td>
                            <td>{!! $item->job_desc !!}</td>
                            <td align="center">{{$item->qty}}</td>
                            <td align="right">{{number_format($item->unit_price, 2)}}</td>
                            <td align="right">{{number_format($item->unit_price * $item->qty, 2)}}</td>
                            <?php
                            /** @var TYPE_NAME $item */
                            $total += ($item->unit_price * $item->qty);
                            ?>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="4" align="right">Sub Total</td>
                        <td align="right">{{number_format($total, 2)}}</td>
                    </tr>
                    <tr>
                        <td colspan="4" align="right">Discount</td>
                        <td align="right">{{number_format($po->discount, 2)}}</td>
                    </tr>
                    <tr>
                        <td colspan="4" align="right">Net include Discount</td>
                        <td align="right">{{number_format($total - $po->discount, 2)}}</td>
                    </tr>
                    <?php $tot = $total - $po->discount; ?>
                    @if(!empty($po->ppn) && is_array(json_decode($po->ppn)))
                    @foreach(json_decode($po->ppn) as $kppn => $vppn)
                        <tr>
                            <td colspan="4" align="right">{{$tax_name[$vppn]}}</td>
                            <td align="right">
                                <?php
                                $sum = $total - $po->discount;
                                $p = eval('return '.$formula[$vppn].';');
                                $ppn_sum = round($p);
                                $tot += $ppn_sum;
                                ?>
                                {{number_format($ppn_sum, 2)}}
                            </td>
                        </tr>
                    @endforeach
                    @endif
                    <tr>
                        <td colspan="4" align="right">Total After Tax</td>
                        <td align="right">{{number_format($tot, 2)}}</td>
                    </tr>
                    <tr>
                        <td colspan="4" align="right">Down Payment</td>
                        <td align="right">{{number_format($po->dp, 2)}}</td>
                    </tr>
                    <tr>
                        <td colspan="4" align="right">Total Due</td>
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
            <form action="{{ route('wo.edit.notes') }}" method="post">
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
                    <input type="hidden" name="id_wo" value="{{ $po->id }}">
                    <button type="button" class="btn btn-light-primary btn-sm" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save</button>
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
