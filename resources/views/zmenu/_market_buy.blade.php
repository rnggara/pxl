<div class="d-flex flex-column">
    @if (empty($xenolot_id))
    <div class="d-flex flex-column py-10">
        <h3>Which Xenolot you want to buy? (5.000 G)</h3>
    </div>
    <div class="d-flex justify-content-between mt-3">
        <button type="button" class="btn btn-primary py-10 mr-3 w-50">Variant A</button>
        <button type="button" class="btn btn-primary py-10 w-50">Variant B</button>
    </div>
    <div class="d-flex justify-content-between mt-3">
        <button type="button" class="btn btn-primary py-10 mr-3 w-50">Variant C</button>
        <button type="button" onclick="_menu('market')" class="btn btn-warning py-10 w-50">Back to Market</button>
    </div>
    @else
    <div class="d-flex flex-column py-10">
        <h3>You already have a Xenolot</h3>
    </div>
    <div class="d-flex justify-content-between mt-3">
        <button type="button" onclick="_menu('market')" class="btn btn-primary py-10 mr-3 w-50">Back to Market</button>
        <button type="button" onclick="_menu('laboratory')" class="btn btn-warning py-10 w-50">Go to Laboratory</button>
    </div>
    @endif
</div>
