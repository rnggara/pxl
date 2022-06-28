<div class="modal-header">
    <h1 class="modal-title">Edit Depreciation</h1>
</div>
<form action="{{ route('finance.dp.add') }}" method="post">
    <div class="modal-body">
        <div class="form-group row">
            <label for="" class="col-form-label col-3">Item</label>
            <div class="col-9">
                <input type="hidden" id="item-edit" data-id="{{ $item->id }}" data-text="[{{ $item->item_code }}] {{ $item->name }}">
                <select name="item_id" class="form-control" id="item-list-edit" required>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-form-label col-3">Amount</label>
            <div class="col-9">
                <input type="text" class="form-control number" name="amount" value="{{ $dp->amount }}" required placeholder="Amount">
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-form-label col-3">Percentage</label>
            <div class="col-9">
                <input type="text" class="form-control number" name="pctg" value="{{ $dp->percentage }}" required placeholder="Percentage">
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-form-label col-3">Start/Time</label>
            <div class="col-6">
                <input type="number" min="1" value="{{ $dp->start }}" class="form-control" name="year" required>
            </div>
            <div class="col-3">
                <input type="number" min="1" class="form-control" value="{{ $dp->start_time }}" name="year_time" required placeholder="Time">
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-form-label col-3">{{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}</label>
            <div class="col-9">
                <input type="hidden" id="tc-edit" data-id="{{ $coa->id }}" data-text="[{{ $coa->code }}] {{ $coa->name }}">
                <select name="tc_id" class="form-control" id="tc_id_edit">
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        @csrf
        <input type="hidden" name="edit" value="1">
        <input type="hidden" name="id_dp" value="{{ $dp->id }}">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
</form>
