@extends('layouts.template')
@section('content')
    @if(session()->has('message'))
        <div class="alert alert-danger">
            {{ session()->get('message') }}
        </div>
    @endif
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">
                    Meeting Schedule of {{date('l, d F Y',strtotime($date))}} at {{$room->nama_ruangan}}
                </h3>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{route('ms.index')}}" class="btn btn-secondary"><i class="fa fa-backspace"></i></a>
                </div>
                <!--end::Button-->
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class='col-md-4'>
                    <table class='table'>
                        <thead>
                            <tr>
                                <th class='text-center'>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                        @actionStart('meeting_scheduler', 'read')
                        @foreach($hours as $key => $hour)
                            @php
                                /** @var TYPE_NAME $hour */
                                $jammasuk2 = explode(' - ',$hour);
                                $status = false;
                            @endphp
                            @foreach($timecheck as $check)
                                @if(strpos($jammasuk2[0], $check->jam) !== false)
                                    @php
                                        $status = true;
                                    @endphp
                                @endif
                            @endforeach
                            <tr @if($status == true) class="bg-warning-o-40" @endif>
                                <td class="text-center">
                                    <button type="button" class="btn @if($status == true) btn-hover-light-warning @else btn-hover-light-primary @endif btn-sm" data-toggle="modal" data-target="#show{{$key}}">
                                        <p class=" @if($status == true) text-danger @endif">{{$hour}}</p>
                                    </button>
                                    <div class="modal fade" id="show{{$key}}" tabindex="-1" role="dialog" aria-labelledby="addNotes" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Reservation - Meeting Schedule of {{date('l, d F Y',strtotime($date))}} at {{$room->nama_ruangan}}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <i aria-hidden="true" class="ki ki-close"></i>
                                                    </button>
                                                </div>
                                                <form method="post" action="{{route('ms.addReservation')}}" >
                                                    @csrf
                                                    @php
                                                        /** @var TYPE_NAME $hour */
                                                        $jammasuk = explode(' - ',$hour);
                                                    @endphp
                                                    <div class="modal-body">
                                                        <div class="form-group row">
                                                            <label class="col-form-label text-right col-lg-3 col-sm-12">Tanggal</label>
                                                            <div class="col-lg-6 col-md-9 col-sm-12">
                                                                <input type="date" name="tgl" class="form-control" value="{{date('Y-m-d', strtotime($date))}}">
                                                            </div>
                                                            <input type="hidden" name="id_room" value="{{$room->id}}">
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-form-label text-right col-lg-3 col-sm-12">Jam Masuk</label>
                                                            <div class="col-lg-6 col-md-9 col-sm-12">
                                                                <input type="time" name="jam_masuk" class="form-control" readonly value="{{date('H:i', strtotime($jammasuk[0]))}}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-form-label text-right col-lg-3 col-sm-12">Jam Keluar</label>
                                                            <div class="col-lg-6 col-md-9 col-sm-12">
                                                                <input type="time" name="jam_keluar" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                                                        @actionStart('meeting_scheduler', 'create')
                                                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                                                            <i class="fa fa-check"></i>
                                                            Save</button>
                                                        @actionEnd
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                        @actionEnd
                        </tbody>
                    </table>
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#all">
                                    <span class="nav-icon">
                                        <i class="flaticon-folder-1"></i>
                                    </span>
                                    <span class="nav-text">Meeting Plan</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#sales" aria-controls="profile">
                                    <span class="nav-icon">
                                        <i class="flaticon2-checkmark"></i>
                                    </span>
                                    <span class="nav-text">Meeting Detail</span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content mt-5" id="myTabContent">
                            <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="home-tab">
                                <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                    <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                                        <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Tanggal</th>
                                            <th class="text-center">Jam Masuk</th>
                                            <th class="text-center">Jam Keluar</th>
                                            <th class="text-center">Event</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @actionStart('meeting_scheduler', 'read')
                                        @php
                                            $stat_plan = false;
                                            $noA = 0;

                                        @endphp

                                        @foreach($books as $key => $value)
                                            @if(!isset($topicchecker[$value->id]))
{{--                                            @if($bookchecker[$value->id][$key] != $value->id)--}}
                                                <tr>
                                                    <td class="text-center">{{($noA+1)}}</td>
                                                    @php
                                                        /** @var TYPE_NAME $noA */
                                                        $noA+=1;
                                                    @endphp
                                                    <td class="text-center">{{date('d F y', strtotime($value->tanggal))}}</td>
                                                    <td class="text-center">{{date('H:i',strtotime($value->jam_masuk))}}</td>
                                                    <td class="text-center">{{date('H:i',strtotime($value->jam_keluar))}}</td>
                                                    <td class="text-center">
                                                        <a href='{{route('ms.event',['tanggal' => base64_encode($value->tanggal),'id_room' =>$room->id,'id_book'=>$value->id])}}' class='btn-link'>
                                                            <i class='fa fa-search'></i>&nbsp;&nbsp;Plan
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endif

                                        @endforeach
                                        @actionEnd
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="sales" role="tabpanel" aria-labelledby="profile-tab">
                                <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                    <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                                        <thead class="table-success">
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Meeting Topic</th>
                                            <th class="text-center">Project</th>
                                            <th class="text-center">Notulen</th>
                                            <th class="text-center">Meeting Date</th>
                                            <th class="text-center">Event</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @actionStart('meeting_scheduler', 'read')
                                        @foreach($topics as $key2 => $value2)
                                            <tr>
                                                <td class="text-center">{{($key2+1)}}</td>
                                                <td class="text-center">{{$value2->topic_meeting}}</td>
                                                <td class="text-center">{{$value2->prjName}}</td>
                                                <td class="text-center">{{$value2->notulaName}}</td>
                                                <td class="text-center">{{date('d-m-Y', strtotime($value2->tanggal))}}</td>
                                                <td class="text-center">
                                                    <a href='{{route('ms.absen',['tanggal'=>base64_encode($value->tanggal),'id_topic' =>$value2->id_topic])}}' class='btn-link'>
                                                        <i class='fa fa-search'></i>&nbsp;&nbsp;Absensi
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @actionEnd
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
@endsection
