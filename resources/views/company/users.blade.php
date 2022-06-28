@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">Users</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#add"><i class="fa fa-plus"></i> Add new user</button>
                    <a href="{{ route('company.detail', base64_encode($company->id)) }}" class="btn btn-sm btn-success btn-icon"><i class="fa fa-arrow-left"></i></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Username</th>
                                    <th class="text-center">Wallet Address</th>
                                    <th class="text-center">Position</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $key => $user)
                                    <tr>
                                        <td align="center">{{$key+1}}</td>
                                        <td nowrap="nowrap">{{ $user->name }}</td>
                                        <td nowrap="nowrap" align="center">
                                            <label for="" class="label label-inline label-primary">{{$user->username}}</label>
                                        </td>
                                        <td align="center">
                                            {{ $user->metamask_id ?? "N/A" }}
                                        </td>
                                        <td nowrap="nowrap" align="center">{{$user->roleName}}&nbsp;{{$user->divName}}</td>
                                        <td nowrap="nowrap" align="center">
                                            <a href="{{route('user.privilege',['id'=>$user->id])}}" class="btn btn-sm btn-warning btn-icon btn-icon-md" title="Privilege"><i class="la la-key"></i></a>
                                            <a href="#edit{{$user->id}}" data-toggle="modal" class="btn btn-sm btn-primary btn-icon btn-icon-md" title="Edit"><i class="la la-edit"></i></a>
                                            @if($user->id > 1)
                                                <a href="{{route('user.delete',['id'=>$user->id])}}" class="btn btn-sm btn-danger btn-icon" title="Delete" onclick="return confirm('Delete User?')"><i class="fa fa-trash"></i></a>
                                            @endif
                                        </td>
                                        <div class="modal fade" id="edit{{$user->id}}" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                            <div class="modal-dialog">
                                                <form class="form" action="{{URL::route('user.edit')}}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="id_u" id="id_u{{$key}}" value="{{$user->id}}">
                                                    <input type="hidden" name="coid" value="{{base64_encode($company->id)}}">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                X
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <input type="hidden" name="old_password" value="{{$user->password}}">
                                                            <div class="form-group">
                                                                <label>Employee</label>
                                                                <select name="empId" class="form-control select2">
                                                                    <option value="">Select Employee</option>
                                                                    @foreach($emp as $empId => $value)
                                                                        <option value="{{$empId}}" {{($empId == $user->emp_id) ? "SELECTED" : ""}}>{{$value}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Name</label>
                                                                <input type="text" class="form-control" name="name" value="{{$user->name}}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Email</label>
                                                                <input type="text" class="form-control" name="email" value="{{$user->email}}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Username</label>
                                                                <input type="text" class="form-control" name="username" value="{{$user->username}}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Password</label>
                                                                <input type="password" class="form-control" name="password" placeholder="New Password">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Dispatch Name</label>
                                                                <input type="text" class="form-control" name="dispatch_name" value="{{ $user->dispatch_name }}" readonly>
                                                            </div>

                                                            {{-- <div class="form-group">
                                                                <label>Position</label>
                                                                <input type="text" class="form-control" name="position" value="{{($user->position != null) ? $user->position : 'SYSTEM'}}">
                                                            </div> --}}
                                                            <div class="form-group">
                                                                <label>Position</label>
                                                                <select name="userRoleEdit" class="form-control">
                                                                    @foreach($roleDivsList as $key => $value)
                                                                        @if($value->id == $user->userRoleDivId)
                                                                            <option value="{{$value->id}}" selected="selected">{{$value->roleName}}&nbsp;{{$value->divName}}</option>
                                                                        @else
                                                                            <option value="{{$value->id}}">{{$value->roleName}}&nbsp;{{$value->divName}}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                                <input type="hidden" class="form-control" name="userRoleEditOld" value="{{$user->userRoleDivId}}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>DO Code</label>
                                                                <input type="text" class="mb-5 form-control" name="do_code" id="do_code_{{$user->id}}" readonly value="{{$user->do_code}}">
                                                                <button type="button" class="btn btn-primary btn-xs" onclick="generateCode('{{$user->id}}')">
                                                                    <i class="fa fa-recycle"></i>&nbsp;Generate
                                                                </button>
                                                                @if(!empty($user->do_code))
                                                                <button type="button" class="btn btn-xs btn-danger" onclick="deleteCode('{{$user->id}}')"><i class="fa fa-times"></i> Delete Code</button>
                                                                <input type="hidden" name="delete_code" id="delete_code_{{$user->id}}">
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary" name="saveEdit">Save</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        X
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs" id="inputUser{{$company->id}}" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="input{{$company->id}}" data-toggle="tab" href="#manual{{$company->id}}">
                                    <span class="nav-icon">
                                        <i class="flaticon-list-1"></i>
                                    </span>
                                <span class="nav-text">Manual Input</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="input2{{$company->id}}" data-toggle="tab" href="#export{{$company->id}}" aria-controls="profile">
                                    <span class="nav-icon">
                                        <i class="flaticon-user-add"></i>
                                    </span>
                                <span class="nav-text">Import User</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content mt-5" id="all{{$company->id}}">
                        <div class="tab-pane fade show active" id="manual{{$company->id}}" role="tabpanel" aria-labelledby="home-tab">
                            <form class="form" action="{{URL::route('user.add')}}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label>Employee</label>
                                    <select name="empId" id="emp-id" class="form-control">
                                        <option value="">Select Employee</option>
                                        @foreach($emp as $key => $value)
                                            <option value="{{$key}}">{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control" id="name-user" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="text" class="form-control" name="email" required>
                                </div>
                                <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" class="form-control" id="user-name" name="username" required>
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" class="form-control" name="password" required>
                                </div>
                                <div class="form-group">
                                    <label>Dispatch Name</label>
                                    <input type="text" class="form-control" name="dispatch_name">
                                </div>
                                <div class="form-group">
                                    <label>Position</label>
                                    <select name="userRoleAdd" class="form-control"  id="role" required>
                                        <option value="">Select Position</option>
                                        @foreach($roleDivsList as $key => $value)
                                            <option value="{{$value->id}}">{{$value->roleName}}&nbsp;{{$value->divName}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="modal-footer">
                                    <input type="hidden" name="coid" value="{{base64_encode($company->id)}}">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" onclick="_post()" class="btn btn-primary" name="saveAdd">Save</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="export{{$company->id}}" role="tabpanel" aria-labelledby="profile-tab">
                            <form class="form" action="{{URL::route('user.add')}}" method="POST" id="form-export">
                                @csrf
                                <div class="form-group">
                                    <label>From Company</label>
                                    <select name="company" id="company" class="form-control">

                                    </select>
                                </div>
                                <div class="form-group" id="opt2">
                                    <label>Choose User</label>
                                    <select name="user_company" id="user_company" class="form-control">

                                    </select>
                                </div>
                                <div class="modal-footer">
                                    <input type="hidden" name="export" value="1">
                                    <input type="hidden" name="coid" value="{{base64_encode($company->id)}}">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" onclick="_post()" class="btn btn-primary" name="saveAdd">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>

        $(document).ready(function(){
            $("table").DataTable({
                pageLength: 50
            })

            $("#company").select2({
                ajax: {
                    url: "{{ URL::route('user.getCompany') }}",
                    type: "GET",
                    placeholder: 'Choose Company',
                    allowClear: true,
                    dataType: 'json',
                    data: function (params) {
                        return {
                            searchTerm: params.term,
                            "_token": "{{ csrf_token() }}",
                            comp: "{{ $company->id }}"
                        };
                    },
                    processResults: function (response) {
                        // alert(dataCustomer);
                        dataCompany = $('#company').val();
                        return {
                            results: response
                        };
                    },
                    cache: false
                },
                width:"100%"
            }).on('select2:select',function () {
                dataCompany = $('#company').val();
                $('#opt2').show();
            })

            function getURLUser(){
                var url = "{{URL::route('user.getUsers',['id_company' => ':id1'])}}";
                url = url.replace(':id1', dataCompany);
                return url;
            }

            $("#emp-id").select2({
                width: "100%"
            })

            $('#user_company').select2({
                ajax: {
                    url: function (params) {
                        return getURLUser()
                    },
                    type: "GET",
                    placeholder: 'Choose User',
                    allowClear: true,
                    dataType: 'json',
                    data: function (params) {
                        return {
                            searchTerm: params.term,
                            "_token": "{{ csrf_token() }}",
                            comp: "{{ $company->id }}"
                        };
                    },
                    processResults: function (response) {
                        return {
                            results: response
                        };
                    },
                    cache: false
                },
                width:"100%"
            }).on('select2:select',function () {
                dataUser = $('#user_company').val();
                // $('#opt4').show();
                // alert(dataUser)
            });

            $("#role").select2({
                width : "100%"
            })

            $("select.select2").select2({
                width: "100%"
            })

            @if (\Session::get('msg'))
                Swal.fire('User exist', 'user {{ \Session::get('msg') }} already exist', 'info')
            @endif
        })
    </script>
@endsection
