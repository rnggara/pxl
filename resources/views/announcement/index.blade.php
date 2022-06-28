@extends('layouts.template')

@section('css')

@endsection

@section('content')
    @if(session()->has('message_needsec_fail'))
        <div class="alert alert-danger">
            {!! session()->get('message_needsec_fail') !!}
        </div>
    @endif
    @if(session()->has('message_needsec_success'))
        <div class="alert alert-success">
            {!! session()->get('message_needsec_success') !!}
        </div>
    @endif
    @if(!(session()->has('seckey_announcement')) || (session()->has('seckey_announcement') < 10))
        @include('ha.needsec.index', ["type" => "announcement"])
    @else
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">Announcement</h3>
            <div class="card-toolbar">
                <button type="button"data-toggle="modal" data-target="#addAnnouncementModal" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add New</button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered table-hover table-responsive-xl display">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Title</th>
                                <th class="text-center">Created By</th>
                                <th class="text-center">Created At</th>
                                <th class="text-center">Status</th>
                                <th class="text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($announcement as $i => $item)
                                <tr>
                                    <td align="center">{{ $i+1 }}</td>
                                    <td align="center">
                                        <button type="button" onclick="get_detail({{ $item->id }})" data-toggle="modal" data-target="#viewDescriptionModal" class="btn btn-primary btn-sm">{{ $item->title }}</button>
                                    </td>
                                    <td align="center">{{ $item->created_by }}</td>
                                    <td align="center">{{ date('d F Y', strtotime($item->created_at)) }}</td>
                                    <td align="center">
                                        @if ($item->status == 0)
                                            <button type="button" onclick="btn_set_active({{ $item->id }}, 'Enable')" class="btn btn-danger btn-sm">Disabled</button>
                                        @else
                                            <button type="button" onclick="btn_set_active({{ $item->id }}, 'Disable')" class="btn btn-success btn-sm">Enabled</button>
                                        @endif
                                    </td>
                                    <td align="center">
                                        <button type="button" onclick="btn_delete({{ $item->id }})" class="btn btn-danger btn-icon btn-sm"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addAnnouncementModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title">Add Announcement</h1>
                </div>
                <form action="{{ route('announcement.add') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-3">Title</label>
                                    <div class="col-9">
                                        <input type="text" name="title" class="form-control" id="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-3">Description</label>
                                    <div class="col-9">
                                        <textarea name="description" id="desc" class="form-control" cols="30" rows="10"></textarea>
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
    <div class="modal fade" id="viewDescriptionModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="view-title"></h1>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div id="view-description"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection

@section('custom_script')
    <script src="{{asset('theme/tinymce/tinymce.min.js')}}"></script>
    <script>

        function get_detail(x){
            $.ajax({
                url : "{{ route('announcement.detail') }}/"+x,
                type: "get",
                dataType : "json",
                cache : false,
                success : function(response){
                    var ann = response.data
                    $("#view-title").html(ann.title)
                    $("#view-description").html(ann.description)
                }
            })
        }

        function btn_set_active(x, y){
            Swal.fire({
                title: "Are you sure?",
                text: "",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, "+y+" it!"
            }).then(function(result) {
                if (result.value) {
                    window.location.href = "{{ route('announcement.activate') }}/" + x
                }
            });
        }

        function btn_delete(x){
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!"
            }).then(function(result) {
                if (result.value) {
                    window.location.href = "{{ route('announcement.delete') }}/" + x
                }
            });
        }

        $(document).ready(function(){
            tinymce.init({
                selector: '#desc',
                menubar: false,
                statusbar: false
            });

            $("table.display").DataTable()
        })
    </script>

@endsection
