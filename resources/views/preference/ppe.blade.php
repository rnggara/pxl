<div class="card-header py-3">
    <div class="row">
        <div class="card-title align-items-start flex-column col-md-10">
            <h3 class="card-label font-weight-bolder text-dark">PPE</h3>
        </div>
        <div class="card-toolbar text-right">

        </div>
    </div>

</div>
<div class="card-body">
    <form action="{{ route('pref.ppe.storage') }}" method="post">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-form-label col-2">Storage From</label>
                    <div class="col-md-3">
                        <select name="storage" class="form-control select2" data-placeholder="Select Storage" required>
                            <option value=""></option>
                            @foreach ($wh as $item)
                                <option value="{{ $item->id }}" {{ ($item->id == $preferences->ppe_wh) ? "SELECTED" : "" }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        @csrf
                        <input type="hidden" name="id" value="{{ $company->id }}">
                        <button type="submit" class="btn btn-success"><i class="fa fa-check"></i>Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="row mx-auto m-5">
    <div class="col-md-12">
        <table class="table table-bordered display">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Description</th>
                    <th class="text-center">Item</th>
                    <th class="text-center">Quantity</th>
                    <th class="text-center">
                        <button type="button" data-toggle="modal" data-target="#modalAddPPE" class="btn btn-primary btn-xs btn-icon"><i class="fa fa-plus"></i></button>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ppe as $i => $item)
                    <tr>
                        <td align="center">{{ $i+1 }}</td>
                        <td>{{ $item->description }}</td>
                        <td>
                            <ol>
                                @for ($j = 0; $j < count($item->item_arr); $j++)
                                    <li>{{ $item->item_arr[$j]['text'] }}</li>
                                @endfor
                            </ol>
                        </td>
                        <td align="center">
                            {{ $item->qty }}
                        </td>
                        <td align="center">
                            <button type="button" data-toggle="modal" data-target="#modalAddPPE{{ $item->id }}" class="btn btn-icon btn-xs btn-primary"><i class="fa fa-edit"></i></button>
                            <button type="button" class="btn btn-icon btn-xs btn-danger"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                    <div class="modal fade" id="modalAddPPE{{ $item->id }}" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title">Add Template PPE</h1>
                                    <button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
                                </div>
                                <form action="{{ route('pref.ppe.add') }}" method="post">
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label for="" class="col-form-label col-3">Description</label>
                                            <div class="col-9">
                                                <input type="text" value="{{ $item->description }}" class="form-control" required name="desc">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="col-form-label col-3">Items</label>
                                            <div class="col-9">
                                                <select name="items[]" class="form-control item-ppe" required multiple data-placeholder="Select Items">
                                                    <option value=""></option>
                                                    @for ($k = 0; $k < count($item->item_arr); $k++)
                                                        <option value="{{ $item->item_arr[$k]['id'] }}" selected>{{ $item->item_arr[$k]['text'] }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="col-form-label col-3">Quantity</label>
                                            <div class="col-9">
                                                <input type="number" min="0" value="{{ $item->qty }}" class="form-control" required name="qty">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $item->id }}">
                                        <button type="button" class="btn btn-light-primary btn-sm" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary btn-sm">Add</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="modal fade" id="modalAddPPE" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title">Add Template PPE</h1>
                <button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <form action="{{ route('pref.ppe.add') }}" method="post">
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="" class="col-form-label col-3">Description</label>
                        <div class="col-9">
                            <input type="text" class="form-control" required name="desc">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-form-label col-3">Items</label>
                        <div class="col-9">
                            <select name="items[]" class="form-control item-ppe" required multiple data-placeholder="Select Items">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-form-label col-3">Quantity</label>
                        <div class="col-9">
                            <input type="number" min="0" class="form-control" required name="qty">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    @csrf
                    <button type="button" class="btn btn-light-primary btn-sm" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
