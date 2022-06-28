@extends('layouts.template')

@section('css')
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #contentModalPrint, #contentModalPrint * {
                visibility: visible;
            }

            #contentModalPrint .print-hide {
                visibility: hidden;
            }
            #contentModalPrint {
                position: absolute;
                left: 0;
                top: 0;
            }
        }
    </style>
@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">Berita Acara</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    @if (isset($_GET['s']))
                        <a href="{{ route('oletter.index') }}" class="btn btn-success"><i class="fa fa-arrow-left"></i>BA Waiting</a>
                    @else
                        <a href="{{ route('oletter.index') }}?s=bank" class="btn btn-success"><i class="fa fa-check-circle"></i>BA Bank</a>
                        <button type="button" data-toggle="modal" data-target="#modalBA" class="btn btn-primary"><i class="fa fa-plus"></i> Add New</button>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered table-hover display">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">pctg</th>
                                <th class="text-center"># Berita Acara</th>
                                <th class="text-center">BA Title</th>
                                <th class="text-center">BA Date</th>
                                <th class="text-center">Reported By</th>
                                <th class="text-center">Client Approve</th>
                                <th class="text-center">Close Problems</th>
                                <th class="text-center">Print</th>
                                <th class="text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ol as $i => $item)
                            @php
                                $ba_approve = "N/A";
                                $pctg = 0;
                                $bg = "#F64E60";
                                if (!empty($item->problems_at)) {
                                    $pctg += 25;
                                }

                                if(!empty($item->actions_at)){
                                    $pctg += 25;
                                }

                                if(!empty($item->man_approve_at)){
                                    $pctg += 25;
                                }

                                // if(!empty($item->hse_approve_at)){
                                //     $pctg += 25;
                                // }

                                if(!empty($item->approved_at)){
                                    $ba_approve = date("d F Y", strtotime($item->approved_at))."<br>".$item->approved_by;
                                    $pctg += 25;
                                }

                                if($pctg >= 40 && $pctg < 60){
                                    $bg = "#ffdd00";
                                } elseif ($pctg >= 60 && $pctg < 100) {
                                    $bg = "#fabc4d";
                                } elseif ($pctg >= 100) {
                                    $bg = "#08c96b";
                                }
                            @endphp
                                <tr>
                                    <td align="center">{{ $i + 1 }}</td>
                                    <td align="center" class="text-white font-weight-bold" style="background-color: {{ $bg }}">{{ number_format($pctg, 0) }} %</td>
                                    <td align="center">
                                        <a href="{{ route('oletter.form', ["type" => "details", "id" => $item->id]) }}">
                                            {{ $item->ba_num }}
                                        </a>
                                    </td>
                                    <td align="center">
                                        {{ $item->title }}
                                    </td>
                                    <td align="center">{{ date("d F Y", strtotime($item->ba_date)) }}</td>
                                    <td align="center">
                                        {{ $item->ba_by }}
                                    </td>
                                    <td align="center">
                                        @if (empty($item->actions_at))
                                            N/A
                                        @else
                                            @if (empty($item->man_approve_at))
                                                <a href="{{ route('oletter.print', ["id" => $item->id, "type" => "ap"]) }}">waiting</a>
                                            @else
                                                <a href="{{ route('oletter.print', ["id" => $item->id, "type" => "ap"]) }}">
                                                    {{ date('d F Y', strtotime($item->man_approve_at)) }} <br>
                                                    {{ $item->man_approve_by }}
                                                </a>
                                            @endif
                                        @endif
                                    </td>
                                    <td align="center">
                                        @if (empty($item->man_approve_at))
                                            N/A
                                        @else
                                            @if (empty($item->approved_at))
                                                <a href="{{ route('oletter.print', ["id" => $item->id, "type" => "hse"]) }}">waiting</a>
                                            @else
                                                {{ date('d F Y', strtotime($item->approved_at)) }}
                                                <br> {{ $item->approved_by }}
                                            @endif
                                        @endif
                                    </td>
                                    <td align="center">
                                        @if(!empty($item->approved_at))
                                            <a href="{{ route('oletter.print', ["id" => $item->id, "type" => "p"]) }}" class="btn btn-xs btn-icon btn-info"><i class="fa fa-print"></i></a>
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td align="center">
                                        <button type="button" onclick="_delete_data({{ $item->id }})" class="btn btn-sm btn-icon btn-danger"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalBA" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title">Add Berita Acara</h1>
                </div>
                <form action="{{ route('oletter.add') }}" method="post" enctype="multipart/form-data" id="form-ba">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-4">Date</label>
                                    <div class="col-8">
                                        <input type="date" class="form-control" value="{{ date("Y-m-d") }}" name="_date" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-4">BA Number</label>
                                    <div class="col-8">
                                        <input type="text" class="form-control" name="_num" value="{{ $ba_num }}" placeholder="BA Number" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-4">Title</label>
                                    <div class="col-8">
                                        <input type="text" class="form-control" name="_title" placeholder="Title" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-4">Reported by</label>
                                    <div class="col-8">
                                        <input type="text" class="form-control" name="_ba_by" placeholder="Reported By">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-4">Description</label>
                                    <div class="col-8">
                                        <textarea name="_description" class="form-control tmce" id="tmce-modal" cols="30" rows="10"></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <hr>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-12 text-center font-weight-bold">Problems</label>
                                </div>
                                <div class="form-group row">
                                    <div class="col-12" id="attachments">
                                        <div class="row mb-2">
                                            <div class="col-5">
                                                <div class="custom-file">
                                                    <input type="file" onchange="_change(this)" class="custom-file-input" accept="image/*" name="_attachment[]">
                                                    <div class="custom-file-label">
                                                        Choose File
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-5">
                                                <div class="custom-file">
                                                    <input type="text" class="form-control description" name="_attach_desc[]" placeholder="description" required>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <button type="button" class="btn btn-primary btn-sm btn-icon" id="btn-plus"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="btn-submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- <div class="modal fade" id="modalAdd" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title">Add Occurrence Letter</h1>
                </div>
                <form action="{{ route('oletter._add') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-4">Date</label>
                                    <div class="col-8">
                                        <input type="date" class="form-control" name="_date" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-4">Title</label>
                                    <div class="col-8">
                                        <input type="text" class="form-control" name="_title" placeholder="Title" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-4">Description</label>
                                    <div class="col-8">
                                        <textarea name="_description" class="form-control tmce" id="tmce-modal" cols="30" rows="10"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-12 text-center font-weight-bold">Attachment</label>
                                </div>
                                <div class="form-group row">
                                    <div class="col-12" id="attachments">
                                        <div class="row mb-2">
                                            <div class="col-5">
                                                <div class="custom-file">
                                                    <input type="file" onchange="_change(this)" class="custom-file-input" accept="image/*" name="_attachment[]" required>
                                                    <div class="custom-file-label">
                                                        Choose File
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-5">
                                                <div class="custom-file">
                                                    <input type="text" class="form-control" name="_attach_desc[]" placeholder="description" required>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <button type="button" class="btn btn-primary btn-sm btn-icon" id="btn-plus"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="_id" id="id-ba">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="btn-submit-add" onclick="_post()">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}
    <div class="modal fade" id="modalApprove" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content" id="contentModalPrint">

            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{ asset('theme/tinymce/tinymce.min.js') }}"></script>
    <script>
        function _ba(id){
            $("#id-ba").val(id)
            $.ajax({
                url : "{{ route('oletter._get') }}/" + id,
                type : "get",
                dataType : "json",
                cache : false,
                success : function(response){
                    tinymce.get("tmce-modal").setContent("")
                    if(response.status){
                        var data = response.data
                        console.log(data)
                        var title = $("#modalAdd").find("input[name=_title]")
                        var _date = $("#modalAdd").find("input[name=_date]")
                        var txtarea = $("#modalADd").find("textarea")
                        title.val(data.title)
                        _date.val(data.input_date)
                        if(data.ba_approved_at != null){
                            $("#attachments").hide()
                            $("#btn-submit-add").hide()
                        } else {
                            $("#attachments").show()
                            $("#btn-submit-add").show()
                        }
                        tinymce.get("tmce-modal").setContent(data.description)
                    }
                }
            })
        }

        function _delete(btn){
            var div = $(btn).parent()
            var row = div.parent()
            console.log(row)
            row.remove()
        }

        function _delete_data(id){
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!"
            }).then(function(result) {
                if (result.value) {
                    window.location.href = "{{ route('oletter._delete') }}/" + id
                }
            });
        }

        function _change(input){
            console.log($(input).val())
            var fileName = $(input).val();
            $(input).siblings(".custom-file-label").addClass("selected").html(fileName);
        }

        function _modal(type, id){
            $("#modalApprove").modal('show')
            $.ajax({
                url : "{{ route('oletter.detail') }}/" + type + "/" + id,
                type : "get",
                success : function(response){
                    $("#modalApprove .modal-content").html(response)
                    tinymce.init({
                        selector : "#modalApprove .tmce",
                        menubar : false,
                        toolbar : false,
                        readonly : 1
                    })
                }
            })
        }

        $(document).ready(function(){

            $("#btn-submit").click(function(e){
                e.preventDefault();
                var fo = $(this).parents('form');
                var required = []

                var title = fo.find('input[name=_title]')
                if(title.val() == ""){
                    required.push('Title')
                }

                var desc = tinymce.get('tmce-modal').getContent()
                if(desc == ""){
                    required.push('Description')
                    Swal.fire('Required', 'Description is required!', 'error')
                }

                var file = fo.find('input[type=file]')
                var fileq_req = 0
                file.each(function(){
                    console.log($(this).val())
                    if($(this).val() == ""){
                        fileq_req++
                        // required.push('File')
                        // Swal.fire('Required', 'File is required!', 'error')
                    }
                })

                if(fileq_req > 0){
                    required.push('File')
                }

                var desc_file = fo.find('.description')
                console.log(desc_file)
                var descq_req = 0
                desc_file.each(function(){
                    console.log($(this).val())
                    if($(this).val() == ""){
                        descq_req++
                        // required.push('File')
                        // Swal.fire('Required', 'File is required!', 'error')
                    }
                })

                if(descq_req > 0){
                    required.push('Description File')
                }

                if(required.length > 0){
                    var msg = "";
                    for (let index = 0; index < required.length; index++) {
                        msg += required[index] + "<br>"

                    }
                    Swal.fire('Required', msg + ' is required!', 'error')
                } else {
                    _post()
                    $("#form-ba").submit()
                }
                console.log(desc)
                console.log(fileq_req)
                console.log(descq_req)
                // $("#form-ba").submit()
            })

            tinymce.init({
                selector : "#tmce-modal",
                menubar : false,
                toolbar : false
            })

            $("#btn-plus").click(function(){
                var html = `<div class="row mb-2">
                                <div class="col-5">
                                    <div class="custom-file">
                                        <input type="file" onchange="_change(this)" class="custom-file-input" accept="image/*" name="_attachment[]" required>
                                        <div class="custom-file-label">
                                            Choose File
                                        </div>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="custom-file">
                                        <input type="text" class="form-control description" name="_attach_desc[]" placeholder="description" required>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <button type="button" onclick="_delete(this)" class="btn btn-danger btn-sm btn-icon"><i class="fa fa-trash"></i></button>
                                </div>
                            </div>`
                $("#attachments").append(html)
            })

            $("table.display").DataTable()


            @if (\Session::get('msg'))
                Swal.fire('Success', 'Data success', 'success')
            @endif
        })
    </script>
@endsection
