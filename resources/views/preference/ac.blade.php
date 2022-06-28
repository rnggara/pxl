<div class="card-header py-3">
    <div class="row">
        <div class="card-title align-items-start flex-column col-md-10">
            <h3 class="card-label font-weight-bolder text-dark">Activity Config</h3>
        </div>
        <div class="card-toolbar text-right">

        </div>
    </div>

</div>
<div class="row col-md-12 mx-auto m-5">
    <div class="col-md-12">
        <div class="row mx-auto">
            <div class="col-md-8">
                <div class="alert alert-warning">
                    <i class="alert-icon flaticon2-warning text-white"></i>
                    <span class="alert-text">Please fill in the fields according to the applicable process</span>
                </div>
            </div>
        </div>
    </div>
    <form action="{{route('pref.store_ac')}}" method="post">
        @csrf
        <div class="col-md-12">
            <table class="table table-responsive-xl display">
                <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th>Modul Name</th>
                    @foreach($action as $item)
                        <th class="text-center">{{$item->action_name}}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @foreach($label as $key => $item)
                    <tr>
                        <td align="center">{{$key + 1}}</td>
                        <td align="center">{{$item->name}}</td>
                        @foreach($action as $itemAction)
                            <td>
                                <?php
                                /** @var TYPE_NAME $item */
                                $step = json_decode($item->step);
                                ?>
                                @foreach($step as $valStep)
                                    @if($valStep == $itemAction->action_name)
                                        <input type="number" class="form-control" name="point[{{$item->id}}][{{$itemAction->action_name}}]" value="{{(isset($data_point[$item->id][$itemAction->action_name])) ? $data_point[$item->id][$itemAction->action_name] : 0}}">
                                    @endif
                                @endforeach
                            </td>
                        @endforeach
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-12 mt-10">
            <div class="text-right">
                <button type="submit" class="btn btn-success btn-xs"><i class="fa fa-save"></i> Save</button>
            </div>
        </div>
    </form>
</div>
