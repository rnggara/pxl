@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Good Received Detail</h3><br>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{URL::route('gr.index')}}" class="btn btn-success btn-xs"><i class="fa fa-arrow-circle-left"></i></a>
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
                                        {{$vendor_name[$po->supplier_id]}}
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
                                        <b>{{$pro_name[$po->project]}}</b>
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
                            @empty($po->gr_date)
                            <th class="text-center">Qty Receive</th>
                            @endempty
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
                        @foreach($po_detail as $key => $item)
                            @if($item->po_num == $po->id)
                                @php
                                    $qty = $item->qty;
                                    if(isset($item_prev[$item->item_id]) && !empty($type)){
                                        $qty = $item->qty - array_sum($item_prev[$item->item_id]);
                                    }
                                @endphp
                                <tr>
                                    <td align="center">{{$no++}}</td>
                                    <td>{{(isset($item_name[$item->item_id])) ? $item_name[$item->item_id] : $item->item_id}}</td>
                                    <td>{{(isset($item_code[$item->item_id])) ? $item_code[$item->item_id] : $item->item_id}}</td>
                                    <input type="hidden" name="item_id[{{$item->item_id}}]" value="{{$item->item_id}}" id="">
                                    <td align="center">{{$qty}}</td>
                                    @empty($po->gr_date)
                                    <td align="center">
                                        <input type="number" min="0" max="{{ $qty }}" name="qty_receive[{{ $item->item_id }}]" class="form-control" value="{{ $qty }}">
                                    </td>
                                    @endempty
                                    <input type="hidden" name="item_qty[{{$item->item_id}}]" value="{{$qty}}" id="">
                                    <td align="center">{{(isset($item_uom[$item->item_id])) ? $item_uom[$item->item_id] : ""}}</td>
                                    <td align="right">{{number_format($item->price, 2)}}</td>
                                    <td align="right">{{number_format($item->price * $qty, 2)}}</td>
                                    <?php
                                    /** @var TYPE_NAME $item */
                                    $total += ($item->price * $item->qty);
                                    ?>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @if (count($gr) > 0 && count($gr_det) > 0)
                    <div class="row">
                        <div class="col-12">
                            <h3>{{ (empty($type)) ? "Detail item received" : "Previous item received" }}</h3>
                            <table class="table table-bordered table-responsive-sm">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">GR#</th>
                                        <th class="text-center">Received Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($gr as $key => $item)
                                        @php
                                            $m = date("m", strtotime($item->gr_date));
                                            $y = date("Y", strtotime($item->gr_date));
                                        @endphp
                                        <tr>
                                            <td align="center">{{ $key+1 }}</td>
                                            <td align="center">
                                                <a href="{{ route('gr.detail.id', ['id' => $item->id, 'type' => $type]) }}">{{ sprintf("%03d", $item->id)."/GR/$m/$y" }}</a>
                                            </td>
                                            <td align="center">
                                                {{ date("d F Y", strtotime($item->gr_date)) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
                <hr>
                @if($type != null)
                    <div>
                        <div class="card card-custom bg-secondary m-5">

                            <div class="card-body">
                                <div class="row">
                                    <h5> Approve Good Received </h5>
                                    <hr>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label text-right">Receive Date</label>
                                <div class="col-md-6">
                                    <input type="date" class="form-control" value="{{date('Y-m-d')}}" name="receive_date">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label text-right">Store To Warehouse</label>
                                <div class="col-md-2">
                                    <select class="form-control select2" name="warehouse" required>
                                        <option value="">--Select Warehouse--</option>
                                        @foreach($whs as $key => $val)
                                            <option value="{{$val->id}}">{{$val->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <label class="col-md-2 col-form-label text-right">Deliver to Warehouse</label>
                                <div class="col-md-2">
                                    <select class="form-control select2" name="warehouse_deliver">
                                        <option value="">--Select Warehouse--</option>
                                        @foreach($whs as $key => $val)
                                            <option value="{{$val->id}}">{{$val->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label text-right">Notes</label>
                                <div class="col-md-6">
                                    <textarea class="form-control" name="notes" id="" cols="30" rows="10">

                                    </textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mt-5">
                            <button class="btn btn-xs btn-success" type="submit"><i class="fa fa-check"></i> Approve</button>
                        </div>
                    </div>
                @endif
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
