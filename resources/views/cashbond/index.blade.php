@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Cashbond</h3>
            </div>
            @actionStart('cashbond', 'create')
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addItem"><i class="fa fa-plus"></i>Add Cashbond</button>
                </div>
                <!--end::Button-->
            </div>
            @actionEnd
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#all">
                        <span class="nav-icon">
                            <i class="flaticon-folder-1"></i>
                        </span>
                        <span class="nav-text">Cash Bond List</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#sales" aria-controls="profile">
                        <span class="nav-icon">
                            <i class="flaticon-folder-2"></i>
                        </span>
                        <span class="nav-text">Cash Bond Bank</span>
                    </a>
                </li>
            </ul>
            <div class="tab-content mt-5" id="myTabContent">
                <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="home-tab">
                    <h3> <small>Outstanding Cashbond: </small><b>{{number_format(0,2)}} </b></h3><br>
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                            <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-left">Cashbond#</th>
                                <th class="text-left">Subject</th>
                                <th class="text-left">Project</th>
                                <th class="text-right">Cash In/Out</th>
                                <th class="text-center">Due Date</th>
                                <th class="text-center">Release Appr.</th>
                                <th class="text-center">Cash Out Final</th>
                                <th class="text-center">Director Appr.</th>
                                <th class="text-center"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @actionStart('cashbond', 'read')
                            @php
                                $no = 1;
                            @endphp
                            @foreach($cashbond as $key => $value)

                                    @if(empty($value->dir_appr))
                                    <tr>
                                        <td class="text-center">{{($no)}}</td>
                                        @php
                                            $now = time();
                                            /** @var TYPE_NAME $value */
                                            $Tdue_date = strtotime($value->man_fin_cashout_date);
                                            $datediff = $now - $Tdue_date;
                                            $nDay =  round($datediff / (60 * 60 * 24)) * -1;

                                            if($nDay < -9) $nDay = -9;
                                            if($nDay < 0 && !$value->m_approve) $classTd = ' table-danger text-danger '; else $classTd = '';

                                            $req_month = date('n',strtotime($value->input_date));
                                            $req_year = date('y',strtotime($value->input_date));
                                            $arrRomawi  = array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
                                        @endphp
                                        <td class="text-left {{$classTd}}">
                                            {{$value->id.'/'.strtoupper(\Session::get('company_tag')).'/'.$arrRomawi[$req_month].'/'.$req_year}}
                                            <br>
                                            {{$value->division}}
                                        </td>
                                        <td class="text-left">
                                            <a href="{{route('cashbond.detail',['id' => $value->id])}}" class="btn btn-link dttb" title="{{$value->subject}}">
                                                <i class="fa fa-search"></i>&nbsp;&nbsp; {{$value->subject}}</a>
                                        </td>
                                        <td class="text-left">
                                            {{(isset($detProject[$value->project])) ? $detProject[$value->project]->prj_name : "N/A"}}
                                        </td>
                                        <td class="text-right">
                                            <p>CASHIN : {{(isset($cash[$value->id])) ? number_format(array_sum($cash[$value->id]['cashin']), 2) : number_format(0,2)}}</p>
                                            <p>CASHOUT : {{(isset($cash[$value->id])) ? number_format(array_sum($cash[$value->id]['cashout']), 2) : number_format(0,2)}}</p>
                                        </td>
                                        <td class="text-center">{{date('d F Y', strtotime($value->man_fin_cashout_date))}}</td>
                                        <td class="text-center">
                                            @if($value->approved_by != null)
                                                {{$value->approved_by}}<br>
                                                {{date('d M Y',strtotime($value->approved_time))}}
                                            @else
                                                @if($value->approved_by == null && isset($cash[$value->id]) && array_sum($cash[$value->id]['cashin']) > 0)
                                                    <a href='{{route('cashbond.getDetRA',['id' => $value->id,'who'=>base64_encode('cashin')])}}' class='btn btn-link'><i class='fa fa-clock'></i>&nbsp;&nbsp;waiting</a>
                                                @else
                                                    waiting for Cash In value
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($value->m_approve != null)
                                                {{$value->m_approve}} <br>
                                                {{date('d M Y',strtotime($value->m_approve_time))}}
                                            @else
                                            @if(isset($cash[$value->id]['cashout']))
                                                @if($value->approved_by == null && isset($cash[$value->id]) && array_sum($cash[$value->id]['cashin']) > 0)
                                                    <a href='{{route('cashbond.getDetRA',['id' => $value->id,'who'=>base64_encode('manager')])}}' class='btn btn-link'><i class='fa fa-clock'></i>&nbsp;&nbsp;waiting</a>
                                                @elseif(array_sum($cash[$value->id]['cashout']) > 0)
                                                    <a href='{{route('cashbond.getDetRA',['id' => $value->id,'who'=>base64_encode('manager')])}}' class='btn btn-link'><i class='fa fa-clock'></i>&nbsp;&nbsp;close</a>
                                                @else
                                                    <label class='text-warning'>waiting Value Cash Out</label>
                                                @endif
                                            @else
                                            -
                                            @endif

                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($value->dir_appr != null)
                                                {{$value->dir_appr}} <br>
                                                {{date('d M Y',strtotime($value->dir_appr_date))}}
                                            @else
                                                @if($value->dir_appr == null && $value->m_approve != null)
                                                    <a href='{{route('cashbond.getDetRA',['id' => $value->id,'who'=>base64_encode('director')])}}' class='btn btn-link'><i class='fa fa-clock'></i>&nbsp;&nbsp;waiting</a>
                                                @else
                                                    waiting
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href='{{route('cashbond.delete',['id' => $value->id])}}' class='btn btn-danger btn-xs' title='Delete' onclick="return confirm('Are you sure you want to delete?')"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    @php
                                    /** @var TYPE_NAME $no */
                                    $no += 1;
                                @endphp
                                    @endif


                            @endforeach
                            @actionEnd
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="sales" role="tabpanel" aria-labelledby="profile-tab">
                    <h3> <small>Outstanding Cashbond: </small><b>{{number_format(0,2)}} </b></h3><br>
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                            <thead class="table-success">
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Cashbond#</th>
                                <th class="text-left">Subject</th>
                                <th class="text-left">Project</th>
                                <th class="text-right">Cash In/Out</th>
                                <th class="text-center">Due Date</th>
                                <th class="text-center">Release Appr.</th>
                                <th class="text-center">Cash Out Final</th>
                                <th class="text-center">Director Appr.</th>
                                <th class="text-center"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @actionStart('cashbond', 'read')
                            @php
                                $no = 1;
                            @endphp
                            @foreach($cashbond as $key => $value)

                                    @if(!empty($value->dir_appr))
                                        <tr>
                                            <td class="text-center">{{($no)}}</td>
                                            @php
                                                $now = time();
                                                /** @var TYPE_NAME $value */
                                                $Tdue_date = strtotime($value->man_fin_cashout_date);
                                                $datediff = $now - $Tdue_date;
                                                $nDay =  round($datediff / (60 * 60 * 24)) * -1;

                                                if($nDay < -9) $nDay = -9;
                                                if($nDay < 0 && !$value->m_approve) $classTd = ' table-danger text-danger '; else $classTd = '';

                                                $req_month = date('n',strtotime($value->input_date));
                                                $req_year = date('y',strtotime($value->input_date));
                                                $arrRomawi  = array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
                                            @endphp
                                            <td class="text-left {{$classTd}}">
                                                {{$value->id.'/'.strtoupper(\Session::get('company_tag')).'/'.$arrRomawi[$req_month].'/'.$req_year}}
                                                <br>
                                                {{$value->division}}
                                            </td>
                                            <td class="text-left">
                                                <a href="{{route('cashbond.detail',['id' => $value->id])}}" class="btn btn-link dttb" title="{{$value->subject}}">
                                                    <i class="fa fa-search"></i>&nbsp;&nbsp; {{$value->subject}}</a>
                                            </td>
                                            <td class="text-left">
                                                {{(isset($detProject[$value->project])) ? $detProject[$value->project]->prj_name : "N/A"}}
                                            </td>
                                            <td class="text-right">
                                                <p>CASHIN : {{(isset($cash[$value->id])) ? number_format(array_sum($cash[$value->id]['cashin']), 2) : number_format(0,2)}}</p>
                                                <p>CASHOUT : {{(isset($cash[$value->id])) ? number_format(array_sum($cash[$value->id]['cashout']), 2) : number_format(0,2)}}</p>
                                            </td>
                                            <td class="text-center">{{date('d F Y', strtotime($value->man_fin_cashout_date))}}</td>
                                            <td class="text-center">
                                                @if($value->approved_by != null)
                                                    {{$value->approved_by}}<br>
                                                    {{date('d M Y',strtotime($value->approved_time))}}
                                                @else
                                                    @if($value->approved_by == null && isset($cash[$value->id]) && array_sum($cash[$value->id]['cashin']) > 0)
                                                        <a href='{{route('cashbond.getDetRA',['id' => $value->id,'who'=>base64_encode('cashin')])}}' class='btn btn-link'><i class='fa fa-clock'></i>&nbsp;&nbsp;waiting</a>
                                                    @else
                                                        waiting for Cash In value
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($value->m_approve != null)
                                                    {{$value->m_approve}} <br>
                                                    {{date('d M Y',strtotime($value->m_approve_time))}}
                                                @else
                                                    @if($value->approved_by == null && isset($cash[$value->id]) && array_sum($cash[$value->id]['cashin']) > 0)
                                                        <a href='{{route('cashbond.getDetRA',['id' => $value->id,'who'=>base64_encode('manager')])}}' class='btn btn-link'><i class='fa fa-clock'></i>&nbsp;&nbsp;waiting</a>
                                                    @elseif(array_sum($cash[$value->id]['cashout']) > 0)
                                                        <a href='{{route('cashbond.getDetRA',['id' => $value->id,'who'=>base64_encode('manager')])}}' class='btn btn-link'><i class='fa fa-clock'></i>&nbsp;&nbsp;close</a>
                                                    @else
                                                        <label class='text-warning'>waiting Value Cash Out</label>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($value->dir_appr != null)
                                                    {{$value->dir_appr}} <br>
                                                    {{date('d M Y',strtotime($value->dir_appr_date))}}
                                                @else
                                                    @if($value->dir_appr == null && $value->m_approve != null)
                                                        <a href='{{route('cashbond.getDetRA',['id' => $value->id,'who'=>base64_encode('director')])}}' class='btn btn-link'><i class='fa fa-clock'></i>&nbsp;&nbsp;waiting</a>
                                                    @else
                                                        waiting
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href='{{route('cashbond.delete',['id' => $value->id])}}' class='btn btn-danger btn-xs' title='Delete' onclick="return confirm('Are you sure you want to delete?')"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        @php
                                    /** @var TYPE_NAME $no */
                                    $no += 1;
                                @endphp
                                    @endif


                            @endforeach
                            @actionEnd
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addItem" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Cashbond</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('cashbond.add')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Subject</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="Name" name="subject">
                                    </div>
                                </div>
                                <div class="form-group row" id="opt">
                                    <label class="col-md-2 col-form-label text-right">Project</label>
                                    <div class="col-md-6">
                                        <select name="project" id="project" class="form-control">
                                            @foreach($projects as $key => $value)
                                                <option value="{{$value->id}}">{{$value->prj_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">For Division</label>
                                    <div class="col-md-6">
                                        <select name="division" id="division" class="form-control">
                                            <option value="">-Choose-</option>
                                            <option value="Asset">Asset</option>
                                            <option value="Consultant">Consultant</option>
                                            <option value="Finance">Finance</option>
                                            <option value="GA">GA</option>
                                            <option value="HRD">HRD</option>
                                            <option value="IT">IT</option>
                                            <option value="Laboratory">Laboratory</option>
                                            <option value="Maintenance">Maintenance</option>
                                            <option value="Marketing">Marketing</option>
                                            <option value="Operation">Operation</option>
                                            <option value="Procurement">Procurement</option>
                                            <option value="Production">Production</option>
                                            <option value="QC">QC</option
                                            ><option value="QHSSE">QHSSE</option>
                                            <option value="Receiptionist">Receiptionist</option>
                                            <option value="Secretary">Secretary</option>
                                            <option value="Technical">Technical</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" id="opt">
                                    <label class="col-md-2 col-form-label text-right">For Personel</label>
                                    <div class="col-md-6">
                                        <select name="for_personel" id="for_personel" class="form-control">
                                            <option value="open">Open Cashbond</option>
                                            @foreach($listpersons as $key => $value)
                                                <option value="{{$value->username}}">{{$value->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" >
                                    <label class="col-md-2 col-form-label text-right">Is Private</label>
                                    <div class="col-md-6" style="margin: 9px 0 0 0;">
                                        <input type="checkbox" name="is_private" value="1" style="vertical-align: middle;">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Due Date</label>
                                    <div class="col-md-6">
                                        <input type="date" name="due_date" id="due_date" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Vehicle</label>
                                    <div class="col-md-6">
                                        <select name="vehicle" id="vehicle" class="form-control">
                                            <option></option>
                                            @foreach($category as $key => $value)
                                                <option value="{{$value->id}}">{{$value->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Currency</label>
                                    <div class="col-md-6">
                                        <select name="currency" id="currency" class="form-control">
                                            <option value="IDR" selected="selected">IDR (Rp)</option>
                                            <option value="USD">USD ($)</option>
                                            <option value="EURO">EURO (€)</option>
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
@endsection

@section('custom_script')
    <script>
        $(document).ready(function () {
            $('.display').DataTable({
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            });
        });
    </script>
@endsection
