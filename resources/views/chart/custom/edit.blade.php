<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Edit Category</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<form method="POST" action="{{URL::route('chart.custom.update')}}">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" value="{{$chart->name}}" class="form-control" required="">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="description">{{$chart->description}}</textarea>
                </div>
                <div class="form-group">
                    <label>From</label>
                    <input type="date" class="form-control" value="{{$chart->date_from}}" name="date_from" required="">
                </div>
                <div class="form-group">
                    <label>To</label>
                    <input type="date" class="form-control" value="{{$chart->date_to}}" name="date_to" required="">
                </div>
                <div class="form-group">
                    <label>Project</label>
                    <select class="form-control" name="project" required="">
                        <option value="0" {{($chart->project == 0) ? "selected" : ""}}>All</option>
                        @foreach($project as $item)
                            <option value="{{$item->id}}" {{($chart->project == $item->id) ? "selected" : ""}}>{{$item->prj_name}}</option>
                        @endforeach
                    </select>
                </div>
                <?php for ($i=0; $i < 5; $i++) {
                /** @var TYPE_NAME $chart */
                $line = json_decode($chart['line_'.($i+1)]);
                    ?>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Line <?= $i+1 ?></label>
                        </div>
                        <div class="col-md-6">
                            <label>Stack</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <select class="form-control" name="type[<?= $i+1 ?>]" <?= ($i == 0) ? "required" : "" ?>>
                                <option value="">-CHOOSE-</option>
                                @foreach($arr as $item)
                                    <option value="{{$item}}" {{(!empty($line) && $line->type == $item) ? "selected" : ""}}>{{ucwords(str_replace("_", " ", $item))}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="control-label" style="margin-top: 10px">
                                <input type="checkbox" {{(!empty($line) && $line->stack == "on") ? "checked" : ""}} name="stack[<?= $i+1 ?>]">
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <input type="hidden" name="id_chart" value="{{$chart->id}}">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
        @actionStart('custom_charts', 'update')
        <button type="submit" id="btn-save-leads" class="btn btn-primary font-weight-bold">
            <i class="fa fa-check"></i>
            Save</button>
        @actionEnd
    </div>
</form>
