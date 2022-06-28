@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">Investor Master</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <a href="{{ route('business.index') }}" class="btn btn-success"><i class="fa fa-arrow-left"></i></a>
                    <button type="button" data-toggle="modal" data-target="#modalAdd" class="btn btn-primary"><i class="fa fa-plus"></i> Add New</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-8 mx-auto">
                    <table class="table table-hover table-bordered display">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 10%">#</th>
                                <th class="text-center">Name</th>
                                <th class="text-center" style="width: 10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($investors as $i => $item)
                                <tr>
                                    <td align="center">{{ $i+1 }}</td>
                                    <td>{{ ucwords($item->name) }}</td>
                                    <td align="center">
                                        <button type="button" data-toggle="modal" data-target="#modalEdit{{ $item->id }}" class="btn btn-xs btn-icon btn-light-primary"><i class="fa fa-edit"></i></button>
                                        <button type="button" data-toggle="modal" data-target="#modalDelete{{ $item->id }}" class="btn btn-xs btn-icon btn-light-danger"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                                {{-- delete --}}
                                <div class="modal fade" id="modalDelete{{ $item->id }}" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title">Are you sure you want to delete?</h1>
                                            </div>
                                            <form action="{{ route('business.add_investors') }}" method="post">
                                                @csrf
                                                <div class="modal-footer">
                                                    <input type="hidden" name="id_delete" value="{{ $item->id }}">
                                                    <button type="button" class="btn btn-light-primary" data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                {{-- edit --}}
                                <div class="modal fade" id="modalEdit{{ $item->id }}" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title">Edit</h1>
                                            </div>
                                            <form action="{{ route('business.add_investors') }}" method="post">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="form-group row">
                                                        <label for="" class="col-form-label col-3">Investor Name</label>
                                                        <div class="col-9">
                                                            <input type="text" name="name" class="form-control" value="{{ $item->name }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="" class="col-form-label col-3">Account Number</label>
                                                        <div class="col-9">
                                                            <textarea name="account_info" class="form-control" id="" cols="30" rows="10">{!! $item->account_info !!}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <input type="hidden" name="id_investor" value="{{ $item->id }}">
                                                    <button type="button" class="btn btn-light-primary" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAdd" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title">Add New</h1>
                </div>
                <form action="{{ route('business.add_investors') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="" class="col-form-label col-3">Investor Name</label>
                            <div class="col-9">
                                <input type="text" name="name" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-form-label col-3">Account Number</label>
                            <div class="col-9">
                                <textarea name="account_info" class="form-control" cols="30" rows="10"></textarea>
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
    <script src="{{ asset('theme/tinymce/tinymce.min.js') }}"></script>
    <script>

        function show_toast(msg, type){
            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": "toast-bottom-center",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };

            if(type === 1){
                toastr.success(msg)
            } else {
                toastr.info(msg)
            }
        }
        $(document).ready(function(){
            tinymce.init({
                selector : "textarea",
                mode : "textareas",
                menubar : false,
                toolbar : false
            })

            $("table.display").DataTable()

            @if (\Session::get('msg'))
                show_toast("{{ \Session::get('msg') }}", {{ \Session::get('investor_deleted') }})
            @endif
        })
    </script>

@endsection
