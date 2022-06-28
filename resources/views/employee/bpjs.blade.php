@extends('layouts.template')

@section('content')
<div class="card card-custom gutter-b">
    <div class="card-header">
        <h3 class="card-title">BPJS KESEHATAN</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <table class="table table-bordered table-hover display" data-page-length="100">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Allowance</th>
                            <th class="text-center">Deduction</th>
                            <th class="text-center">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $sum_allow = 0;
                            $sum_deduc = 0;
                            $sum = 0;
                        @endphp
                        @foreach ($emp as $i => $item)
                            <tr>
                                <td align="center">{{ $i + 1 }}</td>
                                <td>{{ $item->emp_name }}</td>
                                <td align="right">
                                    {{ number_format($item->allow_bpjs_kes, 2) }}
                                </td>
                                <td align="right">
                                    {{ number_format($item->deduc_bpjs_kes, 2) }}
                                </td>
                                <td align="right">
                                    {{ number_format($item->allow_bpjs_kes + $item->deduc_bpjs_kes, 2) }}
                                </td>
                            </tr>
                            @php
                                $sum += $item->allow_bpjs_kes + $item->deduc_bpjs_kes;
                                $sum_allow += $item->allow_bpjs_kes;
                                $sum_deduc += $item->deduc_bpjs_kes;
                            @endphp
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2" class="text-center">Total</th>
                            <th class="text-right">{{ number_format($sum_allow, 2) }}</th>
                            <th class="text-right">{{ number_format($sum_deduc, 2) }}</th>
                            <th class="text-right">{{ number_format($sum, 2) }}</th>
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
        $("table.display").DataTable({
            dom: 'Bfrtip',
            buttons: [
                'excel'
            ]
        })
    })
</script>
@endsection
