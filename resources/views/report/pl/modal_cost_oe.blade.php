<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">{{($type == "oe") ? "OPERATING EXPENSE" : strtoupper($type)}}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<form action="" method="post">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                    <label for="" class="col-form-label col-md-3">Prognosis Value</label>
                    <div class="col-md-9">
                        <input type="text" name="value_p" id="prog_value" class="form-control" value="{{$detail->amount}}">
                    </div>
                </div>
                @if($type == "oe")
                    <div class="form-group row">
                        <label for="" class="col-form-label col-md-3">Percentage Value</label>
                        <div class="col-md-9">
                            <input type="text" name="" id="percentage_value" class="form-control">
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <hr>
</form>

<form method="POST" action="{{route('report.pl.whitelists', $type)}}">
    @csrf
    <div class="modal-body">
        {{--PO--}}
        <div class="row">
            <div class="col-md-12">
                <h4 class="text-center">Purchase Order </h4>
                @for($i=0; $i < 5; $i++)
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <select name="po[{{$i}}]" class="form-control select2" id="">
                                <option value="">Empty</option>
                                @foreach($prj_show as $item)
                                    <option value="{{$item->id}}" {{(isset($whitelist['po'][$i]) && $item->id == $whitelist['po'][$i]->project) ? "SELECTED" : ""}}>{{$item->prj_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select name="po_cat[{{$i}}][]" multiple class="form-control select2" id="">
                                <option value="">Empty</option>
                                @foreach($type_po as $item)
                                    <option value="{{$item->id}}" {{(isset($whitelist['po'][$i]) && in_array($item->id, $whitelist['po'][$i]->category)) ? "SELECTED" : ""}}>{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
        <hr>
        {{--WO--}}
        <div class="row">
            <div class="col-md-12">
                <h4 class="text-center">Work Order</h4>
                @for($i=0; $i < 5; $i++)
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <select name="wo[{{$i}}]" class="form-control select2" id="">
                                <option value="">Empty</option>
                                @foreach($prj_show as $item)
                                    <option value="{{$item->id}}" {{(isset($whitelist['wo'][$i]) && $item->id == $whitelist['wo'][$i]->project) ? "SELECTED" : ""}}>{{$item->prj_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select name="wo_cat[{{$i}}][]" multiple class="form-control select2" id="">
                                <option value="">Empty</option>
                                @foreach($type_wo as $item)
                                    <option value="{{$item->id}}" {{(isset($whitelist['wo'][$i]) && in_array($item->id, $whitelist['wo'][$i]->category)) ? "SELECTED" : ""}}>{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
        <hr>
        {{--TO--}}
        <div class="row">
            <div class="col-md-12">
                <h4 class="text-center">Travel Order</h4>
                @for($i=0; $i < 5; $i++)
                    <div class="row mb-5">
                        <div class="col-md-12">
                            <select name="to[{{$i}}]" class="form-control select2" id="">
                                <option value="">Empty</option>
                                @foreach($prj_show as $item)
                                    <option value="{{$item->id}}" {{(isset($whitelist['to'][$i]) && $item->id == $whitelist['to'][$i]) ? "SELECTED" : ""}}>{{$item->prj_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
        <hr>
        {{--CASHBOND--}}
        <div class="row">
            <div class="col-md-12">
                <h4 class="text-center">CASHBOND</h4>
                @for($i=0; $i < 5; $i++)
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <select name="cb[{{$i}}]" class="form-control select2" id="">
                                <option value="">Empty</option>
                                @foreach($prj_show as $item)
                                    <option value="{{$item->id}}" {{(isset($whitelist['cashbond'][$i]) && $item->id == $whitelist['cashbond'][$i]->project) ? "SELECTED" : ""}}>{{$item->prj_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select name="cb_cat[{{$i}}][]" multiple class="form-control select2" id="">
                                <option value="">Empty</option>
                                @foreach($type_wo as $item)
                                    <option value="{{$item->id}}" {{(isset($whitelist['cashbond'][$i]) && in_array($item->id, $whitelist['cashbond'][$i]->category)) ? "SELECTED" : ""}}>{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
        <hr>
        {{--REIMBURSE--}}
        <div class="row">
            <div class="col-md-12">
                <h4 class="text-center">REIMBURSE</h4>
                @for($i=0; $i < 5; $i++)
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <select name="rs[{{$i}}]" class="form-control select2" id="">
                                <option value="">Empty</option>
                                @foreach($prj_show as $item)
                                    <option value="{{$item->id}}" {{(isset($whitelist['reimburse'][$i]) && $item->id == $whitelist['reimburse'][$i]->project) ? "SELECTED" : ""}}>{{$item->prj_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select name="rs_cat[{{$i}}][]" multiple class="form-control select2" id="">
                                <option value="">Empty</option>
                                @foreach($type_wo as $item)
                                    <option value="{{$item->id}}" {{(isset($whitelist['reimburse'][$i]) && in_array($item->id, $whitelist['reimburse'][$i]->category)) ? "SELECTED" : ""}}>{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
        {{--SUB COST--}}
        <div class="row">
            <div class="col-md-12">
                <h4 class="text-center">SUB COST</h4>
                @for($i=0; $i < 5; $i++)
                    <div class="row mb-5">
                        <div class="col-md-12">
                            <select name="sc[{{$i}}]" class="form-control select2" id="">
                                <option value="">Empty</option>
                                @foreach($prj_show as $item)
                                    <option value="{{$item->id}}" {{(isset($whitelist['subcost'][$i]) && $item->id == $whitelist['subcost'][$i]) ? "SELECTED" : ""}}>{{$item->prj_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
        <hr>
        {{--PAYROLL--}}
        <div class="row">
            <div class="col-md-12">
                <h4 class="text-center">PAYROLL</h4>
                <input type="text" class="form-control" name="payroll" value="{{(isset($whitelist['payroll'])) ? $whitelist['payroll'] : ""}}">
            </div>
        </div>
        <hr>
        {{--INVOICE OUT--}}
        <div class="row">
            <div class="col-md-12">
                <h4 class="text-center">INVOICE OUT</h4>
                <hr>
                <div class="form-group">
                    <select name="inv_out[]" multiple class="form-control select2" id="">
                        <option value=""></option>
                        @foreach($inv as $item)
                            <option value="{{$item['id']}}" {{(isset($whitelist['inv_out']) && in_array($item['id'], $whitelist['inv_out'])) ? "SELECTED" : ""}}>{{$item['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <hr>
        {{--AGRREMENT & TAX--}}
        <div class="row">
            <div class="col-md-6">
                <h4 class="text-center">AGREEMENT NUMBER</h4>
                <div class="form-group">
                    <select name="agree[]" multiple class="form-control select2" id="">
                        <option value=""></option>
                        @foreach($inv as $item)
                            <option value="{{$item['id']}}" {{(isset($whitelist['agreement']) && in_array($item['id'], $whitelist['agreement'])) ? "SELECTED" : ""}}>{{$item['aggrement']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <h4 class="text-center">TAX</h4>
                <div class="form-group">
                    <select name="tax[]" multiple class="form-control select2" id="">
                        <option value=""></option>
                        @foreach($tax as $item)
                            <option value="{{$item['id']}}" {{(isset($whitelist['tax']) && in_array($item['id'], $whitelist['tax'])) ? "SELECTED" : ""}}>{{$item['tax_name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <hr>
        @if($type == "oe")
            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-center">Treasure History</h4>
                    <hr>
                    <div class="form-group">
                        <select name="tre_his[]" multiple class="form-control select2" id="">
                            <option value=""></option>
                            @foreach($tre_his as $tre => $item)
                                <option value="{{$tre}}" {{(isset($whitelist['treasure_history']) && in_array($tre, $whitelist['treasure_history'])) ? "SELECTED" : ""}} >{{$item}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <hr>
        @endif
        {{--DEBUGGING--}}
        <div class="row">
            <h4 class="col-md-3">DEBUGGING</h4>
            <div class="col-md-9">
                <textarea name="" class="form-control" id="" cols="30" rows="2">{{$detail->whitelists}}</textarea>
            </div>
        </div>
        <hr>
    </div>
    <div class="modal-footer">
        <input type="hidden" name="type" id="type">
        <input type="hidden" name="id_prog" value="{{$detail->id}}">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
        <button type="submit" id="btn-save-prognosis" class="btn btn-primary font-weight-bold">
            <i class="fa fa-check"></i>
            Save</button>
    </div>
</form>
