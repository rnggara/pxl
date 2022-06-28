@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Purchase Request Detail</h3><br>
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
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">
                                    {{$pr->fr_num}} <br>
                                    <b>Division Approval</b><br>

                                        @php
                                        $status = "";
                                        /** @var TYPE_NAME $pr */
                                        if ($pr->fr_division_approved_by == null && $pr->fr_division_rejected_by == null){
                                            $status = "N/A";
                                        } else {
                                            if ($pr->fr_division_approved_by != null){
                                                $status = "<label class='text-success'>Approved</label> by". $pr->fr_division_approved_by." ".date('d M Y', strtotime($pr->fr_division_approved_at));
                                            }
                                            if ($pr->fr_division_rejected_by != null){
                                                $status = "<label class='text-success'>Rejected</label> by". $pr->fr_division_rejected_by." ".date('d M Y', strtotime($pr->fr_division_rejected_at));
                                            }
                                        }
                                        @endphp

                                        {!! $status !!}
                                        <br>
                                        <b>Asset Approval</b><br>
                                        @php
                                        $status2 = "";
                                        /** @var TYPE_NAME $pr */
                                        if ($pr->fr_approved_by == null && $pr->fr_rejected_by == null){
                                            $status2 = "N/A";
                                        } else {
                                            if ($pr->fr_approved_by != null){
                                                $status2 = "<label class='text-success'>Approved</label> by". $pr->fr_approved_by." ".date('d M Y', strtotime($pr->fr_approved_at));
                                            }
                                            if ($pr->fr_rejected_by != null){
                                                $status2 = "<label class='text-success'>Rejected</label> by". $pr->fr_rejected_by." ".date('d M Y', strtotime($pr->fr_rejected_at));
                                            }
                                        }
                                        @endphp

                                        {!! $status2 !!}

                                    </td>
                                    <td class="text-center">
                                        {{$pr->pre_num}} <br>
                                        <b>Director Approval</b><br>
                                        @php
                                            $status3 = "";
                                            /** @var TYPE_NAME $pr */
                                            if ($pr->pre_approved_by == null && $pr->pre_rejected_by == null){
                                                $status3 = "N/A";
                                            } else {
                                                if ($pr->pre_approved_by != null){
                                                    $status3 = "<label class='text-success'>Approved</label> by". $pr->pre_approved_by." ".date('d M Y', strtotime($pr->pre_approved_at));
                                                }
                                                if ($pr->pre_rejected_by != null){
                                                    $status3 = "<label class='text-success'>Rejected</label> by". $pr->pre_rejected_by." ".date('d M Y', strtotime($pr->pre_rejected_at));
                                                }
                                            }
                                        @endphp

                                        {!! $status3 !!}
                                        <br>

                                    </td>
                                    <td class="text-center">
                                        {{$pr->pev_num}} <br>
                                        <b>Director Approval</b><br>
                                        @php
                                            $status4 = "";
                                            /** @var TYPE_NAME $pr */
                                            if ($pr->pev_approved_by == null && $pr->pev_rejected_by == null){
                                                $status4 = "N/A";
                                            } else {
                                                if ($pr->pev_approved_by != null){
                                                    $status4 = "<label class='text-success'>Approved</label> by". $pr->pev_approved_by." ".date('d M Y', strtotime($pr->pev_approved_at));
                                                }
                                                if ($pr->pev_rejected_by != null){
                                                    $status4 = "<label class='text-success'>Rejected</label> by". $pr->pev_rejected_by." ".date('d M Y', strtotime($pr->pev_rejected_at));
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
                                                        $status5 = "<label class='text-success'>Approved</label> by". $po->approved_by." ".date('d M Y', strtotime($po->approved_time));
                                                    }
                                                    if ($po->rejected_by != null){
                                                        $status5 = "<label class='text-success'>Rejected</label> by". $po->rejected_by." ".date('d M Y', strtotime($po->rejected_at));
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
                        <td>PRE #</td><td>:</td>
                        <td>{{$pr->pre_num}}</td>
                    </tr>
                    <tr>
                        <td>PRE Date</td><td>:</td>
                        <td>{{date('d F Y',strtotime($pr->fr_approved_at))}}</td>
                    </tr>
                    <tr>
                        <td>FR #</td><td>:</td>
                        <td>{{$pr->fr_num}}</td>
                    </tr>
                    <tr>
                        <td>FR Date</td><td>:</td>
                        <td>{{date('d F Y',strtotime($pr->request_at))}}</td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td>Due Date</td><td>:</td>
                        <td>{{date('d F Y',strtotime($pr->due_date))}}</td>
                    </tr>
                    <tr>
                        <td>Project</td><td>:</td>
                        <td>{{$project->prj_name}}</td>
                    </tr>
                    <tr>
                        <td valign="top">Division</td><td>:</td>
                        <td>
                            {{$pr->division}}
                        </td>
                    </tr>
                    <tr>
                        <td>Notes</td><td>:</td>
                        <td>{{$pr->fr_notes}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @if($code == 'dir_appr')
                <form action="{{route('fr.appr.dir')}}" method="post">
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
                                        <th class="text-center">Quantity Buy</th>
                                        {{-- <th class="text-center">Stock</th> --}}
                                        <th class="text-center" width="15%">Quantity Approve</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($pr_detail as $key => $val)
                                        <tr>
                                            <td class="text-center">{{($key+1)}}</td>
                                            <input type="hidden" name="item[]" value="{{$val->item_id}}">
                                            <input type="hidden" name="itemID[]" value="{{$val->id}}">
                                            <td class="text-center">{{$val->item_id}}</td>
                                            <td class="text-left">{{$val->itemName}}</td>
                                            <td class="text-center">{{$val->uom}}</td>
                                            <td class="text-center">{{$val->qty}}</td>
                                            <td class="text-center">{{$val->qty_buy}}</td>
                                            {{-- <td class="text-center"></td> --}}
                                            <td class="text-center">
                                                <input type="number" name="qty_appr[]" class="form-control" value="{{$val->qty_buy}}">
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
                                <input type="hidden" name="fr_num" value="{{$pr->fr_num}}" id="">
                                <input type="hidden" name="id" value="{{$pr->id}}" id="">
                                <div class="col-md-6">
                                    <textarea class="form-control" name="notes" placeholder="Write note for approve of reject here (optional)" rows="5">{{$pr->fr_approved_notes}}</textarea>
                                    <br>
                                    <button class="btn btn-success" type="submit" name="submit" value="Approve" onclick="return confirm('Are you sure want to approve?')">
                                        <i class="fa fa-check"></i>&nbsp;&nbsp;Approve
                                    </button>

                                    &nbsp;&nbsp;
                                    <button class="btn btn-danger" type="submit" name="submit" value="Reject" onclick="return confirm('Are you sure want to reject?')">
                                        <i class="fa fa-times"></i>&nbsp;&nbsp;Reject
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
                                @foreach($pr_detail as $key => $val)
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
@endsection
@section('custom_script')
@endsection
