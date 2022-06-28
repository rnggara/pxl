@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Daily Report Inventory</h3><br>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button onclick="window.location = '{{route("general.dr.view",["id" => $report->id])}}'" @if(strpos($route,'daily-report/view') !== false) disabled @endif class="btn btn-success">&nbsp; Activity</button>
                    &nbsp;&nbsp;
                    <button onclick="window.location = '{{route("general.dr.view",["id" => $report->id])}}'" @if(strpos($route,'daily-report/inventory') !== false) disabled @endif class="btn btn-primary">&nbsp; Inventory</button>
                    &nbsp;&nbsp;
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{route('general.dr.lock')}}" method="post">
                @csrf
                <table class="table table-bordered table-hover table-checkable" id="kt_datatable1b" style="margin-top: 13px !important">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Item Name</th>
                        <th class="text-center">Start</th>
                        <th class="text-center">In</th>
                        <th class="text-center">Out</th>
                        <th class="text-center">Balance</th>
                        {{--                    <th class="text-center"><button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#addMOM"><i class="fa fa-plus"></i>Add</button></th>--}}
                    </tr>
                    </thead>

                    <tbody>
                    <tr>
                        <td colspan="5"></td>
                        <td align="center"><button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#addInventory"><i class="fa fa-plus"></i>Add</button></td>
                    </tr>

                    @php
                        $lockstatus = false;
                    @endphp
                    @for($ox = 0; $ox < count($itemNames); $ox++)
                        @if(!empty($qtyLock[$itemIDs[$ox]]))
                            @if($qtyLock[$itemIDs[$ox]] > 0 && $qtyLock[$itemIDs[$ox]] != null)
                                @php

                                    /** @var TYPE_NAME $qtyInit */
                                    /** @var TYPE_NAME $itemIDs */
                                    /** @var TYPE_NAME $ox */
                                    /** @var TYPE_NAME $qtyIdReport */
                                    /** @var TYPE_NAME $report */
                                    $idreportqty = intval($qtyIdReport[$itemIDs[$ox]]);
                                    $idreportthis = intval($report->id);
                                    if (($idreportqty >= $idreportthis)){
                                        $styleLock = "disabled";
                                        $lockstatus = true;
                                    } else {
                                        $styleLock = "";
                                    }
                                    $qtyInitNow = $qtyInit[$itemIDs[$ox]];
                                @endphp
                            @else
                                @php
                                    $styleLock = "";
                                    /** @var TYPE_NAME $itemInit */
                                    /** @var TYPE_NAME $ox */
                                    $qtyInitNow = $itemInit[$ox];
                                @endphp
                            @endif
                        @endif
                        <tr>
                            <td class="text-center">{{($ox+1)}}</td>
                            <td class="text-center">{{(isset($itemNames[$ox]))?$itemNames[$ox]:''}}</td>
                            <td class="text-center">
                                {{(isset($qtyInitNow))?$qtyInitNow:$itemInit[$ox]}}
                                <input type='hidden' class="form-control" name='qty_init[{{(isset($itemIDs[$ox]))?$itemIDs[$ox]:0}}]' id='qty_init{{(isset($itemIDs[$ox]))?$itemIDs[$ox]:0}}' value='{{(isset($itemInit[$ox]))?$itemInit[$ox]:0}}' />
                            </td>
                            <td class="text-center">
                                <input type='text' class="form-control" name='qty_in[{{(isset($itemIDs[$ox]))?$itemIDs[$ox]:0}}]' onchange="changeIn({{(isset($itemIDs[$ox]))?$itemIDs[$ox]:0}})" id="qty_in{{(isset($itemIDs[$ox]))?$itemIDs[$ox]:0}}" value='{{(isset($qtyIn[$itemIDs[$ox]]))?($qtyIdReport[$itemIDs[$ox]] == $report->id)?$qtyIn[$itemIDs[$ox]]:0:0}}' {{(isset($styleLock))?$styleLock:''}} />
                                <input type="hidden" name="id_report[{{(isset($itemIDs[$ox]))?$itemIDs[$ox]:0}}]" id="id_report{{(isset($itemIDs[$ox]))?$itemIDs[$ox]:0}}" value="{{$report->id}}">
                            </td>
                            <td class="text-center">
                                <input type='text' class="form-control" name='qty_out[{{(isset($itemIDs[$ox]))?$itemIDs[$ox]:0}}]' onchange="changeOut({{(isset($itemIDs[$ox]))?$itemIDs[$ox]:0}})" id="qty_out{{(isset($itemIDs[$ox]))?$itemIDs[$ox]:0}}" value='{{(isset($qtyOut[$itemIDs[$ox]]))?($qtyIdReport[$itemIDs[$ox]] == $report->id)?$qtyOut[$itemIDs[$ox]]:0:0}}' {{(isset($styleLock))?$styleLock:''}} />
                            </td>
                            <td class="text-center">
                                {{(isset($qtyBal[$itemIDs[$ox]]))?$qtyBal[$itemIDs[$ox]]:$itemInit[$ox]}}
                                <input type="hidden" class="form-control" name="qty_bal[{{(isset($itemIDs[$ox]))?$itemIDs[$ox]:0}}]" id="qty_bal[{{(isset($itemIDs[$ox]))?$itemIDs[$ox]:0}}]" value="{{(isset($qtyBal[$itemIDs[$ox]]))?$qtyBal[$itemIDs[$ox]]:$itemInit[$ox]}}">
                            </td>
                        </tr>
                    @endfor
                    @if($lockstatus ==false)
                    <tr>
                        <td colspan="5"></td>
                        <td align="center">
                            <button type="submit" class="btn btn-warning btn-xs" onclick="return confirm('Are you sure? Locking the inventory also will save the quantity and disable editing.')"><i class="fa fa-lock"></i>&nbsp;Lock</button>
                        </td>
                    </tr>
                    @endif
                    </tbody>
                </table>
            </form>

            <div class="modal fade" id="addInventory" tabindex="-1" role="dialog" aria-labelledby="addInventory" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Insert Item</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <i aria-hidden="true" class="ki ki-close"></i>
                            </button>
                        </div>
                        <form method="post" action="{{route('general.dr.init_item')}}" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-md-2 col-form-label text-right">Item Name</label>
                                            <div class="col-md-10">
                                                <input type="text" class="form-control" placeholder="Insert Item Name" name="item_name" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-2 col-form-label text-right">Item Quantity</label>
                                            <div class="col-md-10">
                                                <input type="number" class="form-control" placeholder="Insert Quantity" name="item_qty" required>
                                            </div>
                                        </div>
                                        <input type="hidden" name="division" value="{{$report->rpt_wh}}">
                                    </div>

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                                <button type="submit" name="save" value="Save" id="save" class="btn btn-primary font-weight-bold">
                                    <i class="fa fa-check"></i>
                                    Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
<script type="text/javascript">
    function changeIn(x){
        var valueInQty = $('#qty_in'+x).val()
        var qty_init = $('#qty_init'+x).val()
        var id_report = $('#id_report'+x).val()
        var valueOutQty = $('#qty_out'+x).val()

        // console.log(valueInQty)
        $.ajax({
            type :'POST',
            data : {
                qty: valueInQty,
                qtyx: valueOutQty,
                id_report: id_report,
                id_item: x,
                qty_init : qty_init,
                _token : '{{csrf_token()}}'
            },
            url:'{{route('general.dr.in_qty')}}',
            success: function (response) {
                console.log(response)
                location.reload();
            }

        })
    }

    function changeOut(x){
        var valueOutQty = $('#qty_out'+x).val()
        var qty_init =$('#qty_init'+x).val()
        var id_report = $('#id_report'+x).val()
        var valueInQty = $('#qty_in'+x).val()

        // console.log(valueInQty)
        $.ajax({
            type :'POST',
            data : {
                qty: valueOutQty,
                qtyx: valueInQty,
                id_report: id_report,
                id_item: x,
                qty_init : qty_init,
                _token : '{{csrf_token()}}'
            },
            url:'{{route('general.dr.out_qty')}}',
            success: function (response) {
                // console.log(response)
                location.reload();
            }

        })
    }
</script>
@endsection
