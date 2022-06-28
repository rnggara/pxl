@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                Policy List
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{route('policy.category')}}" title="Policy Category" class="btn btn-info"><i class="fa fa-cogs"></i></a>
                    &nbsp;&nbsp;
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addEmployee"><i class="fa fa-plus"></i>Add Policy</button>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th nowrap="nowrap" style="width: 20%">Policy #</th>
                        <th nowrap="nowrap" class="text-center">Policy Name</th>
                        <th nowrap="nowrap" class="text-left">Policy Category</th>
                        <th nowrap="nowrap" class="text-left">Division</th>
                        <th nowrap="nowrap" class="text-left">Created By</th>
                        <th nowrap="nowrap" class="text-left">Created Date</th>
                        <th nowrap="nowrap" class="text-left">Revision #</th>
                        <th nowrap="nowrap" data-priority=1 class="text-center">#</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($policy as $key => $value)
                        <tr>
                            <td>{{($key+1)}}</td>
                            <td style="width: 20%">

                                @if(isset($detailID[$value->id_main]))
                                    @if(!isset($detailRev[$value->id_main]))
                                        -
                                    @else

                                        <a href="{{route('policy.detail.viewappr',['id' => $detailID[$value->id_main]])}}" class="btn btn-xs btn-link"><i class="fa fa-search"></i>&nbsp;{{$value->id_main}}/{{strtoupper(\Session::get('company_tag'))}}-POLICY/{{date('m/y',strtotime($detailDate[$value->id_main]))}}</a>
                                        @if(isset($detailRev[$value->id_main]))
                                            @if($detailRev[$value->id_main] != '0')
                                                <label for="" class="text-info">[revision]</label>
                                            @endif
                                        @endif
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center"><a href="{{route('policy.detail',['id' => $value->id_main])}}" class="btn btn-xs btn-link"><i class="fa fa-search"></i>{{$value->topic}}</a></td>
                            <td class="text-left">{{$value->catName}}</td>
                            <td class="text-left">{{$value->location}}</td>
                            <td class="text-left">{{$value->main_created_by}}</td>
                            <td class="text-left">{{date('d F Y', strtotime($value->date_main))}}</td>
                            <td class="text-left">
                                @if(isset($detailRev[$value->id_main]))
                                    @if($detailRev[$value->id_main] != '0')
                                        @php
                                            $ends = array('th','st','nd','rd','th','th','th','th','th','th');
                                            /** @var TYPE_NAME $detailRev */
                                            /** @var TYPE_NAME $value */
                                            if ((intval($detailRev[$value->id_main]) %100) >= 11 && (intval($detailRev[$value->id_main])%100) <= 13)
                                               $abbreviation = intval($detailRev[$value->id_main]). 'th';
                                            else
                                               $abbreviation = intval($detailRev[$value->id_main]). $ends[intval($detailRev[$value->id_main]) % 10];
                                        @endphp
                                    {{$abbreviation}}
                                    @else
                                        {{$detailRev[$value->id_main]}}
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td data-priority=1 class="text-center">
                                <a href="{{route('policy.delete',['id' => $value->id_main])}}" title="Delete" class="btn btn-sm btn-danger btn-icon btn-xs" onclick="return confirm('Delete policy?')"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addEmployee" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Policy</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('policy.store')}}" >
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="form col-md-12">
                                <div class="form-group">
                                    <label>Policy Category</label>
                                    <select class="form-control" name="category">
                                        @foreach($categories as $key => $val)
                                            <option value="{{$val->id_category}}">{{$val->name_category}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Policy Name</label>
                                    <input type="text" class="form-control" name="topic" required/>
                                </div>
                                <div class="form-group">
                                    <label>Division</label>
                                    <select class="form-control" name="location">
                                        <option value='all'>All</option>
                                        <option value='operation'>Operation</option>
                                        <option value='technical'>Technical Engineering</option>
                                        <option value='marketing'>Marketing</option>
                                        <option value='procurement'>Procurement</option>
                                        <option value='finance'>Finance</option>
                                        <option value='hrd'>HRD</option>
                                        <option value='it'>IT</option>
                                    </select>
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
