<div class="modal-header">
    <h3 class="modal-title">Edit Project</h3>
</div>
<div class="modal-body">
    <form class="form" method="post" action="{{route('marketing.project.update')}}"
          enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <br>
                <h4>Basic Info</h4>
                <hr>
                <div class="form-group">
                    <label>Project Code</label>
                    <input type="text" class="form-control" name="prj_code" value="{{$prj->prj_code}}"
                           readonly/>
                </div>
                <div class="form-group">
                    <label>Project Name</label>
                    <input type="text" class="form-control" name="prj_name" placeholder="Project Name"
                           value="{{$prj->prj_name}}" required/>
                </div>
                <div class="form-group">
                    <label>Project prefix</label>
                    <input type="text" class="form-control" name="prefix" value="{{$prj->prefix}}"
                           placeholder="Project Name" required/>
                </div>
                <div class="form-group">
                    <label>Project Category</label>
                    <select class="form-control" name="category" value="{{$prj->category}}" required>
                        <option value="cost" {{ ($prj->category == "cost") ? "SELECTED" : "" }}>COST</option>
                        <option value="sales" {{ ($prj->category == "sales") ? "SELECTED" : "" }}>SALES</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Project Value</label>
                    <input type="number" class="form-control" name="prj_value" value="{{$prj->value}}"
                           placeholder="" required/>
                </div>
                <div class="alert alert-warning" role="alert">
                    <i class="fa fa-exclamation-circle text-white" aria-hidden="true"></i>
                    Please note that Project Value will be related to the amount that will be generated on
                    invoice out
                </div>
                <div class="form-group">
                    <label>Project Client</label>
                    <select class="form-control" name="client" required>
                        @foreach($clients as $key => $client)
                            <option value="{{$client->id}}"
                                    @if($client->id == $prj->id_client) selected @endif>{{$client->company_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <br>
                <h4>Project Detail</h4>
                <hr>
                <div class="form-group">
                    <label>Project</label>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <input type="date" class="form-control" name="prj_start"
                                   value="{{$prj->start_time}}" placeholder="" required>
                            <small><i>start</i></small>
                        </div>
                        <div class="col-sm-6">
                            <input type="date" class="form-control" name="prj_end" value="{{$prj->end_time}}"
                                   placeholder="" required>
                            <small><i>end</i></small>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Project Currency</label>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <select class="form-control" name="currency" required>
                                @foreach($arrCurrency as $key2 => $value)
                                    <option value="{{$key2}}"
                                            @if($key2 == $prj->currency) selected @endif>{{$key2}}
                                        - {{$value}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Project Address</label>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <textarea class="form-control" name="address" required>{{$prj->address}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Longitude</label>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <input type="text" class="form-control number-geo" name="longitude" value="{{ $prj->longitude }}">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Latitude</label>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <input type="text" class="form-control number-geo" name="latitude" value="{{ $prj->latitude }}">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>File Quotation List</label>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <select class="form-control" name="quotation" required>
                                <option value="1">Q1</option>
                                <option value="2">Q2</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Attach WO</label>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <input type='file' name='wo_attach'>
                            <span class="form-text text-muted">Max file size is 500KB </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Agreement #</label>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <input type="text" class="form-control" name="agreement"
                                   value="{{$prj->agreement_number}}" placeholder="" required/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Agreement Title</label>
                    <div class="form-group row">
                        <div class="col-sm-12">
                                    <textarea class="form-control" name="agreement_title"
                                              required>{{$prj->agreement_title}}</textarea>
                        </div>
                    </div>
                </div>

                <br>
                <br>
                <br>
                <h4>Financial Transport</h4>
                <hr>

                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 control-label">Travel</label>
                    <div class="col-sm-12">
                        <input type="number" class="form-control" name="transport" value="{{$prj->transport}}"
                               required placeholder="">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 control-label">Taxi</label>
                    <div class="col-sm-12">
                        <input type="number" class="form-control" name="taxi" value="{{$prj->taxi}}" required
                               placeholder="">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 control-label">Car Rent</label>
                    <div class="col-sm-12">
                        <input type="number" class="form-control" name="rent" value="{{$prj->rent}}"
                               placeholder="" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 control-label">Airport Tax</label>
                    <div class="col-sm-12">
                        <input type="hidden" name="id" value="{{$prj->id}}">
                        <input type="number" class="form-control" name="airtax" value="{{$prj->airtax}}"
                               placeholder="" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close
            </button>
            <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                <i class="fa fa-check"></i>
                Update
            </button>
        </div>
    </form>
</div>
<div class="modal-footer">

</div>
