@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">Training Record</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <button type="button" data-toggle="modal" data-target="#trainingTypeModal" class="btn btn-secondary"><i class="fa fa-cog"></i> Training Type</button>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#trainingAddModal" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Training Record</button>
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
                                <th class="text-center">Paper Number</th>
                                <th class="text-center">Employee Name</th>
                                <th class="text-center">Placement</th>
                                <th class="text-center">Training Type</th>
                                <th class="text-center">Training Date</th>
                                <th class="text-center">Training Place</th>
                                <th class="text-center">Expiration Date</th>
                                <th class="text-center">File</th>
                                <th class="text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($training as $i => $tr )
                                @if (isset($emp[$tr->emp_id]))
                                <tr>
                                    <td align="center">{{ $i+1 }}</td>
                                    <td align="center">{{ $tr->paper_number }}</td>
                                    <td align="center">{{ $emp[$tr->emp_id] }}</td>
                                    <td align="center">{{ strtoupper($tr->placement) }}</td>
                                    <td align="center">{{ (isset($typeById[$tr->training_type])) ? $typeById[$tr->training_type] : "N/A" }}</td>
                                    <td align="center">{{ date('d F Y', strtotime($tr->training_date)) }}</td>
                                    <td align="center">{{ $tr->training_place }}</td>
                                    <td align="center">{{ date('d F Y', strtotime($tr->exp_date)) }}</td>
                                    <td align="center">
                                        @if (empty($tr->old_id))
                                            <a href="{{ route('download', $tr->file) }}" class="btn btn-xs btn-icon btn-light-dark"><i class="fa fa-download"></i></a>
                                        @else
                                            <a href="{{ route('download', $tr->file) }}" class="btn btn-xs btn-icon btn-light-dark"><i class="fa fa-download"></i></a>
                                        @endif
                                    </td>
                                    <td align="center">
                                        <a href="{{ route('qhse.tr.delete', $tr->id) }}" onclick="return confirm('Delete?')" class="btn btn-xs btn-danger btn-icon"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="trainingTypeModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
               <div class="modal-header">
                   <h1 class="modal-title">Training Type</h1>
               </div>
               <div class="modal-body">
                   <div class="row">
                       <div class="col-12">
                           <form action="{{ route('qhse.tr.type.add') }}" method="post">
                               @csrf
                               <div class="form-group row">
                                    <label for="" class="col-form-label col-3">Type Name</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" name="type_name" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-3"></label>
                                    <div class="col-9">
                                        <button type="submit" class="btn btn-primary btn-sm">Add</button>
                                    </div>
                                </div>
                           </form>
                       </div>
                       <hr>
                       <div class="col-12">
                            <table class="table table-bordered table-hover display">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Type Name</th>
                                        <th class="text-center"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($type as $i => $item)
                                        <tr>
                                            <td align="center">{{ $i + 1 }}</td>
                                            <td align="center">{{ $item->type_name }}</td>
                                            <td align="center">
                                                <a href="{{ route('qhse.tr.type.delete', $item->id) }}" class="btn btn-xs btn-icon btn-danger"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                       </div>
                   </div>
               </div>
               <div class="modal-footer">
                   <button type="button" class="btn btn-light-primary" data-dismiss="modal">Close</button>
               </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="trainingAddModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title">Add Training Record</h1>
                </div>
                <form action="{{ route('qhse.tr.add') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-3">Employee Name</label>
                                    <div class="col-9">
                                        <select name="emp_id" class="form-control select2" id="" required>
                                            <option value="">Select Employee</option>
                                            @foreach ($emp as $i => $item)
                                                <option value="{{ $i }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-3">Placement</label>
                                    <div class="col-9">
                                        <select name="placement" id="" class="form-control select2" required>
                                            <option value="">Select Placement</option>
                                            <option value="field">Field</option>
                                            <option value="office">Office</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-3">Paper Number</label>
                                    <div class="col-9">
                                        <input type="text" name="paper_number" class="form-control" id="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-3">Training Type</label>
                                    <div class="col-9">
                                        <select name="training_type" id="" class="select2 form-control" required>
                                            <option value="">Select Training Type</option>
                                            @foreach ($type as $item)
                                                <option value="{{ $item->id }}">{{ $item->type_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-3">Training Date</label>
                                    <div class="col-9">
                                        <input type="date" name="training_date" class="form-control" id="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-3">Training Place</label>
                                    <div class="col-9">
                                        <input type="text" name="training_place" id="" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-3">Expiration Date</label>
                                    <div class="col-9">
                                        <input type="date" name="exp_date" id="" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-3">Upload File</label>
                                    <div class="col-9">
                                        <div class="custom-file">
                                            <input type="file" name="up_file" id="" class="custom-file-input">
                                            <span class="custom-file-label">Choose File</span>
                                        </div>
                                    </div>
                                </div>
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
@endsection

@section('custom_script')
    <script>
        $(document).ready(function(){
            $("table.display").DataTable()
            $("select.select2").select2({
                width: "100%"
            })
        })
    </script>
@endsection
