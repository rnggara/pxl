<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
    <style>
        body {
            font-family: monospace;
        }
        .table {
            border: 1px solid black;
        }

        .table {
            border-collapse: collapse;
            padding: 15px;
        }

        .table-text {
            font-size: 12px;
        }
    </style>
</head>
<body>
    @php
        function _child($list_child, $item, $level, $coa_code, $data_his, $sum_type){
            $tr = "";
            $level += 1;
            $strip = "";
            for ($i=0; $i < $level; $i++) {
                $strip .= "&nbsp;";
                if (($level - $i) == 1) {
                    $strip .= ">";
                }
            }
            if(isset($list_child[$item->id])){
                foreach($list_child[$item->id] as $child){
                    $btn = "";
                    if(empty($child->tc)){
                        $btn .= '<button type="button" data-label="'.$child->description.'" class="btn btn-icon btn-xs btn-outline-primary ml-2" onclick="_modal(this, \''.$child->type.'\', '.$child->id.')"><i class="fa fa-plus"></i></button>';
                    }
                    if (!isset($list_child[$child->id])) {
                        $btn .= '<button type="button" data-label="'.$child->description.'" class="btn btn-icon btn-xs btn-outline-dark ml-2" onclick="_edit_modal('.$child->id.')"><i class="fa fa-cog"></i></button>';
                        $btn .= '<button type="button" class="btn btn-icon btn-xs btn-outline-danger ml-2" onclick="_delete('.$child->id.')"><i class="fa fa-trash"></i></button>';
                    }

                    $tcSpan = "";
                    $align = "";

                    $sum = 0;

                    if (!empty($child->tc)) {
                        $tc = json_decode($child->tc);
                        if(is_array($tc) && !empty($tc)){
                            foreach ($tc as $tcVal) {
                                if(isset($coa_code[$tcVal])){
                                    $c_code = rtrim($coa_code[$tcVal], 0);
                                    if (isset($data_his[$c_code])) {
                                        $sum += array_sum($data_his[$c_code]);
                                        $sum_type += array_sum($data_his[$c_code]);
                                    }
                                }
                            }
                            $tcSpan .= "IDR <span class='tc-sum'>".number_format($sum, 2)."</span>";
                            $align = "right";
                        }
                    }

                    $desc = "<div class='row'><div class='col-8'>$strip $child->description</div><div class='col-4 text-right'></div></div>";
                    $tr .= "<tr><td>$desc</td><td align='$align'>$tcSpan</td></tr>";
                    if (isset($list_child[$child->id])) {
                        $tr .= _child($list_child, $child, $level, $coa_code, $data_his, $sum_type)['view'];
                        $sum_type = _child($list_child, $child, $level, $coa_code, $data_his, $sum_type)['sum'];
                    }
                }
            }

            $data = array(
                'view' => $tr,
                'sum' => $sum_type
            );

            return $data;
        }
    @endphp
    <table style="width: 100%" class="table">
        @php
            $sum_sub_total = 0;
            $sum_kiri = 0;
            $sum_kanan = 0;
        @endphp
        <tr>
            <th colspan="2" class="table">
                Balance Sheet
                Period: {{ date("d F Y", strtotime($from)) }} - {{ date("d F Y", strtotime($to)) }}
            </th>
        </tr>
        <tr>
            <th style="width: 50%" class="table">Assets</th>
            <th class="table">Liabilitiy</th>
        </tr>
        <tr>
            <td class="table" style="vertical-align: top">
                @php
                    $sum_asset = 0;
                @endphp
                    <table style="width: 100%">
                        @if (isset($detail['asset']))
                            @foreach ($detail['asset'] as $item)
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div class="col-8">
                                                <span class="font-weight-bold" style="font-weight: bold">- {{ $item->description }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    @php
                                        $span = "";
                                        $align ="";
                                    @endphp
                                    @if (!empty($item->tc))
                                            @php
                                                $tc = json_decode($item->tc, true);
                                                $sum = 0;
                                                if (is_array($tc) && !empty($tc)) {
                                                    foreach ($tc as $key => $value) {
                                                        if (isset($coa_code[$value])) {
                                                            $c_code = rtrim($coa_code[$value], 0);
                                                            if (isset($data_his[$c_code])) {
                                                                $sum += array_sum($data_his[$c_code]);
                                                                $sum_asset += array_sum($data_his[$c_code]);
                                                            }
                                                        }
                                                    }
                                                    $span .= "IDR <span class='tc-sum'>".number_format($sum, 2)."</span>";
                                                    $align = "right";
                                                }
                                            @endphp
                                        @endif
                                    <td align="{{ $align }}">
                                        {!! $span !!}
                                    </td>
                                </tr>
                                {!! _child($detail_child, $item, 0, $coa_code, $data_his, $sum_asset)['view'] !!}
                                @php
                                    $sum_asset += _child($detail_child, $item, 0, $coa_code, $data_his, $sum_asset)['sum'];
                                @endphp
                            @endforeach
                        @endif
                        <tr>
                            <td>Total</td>
                            <td align="right">IDR {{ number_format($sum_asset, 2) }}</td>
                        </tr>
                        @php
                            $sum_sub_total += $sum_asset;
                            $sum_kiri += $sum_asset;
                        @endphp
                    </table>
            </td>
            <td class="table" style="vertical-align: top">
                @php
                    $sum_lia = 0;
                @endphp
                    <table style="width: 100%">
                        @if (isset($detail['liability']))
                            @foreach ($detail['liability'] as $item)
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div class="col-8">
                                                <span class="font-weight-bold">{{ $item->description }}</span>
                                            </div>
                                            <div class="col-4 text-right">
                                                <div class="button-group">
                                                    @if (empty($item->tc))
                                                        <button type="button" data-label="{{ $item->description }}" class="btn btn-icon btn-xs btn-outline-primary ml-2" onclick="_modal(this, 'asset', {{ $item->id }})"><i class="fa fa-plus"></i></button>
                                                        @if (!isset($detail_child[$item->id]))
                                                            <button type="button" data-label="{{ $item->description }}" class="btn btn-icon btn-xs btn-outline-dark ml-2" onclick="_modal(this, 'asset', {{ $item->id }})"><i class="fa fa-cog"></i></button>
                                                            <button type="button" data-label="{{ $item->description }}" class="btn btn-icon btn-xs btn-outline-danger ml-2" onclick="_delete({{ $item->id }})"><i class="fa fa-trash"></i></button>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    @php
                                        $span = "";
                                        $align ="";
                                    @endphp
                                    @if (!empty($item->tc))
                                            @php
                                                $tc = json_decode($item->tc, true);
                                                $sum = 0;
                                                if (is_array($tc) && !empty($tc)) {
                                                    foreach ($tc as $key => $value) {
                                                        if (isset($coa_code[$value])) {
                                                            $c_code = rtrim($coa_code[$value], 0);
                                                            if (isset($data_his[$c_code])) {
                                                                $sum += array_sum($data_his[$c_code]);
                                                                $sum_lia += array_sum($data_his[$c_code]);
                                                            }
                                                        }
                                                    }
                                                    $span .= "IDR <span class='tc-sum'>".number_format($sum, 2)."</span>";
                                                    $align = "right";
                                                }
                                            @endphp
                                        @endif
                                    <td align="{{ $align }}">
                                        {!! $span !!}
                                    </td>
                                </tr>
                                {!! _child($detail_child, $item, 0, $coa_code, $data_his, $sum_lia)['view'] !!}
                                @php
                                    $sum_asset += _child($detail_child, $item, 0, $coa_code, $data_his, $sum_lia)['sum'];
                                @endphp
                            @endforeach
                            <tr>
                                <td>Total</td>
                                <td align="right">IDR {{ number_format($sum_lia, 2) }}</td>
                            </tr>
                        @endif
                    </table>
            </td>
        </tr>
        <tr>
            <th class="table">Sub Total</th>
            <th class="table">Equity</th>
        </tr>
        <tr>
            <td align="right" class="table">
                <table style="width: 100%">
                    <tr>
                        <td>Total</td>
                        <td align="right">IDR {{ number_format($sum_sub_total, 2) }}</td>
                    </tr>
                </table>
            </td>
            <td class="table" style="vertical-align: top">
                @php
                    $sum_equi = 0;
                @endphp
                    <table style="width: 100%">
                        @if (isset($detail['equity']))
                            @foreach ($detail['equity'] as $item)
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div class="col-8">
                                                <span class="font-weight-bold">{{ $item->description }}</span>
                                            </div>
                                            <div class="col-4 text-right">
                                                <div class="button-group">
                                                    @if (empty($item->tc))
                                                        <button type="button" data-label="{{ $item->description }}" class="btn btn-icon btn-xs btn-outline-primary ml-2" onclick="_modal(this, 'asset', {{ $item->id }})"><i class="fa fa-plus"></i></button>
                                                        @if (!isset($detail_child[$item->id]))
                                                            <button type="button" data-label="{{ $item->description }}" class="btn btn-icon btn-xs btn-outline-dark ml-2" onclick="_modal(this, 'asset', {{ $item->id }})"><i class="fa fa-cog"></i></button>
                                                            <button type="button" data-label="{{ $item->description }}" class="btn btn-icon btn-xs btn-outline-danger ml-2" onclick="_delete({{ $item->id }})"><i class="fa fa-trash"></i></button>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    @php
                                        $span = "";
                                        $align ="";
                                    @endphp
                                    @if (!empty($item->tc))
                                            @php
                                                $tc = json_decode($item->tc, true);
                                                $sum = 0;
                                                if (is_array($tc) && !empty($tc)) {
                                                    foreach ($tc as $key => $value) {
                                                        if (isset($coa_code[$value])) {
                                                            $c_code = rtrim($coa_code[$value], 0);
                                                            if (isset($data_his[$c_code])) {
                                                                $sum += array_sum($data_his[$c_code]);
                                                            }
                                                        }
                                                    }
                                                    $span .= "IDR <span class='tc-sum'>".number_format($sum, 2)."</span>";
                                                    $align = "right";
                                                }
                                            @endphp
                                        @endif
                                    <td align="{{ $align }}">
                                        {!! $span !!}
                                    </td>
                                </tr>
                                {!! _child($detail_child, $item, 0, $coa_code, $data_his, $sum_equi)['view'] !!}
                                @php
                                    $sum_asset += _child($detail_child, $item, 0, $coa_code, $data_his, $sum_equi)['sum'];
                                @endphp
                            @endforeach
                        @endif
                        <tr>
                            <td>Total</td>
                            <td align="right">IDR {{ number_format($sum_equi, 2) }}</td>
                        </tr>
                    </table>
            </td>
        </tr>
        @php
            $sum_kanan = $sum_equi + $sum_lia;
        @endphp
        <tr>
            <td class="table">
                <table style="width: 100%">
                    <tr>
                        <td><h3>Total</h3></td>
                        <td align="right">
                            IDR {{ number_format($sum_kiri, 2) }}
                        </td>
                    </tr>
                </table>
            </td>
            <td class="table">
                <table style="width: 100%">
                    <tr>
                        <td><h3>Total</h3></td>
                        <td align="right">
                            IDR {{ number_format($sum_kanan, 2) }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
