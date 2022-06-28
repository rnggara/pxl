@extends('layouts.template')
@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
                <h3>Detail</h3>
            </div>
            <div class="card-toolbar">
                <a href="{{route('mv.index')}}" class="btn btn-xs btn-secondary"><i class="fa fa-backspace"></i> Back</a>
            </div>
        </div>
        <div class="card-body">
            <div class="alert alert-secondary col-md-6">
                <div class="row">
                    <div class="col-md-10">
                        <div class="well">
                            <table width="100%">
                                <tr>
                                    <td class="text-left">Meeting Date Time</td>
                                    <td class="text-left">:</td>
                                    <td class="text-left"><b>{{date('l, d F Y | H:i',strtotime($detail->date_main))}}</b></td>
                                </tr>
                                <tr>
                                    <td class="text-left">Estimate Meeting End Time</td>
                                    <td class="text-left">:</td>
                                    <td class="text-left"><b>{{date('l, d F Y | H:i',strtotime($detail->date_end))}}</b></td>
                                </tr>
                                <tr>
                                    <td class="text-left">Topic</td>
                                    <td class="text-left">:</td>
                                    <td class="text-left"><b>{{$detail->topic}}</b></td>
                                </tr>
                                <tr>
                                    <td class="text-left">Location</td>
                                    <td class="text-left">:</td>
                                    <td class="text-left"><b>{{$detail->location}}</b></td>
                                </tr>
                            </table>

                        </div>
                    </div>
                </div>
            </div>

            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#all">
                        <span class="nav-icon">
                            <i class="flaticon-eye"></i>
                        </span>
                        <span class="nav-text">Attendance</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#sales" aria-controls="profile">
                        <span class="nav-icon">
                            <i class="flaticon-file-2"></i>
                        </span>
                        <span class="nav-text">MOM</span>
                    </a>
                </li>
            </ul>
            <div class="tab-content mt-5" id="myTabContent">
                <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="home-tab">
                    <table class="table table-bordered table-hover table-checkable" id="kt_datatable1a" style="margin-top: 13px !important">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Name</th>
                            <th class="text-center">Position</th>
                            <th>Email</th>
                            <th class="text-center">Phone</th>
                            <th class="text-center">Signature</th>
                            <th class="text-center"><button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#addAttendance"><i class="fa fa-plus"></i>Add</button></th>
                        </tr>
                        </thead>
                    </table>
                    <div class="modal fade" id="addAttendance" tabindex="-1" role="dialog" aria-labelledby="addAttendance" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Add Attendance</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <i aria-hidden="true" class="ki ki-close"></i>
                                    </button>
                                </div>
                                <form method="post" id="form_guest" action="#" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="id" value="{{$detail->id_main}}">
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                    <label class="col-md-2 col-form-label text-right">Name</label>
                                                    <div class="col-md-10">
                                                        <input type="text" class="form-control" placeholder="Full Name" name="name" required>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-md-2 col-form-label text-right">Company</label>
                                                    <div class="col-md-10">
                                                        <input type="text" class="form-control" placeholder="Company" name="company" required>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-md-2 col-form-label text-right">Position</label>
                                                    <div class="col-md-10">
                                                        <input type="text" class="form-control" placeholder="Position" name="emp_position" required>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-md-2 col-form-label text-right">Email</label>
                                                    <div class="col-md-10">
                                                        <input type="email" class="form-control" placeholder="ex: example@mail.com" name="email" required>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-md-2 col-form-label text-right">Phone Number</label>
                                                    <div class="col-md-10">
                                                        <input type="number" class="form-control" placeholder="ex: 01234567890" name="phone" required>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-md-2 col-form-label text-right">Signature</label>
                                                    <div class="col-md-8">
                                                        <input type="radio" name="signature" id="upload" value="upload">&nbsp;&nbsp;&nbsp;&nbsp; Upload
                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                        <input type="radio" name="signature" id="sketch" value="sketch">&nbsp;&nbsp;&nbsp;&nbsp; Sketch
                                                    </div>
                                                </div>
                                                <div class="form-group row" id="group-file">
                                                    <label class="col-md-2 col-form-label text-right">Signature File</label>
                                                    <div class="col-sm-8">
                                                        <input type="file" name="file" id="up_file_guest" class="form-control" placeholder="Select File">
                                                    </div>
                                                </div>
                                                <div class="form-group row" id="group-sketch">
                                                    <label class="col-md-2 col-form-label text-right">Signature Sketch</label>
                                                    <div class="col-sm-8">
                                                        <div class="wrapper">
                                                            <canvas class="signature-pad"></canvas>
                                                        </div>
                                                        <br>
                                                        <button type="button" class="btn btn-primary btn-xs clear">Clear</button>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                                        <button type="submit"name="submit_guest" value="ok_guest" id="savepad" class="btn btn-primary font-weight-bold">
                                            <i class="fa fa-check"></i>
                                            Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="sales" role="tabpanel" aria-labelledby="profile-tab">
                    <table class="table table-bordered table-hover table-checkable" id="kt_datatable1b" style="margin-top: 13px !important">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Time</th>
                            <th>Speaker</th>
                            <th class="text-center">PIC</th>
                            <th>Minute</th>
                            <th class="text-center">Action</th>
                            <th class="text-center">Deadline</th>
                            <th class="text-center"><button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#addMOM"><i class="fa fa-plus"></i>Add</button></th>
                        </tr>
                        </thead>

                    </table>
                    <div class="modal fade" id="addMOM" tabindex="-1" role="dialog" aria-labelledby="addMOM" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Add Management Visit</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <i aria-hidden="true" class="ki ki-close"></i>
                                    </button>
                                </div>
                                <form method="post" id="form-add" action="{{route('mv.detail.storeMOM')}}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                    <label class="col-md-2 col-form-label text-right">Speaker</label>
                                                    <div class="col-md-10">
                                                        <input type="text" class="form-control" placeholder="Speaker" name="speaker" required>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-md-2 col-form-label text-right">PIC</label>
                                                    <div class="col-md-10">
                                                        <input type="text" class="form-control" placeholder="PIC" name="pic2" required>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-md-2 col-form-label text-right">Minutes</label>
                                                    <div class="col-md-10">
                                                        <textarea name="minute" class="form-control" rows="5" placeholder="Write Minutes Of Meeting Here . ."></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-md-2 col-form-label text-right">Action</label>
                                                    <div class="col-md-10">
                                                        <textarea name="action" class="form-control" rows="5" placeholder="Write Action Here . ."></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-md-2 col-form-label text-right">Deadline</label>
                                                    <div class="col-md-10">
                                                        <input type="date" name="deadline" class="form-control" value="{{date('Y-m-d')}}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="id" value="{{$id_main}}">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                                        <button type="submit" name="submit_mom" value="save" id="savemom" class="btn btn-primary font-weight-bold">
                                            <i class="fa fa-plus"></i>
                                            Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script src="{{asset('theme/assets/js/signature_pad.js')}}"></script>
    <script src="{{asset('theme/tinymce/tinymce.min.js')}}"></script>
    <script>tinymce.init({ selector:'textarea', height: 250 });</script>
    <script>
        $(document).ready(function(){
            var wrapper     = document.getElementById("form_guest"),
                saveButton  = wrapper.querySelector("[name=submit_guest]"),
                canvas      = wrapper.querySelector("canvas"),
                signaturePad;

            signaturePad    = new SignaturePad(canvas);

            saveButton.addEventListener('click',function (event){
                event.preventDefault();
                if($("#upload").is(':checked')) {
                    $.ajax({
                        url         : '{{route('mv.file.save')}}',
                        type        : 'POST',
                        data        : new FormData(wrapper),
                        contentType : false,
                        cache       : false,
                        processData : false,
                        success      : function(result) {
                            location.reload();
                        }
                    });
                    return false;

                } else {
                    var id           = $('input[name="id"]').val();
                    var name         = $('input[name="name"]').val();
                    var company      = $('input[name="company"]').val();
                    var emp_position = $('input[name="emp_position"]').val();
                    var email        = $('input[name="email"]').val();
                    var phone        = $('input[name="phone"]').val();
                    var dataUrl      = signaturePad.toDataURL();

                    var ardata       = {
                        imageData: dataUrl,
                        id: id, name: name,
                        emp_position: emp_position,
                        email: email,
                        company: company,
                        phone: phone,
                        _token: '{{csrf_token()}}',
                    };
                    $.ajax({
                        type    : 'POST',
                        url     : '{{route('mv.sign.save')}}',
                        data    : ardata,
                        success : function(result) {
                            location.reload()
                        }
                    });
                    return false;
                }
            })


            $('#upload').prop('checked', true);
            if($('#upload').is(':checked')) {
                $('#group-file').show();
                $('#group-sketch').hide();
            } else {
                $('#group-file').hide();
                $('#group-sketch').show();
            }
            $('#upload').click(function(){
                $('#group-file').show();
                $('#group-sketch').hide();
                signaturePad.clear();
            });
            $('#sketch').click(function(){
                $('#group-file').hide();
                $('#group-sketch').show();
            });

            $('.clear').click(function() {
                signaturePad.clear();
            });

            $("#btn_modal_mom").click(function() {
                console.log('btn modal mom');
            });

            $('#kt_datatable1a').DataTable({
                'ajax':'{{route('mv.getAbsence',['id' => $id_main])}}',
                'type' : 'GET',
                dataSrc: 'responseData',

                'columns' :[
                    { "data": "no" },
                    { "data": "name" },
                    { "data": "position" },
                    { "data": "email" },
                    { "data": "phone" },
                    { "data": "signature" },
                    { "data": "action" },
                ],
                'columnDefs': [
                    {
                        "targets": 0,
                        "className": "text-center",
                    },
                    {
                        "targets": 2,
                        "className": "text-center",
                    },
                    {
                        "targets": 4,
                        "className": "text-center",
                    },
                    {
                        "targets": 5,
                        "className": "text-center",
                    },
                    {
                        "targets": 6,
                        "className": "text-center",
                    },

                ]
            })

            $('#kt_datatable1b').DataTable({
                'ajax':'{{route('mv.getMom',['id' => $id_main])}}',
                'type' : 'GET',
                dataSrc: 'responseData',

                'columns' :[
                    { "data": "no" },
                    { "data": "time" },
                    { "data": "speaker" },
                    { "data": "PIC" },
                    { "data": "minute" },
                    { "data": "action" },
                    { "data": "deadline" },
                    { "data": "deledit" },
                ],
                'columnDefs': [
                    {
                        "targets": 0,
                        "className": "text-center",
                    },
                    {
                        "targets": 1,
                        "className": "text-center",
                    },
                    {
                        "targets": 3,
                        "className": "text-center",
                    },
                    {
                        "targets": 5,
                        "className": "text-center",
                    },
                    {
                        "targets": 6,
                        "className": "text-center",
                    },
                    {
                        "targets": 7,
                        "className": "text-center",
                    },

                ]
            })
        });
    </script>
@endsection
