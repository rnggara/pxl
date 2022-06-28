<div class="col-md-4">
    <div class="card card-custom gutter-b">
        <div class="card-body">
            <div class="d-flex row">
                <div class="row col-md-12">
                    <div class="flex-shrink-0 mx-auto">
                        <div class="symbol symbol-circle symbol-50 symbol-lg-100 symbol-info">
                            <span class="symbol-label"><i class="fa fa-folder text-white font-size-h1-xl"></i></span>
                        </div>
                    </div>
                </div>
                <div class="row col-md-12 mt-8">
                    <div class="flex-shrink-0 mx-auto col-md-8">
                        <h3 class="card-label text-center">{{$lead->leads_name}}</h3>
                        <div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: {{number_format($lead->progress, 0)}}%;" aria-valuenow="{{number_format($lead->progress, 0)}}" aria-valuemin="0" aria-valuemax="100">{{number_format($lead->progress, 0)}}%</div>
                                    </div>
                                </div>
                            </div>
                            <span class="form-text text-center text-dark-75 mt-6 font-weight-boldest font-size-h2-xl" id="progress-text">Progress : {{number_format($lead->progress)}}%.</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <label class="col-md-12 card-label font-weight-bold font-size-h3-lg">Client Info</label>
                <hr>
                <div class="col-md-12">
                    <div class="row">
                        <label class="col-md-4 card-label font-weight-bold">Company Name</label>
                        <label class="col-md-8 card-label">{{$data_client['client_name'][$lead->id_client]}}</label>
                    </div>
                    <div class="row">
                        <label class="col-md-4 card-label font-weight-bold">Company Address</label>
                        <label class="col-md-8 card-label">{{$data_client['address'][$lead->id_client]}}</label>
                    </div>
                    <div class="row">
                        <label class="col-md-4 card-label font-weight-bold">Company Phone Number</label>
                        <label class="col-md-8 card-label">{{$data_client['phone'][$lead->id_client]}}</label>
                    </div>
                    <div class="row">
                        <label class="col-md-4 card-label font-weight-bold">PIC Name</label>
                        <label class="col-md-8 card-label">{{$data_client['pic'][$lead->id_client]}}</label>
                    </div>
                    <div class="row">
                        <label class="col-md-4 card-label font-weight-bold">PIC Phone Number</label>
                        <label class="col-md-8 card-label">{{$data_client['pic_number'][$lead->id_client]}}</label>
                    </div>
                </div>
                @if (!empty($lead->other_client))
                    @php
                        $js = json_decode($lead->other_client, true);
                    @endphp
                    @if (!empty($js))
                        <div class="col-md-12">
                            <hr>
                            <div class="row">
                                <label class="col-md-4 card-label font-weight-bold font-size-h3-lg">Other Client</label>
                            </div>
                            <hr>
                        </div>
                        @foreach ($js as $item)
                            <div class="col-md-12">
                                <div class="row">
                                    <label class="col-md-4 card-label font-weight-bold">Company Name</label>
                                    <label class="col-md-8 card-label">{{$data_client['client_name'][$item]}}</label>
                                </div>
                                <div class="row">
                                    <label class="col-md-4 card-label font-weight-bold">Company Address</label>
                                    <label class="col-md-8 card-label">{{$data_client['address'][$item]}}</label>
                                </div>
                                <hr>
                            </div>
                        @endforeach
                    @endif
                @endif
            </div>
            <div class="separator separator-solid separator-border-2 separator-info mt-5 mb-5"></div>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-2">
                            <label for="" class="card-label font-weight-bold">Referrals </label>
                            @if(\Illuminate\Support\Facades\Auth::user()->username == $lead->created_by)
                                <button type="button" class="btn btn-primary btn-xs btn-icon" data-toggle="modal" data-target="#referralModal"><i class="fa fa-pencil-alt"></i></button>
                                <div class="modal fade" id="referralModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered " role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Referrals</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <i aria-hidden="true" class="ki ki-close"></i>
                                                </button>
                                            </div>
                                            <form method="POST" action="{{URL::route('leads.edit_referral')}}" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="form-group row">
                                                        <label for="" class="col-form-label col-md-4">Edit Referrals</label>
                                                        <div class="col-md-8">
                                                            <select name="referral[]" multiple class="form-control select2" id="" required>
                                                                <option value="">&nbsp;</option>
                                                                {{--get from users--}}
                                                                @foreach($users as $user)
                                                                    <option value="{{$user->id}}" {{($lead->referral != null && in_array($user->id, json_decode($lead->referral))) ? "SELECTED" : ""}}>{{$user->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <input type="hidden" name="id_leads" value="{{$lead->id}}">
                                                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary font-weight-bold">
                                                        <i class="flaticon-upload"></i>
                                                        Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-10">
                            @if(empty($lead->referral))
                                <label for="" class="card-label font-weight-bold"><span class="label label-inline label-danger">N/A</span></label>
                            @else
                                @foreach(json_decode($lead->referral) as $item)
                                    <div class="row">
                                        <label for="" class="card-label font-weight-bold"><span class="label label-inline label-success">{{$user_name[$item]}}</span></label>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="separator separator-solid separator-border-2 separator-info mt-5 mb-5"></div>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-2">
                            <label for="" class="card-label font-weight-bold">Partners </label>
                            @if(\Illuminate\Support\Facades\Auth::user()->username == $lead->created_by)
                                <button type="button" class="btn btn-primary btn-xs btn-icon" data-toggle="modal" data-target="#partnerModal"><i class="fa fa-pencil-alt"></i></button>
                                <div class="modal fade" id="partnerModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered " role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Partner</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <i aria-hidden="true" class="ki ki-close"></i>
                                                </button>
                                            </div>
                                            <form method="POST" action="{{URL::route('leads.edit_partner')}}" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="form-group row">
                                                        <label for="" class="col-form-label col-md-4">Edit Partners</label>
                                                        <div class="col-md-8">
                                                            <select name="partner[]" multiple class="form-control select2" id="" required>
                                                                <option value="">&nbsp;</option>
                                                                {{--get from users--}}
                                                                @foreach($users as $user)
                                                                    <option value="{{$user->id}}" {{($lead->partner != null && in_array($user->id, json_decode($lead->partner))) ? "SELECTED" : ""}}>{{$user->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <input type="hidden" name="id_leads" value="{{$lead->id}}">
                                                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary font-weight-bold">
                                                        <i class="flaticon-upload"></i>
                                                        Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-10">
                            @if(empty($lead->partner))
                                <label for="" class="card-label font-weight-bold"><span class="label label-inline label-danger">N/A</span></label>
                            @else
                                @foreach(json_decode($lead->partner) as $item)
                                <div class="row">
                                    <label for="" class="card-label font-weight-bold"><span class="label label-inline label-success">{{$user_name[$item]}}</span></label>
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="separator separator-solid separator-border-2 separator-info mt-5 mb-5"></div>
            <div class="row">
                <div class="col-md-12">
                    @if(empty($associates) || count($step) != count($associates))
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-custom alert-outline-danger">
                                    <div class="alert-icon">
                                        <i class="flaticon-warning"></i>
                                    </div>
                                    <div class="alert-text">
                                        &nbsp;Please assign Associate for each step
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                    @endif
                    <div class="row">
                        <div class="col-md-2">
                            <label for="" class="card-label font-weight-bold">Associates </label>
                            <button type="button" class="btn btn-primary btn-xs btn-icon" data-toggle="modal" data-target="#contributorsModal"><i class="fa fa-pencil-alt"></i></button>
                            <div class="modal fade" id="contributorsModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered " role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Edit Associates</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <i aria-hidden="true" class="ki ki-close"></i>
                                            </button>
                                        </div>
                                        <form method="POST" action="{{URL::route('leads.add_contributors')}}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-body">
                                                @foreach($step as $keyStep => $itemStep)
                                                    <div class="form-group row">
                                                        <label for="" class="col-form-label col-md-4">{{$itemStep['title']}}</label>
                                                        <div class="col-md-8">
                                                            <select name="associates[{{$keyStep}}][]" multiple class="form-control select2">
                                                                <option value="">&nbsp;</option>
                                                                {{--get from users--}}
                                                                @foreach($users as $user)
                                                                    <option value="{{$user->id}}" {{(isset($associates[$keyStep]) && $associates[$keyStep]->id_user && in_array($user->id, json_decode($associates[$keyStep]->id_user))) ? "SELECTED" : ""}}>{{$user->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <div class="modal-footer">
                                                <input type="hidden" name="id_leads" value="{{$lead->id}}">
                                                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary font-weight-bold">
                                                    <i class="flaticon-upload"></i>
                                                    Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-10">
                            @foreach($step as $keyStep => $itemStep)
                                <div class="row">
                                    <label for="" class="col-md-3 font-weight-bold">{{$itemStep['title']}}</label>
                                    <label for="" class="col-md-1">:</label>
                                    <label for="" class="col-md-8">
                                        @if(empty($associates))
                                            <span class="label label-inline">N/A</span>
                                        @else
                                            @if($associates[$keyStep]->id_user != null)
                                                @foreach(json_decode($associates[$keyStep]->id_user) as $itemContributor)
                                                    <div class="mb-2">
                                                        <span class="label label-inline label-info">{{$user_name[$itemContributor]}}</span>
                                                    </div>
                                                @endforeach
                                            @else
                                                <span class="label label-inline">N/A</span>
                                            @endif
                                        @endif
                                    </label>
                                </div>
                                <hr>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="separator separator-solid separator-border-2 separator-info mt-5 mb-5"></div>
            <div class="row">
                <label class="col-md-3 card-label font-weight-bold">Leads Description</label>
                <div class="col-md-9">
                    {{strip_tags($lead->description)}}
                </div>
            </div>
            <div class="separator separator-solid separator-border-2 separator-info mt-5 mb-5"></div>

        </div>
    </div>
</div>
