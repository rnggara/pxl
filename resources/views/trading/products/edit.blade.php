<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Add Item</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<form method="post" action="{{URL::route('trading.products.update')}}" enctype="multipart/form-data">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <h4>Basic Information</h4>
                <hr>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">Picture</label>
                    <div class="col-md-6">
                        <div class="col-lg-9 col-xl-6">
                            <div class="image-input image-input-outline" id="app_logo">
                                <div class="image-input-wrapper"></div>
                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change">
                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                    <input type="file" name="pict" id="p_logo_edit" accept=".png, .jpg, .jpeg" />
                                </label>
                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel">
                                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                                        </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">Product Name</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Product Name" name="product_name" value="{{$product->name}}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">Supplier</label>
                    <div class="col-md-6">
                        <select name="supplier" id="" class="form-control select2" required>
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $value)
                                <option value="{{$value->id}}" {{($product->supplier == $value->id) ? "SELECTED" : ""}}>{{ucwords($value->name)}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">Serial Number</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Serial Number" name="serial_number" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">UoM</label>
                    <div class="col-md-6">
                        <select name="uom" id="uom" class="form-control" required>
                            <option value="">- Select UOM -</option>
                            @foreach($uom as $v)
                                <option value="{{$v}}">{{$v}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <h4>Detail Info</h4>
                <hr>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">Notes</label>
                    <div class="col-md-6">
                        <textarea name="notes" class="form-control" id="" cols="30" rows="10"></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">Sample</label>
                    <div class="col-md-6 custom-file">
                        <input type="file" class="custom-file-input" name="upload_sample">
                        <span class="custom-file-label">Choose File</span>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">Lab</label>
                    <div class="col-md-6 custom-file">
                        <input type="file" class="custom-file-input" name="upload_lab">
                        <span class="custom-file-label">Choose File</span>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">Survey</label>
                    <div class="col-md-6 custom-file">
                        <input type="file" class="custom-file-input" name="upload_survey">
                        <span class="custom-file-label">Choose File</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
            <i class="fa fa-check"></i>
            Add</button>
    </div>
</form>
