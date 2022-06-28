<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Edit Document Legal</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<form method="post" action="{{route('asset.legal.update')}}" >
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                    <label for="" class="col-form-label col-md-3">Expired Date</label>
                    <div class="col-md-9">
                        <input type="date" class="form-control" name="exp_date" value="{{$legal->exp_date}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-form-label col-md-3">Legal Doc. Name</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="name" value="{{$legal->name}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-form-label col-md-3">Doc. Name</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="description" value="{{$legal->description}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-form-label col-md-3">No Doc. Legal</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="certificate_no" value="{{$legal->certificate_no}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-form-label col-md-3">Doc. Legal Holder</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="certificate_holder" value="{{$legal->certificate_holder}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-form-label col-md-3">Owner of The Document</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="certificate_owner" value="{{$legal->certificate_owner}}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="hidden" name="id" value="{{$legal->id}}">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
            <i class="fa fa-check"></i>
            Add</button>
    </div>
</form>
