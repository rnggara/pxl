@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                Request File
            </div>
            <div class="card-toolbar">

                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-line bg-white pb-5 pt-5" id="pageTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="" data-toggle="tab" href="#request-tab">
                                <span class="nav-icon">
                                    <i class="fas fa-star"></i>
                                </span>
                        <span class="nav-text">Request</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="" data-toggle="tab" href="#approval-tab" aria-controls="profile">
                                <span class="nav-icon">
                                    <i class="fas fa-sticky-note"></i>
                                </span>
                        <span class="nav-text">Approval</span>
                    </a>
                </li>
            </ul>
            <div class="tab-content mt-5" id="pageTab">
                <div class="tab-pane fade show active" id="request-tab" role="tabpanel" aria-labelledby="home-tab">
                    <div class="row">
                        <div class="col-md-4 mx-auto row">
                            <div class="input-group">
                                <input type="text" class="form-control" id="txt-search-file" placeholder="Search for..."/>
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button" id="btn-search-file"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-10">
                        <div class="col-md-8 mx-auto">
                            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                <table class="table table-bordered table-hover display font-size-sm" id="table-data" style="margin-top: 13px !important; width: 100%;">
                                    <thead>
                                    <tr>
                                        <th nowrap="nowrap" class="text-left">#</th>
                                        <th nowrap="nowrap" class="text-left">File Name</th>
                                        <th nowrap="nowrap" class="text-center">Owner Approval</th>
                                        <th nowrap="nowrap" class="text-center">Dir. Approval</th>
                                        <th class="text-center"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($requests as $key => $request)
                                            <tr>
                                                <td align="center">{{$key + 1}}</td>
                                                <td>
                                                    {{$file_name[$request->file_hash]}}&nbsp;
                                                    @if(!empty($request->own_approved_at) && !empty($request->dir_approved_at))
                                                        <a href="{{route('download', $request->file_hash)}}" class="fa fa-download" target="_blank"></a>
                                                    @endif
                                                </td>
                                                <td align="center">
                                                    @if(empty($request->own_approved_at))
                                                        waiting
                                                    @else
                                                        approved at {{date('Y-m-d', strtotime($request->own_approved_at))}}
                                                    @endif
                                                </td>
                                                <td align="center">
                                                    @if(empty($request->dir_approved_at))
                                                        waiting
                                                    @else
                                                        approved at {{date('Y-m-d', strtotime($request->dir_approved_at))}}
                                                    @endif
                                                </td>
                                                <td align="center">
                                                    <button class="btn btn-danger btn-icon btn-xs" onclick="button_delete('{{$request->id}}')"><i class="fa fa-trash"></i></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="approval-tab" role="tabpanel" aria-labelledby="home-tab">
                    <div class="row mt-10">
                        <div class="col-md-8 mx-auto">
                            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                                    <thead>
                                    <tr>
                                        <th nowrap="nowrap" class="text-left">#</th>
                                        <th nowrap="nowrap" class="text-left">File Name</th>
                                        <th nowrap="nowrap" class="text-center">Owner Approval</th>
                                        <th nowrap="nowrap" class="text-center">Dir. Approval</th>
                                        <th class="text-center"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($approvals as $key => $approval)
                                        <tr>
                                            <td align="center">{{$key + 1}}</td>
                                            <td>
                                                {{$file_name[$approval->file_hash]}}
                                            </td>
                                            <td align="center">
                                                @if(empty($approval->own_approved_at))
                                                    <button type="button" onclick="button_approve({{$approval->id}}, 'own', 'Approve')" class="btn btn-xs btn-primary">Waiting</button>
                                                @else
                                                    <button type="button" onclick="button_approve({{$approval->id}}, 'own', 'Unapprove')" class="btn btn-xs btn-success">Approved</button>
                                                @endif
                                            </td>
                                            <td align="center">
                                                @if(empty($approval->dir_approved_at))
                                                    @if(empty($approval->own_approved_at))
                                                        <button type="button" class="btn btn-xs btn-primary disabled">Waiting</button>
                                                    @else
                                                        <button type="button" onclick="button_approve({{$approval->id}}, 'dir', 'Approve')" class="btn btn-xs btn-primary">Waiting</button>
                                                    @endif
                                                @else
                                                    <label class="btn btn-xs btn-success">Approved</label>
                                                @endif
                                            </td>
                                            <td align="center">
                                                <button class="btn btn-danger btn-icon btn-xs" onclick="button_delete('{{$approval->id}}')"><i class="fa fa-trash"></i></button>
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

        </div>
    </div>
@endsection
@section('custom_script')
    <link href="{{asset('theme/jquery-ui/jquery-ui.css')}}" rel="Stylesheet">
    <script src="{{asset('theme/jquery-ui/jquery-ui.js')}}"></script>
    <script>

        function button_approve(x, y, z) {
            Swal.fire({
                title: z,
                text: z +" this request?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Submit'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{URL::route('rf.approve')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            '_token' : "{{csrf_token()}}",
                            'req' : x,
                            'appr' : y,
                        },
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

        function button_delete(x){
            Swal.fire({
                title: 'Delete',
                text: "Delete this request?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{URL::route('rf.delete')}}/" + x,
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

        $(document).ready(function () {
            $('table.display').DataTable({
                'ordering': false,
                "responsive": true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            })

            $("#btn-search-file").click(function(){
                var x = $("#txt-search-file").val()
                $.ajax({
                    url: "{{route('rf.find')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        '_token': "{{csrf_token()}}",
                        'req' : x,
                    },
                    cache: false,
                    success: function(response){
                        console.log(response)
                        if (response.error == 0){
                            Swal.fire({
                                title: 'File Found',
                                text: "Request this file?",
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        url : "{{URL::route('rf.request')}}",
                                        type: "post",
                                        data: {
                                            '_token': "{{csrf_token()}}",
                                            'req' : response.data.code
                                        },
                                        dataType: "json",
                                        cache: "false",
                                        success: function(response){
                                            if (response.error == 0){
                                                location.reload()
                                            } else if (response.error == 1) {
                                                Swal.fire('Error occured', 'Please contact your administrator!', 'error')
                                            } else {
                                                Swal.fire('File already requested', 'Please wait for the approval', 'warning')
                                            }
                                        }
                                    })
                                }
                            })
                        } else {
                            Swal.fire('File not found', 'The file you are looking for was not found', 'error')
                        }
                    }
                })
            })

            $("#modalSetting select.select2").select2({
                width: "100%"
            })

            var val = []
            val['data'] = src

            console.log(val)

            console.log(hisdata)

        });

    </script>
@endsection
