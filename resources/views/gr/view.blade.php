@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Good Received Detail</h3><br>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{route('gr.detail', ['id' =>$po->id,'type'=>$type])}}" class="btn btn-success btn-xs"><i class="fa fa-arrow-circle-left"></i></a>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <form action="{{route('gr.appr')}}" method="POST">
            @csrf
            <div class="card-body">
                <div class="card card-custom bg-secondary m-5">
                    <div class="separator separator-solid separator-white opacity-20"></div>
                    <div class="card-body">
                        <div class="row">
                            <table class="font-size-sm" style="margin-right: 100px">
                                <tbody>
                                <tr>
                                    <td>PO#</td>
                                    <td>:</td>
                                    <td>
                                        <input type="hidden" name="po_num" value="{{$po->po_num}}" id="">
                                        <input type="hidden" name="po_id" value="{{$po->id}}" id="">
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
                                        {{$vendor->name}}
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
                            <table class="font-size-sm">
                                <tbody>
                                <tr>
                                    <td>Project</td>
                                    <td>:</td>
                                    <td>
                                        <b>{{$project->prj_name}}</b>
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
                <hr>
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
                        @php
                            $no = 1;
                        @endphp
                        @foreach($detail as $key => $item)
                        <tr>
                            <td align="center">{{$no++}}</td>
                            <td>{{(isset($item_name[$item->item_id])) ? $item_name[$item->item_id] : $item->item_id}}</td>
                            <td>{{(isset($item_code[$item->item_id])) ? $item_code[$item->item_id] : $item->item_id}}</td>
                            <input type="hidden" name="item_id[{{$item->item_id}}]" value="{{$item->item_id}}" id="">
                            <td align="center">{{$item->qty}}</td>
                            <input type="hidden" name="item_qty[{{$item->item_id}}]" value="{{$item->qty}}" id="">
                            <td align="center">{{(isset($item_uom[$item->item_id])) ? $item_uom[$item->item_id] : ""}}</td>
                            <td align="right">{{number_format($item_price[$item->item_id], 2)}}</td>
                            <td align="right">{{number_format($item_price[$item->item_id] * $item->qty, 2)}}</td>
                            <?php
                            /** @var TYPE_NAME $item */
                            $total += ($item_price[$item->item_id] * $item->qty);
                            ?>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <hr>
            </div>
        </form>
    </div>
@endsection

@section('custom_script')
    <!--begin::Page Vendors(used by this page)-->
    <script src="{{asset('theme/tinymce/tinymce.min.js')}}"></script>
    <!--end::Page Vendors-->
    <!--begin::Page Scripts(used by this page)-->
    <script>
        $(document).ready(function(){

            tinymce.init({
                editor_selector : ".form-control",
                selector:'textarea',
                mode : "textareas",
                menubar: false,
                toolbar: false
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
