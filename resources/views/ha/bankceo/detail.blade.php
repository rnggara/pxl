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
                                    @php
                                        /** @var TYPE_NAME $bank_name */
                                        if ($bank_name == 'citi'){
                                            $name = 'Citibank';
                                        } else{
                                            $name = strtoupper($bank_name);
                                        }
                                    @endphp
                                    <label>Bank Name</label>
                                    @if($bank_name != "")
                                        <input type="text" class="form-control" name="" id="" value="{{$name}}" readonly/>
                                        <input type="hidden" class="form-control" name="bank_name" id="bank_name" value="{{$bank_name}}" readonly/>
                                    @else
                                        <select name="bank_name" id="bank_name" class="form-control" required>
                                            <option value="">- Pilih Bank -</option>
                                            <option value="bca">BCA</option>
                                            <option value="bri">BRI</option>
                                            <option value="mandiri">Mandiri</option>
                                            <option value="citi">Citibank</option>
                                            <option value="hsbc">HSBC</option>
                                        </select>
                                    @endif
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
                    &nbsp;&nbsp;
                    <a href="{{route('bankceo.index')}}" class="btn btn-success"><i class="fa fa-arrow-left"></i></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form method="post" action="{{route('bankceo.filter',['bank' => base64_encode($bank_name)])}}">
                @csrf
                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-right">Filter :</label>
                    <div class="col-md-3">
                        <select name="bank" id="bank" class="form-control">
                            <option value="">All</option>
                            <option value="bca" <?php /** @var TYPE_NAME $bank_name */
                            if($bank_name== 'bca') : ?> selected <?php endif; ?>>BCA</option>
                            <option value="bri" <?php if($bank_name== 'bri') : ?> selected <?php endif; ?>>BRI</option>
                            <option value="citi" <?php if($bank_name== 'citi') : ?> selected <?php endif; ?>>Citibank</option>
                            <option value="hsbc" <?php if($bank_name== 'hsbc') : ?> selected <?php endif; ?>>HSBC</option>
                            <option value="mandiri" <?php if($bank_name== 'mandiri') : ?> selected <?php endif; ?>>Mandiri</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" name="curr" id="curr">
                            <option value="">All</option>
                            <option value="idr" <?php if((isset($curr)) && ($curr == 'idr') && ($curr!= null)) : ?> selected <?php endif; ?>>IDR</option>
                            <option value="usd" <?php if((isset($curr)) && ($curr == 'usd') && ($curr!= null)) : ?> selected <?php endif; ?>>USD</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" name="show" class="btn btn-primary"><i class="fa fa-search"></i> Show</button>
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="col-md-12">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <table class="table table-bordered table-hover display font-size-sm " style="margin-top: 13px !important; width: 100%;">
                            <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-left">Description</th>
                                <th class="text-center">Transcaction Date</th>
                                <th class="text-center">Expired Date</th>
                                <th class="text-center">Currency</th>
                                <th class="text-right">Total Amount</th>
                                <th class="text-center">PIC</th>
                                <th class="text-center">PIC Phone</th>
                                <th class="text-center"></th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($transaction_list as $key => $value)
                                    <tr>
                                        <td class="text-center">{{($key+1)}}</td>
                                        <td class="text-left">{{$value->keterangan}}</td>
                                        <td class="text-center">{{date('D d-m-Y',strtotime($value->tgl_trans))}}</td>
                                        <td class="text-center">{{date('D d-m-Y',strtotime($value->tgl_exp))}}</td>
                                        <td class="text-center">{{strtoupper($value->currency)}}</td>
                                        <td class="text-right">{{number_format($value->jumlah,2)}}</td>
                                        <td class="text-center">{{$value->pic}}</td>
                                        <td class="text-center">{{$value->telp_pic}}</td>
                                        <td class="pl-20">
                                            <button type="button" class="btn btn-sm btn-icon btn-primary" data-toggle="modal" data-target="#edit{{$value->id}}"><i class="fa fa-edit"></i></button>
                                            <div class="modal fade" id="edit{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="edit{{$value->id}}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Edit Transaction</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <i aria-hidden="true" class="ki ki-close"></i>
                                                            </button>
                                                        </div>
                                                        <form method="post" action="{{route('bankceo.addTrans')}}" >
                                                            @csrf
                                                            <input type="hidden" name="edit" value="{{$value->id}}">
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="form col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="text-left">Bank Name</label>
                                                                            <select name="bank_name" id="bank_name{{$value->id}}" class="form-control" required>
                                                                                <option value="">- Pilih Bank -</option>
                                                                                <option value="bca" @if($value->nama_bank == 'bca') selected @endif>BCA</option>
                                                                                <option value="bri" @if($value->nama_bank == 'bri') selected @endif>BRI</option>
                                                                                <option value="mandiri" @if($value->nama_bank == 'mandiri') selected @endif>Mandiri</option>
                                                                                <option value="citi" @if($value->nama_bank == 'citi') selected @endif>Citibank</option>
                                                                                <option value="hsbc" @if($value->nama_bank == 'hsbc') selected @endif>HSBC</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Transaction Date</label>
                                                                            <input type="date" class="form-control" value="{{date('Y-m-d', strtotime($value->tgl_trans))}}" name="trans_date" />
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Expired Date</label>
                                                                            <input type="date" class="form-control" name="exp_date" value="{{date('Y-m-d', strtotime($value->tgl_exp))}}" />
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Currency</label>
                                                                            <select name="currency" id="currency{{$value->id}}" class="form-control">
                                                                                <option value="">- Pilih Mata Uang -</option>
                                                                                <option value="idr" @if($value->currency == 'idr') selected @endif>IDR</option>
                                                                                <option value="usd" @if($value->currency == 'usd') selected @endif>USD</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Total Amount</label>
                                                                            <input type="text" class="form-control" value="{{$value->jumlah}}" name="amount" id="amount{{$value->id}}" placeholder="ex: 106000.00"/>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>PIC</label>
                                                                            <input type="text" class="form-control" value="{{$value->pic}}" name="pic" id="pic{{$value->id}}" />
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>PIC Number</label>
                                                                            <input type="text" class="form-control" value="{{$value->telp_pic}}" name="pic_number" id="pic_number{{$value->id}}" />
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Description</label>
                                                                            <textarea name="desc" id="desc{{$value->id}}" class="form-control" cols="30" rows="10" placeholder="Write here..">{{$value->keterangan}}</textarea>
                                                                        </div>
                                                                    </div>
                                                                    <script type="text/javascript">
                                                                        $(document).ready(function () {
                                                                            setInputFilter(document.getElementById("amount{{$value->id}}"), function(value) {
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
                                            &nbsp;&nbsp;
                                            <a href="{{route('bankceo.delete',['id' => $value->id])}}" class="btn btn-sm btn-icon btn-danger" onclick="return confirm('Do you want to delete this data?')"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.display').DataTable({
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            });
        })
    </script>
@endsection
