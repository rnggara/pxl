<form action="{{ route('hrd.contract.generate') }}" id="form-sign" method="POST" enctype="multipart/form-data">
<div class="modal-header">
    <h1 class="modal-title">Fields</h1>
    <button class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-12">
            <div class="form-group row">
                <h3 class="col-form-label col-3">Template Name</h3>
                <div class="col-9">
                    <select id="template-id" name="id_template" class="form-control select2" data-placeholder="Select Template">
                        <option value=""></option>
                        @foreach ($tp as $item)
                            <option value="{{ $item->id }}"
                                >{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <h3 class="col-form-label col-3">Nama Karyawan</h3>
                <div class="col-9">
                    <select id="emp-name" class="form-control select2" data-placeholder="Select Employee">
                        <option value=""></option>
                        @foreach ($emp as $item)
                            <option value="{{ $item->id }}"
                                >{{ $item->emp_name }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="emp_name" id="emp-id">
                </div>
            </div>
            <div class="form-group row">
                <h3 class="col-form-label col-3">NIK</h3>
                <div class="col-9">
                    <input type="text" class="form-control" name="nik" required>
                </div>
            </div>
            <div class="form-group row">
                <h3 class="col-form-label col-3">Alamat</h3>
                <div class="col-9">
                    <textarea name="address" required class="form-control" cols="30" rows="10"></textarea>
                </div>
            </div>
            <div class="form-group row">
                <h3 class="col-form-label col-3">Jenis Kelamin</h3>
                <div class="col-9">
                    <select name="jk" required id="jk" class="form-control select2" data-placeholder="Select Gender">
                        <option value="M">Laki - laki</option>
                        <option value="F">Perempuan</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <h3 class="col-form-label col-3">Tempat Lahir</h3>
                <div class="col-9">
                    <input type="text" name="tempat_lahir" id="tmpt" class="form-control" required>
                </div>
            </div>
            <div class="form-group row">
                <h3 class="col-form-label col-3">Tanggal Lahir</h3>
                <div class="col-9">
                    <input type="date" name="tanggal_lahir" id="tgl" class="form-control" required>
                </div>
            </div>
            <div id="mdl-content"></div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="hidden" name="signature" id="sign-url">
    @csrf
    <button type="submit" class="btn btn-sm btn-light-primary" data-dismiss="modal"> Close</button>
    <button type="submit" name="submit_sign" id="btn-generate" class="btn btn-sm btn-primary"><i class="fa fa-check"></i> Generate</button>
</div>
</form>
