@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Salary Financing List</h3> &nbsp;&nbsp;<a href="{{route('salfin.stat')}}" class="btn btn-warning btn-icon btn-xs" title="Salary Financing Statistics"><i class="fa fa-chart-line"></i></a>
            </div>

        </div>

        <div class="card-body">

            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Salary Periode</th>
                        <th class="text-center">Type</th>
                        <th class="text-right">Total</th>
                        <th class="text-center">Payment Date Plan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @actionStart('salary_financing','read')
                    @foreach($salaryfins as $key => $value)
                        <tr>
                            <td class="text-center" class="text-center">{{($key+1)}}</td>
                            <td class="text-center" class="text-center">{{date('d-m-Y',strtotime($value->salary_date))}}</td>
                            <td class="text-center">{{$value->position}}</td>

                            <td class="text-right">
                                <b>
                                    @php
                                        /** @var TYPE_NAME $value */
                                        $total = intval($value->pension)+intval($value->jamsostek)+intval($value->health_insurance)+intval($value->amount);
                                    @endphp
                                    {{$value->currency}}&nbsp;{{number_format($total,2)}}
                                </b>
                            </td>
                            <td class="text-center">
{{--                                @if($value->status =='waiting')--}}
{{--                                    <form class="form-check-inline" method="post" action="{{route('salfin.pay')}}" style="max-width: 140px">--}}
{{--                                        @csrf--}}
{{--                                        <input type="hidden" name="id" id="id" value="{{$value->id}}"/>--}}
{{--                                        <div class="form-group">--}}
{{--                                            <input type="date" class="form-control" name="plan_date" value="{{$value->plan_date}}">--}}
{{--                                        </div>&nbsp;--}}
{{--                                        <div class="form-group">--}}
{{--                                            <button type="submit" name="save" id="save" value="save" class="btn btn-light-primary btn-xs">Save</button>--}}
{{--                                        </div>--}}
{{--                                    </form>--}}
{{--                                @else--}}
{{--                                    {{$value->plan_date}}--}}
{{--                                @endif--}}
                                {{$value->plan_date}}
                            </td>
                            <td class="text-center"><i class="fa fa-clock"></i>&nbsp;&nbsp;{{$value->status}}</td>
                            <td class="text-center">
                                <a href='{{ route('salfin.delete', $value->id) }}' onclick="return confirm('Are you sure you want to delete?'); " class="btn btn-danger btn-xs btn-icon" title="Delete"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    @actionEnd
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
        });
    </script>
@endsection
