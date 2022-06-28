<div class="row">
    <div class="col-md-12">
        <div class="card card-custom gutter-b bg-light-secondary">
            <div class="card-header">
                <h3 class="card-title">Outbox List</h3>
                <div class="card-toolbar">
                    <button class="btn btn-sm btn-icon btn-success" data-toggle="modal" data-target="#olModal" onclick="ol_modal('{{$item->id}}')"><i class="fa fa-plus"></i></button>
                </div>
            </div>
            <div class="card-body overflow-auto">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-custom gutter-b card-stretch">
                            <div class="card-body">
                                <table class="table table-hover table-bordered display">
                                    <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        @foreach($field_ol as $key => $form)
                                            <th class="text-center">{{ucwords(str_replace("_", " ", $key))}}</th>
                                        @endforeach
{{--                                        <th></th>--}}
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($ol as $list)
                                        @if($list->id_step == $item->id)
                                            @foreach(json_decode($list->values) as $key => $val)
                                                <tr>
                                                    <td align="center">{{$key+1}}</td>
                                                    <td align="center">{{$val->name}}</td>
                                                    <td align="center">{{$val->delivered_by}}</td>
                                                    <td align="center">{{$val->delivered_at}}</td>
{{--                                                    <td align="center">--}}
{{--                                                        <button type="button" class="btn btn-xs btn-icon btn-danger"><i class="fa fa-trash"></i></button>--}}
{{--                                                    </td>--}}
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

