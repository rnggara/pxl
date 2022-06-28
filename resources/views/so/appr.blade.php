@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Request Action</h3><br>
            </div>
        </div>
        <div class="card-body">
            <div class="well">
                <table align="left" style="margin-right: 100px">
                    <tr>
                        <td>SO#</td>
                        <td>:</td>
                        <td><b>{{$so->so_num}}</b></td>
                    </tr>
                    <tr>
                        <td>SO Type</td>
                        <td>:</td>
                        <td>{{$so->so_type}}</td>
                    </tr>
                    <tr>
                        <td>Request Date</td>
                        <td>:</td>
                        <td>{{date('d F Y', strtotime($so->so_date))}}</td>
                    </tr>
                    <tr>
                        <td>Back Date</td>
                        <td>:</td>
                        <td>
                            @if($so->bd == 1)
                                <i class="fa fa-check text-success"></i>
                            @else
                                <i class="fa fa-times text-danger"></i>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Division</td>
                        <td>:</td>
                        <td>{{$so->division}}</td>
                    </tr>
                    <tr>
                        <td>Reference</td>
                        <td>:</td>
                        <td>{{$so->reference}}</td>
                    </tr>
                    <tr>
                        <td>Notes</td>
                        <td>:</td>
                        <td>{!! $so->so_notes !!}</td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td>Project</td>
                        <td>:</td>
                        <td>{{(isset($pro[$so->project])) ? $pro[$so->project] : ""}}</td>
                    </tr>
                    <tr>
                        <td>Deliver To</td>
                        <td>:</td>
                        <td>{{$so->deliver_to}}</td>
                    </tr>
                    <tr>
                        <td>Deliver Time</td>
                        <td>:</td>
                        <td>{{$so->deliver_time}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-custom gutter-b">
            <div class="card-body">
                <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-left">Job Desct</th>
                            <th class="text-center">Quantity Request</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($so_det as $key => $value)
                                <tr>
                                    <td align="center">{{$key+1}}</td>
                                    <td>{!! $value->job_desc !!}</td>
                                    <td align="center">{{$value->qty}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <br><br>
                <h4>Confirmation</h4>
                <hr>
                <div class="col-md-12">
                    <form action="{{route('fr.appr.div')}}" method="post">
                        @csrf
                        <input type="hidden" name="fr_id" value="" id="">
                        <div class="col-md-6">
                            <textarea class="form-control" name="notes" id="notes" placeholder="Write note for approve of reject here (optional)" rows="5"></textarea>
                            <br>
                            <button class="btn btn-success" type="button" onclick="button_approve({{$so->id}})" name="submit" id="btn-appr" value="Approve">
                                <i class="fa fa-check"></i>&nbsp;&nbsp;Approve
                            </button>&nbsp;
                            <button class="btn btn-danger" type="button" onclick="button_reject({{$so->id}})" name="submit" id="btn-reject" value="Reject">
                                <i class="fa fa-times"></i>&nbsp;&nbsp;Reject
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script>
        function button_approve(x){
            Swal.fire({
                title: "Approve",
                text: "Are you sure you want to approve?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Approve",
                cancelButtonText: "Cancel",
                reverseButtons: true,
            }).then(function(result){
                if(result.value){
                    $.ajax({
                        url: '{{URL::route('so.approve')}}',
                        data: {
                            '_token': '{{csrf_token()}}',
                            'id': x,
                            'notes' : $("#notes").val()
                        },
                        type: "POST",
                        cache: false,
                        dataType: 'json',
                        success : function(response){
                            if (response.error = 1){
                                window.location = "{{URL::route('general.so')}}"
                            } else {
                                Swal.fire({
                                    title: "Approve Error",
                                    icon: "error"
                                })
                            }
                        }
                    })
                }
            })
        }

        function button_reject(x){
            Swal.fire({
                title: "Reject",
                text: "Are you sure you want to reject?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Reject",
                cancelButtonText: "Cancel",
                reverseButtons: true,
            }).then(function(result){
                if(result.value){
                    $.ajax({
                        url: '{{URL::route('so.reject')}}',
                        data: {
                            '_token': '{{csrf_token()}}',
                            'id': x,
                            'notes' : $("#notes").val()
                        },
                        type: "POST",
                        cache: false,
                        dataType: 'json',
                        success : function(response){
                            if (response.error = 1){
                                window.location = "{{URL::route('general.so')}}"
                            } else {
                                Swal.fire({
                                    title: "Approve Error",
                                    icon: "error"
                                })
                            }
                        }
                    })
                }
            })
        }
    </script>
@endsection
