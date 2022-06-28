<div class="card-header py-3">
    <div class="row">
        <div class="card-title align-items-start flex-column col-md-10">
            <h3 class="card-label font-weight-bolder text-dark">THR Period</h3>
        </div>
        <div class="card-toolbar text-right">
        </div>
    </div>

</div>
<div class="row col-md-12 mx-auto m-5">
    <div class="col-md-6">
        <form action="{{ route('pref.thr') }}" method="post">
            @csrf
            <div class="form-group row">
                <label for="" class="col-form-label col-md-4 text-right">Select Period</label>
                <div class="col-md-8">
                    <textarea name="thr_period" id="" cols="30" rows="10" class="form-control">{{ $preferences->thr_period }}</textarea>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-md-4"></label>
                <label for="" class="col-md-8 text-primary">THR diberikan setahun sekali. Masukan periode dalam format bulan dan tahun [MM-YYYY] di berikannya THR. Boleh diisi lebih dari 1 periode, satu periode, satu baris.</label>
            </div>
            <div class="form-group row">
                <label for="" class="col-md-4"></label>
                <div class="col-md-8">
                    <input type="hidden" name="id" value="{{ $preferences->id }}">
                    <button type="submit" class="btn btn-success btn-xs"><i class="fa fa-check"></i> Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
