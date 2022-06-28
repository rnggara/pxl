@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <a href="{{route('policy.hse.index')}}" title="Policy" class="btn btn-success"><i class="fa fa-backspace"></i>&nbsp;Back</a>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">

                    <a href='javascript:framePrint("print_frame")' target='_self' class='btn btn-success'><i class='fa fa-print'></i> Print</a>&nbsp;&nbsp;&nbsp;
                    <iframe src='{{route('policy.hse.detail.printView', $detail->id_detail)}}' width='0' height='0' frameborder='0' name='print_frame'></iframe>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <style type="text/css">
                .frame {
                    white-space: nowrap;
                    text-align: center;
                    margin: 0.5em;
                }

                .helper {
                    display: inline-block;
                    height: 100%;
                    vertical-align: middle;
                }

                .img-lg {
                    vertical-align: middle;
                    height: 100%
                }
            </style>

            <hr>
            <div class="row">
                <div class="col-md-6">

                </div>
                <div class="col-md-6">
                    <img alt="Logo" src="{{asset('assets/images/'.$dashboard_logo)}}" class="max-h-90px" style="margin-left: -50px"  />
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h2 class="text-center">{{strtoupper(\Session::get('company_name_parent'))}}</h2>
                    <h4 class="text-center">POLICY &nbsp; {{$detail->id_detail}}/{{strtoupper(\Session::get('company_tag'))}}-POLICY/{{date('m/y',strtotime($detail->date_detail))}}</h4>
                    <h4 class="text-center">TOPIC: {{strtoupper($main->topic)}}</h4>
                </div>
            </div>
            <br>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-12">
                        {!! $detail->content !!}
                    </div>
                </div>
            </div>
            <br>
            <hr>
            @if (!empty($detail->attachment))
            <div class="row">
                <div class="col-md-12">
                    <center>
                        <h4>ATTACHMENT</h4>
                    </center>
                    <div class="col-md-12 text-center">
                        <img src="{{str_replace('public','public_html',asset('/media/policy_attachment/'))}}/{{$detail->attachment}}" class="img-responsive center-block">
                    </div>
                </div>
            </div>
            @endif
            <br>
            <hr>

            <div class="row">
                <br>
                <div class="col-md-4">
                    <center>
                        <p>Prepared By</p><br><br><br>
                        <p>{{$detail->created_by}}</p>
                    </center>
                </div>
                <div class="col-md-4">
                    <center>
                        <p>Acknowledged By</p><br><br><br>
                        @if($type != null)
                            @if($type == 'ack')
                                @if($detail->acknowledge_by == null)
                                    <form action="{{route('policy.hse.detail.viewappr.submit')}}" method="POST">
                                        @csrf
                                        <input type="hidden" name="main_id" value="{{$main->id_main}}">
                                        <input type="hidden" name="type" value="{{$type}}">
                                        <input type="hidden" value="{{$detail->id_detail}}" name="id">
                                        <button type="submit" class="btn btn-secondary" value="1" onclick="return confirm('Are you sure?')">Click Here To Sign</button>
                                    </form>

                                @else
                                    <p>{{$detail->acknowledge_by}}</p>
                                @endif
                            @else
                                <p>{{$detail->acknowledge_by}}</p>
                            @endif
                        @else
                            <p>{{$detail->acknowledge_by}}</p>
                        @endif
                    </center>
                </div>
                <div class="col-md-4">
                    <center>
                        <p>Approved By</p><br><br><br>
                        @if($type != null)
                            @if($type == 'appr')
                                @if($detail->approved_by == null)
                                    <form action="{{route('policy.hse.detail.viewappr.submit')}}" method="POST">
                                        @csrf
                                        <input type="hidden" name="main_id" value="{{$main->id_main}}">
                                        <input type="hidden" name="type" value="{{$type}}">
                                        <input type="hidden" value="{{$detail->id_detail}}" name="id">
                                        <button type="submit" class="btn btn-secondary" value="1" onclick="return confirm('Are you sure?')">Click Here To Sign</button>
                                    </form>

                                @else
                                    <p>{{$detail->approved_by}}</p>
                                @endif
                            @else
                                <p>{{$detail->approved_by}}</p>
                            @endif
                        @else
                            <p>{{$detail->approved_by}}</p>
                        @endif
                    </center>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script>
        function framePrint(whichFrame) {
            window.frames[whichFrame].focus();
            window.frames[whichFrame].print();
        }
    </script>
@endsection
