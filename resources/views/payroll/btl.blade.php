<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
    <title>Document</title>
    <style type="text/css">
        @media print {
            body {
                font-weight: bold;
            }
            .page {page-break-after: always;}
        }
    </style>
    <?php 
    if (isset($data['emp_type'][$data['t']])) {
        $empType = $data['emp_type'][$data['t']];
    } else {
        $empType = $data['t'];
    }

    $fileName = "PAYROLL_".$empType."_".date("Y_m_d").".xls";
    if (isset($_GET['export']) &&$_GET['export'] == "on") {
        header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=".$fileName);  //File name extension was wrong
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private",false);
    }
    
     ?>
</head>
<body onload="">
    <pre>
        <div class="page">
            <table>
                <tr>
                    <td>Bank Transfer List</td>
                </tr>
                <tr>
                    <td>Employee Type: {{ucwords($empType)}}</td>
                </tr>
                <tr>
                    <td>Periode: {{ucwords($data['periode'])}}</td>
                </tr>
            </table>

            <table border="1" cellpadding="2" cellspacing="1">
                <tr>
                    <th></th>
                    <th>Bank Account</th>
                    <th>THP</th>
                    <th>Employee Name</th>
                </tr>
                <?php $sum_all = 0; ?>
                @foreach($data['data'] as $key => $value)
                    <?php /** @var TYPE_NAME $value */
                    $sum_all+= intval(str_replace(',', '', (isset($remarks[$value['emp_id']]) ? $remarks[$value['emp_id']]->thp : $value['thp'])))?>
                    <tr>
                        <td>{{$key + 1}}</td>
                        <td>{{(isset($data['bank_code'][$value['bank_code']])) ? $value['bank_account']." ".$data['bank_code'][$value['bank_code']] : $value['bank_account']}}
                        </td>
                        <td align="right">{{(isset($remarks[$value['emp_id']]) ? number_format($remarks[$value['emp_id']]->thp, 2) : $value['thp'])}}</td>
                        <td>{{$value['emp_name']}}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2" align="right">Total</td>
                    <td>{{number_format($sum_all, 2)}}</td>
                    <td></td>
                </tr>
            </table>
        </div>

        @foreach($data['bank_code'] as $bank_key => $bank)
        @foreach($data['data'] as $key => $value)
            @if($value['bank_code'] == $bank_key)
                @php
                    $countData[$bank_key][] = 1;
                @endphp
            @endif
        @endforeach
        @if(isset($countData[$bank_key]) && count($countData[$bank_key]) > 0)
        <div class="page">
            <table>
                <tr>
                    <td>Bank {{$bank}} Transfer List</td>
                </tr>
                <tr>
                    <td>Employee Type: {{ucwords($empType)}}</td>
                </tr>
                <tr>
                    <td>Periode: {{ucwords($data['periode'])}}</td>
                </tr>
            </table>

                <table border="1" cellpadding="2" cellspacing="1">
                <tr>
                    <th></th>
                    <th>Bank Account</th>
                    <th>THP</th>
                    <th>Employee Name</th>
                </tr>
                <?php $sum_all = 0; ?>
                    @foreach($data['data'] as $key => $value)
                        @if($value['bank_code'] == $bank_key)
                            <?php $sum_all+= intval(str_replace(',', '', (isset($remarks[$value['emp_id']]) ? $remarks[$value['emp_id']]->thp : $value['thp'])))?>
                            <tr>
                                <td>{{$key + 1}}</td>
                                <td>{{$value['bank_account']." ".$data['bank_code'][$value['bank_code']]}}</td>
                                <td align="right">{{(isset($remarks[$value['emp_id']]) ? number_format($remarks[$value['emp_id']]->thp, 2) : $value['thp'])}}</td>
                                <td>{{$value['emp_name']}}</td>
                            </tr>
                        @endif
                    @endforeach
                <tr>
                    <td colspan="2" align="right">Total</td>
                    <td>{{number_format($sum_all, 2)}}</td>
                    <td></td>
                </tr>
            </table>
        </div>
        @endif
        @endforeach
    </pre>
</body>
</html>
