<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        #detail td, th {
            border: 1px solid black;
        }
        #detail{
            padding: 10px;
            border-collapse: collapse;
        }
        #detail td{
            padding: 5px
        }

        #detail th{
            padding: 10px;
        }
    </style>
</head>
<body>
    <table width="100%">
        <tr>
            <td width="100px">
                <img src="{{str_replace("public", "public_html", asset('images/'.\Session::get('company_app_logo')))}}" width="100px" alt="">
            </td>
            <td>
                {{\Session::get('company_name_parent')}}
            </td>
        </tr>
    </table>
    <table style="width: 100%" id="detail">
        <tr>
            <th colspan="2">Severance</th>
        </tr>
        <tr>
            <td>Employee Name</td>
            <td>{{$emp->emp_name}}</td>
        </tr>
        <tr>
            <td>Employee In Date</td>
            <td>{{date('d F Y', strtotime($severance->act_date))}}</td>
        </tr>
        <tr>
            <td>Severance Date</td>
            <td>{{date('d F Y', strtotime($severance->sev_date))}}</td>
        </tr>
        <tr>
            <td>Year(s) of service</td>
            <td></td>
        </tr>
        <tr>
            <td>Severance Reason</td>
            <td>{{$reason->reason}}</td>
        </tr>
        <tr>
            <th colspan="2">Result</th>
        </tr>
        <tr>
            <td>Salary</td>
            <td>{{number_format(base64_decode($emp->salary))}}</td>
        </tr>
        <tr>
            <td>Severance</td>
            <td>{{number_format($severance->sev_amount)}}</td>
            <?php
            $sum_total = 0;
            /** @var TYPE_NAME $severance */
            $sum_total += $severance->sev_amount;
            ?>
        </tr>
        <tr>
            <td>Appreciation</td>
            <td>{{number_format($severance->app_amount)}}</td>
            <?php
            $sum_total += $severance->app_amount;
            ?>
        </tr>
        <tr>
            <th colspan="2">Additional</th>
        </tr>
        <tr>
            <td>Outstanding Salary</td>
            <td>{{number_format($severance->add_out_salary)}}</td>
            <?php
            $sum_total += $severance->add_out_salary;
            ?>
        </tr>
        <tr>
            <td>THR</td>
            <td>{{number_format($severance->add_thr)}}</td>
            <?php
            $sum_total += $severance->add_thr;
            ?>
        </tr>
        <tr>
            <td>Bonus</td>
            <td>{{number_format($severance->add_bonus)}}</td>
            <?php
            $sum_total += $severance->add_bonus;
            ?>
        </tr>
        <tr>
            <td>Others</td>
            <td>{{number_format($severance->add_others)}}</td>
            <?php
            $sum_total += $severance->add_others;
            ?>
        </tr>
        <tr>
            <th colspan="2">Deduction</th>
        </tr>
        <tr>
            <td>Loan</td>
            <td>{{number_format($severance->deduc_loan)}}</td>
            <?php
            $sum_total += $severance->deduc_loan;
            ?>
        </tr>
        <tr>
            <td>Union Fee</td>
            <td>{{number_format($severance->deduc_union)}}</td>
            <?php
            $sum_total += $severance->deduc_union;
            ?>
        </tr>
        <tr>
            <td>Others</td>
            <td>{{number_format($severance->deduc_othres)}}</td>
            <?php
            $sum_total += $severance->deduc_othres;
            ?>
        </tr>
        <tr>
            <th colspan="2">Total Severance</th>
        </tr>
        <tr>
            <td>Total</td>
            <td>{{number_format($sum_total, 2)}}</td>
        </tr>
    </table>
</body>
</html>
