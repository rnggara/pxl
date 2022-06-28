<form action="{{ route('business.investors.close') }}" method="post">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title">Close</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>
    <div class="modal-body">
        <div class="form-group row">
            <label for="" class="col-form-label col-3">Payment {{ date("F", strtotime($detail->plan_date)) }}</label>
            <div class="col-9">
                <input type="text" class="form-control number" value="{{ number_format($detail->cicilan + $detail->bunga, 2) }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-form-label col-3">Amount Left</label>
            <div class="col-9">
                <input type="text" id="left-amount" name="left_amount" class="form-control number" value="{{ number_format($investor->amount - $npaid, 2) }}" readonly>
            </div>
        </div>
        <div class="form-group row">
                <label for="" class="col-form-label col-3">Closing Value</label>
                <div class="col-9">
                    <input type="text" class="form-control number close-value" name="amount" id="close-amount">
                </div>
            </div>
        <div class="form-hide">
            <div class="form-group row">
                <label for="" class="col-form-label col-3">Investment Name</label>
                <div class="col-9">
                    <input type="text" class="form-control" name="investment_name">
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-form-label col-3">Investment Start</label>
                <div class="col-9">
                    <input type="date" class="form-control" name="start_from" value="{{ date("Y-m-d", strtotime("+1 month ".$detail->plan_date)) }}" min="{{ date("Y-m-d", strtotime("+1 month ".$detail->plan_date)) }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-form-label col-3">Profit Rate</label>
                <div class="col-9">
                    <input type="text" class="form-control number" name="profit_rate">
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="hidden" name="type" value="{{ $type }}">
        <input type="hidden" name="id" value="{{ $detail->id }}">
        <input type="hidden" name="id_business" value="{{ $investor->id_business }}">
        <input type="hidden" name="investor" value="{{ $investor->id_investor }}">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-danger">Commit Close</button>
    </div>
</form>
