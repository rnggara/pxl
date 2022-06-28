@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">Balance Sheet</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 text-center">
                    <h3>LAPORAN LABA DAN RUGI <br> UNTUK TAHUN YANG BERAKHIR 31 DESEMBER 2020</h3>
                </div>
                <div class="col-12">
                    <table class="table table-bordered table-hover">
                        <tr>
                            <th class="text-center" style="width: 10%">#</th>
                            <th>NAME</th>
                            <th>DESCRIPTION</th>
                            <th>AMOUNT</th>
                        </tr>
                        <tr>
                            <td align="center"><b>A</b></td>
                            <td colspan="3"><b>Sales</b></td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>Pendapatan dalam 1 thn berjalan 1/1-31/12</td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td align="center"><b>B</b></td>
                            <td colspan="3"><b>Cost of Sales</b></td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>Cost Project</td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td align="center"><b>C</b></td>
                            <td colspan="2"><b>Gross Profit</b></td>
                            <td align="right">IDR</td>
                        </tr>
                        <tr>
                            <td colspan="5"></td>
                        </tr>
                        <tr>
                            <td align="center"><b>D</b></td>
                            <td colspan="3"><b>Operating Expense</b></td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>BY GAJI</td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>PPE&HSE</td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>BY ASURANSI KESEHATAN KARYAWAN</td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>BY PELATIHAN</td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>BY PERLENGKAPAN KANTOR</td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>BY FOTOKOPI</td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>BY POS, KURIR, DAN MATERAI</td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>BY TELEPON & FAX</td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>BY INTERNET</td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>BY LISTRIK</td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>BY DAPUR</td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>BY KENDARAAN</td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>BY LEGAL</td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>BY ADMINISTRASI BANK</td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>BY PEMELIHARAAN KOMPUTER</td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>BY PEMELIHARAAN KENDARAAN</td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>BY PEMELIHARAAN GEDUNG</td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>BY ANGKUTAN</td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>BY KONSULTAN</td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>PBB</td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>BY IKLAN</td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>BY SOSIAL</td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>BY PENYUSUTAN MESIN</td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>BY PENYUSUTAN PERALATAN KANTOR</td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>BY PENYUSUTAN KENDARAAN</td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>BY PENYUSUTAN GEDUNG</td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>BY SEWA WAREHOUSE/MESS</td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>BY KEAMANAN</td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>BY PAJAK LAIN-LAIN</td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>BY BUNGA</td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td align="center"><b>E</b></td>
                            <td colspan="2"><b>Total Operating Expense</b></td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td align="center"><b>F</b></td>
                            <td colspan="2"><b>Operating Income</b></td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td align="center"><b>G</b></td>
                            <td colspan="3"><b>Other Income</b></td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>Pendapatan Bunga</td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>Laba Selisih Kurs</td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>Other Income</td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td align="center"><b>H</b></td>
                            <td colspan="2"><b>Total Other Income</b></td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td align="center"><b>I</b></td>
                            <td colspan="3"><b>Other Expense</b></td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>Rugi Selisih Kurs</td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>Beban Administrasi Bank</td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>Other Expense</td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td align="center"><b>J</b></td>
                            <td colspan="2"><b>Total Biaya Lain-lain</b></td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr>
                            <td align="center"><b>K</b></td>
                            <td colspan="2"><b>Total Other (Income)/Expenses</b></td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr class="bg-secondary">
                            <td align="center"><b>L</b></td>
                            <td colspan="2"><b>LABA SEBELUM PAJAK</b></td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr class="bg-secondary">
                            <td align="center"><b>M</b></td>
                            <td colspan="2"><b>PAJAK PENGHASILAN</b></td>
                            <td align="right"> IDR </td>
                        </tr>
                        <tr class="bg-secondary">
                            <td align="center"><b>N</b></td>
                            <td colspan="2"><b>LABA SETELAH PAJAK</b></td>
                            <td align="right"> IDR </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
