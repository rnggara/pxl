@extends('layouts.template')
@section('content')
    <div class="row">
        @include('leads.view_partials._side')
        <div class="col-md-8">
            <ul class="nav nav-tabs nav-tabs-line bg-white pb-5 pt-5" id="pageTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="" data-toggle="tab" href="#progress-tab">
                                <span class="nav-icon">
                                    <i class="fas fa-running"></i>
                                </span>
                        <span class="nav-text">Progress</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="" data-toggle="tab" href="#activity-tab">
                                <span class="nav-icon">
                                    <i class="fas fa-star"></i>
                                </span>
                        <span class="nav-text">Activity</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="" data-toggle="tab" href="#notes-tab" aria-controls="profile">
                                <span class="nav-icon">
                                    <i class="fas fa-sticky-note"></i>
                                </span>
                        <span class="nav-text">Notes</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="" data-toggle="tab" href="#tasks-tab" aria-controls="profile">
                                <span class="nav-icon">
                                    <i class="fas fa-tasks"></i>
                                </span>
                        <span class="nav-text">Tasks</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="" data-toggle="tab" href="#meetings-tab" aria-controls="profile">
                                <span class="nav-icon">
                                    <i class="fas fa-handshake"></i>
                                </span>
                        <span class="nav-text">Meetings</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="" data-toggle="tab" href="#files-tab" aria-controls="profile">
                                <span class="nav-icon">
                                    <i class="fas fa-file"></i>
                                </span>
                        <span class="nav-text">Files</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="" data-toggle="tab" href="#contracts-tab" aria-controls="profile">
                                <span class="nav-icon">
                                    <i class="fas fa-file-invoice"></i>
                                </span>
                        <span class="nav-text">Contracts</span>
                    </a>
                </li>
            </ul>
            <div class="tab-content mt-5" id="pageTab">
                <div class="tab-pane fade show active" id="progress-tab" role="tabpanel" aria-labelledby="progress-tab">
                    <div class="card card-custom gutter-b">
                        <div class="card-header">
                            <div class="card-title">Progress</div>
                        </div>
                    </div>
                    <div class="row col-md-10 mx-auto">
                        <?php
                        $prevI = null;
                        /** @var TYPE_NAME $step */
                        if (!empty($associates) && count($step) == count($associates)){
                            foreach ($step as $keyStep => $itemStep){
                                if ($associates[$keyStep]->file != null){
                                    $prevI = $keyStep;
                                }
                            }
                        }
                        ?>
                        @foreach($step as $keyStep => $itemStep)
                            <div class="col-md-4 mx-auto">
                                <div class="card card-custom gutter-b">
                                    @if(!isset($itemStep['isMeeting']))
                                        <div class="card-body {{(!isset($associates[$keyStep]) || $associates[$keyStep]->file_draft == null) ? "bg-danger" : (($associates[$keyStep]->resi == null) ? "bg-warning" : (($associates[$keyStep]->file == null) ? "bg-info" : "bg-success"))}} text-white text-center">
                                            <div class="row mx-auto text-center">
                                                <div class="col-md-12">
                                                    <i class="{{( !isset($associates[$keyStep]) || $associates[$keyStep]->file_draft == null) ? "flaticon-warning" : (($associates[$keyStep]->resi == null) ? "flaticon2-file" : (($associates[$keyStep]->file == null) ? "flaticon2-send-1" : "flaticon2-check-mark"))}} text-white"></i> <br>
                                                </div>
                                            </div>
                                            <span class="font-weight-bold">{{$itemStep['title']}}</span>
                                            <hr>
                                           <div class="row mx-auto text-center">
                                                <div class="col-md-12">
                                                    <span>
                                                        @if($associates[$keyStep]->file_draft == null)
                                                            Waiting
                                                        @elseif($associates[$keyStep]->resi == null)
                                                            {{$itemStep['title']}} draft has been uploaded
                                                        @elseif($associates[$keyStep]->file == null)
                                                            {{$itemStep['title']}} receipt has been uploaded
                                                        @else
                                                            {{$itemStep['title']}} completed
                                                        @endif
                                                    </span>
                                                </div>
                                           </div>
                                        </div>
                                    @else
                                        <div class="card-body {{(!isset($associates[$keyStep]) || $associates[$keyStep]->id_meeting_internal == null) ? "bg-danger" : (($associates[$keyStep]->file_draft == null) ? "bg-warning" : (($associates[$keyStep]->id_meeting_eksternal == null) ? "bg-info" : (($associates[$keyStep]->file == null) ? "bg-primary" : "bg-success")))}} text-white text-center">
                                            <div class="row mx-auto text-center">
                                                <div class="col-md-12">
                                                    <i class="{{(!isset($associates[$keyStep]) || $associates[$keyStep]->id_meeting_internal == null) ? "flaticon-warning" : (($associates[$keyStep]->file_draft == null) ? "flaticon2-file" : (($associates[$keyStep]->id_meeting_eksternal == null) ? "flaticon2-send-1" : (($associates[$keyStep]->file == null) ? "flaticon2-file" : "flaticon2-check-mark")))}} text-white"></i> <br>
                                                </div>
                                            </div>
                                            <span class="font-weight-bold">{{$itemStep['title']}}</span>
                                            <hr>
                                            <div class="row mx-auto text-center">
                                                <div class="col-md-12">
                                                    <span>
                                                        @if($associates[$keyStep]->id_meeting_internal == null)
                                                            Waiting
                                                        @elseif($associates[$keyStep]->file_draft == null)
                                                            {{$itemStep['title']}} internal created
                                                        @elseif($associates[$keyStep]->id_meeting_eksternal == null)
                                                            {{$itemStep['title']}} internal MOM file uploaded
                                                        @elseif($associates[$keyStep]->file == null)
                                                            {{$itemStep['title']}} external created
                                                        @else
                                                            {{$itemStep['title']}} external MOM file uploaded
                                                        @endif
                                                </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="card card-custom gutter-b">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8 mx-auto">
                                    @if($lead->progress < 100)
                                        <div class="col-md-12 alert alert-primary">
                                            <i class="fa fa-info-circle text-white"></i>
                                            <?php
                                            $prevKey = null;
                                            /** @var TYPE_NAME $step */
                                            if (!empty($associates) && count($step) == count($associates)){
                                                foreach ($step as $keyStep => $itemStep){
                                                    if ($associates[$keyStep]->file != null){
                                                        $prevKey = $keyStep;
                                                    }
                                                }
                                            }
                                            ?>
                                            @if(!empty($associates) && count($step) == count($associates))
                                                @foreach($step as $keyStep => $itemStep)
                                                    @if(!isset($itemStep['isMeeting']))
                                                        @if($associates[$keyStep]->file_draft == null)
                                                            @if(isset($itemStep['durations']))
                                                                <?php
                                                                $date1 = date_create(date('Y-m-d', strtotime("+".$itemStep['durations']." day", strtotime($associates[$prevKey]->file_date))));
                                                                $date3 = date('Y-m-d', strtotime("+".$itemStep['durations']." day", strtotime($associates[$prevKey]->file_date)));
                                                                // dd($date3);
                                                                $date2 = date_create(date('Y-m-d'));
                                                                $diff=date_diff($date1,$date2);
                                                                $n = $diff->format("%a");
                                                                if ($n != 0){
                                                                    echo $itemStep['title']." must be uploaded before ".date('d F Y', strtotime($date3))." (".$diff->format("%a days remaining").")";
                                                                } else {
                                                                    echo $itemStep['title']." must be uploaded to the server today!";
                                                                }
                                                                ?>
                                                            @else
                                                                Please upload {{$itemStep['title']}} signed by {{\Session::get('company_tag')}}
                                                            @endif
                                                            @if(!isset($itemStep['isFinal']))
                                                                <div class="mt-2">
                                                                    <button type="button" onclick="btn_skip('{{$keyStep}}')" class="btn btn-xs btn-light-primary"><i class="flaticon2-fast-next"></i>Skip this process</button>
                                                                </div>
                                                            @endif
                                                            @break
                                                        @elseif($associates[$keyStep]->resi == null)
                                                            {{$itemStep['title']}} signed by {{\Session::get('company_id')}} has been uploaded
                                                            @break
                                                        @elseif($associates[$keyStep]->file == null)
                                                            {{$itemStep['title']}} receipt has been uploaded
                                                            @break
                                                        @endif
                                                    @else
                                                        @if($associates[$keyStep]->id_meeting_internal == null)
                                                            @if(isset($itemStep['durations']))
                                                                <?php
                                                                $date1 = date_create(date('Y-m-d', strtotime("+".$itemStep['durations']." day", strtotime($associates[$prevKey]->file_date))));
                                                                $date2 = date_create(date('Y-m-d'));
                                                                $date3 = date('Y-m-d', strtotime("+".$itemStep['durations']." day", strtotime($associates[$prevKey]->file_date)));
                                                                $diff=date_diff($date1,$date2);
                                                                $n = $diff->format("%a");
                                                                if ($n != 0){
                                                                    echo $itemStep['title']." must be created before ".date('d  F Y', strtotime($date3))."(".$diff->format("%a days remaining").")";
                                                                } else {
                                                                    echo $itemStep['title']." must be created today!";
                                                                }
                                                                ?>
                                                                <div class="mt-2">
                                                                    <button type="button" onclick="btn_skip('{{$keyStep}}')" class="btn btn-xs btn-light-primary"><i class="flaticon2-fast-next"></i>Skip this process</button>
                                                                </div>
                                                            @else
                                                                Please upload {{$itemStep['title']}} signed by {{\Session::get('company_tag')}}
                                                            @endif
                                                            @break
                                                        @elseif($associates[$keyStep]->file_draft == null)
                                                            {{$itemStep['title']}} internal created
                                                            @break
                                                        @elseif($associates[$keyStep]->id_meeting_eksternal == null)
                                                            {{$itemStep['title']}} internal MOM file uploaded
                                                            @break
                                                        @elseif($associates[$keyStep]->file == null)
                                                            {{$itemStep['title']}} external created
                                                            @break
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @else
                                                Please assign Associate for each step
                                            @endif
                                        </div>
                                        @foreach($step as $keyStep => $itemStep)
                                            @if(!empty($associates) && count($step) == count($associates))
                                                @if($associates[$keyStep]->file == null)
                                                    <form action="{{route('leads.upload_progress', $keyStep)}}" id="form-upload" method="post" enctype="multipart/form-data">
                                                        @csrf
                                                        @if(!isset($itemStep['isMeeting']))
                                                            @if(isset($itemStep['type']))
                                                                <div class="form-group row mx-auto">
                                                                    <div class="col-md-12">
                                                                        <select name="seltype" class="form-control select2" {{($associates[$keyStep]->step_type != null) ? "disabled" : ""}} id="" required>
                                                                            <option value="">Select type</option>
                                                                            @foreach($itemStep['type'] as $itemType)
                                                                                <option value="{{$itemType}}" {{($associates[$keyStep]->step_type != null && $associates[$keyStep]->step_type == $itemType) ? "selected" : ""}}>{{ucwords($itemType)}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if($associates[$keyStep]->file_draft == null)
                                                                <div class="form-group row mx-auto">
                                                                    <div class="col-md-9 custom-file">
                                                                        <input type="file" class="form-control custom-file-input" name="file_draft" required/>
                                                                        <label class=" custom-file-label" for="customFile">Upload File Draft</label>
                                                                        <input type="hidden" name="step" value="file_draft">
                                                                    </div>
                                                                    <div class="col-md-3 btn-group">
                                                                        <input type="hidden" name="id_leads" value="{{$lead->id}}">
                                                                        <button type="submit" class="btn btn-xs btn-light-primary"><i class="fa fa-upload"></i>Upload</button>
                                                                    </div>
                                                                </div>
                                                            @elseif($associates[$keyStep]->resi == null)
                                                                <div class="form-group row mx-auto">
                                                                    <div class="col-md-12 custom-file">
                                                                        <input type="file" class="custom-file-input" name="resi_file" required/>
                                                                        <label class="custom-file-label" for="customFile">Upload File Receipt</label>
                                                                        <input type="hidden" name="step" value="resi">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row mx-auto">
                                                                    <div class="col-md-5">
                                                                        <input type="text" class="form-control" placeholder="Receipt" name="resi" required/>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <input type="number" class="form-control" placeholder="Amount" name="amount" required/>
                                                                    </div>
                                                                    <div class="col-md-3 custom-file">
                                                                        <input type="hidden" name="id_leads" value="{{$lead->id}}">
                                                                        <button type="submit" class="btn btn-xs btn-light-primary"><i class="fa fa-upload"></i>Upload</button>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                @if(isset($itemStep['isDocument']))
                                                                    <div class="form-group row mx-auto" id="add-document-file">
                                                                        <div class="col-md-9 custom-file">
                                                                            <input type="file" class="custom-file-input" name="file_document[]" multiple required/>
                                                                            <label class="custom-file-label" for="customFile">Upload Document</label>
                                                                            <input type="hidden" name="step" value="file">
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <button type="button" onclick="add_document_file()" class="btn btn-document btn-xs btn-primary"><i class="fa fa-plus"></i></button>
                                                                        </div>
                                                                    </div>
                                                                    <div id="document-div"></div>
                                                                @elseif(isset($itemStep['isFinal']))
                                                                    <div class="form-group row mx-auto">
                                                                        <div class="col-md-12">
                                                                            <input type="number" class="form-control" name="contract_value" placeholder="Contract Value" required>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                <div class="form-group row mx-auto">
                                                                    <div class="col-md-9 custom-file">
                                                                        <input type="file" class="custom-file-input" name="file" required/>
                                                                        <label class="custom-file-label" for="customFile">Upload File Signed</label>
                                                                        <input type="hidden" name="step" value="file">
                                                                    </div>
                                                                    <div class="col-md-3 custom-file">
                                                                        <input type="hidden" name="id_leads" value="{{$lead->id}}">
                                                                        <button type="submit" class="btn btn-xs btn-light-primary"><i class="fa fa-upload"></i>Upload</button>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @else
                                                            @if($associates[$keyStep]->id_meeting_internal == null)
                                                                <div class="form-group row">
                                                                    <label class="col-form-label text-right col-lg-3 col-sm-12">Subject</label>
                                                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                                                        <input type="text" name="subject" class="form-control" placeholder="Subject" value="Meeting Internal" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label class="col-form-label text-right col-lg-3 col-sm-12">Start Date and Time</label>
                                                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                                                        <input type="date" name="start_date" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label class="col-form-label text-right col-lg-3 col-sm-12"></label>
                                                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                                                        <input type="time" name="start_time" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label class="col-form-label text-right col-lg-3 col-sm-12">Duration</label>
                                                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                                                        <input type="number" name="duration" class="form-control" placeholder="(hours)">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label class="col-form-label text-right col-lg-3 col-sm-12">Attendees</label>
                                                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                                                        <input id="kt_tagify_progress" class="tag_input form-control tagify" name='attendees' placeholder='type attendees and press enter' />
                                                                        <div class="mt-3">
                                                                            <a href="javascript:;" id="kt_tagify_progress_remove" class="tag_remove btn btn-sm btn-light-primary font-weight-bold">Remove Attendees</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label class="col-form-label text-right col-lg-3 col-sm-12">Description</label>
                                                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                                                        <textarea name="description" id="" class="form-control" cols="30" rows="10"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label class="col-form-label text-right col-lg-3 col-sm-12"></label>
                                                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                                                        <button class="btn btn-xs btn-light-primary" type="submit">Submit</button>
                                                                    </div>
                                                                </div>
                                                                <input type="hidden" name="type" value="internal">
                                                            @elseif($associates[$keyStep]->file_draft == null)
                                                                <div class="form-group row mx-auto">
                                                                    <div class="col-md-9 custom-file">
                                                                        <input type="file" class="custom-file-input" name="file" required/>
                                                                        <label class="custom-file-label" for="customFile">Upload MOM Internal File</label>
                                                                    </div>
                                                                    <div class="col-md-3 custom-file">
                                                                        <input type="hidden" name="id_leads" value="{{$lead->id}}">
                                                                        <button type="submit" class="btn btn-xs btn-light-primary"><i class="fa fa-upload"></i>Upload</button>
                                                                    </div>
                                                                </div>
                                                                <input type="hidden" name="type" value="internal_file">
                                                            @elseif($associates[$keyStep]->id_meeting_eksternal == null)
                                                                <div class="form-group row">
                                                                    <label class="col-form-label text-right col-lg-3 col-sm-12">Subject</label>
                                                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                                                        <input type="text" name="subject" class="form-control" placeholder="Subject" value="Meeting Eksternal" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label class="col-form-label text-right col-lg-3 col-sm-12">Start Date and Time</label>
                                                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                                                        <input type="date" name="start_date" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label class="col-form-label text-right col-lg-3 col-sm-12"></label>
                                                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                                                        <input type="time" name="start_time" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label class="col-form-label text-right col-lg-3 col-sm-12">Duration</label>
                                                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                                                        <input type="number" name="duration" class="form-control" placeholder="(hours)">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label class="col-form-label text-right col-lg-3 col-sm-12">Attendees</label>
                                                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                                                        <input id="kt_tagify_progress" class="tag_input form-control tagify" name='attendees' placeholder='type attendees and press enter' />
                                                                        <div class="mt-3">
                                                                            <a href="javascript:;" id="kt_tagify_progress_remove" class="tag_remove btn btn-sm btn-light-primary font-weight-bold">Remove Attendees</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label class="col-form-label text-right col-lg-3 col-sm-12">Description</label>
                                                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                                                        <textarea name="description" id="" class="form-control" cols="30" rows="10"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label class="col-form-label text-right col-lg-3 col-sm-12"></label>
                                                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                                                        <button class="btn btn-xs btn-light-primary" type="submit">Submit</button>
                                                                    </div>
                                                                </div>
                                                                <input type="hidden" name="type" value="eksternal">
                                                            @elseif($associates[$keyStep]->file == null)
                                                                <div class="form-group row mx-auto">
                                                                    <div class="col-md-9 custom-file">
                                                                        <input type="file" class="custom-file-input" name="file" required/>
                                                                        <label class="custom-file-label" for="customFile">Upload MOM Eksternal File</label>
                                                                    </div>
                                                                    <div class="col-md-3 custom-file">
                                                                        <input type="hidden" name="id_leads" value="{{$lead->id}}">
                                                                        <button type="submit" class="btn btn-xs btn-light-primary"><i class="fa fa-upload"></i>Upload</button>
                                                                    </div>
                                                                </div>
                                                                <input type="hidden" name="type" value="eksternal_file">
                                                            @endif
                                                        @endif
                                                        <input type="hidden" name="id_leads" value="{{$lead->id}}">
                                                    </form>
                                                    @break
                                                @endif
                                            @endif
                                        @endforeach
                                    @else
                                        <div class="alert alert-custom alert-outline-2x alert-outline-success">
                                            <div class="alert-icon"><i class="flaticon2-check-mark"></i></div>
                                            <div class="alert-text">{{($lead->approved_at == null) ? "Leads now can be procceed to project" : "Project has been created from this Leads"}}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="activity-tab" role="tabpanel" aria-labelledby="home-tab">
                    <div class="card card-custom gutter-b">
                        <div class="card-header">
                            <div class="card-title">Activity</div>
                        </div>
                    </div>
                    <div class="timeline timeline-3">
                        <div class="timeline-items">
                            @foreach($activity as $key => $item)
                                @if($item->type == "notes")
                                    {{--Notes Acvitity--}}
                                    <div class="card card-custom mb-5">
                                        <div class="card-body">
                                            <div class="timeline-item">
                                                <div class="timeline-media">
                                                    {{date('d M',strtotime($item->data->created_at))}}
                                                </div>
                                                <div class="timeline-content">
                                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                                        <div class="mr-2">
                                                            <a href="#" class="text-dark-75 text-secondary font-weight-bold"><i class="fa fa-sticky-note"></i> &nbsp;Note</a>
                                                            @php
                                                                $dateNow = strtotime(date('Y-m-d'));
                                                                /** @var TYPE_NAME $item->data */
                                                                $dateCreated = strtotime(date('Y-m-d',strtotime($item->data->created_at)));
                                                                $days = ($dateNow - $dateCreated) / 86400
                                                            @endphp
                                                            <span class="text-muted ml-2">created by &nbsp;<b>{{$item->data->created_by}} </b></span>
                                                            <span class="label label-light-info font-weight-bolder label-inline ml-2">{{($days > 0) ? $days.' days ago': 'Today'}}</span>
                                                        </div>
                                                        <div class="dropdown ml-2" data-toggle="tooltip" title="" data-placement="left">
                                                            <button type="button" class="btn btn-hover-light-primary btn-sm btn-icon" data-toggle="modal" data-target="#editNotesActivity{{$item->data->id}}">
                                                                <i class="fa fa-edit icon-sm"></i>
                                                            </button>
                                                            <div class="modal fade" id="editNotesActivity{{$item->data->id}}" tabindex="-1" role="dialog" aria-labelledby="addNotes" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="exampleModalLabel">Edit Note</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <i aria-hidden="true" class="ki ki-close"></i>
                                                                            </button>
                                                                        </div>
                                                                        <form method="post" action="{{route('notes.store')}}" >
                                                                            @csrf
                                                                            <input type="hidden" name="edit" value="{{$item->data->id}}">
                                                                            <div class="modal-body">
                                                                                <input type="hidden" name="id_lead" value="{{$lead->id}}">
                                                                                <div class="form-group row">
                                                                                    <label class="col-form-label text-right col-lg-3 col-sm-12">Notes</label>
                                                                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                                                                        <textarea name="notes" id="" class="form-control" cols="30" rows="10">{{$item->data->notes}}</textarea>
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
                                                            <a href="{{route('notes.delete',['id_lead' => $lead->id, 'id' => $item->data->id])}}" onclick="return confirm('Delete note?');" class="btn btn-hover-light-danger btn-sm btn-icon" >
                                                                <i class="fa fa-trash icon-sm"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <p class="p-0">{{$item->data->notes}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($item->type == "meeting")
                                    {{--Meeting Activity--}}
                                    <div class="card card-custom mb-5">
                                        <div class="card-body">
                                            <div class="timeline-item">
                                                <div class="timeline-media">
                                                    {{date('d M',strtotime($item->data->start_time))}}
                                                </div>
                                                <div class="timeline-content">
                                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                                        <div class="mr-2">
                                                            <a href="#" class="text-dark-75 text-secondary font-weight-bold"><i class="fa fa-handshake"></i>&nbsp;Meeting</a>
                                                            @php
                                                                $dateNow = strtotime(date('Y-m-d'));
                                                                /** @var TYPE_NAME $item->data */
                                                                $dateCreated = strtotime(date('Y-m-d',strtotime($item->data->start_time)));
                                                                $days = ($dateCreated - $dateNow) / 86400
                                                            @endphp
                                                            <span class="text-muted ml-2">created by &nbsp;<b>{{$item->data->created_by}} </b></span>
                                                            <span class="label label-light-info font-weight-bolder label-inline ml-2">{{($days > 0) ? $days.' days to go': 'Today'}}</span>
                                                        </div>
                                                        <div class="dropdown ml-2" data-toggle="tooltip" title="" data-placement="left">
                                                            <button type="button" class="btn btn-hover-light-primary btn-sm btn-icon" data-toggle="modal" data-target="#editMeetingActivity{{$item->data->id}}">
                                                                <i class="fa fa-edit icon-sm"></i>
                                                            </button>
                                                            <div class="modal fade" id="editMeetingActivity{{$item->data->id}}" tabindex="-1" role="dialog" aria-labelledby="editMeeting" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="exampleModalLabel">Edit Meeting</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <i aria-hidden="true" class="ki ki-close"></i>
                                                                            </button>
                                                                        </div>
                                                                        <form method="post" action="{{route('meetings.store')}}" >
                                                                            @csrf
                                                                            <div class="modal-body">
                                                                                <input type="hidden" name="id_lead" value="{{$lead->id}}">
                                                                                <input type="hidden" name="edit" value="{{$item->data->id}}">
                                                                                <div class="form-group row">
                                                                                    <label class="col-form-label text-right col-lg-3 col-sm-12">Subject</label>
                                                                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                                                                        <input type="text" name="subject" class="form-control" value="{{$item->data->subject}}" placeholder="Subject">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row">
                                                                                    <label class="col-form-label text-right col-lg-3 col-sm-12">Start Date and Time</label>
                                                                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                                                                        <input type="date" name="start_date" class="form-control" value="{{date('Y-m-d',strtotime($item->data->start_time))}}">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row">
                                                                                    <label class="col-form-label text-right col-lg-3 col-sm-12"></label>
                                                                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                                                                        <input type="time" name="start_time" class="form-control" value="{{date('H:i',strtotime($item->data->start_time))}}">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row">
                                                                                    <label class="col-form-label text-right col-lg-3 col-sm-12">Duration</label>
                                                                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                                                                        <input type="number" name="duration" class="form-control" placeholder="(hours)" value="{{$item->data->duration}}">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row">
                                                                                    <label class="col-form-label text-right col-lg-3 col-sm-12">Attendees</label>
                                                                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                                                                        @php
                                                                                            /** @var TYPE_NAME $item->data */
                                                                                            $str = $item->data->attendees;
                                                                                            $target = ['["','"]','"'];
                                                                                            $attds = str_replace($target,'',$str);
                                                                                        @endphp
                                                                                        <input id="tag_edit{{$key}}" value="{{$attds}}" class="tag_input form-control tagify" name='attendees' placeholder='type attendees and press enter' />
                                                                                        <div class="mt-3">
                                                                                            <a href="javascript:;" id="tag_remove{{$key}}" class="tag_remove btn btn-sm btn-light-primary font-weight-bold">Remove Attendees</a>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row">
                                                                                    <label class="col-form-label text-right col-lg-3 col-sm-12">Description</label>
                                                                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                                                                        <textarea name="description" id="" class="form-control" cols="30" rows="10">{{$item->data->description}}</textarea>
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
                                                            <a href="{{route('meeting.delete',['id_meeting' => $item->data->id,'id' => $item->data->id_lead, ])}}" onclick="return confirm('Delete meeting schedule?');" class="btn btn-hover-light-danger btn-sm btn-icon" >
                                                                <i class="fa fa-trash icon-sm"></i>
                                                            </a>
                                                        </div>
                                                    </div>

                                                    <p class="text-dark-75 text-secondary font-weight-bold">Subject: {{$item->data->subject}}</p>
                                                    <p class="p-0">{{$item->data->description}}</p>
                                                    <div class="separator separator-dashed my-10"></div>
                                                    <table border="0">
                                                        <thead>
                                                        <tr>
                                                            <th colspan="2">Start Date &nbsp;&nbsp; &nbsp;&nbsp;</th>
                                                            <th colspan="2">Attendees &nbsp;&nbsp; &nbsp;&nbsp;</th>
                                                            <th colspan="2">Duration &nbsp;&nbsp; &nbsp;&nbsp;</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td colspan="6"></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">{{date('d M y', strtotime($item->data->start_time))}}</td>
                                                            @php
                                                                /** @var TYPE_NAME $item->data */
                                                                $names = json_decode($item->data->attendees);

                                                                $count_attd = 0;
                                                                for ($i = 0; $i< count($names); $i++){
                                                                    $count_attd+=1;
                                                                }

                                                            @endphp
                                                            <td colspan="2">{{$count_attd}} person</td>
                                                            <td colspan="2" >{{$item->data->duration}} hour(s)</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                    <div class="separator separator-dashed my-10"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($item->type == "tasks")
                                    {{--Tasks Activity--}}
                                    <div class="card card-custom mb-5">
                                        <div class="card-body">
                                            <div class="timeline-item">
                                                <div class="timeline-media">
                                                    {{date('d M',strtotime($item->data->due_date))}}
                                                </div>
                                                <div class="timeline-content {{($item->data->status == 1)?'bg-success-o-35':''}} ">
                                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                                        <div class="mr-2">
                                                            @if($item->data->status == 1)
                                                                <form method="POST" action="{{route('tasks.follow')}}">
                                                                    @csrf
                                                                    <input type="hidden" name="id_lead" value="{{$lead->id}}">
                                                                    <input type="hidden" name="unfollow" value="{{$item->data->id}}">
                                                                    <button type="submit" onclick="return confirm('Unfollow up?');" class="btn btn-success btn-sm">
                                                                        <i class="fas fa-tasks"></i>&nbsp;Task
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <form method="POST" action="{{route('tasks.follow')}}">
                                                                    @csrf
                                                                    <input type="hidden" name="id_lead" value="{{$lead->id}}">
                                                                    <input type="hidden" name="follow" value="{{$item->data->id}}">
                                                                    <button type="submit" onclick="return confirm('Follow up?');" class="btn btn-secondary btn-sm">
                                                                        <i class="fas fa-tasks"></i>&nbsp;Task
                                                                    </button>
                                                                </form>
                                                            @endif
                                                            @php
                                                                $dateNow = strtotime(date('Y-m-d'));
                                                                /** @var TYPE_NAME $item->data */
                                                                $dateCreated = strtotime(date('Y-m-d',strtotime($item->data->due_date)));
                                                                $days = ($dateCreated - $dateNow) / 86400
                                                            @endphp
                                                            <span class="text-muted ml-2">created by &nbsp;<b>{{$item->data->created_by}} </b></span>
                                                            <span class="label label-light-info font-weight-bolder label-inline ml-2">due date: {{($days > 0) ? $days.' days to go': 'Today'}}</span>
                                                        </div>
                                                        <div class="dropdown ml-2" data-toggle="tooltip" title="" data-placement="left">
                                                            <button type="button" class="btn btn-hover-light-primary btn-sm btn-icon" data-toggle="modal" data-target="#editTaskAct{{$item->data->id}}">
                                                                <i class="fa fa-edit icon-sm"></i>
                                                            </button>
                                                            <div class="modal fade" id="editTaskAct{{$item->data->id}}" tabindex="-1" role="dialog" aria-labelledby="editMeeting" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="exampleModalLabel">Edit Task</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <i aria-hidden="true" class="ki ki-close"></i>
                                                                            </button>
                                                                        </div>
                                                                        <form method="post" action="{{route('tasks.store')}}" >
                                                                            @csrf
                                                                            <div class="modal-body">
                                                                                <input type="hidden" name="edit" value="{{$item->data->id}}">
                                                                                <input type="hidden" name="id_lead" value="{{$item->data->id}}">
                                                                                <div class="form-group row">
                                                                                    <label class="col-form-label text-right col-lg-3 col-sm-12">Title</label>
                                                                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                                                                        <input type="text" name="title" class="form-control" value="{{$item->data->title}}" placeholder="Enter your task">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row">
                                                                                    <label class="col-form-label text-right col-lg-3 col-sm-12">Notes</label>
                                                                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                                                                        <textarea name="notes" id="" class="form-control" cols="30" rows="10">{{$item->data->notes}}</textarea>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row">
                                                                                    <label class="col-form-label text-right col-lg-3 col-sm-12">Due Date</label>
                                                                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                                                                        <input type="date" name="due_date" value="{{date('Y-m-d',strtotime($item->data->due_date))}}" class="form-control">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row">
                                                                                    <label class="col-form-label text-right col-lg-3 col-sm-12"></label>
                                                                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                                                                        <input type="time" name="due_time" value="{{date('H:i',strtotime($item->data->due_date))}}" class="form-control">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row">
                                                                                    <label class="col-form-label text-right col-lg-3 col-sm-12">Type</label>
                                                                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                                                                        <select name="type" class="form-control">
                                                                                            <option value="todo" @if($item->data->type = 'todo') SELECTED @endif>To-do</option>
                                                                                            <option value="call" @if($item->data->type = 'call') SELECTED @endif>Call</option>
                                                                                            <option value="email" @if($item->data->type = 'email') SELECTED @endif>Email</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row">
                                                                                    <label class="col-form-label text-right col-lg-3 col-sm-12">Priority</label>
                                                                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                                                                        <select name="priority" class="form-control">
                                                                                            <option value="0" @if($item->data->priority = '0') SELECTED @endif>None</option>
                                                                                            <option value="1" @if($item->data->priority = '1') SELECTED @endif>High</option>
                                                                                        </select>
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
                                                            <a href="{{route('task.delete',['id' => $lead->id,'id_task' =>$item->data->id])}}" onclick="return confirm('Delete Task?');" class="btn btn-hover-light-danger btn-sm btn-icon" >
                                                                <i class="fa fa-trash icon-sm"></i>
                                                            </a>
                                                        </div>
                                                    </div>

                                                    <p class="text-dark-75 text-secondary font-weight-bold">Subject: {{$item->data->title}}</p>
                                                    <p class="p-0">{{$item->data->notes}}</p>
                                                    <div class="separator separator-dashed my-10"></div>
                                                    <table border="0">
                                                        <thead>
                                                        <tr>
                                                            <th colspan="2">Due Date &nbsp;&nbsp; &nbsp;&nbsp;</th>
                                                            <th colspan="2">Priority &nbsp;&nbsp; &nbsp;&nbsp;</th>
                                                            <th colspan="2">Status &nbsp;&nbsp; &nbsp;&nbsp;</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td colspan="6"></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">{{date('d M y', strtotime($item->data->due_date))}}</td>

                                                            <td colspan="2">{{($item->data->priority == 1)?'High':'None'}} </td>
                                                            <td colspan="2" >{{($item->data->status == 1)?'Follow Up':'Not followed up yet'}}</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                    <div class="separator separator-dashed my-10"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($item->type == "contracts")
                                    <div class="card card-custom mb-5">
                                        <div class="card-body">
                                            <div class="timeline-item">
                                                <div class="timeline-media">
                                                    {{date('d M',strtotime($item->data->created_at))}}
                                                </div>
                                                <div class="timeline-content">
                                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                                        <div class="mr-2">
                                                            <a href="#" class="text-dark-75 text-secondary font-weight-bold"><i class="fa fa-file-invoice"></i>&nbsp;Contract</a>
                                                            @php
                                                                $dateNow = strtotime(date('Y-m-d'));
                                                                /** @var TYPE_NAME $item->data */
                                                                $dateCreated = strtotime(date('Y-m-d',strtotime($item->data->created_at)));
                                                                $days = ($dateCreated - $dateNow) / 86400
                                                            @endphp
                                                            <span class="text-muted ml-2">created by &nbsp;<b>{{$item->data->created_by}} </b></span>
                                                            <span class="label label-light-info font-weight-bolder label-inline ml-2">{{($days > 0) ? $days.' days to go': 'Today'}}</span>
                                                        </div>
                                                        <div class="dropdown ml-2" data-toggle="tooltip" title="" data-placement="left">
                                                            @if($item->data->inv_date != null || $item->data->inv_date != "")
                                                                <button type="button" class="btn btn-light-success btn-sm" data-toggle="modal" data-target="#editContractInvoiceAct{{$item->data->id}}">
                                                                    <i class="fa fa-calendar-plus icon-sm"></i> {{date("d F Y", strtotime($item->data->inv_date))}}
                                                                </button>
                                                            @else
                                                                <button type="button" class="btn btn-light-success btn-sm" data-toggle="modal" data-target="#editContractInvoiceAct{{$item->data->id}}">
                                                                    <i class="fa fa-calendar-plus icon-sm"></i> Invoice
                                                                </button>
                                                            @endif
                                                            <div class="modal fade" id="editContractInvoiceAct{{$item->data->id}}" tabindex="-1" role="dialog" aria-labelledby="editContractInvoiceAct" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="exampleModalLabel">Set Invoice Date</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <i aria-hidden="true" class="ki ki-close"></i>
                                                                            </button>
                                                                        </div>
                                                                        <form method="post" action="{{route('lead.contract.editInv')}}" >
                                                                            @csrf
                                                                            <div class="modal-body">
                                                                                <input type="hidden" name="id_lead" value="{{$lead->id}}">
                                                                                <input type="hidden" name="edit" value="{{$item->data->id}}">
                                                                                <div class="form-group row">
                                                                                    <label class="col-form-label text-right col-lg-3 col-sm-12">Invoice Date</label>
                                                                                    <div class="col-lg-9 col-md-9 col-sm-12">
                                                                                        <input type="date" name="inv_date" value="{{$item->data->inv_date}}" class="form-control" placeholder="Contract Name">
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
                                                            <button type="button" class="btn btn-hover-light-primary btn-sm btn-icon" data-toggle="modal" data-target="#editContractAct{{$item->data->id}}">
                                                                <i class="fa fa-edit icon-sm"></i>
                                                            </button>
                                                            <div class="modal fade" id="editContractAct{{$item->data->id}}" tabindex="-1" role="dialog" aria-labelledby="editContractAct" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="exampleModalLabel">Edit Contract</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <i aria-hidden="true" class="ki ki-close"></i>
                                                                            </button>
                                                                        </div>
                                                                        <form method="post" action="{{route('lead.contract.edit')}}" >
                                                                            @csrf
                                                                            <div class="modal-body">
                                                                                <input type="hidden" name="id_lead" value="{{$lead->id}}">
                                                                                <input type="hidden" name="edit" value="{{$item->data->id}}">
                                                                                <div class="form-group row">
                                                                                    <label class="col-form-label text-right col-lg-3 col-sm-12">Contract Name</label>
                                                                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                                                                        <input type="text" name="contract_name" class="form-control" value="{{$item->data->contract_name}}" placeholder="Contract Name">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row">
                                                                                    <label class="col-form-label text-right col-lg-3 col-sm-12">Description</label>
                                                                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                                                                    <textarea name="description" class="form-control" id="" cols="30"
                                                                                              rows="10">{{strip_tags($item->data->description)}}</textarea>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row">
                                                                                    <label class="col-form-label text-right col-lg-3 col-sm-12">Value</label>
                                                                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                                                                        <input type="number" name="value" step=".01" class="form-control" placeholder="Vakye" value="{{$item->data->value}}">
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
                                                            <a href="{{route('lead.contract.delete',['id' => $item->data->id,'id_lead' => $lead->id, ])}}" onclick="return confirm('Delete meeting schedule?');" class="btn btn-hover-light-danger btn-sm btn-icon" >
                                                                <i class="fa fa-trash icon-sm"></i>
                                                            </a>
                                                        </div>
                                                    </div>

                                                    <p class="text-dark-75 text-secondary font-weight-bold">Subject: {{$item->data->contract_name}}</p>
                                                    <p class="p-0">{{$item->data->description}}</p>
                                                    <p class="p-0 font-weight-bold">Value: {{number_format($item->data->value, 2)}}</p>
                                                    <div class="separator separator-dashed my-10"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="notes-tab" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="card card-custom gutter-b">
                        <div class="card-header">
                            <div class="card-title">Notes</div>
                            <div class="card-toolbar">
                                <button type="button" class="btn btn-light-primary btn-xs" data-toggle="modal" data-target="#addNotes"><i class="fa fa-plus"></i>Add Note</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="addNotes" tabindex="-1" role="dialog" aria-labelledby="addNotes" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Add Note</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <i aria-hidden="true" class="ki ki-close"></i>
                                    </button>
                                </div>
                                <form method="post" action="{{route('notes.store')}}" >
                                    @csrf
                                    <div class="modal-body">
                                        <input type="hidden" name="id_lead" value="{{$lead->id}}">
                                        <div class="form-group row">
                                            <label class="col-form-label text-right col-lg-3 col-sm-12">Notes</label>
                                            <div class="col-lg-6 col-md-9 col-sm-12">
                                                <textarea name="notes" id="" class="form-control" cols="30" rows="10"></textarea>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                                            <i class="fa fa-check"></i>
                                            Add</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="timeline timeline-3">
                        <div class="timeline-items">
                            @foreach($notes as $key => $note)
                                <div class="card card-custom mb-5">
                                    <div class="card-body">
                                        <div class="timeline-item">
                                            <div class="timeline-media">
                                                {{date('d M',strtotime($note->created_at))}}
                                            </div>
                                            <div class="timeline-content">
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <div class="mr-2">
                                                        <a href="#" class="text-dark-75 text-secondary font-weight-bold"><i class="fa fa-sticky-note"></i> &nbsp;Note</a>
                                                        @php
                                                            $dateNow = strtotime(date('Y-m-d'));
                                                            /** @var TYPE_NAME $note */
                                                            $dateCreated = strtotime(date('Y-m-d',strtotime($note->created_at)));
                                                            $days = ($dateNow - $dateCreated) / 86400
                                                        @endphp
                                                        <span class="text-muted ml-2">created by &nbsp;<b>{{$note->created_by}} </b></span>
                                                        <span class="label label-light-info font-weight-bolder label-inline ml-2">{{($days > 0) ? $days.' days ago': 'Today'}}</span>
                                                    </div>
                                                    <div class="dropdown ml-2" data-toggle="tooltip" title="" data-placement="left">
                                                        <button type="button" class="btn btn-hover-light-primary btn-sm btn-icon" data-toggle="modal" data-target="#editNotes{{$note->id}}">
                                                            <i class="fa fa-edit icon-sm"></i>
                                                        </button>
                                                        <div class="modal fade" id="editNotes{{$note->id}}" tabindex="-1" role="dialog" aria-labelledby="addNotes" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Edit Note</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <i aria-hidden="true" class="ki ki-close"></i>
                                                                        </button>
                                                                    </div>
                                                                    <form method="post" action="{{route('notes.store')}}" >
                                                                        @csrf
                                                                        <input type="hidden" name="edit" value="{{$note->id}}">
                                                                        <div class="modal-body">
                                                                            <input type="hidden" name="id_lead" value="{{$lead->id}}">
                                                                            <div class="form-group row">
                                                                                <label class="col-form-label text-right col-lg-3 col-sm-12">Notes</label>
                                                                                <div class="col-lg-6 col-md-9 col-sm-12">
                                                                                    <textarea name="notes" id="" class="form-control" cols="30" rows="10">{{$note->notes}}</textarea>
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
                                                        <a href="{{route('notes.delete',['id_lead' => $lead->id, 'id' => $note->id])}}" onclick="return confirm('Delete note?');" class="btn btn-hover-light-danger btn-sm btn-icon" >
                                                            <i class="fa fa-trash icon-sm"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <p class="p-0">{{$note->notes}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="tasks-tab" role="tabpanel" aria-labelledby="contact-tab">
                    <div class="card card-custom gutter-b">
                        <div class="card-header">
                            <div class="card-title">Tasks</div>
                            <div class="card-toolbar">
                                <button type="button" class="btn btn-light-primary" data-toggle="modal" data-target="#addTask"><i class="fa fa-plus"></i>Create Task</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="addTask" tabindex="-1" role="dialog" aria-labelledby="addTask" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Create Task</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <i aria-hidden="true" class="ki ki-close"></i>
                                    </button>
                                </div>
                                <form method="post" action="{{route('tasks.store')}}" >
                                    @csrf
                                    <div class="modal-body">
                                        <input type="hidden" name="id_lead" value="{{$lead->id}}">
                                        <div class="form-group row">
                                            <label class="col-form-label text-right col-lg-3 col-sm-12">Title</label>
                                            <div class="col-lg-6 col-md-9 col-sm-12">
                                                <input type="text" name="title" class="form-control" placeholder="Enter your task">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label text-right col-lg-3 col-sm-12">Notes</label>
                                            <div class="col-lg-6 col-md-9 col-sm-12">
                                                <textarea name="notes" id="" class="form-control" cols="30" rows="10"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label text-right col-lg-3 col-sm-12">Due Date</label>
                                            <div class="col-lg-6 col-md-9 col-sm-12">
                                                <input type="date" name="due_date" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label text-right col-lg-3 col-sm-12"></label>
                                            <div class="col-lg-6 col-md-9 col-sm-12">
                                                <input type="time" name="due_time" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label text-right col-lg-3 col-sm-12">Type</label>
                                            <div class="col-lg-6 col-md-9 col-sm-12">
                                                <select name="type" class="form-control">
                                                    <option value="todo">To-do</option>
                                                    <option value="call">Call</option>
                                                    <option value="email">Email</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label text-right col-lg-3 col-sm-12">Priority</label>
                                            <div class="col-lg-6 col-md-9 col-sm-12">
                                                <select name="priority" class="form-control">
                                                    <option value="0">None</option>
                                                    <option value="1">High</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                                            <i class="fa fa-check"></i>
                                            Add</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="timeline timeline-3">
                        <div class="timeline-items">
                            @foreach($tasks as $key => $task)
                                <div class="card card-custom mb-5">
                                    <div class="card-body">
                                        <div class="timeline-item">
                                            <div class="timeline-media">
                                                {{date('d M',strtotime($task->due_date))}}
                                            </div>
                                            <div class="timeline-content {{($task->status == 1)?'bg-success-o-35':''}} ">
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <div class="mr-2">
                                                        <a href="#" class="text-dark-75 text-secondary font-weight-bold"><i class="fas fa-tasks"></i>&nbsp;Tasks</a>
                                                        @php
                                                            $dateNow = strtotime(date('Y-m-d'));
                                                            /** @var TYPE_NAME $task */
                                                            $dateCreated = strtotime(date('Y-m-d',strtotime($task->due_date)));
                                                            $days = ($dateCreated - $dateNow) / 86400
                                                        @endphp
                                                        <span class="text-muted ml-2">created by &nbsp;<b>{{$task->created_by}} </b></span>
                                                        <span class="label label-light-info font-weight-bolder label-inline ml-2">due date: {{($days > 0) ? $days.' days to go': 'Today'}}</span>
                                                    </div>
                                                    <div class="dropdown ml-2" data-toggle="tooltip" title="" data-placement="left">
                                                        @if($task->status == 1)
                                                            <a href="#" onclick="return confirm('Unfollow up?');" class="btn btn-hover-light-danger btn-sm btn-icon" >
                                                                <i class="fa fa-window-close icon-sm"></i>
                                                            </a>
                                                        @else
                                                            <a href="#" onclick="return confirm('Follow up?');" class="btn btn-hover-light-success btn-sm btn-icon" >
                                                                <i class="fa fa-check-circle icon-sm"></i>
                                                            </a>
                                                        @endif
                                                        <button type="button" class="btn btn-hover-light-primary btn-sm btn-icon" data-toggle="modal" data-target="#editTask{{$task->id}}">
                                                            <i class="fa fa-edit icon-sm"></i>
                                                        </button>
                                                        <div class="modal fade" id="editTask{{$task->id}}" tabindex="-1" role="dialog" aria-labelledby="editMeeting" aria-hidden="true">

                                                        </div>
                                                        <a href="#" onclick="return confirm('Delete Task?');" class="btn btn-hover-light-danger btn-sm btn-icon" >
                                                            <i class="fa fa-trash icon-sm"></i>
                                                        </a>
                                                    </div>
                                                </div>

                                                <p class="text-dark-75 text-secondary font-weight-bold">Subject: {{$task->title}}</p>
                                                <p class="p-0">{{$task->notes}}</p>
                                                <div class="separator separator-dashed my-10"></div>
                                                <table border="0">
                                                    <thead>
                                                    <tr>
                                                        <th colspan="2">Due Date &nbsp;&nbsp; &nbsp;&nbsp;</th>
                                                        <th colspan="2">Priority &nbsp;&nbsp; &nbsp;&nbsp;</th>
                                                        <th colspan="2">Status &nbsp;&nbsp; &nbsp;&nbsp;</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td colspan="6"></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">{{date('d M y', strtotime($task->due_date))}}</td>

                                                        <td colspan="2">{{($task->priority == 1)?'High':'None'}} </td>
                                                        <td colspan="2" >{{($task->status == 1)?'Follow Up':'Not followed up yet'}}</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <div class="separator separator-dashed my-10"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="meetings-tab" role="tabpanel" aria-labelledby="contact-tab">
                    <div class="card card-custom gutter-b">
                        <div class="card-header">
                            <div class="card-title">Meetings</div>
                            <div class="card-toolbar">
                                <button type="button" class="btn btn-light-primary" data-toggle="modal" data-target="#addMeeting"><i class="fa fa-plus"></i>Create Meeting</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="addMeeting" tabindex="-1" role="dialog" aria-labelledby="addNotes" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Create Meeting</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <i aria-hidden="true" class="ki ki-close"></i>
                                    </button>
                                </div>
                                <form method="post" action="{{route('meetings.store')}}" >
                                    @csrf
                                    <div class="modal-body">
                                        <input type="hidden" name="id_lead" value="{{$lead->id}}">
                                        <div class="form-group row">
                                            <label class="col-form-label text-right col-lg-3 col-sm-12">Subject</label>
                                            <div class="col-lg-6 col-md-9 col-sm-12">
                                                <input type="text" name="subject" class="form-control" placeholder="Subject">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label text-right col-lg-3 col-sm-12">Start Date and Time</label>
                                            <div class="col-lg-6 col-md-9 col-sm-12">
                                                <input type="date" name="start_date" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label text-right col-lg-3 col-sm-12"></label>
                                            <div class="col-lg-6 col-md-9 col-sm-12">
                                                <input type="time" name="start_time" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label text-right col-lg-3 col-sm-12">Duration</label>
                                            <div class="col-lg-6 col-md-9 col-sm-12">
                                                <input type="number" name="duration" class="form-control" placeholder="(hours)">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label text-right col-lg-3 col-sm-12">Attendees</label>
                                            <div class="col-lg-6 col-md-9 col-sm-12">
                                                <input id="kt_tagify_1" class="tag_input form-control tagify" name='attendees' placeholder='type attendees and press enter' />
                                                <div class="mt-3">
                                                    <a href="javascript:;" id="kt_tagify_1_remove" class="tag_remove btn btn-sm btn-light-primary font-weight-bold">Remove Attendees</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label text-right col-lg-3 col-sm-12">Description</label>
                                            <div class="col-lg-6 col-md-9 col-sm-12">
                                                <textarea name="description" id="" class="form-control" cols="30" rows="10"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                                            <i class="fa fa-check"></i>
                                            Add</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="momMeeting" tabindex="-1" role="dialog" aria-labelledby="addNotes" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Upload MOM File</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <i aria-hidden="true" class="ki ki-close"></i>
                                    </button>
                                </div>
                                <form method="post" action="{{route('leads.meeting.upload')}}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <input type="hidden" name="id_lead" value="{{$lead->id}}">
                                        <div class="form-group row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 custom-file">
                                                <input type="file" name="file" class="custom-file-input" placeholder="Subject">
                                                <span class="custom-file-label">Choose File</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="hidden" name="id_meeting" id="id_meeting">
                                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                                            <i class="fa fa-check"></i>
                                            Add</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="timeline timeline-3">
                        <div class="card card-custom mb-5">
                            <div class="card-body">
                                <div id="kt_calendar"></div>
                            </div>
                        </div>
                        <div class="timeline-items">
                            @foreach($meetings as $key => $meeting)
                                <div class="card card-custom mb-5">
                                    <div class="card-body">
                                        <div class="timeline-item">
                                            <div class="timeline-media">
                                                {{date('d M',strtotime($meeting->start_time))}}
                                            </div>
                                            <div class="timeline-content">
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <div class="mr-2">
                                                        <a href="#" class="text-dark-75 text-secondary font-weight-bold"><i class="fa fa-handshake"></i>&nbsp;Meeting</a>
                                                        @php
                                                            $dateNow = strtotime(date('Y-m-d'));
                                                            /** @var TYPE_NAME $meeting */
                                                            $dateCreated = strtotime(date('Y-m-d',strtotime($meeting->start_time)));
                                                            $days = ($dateCreated - $dateNow) / 86400
                                                        @endphp
                                                        <span class="text-muted ml-2">created by &nbsp;<b>{{$meeting->created_by}} </b></span>
                                                        <span class="label label-light-info font-weight-bolder label-inline ml-2">{{($days > 0) ? $days.' days to go': 'Today'}}</span>
                                                    </div>
                                                    <div class="dropdown ml-2" data-toggle="tooltip" title="" data-placement="left">
                                                        <button type="button" class="btn btn-hover-light-primary btn-sm btn-icon" data-toggle="modal" data-target="#editMeeting{{$meeting->id}}">
                                                            <i class="fa fa-edit icon-sm"></i>
                                                        </button>
                                                        <div class="modal fade" id="editMeeting{{$meeting->id}}" tabindex="-1" role="dialog" aria-labelledby="editMeeting" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Edit Meeting</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <i aria-hidden="true" class="ki ki-close"></i>
                                                                        </button>
                                                                    </div>
                                                                    <form method="post" action="{{route('meetings.store')}}" >
                                                                        @csrf
                                                                        <div class="modal-body">
                                                                            <input type="hidden" name="id_lead" value="{{$lead->id}}">
                                                                            <input type="hidden" name="edit" value="{{$meeting->id}}">
                                                                            <div class="form-group row">
                                                                                <label class="col-form-label text-right col-lg-3 col-sm-12">Subject</label>
                                                                                <div class="col-lg-6 col-md-9 col-sm-12">
                                                                                    <input type="text" name="subject" class="form-control" value="{{$meeting->subject}}" placeholder="Subject">
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group row">
                                                                                <label class="col-form-label text-right col-lg-3 col-sm-12">Start Date and Time</label>
                                                                                <div class="col-lg-6 col-md-9 col-sm-12">
                                                                                    <input type="date" name="start_date" class="form-control" value="{{date('Y-m-d',strtotime($meeting->start_time))}}">
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group row">
                                                                                <label class="col-form-label text-right col-lg-3 col-sm-12"></label>
                                                                                <div class="col-lg-6 col-md-9 col-sm-12">
                                                                                    <input type="time" name="start_time" class="form-control" value="{{date('H:i',strtotime($meeting->start_time))}}">
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group row">
                                                                                <label class="col-form-label text-right col-lg-3 col-sm-12">Duration</label>
                                                                                <div class="col-lg-6 col-md-9 col-sm-12">
                                                                                    <input type="number" name="duration" class="form-control" placeholder="(hours)" value="{{$meeting->duration}}">
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group row">
                                                                                <label class="col-form-label text-right col-lg-3 col-sm-12">Attendees</label>
                                                                                <div class="col-lg-6 col-md-9 col-sm-12">
                                                                                    @php
                                                                                        /** @var TYPE_NAME $meeting */
                                                                                        $str = $meeting->attendees;
                                                                                        $target = ['["','"]','"'];
                                                                                        $attds = str_replace($target,'',$str);
                                                                                    @endphp
                                                                                    <input id="tag_edit{{$key}}" value="{{$attds}}" class="tag_input form-control tagify" name='attendees' placeholder='type attendees and press enter' />
                                                                                    <div class="mt-3">
                                                                                        <a href="javascript:;" id="tag_remove{{$key}}" class="tag_remove btn btn-sm btn-light-primary font-weight-bold">Remove Attendees</a>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group row">
                                                                                <label class="col-form-label text-right col-lg-3 col-sm-12">Description</label>
                                                                                <div class="col-lg-6 col-md-9 col-sm-12">
                                                                                    <textarea name="description" id="" class="form-control" cols="30" rows="10">{{$meeting->description}}</textarea>
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
                                                        <a href="{{route('meeting.delete',['id_meeting' => $meeting->id,'id' => $meeting->id_lead, ])}}" onclick="return confirm('Delete meeting schedule?');" class="btn btn-hover-light-danger btn-sm btn-icon" >
                                                            <i class="fa fa-trash icon-sm"></i>
                                                        </a>
                                                    </div>
                                                </div>

                                                <p class="text-dark-75 text-secondary font-weight-bold">Subject: {{$meeting->subject}}</p>
                                                <p class="p-0">{{$meeting->description}}</p>
                                                <div class="separator separator-dashed my-10"></div>
                                                <table border="0">
                                                    <thead>
                                                    <tr>
                                                        <th colspan="2">Start Date &nbsp;&nbsp; &nbsp;&nbsp;</th>
                                                        <th colspan="2">Attendees &nbsp;&nbsp; &nbsp;&nbsp;</th>
                                                        <th colspan="2">Duration &nbsp;&nbsp; &nbsp;&nbsp;</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td colspan="6"></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">{{date('d M y', strtotime($meeting->start_time))}}</td>
                                                        @php
                                                            /** @var TYPE_NAME $meeting */
                                                            $names = json_decode($meeting->attendees);

                                                            $count_attd = 0;
                                                            for ($i = 0; $i< count($names); $i++){
                                                                $count_attd+=1;
                                                            }

                                                        @endphp
                                                        <td colspan="2">{{$count_attd}} person</td>
                                                        <td colspan="2" >{{$meeting->duration}} hour(s)</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <div class="separator separator-dashed my-10"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="files-tab" role="tabpanel" aria-labelledby="contact-tab">
                    <div class="card card-custom gutter-b">
                        <div class="card-header">
                            <div class="card-title">Files</div>
                            <div class="card-toolbar">
                                <div class="dropdown dropdown-inline" data-toggle="tooltip" title="" data-placement="left" data-original-title="">
                                    <button type="button" class="btn btn-light-primary btn-xs mr-2" data-toggle="modal" data-target="#uploadFileModal">
																					<span class="navi-icon">
																						<i class="flaticon-upload"></i>
																					</span>
                                        <span class="navi-text">Upload File</span>
                                    </button>
                                    <a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ki ki-bold-more-hor"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                                        <!--begin::Navigation-->
                                        <ul class="navi navi-hover py-5">
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
																					<span class="navi-icon">
																						<i class="fas fa-file-invoice"></i>
																					</span>
                                                    <span class="navi-text">Quotation Template</span>
                                                </a>
                                            </li>
                                        </ul>
                                        <!--end::Navigation-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @foreach($files as $file)
                            <div class="col-md-3">
                                <div class="card card-custom gutter-b card-stretch">
                                    <div class="card-header">
                                        <h3 class="card-title"></h3>
                                        <div class="card-toolbar">
                                            <div class="dropdown dropdown-inline" data-toggle="tooltip" title="" data-placement="left" data-original-title="Quick actions">
                                                <a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="ki ki-bold-more-hor"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                                                    <!--begin::Navigation-->
                                                    <ul class="navi navi-hover py-5">
                                                        <li class="navi-item">
                                                            <a href="{{URL::route('download', $file->file_code)}}" class="navi-link">
																					<span class="navi-icon">
																						<i class="fas fa-file-download"></i>
																					</span>
                                                                <span class="navi-text">Download</span>
                                                            </a>
                                                        </li>
                                                        <li class="navi-item">
                                                            <a href="#" onclick="share_button('{{$file->file_code}}')" data-toggle="modal" data-target="#shareFileModal" class="navi-link">
																					<span class="navi-icon">
																						<i class="fas fa-share"></i>
																					</span>
                                                                <span class="navi-text">Share</span>
                                                            </a>
                                                        </li>
{{--                                                        <li class="navi-separator my-3"></li>--}}
                                                        <?php
                                                            $disabled_delete = "";
                                                            $onclick = "delete_file($file->id})";
                                                            foreach ($progress_tab as $itemProgress => $valProgress) {
                                                                if ($lead[$itemProgress] == $file->file_code){
                                                                    $disabled_delete = "disabled";
                                                                    $onclick = "";
                                                                    break;
                                                                }
                                                            }
                                                        ?>
                                                        <li class="navi-item">
                                                            <a href="#" onclick="{{$onclick}}"  class="navi-link {{$disabled_delete}}">
																					<span class="navi-icon">
																						<i class="fas fa-trash"></i>
																					</span>
                                                                <span class="navi-text">Delete</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                    <!--end::Navigation-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex flex-column align-items-center">
                                            <img src="{{asset('theme/assets/media/svg/'.$data_file[$file->file_code]['src'])}}" class="h-85px" alt="">
                                            <label for="" style="width: 100%" class="text-center mt-15 text-wrap font-weight-bold">{{$data_file[$file->file_code]['file_name']}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="modal fade" id="uploadFileModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered " role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Upload File</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <i aria-hidden="true" class="ki ki-close"></i>
                                    </button>
                                </div>
                                <form method="POST" action="{{URL::route('leads.upload_file')}}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="row col-md-12 justify-content-center">
                                            <div class="form-group">
                                                <div class="custom-file">
                                                    <input type="file" name="file" class="custom-file-input" id="customFile" />
                                                    <label class="custom-file-label" for="customFile">Choose file</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <input type="hidden" name="id_leads" value="{{$lead->id}}">
                                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary font-weight-bold">
                                            <i class="flaticon-upload"></i>
                                            Upload</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="contracts-tab" role="tabpanel" aria-labelledby="contact-tab">
                    <div class="card card-custom gutter-b">
                        <div class="card-header">
                            <div class="card-title">Contracts</div>
                            <div class="card-toolbar">
                                <button type="button" class="btn btn-light-primary" data-toggle="modal" data-target="#addContracts"><i class="fa fa-plus"></i>Create Contract</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="addContracts" tabindex="-1" role="dialog" aria-labelledby="addNotes" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Create Contract</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <i aria-hidden="true" class="ki ki-close"></i>
                                    </button>
                                </div>
                                <form method="post" action="{{route('lead.contract.add')}}" >
                                    @csrf
                                    <div class="modal-body">
                                        <input type="hidden" name="id_lead" value="{{$lead->id}}">
                                        <div class="form-group row">
                                            <label class="col-form-label text-right col-lg-3 col-sm-12">Contract Name</label>
                                            <div class="col-lg-6 col-md-9 col-sm-12">
                                                <input type="text" name="contract_name" class="form-control" placeholder="Contract Name" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label text-right col-lg-3 col-sm-12">Description</label>
                                            <div class="col-lg-6 col-md-9 col-sm-12">
                                                <textarea name="description" class="form-control" id="" cols="30" rows="10"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label text-right col-lg-3 col-sm-12">Value</label>
                                            <div class="col-lg-6 col-md-9 col-sm-12">
                                                <input type="number" name="value" class="form-control" step=".01" placeholder="Value" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                                            <i class="fa fa-check"></i>
                                            Add</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="timeline timeline-3">
                        <div class="timeline-items">
                            @foreach($contracts as $key => $contract)
                                <div class="card card-custom mb-5">
                                    <div class="card-body">
                                        <div class="timeline-item">
                                            <div class="timeline-media">
                                                {{date('d M',strtotime($contract->created_at))}}
                                            </div>
                                            <div class="timeline-content">
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <div class="mr-2">
                                                        <a href="#" class="text-dark-75 text-secondary font-weight-bold"><i class="fa fa-file-invoice"></i>&nbsp;Contract</a>
                                                        @php
                                                            $dateNow = strtotime(date('Y-m-d'));
                                                            /** @var TYPE_NAME $contract */
                                                            $dateCreated = strtotime(date('Y-m-d',strtotime($contract->created_at)));
                                                            $days = ($dateCreated - $dateNow) / 86400
                                                        @endphp
                                                        <span class="text-muted ml-2">created by &nbsp;<b>{{$contract->created_by}} </b></span>
                                                        <span class="label label-light-info font-weight-bolder label-inline ml-2">{{($days > 0) ? $days.' days to go': 'Today'}}</span>
                                                    </div>
                                                    <div class="dropdown ml-2" data-toggle="tooltip" title="" data-placement="left">
                                                        @if($contract->inv_date != null || $contract->inv_date != "")
                                                            <button type="button" class="btn btn-light-success btn-sm" data-toggle="modal" data-target="#editContractInvoice{{$contract->id}}">
                                                                <i class="fa fa-calendar-plus icon-sm"></i> {{date("d F Y", strtotime($contract->inv_date))}}
                                                            </button>
                                                        @else
                                                            <button type="button" class="btn btn-light-success btn-sm" data-toggle="modal" data-target="#editContractInvoice{{$contract->id}}">
                                                                <i class="fa fa-calendar-plus icon-sm"></i> Invoice
                                                            </button>
                                                        @endif
                                                        <div class="modal fade" id="editContractInvoice{{$contract->id}}" tabindex="-1" role="dialog" aria-labelledby="editContractInvoice" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Set Invoice Date</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <i aria-hidden="true" class="ki ki-close"></i>
                                                                        </button>
                                                                    </div>
                                                                    <form method="post" action="{{route('lead.contract.editInv')}}" >
                                                                        @csrf
                                                                        <div class="modal-body">
                                                                            <input type="hidden" name="id_lead" value="{{$lead->id}}">
                                                                            <input type="hidden" name="edit" value="{{$contract->id}}">
                                                                            <div class="form-group row">
                                                                                <label class="col-form-label text-right col-lg-3 col-sm-12">Invoice Date</label>
                                                                                <div class="col-lg-9 col-md-9 col-sm-12">
                                                                                    <input type="date" name="inv_date" value="{{$contract->inv_date}}" class="form-control" placeholder="Contract Name">
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
                                                        <button type="button" class="btn btn-hover-light-primary btn-sm btn-icon" data-toggle="modal" data-target="#editContract{{$contract->id}}">
                                                            <i class="fa fa-edit icon-sm"></i>
                                                        </button>
                                                        <div class="modal fade" id="editContract{{$contract->id}}" tabindex="-1" role="dialog" aria-labelledby="editContract" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Edit Contract</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <i aria-hidden="true" class="ki ki-close"></i>
                                                                        </button>
                                                                    </div>
                                                                    <form method="post" action="{{route('lead.contract.edit')}}" >
                                                                        @csrf
                                                                        <div class="modal-body">
                                                                            <input type="hidden" name="id_lead" value="{{$lead->id}}">
                                                                            <input type="hidden" name="edit" value="{{$contract->id}}">
                                                                            <div class="form-group row">
                                                                                <label class="col-form-label text-right col-lg-3 col-sm-12">Contract Name</label>
                                                                                <div class="col-lg-6 col-md-9 col-sm-12">
                                                                                    <input type="text" name="contract_name" class="form-control" value="{{$contract->contract_name}}" placeholder="Contract Name">
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group row">
                                                                                <label class="col-form-label text-right col-lg-3 col-sm-12">Description</label>
                                                                                <div class="col-lg-6 col-md-9 col-sm-12">
                                                                                    <textarea name="description" class="form-control" id="" cols="30"
                                                                                              rows="10">{{strip_tags($contract->description)}}</textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group row">
                                                                                <label class="col-form-label text-right col-lg-3 col-sm-12">Value</label>
                                                                                <div class="col-lg-6 col-md-9 col-sm-12">
                                                                                    <input type="number" name="value" step=".01" class="form-control" placeholder="Vakye" value="{{$contract->value}}">
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
                                                        <a href="{{route('lead.contract.delete',['id' => $contract->id,'id_lead' => $lead->id, ])}}" onclick="return confirm('Delete meeting schedule?');" class="btn btn-hover-light-danger btn-sm btn-icon" >
                                                            <i class="fa fa-trash icon-sm"></i>
                                                        </a>
                                                    </div>
                                                </div>

                                                <p class="text-dark-75 text-secondary font-weight-bold">Subject: {{$contract->contract_name}}</p>
                                                <p class="p-0">{{$contract->description}}</p>
                                                <p class="p-0 font-weight-bold">Value: {{number_format($contract->value, 2)}}</p>
                                                <div class="separator separator-dashed my-10"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="shareFileModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Share File</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="POST" action="{{URL::route('leads.upload_file')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" name="file" readonly class="form-control" id="shareFile" />
                        </div>
                    </div>

                    <div class="modal-footer">
                        <input type="hidden" name="id_leads" value="{{$lead->id}}">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="button" id="btn-copy" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-clipboard"></i>
                            Copy to clipboard</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('custom_script')
    <script>
        function btn_skip(x) {
            $.ajax({
                url: "{{route('leads.skip_step', $lead->id)}}",
                type: "post",
                dataType: "json",
                data : {
                    _token : "{{csrf_token()}}",
                    step : x,
                    id : "{{$lead->id}}"
                },
                cache : false,
                success : function(response){
                    if (response.error === 0){
                        location.reload()
                    } else {
                        Swal.fire('Error occured', 'Please contact your system administrator!', 'error')
                    }
                }
            })
        }

        var doc_num = [];
        var doc_count = 0;
        function add_document_file(){
            var $doc = $("#add-document-file").clone()
            var btn = $doc.find(".btn-document")
            doc_count += 1
            doc_num.push(doc_count)
            var id = $doc.attr("id")
            $doc.attr("id", id+doc_num[doc_num.length - 1])
            btn.removeClass("btn-primary")
            btn.addClass("btn-danger")
            btn.html("<i class='fa fa-trash'></i>")
            btn.attr("onclick", "remove_document_file("+doc_count+")")
            $("#document-div").append($doc)
            console.log(doc_num)
        }

        function remove_document_file(x){
            var id = "#add-document-file"+x
            var index = doc_num.indexOf(x)
            if (index > -1){
                doc_num.splice(index, 1)
            }
            $(id).remove()
            console.log(doc_num)
        }

        function add_to_project(){
            Swal.fire({
                title: 'Are you sure?',
                text: "Add this leads to project!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{route('leads.approve', $lead->id)}}",
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

        function share_button(x){
            $("#shareFile").val(x)
        }

        function delete_file(x){
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
                        url : "{{URL::route('leads.delete_file')}}/" + x,
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

        function demo1(){
            var input = document.getElementById('kt_tagify_1'),
                // init Tagify script on the above inputs
                tagify = new Tagify(input, {
                    whitelist: [],
                    blacklist: [], // <-- passed as an attribute in this demo
                })


            // "remove all tags" button event listener
            document.getElementById('kt_tagify_1_remove').addEventListener('click', tagify.removeAllTags.bind(tagify));

            // Chainable event listeners
            tagify.on('add', onAddTag)
                .on('remove', onRemoveTag)
                .on('input', onInput)
                .on('edit', onTagEdit)
                .on('invalid', onInvalidTag)
                .on('click', onTagClick)
                .on('dropdown:show', onDropdownShow)
                .on('dropdown:hide', onDropdownHide)

            // tag added callback
            function onAddTag(e) {
                console.log("onAddTag: ", e.detail);
                console.log("original input value: ", input.value)
                tagify.off('add', onAddTag) // exmaple of removing a custom Tagify event
            }

            // tag remvoed callback
            function onRemoveTag(e) {
                console.log(e.detail);
                console.log("tagify instance value:", tagify.value)
            }

            // on character(s) added/removed (user is typing/deleting)
            function onInput(e) {
                console.log(e.detail);
                console.log("onInput: ", e.detail);
            }

            function onTagEdit(e) {
                console.log("onTagEdit: ", e.detail);
            }

            // invalid tag added callback
            function onInvalidTag(e) {
                console.log("onInvalidTag: ", e.detail);
            }

            // invalid tag added callback
            function onTagClick(e) {
                console.log(e.detail);
                console.log("onTagClick: ", e.detail);
            }

            function onDropdownShow(e) {
                console.log("onDropdownShow: ", e.detail)
            }

            function onDropdownHide(e) {
                console.log("onDropdownHide: ", e.detail)
            }
        }

        function demo_progress(){
            var input = document.getElementById('kt_tagify_progress'),
                // init Tagify script on the above inputs
                tagify = new Tagify(input, {
                    whitelist: [],
                    blacklist: [], // <-- passed as an attribute in this demo
                })


            // "remove all tags" button event listener
            document.getElementById('kt_tagify_progress_remove').addEventListener('click', tagify.removeAllTags.bind(tagify));

            // Chainable event listeners
            tagify.on('add', onAddTag)
                .on('remove', onRemoveTag)
                .on('input', onInput)
                .on('edit', onTagEdit)
                .on('invalid', onInvalidTag)
                .on('click', onTagClick)
                .on('dropdown:show', onDropdownShow)
                .on('dropdown:hide', onDropdownHide)

            // tag added callback
            function onAddTag(e) {
                console.log("onAddTag: ", e.detail);
                console.log("original input value: ", input.value)
                tagify.off('add', onAddTag) // exmaple of removing a custom Tagify event
            }

            // tag remvoed callback
            function onRemoveTag(e) {
                console.log(e.detail);
                console.log("tagify instance value:", tagify.value)
            }

            // on character(s) added/removed (user is typing/deleting)
            function onInput(e) {
                console.log(e.detail);
                console.log("onInput: ", e.detail);
            }

            function onTagEdit(e) {
                console.log("onTagEdit: ", e.detail);
            }

            // invalid tag added callback
            function onInvalidTag(e) {
                console.log("onInvalidTag: ", e.detail);
            }

            // invalid tag added callback
            function onTagClick(e) {
                console.log(e.detail);
                console.log("onTagClick: ", e.detail);
            }

            function onDropdownShow(e) {
                console.log("onDropdownShow: ", e.detail)
            }

            function onDropdownHide(e) {
                console.log("onDropdownHide: ", e.detail)
            }
        }

        function demox(){
            for (let i = 0; i < {{count($meetings)}}; i++) {
                var input = document.getElementById('tag_edit'+i),
                    // init Tagify script on the above inputs
                    tagify = new Tagify(input, {
                        whitelist: [],
                        blacklist: [], // <-- passed as an attribute in this demo
                    })


                // "remove all tags" button event listener
                document.getElementById('tag_remove'+i).addEventListener('click', tagify.removeAllTags.bind(tagify));

                // Chainable event listeners
                tagify.on('add', onAddTag)
                    .on('remove', onRemoveTag)
                    .on('input', onInput)
                    .on('edit', onTagEdit)
                    .on('invalid', onInvalidTag)
                    .on('click', onTagClick)
                    .on('dropdown:show', onDropdownShow)
                    .on('dropdown:hide', onDropdownHide)

                // tag added callback
                function onAddTag(e) {
                    console.log("onAddTag: ", e.detail);
                    console.log("original input value: ", input.value)
                    tagify.off('add', onAddTag) // exmaple of removing a custom Tagify event
                }

                // tag remvoed callback
                function onRemoveTag(e) {
                    console.log(e.detail);
                    console.log("tagify instance value:", tagify.value)
                }

                // on character(s) added/removed (user is typing/deleting)
                function onInput(e) {
                    console.log(e.detail);
                    console.log("onInput: ", e.detail);
                }

                function onTagEdit(e) {
                    console.log("onTagEdit: ", e.detail);
                }

                // invalid tag added callback
                function onInvalidTag(e) {
                    console.log("onInvalidTag: ", e.detail);
                }

                // invalid tag added callback
                function onTagClick(e) {
                    console.log(e.detail);
                    console.log("onTagClick: ", e.detail);
                }

                function onDropdownShow(e) {
                    console.log("onDropdownShow: ", e.detail)
                }

                function onDropdownHide(e) {
                    console.log("onDropdownHide: ", e.detail)
                }
            }
        }

        var KTCalendarBasic = function() {

            return {
                //main function to initiate the module
                init: function() {
                    var todayDate = moment().startOf('day');
                    var YM = todayDate.format('YYYY-MM');
                    var YESTERDAY = todayDate.clone().subtract(1, 'day').format('YYYY-MM-DD');
                    var TODAY = todayDate.format('YYYY-MM-DD');
                    var TOMORROW = todayDate.clone().add(1, 'day').format('YYYY-MM-DD');

                    var calendarEl = document.getElementById('kt_calendar');
                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        plugins: [ 'bootstrap', 'interaction', 'dayGrid', 'timeGrid', 'list' ],
                        themeSystem: 'bootstrap',
                        displayEventTime : false,
                        isRTL: KTUtil.isRTL(),
                        selectable: true,
                        dateClick : function (info){
                            // window.location.href = "meetingscheduler/"+btoa(info.dateStr)
                        },

                        header: {
                            left: 'prev,today',
                            center: 'title',
                            right: 'next'
                        },

                        height: 800,
                        contentHeight: 780,
                        aspectRatio: 3,  // see: https://fullcalendar.io/docs/aspectRatio

                        nowIndicator: true,
                        now: TODAY + 'T08:00:00', // just for demo

                        views: {
                            dayGridMonth: {
                                buttonText: 'month'
                            },
                            timeGridWeek: {
                                buttonText: 'week'
                            },
                            timeGridDay: {
                                buttonText: 'day'
                            }
                        },

                        defaultView: 'dayGridMonth',
                        defaultDate: TODAY,

                        editable: true,
                        eventLimit: true, // allow "more" link when too many events
                        navLinks: true,
                        events: [

                                @foreach($meetings as $key => $schedule)
                            {
                                title: 'Meeting {{$schedule->subject}}',
                                url: "javascript:modal_mom({{$schedule->id}})",
                                start: '{{date('Y-m-d', strtotime($schedule->start_time))}}',
                                className: "fc-event-solid-info fc-event-light",
                                description: 'Start: {{date('H:i:s', strtotime($schedule->start_time))}} | Duration: {{$schedule->duration}} hour(s)'
                            },
                            @endforeach
                        ],

                        eventRender: function(info) {
                            var element = $(info.el);

                            if (info.event.extendedProps && info.event.extendedProps.description) {
                                if (element.hasClass('fc-day-grid-event')) {
                                    element.data('content', info.event.extendedProps.description);
                                    element.data('placement', 'top');
                                    KTApp.initPopover(element);
                                } else if (element.hasClass('fc-time-grid-event')) {
                                    element.find('.fc-title').append('<div class="fc-description">' + info.event.extendedProps.description + '</div>');
                                } else if (element.find('.fc-list-item-title').lenght !== 0) {
                                    element.find('.fc-list-item-title').append('<div class="fc-description">' + info.event.extendedProps.description + '</div>');
                                }
                            }
                        },

                    });

                    calendar.render();
                }
            };
        }();

        function modal_mom(x){
            $("#momMeeting").modal('show')
            $("#id_meeting").val(x)
        }

        $(document).ready(function () {
            KTCalendarBasic.init();

            $('#kt_dropzone_1').dropzone({
                url: "https://keenthemes.com/scripts/void.php", // Set the url for your upload script location
                paramName: "file", // The name that will be used to transfer the file
                maxFiles: 1,
                maxFilesize: 5, // MB
                addRemoveLinks: true,
                accept: function(file, done) {
                    done()
                }
            });

            demo1()
            demox()

            $("#btn-copy").click(function(){
                var txt = document.getElementById('shareFile')
                txt.select()
                txt.setSelectionRange(0, 99999)
                document.execCommand('copy')

                var content = {}

                content.message = "copied"
                var notify = $.notify(content, {
                    type: "success",
                    allow_dismiss: true,
                    newest_on_top: false,
                    mouse_over:  false,
                    showProgressbar:  false,
                    spacing: 10,
                    timer: 500,
                    placement: {
                        from: "bottom",
                        align: "center"
                    },
                    offset: {
                        x: 30,
                        y: 30
                    },
                    delay: 500,
                    z_index: 10000,
                    animate: {
                        enter: 'animate__animated animate__bounce',
                        exit: 'animate__animated animate__bounce'
                    }

                })
            })

            $("select.select2").select2({
                width: "100%"
            })

            $('table.display').DataTable({
                responsive: true,
            });

            demo_progress()

        })


    </script>
@endsection
