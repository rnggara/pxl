<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Payment Confirmation</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<form method="post" action="{{URL::route('business.payConfirm')}}" enctype="multipart/form-data">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-right">Project Name</label>
                    <label for="" class="col-md-7 col-form-label text-right font-weight-bold">{{date('d F Y', strtotime($detail->plan_date))}}</label>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-right">Installment</label>
                    <label for="" class="col-md-7 col-form-label text-right font-weight-bold">{{number_format($detail->cicilan, 2)}}</label>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-right">Profit</label>
                    <label for="" class="col-md-7 col-form-label text-right font-weight-bold">{{number_format($detail->bunga, 2)}}</label>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-right">Sub Total</label>
                    <label for="" class="col-md-7 col-form-label text-right font-weight-bold">{{number_format($detail->cicilan + $detail->bunga, 2)}}</label>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-right">Penalty</label>
                    <div class="col-md-7">
                        <input type="number" class="form-control" placeholder="Penalty" value="{{number_format($detail->penalty_paid, 2)}}" name="penalty" required>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="hidden" name="id" value="{{$detail->id}}">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
            <i class="fa fa-check"></i>
            Update</button>
    </div>
</form>
