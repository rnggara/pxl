@extends('layouts.template')
@section('content')
    <div class="modal fade" id="addEmployee" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Insurance</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('insceo.store')}}" >
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="form col-md-6">
                                <div class="form-group">
                                    <label>Insurance Name</label>
                                    <input type="text" class="form-control" name="ins_name" />
                                </div>
                                <div class="form-group">
                                    <label>Insurance Address</label>
                                    <textarea name="ins_address" class="form-control" cols="30" rows="10"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Insurance Phone</label>
                                    <input type="text" class="form-control" name="ins_phone" />
                                </div>
                                <div class="form-group">
                                    <label>Policy Number</label>
                                    <input type="text" class="form-control" name="pol_num" />
                                </div>

                                <div class="form-group">
                                    <label>Cover Insurance</label>
                                    <textarea name="ins_cover" class="form-control" cols="30" rows="10"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Insured</label>
                                    <input type="text" class="form-control" name="insured" />
                                </div>
                                <div class="form-group">
                                    <label>Currency</label>
                                    <select class="form-control" name = 'curr'>
                                        <option value = 'CNY'>China Yuan</option>
                                        <option value = 'EUR'>Euro</option>
                                        <option value = 'GBP'>Poundsterling</option>
                                        <option value = 'IDR' SELECTED>Indonesia Rupiah</option>
                                        <option value = 'JPY'>Japan Yen</option>
                                        <option value = 'USD'>US Dollar</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Total Amount</label>
                                    <input type="number" class="form-control" name="jml"/>
                                </div>
                                <div class="form-group">
                                    <label>Installment Yearly</label>
                                    <input type="number" class="form-control" name="angsuran"/>
                                </div>
                                <div class="form-group">
                                    <label>Due Date</label>
                                    <input type="date" class="form-control" name="due_date" />
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
                <h3>Insurance RU</h3><br>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addEmployee"><i class="fa fa-plus"></i>Add Insurance</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <table class="table table-bordered table-hover display font-size-sm " style="margin-top: 13px !important; width: 100%;">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-left">Insurance Profile</th>
                        <th class="text-center">Insurance Policy</th>
                        <th class="text-center">Insurance Cover</th>
                        <th class="text-center">Currency</th>
                        <th class="text-center">Installment Yearly</th>
                        <th class="text-right">Total Amount</th>
                        <th class="text-center">Due Date</th>
                        <th class="text-center">Insured</th>
                        <th class="text-center">Doc. File</th>
                        <th class="text-center"></th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($ins_ru as $key => $value)
                            <tr>
                                <td class="text-center">{{($key+1)}}</td>
                                <td class="text-left">
                                    <i class="fa fa-search"></i>
                                    {{$value->nama_asuransi}}
                                    <br>
                                    <i>{{$value->phone_asuransi}}</i>
                                </td>
                                <td class="text-center">{{$value->polis}}</td>
                                <td class="text-center">{{$value->cover_ins}}</td>
                                <td class="text-center">{{$value->currency}}</td>
                                <td class="text-center">{{$value->angsuran}}</td>
                                <td class="text-right">{{number_format($value->jumlah,2)}}</td>
                                <td class="text-center">{{date('D d-m-Y',strtotime($value->due_date))}}</td>
                                <td class="text-center">{{$value->insured}}</td>
                                <td class="text-center">
                                    <a href="{{route('insceo.detail',['id'=>$value->id])}}" class="btn btn-xs btn-primary"><i class="fa fa-upload"></i>&nbsp;Upload</a>
                                </td>
                                <td>
                                    <div class="modal fade" id="addEmployee{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="addEmployee{{$value->id}}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Edit Insurance</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <i aria-hidden="true" class="ki ki-close"></i>
                                                    </button>
                                                </div>
                                                <form method="post" action="{{route('insceo.store')}}" >
                                                    @csrf
                                                    <input type="hidden" name="edit" value="{{$value->id}}">
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="form col-md-6">
                                                                <div class="form-group">
                                                                    <label>Insurance Name</label>
                                                                    <input type="text" class="form-control" value="{{$value->nama_asuransi}}" name="ins_name" />
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Insurance Address</label>
                                                                    <textarea name="ins_address" class="form-control" cols="30" rows="10">{{$value->alamat_asuransi}}</textarea>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Insurance Phone</label>
                                                                    <input type="text" class="form-control" name="ins_phone" value="{{$value->phone_asuransi}}"/>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Policy Number</label>
                                                                    <input type="text" class="form-control" name="pol_num" value="{{$value->polis}}"/>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label>Cover Insurance</label>
                                                                    <textarea name="ins_cover" class="form-control" cols="30" rows="10">{{$value->cover_ins}}</textarea>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Insured</label>
                                                                    <input type="text" class="form-control" name="insured" value="{{$value->insured}}"/>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Currency</label>
                                                                    <select class="form-control" name = 'curr'>
                                                                        <option value = 'CNY' @if($value->currency == 'CNY') SELECTED @endif>China Yuan</option>
                                                                        <option value = 'EUR' @if($value->currency == 'EUR') SELECTED @endif>Euro</option>
                                                                        <option value = 'GBP' @if($value->currency == 'GBP') SELECTED @endif>Poundsterling</option>
                                                                        <option value = 'IDR' @if($value->currency == 'IDR') SELECTED @endif>Indonesia Rupiah</option>
                                                                        <option value = 'JPY' @if($value->currency == 'JPY') SELECTED @endif>Japan Yen</option>
                                                                        <option value = 'USD' @if($value->currency == 'USD') SELECTED @endif>US Dollar</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Total Amount</label>
                                                                    <input type="number" class="form-control" name="jml" value="{{$value->jumlah}}"/>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Installment Yearly</label>
                                                                    <input type="number" class="form-control" name="angsuran" value="{{$value->angsuran}}"/>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Due Date</label>
                                                                    <input type="date" class="form-control" name="due_date" value="{{date('Y-m-d', strtotime($value->due_date))}}"/>
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
                                    <button type="button" class="btn btn-success btn-xs btn-icon" data-toggle="modal" data-target="#addEmployee{{$value->id}}"><i class="fa fa-edit"></i></button>
                                    &nbsp;<a onclick="return confirm('Are you sure?')" href="{{route('insceo.delete',['id'=>$value->id])}}" class="btn btn-danger btn-xs btn-icon"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
