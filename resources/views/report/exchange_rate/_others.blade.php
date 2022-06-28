<div class="modal-header">
    <h1 class="modal-title">Detail</h1>
</div>
<form action="{{ route('report.er.update') }}" method="post">
    @csrf
    <div class="modal-body">
        <div class="form-group row">
            <label class="col-form-label col-3">Date</label>
            <div class="col-9">
                <input type="date" class="form-control" name="_date" readonly value="{{ date("Y-m-d", strtotime($rate->date_rate)) }}">
            </div>
        </div>
        @if (!empty($jsRate))
            @foreach ($jsRate as $curr => $item)
                <div class="form-group row">
                    <label class="col-form-label col-3">{{ $curr }} - IDR</label>
                    <div class="col-9">
                        <input type="text" class="form-control number" name="rate[{{ $curr }}]" value="{{ $item }}" required>
                    </div >
                </div>
            @endforeach
        @endif
    </div>
    <div class="modal-footer">
        <input type="hidden" name="id_rate" value="{{ $rate->id }}">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
</form>
