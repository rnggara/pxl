<form action="{{ route('items.assembly.approve') }}" method="post">
    @csrf
    <div class="modal-header">
        <h1 class="modal-title">Approve Item Assembly</h1>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-12">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Item Name</th>
                            <th class="text-center">Type</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-center">Storage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($_list as $i => $item)
                            <tr>
                                <td align="center">{{ $i+1 }}</td>
                                <td>{{ $item->item_name }}</td>
                                <td align="center">{{ $item->type }}</td>
                                <td align="center">{{ $item->qty }}</td>
                                <td align="center">
                                    <input type="hidden" name="type[{{ $item->item_id }}]" value="{{ $item->type }}">
                                    <select name="_storage[{{ $item->item_id }}]" class="form-control select2 form-approve" id="" data-placeholder="Select Storage">
                                        <option value=""></option>
                                        @foreach ($item->wh as $idWh => $wh)
                                            <option value="{{ $idWh }}">{{ $wh }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="hidden" name="item_id" value="{{ $item_id }}">
        <button type="button" class="btn btn-light-primary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="btn-approve">Approve</button>
    </div>
</form>
