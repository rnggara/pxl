<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Edit Project</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<form method="POST" action="{{URL::route('te.swt.update')}}">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-form-label col-md-4">Project Name</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="project_name" placeholder="Project Name" value="{{$swt_edit->subject}}" required/>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <input type="hidden" name="id_swt" value="{{$swt_edit->id}}">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
        <button type="submit" id="btn-save-leads" class="btn btn-primary font-weight-bold">
            <i class="fa fa-check"></i>
            Save</button>
    </div>
</form>
