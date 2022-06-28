@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Dispatch</h3><br>

            </div>

        </div>
        <div class="card-body">
            <form method="post" id="form-add" action="{{URL::route('do.capture')}}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id_do" value="{{$do->id}}">

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <h4>Detail</h4>
                            <hr>
                            <input type="hidden" name="deliver_by" value="{{Auth::user()->username}}">
                            <div class="form-group row">
                                <label class="col-md-2 col-sm-2 col-form-label">
                                    <div class="row">
                                        <span class="col-8">
                                            From
                                        </span>
                                        <span class="col-4">
                                            :
                                        </span>
                                    </div>
                                </label>
                                <label class="col-md-10 font-weight-bold col-sm-2 col-form-label">{{ $do->whFromName }}</label>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-sm-2 col-form-label">
                                    <div class="row">
                                        <span class="col-8">
                                            To
                                        </span>
                                        <span class="col-4">
                                            :
                                        </span>
                                    </div>
                                </label>
                                <label class="col-md-10 font-weight-bold col-sm-2 col-form-label">{{ $do->whToName }}</label>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-sm-2 col-form-label">
                                    <div class="row">
                                        <span class="col-8">
                                            Location
                                        </span>
                                        <span class="col-4">
                                            :
                                        </span>
                                    </div>
                                </label>
                                <label class="col-md-10 font-weight-bold col-sm-2 col-form-label">{{ $do->location ?? "-" }}</label>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-sm-2 col-form-label">
                                    <div class="row">
                                        <span class="col-8">
                                            Division
                                        </span>
                                        <span class="col-4">
                                            :
                                        </span>
                                    </div>
                                </label>
                                <label class="col-md-10 font-weight-bold col-sm-2 col-form-label">{{ $do->division }}</label>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-sm-2 col-form-label">
                                    <div class="row">
                                        <span class="col-8">
                                            Delivery Time
                                        </span>
                                        <span class="col-4">
                                            :
                                        </span>
                                    </div>
                                </label>
                                <label class="col-md-10 font-weight-bold col-sm-2 col-form-label">{{ date("d F Y", strtotime($do->deliver_date)) }}</label>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-sm-12 col-form-label">
                                    <div class="row">
                                        <span class="col-8">
                                            Notes
                                        </span>
                                        <span class="col-4">
                                            :
                                        </span>
                                    </div>
                                </label>
                                <div class="col-md-6">
                                    {!! $do->notes !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4>Moving Item</h4>
                            <hr>
                            <div class="form-group row">
                                <table class="table table-bordered text-center table-responsive" id="list_item" >
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
                                            <td class="text-center"><span id="qty">{{$val->qty}}</span></td>
                                            <td class="text-center">
                                                <label class="">Transfer & Use</label>
                                            </td>

                                        </tr>
                                    @endforeach
                                </table>
                                <span class="form-text text-muted">* UoM is Unit of Measurement</span>
                            </div>
                           @if (empty($do->departure_at))
                           <label>File Browser</label>
                           <div class="custom-file" action="">
                               <input type="file" name="departure_file" accept="image/*" required capture="camera"/>
                           </div>
                           @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    @if (empty($do->departure_at))
                    <button type="submit" onclick="return confirm('Are you sure you want to dispatch?')" name="submit" value="dispatch" class="btn btn-primary font-weight-bold">
                        <i class="fa fa-check"></i>
                        Dispatch</button>
                    @else
                    @if (empty($do->approved_time))
                    <button type="submit" onclick="return confirm('Are you sure you want to receive?')" name="submit" value="recv" class="btn btn-primary font-weight-bold">
                        <i class="fa fa-check"></i>
                        Receive</button>
                    @endif
                    @endif
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
