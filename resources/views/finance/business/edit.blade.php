<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Edit Business</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<form method="post" action="{{URL::route('business.update')}}" enctype="multipart/form-data">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <h3>Basic Info</h3>
                <hr>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-right">Project Name</label>
                    <div class="col-md-9">
                        <input type="text" maxlength="15" class="form-control" placeholder="Project Name" name="prj_name" value="{{$business->bank}}" required>
                        <span class="text-danger">Max length is 15</span>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-right">Partner Name</label>
                    <div class="col-md-9">
                        <select name="partner_name" class="form-control select2" id="" required>
                            <option value="">Select Partner</option>
                            @foreach ($partners as $id => $partnerName)
                                <option value="{{ $id }}" {{ ($business->partner == $id) ? "SELECTED" : "" }}>{{ $partnerName }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-right">Invested Amount</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control number" placeholder="Invested Amount" name="amount" value="{{$business->value}}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-right">Investment Interest Percentage %</label>
                    <div class="col-md-7">
                        <input type="text" class="form-control" placeholder="Investment Interest Percentage %" value="{{$business->bunga}}" name="percentage" required>
                        <span class="text-primary">(Fill with percent per month.)</span>
                    </div>
                    <label class="col-md-2 col-form-label">%</label>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-right">Currency</label>
                    <div class="col-md-9">
                        <select name="currency" class="form-control select2" required>
                            @foreach(json_decode($list_currency) as $key => $value)
                                <option value="{{$key}}" {{($key == $business->currency) ? "selected" : ""}}>{{strtoupper($key."-".$value)}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-right">Return Duration</label>
                    <div class="col-md-7">
                        <input type="number" class="form-control" placeholder="Return Duration" value="{{$business->period}}" name="duration" required readonly>
                    </div>
                    <label class="col-md-2 col-form-label">Month(s)</label>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-right">Account Information</label>
                    <div class="col-md-7">
                        <textarea name="account_info" class="form-control txt-tiny" id="" cols="30" rows="10">{!! $business->account_info !!}</textarea>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <h3>Details</h3>
                <hr>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-right">Business Investment Given at</label>
                    <div class="col-md-9">
                        <input type="date" class="form-control" name="given_at" value="{{$business->moneydrop}}" required readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-right">Return Payment Start Date</label>
                    <div class="col-md-9">
                        <input type="date" class="form-control" name="start_at" value="{{$business->start}}" required readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-right">Proportional Type</label>
                    <div class="col-md-9">
                        <select name="proportional" class="form-control select2" required>
                            <option value="">Select proportional Type</option>
                            <option value="PRO" {{($business->type == "PRO") ? "SELECTED" : ""}}>Proportional</option>
                            <option value="LUM" {{($business->type == "LUM") ? "SELECTED" : ""}}>Lumpsum</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-right">Penalty</label>
                    <div class="col-md-7">
                        <input type="text" class="form-control number" placeholder="Penalty" value="{{$business->own_amount}}" name="own_amount" required>
                        <span class="text-primary">(per day)</span>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-right">Penalty Remarks</label>
                    <div class="col-md-9">
                        <textarea name="own_remarks" class="form-control txt-tiny" cols="30" rows="10">{!! $business->own_remarks !!}</textarea>
                        <span class="text-primary">if penalty doesn't paid, the consequences are as described here.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="hidden" name="id_business" value="{{$business->id}}">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
        @actionStart('business', 'update')
        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
            <i class="fa fa-check"></i>
            Update</button>
        @actionEnd
    </div>

    <script src="{{asset('assets/jquery-number/jquery.number.js')}}"></script>

<script>


    $(".number").number(true, 2)
</script>


</form>



