@extends('layouts.template')
@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
                <h3>SOP of <span class="text-primary">{{$sop_main->topic}}</span> </h3>
            </div>

            <div class="card-toolbar">
                <!--end::Button-->
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{route('sop.add_detail',['id_main' => $sop_main->id,'status' => 1])}}" class="btn btn-primary"><i class="fa fa-plus"></i>Add Revision</a>
                    &nbsp;&nbsp;
                    <a href="{{route('sop.index')}}" class="btn btn-success btn-icon"><i class="fa fa-arrow-left"></i></a>
                </div>
            </div>

        </div>
        <div class="card-body">
            <!--begin: Datatable-->
            <table class="table table-bordered table-hover table-checkable" id="kt_datatable1" style="margin-top: 13px !important">
                <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Create Date | Time</th>
                    <th class="text-center">SOP#</th>
                    <th class="text-center">View/Approve</th>
                    <th class="text-center">Create by</th>
                    <th class="text-center">Acknowledge by</th>
                    <th class="text-center">Approved by</th>
                    <th class="text-center">Revision#</th>
                    <th class="text-center"></th>
                </tr>
                </thead>
                <tbody>
                @foreach($details as $key => $value)
                    <tr>
                        <td class="text-center">{{($key+1)}}</td>
                        <td class="text-center">{{date("d M Y H:i",strtotime($value->date_detail))}}</td>
                        <td class="text-center">{{$value->id."/".strtoupper(Session::get('company_tag'))."-SOP/".date("m/y",strtotime($value->date_detail))}}</td>
                        <td class="text-center">
                            @if($value->approved_by == null && $value->acknowledge_by == null)
                                <a href='{{route('sop.detail_view',['id_detail' => $value->id,'act' => 'ack'])}}' class="btn btn-primary btn-xs btn-icon" title="View/Approve"><i class="fa fa-eye"></i></a>
                            @elseif($value->approved_by == null && $value->acknowledge_by != null)
                                <a href='{{route('sop.detail_view',['id_detail' => $value->id,'act' => 'app'])}}' class="btn btn-primary btn-xs btn-icon" title="View/Approve"><i class="fa fa-eye"></i></a>
                            @else
                                <a href='{{route('sop.detail_view',['id_detail' => $value->id])}}' class="btn btn-primary btn-xs btn-icon" title="View/Approve"><i class="fa fa-eye"></i></a>
                            @endif
                        </td>
                        <td class="text-center">{{($value->created_by != null)?$value->created_by:'N/A'}}</td>
                        <td class="text-center">{{($value->acknowledge_by != null)?$value->acknowledge_by:'N/A'}}</td>
                        <td class="text-center">{{($value->approved_by != null)?$value->approved_by:'N/A'}}</td>
                        <td class="text-center">{{($value->revision != null)?$value->revision:'-'}}</td>
                        <td class="text-center"></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <!--end: Datatable-->
        </div>
    </div>
@endsection
@section('custom_script')

    <script>
        $(document).ready(function (){
            $('#kt_datatable1').DataTable({
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            })
        })
    </script>
@endsection
