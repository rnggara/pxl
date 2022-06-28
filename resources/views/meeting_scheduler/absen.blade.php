@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">
                    Meeting Presence
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
                <h6>Meeting Leader & Notulen</h6>
                <hr>
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th class="text-center">Role As</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Meeting Topic</th>
                        <th class="text-center">Meeting Hour</th>
                        <th class="text-center">Meeting Date</th>
                        <th class="text-center">Location</th>
                        <th class="text-center">Kehadiran</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @actionStart('meeting_scheduler', 'read')
                    @foreach($absensi as $key => $value)
                        @if($value->divisi != '')
                        @php
                            $bg = "text-danger";
                            if($value->kehadiran == "hadir"){
                                $bg = "text-primary";
                            }
                        @endphp
                        <tr>
                            <td class="text-center">{{$value->divisi}}</td>
                            <td class="text-center">{{$value->nama}}</td>
                            <td class="text-center">{{$value->meetingTopic}}</td>
                            <td class="text-center">{{$value->meetingIn}} - {{$value->meetingOut}}</td>
                            <td class="text-center">{{date('d F Y',strtotime($value->meetingDate))}}</td>
                            <td class="text-center">{{$value->location}}</td>
                            <td class="text-center">
                                @if (!empty($value->kehadiran))
                                    <span class="font-weight-bold {{ $bg }}">{{ ucwords($value->kehadiran) }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(empty($value->kehadiran))
                                    <div class="btn-group">
                                        <form action="{{ route('ms.update.status') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="id_absen" value="{{ $value->id }}">
                                            <button type="submit" name="absen" value="1" class="btn btn-primary btn-sm">Hadir</button>
                                            <button type="submit" name="absen"  value="-1" class="btn btn-danger btn-sm">Tidak Hadir</button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @endif
                    @endforeach
                    @actionEnd
                    </tbody>
                </table>
            </div>
            <br><br><br><br>
            <div class="row">
                <h6>Meeting Participants</h6>
                <hr>
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Meeting Topic</th>
                        <th class="text-center">Meeting Hour</th>
                        <th class="text-center">Meeting Date</th>
                        <th class="text-center">Location</th>
                        <th class="text-center">Kehadiran</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @actionStart('meeting_scheduler', 'read')
                    @php
                    $no = 0;
                    @endphp
                    @foreach($absensi as $key => $value)
                        @if($value->divisi == '')
                            @php
                                /** @var TYPE_NAME $no */
                            $no += 1;
                            $bg = "text-danger";
                            if($value->kehadiran == "hadir"){
                                $bg = "text-primary";
                            }
                            @endphp
                            <tr>
                                <td class="text-center">{{($no)}}</td>
                                <td class="text-center">{{$value->nama}}</td>
                                <td class="text-center">{{$value->meetingTopic}}</td>
                                <td class="text-center">{{$value->meetingIn}} - {{$value->meetingOut}}</td>
                                <td class="text-center">{{date('d F Y',strtotime($value->meetingDate))}}</td>
                                <td class="text-center">{{$value->location}}</td>
                                <td class="text-center">
                                    @if (!empty($value->kehadiran))
                                        <span class="font-weight-bold {{ $bg }}">{{ ucwords($value->kehadiran) }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(empty($value->kehadiran))
                                    <div class="btn-group">
                                        <form action="{{ route('ms.update.status') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="id_absen" value="{{ $value->id }}">
                                            <button type="submit" name="absen" value="1" class="btn btn-primary btn-sm">Hadir</button>
                                            <button type="submit" name="absen"  value="-1" class="btn btn-danger btn-sm">Tidak Hadir</button>
                                        </form>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    @actionEnd
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')

@endsection
