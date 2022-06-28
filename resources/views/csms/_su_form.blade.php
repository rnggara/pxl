<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Add Field</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<form method="post" action="{{route('qhse.csms.su.add_row')}}">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                @foreach(json_decode($su->field) as $name => $type)
                    <div class="form-group row">
                        <label for="" class="col-form-label col-md-3">{{ucwords(str_replace("_", " ", $name))}}</label>
                        <div class="col-md-9">
                            <input type="{{$type}}" class="form-control" name="{{$name}}" placeholder="{{ucwords(str_replace("_", " ", $name))}}">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="hidden" name="id_su" value="{{$su->id}}">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
        <button type="submit" id="btn-su-submit" class="btn btn-primary font-weight-bold">
            <i class="fa fa-check"></i>
            Add</button>
    </div>
</form>
