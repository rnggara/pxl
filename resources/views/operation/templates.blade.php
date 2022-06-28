@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">Template Print Operational Reports</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalAddTemplate"><i class="fa fa-plus"></i> Add Template</button>
                    <a href="{{ route('general.operation.index') }}" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i></a>
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
                                <th class="text-center">Template Name</th>
                                <th class="text-center">Settings</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($templates as $i => $item)
                                <tr>
                                    <td align="center">{{ $i + 1 }}</td>
                                    <td align="center">{{ $item->template_name }}</td>
                                    <td align="center">
                                        @php
                                            $settings = json_decode($item->settings, true);
                                        @endphp
                                        @if ($settings['record'] == 1)
                                            <span class="label label-inline label-primary">Record</span>
                                        @endif
                                        @if ($settings['activity'] == 1)
                                            <span class="label label-inline label-primary">Activity</span>
                                        @endif
                                        @if ($settings['inventory'] == 1)
                                            <span class="label label-inline label-primary">Inventory</span>
                                        @endif
                                    </td>
                                    <td align="center">
                                        <a href="{{ route('general.operation.templates.edit', $item->id) }}" class="btn btn-xs btn-primary btn-icon"><i class="fa fa-edit"></i></a>
                                        <a href="" class="btn btn-xs btn-danger btn-icon"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal-->
    <div class="modal fade" id="modalAddTemplate" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title">Add Template</h1>
                </div>
                <form action="{{ route('general.operation.templates.add') }}" method="post">
                    @csrf<div class="modal-body">
                        <div class="form-group row">
                            <label for="" class="col-form-label col-md-4 col-sm-12">
                                Template Name
                            </label>
                            <div class="col-md-8 col-sm-12">
                                <input type="text" class="form-control required" name="template_name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-form-label col-md-12 col-sm-12">
                                Template Setting
                            </label>
                        </div>
                        <div class="border p-5">
                            <div class="form-group row">
                                <label for="" class="col-form-label col-md-4 col-sm-4">
                                    Record
                                </label>
                                <div class="col-md-8 col-sm-8 col-form-label">
                                    <div class="checkbox-inline">
                                        <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                            <input type="checkbox" checked class="cb" name="cb_record"/>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-form-label col-md-4 col-sm-4">
                                    Activity
                                </label>
                                <div class="col-md-8 col-sm-8 col-form-label">
                                    <div class="checkbox-inline">
                                        <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                            <input type="checkbox" checked class="cb" name="cb_activity"/>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-form-label col-md-4 col-sm-4">
                                    Inventory
                                </label>
                                <div class="col-md-8 col-sm-8 col-form-label">
                                    <div class="checkbox-inline">
                                        <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                            <input type="checkbox" checked class="cb" name="cb_inventory"/>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary" data-dismiss="modal">Close</button>
                        <button type="submit" id="btn-add" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        $(document).ready(function(){
            var req = $("#modalAddTemplate .required")
            var cb = $("#modalAddTemplate .cb")
            $("#btn-add").click(function(e){
                var form = $(this).parents("form")
                e.preventDefault()
                var isempty = 0
                req.each(function(){
                    if($(this).val() == ""){
                        isempty++
                    }
                })

                var cbchecked = 0
                cb.each(function(){
                    if($(this).prop('checked')){
                        cbchecked++
                    }
                })

                console.log(isempty)
                console.log(cbchecked)

                if(isempty > 0){
                    Swal.fire("Form Required", "Please fill Template Name", "warning")
                } else {
                    if(cbchecked == 0){
                        Swal.fire("Form Required", "Please check at least 1 setting", "warning")
                    } else {
                        form.submit()
                    }
                }
            })
        })
    </script>
@endsection
