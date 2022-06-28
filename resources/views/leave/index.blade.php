@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                Leave Request Approval
            </div>

        </div>
        <div class="card-body">
            <table class="table display">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employee Name {{count($leave)}}</th>
                        <th>Division</th>
                        <th>Request Date</th>
                        <th>Start Leave</th>
                        <th>End Leave</th>
                        <th>Leave Amount (Day)</th>
                        <th>Leave Reason</th>
                        <th>Manager Division</th>
                        <th>HRD Approval</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($leave as $key => $value)
                        <tr>
                            <td>{{$key + 1}}</td>
                            <td>
                                @foreach($emp as $e)
                                    @if($value->id_emp == $e->id)
                                        {{$e->emp_name}}
                                    @endif
                                @endforeach
                            </td>
                            <td></td>
                            <td>{{date('d M Y', strtotime($value->request_at))}}</td>
                            <td>{{date('d M Y', strtotime($value->awal))}}</td>
                            <td>{{date('d M Y', strtotime($value->akhir))}}</td>
                            <td align="center">{{date_diff(date_create($value->awal), date_create($value->akhir))->format('%a') + 1}} day(s)</td>
                            <td>{{$value->keterangan}}</td>
                            <td>
                                @if(empty($value->div_by))
                                    <button type="button" id="btnDiv_{{$key}}" onclick="button_approve({{$value->c_id}}, 'div')" class="btn btn-primary btn-xs"> Approve</button>
                                @else
                                    Approved at {{$value->div_date}}
                                @endif
                            </td>
                            <td>@if(empty($value->hrd_by))
                                    @if(empty($value->div_by))
                                        waiting
                                    @else
                                        <button type="button" id="btnHrd_{{$key}}" @actionStart('leave_approval', 'approvediv1') onclick="button_approve({{$value->c_id}}, 'hrd')"@actionEnd class="btn btn-primary btn-xs"> Approve</button>
                                    @endif
                                @else
                                    Approved at {{$value->hrd_date}}
                                @endif</td>
                            <td>
                                @actionStart('leave_approval', 'delete')
                                <button type="button" id="btnDel_{{$key}}" @actionStart('leave_approval', 'approvediv2') onclick="button_delete({{$value->c_id}})"@actionEnd class="btn btn-danger btn-xs btn-icon"><i class="fa fa-trash"></i></button>
                                @actionEnd
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        function button_approve(x, y){
            var txt = ""
            if (y == "div"){
                txt = "DIVISION"
            } else {
                txt = "HRD"
            }
            Swal.fire({
                title: "Approval " + txt,
                text: "Aprrove this leave request?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Approve",
                cancelButtonText: "Cancel",
                reverseButtons: true,
            }).then(function(result){
                if(result.value){
                    $.ajax({
                        url: "{{route('leave.approve')}}",
                        type: "post",
                        dataType: "json",
                        cache: false,
                        data: {
                            '_token': "{{csrf_token()}}",
                            'id': x,
                            'appr': y,
                        },
                        success: function(response){
                            if (response.error == 0){
                                location.reload()
                            } else {
                                Swal.fire('Error Occured', 'Please contact your administrator', 'error')
                            }
                        }
                    })
                }
            })
        }

        function button_delete(x){
            Swal.fire({
                title: "Delete ",
                text: "Delete this leave request?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Delete",
                cancelButtonText: "Cancel",
                reverseButtons: true,
            }).then(function(result){
                if(result.value){
                    $.ajax({
                        url: "{{route('leave.delete')}}/"+ x,
                        type: "get",
                        dataType: "json",
                        cache: false,
                        success: function(response){
                            if (response.error == 0){
                                location.reload()
                            } else {
                                Swal.fire('Error Occured', 'Please contact your administrator', 'error')
                            }
                        }
                    })
                }
            })
        }

        $(document).ready(function(){
            $("table.display").DataTable({
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            })

        })
    </script>
@endsection
