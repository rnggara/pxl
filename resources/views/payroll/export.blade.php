@php

@endphp
<!DOCTYPE html>
<html>
<head>
	<title></title>
    <link href="{{asset('theme/assets/plugins/custom/datatables/datatables.bundle.css?v=7.0.5')}}" rel="stylesheet" type="text/css" />
    <script type="text/javascript">
        var tableToExcel = (function() {
            var uri = 'data:application/vnd.ms-excel;base64,'
                , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>'
                , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
                , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
            return function(table, name) {
                if (!table.nodeType) table = document.getElementById(table)
                var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
                window.location.href = uri + base64(format(template, ctx))
            }
        })()
    </script>
</head>
<body>

<table id="table-display" class="table table-bordered table-responsive" border="1">
    <thead>
    <tr>
        <th class="text-center" rowspan="2">No.</th>
        <th rowspan="2">Name/Position</th>
        <th class="text-center" rowspan="2">Salary *)</th>
        <th class="text-center" colspan="3">Overtime </th>
        <th class="text-center" colspan="3">Field</th>
        <th class="text-center" colspan="3">Warehouse</th>
        <th class="text-center" colspan="3">ODO</th>
        <th class="text-center" colspan="3">Allowance</th>
        <th class="text-center" rowspan="2">Voucher</th>
        <th class="text-center" rowspan="2">Total<br>Salary</th>
        <th class="text-center" colspan="6">Deduction</th>
        <th class="text-center" rowspan="2">Bonus</th>
        <th class="text-center" rowspan="2">THR</th>
        <th class="text-center" rowspan="2">PPH21</th>
        <th class="text-center" rowspan="2">Proportional</th>
        <th class="text-center" rowspan="2">THP</th>
    </tr>
    <tr>
        <th class="text-center">Rate</th>
        <th class="text-center">Hours</th>
        <th class="text-center">Total</th>
        <th class="text-center">Rate</th>
        <th class="text-center">Days</th>
        <th class="text-center">Total</th>
        <th class="text-center">Rate</th>
        <th class="text-center">Days</th>
        <th class="text-center">Total</th>
        <th class="text-center">Rate</th>
        <th class="text-center">Days</th>
        <th class="text-center">Total</th>
        <th class="text-center">BPJS-TK</th>
        <th class="text-center">BPJS-Kes</th>
        <th class="text-center">JSHK</th>
        <th class="text-center">Sunction</th>
        <th class="text-center">Absence</th>
        <th class="text-center">Loan</th>
        <th class="text-center">BPJS-TK</th>
        <th class="text-center">BPJS-Kes</th>
        <th class="text-center">JSHK</th>
    </tr>
    </thead>
    <tbody></tbody>
    <tfoot>
    <tr>
        <td colspan="2" align="right">
            <b>Total :</b>
        </td>
        <td align="right"></td>
        <td align="right"></td>
        <td align="right"></td>
        <td align="right"></td>
        <td align="right"></td>
        <td align="right"></td>
        <td align="right"></td>
        <td align="right"></td>
        <td align="right"></td>
        <td align="right"></td>
        <td align="right"></td>
        <td align="right"></td>
        <td align="right"></td>
        <td align="right"></td>
        <td align="right"></td>
        <td align="right"></td>
        <td align="right"></td>
        <td align="right"></td>
        <td align="right"></td>
        <td align="right"></td>
        <td align="right"></td>
        <td align="right"></td>
        <td align="right"></td>
        <td align="right"></td>
        <td align="right"></td>
        <td align="right"></td>
        <td align="right"></td>
        <td align="right"></td>
        <td align="right"></td>
    </tr>
    <tr>
        <td colspan="31">
            <div class='alert alert-warning'>
                Note:
                <ul>
                    <li><sup><small>*)</small></sup>Salary column is sum of basic salary basic salary + health allowance + transport allowance + meal allowance + house allowance + position allowance. </li>
                    <li>The calculation of basic salary is started from the 16th to the 15th of the following month</li>
                    <li>ODO (One Day Off) On at Off time</li>
                    <li>The amount of overtime is the result of rounding up per hour</li>
                </ul>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="27">
            <div id="table-signature"></div>
        </td>
    </tr>
    </tfoot>
</table>
<script src="{{asset('theme/assets/plugins/global/plugins.bundle.js?v=7.0.5')}}"></script>
<script src="{{asset('theme/assets/plugins/custom/prismjs/prismjs.bundle.js?v=7.0.5')}}"></script>
<script src="{{asset('theme/assets/js/scripts.bundle.js?v=7.0.5')}}"></script>
<script src="{{asset('theme/assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.5')}}"></script>
<script src="{{asset('theme/assets/js/pages/crud/datatables/data-sources/html.js?v=7.0.5')}}"></script>
<script>
    $(document).ready(function(){

        $("#table-display").hide()

        var t = '{{$type}}'
        var m = '{{$month}}'
        var y = '{{$years}}'
        $.ajax({
            url: "{{URL::route('payroll.show')}}",
            type: 'POST',
            dataType: 'json',
            cache: false,
            data: {
                'type': t,
                'month': m,
                'years': y,
                '_token': '{{csrf_token()}}',
            },
            success: function(response){
                console.log(response.data)
                if (response.error == 0) {
                    var datafoot = response.footer;
                    $('#table-display').DataTable().clear();
                    $('#table-display').DataTable().destroy();
                    $("#table-display").DataTable({
                        "data" : response.data,
                        "searching": false,
                        "lengthChange": false,
                        "ordering": false,
                        "aaSorting": [],
                        "paging":   false,
                        "info":     false,
                        'footerCallback' : function(tfoot, data, start, end, display) {
                            var resp = datafoot
                            console.log(datafoot)
                            if (resp) {
                                var td = $(tfoot).find('td')
                                td.eq(1).html(currencyFormat(resp.sum_salary))
                                td.eq(4).html(currencyFormat(resp.sum_ovt))
                                td.eq(7).html(currencyFormat(resp.sum_fld))
                                td.eq(10).html(currencyFormat(resp.sum_wh))
                                td.eq(13).html(currencyFormat(resp.sum_odo))
                                td.eq(14).html(currencyFormat(resp.sum_tk))
                                td.eq(15).html(currencyFormat(resp.sum_ks))
                                td.eq(16).html(currencyFormat(resp.sum_jshk))
                                td.eq(17).html(currencyFormat(resp.sum_voucher))
                                td.eq(18).html(currencyFormat(resp.sum_tot_salary))
                                td.eq(19).html(currencyFormat(resp.sum_sanction))
                                td.eq(21).html(currencyFormat(resp.sum_loan))
                                td.eq(22).html(currencyFormat(resp.sum_ded_tk))
                                td.eq(23).html(currencyFormat(resp.sum_ded_ks))
                                td.eq(24).html(currencyFormat(resp.sum_ded_jshk))
                                td.eq(25).html(currencyFormat(resp.sum_bonus))
                                td.eq(26).html(currencyFormat(resp.sum_thr))
                                td.eq(27).html(currencyFormat(resp.sum_pph21))
                                td.eq(28).html(currencyFormat(resp.sum_prop))
                                td.eq(29).html(currencyFormat(resp.sum_thp))
                            }
                        },
                        "columnDefs": [{
                            targets : [2, 3, 5, 6, 8, 9, 11, 12, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23,24,25,26,27,28,29, 30],
                            className: 'text-right'
                        }]
                    })
                    $("#table-signature").html(response.table_signature)
                    tableToExcel('table-display', 'W3C Example Table')
                } else {
                    $('#table-display').DataTable().clear();
                    $('#table-display').DataTable().destroy();
                    $("#table-display").DataTable()
                }
            }
        })
    })
    function currencyFormat(num) {
        return num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
    }
</script>
</body>
</html>
