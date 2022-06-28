@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title col-md-6">
                <h3>@if(!in_array($type, ['appr', 'receive'])) Edit Delivery Order - {{ $do->no_do }} @else {{ ($type == "appr") ? "Approval" : "Receive DO" }} @endif</h3><br>
            </div>
            <div class="card-toolbar">
                <a href="javascript:framePrint('print_frame');" target="_self" class="btn btn-success pull-right"><i class="fa fa-print"></i> Print</a>
                    <iframe height="0" width="0" name="print_frame" frameborder="0" src="{{route('do.print',['id'=>$do->id])}}" ></iframe>
                    <a href="javascript:framePrint('print_frame_dot');" target="_self" class="btn btn-light-success pull-right"><i class="fa fa-print"></i> Print Dot Matrix</a>
                    <iframe height="0" width="0" name="print_frame_dot" frameborder="0" src="{{route('do.print',['id'=>$do->id, 'type' => 'matrix'])}}" ></iframe>
            </div>
        </div>
        <div class="card-body">
            <form method="post" id="form-add" action="{{URL::route('do.edit')}}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id_do" value="{{$do->id}}">
                <input type="hidden" name="type" value="{{$type}}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Detail</h4>
                            <hr>
                            <input type="hidden" name="deliver_by" value="{{Auth::user()->username}}">
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label text-right">From</label>
                                <div class="col-md-6">
                                    <select name="from" id="from" class="form-control">
                                        @foreach($wh as $key => $val)
                                            <option value="{{$val->id}}" @if($val->id == $do->from_id) SELECTED @endif>{{$val->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label text-right">To</label>
                                <div class="col-md-6">
                                    <select name="to" id="to" class="form-control">
                                        @foreach($wh as $key => $val)
                                            <option value="{{$val->id}}" @if($val->id == $do->to_id) SELECTED @endif>{{$val->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label text-right">Location</label>
                                <div class="col-md-6">
                                    <input type="text" name="location" class="form-control" value="{{ $do->location }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label text-right">Division</label>
                                <div class="col-md-6">
                                    <select name="division" id="division" class="form-control">
                                        <option value="">-Choose-</option>
                                        @foreach($divisions as $div)
                                            <option value="{{$div->name}}" {{($div->name == $do->division) ? "SELECTED" : ""}}>{{$div->name}}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label text-right">Delivery Time</label>
                                <div class="col-md-6">
                                    <input type="date" name="delivery_time" id="delivery_time" class="form-control" value="{{date('Y-m-d',strtotime($do->deliver_date))}}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label text-right">Notes</label>
                                <div class="col-md-6">
                                    <textarea name="notes" id="fr_note" cols="30" rows="10" class="form-control">{{$do->notes}}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-md-2">{{ (!empty($do->departure_at)) ? "Departure Info" : "" }}</label>
                                <div class="col-md-6">
                                    @if (!empty($file_driver))
                                        <img src="{{ str_replace("public", "public_html", asset($file_driver)) }}" style="width: 100%" alt="">
                                    @elseif (!empty($do->departure_by))
                                        {!! $do->departure_by !!}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4>Moving Item</h4>
                            <hr>
                            <div class="form-group row">
                                <table class="table table-bordered table-responsive-sm" id="list_item">
                                    <thead>
                                    <tr>
                                        <th>Item Code</th>
                                        <th>Item Name</th>
                                        <th>UoM</th>
                                        <th>Quantity</th>
                                        <th>Transfer Type</th>
                                        <th class="text-center">
                                            @actionStart('do', 'approvedir')
                                                @if (empty($do->approved_time))
                                                    @if (empty($do->departure_at))
                                                    <button type="button" class="btn btn-xs btn-icon btn-primary" data-toggle="modal" data-target="#modalAddItem"><i class="fa fa-plus"></i></button>
                                                    @endif
                                                @endif
                                            @actionEnd
                                        </th>
                                    </tr>
                                    </thead>
                                    @foreach($do_detail as $key => $val)
                                        <tr>
                                            <td>
                                                <div class="form-group" id="div-target">
                                                    <div id="autocomplete-div">{{$val->item_id}}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group" id="div-target">
                                                    <div id="autocomplete-div">{{$val->itemName}}</div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span id="uom">{{$val->itemUom}}</span>
                                            </td>
                                            <td class="text-center"><input type="number" size="2" id="qty" placeholder="Qty" class="form-control" value="{{$val->qty}}" readonly/></td>
                                            <td class="text-center">
                                                <select class="form-control" name="transfer_type" id="transfer_type" readonly>
                                                    <option value="Transfer" @if($val->type == 'Transfer') SELECTED @endif>Transfer</option>
                                                    <option value="Transfer & Use" @if($val->type == 'Transfer & Use') SELECTED @endif>Transfer & Use</option>
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                @if($do->approved_time == null)
                                                <a href="{{route('dodetail.delete',['id' => $val->id,'do_id'=>$val->do_id])}}" class="btn btn-icon btn-danger btn-xs"  title="Delete" onclick="return confirm('Are you sure you want to delete?')"><i class="fa fa-trash"></i></a>
                                                @else
                                                -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                                <span class="form-text text-muted">* UoM is Unit of Measurement</span>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    @if($type != 'appr' && $type != "receive")
                        @if($do->approved_time != null)
                            <a href="javascript:framePrint('print_frame');" target="_self" class="btn btn-success btn-lg pull-right"><i class="fa fa-print"></i> Print</a>
                            <iframe height="0" width="0" name="print_frame" frameborder="0" src="{{route('do.print',['id'=>$do->id])}}" ></iframe>
                        @else
                            @if (empty($do->departure_at) || empty($do->approved_time))
                                <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                                    <i class="fa fa-check"></i>
                                    Update
                                </button>
                            @endif
                        @endif
                    @else
                        @if ($type == "receive")
                            <input type="hidden" name="rpage" value="1">
                        @endif
                        <button type="submit" onclick="return confirm('Are you sure you want to approve?')" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            {{ ($type == "appr") ? "Approve" : "Receive" }}</button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalAddItem" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form action="{{ route('do.add_item') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-form-label col-md-3">Item</label>
                            <div class="col-md-9">
                                <select name="item_id" class="form-control select2" id="" required data-placeholder="Select Item">
                                    <option value=""></option>
                                    @foreach ($items as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-3">Qty</label>
                            <div class="col-md-9">
                                <input type="number" min="0" class="form-control" name="qty" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-3">Type</label>
                            <div class="col-md-9">
                                <select name="type" class="form-control select2" id="" required data-placeholder="Select Item">
                                    <option value="Transfer">Transfer</option>
                                    <option value="Transfer & Use">Transfer & Use</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_do" value="{{ $do->id }}">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Pass</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script>
        function framePrint(whichFrame) {
            window.frames[whichFrame].focus();
            window.frames[whichFrame].print();
        }

        $(document).ready(function(){
            $("select.select2").select2({
                width : "100%"
            })
        })
    </script>
@endsection
