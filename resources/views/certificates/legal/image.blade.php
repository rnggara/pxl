<div class="modal-header">
    <h3 class="modal-title">Show Image</h3>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12 text-center">
            <img src="{{str_replace("public", "public_html", asset($file->file_name))}}" style="max-width: 400px" alt="">
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
    <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
        <i class="fa fa-check"></i>
        Upload</button>
</div>
