<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">{{$item->subject}}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<form method="POST" action="{{URL::route('te.el.update')}}" enctype="multipart/form-data">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-form-label col-md-4">Label</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="label" id="el-label" value="{{$item->subject}}" placeholder="Label" readonly/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-md-4">Type</label>
                    <div class="col-md-8">
                        <?php
                        /** @var TYPE_NAME $item */
                        if ($item->type == 1){
                            $type = "MAIN EQUIPMENT";
                        } elseif ($item->type == 1){
                            $type = "ACCESORIES";
                        } else {
                            $type = "SAFETY EQUIPMENT";
                        }

                        $tag = (isset($cat[$item->category]['tag'])) ? $cat[$item->category]['tag'] : "";
                        $sep = json_decode($item->additional_information);
                        ?>
                        <input type="text" class="form-control" name="label" id="el-label" value="{{$type}}" placeholder="Label" readonly/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-md-4" id="edit-param1-label">
                        {{($tag == "SEP" || $tag == "SCRB") ? "Dimension" : "Capacity"}}
                    </label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="el-param1" value="{{$item->param1}}" name="param1" readonly/>
                    </div>
                </div>
                @if($tag == "SEP" || $tag == "SCRB")
                <div class="form-group row" id="edit-param2">
                    <label class="col-form-label col-md-4" id="param2-label">Design Pressure</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="el-param2" value="{{$item->param2}}" name="param2" readonly/>
                    </div>
                </div>
                @endif
                <div class="form-group row">
                    <label class="col-form-label col-md-4">COI Expiry</label>
                    <div class="col-md-8">
                        <input type="text" value="{{($item->coi_expiry != "0000-00-00") ? date("d F Y", strtotime($item->coi_expiry)) : "N/A"}}" class="form-control" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-md-4">Status</label>
                    <div class="col-md-8">
                        <input type="text" value="{{($item->status == 1) ? "READY" : "NOT READY"}}" class="form-control" readonly>
                    </div>
                </div>
                @if($tag == "SEP")
                <div id="edit-target-separator">
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">Capacity Oil</label>
                        <div class="col-md-8">
                            <input type="text" name="capacity_oil" value="{{$sep->capacity_oil}}" id="el-coil" placeholder="Capacity Oil" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">Capacity Water</label>
                        <div class="col-md-8">
                            <input type="text" name="capacity_water" value="{{$sep->capacity_water}}" id="el-cwater" placeholder="Capacity Water" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">Capacity Gas</label>
                        <div class="col-md-8">
                            <input type="text" name="capacity_gas" value="{{$sep->capacity_gas}}" id="el-cgas" placeholder="Capacity Gas" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">Retention Time</label>
                        <div class="col-md-8">
                            <input type="text" name="retention_time" value="{{$sep->retention_time}}" id="el-rtime" placeholder="Retention Time" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-form-label col-md-4">Description</label>
                    <div class="col-md-8">
                        {!! $item->description !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="hidden" name="id_el" id="el-id">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
    </div>
</form>
