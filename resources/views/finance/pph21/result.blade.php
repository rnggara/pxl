<table class="table display table-hover table-bordered" data-page-length="100">
    <thead>
    <tr>
        <th class="text-center" rowspan="2">#</th>
        <th class="text-center" rowspan="2">Nama Pegawai</th>
        <th class="text-center" rowspan="2">Status</th>
        <th class="text-center" rowspan="2">Masa Jabatan</th>
        <th class="text-center" colspan="3">Penghasilan Net</th>
        <th class="text-center" colspan="2">PTKP</th>
        <th class="text-center" rowspan="2">PKP</th>
        <th class="text-center" colspan="2">PPH21</th>
    </tr>
    <tr>
        <th class="text-center">Penghasilan per Bulan</th>
        <th class="text-center">Tunjangan Jabatan</th>
        <th class="text-center">Penghasilan per Tahun</th>
        <th class="text-center">Pokok</th>
        <th class="text-center">Tanggungan</th>
        <th class="text-center">per Tahun</th>
        <th class="text-center">per Bulan</th>
    </tr>
    </thead>
    <tbody>
    @foreach($row as $key => $item)
        <tr>
            <td align="center">{{$key+1}}</td>
            <td align="center">{{$item['employee']['emp_name']}}</td>
            <td align="center">
                {{($item['employee']['status_marriage'] == 0) ? "TK" : "K"}}/{{($item['employee']['status_marriage'] == 0) ? 0 : $item['employee']['allowance_family']}}
            </td>
            <td align="center">{{$item['months']}}</td>
            <td align="right">{{number_format($item['salary'], 2)}}</td>
            <td align="right">{{number_format($item['salary'] - $item['tunjangan_jabatan'], 2)}}</td>
            <td align="right">{{number_format($item['netperyear'], 2)}}</td>
            <td align="right">{{number_format($item['ptkp'], 2)}}</td>
            <td align="right">{{number_format($item['ptkp_tanggungan'], 2)}}</td>
            <td align="right">{{number_format($item['pkp'], 2)}}</td>
            <td align="right">{{number_format($item['pph21peryear'], 2)}}</td>
            <td align="right">{{number_format($item['pph21permonth'], 2)}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
