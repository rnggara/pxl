@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">Password Management</h3>
            <div class="card-toolbar">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addPassword"><i class="fa fa-plus"></i> Add New Password</button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <ul class="nav nav-tabs nav-tabs-line mb-5">
                        <li class="nav-item">
                            <a class="nav-link  active" data-toggle="tab" href="#available-password" role="button" aria-haspopup="true" aria-expanded="false">
                                <span class="nav-icon"><i class="flaticon2-checkmark"></i></span>
                                <span class="nav-text">Available Passwords</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#used-password" role="button" aria-haspopup="true" aria-expanded="false">
                                <span class="nav-icon"><i class="flaticon2-time"></i></span>
                                <span class="nav-text">Used Passwords</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="available-password" role="tabpanel" aria-labelledby="available-password">
                            <table class="table table-bordered table-hover display">
                                <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Password</th>
                                    <th class="text-center">Purpose/Personel</th>
                                    <th class="text-center">Usage</th>
                                    <th class="text-center"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($passwords as $i => $password)
                                    <tr>
                                        <td align="center">{{$i+1}}</td>
                                        <td align="center">
                                            <span class="label label-inline label-primary">{{$password->password}}</span>
                                        </td>
                                        <td align="center">{{$password->purposes}}</td>
                                        <td align="center">{{($password->limit_usage == 1) ? "One time only" : (($password->limit_usage == 0) ? "Permanent" : (($password->limit_usage == -2) ? "Project Prognosis" : "Announcement"))}}</td>
                                        <td align="center">
                                            <a href="{{route('ha.password.delete', $password->id)}}" onclick="return confirm('delete?')" class="btn btn-xs btn-icon btn-danger"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade show" id="used-password" role="tabpanel" aria-labelledby="used-password">
                            <table class="table table-bordered table-hover display">
                                <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Password</th>
                                    <th class="text-center">Used By</th>
                                    <th class="text-center">Used At</th>
                                    <th class="text-center">Purpose/Personel</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $iNum = 1;
                                    @endphp
                                    @foreach($used as $i => $data)
                                        @if (isset($detail[$data->id_password]))
                                        <tr>
                                            <td align="center">{{$iNum++}}</td>
                                            <td align="center">
                                                <span class="label label-inline label-primary">{{$detail[$data->id_password]->password ?? ""}}</span>
                                            </td>
                                            <td align="center">
                                                {{$data->usaged_by}}
                                            </td>
                                            <td align="center">
                                                {{date('d F Y', strtotime($data->usaged_at))}}
                                            </td>
                                            <td align="center">
                                                {{$data->usaged_view}}
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
        </div>
    </div>
    <div class="modal fade" id="addPassword" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="POST" action="{{route('ha.password.create')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Usage</label>
                                    <div class="col-md-8">
                                        <select name="usage" class="form-control select2" id="">
                                            <option value="1">One time only</option>
                                            <option value="0">Permanent</option>
                                            <option value="-1">Announcement</option>
                                            <option value="-2">Project Prognosis</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Purpose / For Personel</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="purpose">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" id="btn-save-leads" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script>
        $(document).ready(function () {
            $("select.select2").select2({
                width: "100%"
            })


            $('.display').DataTable({
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            });
        })

    </script>
@endsection
