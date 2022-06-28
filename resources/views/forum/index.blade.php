@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <!--begin::Header-->
        <div class="card-header row row-marginless align-items-center flex-wrap h-auto">
            <div class="card-title">
                <h3>Forums</h3><br>

            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addItem"><i class="fa fa-plus"></i>Create Forum</button>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body table-responsive px-0">
            <!--begin::Items-->
            @foreach($forums as $key => $value)
                <div class="list list-hover min-w-500px">
                    <div class="d-flex align-items-start list-item card-spacer-x py-3" >
                        <!--begin::Toolbar-->
                        <div class="d-flex align-items-center">
                            <!--begin::Author-->
                            <div class="d-flex align-items-center flex-wrap min-w-xxl-700px mr-3">
                                <div class="symbol symbol-light-danger symbol-35 mr-3">
                                    @php
                                        /** @var TYPE_NAME $value */
                                        if ($value->nama_forum == trim($value->nama_forum) && strpos($value->nama_forum, ' ') !== false) {
                                            $str = explode(' ', $value->nama_forum);
                                            $pertama = $str[0];
                                            $kedua = $str[1];
                                            $a = $pertama[0];
                                            $b = $kedua[0];
                                            $img = strtoupper($a.$b);
                                        } else {
                                            $str = $value->nama_forum;
                                            $img = strtoupper(substr($str, 0, 2));
                                        }
                                    @endphp
                                    <span class="symbol-label font-weight-bolder">{{$img}}</span>
                                </div>
                                <a href="{{route('forum.topic',['id' => $value->id])}}" class="font-weight-bold text-dark-75 text-hover-primary">{{$value->nama_forum}}</a>
                            </div>
                            <!--end::Author-->
                        </div>
                        <div class="flex-grow-1 mt-2 mr-2">
                            <div>
                                @php
                                    $tpc = 0;
                                    $cmt = 0;
                                @endphp
                                @foreach($topics as $key2 => $value2)
                                    @if($value->id_forum == $value2->id_forum)
                                        @php
                                            /** @var TYPE_NAME $tpc */
                                            $tpc += 1;
                                        @endphp
                                        @foreach($comments as $key3 =>$value3)
                                            @if($value2->id_topik == $value3->id_topik)
                                                @php
                                                    /** @var TYPE_NAME $cmt */
                                                    $cmt += 1;
                                                @endphp
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                                <span class="font-weight-bolder font-size-lg mr-2">Topics: {{$tpc}}&nbsp;&nbsp;| &nbsp;&nbsp;Posts: {{$cmt}}&nbsp;&nbsp;</span><br>
                                <span class="text-muted">initialize by {{$value->created_by}} <br> {{date('d F Y', strtotime($value->date_forum))}} |  {{date('H:i', strtotime($value->date_forum))}}</span>
                            </div>
                        </div>
                        @php
                            /** @var TYPE_NAME $value */
                            $datetime1 = strtotime(date('Y-m-d',strtotime($value->date_forum)));
                            $datetime2 = strtotime(date('Y-m-d'));
                            $secs = $datetime2 - $datetime1;
                            $days = $secs / 86400;
                        @endphp
                        <div class="mt-2 mr-3 font-weight-bolder w-100px text-right" >
                            @if($days == 0)
                                Today
                            @elseif($days == 1)
                                Yesterday
                            @else
                                {{$days}}&nbsp;day ago
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="modal fade" id="addItem" tabindex="-1" role="dialog" aria-labelledby="addItem" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Forum</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" id="form-add" action="{{route('forum.store')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Forum Name</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="Name" name="forum_name" required>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script src="{{asset('theme/assets/js/pages/custom/inbox/inbox.js?v=7.0.5')}}"></script>
@endsection
