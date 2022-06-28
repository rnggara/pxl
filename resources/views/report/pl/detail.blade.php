@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <a href="javascript:" class="text-black-50">Prognosis - <span class="text-primary">{{$pl->year}}</span></a>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{route('report.pl.actual', $pl->year)}}" class="btn btn-info btn-xs"><i class="fa fa-search-dollar"></i></a>
                    <a href="{{route('report.pl.index')}}" class="btn btn-success btn-xs"><i class="fa fa-arrow-left"></i></a>
                </div>
                <!--end::Button-->
            </div>
        </div>
    </div>
    @foreach($tables as $table)
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>{{ucwords(str_replace("_", " ", $table))}}</h3>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-hover table-bordered">
                        <thead class="bg-warning text-white">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-left">{{strtoupper(str_replace("_", " ", $table))}}</th>
                                <th class="text-center">PASS CODE PROJECT</th>
                                <th class="text-right">NOMINAL</th>
                                <th class="text-center">%</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        /** @var TYPE_NAME $table */
                        /** @var TYPE_NAME $prognosis */
                        $i = 1;
                        $total[$table] = 0;
                        $total_sum[$table] = 0;
                        $percent[$table] = 0;
                        foreach ($prognosis as $item){
                            if ($item->category == $table){
                                $total_sum[$table] += $item->amount;
                            }
                        }
                        ?>
                        @if ($table == "sales")
                            @foreach ($prj_sales as $project)
                                <tr>
                                    <td colspan="6">
                                        <span class="label label-inline label-dark label-lg">
                                            {{ $prj[$project] }}
                                        </span>
                                    </td>
                                </tr>
                                @foreach ($prognosis as $item)
                                    @if ($item->category == $table && $item->id_project == $project)
                                        <tr>
                                            <td align="center">{{$i++}}</td>
                                            <td>{{strtoupper($item->subject)}}</td>
                                            <td align="center">
                                                <span class="label label-md label-primary label-inline">{{$item->RCTR}}</span>
                                            </td>
                                            <td align="right">
                                                @if($table == "operating_expenses")
                                                    {{number_format(($item->amount/100) * $totalsales), 2}}
                                                    <?php
                                                    /** @var TYPE_NAME $item */
                                                    /** @var TYPE_NAME $totalsales */
                                                    $total[$table] += ($item->amount/100) * $totalsales
                                                    ?>
                                                @else
                                                    {{number_format($item->amount, 2)}}
                                                    <?php
                                                    /** @var TYPE_NAME $item */
                                                    $total[$table] += $item->amount
                                                    ?>
                                                @endif
                                            </td>
                                            <td align="center">
                                                @if($table == "operating_expenses")
                                                    {{number_format($item->amount, 2)}} %
                                                    <?php
                                                    /** @var TYPE_NAME $item */
                                                    $percent[$table] += $item->amount;
                                                    ?>
                                                @else
                                                    {{number_format(($item->amount / $totalsales) * 100, 2)}} %
                                                    <?php
                                                    /** @var TYPE_NAME $item */
                                                    $perc = ($item->amount / $totalsales) * 100;
                                                    $percent[$table] += $perc;
                                                    ?>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endforeach
                        @else
                        @foreach($prognosis as $item)
                            @if($item->category == $table)
                                <tr>
                                    <td align="center">{{$i++}}</td>
                                    <td>{{strtoupper($item->subject)}}</td>
                                    <td align="center">
                                        <span class="label label-md label-primary label-inline">{{$item->RCTR}}</span>
                                    </td>
                                    <td align="right">
                                        @if($table == "operating_expenses")
                                            {{number_format(($item->amount/100) * $totalsales), 2}}
                                            <?php
                                            /** @var TYPE_NAME $item */
                                            /** @var TYPE_NAME $totalsales */
                                            $total[$table] += ($item->amount/100) * $totalsales
                                            ?>
                                        @else
                                            {{number_format($item->amount, 2)}}
                                            <?php
                                            /** @var TYPE_NAME $item */
                                            $total[$table] += $item->amount
                                            ?>
                                        @endif
                                    </td>
                                    <td align="center">
                                        @if($table == "operating_expenses")
                                            {{number_format($item->amount, 2)}} %
                                            <?php
                                            /** @var TYPE_NAME $item */
                                            $percent[$table] += $item->amount;
                                            ?>
                                        @else
                                            {{number_format(($item->amount / $totalsales) * 100, 2)}} %
                                            <?php
                                            /** @var TYPE_NAME $item */
                                            $perc = ($item->amount / $totalsales) * 100;
                                            $percent[$table] += $perc;
                                            ?>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        @endif

                        </tbody>
                        <tfoot class="bg-secondary">
                            <tr>
                                <td colspan="2" align="center"><b>TOTAL {{strtoupper($table)}}</b></td>
                                <td align="right">{{number_format($total[$table], 2)}}</td>
                                <td align="center">
                                    @if($percent[$table] > 0)
                                        {{number_format($percent[$table], 2)}} %
                                    @endif
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    <div class="card card-custom gutter-b">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <?php
                    if (count($prognosis) > 0){
                        /** @var TYPE_NAME $tables */
                        $net = $totalsales;
                        foreach ($tables as $table){
                            if ($table != "sales"){
                                $net -= ($total[$table]);
                            }
                        }
                        $netper = $net / $totalsales * 100;
                        $tax = $net * (30/100);
                        $taxper = $tax / $totalsales * 100;
                        $profit = $net - $tax;
                        $profitper = $profit/$totalsales * 100;
                    } else {
                        $net = 0;
                        $netper = 0;
                        $tax = 0;
                        $taxper = 0;
                        $profit = 0;
                        $profitper = 0;
                    }
                    ?>
                    <table class="table table-bordered">
                        <tr>
                            <th colspan="3" class="text-center">NET BEFORE TAX</th>
                            <th class="text-right">
                                {{number_format($net, 2)}}
                            </th>
                            <th class="text-right">
                                {{round(number_format($netper, 2))}} %
                            </th>
                        </tr>
                        <tr>
                            <th colspan="3" class="text-center">TAX (30%)</th>
                            <th class="text-right">
                                {{number_format($tax, 2)}}
                            </th>
                            <th class="text-right">
                                {{round(number_format($taxper, 2))}} %
                            </th>
                        </tr>
                        <tr>
                            <th colspan="3" class="text-center">NET PROFIT AFTER TAX</th>
                            <th class="text-right">
                                {{number_format($profit, 2)}}
                            </th>
                            <th class="text-right">
                                {{round(number_format($profitper, 2))}} %
                            </th>
                        </tr>
                    </table>
                </div>
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
        })

    </script>
@endsection
