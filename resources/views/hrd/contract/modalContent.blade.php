@foreach ($flds as $item)
    @php
        $value = "";
        if(!empty($item->field_emp)){
            if($item->field_emp == "salary"){
                $value = base64_decode($emp->salary) + base64_decode($emp->health) + base64_decode($emp->house) + base64_decode($emp->transport) + base64_decode($emp->meal);
            } else {
                if(isset($emp[$item->field_emp])){
                    $value = $emp[$item->field_emp];
                }
            }
        }
    @endphp
    <div class="form-group row">
        <h3 class="col-form-label col-3">{{ ucwords($item->name) }}</h3>
        @if ($item->type_data == "int")
        <div class="col-9">
            <input type="number" value="{{ $value }}" required class="form-control {{ (!empty($item->field_emp)) ? "field_emp" : "" }}" {{ (!empty($item->field_emp)) ? "id=$item->field_emp" : "" }} name="fld[{{ str_replace(" ", "_", strtolower($item->name)) }}]">
            <span class="text-danger" id="desc-{{ $item->id }}">*{{ $item->description }}</span>
        </div>
        @elseif ($item->type_data == "text")
        <div class="col-9">
            <input type="text" value="{{ $value }}" required class="form-control {{ (!empty($item->field_emp)) ? "field_emp" : "" }}" {{ (!empty($item->field_emp)) ? "id=$item->field_emp" : "" }} name="fld[{{ str_replace(" ", "_", strtolower($item->name)) }}]">
            <span class="text-danger" id="desc-{{ $item->id }}">*{{ $item->description }}</span>
        </div>
        @elseif ($item->type_data == "time")
        <div class="col-9">
            <input type="time" value="{{ $value }}" required class="form-control {{ (!empty($item->field_emp)) ? "field_emp" : "" }}" {{ (!empty($item->field_emp)) ? "id=$item->field_emp" : "" }} name="fld[{{ str_replace(" ", "_", strtolower($item->name)) }}]">
            <span class="text-danger" id="desc-{{ $item->id }}">*{{ $item->description }}</span>
        </div>
        @elseif ($item->type_data == "date")
        <div class="col-9">
            <input type="date" value="{{ $value }}" required class="form-control {{ (!empty($item->field_emp)) ? "field_emp" : "" }}" {{ (!empty($item->field_emp)) ? "id=$item->field_emp" : "" }} name="fld[{{ str_replace(" ", "_", strtolower($item->name)) }}]">
            <span class="text-danger" id="desc-{{ $item->id }}">*{{ $item->description }}</span>
        </div>
        @elseif ($item->type_data == "currency")
        <div class="col-9">
            <input type="text" value="{{ $value }}" required class="form-control {{ (!empty($item->field_emp)) ? "field_emp" : "" }} number" {{ (!empty($item->field_emp)) ? "id=$item->field_emp" : "" }} name="fld[{{ str_replace(" ", "_", strtolower($item->name)) }}]">
            <span class="text-danger" id="desc-{{ $item->id }}">*{{ $item->description }}</span>
        </div>
        @elseif ($item->type_data == "position")
        <div class="col-9">
            <div class="row">
                <div class="col-6">
                    <select name="fld[position][emp_type]" required id="emp-type" class="form-control select2">
                        <option value=""></option>
                        @foreach ($emptypes as $itype)
                            <option value="{{ $itype->id }}" {{ ($emp->emp_type == $itype->id) ? "SELECTED" : "" }}>{{ $itype->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6">
                    <select name="fld[position][emp_div]" required id="emp-div" class="form-control select2">
                        <option value=""></option>
                        @foreach ($div as $divType)
                            <option value="{{ $divType->id }}" {{ ($emp->division == $itype->id) ? "SELECTED" : "" }}>{{ $divType->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <span class="text-danger" id="desc-{{ $item->id }}">*{{ $item->description }}</span>
        </div>
        @endif
    </div>
@endforeach
<div class="form-group row">
    <h3 class="col-form-label col-3">Assign PPE</h3>
    <div class="col-9">
        <label class="checkbox col-form-label">
            <input type="checkbox"  name="assign_ppe" value="1" {{ ($etype->is_ppe == 1) ? "CHECKED" : "" }}/>
            <span></span>
            &nbsp;Yes
        </label>
    </div>
</div>
<div class="form-group row">
    <h3 class="col-form-label col-3">Nama Pembuat Kontrak</h3>
    <div class="col-9">
        <input type="text" name="pihak_pertama" class="form-control" required>
    </div>
</div>
<div class="form-group row">
    <h3 class="col-form-label col-3">Jabatan Pembuat Kontrak</h3>
    <div class="col-9">
        <input type="text" placeholder="HRD Manager" name="jabatan_pihak_pertama" class="form-control" required>
    </div>
</div>
<div class="form-group row">
    <h3 class="col-form-label col-3">Signature</h3>
    <div class="col-9">
        <div class="wrapper">
            <canvas class="signature-pad border"></canvas>
        </div>
        <br>
        <button type="button" class="btn btn-primary btn-xs" id="btn-sign-clear">Clear</button>
    </div>
</div>
