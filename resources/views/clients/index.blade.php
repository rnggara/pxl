@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                Client List
            </div>
            @actionStart('client','create')
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addEmployee"><i class="fa fa-plus"></i>Add Client</button>
                </div>
                <!--end::Button-->
            </div>
            @actionEnd
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
                    @actionStart('client','read')
                    @foreach($clients as $key => $value)
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
                                            <h5 class="modal-title" id="exampleModalLabel">Edit Client</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <i aria-hidden="true" class="ki ki-close"></i>
                                            </button>
                                        </div>
                                        <form method="post" action="{{route('marketing.client.update')}}" >
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
                                                            <textarea name="address" class="form-control tiny-text">{!! $value->address !!}</textarea>
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
                                                                This PIC will appear on the UP of invoices related to this client
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
                                                @actionStart('client','update')
                                                <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                                                    <i class="fa fa-check"></i>
                                                    Update</button>
                                                @actionEnd
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <td class="text-center">
                                <a href="#edit{{$value->id}}" data-toggle="modal" class="btn btn-sm btn-primary btn-icon btn-icon-md" title="Edit"><i class="fa fa-edit"></i></a>
                                @actionStart('client','create')
                                <a href="{{route('marketing.client.delete',['id' => $value->id])}}" title="Delete" class="btn btn-sm btn-danger btn-icon btn-icon-md" onclick="return confirm('Delete client?')"><i class="fa fa-trash"></i></a>
                                @actionEnd
                            </td>
                        </tr>
                    @endforeach
                    @actionEnd
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addEmployee" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Client</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('marketing.client.store')}}" >
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="form col-md-6">
                                <div class="form-group">
                                    <label>Company Name</label>
                                    <input type="text" class="form-control required" name="name" required/>
                                </div>
                                <div class="form-group">
                                    <label>Company Address</label>
                                    <textarea name="address" class="form-control tiny-text required"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Phone Number 1</label>
                                    <input type="text" class="form-control required" name="phone1" required/>
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
                                    <input type="text" class="form-control required" name="pic_name" required/>
                                </div>
                                <div class="form-group">
                                    <p class="text-danger">
                                        This PIC will appear on the UP of invoices related to this client
                                    </p>
                                </div>
                                <div class="form-group">
                                    <label>PIC Phone Number</label>
                                    <input type="text" class="form-control required" name="pic_phone" required/>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" id="btn-add" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script src="{{ asset('theme/tinymce/tinymce.min.js') }}"></script>
    <script>

        function is_valid(el){
            var v = ""
            var i = 0
            if($(el).is('textarea')){
                var id = $(el).attr('id')
                v = tinymce.get(id).getContent()
            } else {
                v  = $(el).val()
            }

            if(v == ""){
                $(el).addClass("is-invalid")
                $(el).removeClass("is-valid")
                i = 1
            } else {
                $(el).removeClass("is-invalid")
                $(el).addClass("is-valid")
            }

            return i
        }

        function _client_add(btn){
            var form = $(btn).parents("form")
            var req = form.find(".required")

            var isreq = 0
            req.each(function(){
                isreq += is_valid(this)
            })

            console.log(isreq)

            if(isreq > 0){
                Swal.fire('Fields Required', 'Please fill the required field', 'info')
            } else {
                form.submit()
            }
        }

        $(document).ready(function () {

            $(".required").change(function(){
                var v = ""
                if($(this).is('textarea')){
                    var id = $(this).attr('id')
                    v = tinymce.get(id).getContent()
                } else {
                    v  = $(this).val()
                }

                if(v == ""){
                    $(this).addClass("is-invalid")
                    $(this).removeClass("is-valid")
                    i = 1
                } else {
                    $(this).removeClass("is-invalid")
                    $(this).addClass("is-valid")
                }
            })

            // $("#btn-add").click(function(e){
            //     e.preventDefault()
            //     _client_add(this)
            // })

            tinymce.init({
                selector : ".tiny-text",
                menubar : false,
                toolbar : false,
            })
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
