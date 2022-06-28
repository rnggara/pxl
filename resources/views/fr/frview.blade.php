@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Request Action</h3><br>

            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tracking">Tracking</button>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="modal fade" id="tracking" tabindex="-1" role="dialog" aria-labelledby="tracking" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tracking</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered display">
                            <thead>
                                <tr>
                                    <th class="text-center">Item Request (IR)</th>
                                    <th class="text-center">Purchase Request (PR)</th>
                                    <th class="text-center">Purchase Evaluation (PE)</th>
                                    <th class="text-center">Purchase Order (PO)</th>
                                    <th class="text-center">Good Receive (GR)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">
                                    {{$fr->fr_num}} <br>
                                    <b>Division Approval</b><br>

                                        @php
                                        $status = "";
                                        /** @var TYPE_NAME $fr */
                                        if ($fr->fr_division_approved_by == null && $fr->fr_division_rejected_by == null){
                                            $status = "N/A";
                                        } else {
                                            if ($fr->fr_division_approved_by != null){
                                                $status = "<label class='text-success'>Approved</label> by ". $fr->fr_division_approved_by." ".date('d M Y', strtotime($fr->fr_division_approved_at));
                                            }
                                            if ($fr->fr_division_rejected_by != null){
                                                $status = "<label class='text-success'>Rejected</label> by ". $fr->fr_division_rejected_by." ".date('d M Y', strtotime($fr->fr_division_rejected_at));
                                            }
                                        }
                                        @endphp

                                        {!! $status !!}
                                        <br>
                                        <b>Asset Approval</b><br>
                                        @php
                                        $status2 = "";
                                        /** @var TYPE_NAME $fr */
                                        if ($fr->fr_approved_by == null && $fr->fr_rejected_by == null){
                                            $status2 = "N/A";
                                        } else {
                                            if ($fr->fr_approved_by != null){
                                                $status2 = "<label class='text-success'>Approved</label> by ". $fr->fr_approved_by." ".date('d M Y', strtotime($fr->fr_approved_at));
                                            }
                                            if ($fr->fr_rejected_by != null){
                                                $status2 = "<label class='text-success'>Rejected</label> by ". $fr->fr_rejected_by." ".date('d M Y', strtotime($fr->fr_rejected_at));
                                            }
                                        }
                                        @endphp

                                        {!! $status2 !!}

                                    </td>
                                    <td class="text-center">
                                        {{$fr->pre_num}} <br>
                                        <b>Director Approval</b><br>
                                        @php
                                            $status3 = "";
                                            /** @var TYPE_NAME $fr */
                                            if ($fr->pre_approved_by == null && $fr->pre_rejected_by == null){
                                                $status3 = "N/A";
                                            } else {
                                                if ($fr->pre_approved_by != null){
                                                    $status3 = "<label class='text-success'>Approved</label> by ". $fr->pre_approved_by." ".date('d M Y', strtotime($fr->pre_approved_at));
                                                }
                                                if ($fr->pre_rejected_by != null){
                                                    $status3 = "<label class='text-success'>Rejected</label> by ". $fr->pre_rejected_by." ".date('d M Y', strtotime($fr->pre_rejected_at));
                                                }
                                            }
                                        @endphp

                                        {!! $status3 !!}
                                        <br>

                                    </td>
                                    <td class="text-center">
                                        {{$fr->pev_num}} <br>
                                        <b>Director Approval</b><br>
                                        @php
                                            $status4 = "";
                                            /** @var TYPE_NAME $fr */
                                            if ($fr->pev_approved_by == null && $fr->pev_rejected_by == null){
                                                $status4 = "N/A";
                                            } else {
                                                if ($fr->pev_approved_by != null){
                                                    $status4 = "<label class='text-success'>Approved</label> by ". $fr->pev_approved_by." ".date('d M Y', strtotime($fr->pev_approved_at));
                                                }
                                                if ($fr->pev_rejected_by != null){
                                                    $status4 = "<label class='text-success'>Rejected</label> by ". $fr->pev_rejected_by." ".date('d M Y', strtotime($fr->pev_rejected_at));
                                                }
                                            }
                                        @endphp

                                        {!! $status4 !!}
                                        <br>
                                    </td>
                                    <td class="text-center">
                                        {{($po != null)? $po->po_num : ''}}<br>
                                        <b>Approval</b><br>
                                        @php
                                            $status5 = "";
                                            /** @var TYPE_NAME $po */
                                            if ($po == null){
                                                $status5 = "N/A";
                                            } else {
                                               if ($po->approved_by == null && $po->rejected_by == null){
                                                $status5 = "N/A";
                                                } else {
                                                    if ($po->approved_by != null){
                                                        $status5 = "<label class='text-success'>Approved</label> by ". $po->approved_by." ".date('d M Y', strtotime($po->approved_time));
                                                    }
                                                    if ($po->rejected_by != null){
                                                        $status5 = "<label class='text-success'>Rejected</label> by ". $po->rejected_by." ".date('d M Y', strtotime($po->rejected_at));
                                                    }
                                                }
                                            }

                                        @endphp
                                        {!! $status5 !!}
                                        <br>
                                    </td>
                                    <td class="text-center">
                                        {{($gr != null)? $gr->po_num : ''}}<br>
                                        <b>Approval</b><br>
                                        @php
                                            $status5 = "";
                                            /** @var TYPE_NAME $po */
                                            if ($gr == null){
                                                $status5 = "N/A";
                                            } else {
                                               if ($po->gr_date == null){
                                                $status5 = "N/A";
                                                } else {
                                                    if ($gr->gr_date != null){
                                                        $status5 = "<label class='text-success'>Approved</label> by ". $gr->gr_by." ".date('d M Y', strtotime($gr->gr_date));
                                                    }
                                                }
                                            }

                                        @endphp
                                        {!! $status5 !!}
                                        <br>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="well">
                <table align="left" style="margin-right: 100px">
                    <tr>
                        <td>RQ #</td><td>:</td>
                        <td>{{$fr->fr_num}}</td>
                    </tr>
                    <tr>
                        <td>Request By</td><td>:</td>
                        <td>{{$fr->request_by}}</td>
                    </tr>
                    <tr>
                        <td>Division</td><td>:</td>
                        <td>{{$fr->division}}</td>
                    </tr>
                    <tr>
                        <td>Request Date</td><td>:</td>
                        <td>{{date('d F Y',strtotime($fr->request_at))}}</td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td>Due Date</td><td>:</td>
                        <td>{{date('d F Y',strtotime($fr->due_date))}}</td>
                    </tr>
                    <tr>
                        <td>Project</td><td>:</td>
                        <td>{{$project->prj_name}}</td>
                    </tr>
                    <tr>
                        <td valign="top">Payment Method</td><td>:</td>
                        <td>
                            {{($fr->bd != '0')? 'Back Date' : 'Paid By Company'}}
                        </td>
                    </tr>
                    <tr>
                        <td>Notes</td><td>:</td>
                        <td>{{$fr->fr_notes}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @if($code == 'div_appr')
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                            <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                                <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Item Code</th>
                                    <th class="text-left">Item Name</th>
                                    <th class="text-center">UoM</th>
                                    <th class="text-center">Quantity Request</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($fr_detail as $key => $val)
                                    <tr>
                                        <td class="text-center">{{($key+1)}}</td>
                                        <td class="text-center">{{$val->item_id}}</td>
                                        <td class="text-left">{{$val->itemName}}</td>
                                        <td class="text-center">{{$val->uom}}</td>
                                        <td class="text-center">{{$val->qty}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <em style="font-size:10pt">*UoM is Unit of Measurement</em>
                        <br><br>
                        <h4>Confirmation</h4>
                        <hr>
                        <div class="col-md-12">
                            <form action="{{route('fr.appr.div')}}" method="post">
                                @csrf
                                <input type="hidden" name="fr_id" value="{{$fr->id}}" id="">
                                <div class="col-md-6">
                                    {{-- <textarea class="form-control" name="notes" placeholder="Write note for approve of reject here (optional)" rows="5">{{$fr->fr_notes}}</textarea> --}}
                                    <br>
                                    <button class="btn btn-success" type="submit" name="submit" value="Approve" onclick="return confirm('Are you sure want to approve?')">
                                        <i class="fa fa-check"></i>&nbsp;&nbsp;Approve
                                    </button>

                                    &nbsp;&nbsp;
                                    <button class="btn btn-danger" type="button" name="submit" value="Reject" data-toggle="modal" data-target="#modalReject">
                                        <i class="fa fa-times"></i>&nbsp;&nbsp;Reject
                                    </button>
                                </div>
                                <div class="modal fade" id="modalReject" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="" class="col-form-label">Reason</label>
                                                    <textarea class="form-control" name="notes" placeholder="PLease write the reason (Mandatory)" rows="5"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light-danger" data-dismiss="modal">Close</button>
                                                <button type="submit" name="submit" value="Reject" class="btn btn-danger">Reject</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @elseif($code == 'asset_appr')
                @php
                    $item_waiting = 0
                @endphp
                <form action="{{route('fr.appr.asset')}}" method="post">
                    @csrf
                    <div class="card card-custom gutter-b">
                        <div class="card-body">
                            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                                    <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Item Code</th>
                                        <th class="text-left">Item Name</th>
                                        <th class="text-center">UoM</th>
                                        <th class="text-center">Quantity On Hand</th>
                                        <th class="text-center">Quantity Request</th>
                                        <th class="text-center" width="15%">Quantity To Buy</th>
                                        <th class="text-center" width="15%">Quantity To Deliver</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <input type="hidden" name="fr_num" id="" value="{{$fr->fr_num}}">
                                    @foreach($fr_detail as $key => $val)
                                        <tr class="input-item">
                                            <td class="text-center">{{($key+1)}}</td>
                                            <td class="text-center">{{$val->item_id}}</td>
                                            <td class="text-left">{{$val->itemName}}</td>
                                            <td class="text-center">{{$val->uom}}</td>
                                            <td class="text-center">
                                                <span class="label label-inline label-primary" id="qoh{{ $val->itemId }}">
                                                    @php
                                                        $qqoh = (isset($qoh[$val->itemId])) ? array_sum($qoh[$val->itemId]) : 0;
                                                        $disabled = "readonly";
                                                        if($val->qty <= $qqoh){
                                                            $disabled = "";
                                                        }

                                                        if($val->new_item == "waiting"){
                                                            $item_waiting++;
                                                        }
                                                    @endphp
                                                    {{ $qqoh }}
                                                </span>
                                                <br><br>
                                                <button type="button" onclick="see_details({{ $val->itemId }})" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> See details</button>
                                            </td>
                                            <td class="text-center">{{$val->qty}}</td>
                                            <td class="text-center">
                                                <input type="number" step=".01" name="qty_buy[]" onchange="_notnull()" value="{{$val->qty}}" id="qty-buy{{ $val->itemId }}" min="0" class=" qty_buy form-control" >
                                                <input type="hidden" name="fr_detail_id[]" value="{{$val->id}}">
                                                <input type="hidden" name="fr_detail_code[]" value="{{$val->item_id}}">
                                            </td>
                                            <td class="text-center">
                                                <input type="number" step=".01" max="{{ $qqoh }}" {{ $disabled }} min="0" name="qty_deliver[]" value="0" class="form-control qty_deliver" id="qty-deliver{{ $val->itemId }}" onchange="item_deliver(this, '#qty-buy{{ $val->itemId }}', '#qoh{{ $val->itemId }}', {{ $val->qty }})" onkeyup="item_deliver(this, '#qty-buy{{ $val->itemId }}', '#qoh{{ $val->itemId }}', {{ $val->qty }})">
                                                <br>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <em style="font-size:10pt">*UoM is Unit of Measurement</em>
                            <br><br>
                            @if ($item_waiting > 0)
                                <div class="alert alert-custom alert-danger col-4">
                                    {{ $item_waiting }} item(s) need Approval
                                </div>
                            @else
                            <h4>Remark</h4>
                            <hr>
                            <div class="col-md-12">
                                <input type="hidden" name="fr_id" value="{{$fr->id}}" id="">
                                <div class="col-md-6">
                                    <textarea class="form-control" name="notes" placeholder="Write note for approve of reject here (mandatory)" rows="5">{{$fr->fr_approved_notes}}</textarea>
                                    <br>
                                    <button class="btn btn-success" id="btn-approve" type="submit" name="submit" value="Approve" onclick="return confirm('Are you sure want to approve?')">
                                        <i class="fa fa-check"></i>&nbsp;&nbsp;Approve
                                    </button>

                                    &nbsp;&nbsp;
                                    <button class="btn btn-danger" id="btn-reject" type="submit" name="submit" value="Reject" onclick="return confirm('Are you sure want to reject?')">
                                        <i class="fa fa-times"></i>&nbsp;&nbsp;Reject
                                    </button>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </form>
            @elseif($code == 'deliver')
                <form action="{{route('fr.appr.deliver')}}" method="post">
                    @csrf
                    <div class="card card-custom gutter-b">
                        <div class="card-body">
                            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                                    <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Item Code</th>
                                        <th class="text-left">Item Name</th>
                                        <th class="text-center">UoM</th>
                                        <th class="text-center">Quantity Request</th>
                                        <th class="text-center" width="15%">Delivered</th>
                                        <th class="text-center" width="15%">Remnant</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <input type="hidden" name="fr_num" id="" value="{{$fr->fr_num}}">
                                    <input type="hidden" name="fr_id" id="" value="{{$fr->fr_id}}">
                                    @foreach($fr_detail as $key => $val)
                                        <tr>
                                            <td class="text-center">{{($key+1)}}</td>
                                            <td class="text-center">{{$val->item_id}}</td>
                                            <td class="text-left">{{$val->itemName}}</td>
                                            <td class="text-center">{{$val->uom}}</td>
                                            <td class="text-center">{{$val->qty}}</td>

                                            <td class="text-center">{{$val->delivered}}</td>
                                            <td class="text-center">
                                                <input type="number" name="remnant[]" class="form-control" placeholder="{{(intval($val->qty) - intval($val->delivered))}}">
                                                <input type="hidden" name="qty_remnant[]" value="{{(intval($val->qty) - intval($val->delivered))}}">
                                                <input type="hidden" name="qty_deliver[]" value="{{(intval($val->qty_deliver))}}">
                                                <input type="hidden" name="fr_detail_id[]" value="{{$val->id}}">
                                                <input type="hidden" name="fr_detail_code[]" value="{{$val->item_id}}">
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <em style="font-size:10pt">*UoM is Unit of Measurement</em>
                            <br><br>
                            <h4>Confirmation</h4>
                            <hr>
                            <div class="col-md-12">
                                <input type="hidden" name="fr_id" value="{{$fr->id}}" id="">
                                <div class="col-md-6">
                                    <textarea class="form-control" name="notes" placeholder="Write note for approve of reject here (optional)" rows="5">{{$fr->fr_notes}}</textarea>
                                    <br>
                                    <button class="btn btn-success" type="submit" name="submit" value="Approve" onclick="return confirm('Are you sure want to approve?')">
                                        <i class="fa fa-check"></i>&nbsp;&nbsp;Approve
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            @else
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                            <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                                <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Item Code</th>
                                    <th class="text-left">Item Name</th>
                                    <th class="text-center">UoM</th>
                                    <th class="text-center">Quantity Request</th>
                                    <th class="text-center">Quantity Buy</th>
                                    <th class="text-center">Quantity Delivered</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($fr_detail as $key => $val)
                                    <tr>
                                        <td class="text-center">{{($key+1)}}</td>
                                        <td class="text-center">{{$val->item_id}}</td>
                                        <td class="text-left">{{$val->itemName}}</td>
                                        <td class="text-center">{{$val->uom}}</td>
                                        <td class="text-center">{{$val->qty}}</td>
                                        <td class="text-center">{{$val->qty_buy}}</td>
                                        <td class="text-center">{{($val->delivered != null)?$val->delivered:'-'}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <em style="font-size:10pt">*UoM is Unit of Measurement</em>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="seeDetailModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" id="detail-content">

            </div>
        </div>
    </div>
@endsection
@section('custom_script')
<script>

    function _notnull(){
        var btn_approve = true
        $(".input-item").each(function(){
            var qty_buy = $(this).find("input.qty_buy")
            var qty_deliver = $(this).find("input.qty_deliver")
            if(btn_approve){
                if(qty_buy.val() == 0 && qty_deliver.val() == 0){
                    btn_approve = false
                }
            }
        })

        if(!btn_approve){
            $("#btn-approve").prop('disabled', true)
            $("#btn-reject").prop('disabled', true)
        } else {
            $("#btn-reject").prop('disabled', false)
            $("#btn-approve").prop('disabled', false)
        }
    }

    function item_deliver(deliver, target, qoh, request){
        var item = $(deliver).val()
        var qoh = parseInt($(qoh).text())
        if(item > 0){
            if(item > qoh || item > request){
                Swal.fire("Incorrect Quantity to Deliver", 'Quantity to deliver should be the same as requested or sufficient with Quantity on hand', 'warning')
                $(deliver).val(0)
                $(target).val(request)
            } else {
                $(target).val(0)
                $(target).prop('readonly', true)
            }
        } else {
            $(target).prop('readonly', false)
        }
    }

    function see_details(x){
        $("#seeDetailModal").modal('show')
        $.ajax({
            url: "{{ route('fr.see.detail') }}/"+x,
            type: "get",
            cache: false,
            success: function(response){
                $("#detail-content").html(response)
                var table = $("#detail-content").find('table')
                table.DataTable()
            }
        })
    }

    function to_required(qty, x){
        var sel = $("#qty-select"+x)
        console.log(qty)
        console.log(sel)
        if(qty.value > 0){
            $("#qty-select"+x).prop('required', true)
            $("#qty-buy"+x).val(0)
        } else {
            $("#qty-select"+x).prop('required', false)
        }



    }

    function to_buy(x, y){
        if(x.value > 0){
            $("#qty-deliver"+y).val(0)
            $("#qty-select"+y).prop('required', false)
        }
    }

    $(document).ready(function(){
        $("select.select2").select2({
            width: "100%"
        })
    })
</script>
@endsection
