@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>CASH BOND</h3>
            </div>
        </div>
        <div class="card-body">
            <div class="well">
                <table>
                    <tr>
                        <td>
                            <table style="margin-right: 50px;clear:both">
                                <tr>
                                    <td>Name #</td>
                                    <td>:</td>
                                    <td><b>{{strtoupper(\Session::get('company_name_parent'))}}</b></td>
                                </tr>
                                <tr>
                                    <td>Address</td>
                                    <td>:</td>
                                    <td>{{\Session::get('company_address')}} </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td>Phone/Fax: {{\Session::get('company_phone')}} /
                                        Email: {{\Session::get('company_email')}}
                                        NPWP: {{\Session::get('company_npwp')}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>No</td>
                                    <td>:</td>
                                    <td>{{$cashbond->id}}</td>
                                </tr>
                                <tr>
                                    <td>Date</td>
                                    <td>:</td>
                                    <td>{{date('d F Y', strtotime($cashbond->input_date))}}</td>
                                </tr>
                                <tr>
                                    <td>Subject</td>
                                    <td>:</td>
                                    <td>{{$cashbond->subject}}</td>
                                </tr>
                                <tr>
                                    <td>Currency</td>
                                    <td>:</td>
                                    <td>{{$cashbond->currency}}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
            <br>
            <br>
            <table class="table table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Description</th>
                        <th class="text-center">No Nota</th>
                        <th class="text-center">Date</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr valign="top">
                        <td colspan="5" align='right' bgcolor="#CCCCCC" style="border-bottom:none; border-top:none; text-align: left; font-weight: bold;">CASH IN</td>
                    </tr>
                    @php
                    $no=1;
                    $sum = 0.0;
                    $cashin = 0.0;
                    $cashout =0.0;
                    @endphp
                @foreach($cashbond_detail as $key => $value)
                    <tr valign='top'>
                        <td class="text-center" style="border-bottom:none; border-top:none">{{($no)}}.</td>
                        <td style="border-bottom:none; border-top:none">
                            {{$value->deskripsi}}</td>
                        <td align='center' style="border-bottom:none; border-top:none">{{$value->no_nota}}</td>
                        <td align='center' style="border-bottom:none; border-top:none">{{date('d-m-Y', strtotime($value->tanggal))}}</td>
                        <td class="text-right" style="border-bottom:none; border-top:none">{{$cashbond->currency}}. {{number_format($value->cashin,2)}}</td>
                        @php
                            $sum += intval($value->cashin);
                            $cashin += intval($value->cashin);
                            $no+=1;
                        @endphp
                    </tr>
                @endforeach
                    <tr valign='top'>
                        <td colspan="5" align='right' bgcolor="#CCCCCC" style="text-align: left; font-weight: bold;">CASH OUT</td>
                    </tr>
                @foreach($cashbond_detailOut as $key => $value)
                    <tr valign='top'>
                        <td class="text-center" style="border-bottom:none; border-top:none">{{($no)}}.</td>
                        <td style="border-bottom:none; border-top:none">
                            {{$value->deskripsi}}</td>
                        <td align='center' style="border-bottom:none; border-top:none">{{$value->no_nota}}</td>
                        <td align='center' style="border-bottom:none; border-top:none">{{date('d-m-Y', strtotime($value->tanggal))}}</td>
                        <td class="text-right" style="border-bottom:none; border-top:none">{{$cashbond->currency}}. {{number_format($value->cashout,2)}}</td>
                    </tr>
                    @php
                        $sum -= intval($value->cashout);
                        $cashout += intval($value->cashout);
                        $no+=1;
                    @endphp
                @endforeach
                    <tr>
                        <td valign="top" style="border-top:none">&nbsp;</td>
                        <td valign="top" style="border-top:none">&nbsp;</td>
                        <td valign="top" style="border-top:none">&nbsp;</td>
                        <td align="right" style="border-top:none">TOTAL CASH OUT :</td>
                        <td class="text-right" style="border-top:medium"><b>{{$cashbond->currency}}. {{number_format($cashout,2)}}</b></td>
                    </tr>
                    <tr>
                        <td valign="top" style="border-top:none">&nbsp;</td>
                        <td valign="top" style="border-top:none" align='center' class="text-success">
                            TOTAL</td>
                        <td valign="top" style="border-top:none" align='center'>&nbsp;</td>
                        <td valign="top" style="border-top:none" align='center'>&nbsp;</td>
                        <td class="text-right" style="border-top:none" align='center'><b>{{$cashbond->currency}}. {{number_format($sum,2)}}</b></td>
                    </tr>
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-12">
                    <form  method="post" name="appr" id="appr" class="form-horizontal" action="{{route('cashbond.RAppr')}}">
                        @csrf
                        @if($who == 'director' || $who == 'cashin')
                            <table class='table'>
                                <tr>
                                    <td width='300px' align='right'>Bank Source : </td>
                                    <td>

                                        <select class="form-control" name="bank_sel" id="bank_sel" required>
                                            <option value="">Select Source</option>
                                            @foreach($sources as $key => $value)
                                                <option value="{{$value->id}}">{{$value->source}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        @endif
                        @if($who == 'cashin')
                            <div class="form-group">
                                <label for="" class="col-sm-1 control-label"></label>
                                <div class="col-sm-3">
                                    <strong>Proposed Due Date : {{date("d M Y", strtotime($cashbond->man_fin_cashout_date))}} </strong><br>
                                    <label class="checkbox-inline">
                                        Change due date to : <input type="date" class='form-control' id="due_date" name="due_date" value="{{$cashbond->man_fin_cashout_date}}"/>
                                    </label>
                                </div>
                            </div>
                        @endif
                        <!-- <div class="form-group">
                            <label for="" class="col-sm-1 control-label"></label>
                            <div class="col-sm-9">
                                Approve cashbond?<br>
                                <label class="checkbox-inline">
                                    <input type="checkbox" id="inlineCheckbox3" name="approved" value="option3"/> &nbsp;&nbsp;&nbsp;&nbsp; Approve
                                </label>
                            </div>
                        </div> -->
                        <br>
                        <br>
                        <div class="form-group">
                            <label for="" class="col-sm-1 control-label"></label>
                            <div class="col-sm-9">
                                <button type='submit' name='Submit' class="btn btn-success" value='Submit' onClick="return confirm('Are you sure to {{($who == 'manager') ? 'close' : 'approve'}}?');"><i class="fa fa-check"></i>&nbsp;&nbsp;{{($who == 'manager') ? 'Close Cashbond' : 'Approve'}}</button>
                            </div>
                        </div>
                        <input type='hidden' name='id' value='{{$cashbond->id}}'>
                        <input type='hidden' name='sum' value='{{$sum}}'>
                        <input type='hidden' name='cashinPost' value='{{$cashin}}'>
                        <input type='hidden' name='sum2' value='{{$cashout}}'>
                        <input type='hidden' name='who' value='{{$who}}'>
                        <input type='hidden' name='subject' value='{{$cashbond->subject}}'>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-footer">

        </div>

    </div>

@endsection
@section('custom_script')

@endsection
