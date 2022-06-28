<div class="row">
    <div class="col-md-12">
        <div class="card card-custom gutter-b bg-light-secondary">
            <div class="card-header">
                <h3 class="card-title">Time Table</h3>
                <div class="card-toolbar">
                    <button class="btn btn-xs btn-icon btn-success" data-toggle="modal" data-target="#ttModal" onclick="tt_modal('{{$item->id}}')"><i class="fa fa-plus"></i></button>
                </div>
            </div>
            <div class="card-body overflow-auto">
                <div class="row bg-white p-5">
                    <div class="col-md-12">
                        <table class="table table-bordered table-hover display">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center" nowrap="nowrap">Due Date</th>
                                    <th class="text-center">Title</th>
                                    <th class="text-center">Notes</th>
                                    <th class="text-center">Follow Up</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tt as $key => $list)
                                    @if($list->id_step == $item->id)
                                        <tr class="{{($list->status == 1) ? "bg-success" : ""}}">
                                            <td align="center">{{$key+1}}</td>
                                            <td align="center" nowrap="nowrap">{{date('d F Y', strtotime($list->due_date))}}</td>
                                            <td align="center">{{$list->title}}</td>
                                            <td align="left">{{strip_tags($list->notes)}}</td>
                                            <td align="center">
                                                @if($list->status == 0)
                                                    <button type="button" class="btn btn-xs btn-icon btn-success" onclick="tt_follow(this, '{{$list->id}}')"><i class="fa fa-check"></i></button>
                                                @else
                                                    <button type="button" class="btn btn-xs btn-icon btn-light-dark" onclick="tt_follow(this, '{{$list->id}}')"><i class="fa fa-times"></i></button>
                                                @endif
                                            </td>
                                            <td align="center">
                                                <button type="button" onclick="tt_delete('{{$list->id}}')" class="btn btn-xs btn-icon btn-danger"><i class="fa fa-trash"></i></button>
                                            </td>
                                        </tr>
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

