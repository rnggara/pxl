@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Item List - <span class="text-primary"><strong>{{$wh->name}}</strong></span></h3><br>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{route('wh.index')}}" class="btn btn-xs btn-success ml-3"><i class="fa fa-arrow-left"></i></a>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <table class="table display">
                <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-left">Item Name</th>
                    <th class="text-left">Category</th>
                    <th class="text-left">Type</th>
                    <th class="text-center">Code</th>
                    <th class="text-center">Stock in Storage</th>
                    <th class="text-center">Company</th>
                    <th class="text-center">Barcode</th>
                </tr>
                </thead>
                <tbody>
                @for($i=0;$i<count($itemsId); $i++)
                    <tr>
                        <td align="center">{{$i + 1}}</td>
                        <td>{{(isset($item_name[$itemsId[$i]]))?$item_name[$itemsId[$i]]['name'] : $itemsId[$i]}}</td>
                        <td>{{(isset($item_category[$itemsId[$i]])) ? $item_category[$itemsId[$i]]['cat'] : ""}}</td>
                        <td>{{(isset($item_type[$itemsId[$i]]))?(($item_type[$itemsId[$i]]['type'] == 1) ? "Consumable" : "Non Consumable") : ""}}</td>
                        <td align="center">{{(isset($item_code[$itemsId[$i]])) ? $item_code[$itemsId[$i]]['code'] : ""}}</td>
                        <td align="center">{{(isset($itemsQty[$itemsId[$i]]))?$itemsQty[$itemsId[$i]]['qty'] : ""}}&nbsp;{{(isset($item_uom[$itemsId[$i]]))?$item_uom[$itemsId[$i]]['uom'] : ""}}</td>
                        <td align="center">
                            @if(isset($item_comp_id[$itemsId[$i]]))
                            {{$company[$item_comp_id[$itemsId[$i]]['comp_id']]['comp_name']}}
                            @endif
                        </td>
                        <td align="center">
                            @if (isset($item_name[$itemsId[$i]]))
                            <a href="{{ route("barcode.generate", $itemsId[$i]) }}" class="btn btn-sm btn-primary">
                                <i class="fa fa-print"></i> Barcode
                            </a>
                            @endif
                        </td>
                    </tr>
                @endfor
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        $(document).ready(function() {
            $("table.display").DataTable({
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            })
        })
    </script>
@endsection
