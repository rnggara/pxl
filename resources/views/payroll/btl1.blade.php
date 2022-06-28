<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
    <title>Document</title>
</head>
<body onload="">
    <pre>
        <table>
            <tr>
                <td>Bank Transfer List</td>
            </tr>
            <tr>
                <td>Employee Type: {{ucwords($data['t'])}}</td>
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
                    <td>{{$value['bank_account']." ".$data['bank_code'][$value['bank_code']]}}</td>
                    <td align="right">{{(isset($remarks[$value['emp_id']]) ? number_format($remarks[$value['emp_id']]->thp, 2) : number_format($value['thp'], 2))}}</td>
                    <td>{{$value['emp_name']}}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2" align="right">Total</td>
                <td>{{number_format($sum_all, 2)}}</td>
                <td></td>
            </tr>
        </table>

        @foreach($data['bank_code'] as $bank_key => $bank)
            <table>
                <tr>
                    <td>Bank {{$bank}} Transfer List</td>
                </tr>
                <tr>
                    <td>Employee Type: {{ucwords($data['t'])}}</td>
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
                                <td align="right">{{(isset($remarks[$value['emp_id']]) ? number_format($remarks[$value['emp_id']]->thp, 2) : number_format($value['thp'], 2))}}</td>
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
        @endforeach
    </pre>
</body>
</html>
