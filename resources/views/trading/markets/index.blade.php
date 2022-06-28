@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                Markets List
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addEmployee"><i class="fa fa-plus"></i>Add Market</button>
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
                        <th nowrap="nowrap" style="width: 20%">Company Name</th>
                        <th nowrap="nowrap" class="text-left">Company Phone Number</th>
                        <th nowrap="nowrap" class="text-left">PIC</th>
                        <th nowrap="nowrap" class="text-left">PIC Phone Number</th>
                        <th nowrap="nowrap" data-priority=1 class="text-center">#</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($markets as $key => $value)
                        <tr>
                            <td>{{($key+1)}}</td>
                            <td>{{$value->company_name}}</td>
                            <td class="text-left">{{$value->phone_1}}
                                @if($value->phone_2 != null)
                                    {{' / '.$value->phone_2}}
                                @endif
                            </td>
                            <td class="text-left">{{$value->pic}}</td>
                            <td class="text-left">{{$value->pic_number}}</td>
                            <div class="modal fade" id="edit{{$value->id}}" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Edit Market</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <i aria-hidden="true" class="ki ki-close"></i>
                                            </button>
                                        </div>
                                        <form method="post" action="{{route('trading.market.update')}}" >
                                            @csrf
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="form col-md-6">
                                                        <div class="form-group">
                                                            <label>Company Name</label>
                                                            <input type="hidden" name="id" id="id" value="{{$value->id}}">
                                                            <input type="text" class="form-control" name="name" value="{{$value->company_name}}"/>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Company Address</label>
                                                            <textarea name="address" class="form-control">{{$value->address}}</textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Phone Number 1</label>
                                                            <input type="text" class="form-control" name="phone1" value="{{$value->phone_1}}"/>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Phone Number 2</label>
                                                            <input type="text" class="form-control" name="phone2" value="{{($value->phone_2!=null)?$value->phone_2:''}}"/>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>FAX</label>
                                                            <input type="text" class="form-control" name="fax" value="{{$value->fax}}"/>
                                                        </div>
                                                    </div>
                                                    <div class="form col-md-6">
                                                        <div class="form-group">
                                                            <label>PIC Name</label>
                                                            <input type="text" class="form-control" name="pic_name" value="{{$value->pic}}"/>
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="text-danger">
                                                                This PIC will appear on the UP of invoices related to this market
                                                            </p>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>PIC Phone Number</label>
                                                            <input type="text" class="form-control" name="pic_phone" value="{{$value->pic_number}}" />
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                                                <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                                                    <i class="fa fa-check"></i>
                                                    Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <td class="text-center">
                                <a href="#edit{{$value->id}}" data-toggle="modal" class="btn btn-sm btn-primary btn-icon btn-icon-md" title="Edit"><i class="fa fa-edit"></i></a>
                                <a href="{{route('trading.market.delete',['id' => $value->id])}}" title="Delete" class="btn btn-sm btn-danger btn-icon btn-icon-md" onclick="return confirm('Delete market?')"><i class="fa fa-trash"></i></a>
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
                    <h5 class="modal-title" id="exampleModalLabel">Add Market</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('trading.market.store')}}" >
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="form col-md-6">
                                <div class="form-group">
                                    <label>Company Name</label>
                                    <input type="text" class="form-control" name="name" required/>
                                </div>
                                <div class="form-group">
                                    <label>Company Address</label>
                                    <textarea name="address" class="form-control" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Phone Number 1</label>
                                    <input type="text" class="form-control" name="phone1" required/>
                                </div>
                                <div class="form-group">
                                    <label>Phone Number 2</label>
                                    <input type="text" class="form-control" name="phone2" />
                                </div>
                                <div class="form-group">
                                    <label>FAX</label>
                                    <input type="text" class="form-control" name="fax"/>
                                </div>
                            </div>
                            <div class="form col-md-6">
                                <div class="form-group">
                                    <label>PIC Name</label>
                                    <input type="text" class="form-control" name="pic_name" required/>
                                </div>
                                <div class="form-group">
                                    <p class="text-danger">
                                        This PIC will appear on the UP of invoices related to this market
                                    </p>
                                </div>
                                <div class="form-group">
                                    <label>PIC Phone Number</label>
                                    <input type="text" class="form-control" name="pic_phone" required/>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script>
        $(document).ready(function () {
            $('.display').DataTable({
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            });
        });
    </script>
@endsection
