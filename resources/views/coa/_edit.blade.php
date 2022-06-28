<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Edit {{ !empty(\Session::get('company_tc_initial')) ? strtoupper(\Session::get('company_tc_initial')) : "TC" }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<form method="post" action="{{route('coa.store')}}" >
    @csrf
    <input type="hidden" name="edit" value="1">
    <input type="hidden" name="id" value="{{$value->id}}">
    <div class="modal-body">
        <div class="row">
            <div class="form col-md-12">
                <div class="form-group">
                    <label>{{ !empty(\Session::get('company_tc_initial')) ? strtoupper(\Session::get('company_tc_initial')) : "TC" }} Name</label>
                    <input type="text" class="form-control" name="name" id="name{{$value->id}}" value="{{$value->name}}"/>
                </div>
                <div class="form-group">
                    <label>Source </label>
                    <select class="form-control select2" name="source[]" id="source{{ $value->id }}" multiple>
                        <option value="">--Choose Source--</option>
                        @if (!empty($value->source))
                            @foreach (json_decode($value->source, true) as $iSrc)
                                @if (isset($srcAll[$iSrc]))
                                    <option value="{{ $iSrc }}" selected>{{ $srcAll[$iSrc] }}</option>
                                @endif
                            @endforeach
                        @endif
                        @foreach($source as $sKey => $val)
                            <option value="{{$sKey}}">{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Parent </label>
                    <select class="form-control select2" name="id_parent" onchange="idParent(this, {{$value->id}})" id="id_parent{{$value->id}}">
                        <option value="">--Choose Parent--</option>
                        <option value="new">New</option>
                        @foreach($coa as $key => $val)
                            <option value="{{$val->code}}" @if($val->code == $value->parent_id) selected @endif>{{$val->code}}-{{$val->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group row">
                    <div class="col-md-6">
                        <input type="number" class="form-control" placeholder="parent code" name="code_parent" id="code_parent{{$value->id}}" readonly/>
                    </div>
                    <div class="col-md-6">
                        <input type="number" class="form-control" name="code_child" placeholder="code" onchange="idParent2(this,{{$value->id}})" id="code_child{{$value->id}}" />
                        <input type="hidden" name="parentcode" id="parentcode{{$value->id}}"/>
                        <input type="hidden" name="newcode" id="newcode{{$value->id}}"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
        <button type="submit" name="submit" class="btn btn-primary font-weight-bold" id="btnSubmit{{$value->id}}">
            <i class="fa fa-check"></i>
            Update</button>
    </div>
</form>
