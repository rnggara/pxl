<div class="d-flex flex-column">
    <table class="table table-bordered table-hover table-dark display">
        <thead>
            <tr>
                <th>Item</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($myXenolot as $i => $item)
                <tr>
                    <td>
                        <a href="" class="text-white text-hover-primary">XL {{ "Item $i" }}</a>
                    </td>
                    <td align="center">
                        <button type="button" class="btn btn-{{ ($item == 1) ? "warning" : "info" }}">
                            {{ ($item == 1) ? "Active" : "Frozen" }}
                        </button>
                    </td>
                    <td align="center">
                        <button type="button" class="btn btn-{{ ($item == 1) ? "primary" : "danger" }}">
                            {{ ($item == 1) ? "Freeze" : "Sell" }}
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-between mt-3">
        <button type="button" onclick="_menu('town')" class="btn btn-primary py-10 mr-3 w-50">Back to Town</button>
        <button type="button" onclick="_menu('laboratory')" class="btn btn-warning py-10 mr-3 w-50">Back to Laboratory</button>
    </div>
</div>
