@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                Medical Check Up Log - &nbsp;<b class="text-primary">{{$employee->emp_name}}</b>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{route('mcu.index')}}" title="Back" class="btn btn-secondary btn-xs"><i class="fa fa-backspace"> Back</i></a>
                    &nbsp;
                    &nbsp;
                    @actionStart('mcu', 'update')
                    <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#addEmployee"><i class="fa fa-plus"></i>Add MCU Log</button>
                    @actionEnd
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th nowrap="nowrap" class="text-center">File Name</th>
                        <th nowrap="nowrap" class="text-center">MCU Date</th>
                        <th nowrap="nowrap" class="text-center">MCU Expired</th>
                        <th nowrap="nowrap" class="text-center">Remarks</th>
                        <th nowrap="nowrap" class="text-center">Download</th>
                        <th nowrap="nowrap" data-priority=1 class="text-center"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($logs as $key => $value)
                        <tr>
                            <td>{{($key+1)}}</td>
                            <td class="text-center">{{$value->name}}</td>
                            <td class="text-center">
                                {{date('d F Y',strtotime($value->mcu_date))}}
                            </td>
                            <td class="text-center"> {{date('d F Y',strtotime($value->mcu_expired))}}</td>
                            <td class="text-center"> {{$value->mcu_remark}}</td>
                            <td class="text-center">
                                <a href="{{URL::route('download',$value->address)}}" target="_blank" title="Download Document" class="btn btn-sm btn-primary btn-icon btn-xs"><i class="fa fa-download"></i></a>
                            </td>
                            <td width="10%" class="text-center">
                                <a href="{{route('mcu.delete',['id' => $value->id])}}" title="Delete" class="btn btn-sm btn-danger btn-icon btn-xs" onclick="return confirm('Delete MCU Log?')"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addEmployee" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add MCU Log</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('mcu.storeLog')}}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="mcu_id" value="{{$mcu->id}}">
                    <div class="modal-body">
                        <div class="row">
                            <div class="form col-md-12">
                                <div class="form-group">
                                    <label>File Name</label>
                                    <input type="text" class="form-control" name="title" required/>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-sm-3 control-label">File Upload</label>
                                    <div class="col-sm-7">
                                        <input type="file" name="file" class="form-control" multiple required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>MCU Date</label>
                                    <input type="date" class="form-control" name="mcu_date" required/>
                                </div>
                                <div class="form-group">
                                    <label>MCU Expired</label>
                                    <input type="date" class="form-control" name="mcu_expired" required/>
                                </div>
                                <div class="form-group">
                                    <label>Remarks</label>
                                    <textarea name="remarks" id="remarks" class="form-control remarks" rows="10" placeholder="Remarks here..">

                                    </textarea>
                                </div>

                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script src="{{asset('theme/tinymce/tinymce.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            tinymce.init({
                editor_selector: ".remarks",
                selector: 'textarea',
                mode: "textareas",
                menubar: true,
                toolbar: true,
            });
        });
    </script>
@endsection
