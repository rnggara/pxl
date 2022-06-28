@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Project <b>{{$project->prj_name}}</b></h3>
            </div>
        </div>
        <div class="card-body">
            <div class="col-md-12">
                <table class="table table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;" border="0">
                    <thead>
                    <tr>
                        <th class="text-left" colspan="3">
                            <h4 class="text-primary">
                                SUBCOST BUDGET PROJECT
                            </h4>
                        </th>
                        <th class="text-right" colspan="4">
                            <h4 class="text-success">
                                @if($project->view_subcost==null)
                                    <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#addCashIn"><i class="fa fa-plus"></i></button>
                                @endif
                            </h4>
                        </th>
                    </tr>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Date</th>
                        <th class="text-center text-block">Ref. Number #</th>
                        <th class="text-left">Description</th>
                        <th class="text-right">IDR Amount</th>
                        <th class="text-right">USD Amount</th>
                        <th class="text-center"></th>
                    </tr>
                    </thead>
                    @php
                        $cashins = 0.0;
                        $cashinsD = 0.0;
                    @endphp
                    @if($numRowsIn > 0)
                        @foreach($cashin as $key => $val)
                            <tr>
                                <td class="text-center">{{$key+1}}</td>
                                <td class="text-center">{{date('d-m-Y',strtotime($val->tanggal))}}</td>
                                <td class="text-center">{{$val->no_nota}}</td>
                                <td class="text-left">{{"[".$val->source_string."] ".$val->deskripsi}}</td>
                                <td class="text-right">@if($val->currency == 'IDR'){{number_format($val->cashin,2)}} @else 0.00 @endif</td>
                                <td class="text-right">@if($val->currency == 'USD'){{number_format($val->cashin,2)}} @else 0.00 @endif</td>
                                <td class="text-right text-block">
{{--                                    @if($detail->m_approve==null)--}}
                                        <button type="button" class="btn btn-default btn-xs	btn-primary" data-toggle="modal" data-target="#editCashIn{{$val->id}}"><i class="fa fa-edit"></i></button>
                                        <a href="{{route('subcost.delete.detail',['id' => $val->id_subcost,'id_detail' => $val->id])}}" class="btn btn-danger btn-xs btn-PRIMARY" title="Delete" onclick="return confirm('Are you sure you want to delete ?')"><i class="fa fa-trash"></i></a>
{{--                                    @endif--}}
                                </td>
                                <div class="modal fade" id="editCashIn{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Edit Cash In</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <i aria-hidden="true" class="ki ki-close"></i>
                                                </button>
                                            </div>
                                            <form method="post" action="{{route('subcost.addCash')}}" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="prj_id" id="prj_id" value="{{$project->id}}">
                                                <input type="hidden" name="id_edit" id="id_edit" value="{{$val->id}}">
                                                <input type="hidden" name="cashtype" id="" value="cashin">
                                                <div class="modal-body">
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group row" id="opt">
                                                                <label class="col-md-2 col-form-label text-right">Source</label>
                                                                <div class="col-md-6">
                                                                    <select name="source" id="source" class="form-control">
                                                                        <option></option>
                                                                        <option value="BR" @if($val->source_string == 'BR') SELECTED @endif>BR</option>
                                                                        <option value="oo" @if($val->source_string == 'oo') SELECTED @endif>--</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row" id="opt">
                                                                <label class="col-md-2 col-form-label text-right">Currency</label>
                                                                <div class="col-md-6">
                                                                    <select name="currency" id="currency" class="form-control">
                                                                        <option></option>
                                                                        <option value="IDR" @if($val->currency == 'IDR') SELECTED @endif>IDR</option>
                                                                        <option value="USD" @if($val->currency == 'USD') SELECTED @endif>USD</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-md-2 col-form-label text-right">Date</label>
                                                                <div class="col-md-6">
                                                                    <input type="date" name="req_date" id="req_date" value="{{$val->tanggal}}" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-md-2 col-form-label text-right">Subject</label>
                                                                <div class="col-md-5">
                                                                    <input type="text" class="form-control" name="subject" value="{{$val->no_nota}}" required>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <p style="color: red">*Wajib diisi</p>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-md-2 col-form-label text-right">Project</label>
                                                                <div class="col-md-6">
                                                                    <input type="text" name="prj_name" readonly value="{{$project->prj_name}}" id="prj_name" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-md-2 col-form-label text-right">Deliver To</label>
                                                                <div class="col-md-6">
                                                                    <input type="text" name="deliverto" id="deliverto" value="{{$val->receiver}}" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-md-2 col-form-label text-right">Deliver By</label>
                                                                <div class="col-md-6">
                                                                    <input type="text" name="deliverby" id="deliverby" value="{{$val->deliver}}" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-md-2 col-form-label text-right">Amount</label>
                                                                <div class="col-md-6">
                                                                    <input type="number" class="form-control" value="{{$val->cashin}}" name="amount">
                                                                </div>

                                                            </div>

                                                            <div class="form-group row" >
                                                                <label class="col-md-2 col-form-label text-right">Description</label>
                                                                <div class="col-md-6" style="margin: 9px 0 0 0;">
                                                                    <textarea name="deskripsi" class="form-control" id="deskripsi" size="50">{{$val->deskripsi}}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                                                    <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                                                        <i class="fa fa-check"></i>
                                                        Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </tr>
                            @php
                                if ($val->currency == 'IDR'){
                                    $cashins += intval($val->cashin);
                                } else {
                                    $cashinsD += intval($val->cashin);
                                }
                            @endphp
                        @endforeach
                    @else
                        <tr><td colspan='6'>No record found.</td></tr>
                    @endif
                    <tr>
                        <td colspan="2"></td>
                        <td colspan="2" class="text-right"><b>TOTAL</b></td>
                        <td class="text-right"><b>IDR. {{number_format($cashins,2)}}</b></td>
                        <td class="text-right"><b>USD. {{number_format($cashinsD,2)}}</b></td>
                        <td></td>
                    </tr>
                </table>
                <table class="table table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;" border="0">
                    <thead>
                    <tr>
                        <th class="text-left" colspan="5">
                            <h4 class="text-primary">
                                SUB COST OUT
                            </h4>
                        </th>
                        <th class="text-right" colspan="5">
                            <h4 class="text-success">
                                @if($project->view_subcost==null)
                                    <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#addCashOut"><i class="fa fa-plus"></i></button>
                                @endif
                            </h4>
                        </th>
                    </tr>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Date</th>
                        <th class="text-center text-block">Ref. Number #</th>
                        <th class="text-left">Description</th>
                        <th class="text-right">IDR Amount</th>
                        <th class="text-right">USD Amount</th>
                        <th class="text-center">President Dir. Approval</th>
                        <th class="text-center"></th>
                    </tr>
                    </thead>
                    @php
                        $cashouts = 0.0;
                        $cashoutsD = 0.0;
                    @endphp
                    @if($numRowsOut > 0)
                        @foreach($cashout as $key => $val)
                            <tr>
                                <td class="text-center">{{$key+1}}</td>
                                <td class="text-center">{{date('d-m-Y',strtotime($val->tanggal))}}</td>
                                <td class="text-center">{{$val->no_nota}}</td>
                                <td class="text-left">{{"[".$val->source_string."] ".$val->deskripsi}}</td>
                                <td class="text-right">@if($val->currency == 'IDR'){{number_format($val->cashout,2)}} @else 0.00 @endif</td>
                                <td class="text-right">@if($val->currency == 'USD'){{number_format($val->cashout,2)}} @else 0.00 @endif</td>
                                <td class="text-center">
                                    @if($val->fin_approve == null)
                                        <button type="button" class="btn btn-link-primary btn-xs" data-toggle="modal" data-target="#apprCashOut{{$val->id}}"><i class="fa fa-clock"></i>&nbsp;&nbsp; Waiting... </button>
                                        <div class="modal fade" id="apprCashOut{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Finance Approval</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <i aria-hidden="true" class="ki ki-close"></i>
                                                        </button>
                                                    </div>
                                                    <form method="post" action="{{route('subcost.approveFin')}}" enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="hidden" name="prj_id" id="prj_id" value="{{$project->id}}">
                                                        <input type="hidden" name="id" id="id" value="{{$val->id}}">
                                                        <input type="hidden" name="cashtype" id="" value="cashout">
                                                        <div class="modal-body">
                                                            <hr>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    
                                                                    <div class="form-group row" id="opt">
                                                                        <label class="col-md-2 col-form-label text-right">Currency</label>
                                                                        <div class="col-md-6">
                                                                            <select name="currency" id="currency" class="form-control">
                                                                                <option></option>
                                                                                <option value="IDR" @if($val->currency == 'IDR') SELECTED @endif>IDR</option>
                                                                                <option value="USD" @if($val->currency == 'USD') SELECTED @endif>USD</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label class="col-md-2 col-form-label text-right">Date</label>
                                                                        <div class="col-md-6">
                                                                            <input type="date" name="req_date" id="req_date" value="{{$val->tanggal}}" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label class="col-md-2 col-form-label text-right">Subject</label>
                                                                        <div class="col-md-5">
                                                                            <input type="text" class="form-control" name="subject" value="{{$val->no_nota}}" required>
                                                                        </div>
                                                                        <div class="col-sm-3">
                                                                            <p style="color: red">*Wajib diisi</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label class="col-md-2 col-form-label text-right">Project</label>
                                                                        <div class="col-md-6">
                                                                            <input type="text" name="prj_name" readonly value="{{$project->prj_name}}" id="prj_name" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label class="col-md-2 col-form-label text-right">Deliver To</label>
                                                                        <div class="col-md-6">
                                                                            <input type="text" name="deliverto" id="deliverto" value="{{$val->receiver}}" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label class="col-md-2 col-form-label text-right">Deliver By</label>
                                                                        <div class="col-md-6">
                                                                            <input type="text" name="deliverby" id="deliverby" value="{{$val->deliver}}" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label class="col-md-2 col-form-label text-right">Amount</label>
                                                                        <div class="col-md-6">
                                                                            <input type="number" class="form-control" value="{{$val->cashout}}" name="amount">
                                                                        </div>

                                                                    </div>

                                                                    <div class="form-group row" >
                                                                        <label class="col-md-2 col-form-label text-right">Description</label>
                                                                        <div class="col-md-6" style="margin: 9px 0 0 0;">
                                                                            <textarea name="deskripsi" class="form-control" id="deskripsi" size="50">{{$val->deskripsi}}</textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row" id="opt">
                                                                        <label class="col-md-2 col-form-label text-right">Bank Source</label>
                                                                        <div class="col-md-6">
                                                                            <select name="source" id="source" class="form-control" required="">
                                                                                <option value=""></option>
                                                                                @foreach($sources as $key2 => $bank)
                                                                                    @if($val->currency == $bank->currency)
                                                                                        <option value="{{$bank->id}}">[{{$bank->currency}}] {{$bank->source}}</option>
                                                                                    @endif
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                                                            <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                                                                <i class="fa fa-check"></i>
                                                                Approve</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($val->fin_approve != null)
                                        <label class='text-success'><i class='fa fa-check'></i></label>&nbsp;&nbsp {{date('Y-m-d', strtotime($val->fin_approve_time))}}
                                    @else
                                        Waiting
                                    @endif
                                </td>
                                <td class="text-right text-block">
{{--                                    @if($detail->m_approve==null)--}}
                                        <button type="button" class="btn btn-default btn-xs	btn-primary" data-toggle="modal" data-target="#editCashOut{{$val->id}}"><i class="fa fa-edit"></i></button>
                                        <a href="{{route('subcost.delete.detail',['id' => $val->id_subcost,'id_detail' => $val->id])}}" class="btn btn-danger btn-xs btn-PRIMARY" title="Delete" onclick="return confirm('Are you sure you want to delete ?')"><i class="fa fa-trash"></i></a>
{{--                                    @endif--}}
                                </td>
                                <div class="modal fade" id="editCashOut{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Edit Cash In</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <i aria-hidden="true" class="ki ki-close"></i>
                                                </button>
                                            </div>
                                            <form method="post" action="{{route('subcost.addCash')}}" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="prj_id" id="prj_id" value="{{$project->id}}">
                                                <input type="hidden" name="id_edit" id="id_edit" value="{{$val->id}}">
                                                <input type="hidden" name="cashtype" id="" value="cashout">
                                                <div class="modal-body">
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group row" id="opt">
                                                                <label class="col-md-2 col-form-label text-right">Source</label>
                                                                <div class="col-md-6">
                                                                    <select name="source" id="source" class="form-control">
                                                                        <option></option>
                                                                        <option value="BR" @if($val->source_string == 'BR') SELECTED @endif>BR</option>
                                                                        <option value="oo" @if($val->source_string == 'oo') SELECTED @endif>--</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row" id="opt">
                                                                <label class="col-md-2 col-form-label text-right">Currency</label>
                                                                <div class="col-md-6">
                                                                    <select name="currency" id="currency" class="form-control">
                                                                        <option></option>
                                                                        <option value="IDR" @if($val->currency == 'IDR') SELECTED @endif>IDR</option>
                                                                        <option value="USD" @if($val->currency == 'USD') SELECTED @endif>USD</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-md-2 col-form-label text-right">Date</label>
                                                                <div class="col-md-6">
                                                                    <input type="date" name="req_date" id="req_date" value="{{$val->tanggal}}" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-md-2 col-form-label text-right">Subject</label>
                                                                <div class="col-md-5">
                                                                    <input type="text" class="form-control" name="subject" value="{{$val->no_nota}}" required>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <p style="color: red">*Wajib diisi</p>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-md-2 col-form-label text-right">Project</label>
                                                                <div class="col-md-6">
                                                                    <input type="text" name="prj_name" readonly value="{{$project->prj_name}}" id="prj_name" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-md-2 col-form-label text-right">Deliver To</label>
                                                                <div class="col-md-6">
                                                                    <input type="text" name="deliverto" id="deliverto" value="{{$val->receiver}}" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-md-2 col-form-label text-right">Deliver By</label>
                                                                <div class="col-md-6">
                                                                    <input type="text" name="deliverby" id="deliverby" value="{{$val->deliver}}" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-md-2 col-form-label text-right">Amount</label>
                                                                <div class="col-md-6">
                                                                    <input type="number" class="form-control" value="{{$val->cashout}}" name="amount">
                                                                </div>

                                                            </div>

                                                            <div class="form-group row" >
                                                                <label class="col-md-2 col-form-label text-right">Description</label>
                                                                <div class="col-md-6" style="margin: 9px 0 0 0;">
                                                                    <textarea name="deskripsi" class="form-control" id="deskripsi" size="50">{{$val->deskripsi}}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                                                    <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                                                        <i class="fa fa-check"></i>
                                                        Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </tr>
                            @php
                                if ($val->currency == 'IDR'){
                                    $cashouts += intval($val->cashout);
                                } else {
                                    $cashoutsD += intval($val->cashout);
                                }
                            @endphp
                        @endforeach
                    @else
                        <tr><td colspan='6'>No record found.</td></tr>
                    @endif
                    <tr>
                        <td colspan="2"></td>
                        <td colspan="2" class="text-right"><b>TOTAL</b></td>
                        <td class="text-right"><b>IDR. {{number_format($cashouts,2)}}</b></td>
                        <td class="text-right"><b>USD. {{number_format($cashoutsD,2)}}</b></td>
                        <td colspan="4"></td>
                    </tr>
                </table>

                <table class="table table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;" border="0">
                    <thead>
                    <tr>
                        <th class="text-left" colspan="7">
                            <h4 class="text-success">

                            </h4>
                        </th>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td colspan="2" class="text-right"><h2><b>BALANCES</b></h2></td>
                        <td class="text-right"><b>IDR. {{number_format(($cashins - $cashouts),2)}}</b></td>
                        <td class="text-right"><b>USD. {{number_format(($cashinsD - $cashoutsD),2)}}</b></td>
                        <td></td>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-md-10"></div>
                <div class="col-md-2">
                    <a href="{{route('subcost.index')}}" class="btn btn-success btn-lg">
                        <i class="fa fa-window-close"></i>&nbsp;&nbsp;Close
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addCashIn" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Sub Cost</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('subcost.addCash')}}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="prj_id" id="prj_id" value="{{$project->id}}">
                    <input type="hidden" name="cashtype" id="" value="cashin">
                    <div class="modal-body">
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row" id="opt">
                                    <label class="col-md-2 col-form-label text-right">Source</label>
                                    <div class="col-md-6">
                                        <select name="source" id="source" class="form-control">
                                            <option></option>
                                            <option value="BR">BR</option>
                                            <option value="oo">--</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" id="opt">
                                    <label class="col-md-2 col-form-label text-right">Currency</label>
                                    <div class="col-md-6">
                                        <select name="currency" id="currency" class="form-control">
                                            <option></option>
                                            <option value="IDR">IDR</option>
                                            <option value="USD">USD</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Date</label>
                                    <div class="col-md-6">
                                        <input type="date" name="req_date" id="req_date" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Subject</label>
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" name="subject" required>
                                    </div>
                                    <div class="col-sm-3">
                                        <p style="color: red">*Wajib diisi</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Project</label>
                                    <div class="col-md-6">
                                        <input type="text" name="prj_name" readonly value="{{$project->prj_name}}" id="prj_name" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Deliver To</label>
                                    <div class="col-md-6">
                                        <input type="text" name="deliverto" id="deliverto" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Deliver By</label>
                                    <div class="col-md-6">
                                        <input type="text" name="deliverby" id="deliverby" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Amount</label>
                                    <div class="col-md-6">
                                        <input type="number" class="form-control" name="amount">
                                    </div>

                                </div>

                                <div class="form-group row" >
                                    <label class="col-md-2 col-form-label text-right">Description</label>
                                    <div class="col-md-6" style="margin: 9px 0 0 0;">
                                        <textarea name="deskripsi" class="form-control" id="deskripsi" size="50"></textarea>
                                    </div>
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
    <div class="modal fade" id="addCashOut" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Sub Cost</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('subcost.addCash')}}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="prj_id" id="prj_id" value="{{$project->id}}">
                    <input type="hidden" name="cashtype" id="" value="cashout">
                    <div class="modal-body">
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row" id="opt">
                                    <label class="col-md-2 col-form-label text-right">Source</label>
                                    <div class="col-md-6">
                                        <select name="source" id="source" class="form-control">
                                            <option></option>
                                            <option value="BR">BR</option>
                                            <option value="oo">--</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" id="opt">
                                    <label class="col-md-2 col-form-label text-right">Currency</label>
                                    <div class="col-md-6">
                                        <select name="currency" id="currency" class="form-control">
                                            <option></option>
                                            <option value="IDR">IDR</option>
                                            <option value="USD">USD</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Date</label>
                                    <div class="col-md-6">
                                        <input type="date" name="req_date" id="req_date" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Subject</label>
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" name="subject" required>
                                    </div>
                                    <div class="col-sm-3">
                                        <p style="color: red">*Wajib diisi</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Project</label>
                                    <div class="col-md-6">
                                        <input type="text" name="prj_name" readonly value="{{$project->prj_name}}" id="prj_name" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Deliver To</label>
                                    <div class="col-md-6">
                                        <input type="text" name="deliverto" id="deliverto" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Deliver By</label>
                                    <div class="col-md-6">
                                        <input type="text" name="deliverby" id="deliverby" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Amount</label>
                                    <div class="col-md-6">
                                        <input type="number" class="form-control" name="amount">
                                    </div>

                                </div>

                                <div class="form-group row" >
                                    <label class="col-md-2 col-form-label text-right">Description</label>
                                    <div class="col-md-6" style="margin: 9px 0 0 0;">
                                        <textarea name="deskripsi" class="form-control" id="deskripsi" size="50"></textarea>
                                    </div>
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
@endsection
@section('custom_script')

@endsection
