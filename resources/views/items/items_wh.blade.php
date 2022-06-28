@extends('layouts.template')

@section('css')
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #print-page, #print-page * {
                visibility: visible;
            }
            #print-page {
                position: absolute;
                left: 0;
                top: 0;
            }
        }
    </style>
@endsection

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">Item Inventory</h3>
            <div class="card-toolbar">
                <button type="button" onclick="window.print()" class="btn btn-icon btn-sm btn-primary"><i class="fa fa-print"></i></button>
            </div>
        </div>
        <div class="card-body">
            <div class="row" id="print-page">
                <div class="col-12">
                    <table class="table table-bordered table-hover display">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Item Name</th>
                                <th class="text-center">Item Code</th>
                                <th class="text-center">Storage</th>
                                <th class="text-center">Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $iNum = 1;
                            @endphp
                            @foreach ($qty_wh as $i => $item)
                                @if (isset($items[$item->item_id]) && isset($wh[$item->wh_id]) && $item->qty > 0)
                                    <tr>
                                        <td align="center">{{ $iNum++ }}</td>
                                        <td>
                                            <span class="font-weight-bold font-size-lg">{{ $items[$item->item_id] }}</span>
                                        </td>
                                        <td align="center">
                                            <span class="font-weight-bold font-size-lg">{{ $item_code[$item->item_id] }}</span>
                                        </td>
                                        <td>
                                            <span class="font-weight-bold font-size-lg">{{ $wh[$item->wh_id] }}</span>
                                        </td>
                                        <td align="center">
                                            <span class="font-weight-bold font-size-lg">{{ $item->qty }}</span>
                                        </td>
                                    </tr>
                                @endif
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
                pageLength : 100
            })
        })
    </script>
@endsection
