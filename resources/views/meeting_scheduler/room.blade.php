@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">
                    Room List
                </h3>
            </div>
            @actionStart('meeting_scheduler', 'create')
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addItem"><i class="fa fa-plus"></i>New Room</button>
                </div>
                <!--end::Button-->
            </div>
            @actionEnd
        </div>
        <div class="modal fade" id="addItem" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add New Room</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                    </div>
                    <form method="post" action="{{route('ms.newroom')}}" >
                        @csrf
                        <div class="modal-body">
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label text-right">Room Name</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="name" placeholder="Room Name">
                                </div>
                            </div>

                            <input type="hidden" name="tanggal" value="{{$date}}">
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
        <div class="card-body">
            <table class="table display">
                <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-left" width="90%">Room Name</th>
                </tr>
                </thead>
                <tbody>
                @actionStart('meeting_scheduler', 'read')
                @foreach($rooms as $key => $room)
                    <tr>
                        <td class="text-center">{{($key+1)}}</td>
                        <td><a href="{{route('ms.book',['tanggal'=>base64_encode($date),'id_room' => $room->id])}}" class="btn btn-link"><i class="fa fa-calendar-plus"></i>{{$room->nama_ruangan}}</a></td>
                    </tr>
                @endforeach
                @actionEnd
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('custom_script')
    <script>
        $(document).ready(function(){
            $("table.display").DataTable()
        })
    </script>
@endsection
