<form action="{{ route('general.operation.setting_update') }}" method="post">
    <div class="modal-header">
        <h1 class="modal-title">Add Record</h1>
    </div>
    <div class="modal-body">
        <div class="form-group row">
            <label for="" class="col-md-3 col-sm-12 col-form-label">Category</label>
            <div class="col-md-9 col-sm-12">
                <select name="_category" class="form-control select2 required" aria-placeholder="Category">
                    @foreach ($_cat as $key => $item)
                        <option value="{{ $key }}" {{ ($record->category == $key) ? "SELECTED" : "" }}>{{ $item }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-md-3 col-sm-12 col-form-label">Name</label>
            <div class="col-md-9 col-sm-12">
                <input type="text" class="form-control required" name="_name" value="{{ $record->item_name }}" aria-placeholder="Name">
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-md-3 col-sm-12 col-form-label">Description</label>
            <div class="col-md-9 col-sm-12">
                <textarea name="_description" class="form-control tmce required" id="" cols="30" rows="10" aria-placeholder="Description">{!! $record->description !!}</textarea>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-md-3 col-sm-12 col-form-label">Unit of Measurements (satuan)</label>
            <div class="col-md-9 col-sm-12">
                <input type="text" class="form-control required" name="_uom" aria-placeholder="Unit of Measurements" value="{{ $record->uom }}">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        @csrf
        <input type="hidden" name="id_detail" value="{{ $record->id }}">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" onclick="_validation(this)">Edit</button>
    </div>
</form>
