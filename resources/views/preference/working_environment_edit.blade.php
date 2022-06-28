<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add New Working Environment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>
    <form action="{{route('pref.update_we')}}" method="post">
        @csrf
        <div class="modal-body">
            <div class="form-group row">
                <label for="" class="col-form-label col-md-3">Name</label>
                <div class="col-md-9">
                    <input type="text" class="form-control" name="name" required value="{{$we->name}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-form-label col-md-3">Tag</label>
                <div class="col-md-9">
                    <input type="text" class="form-control" name="tag" required value="{{strtoupper($we->tag)}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-form-label col-md-3">Formula</label>
                <div class="col-md-9">
                    <input type="text" class="form-control" name="formula" value="{{$we->formula}}">
                    <span class="font-size-xs text-secondary">eg = $rate + 100000. [+, -, *, /]</span>
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <input type="hidden" name="id_we" value="{{$we->id}}">
            <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary font-weight-bold">Add</button>
        </div>
    </form>
</div>
