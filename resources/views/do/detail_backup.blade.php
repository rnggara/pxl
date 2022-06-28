@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>@if($type == null) Edit Delivery Order @else Approval @endif</h3><br>

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
                                <label class="col-md-2 col-form-label text-right">Division</label>
                                <div class="col-md-6">
                                    <select name="division" id="division" class="form-control">
                                        <option value="">-Choose-</option>
                                        <option value="Asset" @if($do->division == "Asset") SELECTED @endif>Asset</option>
                                        <option value="Consultant" @if($do->division == "Consultant") SELECTED @endif>Consultant</option>
                                        <option value="Finance" @if($do->division == "Finance") SELECTED @endif>Finance</option>
                                        <option value="GA" @if($do->division == "GA") SELECTED @endif>GA</option>
                                        <option value="HRD" @if($do->division == "HRD") SELECTED @endif>HRD</option>
                                        <option value="IT" @if($do->division == "IT") SELECTED @endif>IT</option>
                                        <option value="Laboratory" @if($do->division == "Laboratory") SELECTED @endif>Laboratory</option>
                                        <option value="Maintenance" @if($do->division == "Maintenance") SELECTED @endif>Maintenance</option>
                                        <option value="Marketing" @if($do->division == "Marketing") SELECTED @endif>Marketing</option>
                                        <option value="Operation" @if($do->division == "Operation") SELECTED @endif>Operation</option>
                                        <option value="Procurement" @if($do->division == "Procurement") SELECTED @endif>Procurement</option>
                                        <option value="Production" @if($do->division == "Production") SELECTED @endif>Production</option>
                                        <option value="QC" @if($do->division == "QC") SELECTED @endif>QC</option
                                        ><option value="QHSSE" @if($do->division == "QHSSE") SELECTED @endif>QHSSE</option>
                                        <option value="Receiptionist" @if($do->division == "Receiptionist") SELECTED @endif>Receiptionist</option>
                                        <option value="Secretary" @if($do->division == "Secretary") SELECTED @endif>Secretary</option>
                                        <option value="Technical" @if($do->division == "Technical") SELECTED @endif>Technical</option>
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
                        </div>
                        <div class="col-md-6">
                            <h4>Moving Item</h4>
                            <hr>
                            <div class="form-group row">
                                <table class="table table-bordered" id="list_item">
                                    <thead>
                                    <tr>
                                        <th>Item Code</th>
                                        <th>Item Name</th>
                                        <th>UoM</th>
                                        <th>Quantity</th>
                                        <th>Transfer Type</th>
                                        <th>Action</th>
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
                                            <td class="text-center" style="vertical-align: middle;">
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
                                                <a href="{{route('dodetail.delete',['id' => $val->id,'do_id'=>$val->do_id])}}" class="btn btn-danger btn-xs"  title="Delete" onclick="return confirm('Are you sure you want to delete?')"><i class="fa fa-trash"></i></a>
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
                    @if($type == null)
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Update</button>
                    @else
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Approve</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection
