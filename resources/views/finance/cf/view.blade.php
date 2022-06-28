@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">Cashflow - {{ $st->label }}</h3>
            <div class="card-toolbar">
                Periode : {{ date("F Y", strtotime($year."-".$month)) }}
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="col-12">
                        <table class="table table-bordered table-hover display" data-page-length="100">
                            <thead>
                                <tr>
                                    <th class="text-center" colspan="6">{{ $st->label }} <br> Period : {{ date("F Y", strtotime("$year-$month")) }}</th>
                                </tr>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Project/TC</th>
                                    <th class="text-center">Description</th>
                                    <th class="text-center">Amount (IDR)</th>
                                    <th class="text-center">Amount (USD)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $sumIDR = 0;
                                    $sumUSD = 0;
                                    $num = 1;
                                @endphp
                                @foreach ($data as $i => $item)
                                    <tr>
                                        <td align="center">{{ $num++ }}</td>
                                        <td align="center">{{ date("d-m-Y", strtotime($item['date'])) }}</td>
                                        <td align="center">[{{ $item['project'] }}] {{ $item['no_coa'] }}</td>
                                        <td>{!! $item['description'] !!}</td>
                                        <td align="right">{{ number_format($item['IDR'], 2) }}</td>
                                        <td align="right">{{ number_format($item['USD'], 2) }}</td>
                                    </tr>
                                    @php
                                        $sumIDR += $item['IDR'];
                                        $sumUSD += $item['USD'];
                                    @endphp
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-center" colspan="4">TOTAL</th>
                                    <th class="text-right">{{ number_format($sumIDR, 2) }}</th>
                                    <th class="text-right">{{ number_format($sumUSD, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        $(document).ready(function(){
            $("table.display").DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'excel', 'pdf', 'print'
                ]
            })
        })
    </script>
@endsection
