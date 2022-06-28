@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Project Fees</h3>
            </div>
            <div class="card-toolbar">

                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th nowrap="nowrap" class="text-left">Project Name</th>
                        <th nowrap="nowrap" class="text-right">Project Amount</th>
                        <th nowrap="nowrap" class="text-right">Fee Amount</th>
                        <th nowrap="nowrap" class="text-center">Approval</th>
                        <th nowrap="nowrap" class="text-center">Payment</th>
{{--                        <th nowrap="nowrap" class="text-center" data-priority=1>#</th>--}}
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($projects as $key => $value)
                        <tr>
                            <td class="text-center">{{($key+1)}}</td>
                            <td class="text-left">{{$value->prj_name}}</td>
                            <td class="text-right">IDR {{(isset($value->value))? number_format($value->value,2):'-'}}</td>
                            <td class="text-right">IDR {{(isset($value->total_fee)) ? number_format($value->total_fee,2):'-'}}</td>
                            <td class="text-center">
                                @if($value->fee_approve_at!=null)
                                    {{date('d M Y', strtotime($value->fee_approve_at))}}
                                @else
                                    @if(isset($value->total_fee))
                                    <a href="{{route('hrd.project_fees.detail', $value->id)}}" class="btn btn-link btn-xs"><i class="fa fa-clock"></i>waiting</a>
                                    @else
                                        waiting for value
                                    @endif
                                @endif
                            </td>
                            <td class="text-center">
                                @if($value->paid_at!=null)
                                    {{date('d M Y', strtotime($value->paid_at))}}
                                @else
                                    @if($value->fee_approve_at == null)
                                        waiting for approval
                                    @else
                                        <a href="{{route('hrd.project_fees.detail', $value->id)}}" class="btn btn-link btn-xs"><i class="fa fa-clock"></i>waiting</a>
                                    @endif
                                @endif
                            </td>
{{--                            <td class="text-center">--}}
{{--                                <a href="#" class="btn btn-danger btn-xs btn-icon"><i class="fa fa-trash"></i></a>--}}
{{--                            </td>--}}
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

        $(document).ready(function () {
            $('.display').DataTable({
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            });
            $("select.select2").select2({
                width: "100%"
            })

        });



    </script>
@endsection
