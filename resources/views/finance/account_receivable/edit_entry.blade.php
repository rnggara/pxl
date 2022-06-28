<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Edit Payment </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<form action="{{URL::route('ar.update')}}" method="post">
    @csrf
    <div class="modal-body">
        <div class="form-group row">
            <label for="" class="col-form-label col-md-3 text-right">Activity</label>
            <div class="col-md-9">
                <input type="text" class="form-control" name="activity" value="{{ $detail->activity }}" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-form-label col-md-3 text-right">Date</label>
            <div class="col-md-9">
                <input type="date" class="form-control" name="date" value="{{ $detail->date }}" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-form-label col-md-3 text-right">Payment Account</label>
            <div class="col-md-9">
                <select name="bank_src" class="form-control select2" id="" required>
                    <option value="">Select Bank</option>
                    @foreach($banks as $bank)
                        <option value="{{$bank->id}}" {{ ($bank->id == $detail->payment_account) ? "selected" : "" }} >{{"[".$bank->currency."] ".$bank->source}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-form-label col-md-3 text-right">Taxes</label>
            <div class="col-md-9">
                <select name="tax[]" multiple class="form-control select2" id="">
                    @foreach($taxes as $tax)
                        <option value="{{$tax->id}}" {{ (is_array(json_decode($detail->taxes)) && in_array($tax->id, json_decode($detail->taxes))) ? "SELECTED" : "" }} >{{$tax->tax_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-3 text-right">{{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}</label>
            <div class="col-9">
                <select name="tc_id" class="form-control select2" id="">
                    <option value="">Choose here</option>
                    @foreach ($coa as $item)
                        <option value="{{ $item->id }}" {{ ($detail->tc_id == $item->id) ? "SELECTED" : "" }}>[{{ $item->code }}] {{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-form-label col-md-3 text-right">WAPU</label>
            <div class="col-md-9">
                <label class="col-form-label checkbox checkbox-outline checkbox-outline-2x checkbox-success">
                    <input type="checkbox" name="wapu" {{ ($detail->wapu == "on") ? "checked" : "" }}/>
                    <span></span>
                </label>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-12">
                <div class="alert alert-primary">
                    <i class="fa fa-info-circle text-white"></i> If WAPU is selected, the invoice will not receive additional amount for Ppn 10%
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="hidden" name="id_detail" value="{{$detail->id}}">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
            <i class="fa fa-check"></i>
            Update</button>
    </div>
</form>
