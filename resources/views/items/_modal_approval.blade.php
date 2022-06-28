<form action="{{ route('items.approval.update') }}" method="post">
    @csrf
    <div class="modal-header">
        <h1 class="modal-title">Approve Item</h1>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-12">
                <div class="form-group row">
                    <label class="col-form-label col-3">Assign to existing item :</label>
                    <div class="col-9">
                        <div class="input-group">
                            <select name="_item_exist" class="form-control" id="_item_exist" required>
                                <option value=""></option>
                                @foreach ($all_item as $item)
                                    <option value="{{ $item->id }}">{{ "[$item->item_code] $item->name" }}</option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <input type="hidden" name="old_code" value="{{ $items->item_code }}">
                                <input type="hidden" name="id" value="{{ $items->id }}">
                                <button type="submit" name="submit" value="assign" class="btn btn-primary">Assign</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<form action="{{ route('items.approval.update') }}" method="post">
    @csrf
    <div class="modal-body">
        <hr>
        <div class="row">
            <div class="col-12">
                <label class="col-form-label">Or create new item : </label>
            </div>
            <div class="col-12">
                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-right">Item Name</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" placeholder="Item Name" name="item_name" value="{{ $items->name }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-right">Item Category</label>
                    <div class="col-md-9">
                        <select name="category" class="form-control select2" id="item-category" required>
                            <option value="">Select Category</option>
                            @foreach ($categories as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-right">Item Classification</label>
                    <div class="col-md-9">
                        <select name="classification" class="form-control select2" id="item-class" required>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-right">Item Type</label>
                    <div class="col-md-9">
                        <select name="type" class="form-control select2" id="item-type" required>
                            <option value="">Select Type</option>
                            <option value="1">Consumable</option>
                            <option value="2">Non Consumable</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-right">Brand Name</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" placeholder="Brand Name" name="item_series" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-right">Serial Number</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" placeholder="Serial Number" name="serial_number" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-right">Item Code</label>
                    <div class="col-md-9">
                        <input type="hidden" name="code" id="code">
                        <input type="text" class="form-control" placeholder="Item Code" name="item_code" id="item_code" readonly required>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="hidden" name="old_code" value="{{ $items->item_code }}">
        <input type="hidden" name="id" value="{{ $items->id }}">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="btn-create" name="submit" value="create">Create</button>
    </div>
</form>
