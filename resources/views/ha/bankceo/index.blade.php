@extends('layouts.template')
@section('content')
    <div class="modal fade" id="addEmployee" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Transaction</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('bankceo.addTrans')}}" >
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="form col-md-6">
                                <div class="form-group">
                                    <label>Bank Name</label>
                                    <select name="bank_name" id="bank_name" class="form-control" required>
                                        <option value="">- Pilih Bank -</option>
                                        <option value="bca">BCA</option>
                                        <option value="bri">BRI</option>
                                        <option value="mandiri">Mandiri</option>
                                        <option value="citi">Citibank</option>
                                        <option value="hsbc">HSBC</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Transaction Date</label>
                                    <input type="date" class="form-control" name="trans_date" />
                                </div>
                                <div class="form-group">
                                    <label>Expired Date</label>
                                    <input type="date" class="form-control" name="exp_date" />
                                </div>
                                <div class="form-group">
                                    <label>Currency</label>
                                    <select name="currency" id="currency" class="form-control">
                                        <option value="">- Pilih Mata Uang -</option>
                                        <option value="idr">IDR</option>
                                        <option value="usd">USD</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Total Amount</label>
                                    <input type="text" class="form-control" name="amount" id="amount" placeholder="ex: 106000.00"/>
                                </div>
                                <div class="form-group">
                                    <label>PIC</label>
                                    <input type="text" class="form-control" name="pic" id="pic" />
                                </div>
                                <div class="form-group">
                                    <label>PIC Number</label>
                                    <input type="text" class="form-control" name="pic_number" id="pic_number" />
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="desc" id="desc" class="form-control" cols="30" rows="10" placeholder="Write here.."></textarea>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Bank RU</h3><br>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addEmployee"><i class="fa fa-plus"></i>Add Transaction</button>
                </div>
            </div>

        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-3">
                    <a href="{{route('bankceo.detail',['bank' =>base64_encode('bca')])}}" class="img-thumbnail">
                        <img src="{{asset('theme/assets/media/bca.jpg')}}" alt="...">
                        <table class="table">
                            <thead>
                            <tr>
                                <th colspan="3" class="text-center"><b>BALANCE</b></th>
                            </tr>
                            </thead>
                            <tr>
                                <td>IDR</td>
                                <td>:</td>
                                <td class="text-right"><?= /** @var TYPE_NAME $totalBCAIDR */
                                    number_format($totalBCAIDR, 2) ?></td>
                            </tr>
                            <tr>
                                <td>USD</td>
                                <td>:</td>
                                <td class="text-right"><?= /** @var TYPE_NAME $totalBCAUSD */
                                    number_format($totalBCAUSD, 2) ?></td>
                            </tr>

                        </table>
                    </a>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3">
                    <a href="{{route('bankceo.detail',['bank' =>base64_encode('bri')])}}" class="img-thumbnail">
                        <img src="{{asset('theme/assets/media/bri.jpg')}}" alt="...">
                        <table class="table">
                            <thead>
                            <tr>
                                <th colspan="3" class="text-center"><b>BALANCE</b></th>
                            </tr>
                            </thead>
                            <tr>
                                <td>IDR</td>
                                <td>:</td>
                                <td class="text-right"><?= /** @var TYPE_NAME $totalBRIIDR */
                                    number_format($totalBRIIDR, 2) ?></td>
                            </tr>
                            <tr>
                                <td>USD</td>
                                <td>:</td>
                                <td class="text-right"><?= /** @var TYPE_NAME $totalBRIUSD */
                                    number_format($totalBRIUSD, 2) ?></td>
                            </tr>

                        </table>
                    </a>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3">
                    <a href="{{route('bankceo.detail',['bank' =>base64_encode('citi')])}}" class="img-thumbnail">
                        <img src="{{asset('theme/assets/media/citibank.jpg')}}" alt="...">
                        <table class="table">
                            <thead>
                            <tr>
                                <th colspan="3" class="text-center"><b>BALANCE</b></th>
                            </tr>
                            </thead>
                            <tr>
                                <td>IDR</td>
                                <td>:</td>
                                <td class="text-right"><?= /** @var TYPE_NAME $totalCitiIDR */
                                    number_format($totalCitiIDR, 2) ?></td>
                            </tr>
                            <tr>
                                <td>USD</td>
                                <td>:</td>
                                <td class="text-right"><?= /** @var TYPE_NAME $totalCitiUSD */
                                    number_format($totalCitiUSD, 2) ?></td>
                            </tr>

                        </table>
                    </a>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3">
                    <a href="{{route('bankceo.detail',['bank' =>base64_encode('hsbc')])}}" class="img-thumbnail">
                        <img src="{{asset('theme/assets/media/hsbc.jpg')}}" alt="...">
                        <table class="table">
                            <thead>
                            <tr>
                                <th colspan="3" class="text-center"><b>BALANCE</b></th>
                            </tr>
                            </thead>
                            <tr>
                                <td>IDR</td>
                                <td>:</td>
                                <td class="text-right"><?= /** @var TYPE_NAME $totalHSBCIDR */
                                    number_format($totalHSBCIDR, 2) ?></td>
                            </tr>
                            <tr>
                                <td>USD</td>
                                <td>:</td>
                                <td class="text-right"><?= /** @var TYPE_NAME $totalHSBCUSD */
                                    number_format($totalHSBCUSD, 2) ?></td>
                            </tr>

                        </table>
                    </a>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3">
                    <a href="{{route('bankceo.detail',['bank' => base64_encode('mandiri')])}}" class="img-thumbnail">
                        <img src="{{asset('theme/assets/media/mandiri.jpg')}}" alt="...">
                        <table class="table">
                            <thead>
                            <tr>
                                <th colspan="3" class="text-center"><b>BALANCE</b></th>
                            </tr>
                            </thead>
                            <tr>
                                <td>IDR</td>
                                <td>:</td>
                                <td class="text-right"><?= /** @var TYPE_NAME $totalMandiriIDR */
                                    number_format($totalMandiriIDR, 2) ?></td>
                            </tr>
                            <tr>
                                <td>USD</td>
                                <td>:</td>
                                <td class="text-right"><?= /** @var TYPE_NAME $totalMandiriUSD */
                                    number_format($totalMandiriUSD, 2) ?></td>
                            </tr>

                        </table>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script type="text/javascript">
        $(document).ready(function () {
            setInputFilter(document.getElementById("amount"), function(value) {
                return /^-?\d*[.,]?\d*$/.test(value); });
        })
        function setInputFilter(textbox, inputFilter) {
            ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function(event) {
                textbox.addEventListener(event, function() {
                    if (inputFilter(this.value)) {
                        this.oldValue = this.value;
                        this.oldSelectionStart = this.selectionStart;
                        this.oldSelectionEnd = this.selectionEnd;
                    } else if (this.hasOwnProperty("oldValue")) {
                        this.value = this.oldValue;
                        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
                    } else {
                        this.value = "";
                    }
                });
            });
        }
    </script>
@endsection
