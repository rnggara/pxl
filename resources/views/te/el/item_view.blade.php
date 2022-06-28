@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="row">
        <div class="col-12 mx-auto">
            <div class="card card-custom gutter-b card-stretch">
                <div class="card-header">
                    <h3 class="card-title">View - {{ $item->subject }}</h3>
                    <div class="card-toolbar">
                        <div class="btn-group">
                            <a href="{{ route('te.el.detail', $item->category) }}" class="btn btn-sm btn-icon btn-success"><i class="fa fa-arrow-left"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-10">
                        <div class="col-6 mx-auto text-center">
                            @if (!empty($item->thumbnail))
                                @if (!empty($thumbnail))
                                    <img style="max-width: 400px" src="{{ str_replace("public", "public_html", asset($thumbnail->file_name)) }}" alt="" srcset="">
                                @else
                                    <img src="{{ str_replace("public", "public_html", asset($item->thumbnail)) }}" alt="" srcset="">
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group row">
                                <label class="col-3 col-form-label">Type</label>
                                <div class="col-md-8">
                                    @php
                                        if ($item->type == 1){
                                            $type = "MAIN EQUIPMENT";
                                        } elseif ($item->type == 1){
                                            $type = "ACCESORIES";
                                        } else {
                                            $type = "SAFETY EQUIPMENT";
                                        }
                                    @endphp
                                    <label class="col-form-label">{{ $type }}</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label">
                                    @if($elCat->tag == "SEP" || $elCat->tag == "SCRB")
                                        Dimension
                                    @else
                                        Capacity
                                    @endif
                                </label>
                                <div class="col-md-8">
                                    <label class="col-form-label">{{ $item->param1 }}</label>
                                </div>
                            </div>
                            @if ($elCat->tag == "SEP")
                                <div class="form-group row">
                                    <label class="col-3 col-form-label">Design Pressure</label>
                                    <div class="col-md-8">
                                        <label class="col-form-label">{{ $item->param2 }}</label>
                                    </div>
                                </div>
                            @endif
                            <div class="form-group row">
                                <label class="col-3 col-form-label">COI Expiry</label>
                                <div class="col-md-8">
                                    <label class="col-form-label">{{($item->coi_expiry != "0000-00-00") ? date("d F Y", strtotime($item->coi_expiry)) : "N/A"}}</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label">Status</label>
                                <div class="col-md-8">
                                    <label class="col-form-label">{{($item->status == 1) ? "READY" : "NOT READY"}}</label>
                                </div>
                            </div>
                            @if($elCat->tag == "SEP")
                            @php
                                $sep = json_decode($item->additional_information);
                            @endphp
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Capacity Oil</label>
                                <div class="col-md-9">
                                    <label class="col-form-label">{{$sep->capacity_oil ?? "-"}}</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Capacity Water</label>
                                <div class="col-md-9">
                                    <label class="col-form-label">{{$sep->capacity_water ?? "-"}}</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Capacity Gas</label>
                                <div class="col-md-9">
                                    <label class="col-form-label">{{$sep->capacity_gas ?? "-"}}</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Retention Time</label>
                                <div class="col-md-9">
                                    <label class="col-form-label">{{$sep->retention_time ?? "-"}}</label>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="col-6">
                            <div class="form-group row">
                                <label class="col-form-label col-3">Description</label>
                                <label class="col-form-label col-9">
                                    {!! $item->description !!}
                                </label>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-3">Drawing</label>
                                <label class="col-form-label col-9">
                                    @if (!empty($item->drawing))
                                        @if (!empty($file))
                                            @php
                                                $target = str_replace("public", 'public_html', asset($file->file_name));
                                                $target = str_replace("\\", '/', $target);
                                            @endphp
                                            <iframe src="//sharecad.org/cadframe/load?url={{ $target }}" scrolling="no" style="width: 100%" class="h-425px"></iframe>
                                        @else
                                            <span class="label label-inline label-secondary">File not found</span>
                                        @endif
                                    @else
                                    <span class="label label-inline label-secondary">No file drawing</span>
                                    @endif
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="separator separator-solid separator-border-2"></div>
                        </div>
                    </div>
                </div>
                <div class="card-header">
                    <h3 class="card-title">Maintenance Records</h3>
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalMaintenance"><i class="fa fa-plus"></i> Add Record</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-bordered table-hover table-responsive-sm display">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Maintenance Date</th>
                                        <th class="text-center">Description</th>
                                        <th class="text-center">Report File</th>
                                        <th class="text-center">Follow up notes</th>
                                        <th class="text-center">Next Maintenance Date</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mt as $i => $value)
                                        <tr>
                                            <td align="center">{{ $i+1 }}</td>
                                            <td align="center">{{ date("d/m/Y", strtotime($value->mt_date)) }}</td>
                                            <td>{!! $value->mt_description !!}</td>
                                            <td align="center">
                                                @if (empty($value->mt_report_file))
                                                    -
                                                @else
                                                    @if (isset($files[$value->mt_report_file]))
                                                        <a href="{{ route('download', $value->mt_report_file) }}" class="btn btn-icon btn-xs btn-primary" target="_blank"><i class="fa fa-download"></i></a>
                                                    @else
                                                        -
                                                    @endif
                                                @endif
                                            </td>
                                            <td>{!! $value->mt_fol_up !!}</td>
                                            <td align="center">{{ date("d/m/Y", strtotime($value->mt_next_date)) }}</td>
                                            <td align="center">
                                                <a href="{{ route('te.el.maintenance.delete', $value->id) }}" onclick="return confirm('delete record?')" class="btn btn-xs btn-icon btn-danger"><i class="fa fa-trash"></i></a>
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
    <div class="modal fade" id="modalMaintenance" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('te.el.maintenance.add') }}" method="post" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h1 class="modal-title">Add Record Maintenance</h1>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-form-label col-md-3 col-sm-12">Maintenance Date</label>
                            <div class="col-md-9 col-sm-12">
                                <input type="date" class="form-control required" aria-placeholder="Maintenance Date" name="_mt_date" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-3 col-sm-12">Maintenance Description</label>
                            <div class="col-md-9 col-sm-12">
                                <textarea name="_description" class="form-control tmce required" aria-placeholder="Maintenance Description" cols="50" rows="30"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-3 col-sm-12">Follow Up Notes</label>
                            <div class="col-md-9 col-sm-12">
                                <textarea name="_follow_up" class="form-control tmce" aria-placeholder="Follow Up Notes" cols="50" rows="30"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-3 col-sm-12">Next Maintenance Date</label>
                            <div class="col-md-9 col-sm-12">
                                <input type="date" class="form-control" aria-placeholder="Next Maintenance Date" name="_next_mt_date" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-3 col-sm-12">Report File</label>
                            <div class="col-md-9 col-sm-12">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="_report_file">
                                    <span class="custom-file-label">Choose file</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @csrf
                        <input type="hidden" name="_id_el" value="{{ $item->id }}">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" id="btn-add" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{ asset('theme/tinymce/tinymce.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            $("table.display").DataTable()
            tinymce.init({
                selector : ".tmce",
                mode : 'textareas',
                menubar : false,
                toolbar: ['styleselect fontselect fontsizeselect',
                    'undo redo | cut copy paste | bold italic | link image | alignleft aligncenter alignright alignjustify',
                    'bullist numlist | outdent indent | blockquote subscript superscript | advlist | autolink | lists charmap | print preview |  code'],
                plugins : 'advlist autolink link image lists charmap print preview code',
                height : "480"
            })


            $("#btn-add").click(function(e){
                e.preventDefault()
                var form = $(this).parents('form')
                var inputs = form.find('.required')
                var fields = []
                inputs.each(function(){
                    if($(this).is('input')){
                        if($(this).val() == ''){
                            var ph = $(this).attr('aria-placeholder')
                            fields.push(ph)
                        }
                    } else {
                        var id = $(this).attr('id')
                        var tmce = tinymce.get(id).getContent()
                        if(tmce == ""){
                            var ph = $(this).attr('aria-placeholder')
                            fields.push(ph)
                        }
                    }
                })

                if(fields.length > 0){
                    var fl = ""
                    for (let index = 0; index < fields.length; index++) {
                        fl += fields[index]
                        if((index + 1) < fields.length){
                            fl += "<br>"
                        }

                    }
                    Swal.fire('Empty', 'Field <br>'+fl+'<br> is required', 'warning')
                } else {
                    _post()
                    form.submit()
                }
            })

            @if (\Session::get('msg'))
                Swal.fire('{{ \Session::get('msg') }}', 'Add record success', 'success')
            @endif

            @if (\Session::get('error'))
                Swal.fire('{{ \Session::get('error') }}', 'Failed to add record', 'error')
            @endif

            @if (\Session::get('delete'))
                Swal.fire('{{ \Session::get('error') }}', 'Record deleted', 'success')
            @endif
        })
    </script>
@endsection
