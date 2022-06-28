<div class="card-header py-3">
    <div class="row">
        <div class="card-title align-items-start flex-column col-md-10">
            <h3 class="card-label font-weight-bolder text-dark">Performa Review</h3>
        </div>
        <div class="card-toolbar text-right">

        </div>
    </div>

</div>
<div class="row col-md-12 mx-auto m-5">
    <form action="{{route('pref.store_pr')}}" method="post">
        @csrf
        <div class="col-md-12">
            <div class="form-group row">
                <label for="" class="col-form-label col-md-4 text-right">Payroll Update Month</label>
                <div class="col-md-8">
                    <select name="performa_period" class="form-control select2" id="">
                        @foreach($months as $key => $val)
                            <option value="{{$key}}" {{($preferences != null && $preferences->performa_period == $key) ? "SELECTED" : ""}}>{{$val}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-md-4"></label>
                <label for="" class="col-md-8 text-primary">Bulan yang dipilih diatas akan menentukan kenaikan gaji tahun berjalan.</label>
            </div>
            <div class="form-group row">
                <label for="" class="col-form-label col-md-4 text-right">Review Start Date</label>
                <div class="col-md-8">
                    <select name="performa_start" class="form-control select2" id="">
                        @foreach($months as $key => $val)
                            <option value="{{$key}}" {{($preferences != null && $preferences->performa_start == $key) ? "SELECTED" : ""}}>{{$val}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-md-4"></label>
                <label for="" class="col-md-8 text-primary">Performa Review akan dimulai pada bulan yang dipilih.</label>
            </div>
            <div class="form-group row">
                <label for="" class="col-form-label col-md-4 text-right">Review End Date</label>
                <div class="col-md-8">
                    <select name="performa_end" class="form-control select2" id="">
                        @foreach($months as $key => $val)
                            <option value="{{$key}}" {{($preferences != null && $preferences->performa_end == $key) ? "SELECTED" : ""}}>{{$val}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-md-4"></label>
                <label for="" class="col-md-8 text-primary">Performa Review akan berakhir pada bulan yang dipilih.</label>
            </div>
            <div class="form-group row">
                <label for="" class="col-form-label col-md-4 text-right">Persentasi kenaikan untuk UNACCEPTABLE (1) (%)</label>
                <div class="col-md-8">
                    <input type="number" class="form-control" value="{{$preferences->performa_amt1}}" name="performa_amt1">
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-form-label col-md-4 text-right">Persentasi kenaikan untuk NEED IMPROVEMENT (2) (%)</label>
                <div class="col-md-8">
                    <input type="number" class="form-control" value="{{$preferences->performa_amt2}}" name="performa_amt2">
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-form-label col-md-4 text-right">Persentasi kenaikan untuk SATISFACTORY (3) (%)</label>
                <div class="col-md-8">
                    <input type="number" class="form-control" value="{{$preferences->performa_amt3}}" name="performa_amt3">
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-form-label col-md-4 text-right">Persentasi kenaikan untuk MORE THAN SATISFACTORY (4) (%)</label>
                <div class="col-md-8">
                    <input type="number" class="form-control" value="{{$preferences->performa_amt4}}" name="performa_amt4">
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-form-label col-md-4 text-right">Persentasi kenaikan untuk EXCELLENT (5) (%)</label>
                <div class="col-md-8">
                    <input type="number" class="form-control" value="{{$preferences->performa_amt5}}" name="performa_amt5">
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-md-4"></label>
                <input type="hidden" name="id" value="{{$company->id}}">
                <div class="col-md-8">
                    <button type="submit" class="btn btn-success btn-xs"><i class="fa fa-check"></i> Save</button>
                </div>
            </div>
        </div>
    </form>
</div>
