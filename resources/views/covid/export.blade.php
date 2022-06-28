@php
    $file_name = "employee_$type.xls";
    header("Content-Type: application/octet-stream");
	header("Expires: 0");
	header("Pragma: no-cache");
	header("Content-Disposition: attachment; filename=$file_name");
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Export</title>
</head>
<style>
    table tr th, td {
        border: 1px solid black;
    }
    table {
        border-collapse: collapse;
    }
</style>
<body>
<table style="width: 100%" border="1">
    <tr>
        <th colspan="24"><h3>Export Employee {{ $type }}</h3></th>
    </tr>
    <tr>
        <th class="text-center" rowspan="2">No</th>
        <th class="text-center" rowspan="2">Nama Karyawan</th>
        <th class="text-center" rowspan="2">Jabatan</th>
        <th class="text-center" rowspan="2">Perusahaan</th>
        <th class="text-center" rowspan="2">Tanggal Terinfeksi</th>
        <th class="text-center" rowspan="2">Lama Terpapar</th>
        <th class="text-center" rowspan="2">Tanggal Negatif</th>
        <th class="text-center" rowspan="2">Penyakit Bawaan</th>
        <th class="text-center" colspan="3">Obat</th>
        <th class="text-center" rowspan="2">Kondisi Saat Ini</th>
        @for($i = 1; $i <= 3; $i++)
            <th class="text-center" colspan="4">Swab Antingen/PCR {{ $i }}</th>
        @endfor
    </tr>
    <tr>
        <th class="text-center">Office</th>
        <th class="text-center">Dokter</th>
        <th class="text-center">Bawaan</th>
        @for($i = 1; $i <= 3; $i++)
            <th class="text-center">Metode</th>
            <th class="text-center">Tanggal</th>
            <th class="text-center">Tempat</th>
            <th class="text-center">Hasil</th>
        @endfor
    </tr>
    @if (count($data) == 0)
        <tr>
            <td align="center" colspan="24">No data available</td>
        </tr>
    @else
        @php
            $num = 1;
        @endphp
        @foreach ($data as $i => $item)
            @php
                $lama_terpapar = "";
                $date2 = date_create(date("Y-m-d"));
                if(!empty($item->tanggal_negatif)){
                    $date2 = date_create($item->tanggal_negatif);
                }
                $date1 = date_create($item->tanggal_infeksi);
                $diff = date_diff($date1, $date2);
                $ydiff = $diff->format("%Y");
                $mdiff = $diff->format("%m");
                $ddiff = $diff->format("%d");
                if ($ydiff > 0) {
                    $lama_terpapar .= "$ydiff Tahun ";
                }
                $lama_terpapar .= "$mdiff Bulan $ddiff Hari";
            @endphp
            <tr>
                <td align="center">{{ $num++ }}</td>
                <td>{{ $item->nama_emp }}</td>
                <td align="center">{{ $item->jabatan }}</td>
                <td align="center">{{ $emp_comp[$item->perusahaan] }}</td>
                <td align="center">{{ date("m/d/Y", strtotime($item->tanggal_infeksi)) }}</td>
                <td align="center">{{ $lama_terpapar }}</td>
                <td align="center">{{ (empty($item->tanggal_negatif)) ? "-" : date("m/d/Y", strtotime($item->tanggal_negatif))  }}</td>
                <td>{!! $item->penyakit_bawaan !!}</td>
                <td>{!! $item->obat_office !!}</td>
                <td>{!! $item->obat_dokter !!}</td>
                <td>{!! $item->obat_bawaan !!}</td>
                <td>{!! $item->kondisi !!}</td>
                @for ($j=1; $j <= 3; $j++)
                    @php
                        $field = "test_$j";
                        $test = $item->$field;
                        $metode[$j] = "";
                        $tanggal[$j] = "";
                        $tempat[$j] = "";
                        $hasil[$j] = "";
                        if(!empty($test)){
                            $js = json_decode($test, true);
                            if(!empty($js)){
                                $metode[$j] = $js['metode'];
                                $tanggal[$j] = date("m/d/Y", strtotime($js['tanggal']));
                                $tempat[$j] = $js['tempat'];
                                $hasil[$j] = ($js['hasil'] == 1) ? "Positif" : "Negatif";
                            }
                        }
                    @endphp
                    <td align="center">{{ $metode[$j] }}</td>
                    <td align="center">{{ $tanggal[$j] }}</td>
                    <td align="center">{{ $tempat[$j] }}</td>
                    <td align="center">{{ $hasil[$j] }}</td>
                @endfor
            </tr>
        @endforeach
    @endif
</table>
</body>
</html>
