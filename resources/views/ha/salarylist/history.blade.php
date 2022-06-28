@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">
                    Salary History
                </h3>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{route('salarylist.index')}}" class="btn btn-secondary"><i class="fa fa-backspace"></i> Back</a>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-custom bg-secondary">
                        <div class="separator separator-solid separator-white opacity-20"></div>
                        <div class="card-body text-black-50">
                            <table border="0">
                                <tr>
                                    <td>Name</td>
                                    <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                    <td>&nbsp;&nbsp;{{$emp->emp_name}}</td>
                                </tr>
                                <tr>
                                    <td>ID</td>
                                    <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                    <td>&nbsp;&nbsp;{{$emp->emp_id}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <br><br><br><br>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered table-hover display table-responsive-xl">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Date</th>
                            <th class="text-center">Time</th>
                            <th class="text-center">Execute By</th>
                            <th class="text-center">Basic Salary</th>
                            <th class="text-center">Voucher</th>
                            <th class="text-center">Position Allowance</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $no = 1;
                        @endphp
                        @foreach($histories as $key => $value)
                            <tr>
                                <td class="text-center">{{($no++)}}</td>
                                <td class="text-center">{{date('d F Y', strtotime($value->execute_time))}}</td>
                                <td class="text-center">{{date('H:i:s', strtotime($value->execute_time))}}</td>
                                <td class="text-center">{{$value->user}}</td>
                                <td class="text-center"><label class="text-success">{{number_format($value->basic,2)}}</label></td>
                                <td class="text-center">{{number_format($value->voucher,2)}}</td>
                                <td class="text-center">{{number_format($value->position,2)}}</td>
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
        $(document).ready(function(){
            $("table.display").DataTable({
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            })
        })
    </script>
@endsection
