<table class="table table-hover table-bordered">
    <thead>
    <tr>
        <th class="text-center" colspan="3">Business Schedule Payment {{\Session::get('company_name_parent')}}</th>
    </tr>
    <tr>
        <th class="text-center">Date</th>
        <th class="text-center">Name</th>
        <th class="text-center">Amount</th>
    </tr>
    </thead>
    <tbody>
    <?php $total = 0 ?>
        @if(!empty($col))
            @foreach($col as $key => $value)
                <?php
                /** @var TYPE_NAME $value */
                foreach ($value as $i => $item){
                    $firstKey = $i;
                    break;
                }
                ?>
                <tr>
                    <td align="center" style="vertical-align: middle;" rowspan="<?= count($value) ?>"><?= date('d F Y', strtotime($key)) ?></td>
                    <td align="center"><?= $value[$firstKey]['name'] ?></td>
                    <td align="right">
                        <?php $sum=0; foreach ($value[$firstKey]['data'] as $list){
                            $sum += $list['amount'];
                        } echo number_format($sum, 2); $total+=$sum; ?>
                    </td>
                </tr>
                @foreach($value as $i => $item)
                    @if($i != $firstKey)
                        <tr>
                            <td align="center"><?= $item['name'] ?></td>
                            <td align="right">
                                <?php $sum=0; foreach ($item['data'] as $list){
                                    $sum += $list['amount'];
                                } echo number_format($sum, 2);$total+=$sum; ?>
                            </td>
                        </tr>
                    @endif
                @endforeach
            @endforeach
        @else
            <tr>
                <td align="center" colspan="3"><strong>No data available</strong></td>
            </tr>
        @endif
    </tbody>
    <tr>
        <td colspan="2" class="text-center"><strong>Totals</strong></td>
        <td align="right"><b><?= number_format($total, 2) ?></b></td>
    </tr>
</table>
