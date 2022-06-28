<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">SALES</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<form action="{{route('marketing.prognosis.update')}}" method="post">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                    <label for="" class="col-form-label col-md-3">Prognosis Value</label>
                    <div class="col-md-9">
                        <input type="text" name="value_p" class="form-control" value="{{$detail->amount}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-form-label col-md-3">Notes</label>
                    <div class="col-md-9">
                        <textarea name="notes" class="form-control" id="" cols="30" rows="10">{{$detail->notes}}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-form-label col-md-3"></label>
                    <div class="col-md-9">
                        <input type="hidden" name="id_prog" value="{{$detail->id}}">
                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
</form>

<form method="POST" action="{{route('marketing.prognosis.whitelists', 'sales')}}">
    @csrf
    <div class="modal-body">
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
