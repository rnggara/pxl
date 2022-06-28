@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">BA Form - {{ strtoupper($type) }}</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <a href="{{ route('oletter.index') }}" class="btn btn-icon btn-success"><i class="fa fa-arrow-left"></i></a>
                    <a class="btn btn-primary" href="{{ route('oletter.print', ["id" => $ol->id, "type" => "sp"]) }}"><i class="fa fa-print"></i> Surat Pelaporan</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-2 mb-5">
                    <table>
                        <tr>
                            <td>BA Number</td>
                            <td>:</td>
                            <td class="font-weight-bold">{{ $ol->ba_num }}</td>
                        </tr>
                        <tr>
                            <td>Title</td>
                            <td>:</td>
                            <td class="font-weight-bold">{{ $ol->title }}</td>
                        </tr>
                        <tr>
                            <td>Reported By</td>
                            <td>:</td>
                            <td class="font-weight-bold">{{ $ol->ba_by }}</td>
                        </tr>
                        <tr>
                            <td>Description</td>
                            <td>:</td>
                            <td class="font-weight-bold">{{ strip_tags($ol->description) }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-2">
                    @if (!empty($ol->problems_at))
                        <a href="{{ route('oletter.print', ["id" => $ol->id, "type" => "st"]) }}" target="_blank" class="btn btn-block btn-primary py-10 font-size-h3">
                            Print <br>Surat Tugas
                        </a>
                    @endif
                </div>
                <div class="col-2">
                    @if (!empty($ol->problems_at))
                        <a class="btn btn-block {{ (empty($ol->actions_at)) ? "btn-success" : "btn-secondary" }} py-10 font-size-h3" data-toggle="tab" id="actions" href="#actions-tab">
                            <span class="nav-text">Input <br>Follow Up</span>
                        </a>
                    @endif
                </div>
                <div class="col-2">
                    @if (!empty($ol->actions_at))
                        <a class="btn btn-block {{ (empty($ol->man_approved_at)) ? "btn-warning" : "btn-secondary" }} py-10 font-size-h3" target="_blank" href="{{ route('oletter.print', ["id" => $ol->id, "type" => "ap"]) }}">
                            <span class="nav-text">Client <br>Approve</span>
                        </a>
                    @endif
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-12 mt-5">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="problems-tab" role="tabpanel" aria-labelledby="problems-tab">
                            <table class="table table-bordered table-hover display">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Description</th>
                                        <th class="text-center">Attachment</th>
                                        <th class="text-center">Reported By</th>
                                        <th class="text-center">Reported At</th>
                                        @if (empty($ol->problems_at))
                                            <th class="text-center">
                                                @if (empty($ol->problems_at))
                                                    <button type="button" data-toggle="modal" data-target="#modalForm" class="btn btn-primary btn-icon btn-sm"><i class="fa fa-plus"></i></button>
                                                @endif
                                            </th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $actions_num = 0;
                                    @endphp
                                    @foreach ($detail as $i => $item)
                                        @php
                                            $img_problems = asset('assets/media/users/default.jpg');
                                            if (isset($file[$item->problems_attachment])) {
                                                $img_problems = str_replace("public", "public_html", asset($file[$item->problems_attachment]));
                                            }
                                            if(!empty($item->actions)){
                                                $actions_num++;
                                            }
                                        @endphp
                                        <tr>
                                            <td align="center">{{ $i+1 }}</td>
                                            <td>{!! $item->problems !!}</td>
                                            <td align="center" style="vertical-align: center">
                                                <a href="{{ $img_problems }}" download>
                                                    <div class="symbol symbol-100 mr-3">
                                                        <img alt="Pic" src="{{ $img_problems }}"/>
                                                    </div>
                                                </a>
                                            </td>
                                            <td align="center">{!! $item->created_by !!}</td>
                                            <td align="center">{!! $item->created_at !!}</td>
                                            @if (empty($ol->problems_at))
                                                <td align="center">
                                                    <button type="button" onclick="_delete({{ $item->id }})" class="btn btn-danger btn-sm btn-icon"><i class="fa fa-trash"></i></button>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="row mt-5">
                                <div class="col-12 text-right">
                                    <form action="{{ route('oletter.form_update') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="_id" value="{{ $ol->id }}">
                                        <input type="hidden" name="_type" value="problems">
                                        @if (empty($ol->actions_at) && count($detail) > 0 && $actions_num == 0)
                                            @if (empty($ol->problems_at))
                                                <button type="submit" name="status" value="done" class="btn btn-primary">Approve to follow up</button>
                                            @else
                                                <button type="submit" name="status" value="cancel" class="btn btn-danger">Cancel</button>
                                            @endif
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="actions-tab" role="tabpanel" aria-labelledby="actions-tab">
                            <table class="table table-bordered table-hover display">
                                <thead class="table-success">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Description</th>
                                        <th class="text-center">Attachment</th>
                                        <th class="text-center">Reported By</th>
                                        <th class="text-center">Reported At</th>
                                        <th class="text-center">Action</th>
                                        <th class="text-center">Attachment</th>
                                        <th class="text-center">Action Reported At</th>
                                        <th class="text-center">Action Reported By</th>
                                        @if (empty($ol->actions_at))
                                            <th class="text-center">
                                                {{-- <a class="btn btn-success btn-icon btn-sm" data-toggle="tab" href="#problems-tab">
                                                    <i class="fa fa-arrow-left"></i>
                                                </a> --}}
                                            </th>
                                        @endif
                                        @if (empty($ol->problems_at))
                                            <th class="text-center"></th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $actions_num = 0;
                                    @endphp
                                    @foreach ($detail as $i => $item)
                                        @php
                                            $img_problems = asset('assets/media/users/default.jpg');
                                            if (isset($file[$item->problems_attachment])) {
                                                $img_problems = str_replace("public", "public_html", asset($file[$item->problems_attachment]));
                                            }
                                            if(!empty($item->actions)){
                                                $actions_num++;
                                            }
                                        @endphp
                                        <tr>
                                            <td align="center">{{ $i+1 }}</td>
                                            <td>{!! $item->problems !!}</td>
                                            <td align="center" style="vertical-align: center">
                                                <a href="{{ $img_problems }}" download>
                                                    <div class="symbol symbol-100 mr-3">
                                                        <img alt="Pic" src="{{ $img_problems }}"/>
                                                    </div>
                                                </a>
                                            </td>
                                            <td align="center">{!! $item->created_by !!}</td>
                                            <td align="center">{!! $item->created_at !!}</td>
                                            @php
                                                $action = "waiting";
                                                $attachment = "waiting";
                                                $report = "waiting";
                                                $report_by = "waiting";

                                                $img_actions = asset('assets/media/users/default.jpg');
                                                if (isset($file[$item->actions_attachment])) {
                                                    $img_actions = str_replace("public", "public_html", asset($file[$item->actions_attachment]));
                                                }
                                            @endphp
                                            <td class="text-center">
                                                @if (empty($item->actions))
                                                    waiting
                                                @else
                                                    {!! $item->actions !!}
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if (empty($item->actions))
                                                    waiting
                                                @else
                                                    <a href="{{ $img_actions }}" download>
                                                        <div class="symbol symbol-100 mr-3">
                                                            <img alt="Pic" src="{{ $img_actions }}"/>
                                                        </div>
                                                    </a>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if (empty($item->actions))
                                                    waiting
                                                @else
                                                    {!! $item->actions_at !!}
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if (empty($item->actions))
                                                    waiting
                                                @else
                                                    {!! $item->actions_by !!}
                                                @endif
                                            </td>
                                            @if (empty($ol->actions_at))
                                            <td class="text-center">
                                                <button type="button" onclick="_action({{ $item->id }})" data-toggle="modal" data-target="#modalForm" class="btn btn-primary btn-icon"><i class="fa fa-pen-alt"></i></button>
                                            </td>
                                            @endif
                                            @if (empty($ol->problems_at))
                                                <td align="center">
                                                    <button type="button" onclick="_delete({{ $item->id }})" class="btn btn-danger btn-sm btn-icon"><i class="fa fa-trash"></i></button>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="row mt-5">
                                <div class="col-12 text-right">
                                    <form action="{{ route('oletter.form_update') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="_id" value="{{ $ol->id }}">
                                        <input type="hidden" name="_type" value="actions">
                                        @if ($actions_num > 0 && empty($ol->hse_approve_at))
                                            @if (empty($ol->actions_at))
                                                <button type="submit" name="status" value="done" class="btn btn-primary">Confirm follow up is complete</button>
                                            @else
                                                <button type="submit" name="status" value="cancel" class="btn btn-danger">Cancel</button>
                                            @endif
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="approve-tab" role="tabpanel" aria-labelledby="approve-tab">
                            <table class="table table-bordered table-hover display">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Description</th>
                                        <th class="text-center">Attachment</th>
                                        <th class="text-center">Reported By</th>
                                        <th class="text-center">Reported At</th>
                                        <th class="text-center">Action</th>
                                        <th class="text-center">Attachment</th>
                                        <th class="text-center">Action Reported At</th>
                                        <th class="text-center">Action Reported By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $actions_num = 0;
                                    @endphp
                                    @foreach ($detail as $i => $item)
                                        @php
                                            $img_problems = asset('assets/media/users/default.jpg');
                                            if (isset($file[$item->problems_attachment])) {
                                                $img_problems = str_replace("public", "public_html", asset($file[$item->problems_attachment]));
                                            }
                                            if(!empty($item->actions)){
                                                $actions_num++;
                                            }
                                        @endphp
                                        <tr>
                                            <td align="center">{{ $i+1 }}</td>
                                            <td>{!! $item->problems !!}</td>
                                            <td align="center" style="vertical-align: center">
                                                <a href="{{ $img_problems }}" download>
                                                    <div class="symbol symbol-100 mr-3">
                                                        <img alt="Pic" src="{{ $img_problems }}"/>
                                                    </div>
                                                </a>
                                            </td>
                                            <td align="center">{!! $item->created_by !!}</td>
                                            <td align="center">{!! $item->created_at !!}</td>
                                            @php
                                                $action = "waiting";
                                                $attachment = "waiting";
                                                $report = "waiting";
                                                $report_by = "waiting";

                                                $img_actions = asset('assets/media/users/default.jpg');
                                                if (isset($file[$item->actions_attachment])) {
                                                    $img_actions = str_replace("public", "public_html", asset($file[$item->actions_attachment]));
                                                }
                                            @endphp
                                            <td class="text-center">
                                                @if (empty($item->actions))
                                                    waiting
                                                @else
                                                    {!! $item->actions !!}
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if (empty($item->actions))
                                                    waiting
                                                @else
                                                    <a href="{{ $img_actions }}" download>
                                                        <div class="symbol symbol-100 mr-3">
                                                            <img alt="Pic" src="{{ $img_actions }}"/>
                                                        </div>
                                                    </a>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if (empty($item->actions))
                                                    waiting
                                                @else
                                                    {!! $item->actions_at !!}
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if (empty($item->actions))
                                                    waiting
                                                @else
                                                    {!! $item->actions_by !!}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="row mt-5">
                                <div class="col-12 text-right">
                                    <form action="{{ route('oletter.form_update') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="_id" value="{{ $ol->id }}">
                                        <input type="hidden" name="_type" value="man-approve">
                                        @if (empty($ol->hse_approve_at))
                                            @if (empty($ol->man_approve_at))
                                                <button type="submit" name="status" value="done" class="btn btn-primary">Approve</button>
                                            @else
                                                <button type="submit" name="status" value="cancel" class="btn btn-danger">Cancel</button>
                                            @endif
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="hse-approve-tab" role="tabpanel" aria-labelledby="approve-tab">
                            <table class="table table-bordered table-hover display">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Description</th>
                                        <th class="text-center">Attachment</th>
                                        <th class="text-center">Reported By</th>
                                        <th class="text-center">Reported At</th>
                                        <th class="text-center">Action</th>
                                        <th class="text-center">Attachment</th>
                                        <th class="text-center">Action Reported At</th>
                                        <th class="text-center">Action Reported By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $actions_num = 0;
                                    @endphp
                                    @foreach ($detail as $i => $item)
                                        @php
                                            $img_problems = asset('assets/media/users/default.jpg');
                                            if (isset($file[$item->problems_attachment])) {
                                                $img_problems = str_replace("public", "public_html", asset($file[$item->problems_attachment]));
                                            }
                                            if(!empty($item->actions)){
                                                $actions_num++;
                                            }
                                        @endphp
                                        <tr>
                                            <td align="center">{{ $i+1 }}</td>
                                            <td>{!! $item->problems !!}</td>
                                            <td align="center" style="vertical-align: center">
                                                <a href="{{ $img_problems }}" download>
                                                    <div class="symbol symbol-100 mr-3">
                                                        <img alt="Pic" src="{{ $img_problems }}"/>
                                                    </div>
                                                </a>
                                            </td>
                                            <td align="center">{!! $item->created_by !!}</td>
                                            <td align="center">{!! $item->created_at !!}</td>
                                            @php
                                                $action = "waiting";
                                                $attachment = "waiting";
                                                $report = "waiting";
                                                $report_by = "waiting";

                                                $img_actions = asset('assets/media/users/default.jpg');
                                                if (isset($file[$item->actions_attachment])) {
                                                    $img_actions = str_replace("public", "public_html", asset($file[$item->actions_attachment]));
                                                }
                                            @endphp
                                            <td class="text-center">
                                                @if (empty($item->actions))
                                                    waiting
                                                @else
                                                    {!! $item->actions !!}
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if (empty($item->actions))
                                                    waiting
                                                @else
                                                    <a href="{{ $img_actions }}" download>
                                                        <div class="symbol symbol-100 mr-3">
                                                            <img alt="Pic" src="{{ $img_actions }}"/>
                                                        </div>
                                                    </a>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if (empty($item->actions))
                                                    waiting
                                                @else
                                                    {!! $item->actions_at !!}
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if (empty($item->actions))
                                                    waiting
                                                @else
                                                    {!! $item->actions_by !!}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="row mt-5">
                                <div class="col-12 text-right">
                                    <form action="{{ route('oletter.form_update') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="_id" value="{{ $ol->id }}">
                                        <input type="hidden" name="_type" value="hse-approve">
                                        @if (empty($ol->hse_approve_at))
                                            <button type="submit" name="status" value="done" class="btn btn-primary">Approve</button>
                                        @else
                                            <button type="submit" name="status" value="cancel" class="btn btn-danger">Cancel</button>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalForm" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title">Form - {{ strtoupper($type) }}</h1>
                </div>
                <form action="{{ route('oletter.form.add') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group row">
                                    <label class="col-form-label col-3">Description</label>
                                    <div class="col-9">
                                        <textarea name="_description" id="txt-desc" cols="30" rows="10"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-3">Attachment</label>
                                    <div class="col-9">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="_file" accept="image/*" required>
                                            <span class="custom-file-label">Choose File</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="_id_detail" id="id-detail">
                        <input type="hidden" name="_id" value="{{ $ol->id }}">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{ asset('theme/tinymce/tinymce.min.js') }}"></script>
    <script>
        function _get_detail(id){
            $.ajax({
                url : "{{ route('oletter.detail_get') }}/" + id,
                type : "get",
                dataType : "json",
                cache : false,
                success : function(response){
                    tinymce.get('txt-desc').setContent("")
                    if(response.status){
                        var data = response.data
                        if(data.actions != null){
                            tinymce.get('txt-desc').setContent(data.actions)
                        }
                    }
                }
            })
        }

        function _action(id){
            $("#id-detail").val(id)
            _get_detail(id)
        }

        function _delete(id){
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!"
            }).then(function(result) {
                if (result.value) {
                    window.location.href = "{{ route('oletter.detail_delete') }}/" + id
                }
            });
        }
        $(document).ready(function(){
            tinymce.init({
                selector : "#modalForm textarea",
                menubar : false,
                toolbar : false
            })

            @if (\Session::get('tab'))
                var tab = "#{{ \Session::get('tab') }}"
                console.log(tab)
                $(tab).click()
            @endif

            $("table.display").DataTable()

            @if (\Session::get('msg'))
                var msg = '{{ \Session::get('msg') }}'
                if (msg == '1') {
                    Swal.fire('Data saved', 'Problems has been added', 'success')
                } else if(msg == '-1'){
                    Swal.fire('Data deleted', 'Data has been deleted', 'success')
                } else if(msg == '2'){
                    Swal.fire('Data Updated', '{{ ucwords(str_replace("-", " ", $type)) }} done', 'success')
                } else if(msg == '3'){
                    Swal.fire('Data Updated', '{{ ucwords(str_replace("-", " ", $type)) }} canceled', 'success')
                } else if(msg == '0'){
                    Swal.fire('Error Occured', 'Please contact your system administrator', 'error')
                }
            @endif
        })
    </script>
@endsection
