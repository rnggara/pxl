<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Edit </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<form method="post" action="{{URL::route('finance.cf.settings')}}">
    @csrf
    <div class="modal-body">
        <div class="form-group row">
            <label class="col-md-2 col-form-label text-right font-weight-bold">Label</label>
            <div class="col-md-10">
                <input type="text" class="form-control" required name="label" value="{{ $st->label }}">
            </div>
        </div>
        @if (!empty($st->parent))
        <div class="form-group row">
            <label class="col-md-2 col-form-label text-right font-weight-bold"></label>
            <div class="col-md-10 text-right">
                <button type="button" onclick="_add_row(this)" class="btn btn-primary btn-icon btn-sm"><i class="fa fa-plus"></i></button>
            </div>
        </div>
        @for ($i = 0; $i < $num; $i++)
        <fieldset class="border p-5 fl" id="dup-fieldset-edit">
            <legend class="w-auto">Source {{ $i+1 }}</legend>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label">Project</label>
                        <select name="prj[{{ $i }}]" class="form-control select2 prj" data-placeholder="All Project">
                            <option value=""></option>
                            @foreach ($projects as $item)
                                <option value="{{ $item->id }}" {{ (isset($prj[$i]) && $item->id == $prj[$i]) ? "SELECTED" : "" }} >[{{ sprintf("%02d", $item->id) }}] - {{ $item->prj_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label">{{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}</label>
                        <select name="tc[{{ $i }}][]" class="form-control select2 tc" multiple data-allow-clear="true" data-placeholder="Select {{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}" required>
                            @foreach($coa as $value)
                                <option value="{{$value->id}}" {{ in_array($value->id, $tcs[$i]) ? "SELECTED" : "" }}>{{"[".$value->code."] ".$value->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-12 text-right div-rm">
                    @if ($i > 0)
                    <button type="button" onclick="_remove_row(this)" class="btn btn-icon btn-danger btn-sm"><i class="fa fa-times"></i></button>
                    @endif
                </div>
            </div>
        </fieldset>
        @endfor
        @endif

    </div>
    <div class="modal-footer">
        <input type="hidden" name="id_st" value="{{ $st->id }}">
        @if (!empty($st->parent))
            <input type="hidden" name="is_child" value="1">
        @endif
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
            <i class="fa fa-check"></i>
            Save</button>
    </div>
</form>
