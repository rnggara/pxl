@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Treasury Detail</h3><br>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{URL::route('treasury.index')}}" class="btn btn-success btn-xs"><i class="fa fa-arrow-circle-left"></i></a>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div class="card card-custom bg-primary m-5">
                <div class="separator separator-solid separator-white opacity-20"></div>
                <div class="card-body text-white">
                    <table class="text-white">
                        <tr>
                            <td>Bank Name</td>
                            <td>&nbsp;:&nbsp;</td>
                            <td>{{$treasure->source}}</td>
                        </tr>
                        <tr>
                            <td>Branch Name</td>
                            <td>&nbsp;:&nbsp;</td>
                            <td>{{$treasure->branch}}</td>
                        </tr>
                        <tr>
                            <td>Account Name</td>
                            <td>&nbsp;:&nbsp;</td>
                            <td>{{$treasure->account_name}}</td>
                        </tr>
                        <tr>
                            <td>Account Number</td>
                            <td>&nbsp;:&nbsp;</td>
                            <td>{{$treasure->account_number}}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="separator separator-dashed separator-border-2 separator-primary"></div>
            <div class="m-5">
                <table class="table display">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Credit</th>
                        <th class="text-center">Debit</th>
                        <th class="text-center">Description</th>
                        <th class="text-center"></th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($tre_his as $key => $value)
                            <tr>
                                <td align="center">{{$key + 1}}</td>
                                <td align="center">{{date('d F Y', strtotime($value->date_insert))}}</td>
                                <td align="center">
                                    <label class="text-success">{{($value->IDR > 0) ? number_format($value->IDR, 2) : number_format(0, 2)}}</label>
                                </td>
                                <td align="center">
                                    <label class="text-danger">{{($value->IDR < 0) ? number_format(str_replace("-", "", $value->IDR), 2) : number_format(0, 2)}}</label>
                                </td>
                                <td align="center">{{$value->description}}</td>
                                <td align="center">
                                    <button type="button" onclick="button_approve('{{base64_encode(rand(100, 999)."-".$value->id)}}')" class="btn btn-xs btn-success">Approve</button>
                                    <button type="button" onclick="button_reject('{{base64_encode(rand(100, 999)."-".$value->id)}}')" class="btn btn-xs btn-danger">Reject</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
                confirmButtonText: "Submit",
                cancelButtonText: "Cancel",
                reverseButtons: true,
            }).then(function(result){
                if(result.value){
                    $.ajax({
                        url: "{{URL::route('treasury.approve')}}",
                        type: "POST",
                        dataType: "json",
                        data: {
                            '_token' : '{{csrf_token()}}',
                            'val' : x
                        },
                        cache: false,
                        success: function(response){
                            if (response.error == 0) {
                                location.reload()
                            } else {
                                Swal.fire({
                                    title: "Error Occured",
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
                confirmButtonText: "Submit",
                cancelButtonText: "Cancel",
                reverseButtons: true,
            }).then(function(result){
                if(result.value){
                    $.ajax({
                        url: "{{URL::route('treasury.reject')}}",
                        type: "POST",
                        dataType: "json",
                        data: {
                            '_token' : '{{csrf_token()}}',
                            'val' : x
                        },
                        cache: false,
                        success: function(response){
                            if (response.error == 0) {
                                location.reload()
                            } else {
                                Swal.fire({
                                    title: "Error Occured",
                                    icon: "error"
                                })
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
