@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">Cashflow - {{ date("F Y", strtotime($_t)) }}</h3>
            <div class="card-toolbar">
                Periode :
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="col-12">
                        <table class="table table-bordered table-hover display" data-page-length="100">
                            <thead>
                                <tr>
                                    <th class="text-center" colspan="8">Period : {{ date("F Y", strtotime($_t)) }}</th>
                                </tr>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Bank</th>
                                    <th class="text-center">Project/TC</th>
                                    <th class="text-center">Description</th>
                                    <th class="text-center">Credit</th>
                                    <th class="text-center">Debit</th>
                                    <th class="text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($childs as $type => $item)
                                    <tr class="bg-secondary">
                                        <td colspan="8" align="center">
                                            <span class="font-weight-bold">{{ strtoupper(str_replace("_", " ", $type)) }}</span>
                                        </td>
                                        <td style="display: none"></td>
                                        <td style="display: none"></td>
                                        <td style="display: none"></td>
                                        <td style="display: none"></td>
                                        <td style="display: none"></td>
                                        <td style="display: none"></td>
                                        <td style="display: none"></td>
                                    </tr>
                                    @foreach ($item as $label => $detail)
                                        <tr class="table-primary">
                                            <td colspan="8">
                                                <span class="font-weight-bold">
                                                    {{ strtoupper(str_replace("_", " ", $label)) }}
                                                </span>
                                            </td>
                                            <td style="display: none"></td>
                                            <td style="display: none"></td>
                                            <td style="display: none"></td>
                                            <td style="display: none"></td>
                                            <td style="display: none"></td>
                                            <td style="display: none"></td>
                                            <td style="display: none"></td>
                                        </tr>
                                        @foreach ($detail as $key => $value)
                                            <tr>
                                                <td align="center"></td>
                                                <td colspan="7">{{ $key }}</td>
                                                <td style="display: none"></td>
                                                <td style="display: none"></td>
                                                <td style="display: none"></td>
                                                <td style="display: none"></td>
                                                <td style="display: none"></td>
                                                <td style="display: none"></td>
                                            </tr>
                                            @php
                                                $sum_credit = 0;
                                                $sum_debit = 0;
                                            @endphp
                                            @foreach ($value as $val)
                                                @foreach ($val as $i => $row)
                                                    @php
                                                        $id_bank = (isset($his[$row->id_treasure_history])) ? $his[$row->id_treasure_history] : null;
                                                        $bank = (isset($treasure[$id_bank])) ? $treasure[$id_bank] : "N/A";
                                                    @endphp
                                                    <tr>
                                                        <td align="center">{{ $i+1 }}</td>
                                                        <td align="center">{{ date("d-M-y", strtotime($row->coa_date)) }}</td>
                                                        <td align="center">{{ $bank }}</td>
                                                        <td align="center">
                                                            {{ (!empty($row->project)) ? "[$row->project]" : "" }}
                                                            {{ $row->no_coa }}
                                                        </td>
                                                        <td>{{ $row->description }}</td>
                                                        <td align="right">{{ number_format(abs($row->credit), 2) }}</td>
                                                        <td align="right">{{ number_format(abs($row->debit), 2) }}</td>
                                                        <td></td>
                                                    </tr>
                                                    @php
                                                        $sum_credit += abs($row->credit);
                                                        $sum_debit += abs($row->debit);
                                                    @endphp
                                                @endforeach
                                            @endforeach
                                            <tr class="table-secondary">
                                                <td align="center"></td>
                                                <td colspan="4">Total {{ $key }}</td>
                                                <td style="display: none"></td>
                                                <td style="display: none"></td>
                                                <td style="display: none"></td>
                                                <td align="right">{{ number_format($sum_credit, 2) }}</td>
                                                <td align="right">{{ number_format($sum_debit, 2) }}</td>
                                                @if ($type == "cash_in")
                                                    <td align="right">{{ number_format($sum_credit - $sum_debit, 2) }}</td>
                                                @else
                                                    <td align="right">{{ number_format($sum_debit - $sum_credit, 2) }}</td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    @endforeach
                                @endforeach
                            </tbody>
                            <tfoot>
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
                ordering : false,
                paging : false,
                dom: 'Bfrtip',
                buttons: [
                    'excel', 'pdf', 'print'
                ]
            })
        })
    </script>
@endsection
