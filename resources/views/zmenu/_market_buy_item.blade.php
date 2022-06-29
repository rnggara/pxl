<div class="d-flex flex-column">
    <div class="d-flex flex-column py-10">
        <h3>Which item you want to buy?</h3>
    </div>
    <table class="table table-bordered table-hover table-dark">
        <thead>
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Buy</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $i => $item)
                <tr>
                    <td>{{ "Item $i" }} @ {{ number_format($item) }} C</td>
                    <td>
                        <input type="number" class="form-control" value="0" min="0">
                    </td>
                    <td align="center">
                        <button type="button" class="btn btn-sm btn-primary">Buy!</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-between mt-3">
        <button type="button" onclick="_menu('town')" class="btn btn-primary py-10 mr-3 w-50">Go to Town</button>
        <button type="button" onclick="_menu('market')" class="btn btn-warning py-10 w-50">Cancel</button>
    </div>
</div>
