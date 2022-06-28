@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Salary Financing Statistic</h3> &nbsp;&nbsp;<a href="{{route('salfin.index')}}" class="btn btn-secondary btn-icon btn-xs" title="Salary Financing List"><i class="fa fa-backspace"></i></a>
            </div>

        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6 col-sm-6">
                    <div class="alert alert-info" role="alert">
                        <i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;&nbsp;To insert new Salary Financing, please click Add to Schedule Payment on the payroll page.
                    </div>
                </div>
                <div class="col-md-6 col-sm-6">
                </div>
            </div>
            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Year</th>
                        <th class="text-center">Payroll</th>
                    </tr>
                    </thead>
                    <tbody>
                    @actionStart('salary_financing','read')

                    @foreach($salaryfinstats as $key => $value)
                        <tr>
                            <td class="text-center">{{($key+1)}}</td>
                            <td class="text-center">{{$value->year}}</td>
                            @php
                                /** @var TYPE_NAME $value */
                                $total = intval($value->sum_amount) + intval($value->sum_jam) + intval($value->sum_health) + intval($value->sum_pension);
                            @endphp
                            <td class="text-center">{{$value->currency}}&nbsp;{{number_format($total,2)}}</td>
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
