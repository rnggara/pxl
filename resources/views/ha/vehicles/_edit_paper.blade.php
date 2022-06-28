<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Edit Paper</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<form method="POST" action="{{route('ha.ve.update.paper')}}">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                    <label for="" class="col-form-label col-md-4">Police Number</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control required" name="paper_name" value="{{$cert->name}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-form-label col-md-4">STNK Number</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control required" name="paper_number" value="{{$cert->certificate_no}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-form-label col-md-4">STNK Date</label>
                    <div class="col-md-8">
                        <input type="date" class="form-control required" name="paper_date" value="{{$cert->exp_date}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-form-label col-md-4">STNK Value</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control number required" name="paper_value" value="{{$cert->certificate_value}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-form-label col-md-4">STNK Owner</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control required" name="paper_owner" value="{{$cert->certificate_owner}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-form-label col-md-4">STNK Holder</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control required" name="paper_holder" value="{{$cert->certificate_holder}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-form-label col-md-4">STNK Specifications</label>
                    <div class="col-md-8">
                        <textarea class="form-control" name="paper_spec" cols="30" rows="10">{{$cert->description}}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-form-label col-md-4">STNK Year/Color</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control required" name="stnk_y_c" id="stnk-y-c-paper" value="{{$cert->others}}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="hidden" name="_action" value="post">
        <input type="hidden" name="id_paper" value="{{$cert->id}}">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
        <button type="submit" id="btn-save-leads" class="btn btn-primary font-weight-bold">
            <i class="fa fa-check"></i>
            Save</button>
    </div>
</form>
