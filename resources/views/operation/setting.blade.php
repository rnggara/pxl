@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">Operation Report - {{ $project->prj_name }}</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <a href="{{ route('general.operation.index') }}" class="btn btn-sm btn-icon btn-success"><i class="fa fa-arrow-left"></i></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('general.operation.logo_setting') }}" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-12 text-center">
                        <h1>Company Logo Setting</h1>
                    </div>
                    @php
                        $left_logo = 'assets/media/users/100_1.jpg';
                        $right_logo = 'assets/media/users/100_1.jpg';
                        if(!empty($setting_report)){
                            if(!empty($setting_report->left_logo)){
                                $left_logo = str_replace('public', 'public_html', asset($setting_report->left_logo));
                            }

                            if(!empty($setting_report->right_logo)){
                                $right_logo = str_replace('public', 'public_html', asset($setting_report->right_logo));
                            }
                        }
                    @endphp
                    <div class="col-4">
                        <h3>Left Logo</h3>
                        <hr>
                        <div class="image-input image-input-outline" id="left_logo">
                            <div class="image-input-wrapper" style="background-image: url('{{ $left_logo }}'); width: 200px; height: 200px; background-size: contain"></div>

                            <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change left logo">
                                <i class="fa fa-pen icon-sm text-muted"></i>
                                <input type="file" name="left_logo" accept=".png, .jpg, .jpeg"/>
                                <input type="hidden" name="left_logo_remove"/>
                            </label>

                            <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel logo">
                                <i class="ki ki-bold-close icon-xs text-muted"></i>
                            </span>
                        </div>
                        <div class="form-group">
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-danger">
                                    <input type="checkbox" name="delete_left_logo"/>
                                    <span></span>
                                    Check to Delete Logo
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 text-center">
                        @csrf
                        <input type="hidden" name="id_project" value="{{ $project->id }}">
                        <button type="submit" class="btn btn-success mt-10">Save Setting</button>
                    </div>
                    <div class="col-4">
                        <h3>Right Logo</h3>
                        <hr>
                        <div class="image-input image-input-outline" id="right_logo">
                            <div class="image-input-wrapper" style="background-image: url('{{ $right_logo }}'); width: 200px; height: 200px; background-size: contain"></div>

                            <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change left logo">
                                <i class="fa fa-pen icon-sm text-muted"></i>
                                <input type="file" name="right_logo" accept=".png, .jpg, .jpeg"/>
                                <input type="hidden" name="right_logo_remove"/>
                            </label>

                            <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel logo">
                                <i class="ki ki-bold-close icon-xs text-muted"></i>
                            </span>
                        </div>
                        <div class="form-group">
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-danger">
                                    <input type="checkbox" name="delete_left_logo"/>
                                    <span></span>
                                    Check to Delete Logo
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <hr>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h3>Choose template print</h3>
                    </div>
                    <div class="col-4">
                        <select name="_template" class="form-control select2" data-placeholder="Select Template" id="">
                            <option value=""></option>
                            @foreach ($templates as $item)
                                <option value="{{ $item->id }}" {{ (!empty($setting_report) && $setting_report->id_template == $item->id) ? "SELECTED" : "" }}>{{ $item->template_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <hr>
            </form>
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered table-hover display table-responsive-sm">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Item Name (uom)</th>
                                <th class="text-center">Item Description</th>
                                <th class="text-center">Category</th>
                                <th class="text-center">
                                    <button type="button" data-toggle="modal" data-target="#modalAddRecord" class="btn btn-primary btn-sm">Add Record</button>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detail as $i => $item)
                                <tr>
                                    <td align="center">{{ $i+1 }}</td>
                                    <td align="center">
                                        {{ "$item->item_name ($item->uom)" }}
                                    </td>
                                    <td>
                                        {!! $item->description !!}
                                    </td>
                                    <td align="center">
                                        {{ strtoupper($item->category) }}
                                    </td>
                                    <td align="center">
                                        <button type="button" onclick="_edit({{ $item->id }})" class="btn btn-icon btn-success btn-xs"><i class="fa fa-pencil-alt"></i></button>
                                        <a href="{{ route('general.operation.setting_delete', $item->id) }}" onclick="return confirm('Delete record?')" class="btn btn-icon btn-danger btn-xs"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAddRecord" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('general.operation.setting_add') }}" method="post">
                    <div class="modal-header">
                        <h1 class="modal-title">Add Record</h1>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="" class="col-md-3 col-sm-12 col-form-label">Category</label>
                            <div class="col-md-9 col-sm-12">
                                <select name="_category" class="form-control select2 required" aria-placeholder="Category">
                                    @foreach ($_category as $key => $item)
                                        <option value="{{ $key }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-md-3 col-sm-12 col-form-label">Name</label>
                            <div class="col-md-9 col-sm-12">
                                <input type="text" class="form-control required" name="_name" aria-placeholder="Name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-md-3 col-sm-12 col-form-label">Description</label>
                            <div class="col-md-9 col-sm-12">
                                <textarea name="_description" class="form-control tmce required" id="" cols="30" rows="10" aria-placeholder="Description"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-md-3 col-sm-12 col-form-label">Unit of Measurements (satuan)</label>
                            <div class="col-md-9 col-sm-12">
                                <input type="text" class="form-control required" name="_uom" aria-placeholder="Unit of Measurements">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @csrf
                        <input type="hidden" name="_id_project" value="{{ $project->id }}">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="btn-add-report">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalEditRecord" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">

            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{ asset('theme/tinymce/tinymce.min.js') }}"></script>
    <script>
        function _edit(x){
            var content = $("#modalEditRecord").find('.modal-content')
            content.html('')
            $.ajax({
                url : "{{ route('general.operation.setting_get') }}/"+x,
                type : "get",
                success : function(response){
                    $("#modalEditRecord").modal('show')
                    content.html(response)
                    var sel = content.find("select.select2")
                    sel.select2({
                        width: "100%"
                    })
                    tinymce.init({
                        selector : ".tmce",
                        menubar : false
                    })
                }
            })
        }

        function _validation(btn){
            btn.preventDefault()
            var form = $(btn).parents('form')
            var req = form.find(".required")
            console.log(req)
            var isreq = []
            req.each(function(){
                var val = ""
                if($(this).is('textarea')){
                    var id = $(this).attr("id")
                    val = tinymce.get(id).getContent()
                } else {
                    val = $(this).val()
                }

                if(val == ''){
                    isreq.push($(this).attr('aria-placeholder'))
                }
            })

            if(isreq.length > 0){
                var field = ""
                for (let index = 0; index < isreq.length; index++) {
                    field += isreq[index]
                    if(index < (isreq.length - 1)){
                        field += "<br>"
                    }
                }

                Swal.fire('Field required', 'Field <br>'+field+"is required", 'error')
            } else {
                form.submit()
            }
        }

        $(document).ready(function(){
            tinymce.init({
                selector : ".tmce",
                menubar : false
            })
            $("table.display").DataTable()
            var left_logo = new KTImageInput('left_logo');
            var right_logo = new KTImageInput('right_logo');

            $("select.select2").select2({
                width: "100%"
            })

            $("#btn-add-report").click(function(e){
                e.preventDefault()
                var form = $(this).parents('form')
                var req = form.find(".required")
                console.log(req)
                var isreq = []
                req.each(function(){
                    var val = ""
                    if($(this).is('textarea')){
                        var id = $(this).attr("id")
                        val = tinymce.get(id).getContent()
                    } else {
                        val = $(this).val()
                    }

                    if(val == ''){
                        isreq.push($(this).attr('aria-placeholder'))
                    }
                })

                if(isreq.length > 0){
                    var field = ""
                    for (let index = 0; index < isreq.length; index++) {
                        field += isreq[index]
                        if(index < (isreq.length - 1)){
                            field += "<br>"
                        }
                    }

                    Swal.fire('Field required', 'Field <br>'+field+"is required", 'error')
                } else {
                    form.submit()
                }
            })

            @if (\Session::get('success'))
                Swal.fire('Save', '{{ \Session::get('success') }}', "success")
            @endif

            @if (\Session::get('delete'))
                Swal.fire('Delete', '{{ \Session::get('delete') }}', "success")
            @endif

            @if (\Session::get('error'))
                Swal.fire('Error', '{{ \Session::get('error') }}', "error")
            @endif
        })
    </script>
@endsection
