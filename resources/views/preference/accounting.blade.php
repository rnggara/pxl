<div class="card-header">
    <h3 class="card-title">Accounting</h3>
</div>
<div class="card-body">
    <div class="row">
        <div class="col-8 mx-auto">
            <form action="{{ route('pref.accounting.save') }}" method="post">
                @csrf
                <div class="form-group row">
                    <label for="" class="col-form-label col-3">Transaction Code Name</label>
                    <div class="col-9">
                        <input type="text" class="form-control" name="t_name" value="{{ $preferences->transaction_name }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-form-label col-3">Transaction Code Initial</label>
                    <div class="col-9">
                        <input type="text" class="form-control" name="t_initial" value="{{ $preferences->transaction_initial }}" maxlength="4" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-form-label col-3"></label>
                    <div class="col-9">
                        <input type="hidden" name="id_comp" value="{{ $preferences->id_company }}">
                        <button type="submit" class="btn btn-primary" onclick="_post()">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
