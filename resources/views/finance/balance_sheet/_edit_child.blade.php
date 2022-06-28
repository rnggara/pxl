<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel"><span id="title-add"></span>{{ strtoupper($detail->type) }} - {{ $detail->description }} - Add New</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<form method="post" action="{{URL::route('bs.detail.add')}}" enctype="multipart/form-data">
    @csrf
    <div class="modal-body">
        <input type="hidden" name="asset" value="1">
        <div class="form-group row">
            <label for="" class="col-md-2 col-form-label">Name</label>
            <div class="col-md-10">
                <input type="text" class="form-control" name="nama" value="{{ $detail->description }}" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-2 col-form-label text-right">{{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}</label>
            <div class="col-md-10">
                <select name="tc[]" class="form-control select2" multiple id="">
                    <option value="">&nbsp;</option>
                    @foreach($coa as $value)
                        <option value="{{$value->id}}"
                        @if($detail->tc != null)
                            @foreach(json_decode($detail->tc, true) as $item)
                                {{($item == $value->id) ? "SELECTED" : ""}}
                                @endforeach
                            @endif
                        >{{"[".$value->code."] ".$value->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div id="coa-target"></div>
    </div>
    <div class="modal-footer">
        <input type="hidden" name="id_edit" value="{{ $detail->id }}">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
            <i class="fa fa-check"></i>
            Save</button>
    </div>
</form>
