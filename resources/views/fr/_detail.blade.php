<div class="modal-header">
    <h3 class="modal-title">Detail {{ $item->name }}</h3>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-12">
            <table class="table table-bordered table-hover display">
                <thead>
                    <th class="text-center">#</th>
                    <th class="text-center">Storage</th>
                    <th class="text-center">Qty</th>
                </thead>
                <tbody>
                    @foreach ($qtyWh as $i => $item)
                        @if (isset($wh[$item->wh_id]))
                            <tr>
                                <td class="text-center">{{ $i+1 }}</td>
                                <td class="text-center">{{ $wh[$item->wh_id] }}</td>
                                <td class="text-center">{{ $item->qty }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-light-primary" type="button" data-dismiss="modal">
        Close
    </button>
</div>
