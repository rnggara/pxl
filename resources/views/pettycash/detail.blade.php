@extends('layouts.template')
@section('content')
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #print-sheet, #print-sheet * {
                visibility: visible;
            }

            .print-hide {
                display: none;
            }

            #print-sheet {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    </style>
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Petty Cash Detail of: <b>{{$detail->subject}}</b></h3>
            </div>
            <div class="card-toolbar">
                <button type="button" onclick="print()" class="btn btn-primary"><i class="fa fa-print"></i></button>
            </div>
        </div>
        <div class="card-body">
            <div class="row" id="print-sheet">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="symbol symbol-90">
                                <img src='{{str_replace("public", "public_html", asset('images/'.Session::get('company_app_logo')))}}' height='30px' alt="Company Logo"/>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <h3>Subject : {{ $detail->subject }}</h3>
                            <h3>Project : {{ $project->prj_name }}</h3>
                            <h3>NO#     : {{ sprintf("%03d", $detail->id) }}/CASHBOND/{{ \Session::get('company_tag') }}/{{ $mnth_array[date('n', strtotime($detail->input_date))] }}/{{ date('y', strtotime($detail->input_date)) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <table class="table table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;" border="0">
                        <thead>
                        <tr>
                            <th class="text-left" colspan="3">
                                <h4 class="text-success">
                                    CASH IN
                                </h4>
                            </th>
                            <th class="text-right" colspan="3">
                                <h4 class="text-success">
                                    @if($detail->m_approve==null)
                                        <button type="button" class="btn btn-default btn-xs print-hide" data-toggle="modal" data-target="#addCashIn"><i class="fa fa-plus"></i></button>
                                    @endif
                                </h4>
                            </th>
                        </tr>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Date</th>
                            <th class="text-center text-block">Receipt #</th>
                            <th class="text-left">Description</th>
                            <th class="text-right">Amount</th>
                            <th class="text-center"></th>
                        </tr>
                        </thead>
                        @php
                            $cashins = 0.0;
                        @endphp
                        @if($numRowsIn > 0)
                            @foreach($detailIn as $key => $val)
                                <tr>
                                    <td class="text-center">{{$key+1}}</td>
                                    <td class="text-center">{{date('d-m-Y',strtotime($val->tanggal))}}</td>
                                    <td class="text-center">{{$val->no_nota}}</td>
                                    <td class="text-left">{{"[".$val->source_string."] ".$val->deskripsi}}</td>
                                    <td class="text-right">{{$detail->currency}}. {{number_format($val->cashin,2)}}</td>
                                    <td class="text-right text-block">
                                        @if($detail->m_approve==null)
                                            <button type="button" class="btn btn-default btn-xs btn-primary print-hide" data-toggle="modal" data-target="#editCashIn{{$val->id}}"><i class="fa fa-edit"></i></button>
                                            <a href="{{route('cashbond.deleteDetail',['id' => $val->id,'id_cb' => $val->id_cashbond])}}" class="btn btn-danger btn-xs btn-PRIMARY print-hide" title="Delete" onclick="return confirm('Are you sure you want to delete ?')"><i class="fa fa-trash"></i></a>
                                        @endif
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
                                                <form method="post" action="{{route('cashbond.addCashIn')}}" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="id" id="id" value="{{$detail->id}}">
                                                    <input type="hidden" name="curr" id="curr" value="{{$detail->currency}}">
                                                    <input type="hidden" name="cashtype" id="" value="cashin">
                                                    <input type="hidden" name="id_edit" id="" value="{{$val->id}}">
                                                    <div class="modal-body">
                                                        <hr>
                                                        <div class="row">
                                                            <div class="col-md-12">

                                                                {{-- <div class="form-group row" id="opt">
                                                                    <label class="col-md-2 col-form-label text-right">Source</label>
                                                                    <div class="col-md-6">
                                                                        <select name="source" id="source" class="form-control">
                                                                            <option></option>
                                                                            <option value="BR" @if($val->source_string == 'BR') SELECTED @endif>BR</option>
                                                                            <option value="oo" @if($val->source_string == 'oo') SELECTED @endif>--</option>
                                                                        </select>
                                                                    </div>
                                                                </div> --}}
                                                                <div class="form-group row">
                                                                    <label class="col-md-2 col-form-label text-right">Date</label>
                                                                    <div class="col-md-6">
                                                                        <input type="date" name="req_date" id="req_date" required class="form-control" value="{{$val->tanggal}}">
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
                                                                    <label class="col-md-2 col-form-label text-right">Amount</label>
                                                                    <div class="col-md-6">
                                                                        <input type="number" class="form-control" name="amount" required value="{{$val->cashin}}">
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
                                    $cashins += intval($val->cashin);
                                @endphp
                            @endforeach
                        @else
                            <tr><td colspan='6'>No record found.</td></tr>
                        @endif
                        <tr>
                            <td colspan="2"></td>
                            <td colspan="2" class="text-right"><b>TOTAL</b></td>
                            <td class="text-right"><b>{{$detail->currency}}. {{number_format($cashins,2)}}</b></td>
                            <td></td>
                        </tr>
                    </table>
                    <?php $cashout = 0 ?>
                    @if(!empty($detail->approved_by))
                    <table class="table table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;" border="0">
                        <thead>
                        <tr>
                            <th class="text-left" colspan="6">
                                <h4 class="text-danger">
                                    CASH OUT
                                </h4>
                            </th>
                        </tr>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Date</th>
                            <th class="text-center text-block">Receipt #</th>
                            <th class="text-left">Description</th>
                            <th class="text-right">Amount</th>
                            <th class="text-center"></th>
                        </tr>
                        </thead>
                        @php
                            $iType = 1;
                            $cashout = 0.0;
                        @endphp
                        @foreach($typewo as $key => $val)
                            <tr {{(!isset($isCashbond[$val->id])) ? "class=print-hide" : ""}}>
                                <th class="text-left text-primary" colspan="3">
                                    {{$val->name}}
                                </th>
                                <th class="text-right" colspan="3">
                                    <h4 class="text-success">
                                        @if($detail->m_approve==null)
                                            <button type="button" class="btn btn-default btn-xs print-hide" data-toggle="modal" data-target="#addCashOut{{$val->id}}"><i class="fa fa-plus"></i></button>
                                        @endif
                                    </h4>
                                </th>
                                <div class="modal fade" id="addCashOut{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Create Cash Out {{$val->name}}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <i aria-hidden="true" class="ki ki-close"></i>
                                                </button>
                                            </div>
                                            <form method="post" action="{{route('cashbond.addCashOut')}}" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="id" id="id" value="{{$detail->id}}">
                                                <input type="hidden" name="curr" id="curr" value="{{$detail->currency}}">
                                                <input type="hidden" name="category" id="category" value="{{$val->id}}">
                                                <input type="hidden" name="cashtype" id="" value="cashout">
                                                <div class="modal-body">
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-md-12">

                                                            {{-- <div class="form-group row" id="opt">
                                                                <label class="col-md-2 col-form-label text-right">Source</label>
                                                                <div class="col-md-6">
                                                                    <select name="source" id="source" class="form-control">
                                                                        <option></option>
                                                                        <option value="BR">BR</option>
                                                                        <option value="oo">--</option>
                                                                    </select>
                                                                </div>
                                                            </div> --}}
                                                            <div class="form-group row">
                                                                <label class="col-md-2 col-form-label text-right">Date</label>
                                                                <div class="col-md-6">
                                                                    <input type="date" name="req_date" id="req_date" class="form-control" required>
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
                                                                <label class="col-md-2 col-form-label text-right">Amount</label>
                                                                <div class="col-md-6">
                                                                    <input type="text" class="form-control number" name="amount" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row" >
                                                                <label class="col-md-2 col-form-label text-right">Description</label>
                                                                <div class="col-md-6" style="margin: 9px 0 0 0;">
                                                                    <textarea name="deskripsi" class="form-control" id="deskripsi" size="50"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row" >
                                                                <label class="col-md-2 col-form-label text-right">{{ (!empty(\Session::get('company_tc_name'))) ? \Session::get('company_tc_name') : "Transaction Code" }}</label>
                                                                <div class="col-md-6" style="margin: 9px 0 0 0;">
                                                                    <select name="tc_child" class="form-control" required>
                                                                        <option value="{{ $val->id }}">{{ "[$val->code] $val->name" }}</option>
                                                                        @if (isset($coa[$val->code]))
                                                                            @foreach ($coa[$val->code] as $item)
                                                                                <option value="{{ $item->id }}">{{ "[$item->code] $item->name" }}</option>
                                                                            @endforeach
                                                                        @endif
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
                                                        Save</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </tr>
                            @if ($numRowsOut > 0)
                                @php
                                    $a = 0;
                                @endphp
                                @foreach($detailOut as $key2 => $value)
                                    @if($value->tc_id_parent == $val->id)
                                        @php
                                            $coa_name = (isset($dCoa['name'][$value->tc_id])) ? $dCoa['name'][$value->tc_id] : "";
                                            $coa_code = (isset($dCoa['code'][$value->tc_id])) ? $dCoa['code'][$value->tc_id] : "";
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{$a+1}}</td>
                                            <td class="text-center">{{date('d-m-Y',strtotime($value->tanggal))}}</td>
                                            <td class="text-center">{{$value->no_nota}}</td>
                                            <td class="text-left">{{"[".$coa_code."] $coa_name : ".$value->deskripsi}}</td>
                                            <td class="text-right">{{$detail->currency}}. {{number_format($value->cashout,2)}}</td>
                                            <td class="text-right text-block">
                                                @if($detail->m_approve==null)
                                                    <button type="button" class="btn btn-default btn-xs btn-primary print-hide" data-toggle="modal" data-target="#editCashOut{{$value->id}}"><i class="fa fa-edit"></i></button>
                                                    <a href="{{route('cashbond.deleteDetail',['id' => $value->id,'id_cb' => $value->id_cashbond])}}" class="btn btn-danger btn-xs btn-PRIMARY print-hide" title="Delete" onclick="return confirm('Are you sure you want to delete ?')"><i class="fa fa-trash"></i></a>
                                                @endif
                                            </td>
                                            <div class="modal fade" id="editCashOut{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Edit Cash Out {{$value->name}}</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <i aria-hidden="true" class="ki ki-close"></i>
                                                            </button>
                                                        </div>
                                                        <form method="post" action="{{route('cashbond.addCashOut')}}" enctype="multipart/form-data">
                                                            @csrf
                                                            <input type="hidden" name="id" id="id" value="{{$detail->id}}">
                                                            <input type="hidden" name="curr" id="curr" value="{{$detail->currency}}">
                                                            <input type="hidden" name="cashtype" id="" value="cashout">
                                                            <input type="hidden" name="id_edit" id="" value="{{$value->id}}">
                                                            <div class="modal-body">
                                                                <hr>
                                                                <div class="row">
                                                                    <div class="col-md-12">

                                                                        {{-- <div class="form-group row" id="opt">
                                                                            <label class="col-md-2 col-form-label text-right">Source</label>
                                                                            <div class="col-md-6">
                                                                                <select name="source" id="source" class="form-control">
                                                                                    <option></option>
                                                                                    <option value="BR" @if($value->source_string == 'BR') SELECTED @endif>BR</option>
                                                                                    <option value="oo" @if($value->source_string == 'oo') SELECTED @endif>--</option>
                                                                                </select>
                                                                            </div>
                                                                        </div> --}}
                                                                        <div class="form-group row">
                                                                            <label class="col-md-2 col-form-label text-right">Date</label>
                                                                            <div class="col-md-6">
                                                                                <input type="date" name="req_date" id="req_date" required class="form-control" value="{{$value->tanggal}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <label class="col-md-2 col-form-label text-right">Subject</label>
                                                                            <div class="col-md-5">
                                                                                <input type="text" class="form-control" name="subject" value="{{$value->no_nota}}" required>
                                                                            </div>
                                                                            <div class="col-sm-3">
                                                                                <p style="color: red">*Wajib diisi</p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <label class="col-md-2 col-form-label text-right">Amount</label>
                                                                            <div class="col-md-6">
                                                                                <input type="text" class="form-control number" required name="amount" value="{{$value->cashout}}">
                                                                            </div>

                                                                        </div>

                                                                        <div class="form-group row" >
                                                                            <label class="col-md-2 col-form-label text-right">Description</label>
                                                                            <div class="col-md-6" style="margin: 9px 0 0 0;">
                                                                                <textarea name="deskripsi" class="form-control" id="deskripsi" size="50">{{$value->deskripsi}}</textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row" >
                                                                            <label class="col-md-2 col-form-label text-right">{{ (!empty(\Session::get('company_tc_name'))) ? \Session::get('company_tc_name') : "Transaction Code" }}</label>
                                                                            <div class="col-md-6" style="margin: 9px 0 0 0;">
                                                                                <select name="tc_child" class="form-control" required>
                                                                                    <option value="{{ $val->id }}">{{ "[$val->code] $val->name" }}</option>
                                                                                    @if (isset($coa[$val->code]))
                                                                                        @foreach ($coa[$val->code] as $item)
                                                                                            <option value="{{ $item->id }}" {{ ($item->id == $value->tc_id) ? "SELECTED" : "" }}>{{ "[$item->code] $item->name" }}</option>
                                                                                        @endforeach
                                                                                    @endif
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
                                                                    Update</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </tr>
                                        @php
                                            $a++;
                                            $cashout += intval($value->cashout);
                                        @endphp
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                        <tr>
                            <td colspan="2"></td>
                            <td colspan="2" class="text-right"><b>TOTAL</b></td>
                            <td class="text-right"><b>{{$detail->currency}}. {{number_format($cashout,2)}}</b></td>
                            <td></td>
                        </tr>
                    </table>
                    @endif
                    <table class="table table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;" border="0">
                        <thead>
                        <tr>
                            <th class="text-left" colspan="6">
                                <h4 class="text-success">
                                    BALANCES
                                </h4>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="2"></th>
                            <th colspan="2" class="text-right text-success"><b><h4>GRAND TOTAL</h4></b></th>
                            <th class="text-right">
                                <h4 class=" text-blue"><b>{{$detail->currency}}. {{number_format($cashins-$cashout,2)}}</b></h4>
                            </th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-md-10"></div>
                <div class="col-md-2">
                    <a href="{{route('cashbond.index')}}" class="btn btn-success btn-lg">
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
                    <h5 class="modal-title" id="exampleModalLabel">Create Cash In</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('cashbond.addCashIn')}}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="id" value="{{$detail->id}}">
                    <input type="hidden" name="curr" id="curr" value="{{$detail->currency}}">
                    <input type="hidden" name="cashtype" id="" value="cashin">
                    <div class="modal-body">
                        <hr>
                        <div class="row">
                            <div class="col-md-12">

                                {{-- <div class="form-group row" id="opt">
                                    <label class="col-md-2 col-form-label text-right">Source</label>
                                    <div class="col-md-6">
                                        <select name="source" id="source" class="form-control">
                                            <option></option>
                                            <option value="BR">BR</option>
                                            <option value="oo">--</option>
                                        </select>
                                    </div>
                                </div> --}}
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Date</label>
                                    <div class="col-md-6">
                                        <input type="date" name="req_date" id="req_date" class="form-control" required>
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
                                    <label class="col-md-2 col-form-label text-right">Amount</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control number" name="amount" required>
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
<script src="{{asset('assets/jquery-number/jquery.number.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("input.number").number(true, 2)
    })
</script>

@endsection
