<form action="{{ route('business.investors.pay') }}" method="post">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title">Pay</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>
    <div class="modal-body">
        <div class="form-group row">
            <label for="" class="col-form-label col-3">Profit Rate</label>
            <div class="col-9">
                <input type="text" class="form-control number" value="{{ number_format($detail->bunga_rate, 2) }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-form-label col-3">Installment</label>
            <div class="col-9">
                <input type="text" class="form-control number" value="{{ number_format($detail->cicilan, 2) }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-form-label col-3">Profit</label>
            <div class="col-9">
                <input type="text" class="form-control number" value="{{ number_format($detail->bunga, 2) }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-form-label col-3">Total Amount</label>
            <div class="col-9">
                <input type="text" class="form-control number" value="{{ number_format($detail->cicilan + $detail->bunga, 2) }}" readonly>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="hidden" name="type" value="{{ $type }}">
        <input type="hidden" name="id" value="{{ $detail->id }}">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Pay</button>
    </div>
</form>
