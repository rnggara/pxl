<div class="form-group row form-desc">
    <div class="col-md-3 col-sm-12">
        <label for="" class="col-form-label">From</label>
        <input type="time" name="activity_from[]" value="{{ $last }}" class="form-control desc-from required">
    </div>
    <div class="col-md-3 col-sm-12">
        <label for="" class="col-form-label">To</label>
        <input type="time" name="activity_to[]" class="form-control desc-to required">
    </div>
    <div class="col-md-6 col-sm-12">
        <label for="" class="col-form-label">Description</label>
        <textarea name="description[]" id="" class="form-control tmce" cols="30" rows="10"></textarea>
    </div>
</div>
