@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Add Document</h3><br>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{route('insceo.index')}}" class="btn btn-sm btn-secondary"><i class="fa fa-arrow-left"></i></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="well">
                <table align="left" style="margin-right: 100px">
                    <tr>
                        <td>Insurance</td><td>:</td>
                        <td>{{$ins_detail->nama_asuransi}}</td>
                    </tr>
                    <tr>
                        <td>Policy Number</td><td>:</td>
                        <td>{{$ins_detail->polis}}</td>
                    </tr>
                    <tr>
                        <td>Currency</td><td>:</td>
                        <td>{{$ins_detail->currency}}</td>
                    </tr>
                    <tr>
                        <td>Installment Yearly</td><td>:</td>
                        <td>{{$ins_detail->angsuran}}</td>
                    </tr>
                    <tr>
                        <td>Total Amount</td><td>:</td>
                        <td>{{number_format($ins_detail->jumlah)}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-custom gutter-b">
                <div class="card-body">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                            <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Name</th>
                                <th class="text-center" colspan="2">File</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <form action="{{route('insceo.saveFile')}}" enctype="multipart/form-data" method="POST">
                                        @csrf
                                        <td>
                                            <input type="text" class="form-control" name="file_name">
                                            <input type="hidden" name="id_main" value="{{$ins_detail->id}}">
                                        </td>
                                        <td>
                                            <input type="file" class="form-control" name="file" style="display: inline">
                                        </td>
                                        <td class="text-center">
                                            <button type="submit" class="btn btn-xs btn-success" name="save"><i class="fa fa-save"></i>Save</button>
                                        </td>
                                    </form>
                                </tr>
                                @foreach($files as $key => $val)
                                    <tr>
                                        <td class="text-center">{{($key+1)}}</td>
                                        <td class="text-center">{{$val->nama_file}}</td>
                                        <td class="text-center">
                                            <a href="{{route('download',$val->location)}}" target="_blank" class="btn btn-xs btn-info"><i class="fa fa-download"></i> &nbsp;Download File</a>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{route('insceo.deleteDetail',['id'=>$val->id])}}" class="btn btn-xs btn-danger" onclick="return confirm('Delete File?')"><i class="fa fa-trash"></i></a>
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
@endsection
