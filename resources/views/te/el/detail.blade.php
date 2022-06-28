@extends('layouts.template')
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.8.0/viewer.min.css" integrity="sha512-i7JFM7eCKzhlragtp4wNwz36fgRWH/Zsm3GAIqqO2sjiSlx7nQhx9HB3nmQcxDHLrJzEBQJWZYQQ2TPfexAjgQ==" crossorigin="anonymous" />
@endsection
@section('content')
    <input type="hidden" id="json" value="{{$json_els}}">
    <input type="hidden" id="json_file" value="{{$file_}}">
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <a href="#" class="text-black-50">{{$elCat->category_name}}</a>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addLeads"><i class="fa fa-plus"></i>Add Item</button>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                @php
                    $cl = ['success', 'warning', 'info', 'primary', 'danger'];
                    $ind = 0;
                @endphp
                @foreach ($els as $key => $item)
                @php
                $next_mt = null;
                $notif_mt = "<span class='label label-inline label-secondary'>N/A<span>";
                if(isset($mt[$item->id])){
                    $next_mt = $mt[$item->id][0];
                    $date1 = date_create(date("Y-m-d"));
                    $date2 = date_create($next_mt);
                    $diff = date_diff($date1, $date2);
                    $m = $diff->format("%m");
                    $d = $diff->format("%d");
                    if($m == 0 || ($m == 1 && $d == 0)){
                        $dmt = date("d/m/Y", strtotime($next_mt));
                        $notif_mt = "<span class='label label-inline label-warning'>$dmt<span>";
                    }
                }
            @endphp
            <div class="col-md-3 col-sm-12 mx-auto">
                <div class="card card-custom bg-light-{{ $cl[rand(0,3)] }} gutter-b card-stretch">
                    <div class="card-header">
                        <h3 class="card-title"><a href="{{route('te.el.view_item', $item->id)}}">{{$item->subject}}</a></h3>
                        <div class="card-toolbar">
                        </div>
                    </div>
                    <div class="card-body">
                        <p>Serial Number : <span class="label label-inline label-primary">{{$item->serial_number}}</span></p>
                        <p>{{ ($elCat->tag == "SEP" || $elCat->tag == "SCRB") ? "Dimension" : "Capacity" }} : {{$item->param1}}</p>
                        @if ($elCat->tag == "SEP")
                            <p>Design Pressure : {{$item->param2}}</p>
                        @endif
                        <p>Status :
                            @if($item->status == 1)
                                <span class="label label-success label-inline">Ready</span>
                            @else
                                <span class="label label-danger label-inline">Not Ready</span>
                            @endif
                        </p>
                        <p>Next Maintenance : {!! $notif_mt !!}</p>
                        <div class="row mt-5">
                            <div class="col-12 text-right">
                                <button type="button" class="btn btn-primary btn-xs btn-icon" onclick="button_edit('{{$key}}')"><i class="fa fa-edit"></i></button>
                                <button type="button" class="btn btn-danger btn-xs btn-icon" onclick="button_delete('{{$item->id}}')"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                @endforeach
            </div>
            <div class="row">
                <div class="col-md-12 mx-auto">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                            <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th nowrap="nowrap" class="text-left">Label</th>
                                <th nowrap="nowrap" class="text-center">Serial Number</th>
                                <th nowrap="nowrap" class="text-center">Type</th>
                                <th nowrap="nowrap" class="text-center">
                                    @if($elCat->tag == "SEP" || $elCat->tag == "SCRB")
                                        Dimension
                                    @else
                                        Capacity
                                    @endif
                                </th>
                                @if($elCat->tag == "SEP")
                                    <th nowrap="nowrap" class="text-center">Design Pressure</th>
                                @endif
                                <th nowrap="nowrap" class="text-center">Status</th>
                                <th nowrap="nowrap" class="text-center"></th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($els as $key => $val)
                                    <tr>
                                        <td align="center">{{$key + 1}}</td>
                                        <td>
                                            <button type="button" class="btn btn-xs btn-primary" onclick="view_items({{$val->id}})">{{$val->subject}}</button>
                                        </td>
                                        <td align="center">
                                            <span class="label label-inline label-primary">{{$val->serial_number}}</span>
                                        </td>
                                        <td align="center">
                                            @if($val->type == 1)
                                                Main Equipment
                                            @elseif($val->type == 2)
                                                Accessories
                                            @elseif(($val->type == 3))
                                                Safety Equipment
                                            @endif
                                        </td>
                                        <td align="center">
                                            {{$val->param1}}
                                        </td>
                                        @if($elCat->tag == "SEP")
                                            <td align="center">{{$val->param2}}</td>
                                        @endif
                                        <td align="center">
                                            @if($val->status == 1)
                                                <span class="label label-success label-inline">Ready</span>
                                            @else
                                                <span class="label label-danger label-inline">Not Ready</span>
                                            @endif
                                        </td>
                                        <td align="center">
                                            <button type="button" class="btn btn-primary btn-xs btn-icon" onclick="button_edit('{{$key}}')"><i class="fa fa-edit"></i></button>
                                            <button type="button" class="btn btn-danger btn-xs btn-icon" onclick="button_delete('{{$val->id}}')"><i class="fa fa-trash"></i></button>
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
    <div class="modal fade" id="addLeads" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="POST" action="{{URL::route('te.el.add')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Label</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="label" placeholder="Label" required/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Type</label>
                                    <div class="col-md-8">
                                        <select name="type" class="form-control select2" id="" required>
                                            <option value="">Select Type</option>
                                            <option value="1">Main Equipment</option>
                                            <option value="2">Accessories</option>
                                            <option value="3">Safety Equipment</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4" id="param1-label"></label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="param1" required/>
                                    </div>
                                </div>
                                <div class="form-group row" id="param2">
                                    <label class="col-form-label col-md-4" id="param2-label">Design Pressure</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="param2"/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">COI Expiry</label>
                                    <div class="col-md-8">
                                        <input type="date" class="form-control" name="coi_expiry" placeholder="COI Expiry" required/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Status</label>
                                    <div class="col-md-8">
                                        <select name="status" class="form-control select2" id="" required>
                                            <option value="">Select Status</option>
                                            <option value="1">Ready</option>
                                            <option value="2">Not Ready</option>
                                        </select>
                                    </div>
                                </div>
                                <div id="target-separator">
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-4 col-form-label">Capacity Oil</label>
                                        <div class="col-md-8">
                                            <input type="text" name="capacity_oil" placeholder="Capacity Oil" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-4 col-form-label">Capacity Water</label>
                                        <div class="col-md-8">
                                            <input type="text" name="capacity_water" placeholder="Capacity Water" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-4 col-form-label">Capacity Gas</label>
                                        <div class="col-md-8">
                                            <input type="text" name="capacity_gas" placeholder="Capacity Gas" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-4 col-form-label">Retention Time</label>
                                        <div class="col-md-8">
                                            <input type="text" name="retention_time" placeholder="Retention Time" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">File COI/Calibration/COC</label>
                                    <div class="col-md-8">
                                        <div class="custom-file">
                                            <input type="file" name="coi_file" class="custom-file-input"/>
                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Thumbnail</label>
                                    <div class="col-md-8">
                                        <div class="custom-file">
                                            <input type="file" name="thumbnail" class="custom-file-input"/>
                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Drawing</label>
                                    <div class="col-md-8">
                                        <div class="custom-file">
                                            <input type="file" name="drawing" class="custom-file-input"/>
                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Data Sheet</label>
                                    <div class="col-md-8">
                                        <div class="custom-file">
                                            <input type="file" name="datasheet" class="custom-file-input"/>
                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Description</label>
                                    <div class="col-md-8">
                                        <textarea name="desc" class="form-control" id="" cols="30" rows="10"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <input type="hidden" name="id_category" value="{{$elCat->id}}">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" id="btn-save-leads" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editCategory" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="POST" action="{{URL::route('te.el.update')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Label</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="label" id="el-label" placeholder="Label" required/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Type</label>
                                    <div class="col-md-8">
                                        <select name="type" class="form-control select2" id="el-type" required>
                                            <option value="">Select Type</option>
                                            <option value="1">Main Equipment</option>
                                            <option value="2">Accessories</option>
                                            <option value="3">Safety Equipment</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4" id="edit-param1-label"></label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="el-param1" name="param1" required/>
                                    </div>
                                </div>
                                <div class="form-group row" id="edit-param2">
                                    <label class="col-form-label col-md-4" id="param2-label">Design Pressure</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="el-param2" name="param2" required/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">COI Expiry</label>
                                    <div class="col-md-8">
                                        <input type="date" class="form-control" id="el-coi_expiry" name="coi_expiry" placeholder="COI Expiry" required/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Status</label>
                                    <div class="col-md-8">
                                        <select name="status" class="form-control select2" id="el-status" required>
                                            <option value="">Select Status</option>
                                            <option value="1">Ready</option>
                                            <option value="2">Not Ready</option>
                                        </select>
                                    </div>
                                </div>
                                <div id="edit-target-separator">
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-4 col-form-label">Capacity Oil</label>
                                        <div class="col-md-8">
                                            <input type="text" name="capacity_oil" id="el-coil" placeholder="Capacity Oil" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-4 col-form-label">Capacity Water</label>
                                        <div class="col-md-8">
                                            <input type="text" name="capacity_water" id="el-cwater" placeholder="Capacity Water" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-4 col-form-label">Capacity Gas</label>
                                        <div class="col-md-8">
                                            <input type="text" name="capacity_gas" id="el-cgas" placeholder="Capacity Gas" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-4 col-form-label">Retention Time</label>
                                        <div class="col-md-8">
                                            <input type="text" name="retention_time" id="el-rtime" placeholder="Retention Time" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">File COI/Calibration/COC</label>
                                    <div class="col-md-6">
                                        <div class="custom-file">
                                            <input type="file" name="coi_file" class="custom-file-input"/>
                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="dropdown dropdown-inline ml-2" data-toggle="tooltip" title="Quick actions" data-placement="left">
                                            <a href="#" class="btn btn-icon" id="el-more-coi" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<span class="svg-icon svg-icon-success svg-icon-2x">
												<i class="flaticon-more-1 font-weight-bold"></i>
											</span>
                                            </a>
                                            <div class="dropdown-menu p-0 m-0 dropdown-menu-md dropdown-menu-right">
                                                <!--begin::Naviigation-->
                                                <ul class="navi">
                                                    <li class="navi-separator mb-3 opacity-70"></li>
                                                    <li class="navi-item" id="el-view-coi">
                                                        <a href="#" class="navi-link" onclick="button_view('img-coi')">
														<span class="navi-icon">
															<i class="flaticon-eye"></i>
														</span>
                                                            <span class="navi-text">View</span>
                                                        </a>
                                                        <img src="" alt="" class="image-show" id="img-coi">
                                                    </li>
                                                    <li class="navi-item">
                                                        <a href="#" class="navi-link" id="el-download-coi" target="_blank">
														<span class="navi-icon">
															<i class="navi-icon flaticon-download-1"></i>
														</span>
                                                            <span class="navi-text">Download</span>
                                                        </a>
                                                    </li>
                                                    <li class="navi-item">
                                                        <a href="#" class="navi-link" id="el-delete-coi">
														<span class="navi-icon">
															<i class="navi-icon flaticon-delete-1"></i>
														</span>
                                                            <span class="navi-text">Delete</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                                <!--end::Naviigation-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Thumbnail</label>
                                    <div class="col-md-6">
                                        <div class="custom-file">
                                            <input type="file" name="thumbnail" class="custom-file-input"/>
                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="dropdown dropdown-inline ml-2" data-toggle="tooltip" title="Quick actions" data-placement="left">
                                            <a href="#" class="btn btn-icon" id="el-more-thumbnail" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<span class="svg-icon svg-icon-success svg-icon-2x">
												<i class="flaticon-more-1 font-weight-bold"></i>
											</span>
                                            </a>
                                            <div class="dropdown-menu p-0 m-0 dropdown-menu-md dropdown-menu-right">
                                                <!--begin::Naviigation-->
                                                <ul class="navi">
                                                    <li class="navi-separator mb-3 opacity-70"></li>
                                                    <li class="navi-item" id="el-view-thumbnail">
                                                        <a href="#" class="navi-link" onclick="button_view('img-thumbnail')">
														<span class="navi-icon">
															<i class="flaticon-eye"></i>
														</span>
                                                            <span class="navi-text">View</span>
                                                        </a>
                                                        <img src="" alt="" class="image-show" id="img-thumbnail">
                                                    </li>
                                                    <li class="navi-item">
                                                        <a href="#" class="navi-link" id="el-download-thumbnail" target="_blank">
														<span class="navi-icon">
															<i class="navi-icon flaticon-download-1"></i>
														</span>
                                                            <span class="navi-text">Download</span>
                                                        </a>
                                                    </li>
                                                    <li class="navi-item">
                                                        <a href="#" class="navi-link" id="el-delete-thumbnail">
														<span class="navi-icon">
															<i class="navi-icon flaticon-delete-1"></i>
														</span>
                                                            <span class="navi-text">Delete</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                                <!--end::Naviigation-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Drawing</label>
                                    <div class="col-md-6">
                                        <div class="custom-file">
                                            <input type="file" name="drawing" class="custom-file-input"/>
                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="dropdown dropdown-inline ml-2" data-toggle="tooltip" title="Quick actions" data-placement="left">
                                            <a href="#" class="btn btn-icon" id="el-more-drawing" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<span class="svg-icon svg-icon-success svg-icon-2x">
												<i class="flaticon-more-1 font-weight-bold"></i>
											</span>
                                            </a>
                                            <div class="dropdown-menu p-0 m-0 dropdown-menu-md dropdown-menu-right">
                                                <!--begin::Naviigation-->
                                                <ul class="navi">
                                                    <li class="navi-separator mb-3 opacity-70"></li>
                                                    <li class="navi-item" id="el-view-drawing">
                                                        <a href="#" class="navi-link" onclick="button_view('img-drawing')">
														<span class="navi-icon">
															<i class="flaticon-eye"></i>
														</span>
                                                            <span class="navi-text">View</span>
                                                        </a>
                                                        <img src="" alt="" class="image-show" id="img-drawing">
                                                    </li>
                                                    <li class="navi-item">
                                                        <a href="#" class="navi-link" id="el-download-drawing" target="_blank">
														<span class="navi-icon">
															<i class="navi-icon flaticon-download-1"></i>
														</span>
                                                            <span class="navi-text">Download</span>
                                                        </a>
                                                    </li>
                                                    <li class="navi-item">
                                                        <a href="#" class="navi-link" id="el-delete-drawing">
														<span class="navi-icon">
															<i class="navi-icon flaticon-delete-1"></i>
														</span>
                                                            <span class="navi-text">Delete</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                                <!--end::Naviigation-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Data Sheet</label>
                                    <div class="col-md-6">
                                        <div class="custom-file">
                                            <input type="file" name="datasheet" class="custom-file-input"/>
                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="dropdown dropdown-inline ml-2" data-toggle="tooltip" title="Quick actions" data-placement="left">
                                            <a href="#" class="btn btn-icon" id="el-more-data_sheet" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<span class="svg-icon svg-icon-success svg-icon-2x">
												<i class="flaticon-more-1 font-weight-bold"></i>
											</span>
                                            </a>
                                            <div class="dropdown-menu p-0 m-0 dropdown-menu-md dropdown-menu-right">
                                                <!--begin::Naviigation-->
                                                <ul class="navi">
                                                    <li class="navi-separator mb-3 opacity-70"></li>
                                                    <li class="navi-item" id="el-view-data_sheet">
                                                        <a href="#" class="navi-link" onclick="button_view('img-data_sheet')">
														<span class="navi-icon">
															<i class="flaticon-eye"></i>
														</span>
                                                            <span class="navi-text">View</span>
                                                        </a>
                                                        <img src="" alt="" class="image-show" id="img-data_sheet">
                                                    </li>
                                                    <li class="navi-item">
                                                        <a href="#" class="navi-link" id="el-download-data_sheet" target="_blank">
														<span class="navi-icon">
															<i class="navi-icon flaticon-download-1"></i>
														</span>
                                                            <span class="navi-text">Download</span>
                                                        </a>
                                                    </li>
                                                    <li class="navi-item">
                                                        <a href="#" class="navi-link" id="el-delete-data_sheet">
														<span class="navi-icon">
															<i class="navi-icon flaticon-delete-1"></i>
														</span>
                                                            <span class="navi-text">Delete</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                                <!--end::Naviigation-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Description</label>
                                    <div class="col-md-8">
                                        <textarea name="desc" class="form-control" id="el-description" cols="30" rows="10"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_el" id="el-id">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="viewItems" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content" id="modalView">

            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.8.0/viewer.js" integrity="sha512-Yuv1HMS0DNKLgd09CcwVjsDkfEzaBv9CItoCgfTfbglWFXLenu7BoZylWuUtfvvZE55/j8fkaCVtWz1ZtxCo+Q==" crossorigin="anonymous"></script>

    <script>
        // import Viewer from 'viewerjs/dist/viewer.esm';

        function view_items(x) {
            $("#modalView").html(" ")
            $("#viewItems").modal('show')
            $.ajax({
                url: "{{route('te.el.items_detail')}}/" + x,
                type: "get",
                cache: false,
                success: function(response){
                    $("#modalView").append(response)
                }
            })
        }

        function button_view(x){
            var Viewer = window.Viewer
// View an image
            var viewer = new Viewer(document.getElementById(x), {
                modal: true,
                toolbar: false,
                viewed() {
                    viewer.zoom(0.1);
                },
            });

            if (viewer){
                viewer.destroy();
                var viewer = new Viewer(document.getElementById(x), {
                    modal: true,
                    toolbar: false,
                    viewed() {
                        viewer.zoom(0.1);
                    },
                });
                viewer.show()
            } else {
                viewer.show()
            }
        }

        function button_edit(x) {
            var json_cat = $("#json").val()
            var cat = JSON.parse(json_cat)
            // console.log(file_[cat[x].coi])
            $("#editCategory").modal('show')

            $("#el-label").val(cat[x].subject)
            $("#el-type").val(cat[x].type).trigger("change")
            $("#el-param1").val(cat[x].param1)
            $("#el-coi_expiry").val(cat[x].coi_expiry)
            $("#el-id").val(cat[x].id)
            $("#el-status").val(cat[x].status).trigger("change")
            $("#el-description").val(cat[x].description)
            @if($elCat->tag == "SEP" || $elCat->tag == "SCRB")
                $("#el-param2").val(cat[x].param2)
            @endif
            @if($elCat->tag == "SEP")
                var additional = JSON.parse(cat[x].additional_information)
                $("#el-coil").val(additional.capacity_oil)
                $("#el-cgas").val(additional.capacity_gas)
                $("#el-cwater").val(additional.capacity_water)
                $("#el-rtime").val(additional.retention_time)
            @endif

            isFileExist(cat[x], "coi")
            isFileExist(cat[x], "drawing")
            isFileExist(cat[x], "thumbnail")
            isFileExist(cat[x], "data_sheet")
        }

        function isFileExist(x, y){
            var json_file = $("#json_file").val()
            var file_ = JSON.parse(json_file)
            if (x[y] == null || x[y] === ""){
                $("#el-more-"+y).hide()
            } else {
                var file = "../../../../public_html/" + file_[x[y]]
                $("#img-"+y).attr("src",  "../../../../public_html/" + file_[x[y]])
                var ext = file.split(".")
                var validateExt = ['gif', 'jpeg', 'png', 'jpg', 'tiff'];
                if (!validateExt.includes(ext[ext.length-1])){
                    $("#el-view-"+y).hide()
                }
                $("#el-download-"+y).attr("href", "{{route('download')}}/"+x[y])
                $("#el-delete-"+y).on("click", function () {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url : "{{URL::route('te.el.deleteFile')}}/" + x['id'] + "/" + y,
                                type: "get",
                                dataType: "json",
                                cache: "false",
                                success: function(response){
                                    if (response.error == 0){
                                        $("#el-more-"+y).hide()
                                    } else {
                                        Swal.fire('Error occured', 'Please contact your administrator!', 'error')
                                    }
                                }
                            })
                        }
                    })
                })
            }
        }

        function button_delete(x){
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{URL::route('te.el.delete')}}/" + x,
                        type: "get",
                        dataType: "json",
                        cache: "false",
                        success: function(response){
                            if (response.error == 0){
                                location.reload()
                            } else {
                                Swal.fire('Error occured', 'Please contact your administrator!', 'error')
                            }
                        }
                    })
                }
            })
        }

        function button_delete_file(x, y){

        }

        $(document).ready(function () {
            $(".image-show").attr("style", "max-width: '1280px'")
            $(".image-show").hide()

            var target = $('#target-separator');
            var target_edit = $('#edit-target-separator');

            $("select.select2").select2({
                width: "100%"
            })

            var tag = "{{$elCat->tag}}";

            if (tag === "SEP" || tag === "SCRB"){
                console.log("tes")
                switch (tag) {
                    case "SEP":
                        target.show()
                        target_edit.show()
                        break;
                    case "SCRB":
                        target.hide()
                        target_edit.hide()
                        break;
                }
                $("#param2").show()
                $("#edit-param2").show()
                $("#param1-label").text("Dimension")
                $("#edit-param1-label").text("Dimension")
            } else {
                target.hide()
                console.log("hm")
                $("#param2").hide()
                $("#edit-param2").hide()
                $("#param1-label").text("Capacity")
                $("#edit-param1-label").text("Capacity")
            }


            $('.display').DataTable({
                responsive: true,
            });
        })

    </script>
@endsection
