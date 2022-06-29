<div class="d-flex flex-column mt-10">
    <h3>Select a Xenolot (Parent A)</h3>
    <table class="table table-bordered table-hover table-dark display">
        <thead>
            <tr>
                <th>Item</th>
                <th>Age</th>
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
                        {{ rand(0,5)." yr, ".rand(0,12)." mo, ".rand(0,4)." wk" }}
                    </td>
                    <td align="center">
                        <button type="button" class="btn btn-primary">
                            Select
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
