@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Delivery Order - {{ $do->no_do }}<br>
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
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Detail</h4>
                            <hr>
                            <input type="hidden" name="deliver_by" value="{{Auth::user()->username}}">
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label text-right">From</label>
                                <label class="col-md-6 col-form-label">: {{ $do->whFromName }}</label>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label text-right">To</label>
                                <label class="col-md-6 col-form-label">: {{ $do->whToName }}</label>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label text-right">Location</label>
                                <label class="col-md-6 col-form-label">: {{ $do->location }}</label>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label text-right">Division</label>
                                <label class="col-md-6 col-form-label">: {{ $do->division }}</label>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label text-right">Delivery Time</label>
                                <label class="col-md-6 col-form-label">: {{date('Y-m-d',strtotime($do->deliver_date))}}</label>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label text-right">Notes</label>
                                <label class="col-md-6 col-form-label">: {!! $do->notes !!}</label>
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
                                            <td class="text-center">
                                                {{$val->qty}}
                                            </td>
                                            <td class="text-center">
                                                {{ $val->type }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                                <span class="form-text text-muted">* UoM is Unit of Measurement</span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('custom_script')
    <script>
        function framePrint(whichFrame) {
            window.frames[whichFrame].focus();
            window.frames[whichFrame].print();
        }
    </script>
@endsection
