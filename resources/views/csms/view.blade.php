@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">CSMS</h3>
            <div class="card-toolbar btn-group">
                <a href="{{ route('qhse.csms.print', $csms->id) }}" class="btn btn-primary"><i class="fa fa-print"></i></a>
                <a href="{{ route('qhse.csms.view', ["type" => "step", "id" => $csms->id]) }}" class="btn btn-secondary"><i class="fa fa-cog"></i></a>
                <a href="{{ route('qhse.csms.index') }}" class="btn btn-success"><i class="fa fa-arrow-left"></i></a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="wizard wizard-2" id="wizard2" data-wizard-state="step-first" data-wizard-clickable="true">
                        <!--begin: Wizard Nav-->
                        <div class="wizard-nav border-right">
                            <!--begin::Wizard Step 1 Nav-->
                            <div class="wizard-steps">
                                <div class="scroll scroll-pull" data-scroll="true" style="height: 559px">
                                @foreach($step as $key => $item)
                                    <div class="wizard-step" data-wizard-type="step" {{($key == 0) ? "data-wizard-state='current'" : ""}}>
                                        <div class="wizard-wrapper">
                                            <div class="wizard-icon">
                                                <i class="flaticon-add-label-button"></i>
                                            </div>
                                            <div class="wizard-label">
                                                <h3 class="wizard-title">{{$item->name}}</h3>
                                                <div class="wizard-desc">Requirements for {{$item->progress}}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                </div>
                            <!--end::Wizard Step 1 Nav-->
                            </div>
                        </div>
                        <!--end: Wizard Nav-->
                        <!--begin: Wizard Body-->
                        <div class="wizard-body">
                            <!--begin: Wizard Form-->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mr-5 ml-5">

                                        <!--begin: Wizard Step 1-->
                                        @foreach($step as $key => $item)
                                            <div class="pb-5" data-wizard-type="step-content" {{($key == 0) ? "data-wizard-state='current'" : ""}}>
                                                <h4 class="mb-10 font-weight-bold text-dark">{{$item->name}} Requirements</h4>
                                                @if(isset($input_step[$item->id]))
                                                    @foreach($input_step[$item->id] as $list)
                                                        @if($list == 'ud')
                                                            @include('csms.form_upload', ['formType' => 'draft'])
                                                        @elseif($list == "ms")
                                                            @include('csms.form_ms')
                                                        @elseif($list == "ol")
                                                            @include('csms.form_ol')
                                                        @elseif($list == "su")
                                                            @include('csms.form_su')
                                                        @elseif($list == "tt")
                                                            @include('csms.form_tt')
                                                        @elseif($list == "ba")
                                                            @include('csms.form_upload', ['formType' => 'ba'])
                                                        @elseif($list == "pe")
                                                            @include('csms.form_upload', ['formType' => 'pe'])
                                                        @elseif ($list == "link")
                                                            @include("csms.form_links")
                                                        @endif
                                                        <div class="separator separator-solid separator-border-2 separator-info mt-5 mb-5"></div>
                                                    @endforeach
                                                @endif
                                            </div>

                                        @endforeach
                                        <!--end: Wizard Step 1-->
                                    </div>
                                </div>
                                <!--end: Wizard-->
                            </div>
                            <!--end: Wizard Form-->
                        </div>
                        <!--end: Wizard Body-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addCsms" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Add CSMS</h3>
                </div>
                <form action="{{ route('qhse.csms.add') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="" class="col-form-label col-3">Name</label>
                            <div class="col-9">
                                <input type="text" class="form-control" name="name" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-form-label col-3">Year</label>
                            <div class="col-9">
                                <input type="number" class="form-control" name="year" value="{{ date('Y') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fad" id="addDocument" tabindex="-1" role="dialog" aria-labelledby="addDocument" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Document of <span id="document-title"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row custom-file">
                        <label for="" class="custom-file-label">Choose File</label>
                        <input type="file" class="custom-file-input" name="document">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="meetingModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create new meeting</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form action="{{route('qhse.csms.meetings.create')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="" class="col-md-3 col-form-label">Subject</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="subject" placeholder="Subject">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-md-3 col-form-label">Date</label>
                            <div class="col-md-9">
                                <input type="date" class="form-control" name="date" id="dateMeeting" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-md-3 col-form-label">Start Time</label>
                            <div class="col-md-9">
                                <input type="time" class="form-control" name="time">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-md-3 col-form-label">Duration(s)</label>
                            <div class="col-md-9">
                                <input type="number" class="form-control" name="duration" placeholder="hours">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-md-3 col-form-label">Atendees</label>
                            <div class="col-md-9">
                                <input id="atendees-meeting" class="tag_input form-control tagify" name='attendees' placeholder='type attendees and press enter' />
                                <div class="mt-3">
                                    <a href="javascript:;" id="atendees-meeting-remove" class="tag_remove btn btn-sm btn-light-primary font-weight-bold">Remove Attendees</a>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-md-3 col-form-label">Description</label>
                            <div class="col-md-9">
                                <textarea name="description" class="form-control" id="" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_project" value="{{$csms->id}}">
                        <input type="hidden" name="id_step" id="id_step">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" id="btn-copy" class="btn btn-primary font-weight-bold">
                            Create</button>
                    </div>
                </form>
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
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" name="file" readonly class="form-control" id="shareFile" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                    <button type="button" id="btn-copy" class="btn btn-primary font-weight-bold">
                        <i class="fa fa-clipboard"></i>
                        Copy to clipboard</button>
                </div>
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
                <form method="post" action="{{route('qhse.csms.meetings.mom')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row mb-5">
                            <div class="col-md-12">
                                <a href="" id="meeting-download">File MOM <i class="fa fa-download text-primary"></i></a>
                            </div>
                        </div>
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
    <div class="modal fade" id="olModal" tabindex="-1" role="dialog" aria-labelledby="addNotes" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Outbox List</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('qhse.csms.ol.create')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        @foreach($field_ol as $key => $form)
                            <div class="form-group row">
                                <label for="" class="col-form-label col-3">{{ucwords(str_replace("_", " ", $key))}}</label>
                                <div class="col-lg-9 col-md-9 col-sm-9">
                                    <input type="{{$form}}" class="form-control" name="{{$key}}">
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_project" value="{{$csms->id}}">
                        <input type="hidden" name="id_step" id="id_step_ol">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="olEditModal" tabindex="-1" role="dialog" aria-labelledby="addNotes" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Outbox List</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('qhse.csms.ol.update')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        @foreach($field_ol as $key => $form)
                            <div class="form-group row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <input type="{{$form}}" class="form-control" name="$key">
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="ol-id" name="ol_id">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="readModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="read-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <p id="read-more"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="ttModal" tabindex="-1" role="dialog" aria-labelledby="addNotes" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Time Table</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('qhse.csms.tt.create')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="" class="col-form-label col-md-3 text-right">Title</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" placeholder="Title" name="title">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-form-label col-md-3 text-right">Notes</label>
                            <div class="col-md-9">
                                <textarea name="notes" class="form-control" id="" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-form-label col-md-3 text-right">Due Date</label>
                            <div class="col-md-9">
                                <input type="date" class="form-control" name="due_date">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-form-label col-md-3 text-right"></label>
                            <div class="col-md-9">
                                <input type="time" class="form-control" name="due_time">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_project" value="{{$csms->id}}">
                        <input type="hidden" name="id_step" id="id_step_tt">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="suFieldModal" tabindex="-1" role="dialog" aria-labelledby="addNotes" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Summary Field</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('qhse.csms.su.field')}}" id="form-su">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-3">Field Name</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" id="field_name" placeholder="(name, address, phone_number, etc)">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-3">Field Type</label>
                                    <div class="col-9">
                                        <select name="" id="field_type" class="form-control select2">
                                            <option value="text">Text</option>
                                            <option value="number">Number</option>
                                            <option value="date">Date</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-12 text-right">
                                        <button type="button" class="btn btn-sm btn-icon btn-info" id="btn-add-field"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-3">Table Name</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" name="table_title" placeholder="">
                                    </div>
                                </div>
                                <table class="table table-bordered table-hover" id="table-field">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Field Name</th>
                                            <th class="text-center">Field Type</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_project" value="{{$csms->id}}">
                        <input type="hidden" name="id_step" id="id_step_su">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="button" id="btn-su-submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="suAddRowModal" tabindex="-1" role="dialog" aria-labelledby="addNotes" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content" id="addRowContent">

            </div>
        </div>
    </div>
    <div class="modal fade" id="suEditFieldModal" tabindex="-1" role="dialog" aria-labelledby="addNotes" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content" id="addRowContent">

            </div>
        </div>
    </div>
    <div class="modal fade" id="linkAddModal" tabindex="-1" role="dialog" aria-labelledby="addNotes" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Add Link</h3>
                </div>
                <form action="{{ route('qhse.csms.links.create') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="" class="col-form-label col-3">Link</label>
                            <div class="col-9">
                                <input type="text" class="form-control" name="link">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_step" id="id_step_link">
                        <input type="hidden" name="id_csms" value="{{ $csms->id }}">
                        <button type="button" class="btn btn-light-primary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
<script>
    function modal_link(x){
        $("#id_step_link").val(x)
    }

    function delete_ol(x) {
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
                location.href = "{{route('qhse.csms.ol.delete')}}/"+x
            }
        })
    }

    function edit_ol(x) {
        $("#olEditModal").modal('show')
        $.ajax({
            url: "{{route('qhse.csms.ol.get')}}/"+x,
            type: "GET",
            dataType: "json",
            cache: false,
            success: function (response) {
                tinymce.get('ol-edit-notes').setContent(response.notes)
                $("#ol-edit-title").val(response.title)
                $("#ol-id").val(x)
            }
        })
    }

    function su_add(x) {
        $.ajax({
            url: "{{route('qhse.csms.su.form')}}/"+x,
            type: "get",
            cache: false,
            success: function (response) {
                $("#addRowContent").html("")
                $("#addRowContent").append(response)
            }
        })
    }

    function su_delete(x,y){
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
                    url: "{{route('qhse.csms.su.delete_row')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        _token: "{{csrf_token()}}",
                        _id: x,
                        _index: y
                    },
                    cache: false,
                    success: function (response) {
                        if (response.delete === 1){
                            location.reload()
                        } else {
                            Swal.fire('Error occured', "Please contact your system administration", 'error')
                        }
                    }
                })
            }
        })
    }

    function delete_su(x) {
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
                location.href = "{{route('qhse.csms.su.delete')}}/"+x
            }
        })
    }

    function read_more(x) {
        $("#readModal").modal('show')
        $.ajax({
            url: "{{route('qhse.csms.ol.get')}}/"+x,
            type: "GET",
            dataType: "json",
            cache: false,
            success: function (response) {
                $("#read-more").html(response.notes)
                $("#read-title").html(response.title)
            }
        })
    }

    function share_button(x){
        $("#shareFile").val(x)
    }

    function set_data(x, y, z) {
        $("#"+x+".")
    }

    function show_modal(x, y) {
        $(x).val(y)
        $("#table-field > tbody").html("")
    }

    function ol_modal(x) {
        $("#id_step_ol").val(x)
    }

    function tt_modal(x) {
        $("#id_step_tt").val(x)
    }

    function tt_delete(x) {
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
                location.href = "{{route('qhse.csms.tt.delete')}}/"+x
            }
        })
    }

    function tt_follow(btn, x) {
        var td = $(btn).parent()
        var tr = td.parent()
        $.ajax({
            url: "{{route('qhse.csms.tt.follow')}}/"+x,
            type: "get",
            dataType: "json",
            success: function(response){
                var i = $(btn).find('i')
                console.log(i)
                if (response === 1){
                    i.removeClass("fa-check")
                    i.addClass("fa-times")
                    $(btn).removeClass("btn-success")
                    $(btn).addClass("btn-light-dark")
                    tr.addClass('bg-success')
                } else {
                    i.addClass("fa-check")
                    i.removeClass("fa-times")
                    $(btn).addClass("btn-success")
                    $(btn).removeClass("btn-light-dark")
                    tr.removeClass('bg-success')
                }
            }
        })
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
                    url : "{{URL::route('qhse.csms.files.delete')}}/" + x,
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
    var wizard2 = function () {
        var _wizardEl;
        var _wizard;

        // Private functions
        var initWizard = function () {
            // Initialize form wizard
            _wizard = new KTWizard(_wizardEl, {
                startStep: 1, // initial active step number
                clickableSteps: true // to make steps clickable this set value true and add data-wizard-clickable="true" in HTML for class="wizard" element
            });

            // Change event
        }

        return {
            // public functions
            init: function () {
                _wizardEl = KTUtil.getById('wizard2');

                initWizard();
            }
        };
    }()

    // Class definition
    var KTTagify = function() {

        // Private functions
        var demo1 = function(x, y) {
            var input = document.getElementById(x),
                // init Tagify script on the above inputs
                tagify = new Tagify(input, {
                    whitelist: [],
                    blacklist: [], // <-- passed as an attribute in this demo
                })


            // "remove all tags" button event listener
            document.getElementById(y).addEventListener('click', tagify.removeAllTags.bind(tagify))

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

        return {
            // public functions
            init: function(x,y) {
                demo1(x,y);
            }
        };
    }();

    function modal_mom(x, y, z){
        $("#momMeeting").modal('show')
        $("#id_meeting").val(x)
        if (y === 1){
            $("#meeting-download").show()
            $("#meeting-download").attr('href', "{{route('download')}}/"+z)
        } else {
            $("#meeting-download").hide()
            $("#meeting-download").attr('href', "")
        }
    }

    var KTCalendarBasic = function() {

        return {
            //main function to initiate the module
            init: function(x) {
                var todayDate = moment().startOf('day');
                var YM = todayDate.format('YYYY-MM');
                var YESTERDAY = todayDate.clone().subtract(1, 'day').format('YYYY-MM-DD');
                var TODAY = todayDate.format('YYYY-MM-DD');
                var TOMORROW = todayDate.clone().add(1, 'day').format('YYYY-MM-DD');
                var data = JSON.parse($.ajax({
                    url: "{{route('qhse.csms.meetings.get', $csms->id)}}",
                    type: "get",
                    dataType: "json",
                    cache: false,
                    async: false
                }).responseText)
                console.log(data)
                var list = []
                for (const key in data) {
                    if (data[key].id_step == x){
                        // console.log(data[key].file_mom)
                        var row = []
                        var classBg = ""
                        var dw = 0
                        if (data[key].file_mom == null || data[key].file_mom === ""){
                            classBg = "fc-event-solid-info"
                            dw = 0
                        } else {
                            classBg = "fc-event-solid-success"
                            dw = 1
                        }
                        row.title = "Meeting "+data[key].subject
                        row.url = "Javascript:modal_mom("+data[key].id+", "+dw+", '"+data[key].file_mom+"')"
                        row.start = data[key].date
                        row.className = classBg+" fc-event-light"
                        row.description = "Start: "+data[key].time+" | Duration: "+data[key].duration+" hour(s)"
                        list.push(row)
                    }
                }

                function meeting_modal(x, y) {
                    $("#meetingModal").modal('show')
                    $("#dateMeeting").val(x)
                    $("#id_step").val(y)
                    console.log(x)
                }

                var calendarEl = document.getElementById('kt_calendar_'+x);
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    plugins: [ 'bootstrap', 'interaction', 'dayGrid', 'timeGrid', 'list' ],
                    themeSystem: 'bootstrap',
                    displayEventTime : false,
                    isRTL: KTUtil.isRTL(),
                    selectable: true,
                    dateClick : function (info){
                        // window.location.href = "meetingscheduler/"+btoa(info.dateStr)
                        meeting_modal(info.dateStr, x)

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
                    events: list,

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

    $(document).ready(function(){
        wizard2.init()
        @foreach($step as $key => $item)
            @if(isset($input_step[$item->id]))
                @foreach($input_step[$item->id] as $list)
                    @if($list == "ms")
                        KTCalendarBasic.init('{{$item->id}}')
                    @endif
                @endforeach
            @endif
        @endforeach
        KTTagify.init('atendees-meeting', 'atendees-meeting-remove')
        $("table.display").DataTable()

        var field = 0;

        $("#btn-add-field").click(function(){
            var name = $("#field_name").val()
            var type = $("#field_type option:selected").val()
            var tbody = $("#table-field > tbody:last-child")
            if (name !== ""){
                var tr = "<tr>" +
                    "<td align='center'>"+name+"<input type='hidden' name='field_name[]' value='"+name+"'></td>" +
                    "<td align='center'>"+type+"<input type='hidden' name='field_type[]' value='"+type+"'></td>" +
                    "<td align='center'><button type='button' class='btn btn-xs btn-icon btn-danger' id='btn_delete_"+name+"'><i class='fa fa-trash'></i></button></td>" +
                    "</tr>"
                tbody.append(tr)
                field++
            } else {
                Swal.fire('Warning', 'Please fill the form!', 'warning')
            }
            $("#field_name").val("")
            $("#field_type").val("text").trigger("change")
            $("#field_name").focus()

            $("#btn_delete_"+name).click(function(){
                var td = $(this).parent()
                var tr = $(td).parent()
                $(tr).remove()
                field--
            })
        })

        $("#btn-su-submit").click(function(){
            if (field > 0){
                $("#form-su").submit()
            }
        })

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
    })
</script>
@endsection
