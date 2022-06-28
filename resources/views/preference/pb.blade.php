<div class="card-header py-3">
    <div class="row">
        <div class="card-title align-items-start flex-column col-md-10">
            <h3 class="card-label font-weight-bolder text-dark">Performance Bonus</h3>
        </div>
        <div class="card-toolbar text-right">
        </div>
    </div>

</div>
<div class="row col-md-12 mx-auto m-5">
    <div class="col-md-6">
        <div class="form-group row">
            <label for="" class="col-form-label col-md-4 text-right">Select Month</label>
            <div class="col-md-8">
                <select name="pbmonth" class="form-control select2" id="">
                    @foreach($months as $key => $val)
                        <option value="{{$key}}" {{($key == date('m')) ? "SELECTED" : ""}}>{{$val}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-md-4"></label>
            <label for="" class="col-md-8 text-primary">Bonus diberikan setahun sekali pada bulan yang telah ditentukan</label>
        </div>
        <div class="form-group row">
            <label for="" class="col-md-4"></label>
            <div class="col-md-8">
                <button type="button" class="btn btn-success btn-xs"><i class="fa fa-check"></i> Save</button>
            </div>
        </div>
    </div>
</div>
