@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">{{ $coa->code }} - {{ $coa->name }}</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered table-hover display" data-page-length="100">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 10%">#</th>
                                <th class="text-center">Description</th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalSum = 0;
                            @endphp
                            @foreach ($coa_his as $i => $item)
                                <tr>
                                    <td align="center">{{ $i+1 }}</td>
                                    <td>
                                        <a href="{{ route('treasury.viewcoa', $item->id_treasure_history) }}">{!! (isset($tre_hist[$item->id_treasure_history])) ? $tre_hist[$item->id_treasure_history] : $item->description ?? "N/A" !!}</a>
                                    </td>
                                    <td align="center">{{ date('d F Y', strtotime($item->coa_date)) }}</td>
                                    <td align="right">{{ number_format((empty($item->debit)) ? $item->credit * -1 : $item->debit, 2) }}</td>
                                </tr>
                                @php
                                    $totalSum += (empty($item->debit)) ? $item->credit * -1 : $item->debit;
                                @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td align="center" colspan="3">Total Amount</td>
                                <td align="right">
                                    {{ number_format($totalSum, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        $(document).ready(function(){
            $("table.display").DataTable()
        })
    </script>
@endsection
