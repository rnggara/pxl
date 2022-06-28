<div class="row">
    <div class="col-md-12">
        <div class="card card-custom gutter-b bg-light-secondary">
            <div class="card-header">
                <h3 class="card-title">Summary</h3>
                <div class="card-toolbar">
                    <button class="btn btn-sm btn-icon btn-success" data-toggle="modal" data-target="#suFieldModal" onclick="show_modal('#id_step_su','{{$item->id}}')"><i class="fa fa-plus"></i></button>
                </div>
            </div>
            <div class="card-body overflow-auto">
                <div class="row">
                    <div class="col-md-12">
                        @php $i = 1 @endphp
                        @foreach($summary_field as $table)
                            @if($table->id_step == $item->id)
                                <div class="card card-custom gutter-b">
                                    <div class="card-body">
                                        <div class="row">
                                            <h3 class="col-md-6">{{ucwords($table->title)}} Summary</h3>
                                            <div class="col-md-6 text-right">
                                                <button type="button" onclick="delete_su('{{$table->id}}')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Delete</button>
                                            </div>
                                        </div>
                                        <div class="row mt-10">
                                            <div class="col-md-12">
                                                <table class="table table-hover table-bordered display">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-center">
                                                            #
                                                        </th>
                                                        @foreach(json_decode($table->field) as $field => $type)
                                                            <th class="text-center">{{ucwords(str_replace("_", " ", $field))}}</th>
                                                        @endforeach
                                                        <th class="text-center">
                                                            <button type="button" data-toggle="modal" data-target="#suAddRowModal" onclick="su_add('{{$table->id}}')" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Data Row</button>
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @if(!empty($table->values))
                                                        @foreach(json_decode($table->values) as $key => $value)
                                                            <tr>
                                                                <td align="center">{{$key+1}}</td>
                                                                @foreach(json_decode($table->field) as $field => $type)
                                                                    <td align="center">{{$value->$field}}</td>
                                                                @endforeach
                                                                <td align="center">
                                                                    <button type="button" onclick="su_delete('{{$table->id}}', '{{$key}}')" class="btn btn-xs btn-danger btn-icon"><i class="fa fa-trash"></i></button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php $i++ @endphp
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

