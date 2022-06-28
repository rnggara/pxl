@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">Zoom Meeting - {{ $meeting->description }}</h3>
            <div class="card-toolbar">
                <div class="btn-group">

                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table table-borderless">
                        <tr>
                            <th>Meeting Date</th>
                            <th> : </th>
                            <th>{{ date("d F Y", strtotime($meeting->meeting_date)) }}</th>
                        </tr>
                        <tr>
                            <th>Meeting Time</th>
                            <th> : </th>
                            <th>{{ date("H:i", strtotime($meeting->meeting_time)) }}</th>
                        </tr>
                        <tr>
                            <th>Meeting Link</th>
                            <th> : </th>
                            <th>
                                <div class="checkbox-list">
                                    <label class="checkbox checkbox-outline checkbox-primary checkbox-outline-2x"><input type="checkbox" {{ (!empty($isJoin)) ? "CHECKED" : "" }} onclick="zoom_join(this)" name="cb" data-id="{{ $meeting->id }}" />
                                        <span></span>
                                        <a href="{{ (!empty($isJoin)) ? $meeting->link_zoom : "" }}" target="_blank" id="link" style="word-break: break-all">
                                            @if (!empty($isJoin))
                                            {{ $meeting->link_zoom }}
                                            @endif
                                        </a>
                                    </label>
                                </div>
                            </th>
                        </tr>
                    </table>
                </div>
                <div class="col-12">
                    <hr>
                </div>
                <div class="col-md-6 col-sm-12" id="table-show">
                    <table class="table table-bordered display">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Participant Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($participant as $i => $item)
                                <tr>
                                    <td align="center">{{ $i+1 }}</td>
                                    <td>{{ $item->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        function zoom_join(cb){
            var id = $(cb).data("id")
            var checked = cb.checked
            var _checked = 0
            if(checked){
                _checked = 1
            } else {
                _checked = 0
            }
            $.ajax({
                url : "{{ route("mz.join") }}",
                type : "post",
                dataType : "json",
                data : {
                    _token : "{{ csrf_token() }}",
                    id_meeting : {{ $meeting->id }},
                    user_id : {{ \Auth::id() }},
                    checked : _checked
                },
                beforeSend : function(){
                    Swal.fire({
                        title: "Proccessing",
                        text: "Please wait!",
                        onOpen: function() {
                            Swal.showLoading()
                        }
                    })
                },
                success : function(response){
                    if(response.success){
                        if(response.link == ""){
                            $("#table-show").hide()
                        } else {
                            $("#table-show").show()
                        }
                        $("#link").text(response.link)
                        $("#link").attr("href", response.link)
                    }
                    swal.close()
                }
            })
        }
        $(document).ready(function(){
            $("table.display").DataTable()

            @if (empty($isJoin))
                $("#table-show").hide()
            @endif
        })
    </script>
@endsection
