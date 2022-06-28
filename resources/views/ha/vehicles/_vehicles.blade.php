<div class="card card-custom gutter-b">
    <div class="card-header card-header-tabs-line">
        <div>
            <ul class="nav nav-tabs nav-tabs-line">
                <li class="nav-item">
                    <a class="nav-link active" onclick="table_vehicles('all')" data-toggle="tab" href="#">All</a>
                </li>
                @foreach($category as $item)
                    <li class="nav-item">
                        <a class="nav-link" onclick="table_vehicles('{{$item->id}}')" data-toggle="tab" href="#">{{ucwords($item->name)}}</a>
                    </li>
                @endforeach
                <li class="nav-item">
                    <a class="nav-link" onclick="table_vehicles('other')" data-toggle="tab" href="#">Others</a>
                </li>
            </ul>
        </div>
        <div class="card-toolbar btn-group">
            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#addCategory"><i class="fa fa-cog"></i> Add Category</button>
            <button type="button" class="btn btn-primary" onclick="cancel_add_paper()" data-toggle="modal" data-target="#addVehicles"><i class="fa fa-plus"></i> Add Vehicle</button>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">

            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12">
                <table class="table table-bordered table-hover" id="table-vehicle">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th style="width:200%" class="text-center">Document Name</th>
                            <th class="text-center">Number Plat</th>
                            <th class="text-center">STNK NO</th>
                            <th class="text-center">STNK Holder</th>
                            <th class="text-center">BPKB NO</th>
                            <th class="text-center">VEHICLE USE BY</th>
                            <th class="text-center">LOCATION</th>
                            <th class="text-center">YEAR/CC/COLOUR</th>
                            <th class="text-center">VENDOR</th>
                            <th class="text-center">PHONE</th>
                            <th class="text-center">Expired Date</th>
                            <th class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="addCategory" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <form method="POST" action="{{route('ha.ve.add.category')}}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-form-label col-md-4">Name</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" required name="category_name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-4">View</label>
                                <div class="col-md-8">
                                    <select name="view[]" class="form-control" id="view-cat" multiple></select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-hover table-bordered display">
                                <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Category Name</th>
                                    <th class="text-center">View</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($category as $i => $item)
                                    <tr>
                                        <td align="center">{{$i+1}}</td>
                                        <td align="center">{{ucwords($item->name)}}</td>
                                        <td align="center">
                                            @if(!empty($item->view))
                                                @foreach(json_decode($item->view) as $view)
                                                    {!! (isset($div[$view])) ? "<span class='label label-inline label-primary'>".$div[$view]."</span>" : "" !!}
                                                @endforeach
                                            @endif
                                        </td>
                                        <td align="center">
                                            <a href="{{route('ha.ve.delete.category', $item->id)}}" class="btn btn-xs btn-icon btn-danger"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                    <button type="submit" id="btn-save-leads" class="btn btn-primary font-weight-bold">
                        <i class="fa fa-check"></i>
                        Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="addVehicles" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Vehicle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <form method="POST" action="{{route('ha.ve.add.vehicle')}}">
                @csrf
                <div class="modal-body">
                    <div class="row" id="add-vehicle">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-form-label col-md-4">Vehicle Name</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" required name="name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-4">Type</label>
                                <div class="col-md-8">
                                    <select name="category" id="" class="form-control select2" required>
                                        <option value="">Select Category</option>
                                        @foreach($category as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-4">Vendor</label>
                                <div class="col-md-8">
                                    <select name="vendor" id="" class="form-control select2">
                                        <option value="">Select Vendor</option>
                                        @foreach($vendors as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-4">Specification</label>
                                <div class="col-md-8">
                                    <textarea name="specification" id="ve-spec" cols="30" rows="10"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-4">No BPKB</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" required name="bpkb">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-4">BPKB Name</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" required name="bpkb_name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-4">STNK</label>
                                <div class="col-md-8">
                                    <select name="stnk" id="stnk" class="form-control">

                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row" id="add-paper">
                        <div class="col-md-12">
                            <h3>Add Paper</h3>
                            <div class="form-group row">
                                <label for="" class="col-form-label col-md-4">Police Number</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control required" id="paper-name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-form-label col-md-4">STNK Number</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control required" id="paper-number">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-form-label col-md-4">STNK Date</label>
                                <div class="col-md-8">
                                    <input type="date" class="form-control required" id="paper-date">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-form-label col-md-4">STNK Value</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control number required" id="paper-value">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-form-label col-md-4">STNK Owner</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control required" id="paper-owner">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-form-label col-md-4">STNK Holder</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control required" id="paper-holder">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-form-label col-md-4">STNK Specifications</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" id="paper-spec" cols="30" rows="10"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-form-label col-md-4">STNK Year/Color</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control required" id="stnk-y-c">
                                </div>
                            </div>
                            <div class="form-group row">
                                <span class="col-md-4"></span>
                                <div class="col-md-8 text-right">
                                    <button type="button" onclick="cancel_add_paper()" class="btn btn-sm btn-light-dark">Cancel</button>
                                    <button type="button" onclick="post_paper()" class="btn btn-sm btn-light-success">Save Paper</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                    <button type="submit" id="btn-save-leads" class="btn btn-primary font-weight-bold">
                        <i class="fa fa-check"></i>
                        Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="editVehicles" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content" id="edit-vehicle">

        </div>
    </div>
</div>
