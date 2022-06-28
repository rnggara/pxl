<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Add Vehicle</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<form method="POST" action="{{route('ha.ve.update.vehicle')}}">
    @csrf
    <div class="modal-body">
        <div class="row" id="add-vehicle">
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-form-label col-md-4">Vehicle Name</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" required name="name" value="{{$ve->name}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-md-4">Type</label>
                    <div class="col-md-8">
                        <select name="category" id="" class="form-control select2" required>
                            <option value="">Select Category</option>
                            @foreach($category as $item)
                                <option value="{{$item->id}}" {{($item->id == $ve->category) ? "SELECTED" : ""}}>{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-md-4">Vendor</label>
                    <div class="col-md-8">
                        <select name="vendor" id="" class="form-control select2">
                            <option value="">Select Vendor</option>
                            @foreach($vendor as $item)
                                <option value="{{$item->id}}" {{($item->id == $ve->vendor_id) ? "SELECTED" : ""}}>{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-md-4">Specification</label>
                    <div class="col-md-8">
                        <textarea name="specification" cols="30" rows="10">{{$ve->description}}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-md-4">No BPKB</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" required name="bpkb" value="{{$ve->bpkb_no}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-md-4">BPKB Name</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" required name="bpkb_name" value="{{$ve->bpkb_name}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-md-4">Used By</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="used_by" value="{{$ve->used_by}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-md-4">Location</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="location" value="{{$ve->location}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-md-4">STNK</label>
                    <div class="col-md-8">
                        <select name="stnk" id="stnk-edit" class="form-control">
                            @foreach($cert as $item)
                                <option value="{{$item->id}}" {{($item->id == $ve->certificate_id) ? "SELECTED" : ""}}>{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="hidden" name="id_ve" value="{{$ve->id}}">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
        <button type="submit" id="btn-save-leads" class="btn btn-primary font-weight-bold">
            <i class="fa fa-check"></i>
            Update</button>
    </div>
</form>
