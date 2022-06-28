<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">List Items</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="text-right">
                <button type="button" class="btn btn-xs btn-primary" onclick="addItems({{$pd->id}}, '{{$pd->project_name}}')"><i class="fa fa-plus"></i>Add Items</button>
            </div>
        </div>
        <div class="col-md-12 mt-10">
            <table class="display table table-bordered table-hover table-responsive-xl table-striped" id="table-list">
                <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Serial Number</th>
                    <th class="text-center">Category</th>
                    <th class="text-center">Type</th>
                    <th class="text-center">Label</th>
                    <th class="text-center">Status</th>
                </tr>
                </thead>
                <tbody>
                @if(!empty($items))
                    @foreach($items as $key => $value)
                        <tr>
                            <td align="center">{{$key+1}}</td>
                            <td align="center"><button type="button" class="btn btn-xs btn-primary" onclick="view_items({{$value->id}})">{{$value->subject}}</button></td>
                            <td align="center">{{(isset($category[$value->category])) ? strtoupper($category[$value->category]) : "N/A"}}</td>
                            <td align="center">
                                @if($value->type == 1)
                                    MAIN EQUIPMENT
                                @elseif($value->type == 2)
                                    ACCESORIES
                                @else
                                    SAFETY EQUIPMENT
                                @endif
                            </td>
                            <td align="center">{{$value->subject}}</td>
                            <td align="center">{{($value->status == 1) ? "READY" : "NOT READY"}}</td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="hidden" name="id_el" id="el-id">
    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary font-weight-bold">
        <i class="fa fa-check"></i>
        Update</button>
</div>
