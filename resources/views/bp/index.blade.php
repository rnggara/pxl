@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Bid & Performance</h3>
            </div>
            @actionStart('b_p','create')
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addItem"><i class="fa fa-plus"></i>Add New</button>
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
                        <span class="nav-text">Ongoing Bonds</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#sales" aria-controls="profile">
                        <span class="nav-icon">
                            <i class="flaticon-folder-2"></i>
                        </span>
                        <span class="nav-text">Bonds Bank</span>
                    </a>
                </li>
            </ul>
            <div class="tab-content mt-5" id="myTabContent">
                @actionStart('b_p','read')
                <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="home-tab">
                    <div class="alert alert-info" role="alert">
                        <i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;&nbsp;{{ "This page contains all bid  bonds and performance bonds that needs approval. You can also track each bond's progress and fund retrieval." }}
                    </div>
                    <hr class="n_button">
                    <div class="alert alert-secondary col-md-6">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="well">
                                    <table width="100%">
                                        <tr>
                                            <td class="text-left">Ongoing ({{$count_ongoing}})</td>
                                            <td class="text-left">:</td>
                                            <td class="text-right"><b>{{number_format($sum_ongoing,2)}}</b></td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">Published ({{$count_publish}}) </td>
                                            <td class="text-left">:</td>
                                            <td class="text-right"><b>{{number_format($sum_publish,2)}}</b></td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">Total ({{(intval($count_ongoing) + intval($count_publish))}}) </td>
                                            <td class="text-left">:</td>
                                            <td class="text-right"><b>{{number_format((intval($sum_ongoing) + intval($sum_publish)),2)}}</b></td>
                                        </tr>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <table class="table table-bordered table-hover display font-size-sm table-light-primary" style="margin-top: 13px !important; width: 100%;">
                            <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-left">Bond#</th>
                                <th class="text-center">Code & Company</th>
                                <th class="text-center">Project Name</th>
                                <th class="text-center">Total Amount</th>
                                <th class="text-center">Amount <br> Administration</th>
                                <th class="text-center">Marketing Div. Approval</th>
                                <th class="text-center">Finance Div. Approval</th>
                                <th class="text-center">Finance Dir. Approval</th>
                                <th class="text-center">Bond Retrieval Marketing</th>
                                <th class="text-center">Bond Receipt Finance</th>
                                <th class="text-center">Final Approval</th>
                                @actionStart('b_p', 'delete')
                                <th></th>
                                @actionEnd
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($bpongoing as $key => $value)
                                @php
                                    $date1 = date_create(date("Y-m-d"));
                                    $date2 = date_create($value->date2);
                                    $dateDiff = date_diff($date1, $date2);
                                    $diffDay = $dateDiff->format("%R%a");
                                    $bg = "";
                                    if($diffDay <= 7 && !empty($value->release_date)){
                                        $bg = "bg-light-danger";
                                    }
                                @endphp
                                <tr class="{{ $bg }}">
                                    <td class="text-center">{{($key+1)}}</td>
                                    <td>{{$value->id.'/'.(strtoupper(\Session::get('company_tag'))).'/'.$value->type_bond.'/'.date('m',strtotime($value->submit_date)).'/'.date('Y',strtotime($value->submit_date))}}</td>
                                    <td class="text-center">[{{$value->prj_code}}]<br><b>{{$value->perusahaan}}</b></td>
                                    <td class="text-center">{{$value->prj_name}} </td>
                                    <td class="text-center">
                                        {{$value->currency}}
                                        {{number_format($value->nilai_jaminan)}}
                                    </td>
                                    <td class="text-right">
                                        Amount : @foreach ($value->det->where("item_name", "AMOUNT") as $item)
                                            {{ number_format($item->request_amount, 2) }}
                                        @endforeach
                                        <br>
                                        Administration : @foreach ($value->det->where("item_name", "ADMINISTRATION") as $item)
                                            {{ number_format($item->request_amount, 2) }}
                                        @endforeach
                                    </td>
                                    <td class="text-center">
                                        @if($value->bp_status == 'Created')
                                            <a href='#' class='btn btn-link btn-xs'><i class='fa fa-pencil-alt'></i>&nbsp;&nbsp;Add Item</a>
                                        @elseif($value->bp_status == 'Marketing Done' ||
                                                $value->bp_status == 'Waiting Approval' ||
                                                $value->bp_status == 'Reject' ||
                                                $value->bp_status == 'Waiting For Release' ||
                                                $value->bp_status == 'Retrieved' ||
                                                $value->bp_status == 'Received' ||
                                                $value->bp_status == 'Released')
                                            <label class="label label-success" title="Approved"><i class="fa fa-check"></i></label>
                                        @else
                                            <a href='#' class='btn btn-link btn-xs'><i class='fa fa-pencil-alt'></i>&nbsp;&nbsp;Add Item</a>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($value->bp_status == 'Created')
                                            <a href='#' class='btn btn-link btn-xs'><i class='fa fa-pencil-alt'></i>&nbsp;&nbsp;Add Item</a>
                                        @elseif($value->bp_status == 'Waiting Approval' ||
                                                  $value->bp_status == 'Reject' ||
                                                  $value->bp_status == 'Waiting For Release' ||
                                                  $value->bp_status == 'Retrieved' ||
                                                  $value->bp_status == 'Received' ||
                                                  $value->bp_status == 'Released')
                                            <label class="label label-success" title="Approved"><i class="fa fa-check"></i></label>
                                        @elseif($value->bp_status == 'Reject')
                                            <p class="text-danger">Price Has Been Reject<br><a href='#' class='btn btn-link btn-xs'><i class='fa fa-pencil-alt'></i>&nbsp;&nbsp;Add Price </a></p>
                                        @else
                                            <a href='{{route('bp.findiv',['id'=>$value->id])}}' class='btn btn-link btn-xs'><i class='fa fa-pencil-alt'></i>&nbsp;&nbsp;Add Price</a>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($value->bp_status == 'Waiting Approval')
                                            <a href='{{route('bp.getDirAppr',['id'=>$value->id,'code' => base64_encode('detail')])}}' class='btn btn-link btn-xs'><i class='fa fa-pencil-alt'></i>&nbsp;&nbsp;Approve</a>
                                        @elseif($value->bp_status == 'Retrieved' ||
                                                  $value->bp_status == 'Received' ||
                                                  $value->bp_status == 'Released')
                                            <label class="label label-success" title="Approved"><i class="fa fa-check"></i></label><br>
                                            {{date('Y-m-d',strtotime($value->release_date))}}
                                        @else
                                            <i class="fa fa-clock"></i>&nbsp;&nbsp;Waiting
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($value->bp_status == 'Released')
                                            <form id='form1' class='form-inline' name='form1' method='post' action='{{route('bp.bondR')}}'>
                                                @csrf
                                                <div class='form-group'>
                                                    <input type='text' name='retrieve_to' id='retrieve_to' placeholder='Name' style='max-width: 100px' class='form-control' />
                                                </div>
                                                <input type="hidden" name="id" value="{{$value->id}}">
                                                <input type="hidden" name="type" value="Retrive">
                                                <input type='submit' name='Retrive' id='Retrive' value='Retrieve' class='btn btn-default' />
                                            </form>
                                        @elseif($value->bp_status == 'Retrieved' || $value->bp_status == 'Received')
                                            <label class="label label-success" title="Approved"><i class="fa fa-check"></i></label><br>
                                            {{date("d-m-Y",strtotime($value->retrieve_date))}}<br>
                                            {{$value->retrieve_to}} to {{$value->retrieve_by}}
                                        @else
                                            <i class="fa fa-clock"></i>&nbsp;&nbsp;Waiting
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($value->bp_status == 'Retrieved')
                                            <form id='form1' class='form-inline' name='form1' method='post' action='{{route('bp.bondR')}}'>
                                                @csrf
                                                <input type="hidden" name="id" value="{{$value->id}}">
                                                <div class='form-group'>
                                                    <input type='text' name='receive_to' id='receive_to' placeholder='Name' style='max-width: 100px' class='form-control' />
                                                </div>
                                                <input type="hidden" name="type" value="Receive">
                                                <input type='submit' name='Receive' id='Receive' value='Receive' class='btn btn-default' />
                                            </form>
                                        @elseif($value->bp_status == 'Received')
                                            <label class="label label-success" title="Approved"><i class="fa fa-check"></i></label><br>
                                            {{date("d-m-Y",strtotime($value->receive_date))}}<br>
                                            {{$value->receive_to}} to {{$value->receive_by}}
                                        @else
                                            <i class="fa fa-clock"></i>&nbsp;&nbsp;Waiting
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($value->bp_status == 'Received')
                                            <a href='{{route('bp.getDirAppr',['id'=>$value->id,'code' => base64_encode('actual')])}}'><i class='fa fa-pencil-alt'></i>&nbsp;&nbsp;Approve</a>
                                        @elseif($value->bp_status == 'Done')
                                            <label class="label label-success" title="Approved"><i class="fa fa-check"></i></label><br>
                                        @else
                                            <i class="fa fa-clock"></i>&nbsp;&nbsp;Waiting
                                        @endif
                                    </td>
                                    @actionStart('b_p', 'delete')
                                    <td align="center">
                                        <a href="{{ route('bp.delete', $value->id) }}" class="btn btn-icon btn-xs btn-danger" onclick="return confirm('Delete?')"><i class="fa fa-trash"></i></a>
                                    </td>
                                    @actionEnd
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="sales" role="tabpanel" aria-labelledby="profile-tab">

                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                            <thead class="table-success">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-left">Project Code</th>
                                <th class="text-left">Project Name</th>
                                <th class="text-right">Currency</th>
                                <th class="text-right">Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($bppublish as $key => $value)
                                <tr>
                                    <td class="text-center">{{($key+1)}}</td>
                                    <td>
                                        <a href='{{route('bp.view',['id'=>$value->id])}}' class='btn btn-link btn-xs'><i class='fa fa-pencil-alt'></i>&nbsp;&nbsp;{{$value->prj_code}}</a>
                                    </td>
                                    <td>{{$value->prj_name}}</td>
                                    <td class="text-right">{{$value->currency}}</td>
                                    <td class="text-right">{{number_format($value->nilai_jaminan)}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @actionEnd
            </div>
        </div>
    </div>
    <div class="modal fade" id="addItem" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Bid & Performance Bond</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('bp.add')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row" id="opt">
                                    <label class="col-md-2 col-form-label text-right">Project Name</label>
                                    <div class="col-md-6">
                                        <select name="project" id="project" class="form-control" required>
                                            <option value="">Select Project</option>
                                            @foreach($projects as $project)
                                                <option value="{{$project->id}}">{{$project->prj_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Tender Number</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="Tender Number" name="tender_number" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Company Name</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="Company Name" name="company_name" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Type of Bond</label>
                                    <div class="col-md-3 radio-inline">
                                        <div class="radio-inline">
                                            <label class="radio radio-outline radio-success">
                                                <input type="radio" name="bond_type" value="B"/>
                                                <span></span>
                                                Bid
                                            </label>
                                            <label class="radio radio-outline radio-success">
                                                <input type="radio" name="bond_type"  value="P"/>
                                                <span></span>
                                                Performance
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Bond Number</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="Bond Number" name="bond_number">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Purpose of Work</label>
                                    <div class="col-md-6">
                                        <textarea name="purpose_work" id="" class="form-control" cols="30" rows="10"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row" id="opt">
                                    <label class="col-md-2 col-form-label text-right">Collateral Amount</label>
                                    <div class="col-md-6">
                                        <select name="currency" id="currency" class="form-control">
                                            <option value="IDR" selected="selected">IDR (Rp)</option>
                                            <option value="USD">USD ($)</option>
                                            <option value="EURO">EURO (€)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" id="opt">
                                    <label class="col-md-2 col-form-label text-right"></label>
                                    <div class="col-md-6">
                                        <input type="number" class="form-control" placeholder="Amount" name="amount" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Valid From</label>
                                    <div class="col-md-6">
                                        <input type="date" name="date1" id="date1" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group row" id="opt">
                                    <label class="col-md-2 col-form-label text-right">Duration (Days)</label>
                                    <div class="col-md-6">
                                        <input type="number" class="form-control" placeholder="" name="duration">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">{{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}</label>
                                    <div class="col-md-6">
                                        <select name="tc_id" class="form-control" id="" required>
                                            <option value="">Choose here</option>
                                            @foreach ($coa as $item)
                                                <option value="{{ $item->id }}">[{{ $item->code }}] {{ $item->name }}</option>
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
            $("select.form-control").select2({
                width: "100%"
            })
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
