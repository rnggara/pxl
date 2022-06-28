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
                        <tr><td>PT. PUTRA SEJATI INDOMAKMUR</td><td></td><td></td><td><td></td></tr>
<tr><td>NPWP :  02.188.300.4-017.000</td><td></td><td></td><td><td></td></tr>
<tr><td></td><td></td><td></td><td><td></td></tr>
<tr><td></td><td>DESCRIPTION</td><td></td><td>CYPHER<td>REMARKS</td></tr>
<tr><td></td><td></td><td></td><td><td></td></tr>
<tr><td>Neraca</td><td></td><td></td><td><td></td></tr>
<tr><td>Per 31 Desember 2020</td><td></td><td></td><td><td></td></tr>
<tr><td></td><td></td><td></td><td><td></td></tr>
<tr><td>ASET</td><td></td><td></td><td><td></td></tr>
<tr><td></td><td></td><td></td><td><td></td></tr>
<tr><td>Aset Lancar</td><td></td><td></td><td><td></td></tr>
<tr><td></td><td>Kas dan Bank</td><td></td><td><td></td></tr>
<tr><td></td><td></td><td>Saldo Bank</td><td>Treasury<td>Saldo selurun Bank</td></tr>
<tr><td></td><td></td><td>Utilisasi yang belum Report</td><td>Utilisasi Project<td>Utilisasi yg belum Report</td></tr>
<tr><td></td><td>Piutang usaha</td><td></td><td><td></td></tr>
<tr><td></td><td></td><td>Invoice Out yg belum dibayar</td><td>Invoice Out<td>Saldo Invoice Out</td></tr>
<tr><td></td><td>Piutang lain-lain</td><td></td><td><td></td></tr>
<tr><td></td><td></td><td>Loan Employee</td><td>Loan Employee<td>Saldo Loan Employee</td></tr>
<tr><td></td><td></td><td>Pinjaman Holding</td><td>Treasuty Record<td>Manual /Pinjaman Anak Usaha</td></tr>
<tr><td></td><td></td><td>Pinjaman Pemegang Saham</td><td>Treasuty Record<td>Manual/Pinjaman Pemegang Saham</td></tr>
<tr><td></td><td></td><td>Pinjaman Lainnya</td><td>Treasuty Record<td>Manual/Pinjaman pihak ketiga</td></tr>
<tr><td></td><td>Biaya Dibayar Dimuka</td><td></td><td><td></td></tr>
<tr><td></td><td></td><td>Biaya dibayar dimuka</td><td>Manual<td>Manual/Biaya yang belum ada dokumen</td></tr>
<tr><td></td><td>Pajak Dibayar Dimuka</td><td></td><td><td></td></tr>
<tr><td></td><td></td><td>PPN In</td><td>PO/WO<td>PPN dari invoice in</td></tr>
<tr><td></td><td></td><td>Prepaid PPN</td><td>Manual<td>PPN out dikurangi PPN In</td></tr>
<tr><td></td><td></td><td>PPh Pasal 21</td><td>Utilisasi PPH 21<td>PPH 21 masa bulan berjalan (dibayarkan tgl 1-10 bulan berikutnya)</td></tr>
<tr><td></td><td></td><td>PPh Pasal 25</td><td>Utilisasi PPH 25<td>PPH 25 masa bulan berjalan (dibayarkan tgl 1- 10 bulan berikutnya)</td></tr>
<tr><td></td><td></td><td>PPh Pasal 23</td><td>Invoice Out<td>PPH 23 dari invoice Out</td></tr>
<tr><td></td><td></td><td>PPh Pasal 22</td><td>WO<td>Pajak Impor</td></tr>
<tr><td></td><td></td><td>PPh Pasal 29</td><td>Utilisasi PPH 29<td>PPH Badan</td></tr>
<tr><td></td><td></td><td>PPh Pasal 4 (2)</td><td>WO<td>PPh Final dari invoice in (sewa tempat)</td></tr>
<tr><td></td><td></td><td>PPN Impor</td><td>WO<td>Pajak Impor</td></tr>
<tr><td></td><td>Aset yang dibatasi penggunaannya</td><td></td><td><td></td></tr>
<tr><td></td><td></td><td>Deposito</td><td>Treasury<td>Saldo Deposito</td></tr>
<tr><td></td><td></td><td>Bank Garansi</td><td>Bidbond/Performabond<td>Amount Bidbond /Performabond </td></tr>
<tr><td>Aset Tidak Lancar</td><td></td><td></td><td><td></td></tr>
<tr><td></td><td>Aset Tetap - Bersih</td><td></td><td><td></td></tr>
<tr><td></td><td></td><td>Land</td><td>PO/WO<td>Investasi</td></tr>
<tr><td></td><td></td><td>Construc in Progress</td><td>PO/WO<td>Investasi</td></tr>
<tr><td></td><td></td><td>Building</td><td>PO/WO<td>Investasi</td></tr>
<tr><td></td><td></td><td>Vehicle at cost</td><td>PO/WO<td>Investasi</td></tr>
<tr><td></td><td></td><td>Furniture & Fixtures Orig Co</td><td>PO/WO<td>Investasi</td></tr>
<tr><td></td><td></td><td>Equipment at Cost</td><td>PO/WO<td>Investasi</td></tr>
<tr><td></td><td></td><td>Asset - Tax Amnesty</td><td>Manual<td></td></tr>
<tr><td></td><td></td><td>(Accumulated Depreciation Exp)</td><td>Manual<td>Akumulasi biaya Penyusutan</td></tr>
<tr><td></td><td></td><td></td><td><td></td></tr>
<tr><td></td><td></td><td></td><td><td></td></tr>
<tr><td></td><td>TOTAL AKTIVA</td><td></td><td><td></td></tr>
<tr><td></td><td></td><td></td><td><td></td></tr>
<tr><td>LIABILITAS DAN EKUITAS</td><td></td><td></td><td><td></td></tr>
<tr><td></td><td></td><td></td><td><td></td></tr>
<tr><td>Liabilitas Jangka Pendek</td><td></td><td></td><td><td></td></tr>
<tr><td></td><td>Utang Usaha</td><td></td><td><td></td></tr>
<tr><td></td><td></td><td>Invoice In yg belum dibayar</td><td>PO/WO<td>Saldo Invoice In yang belum dibayar</td></tr>
<tr><td></td><td>Uang Muka Penjualan</td><td></td><td><td></td></tr>
<tr><td></td><td></td><td>Dana masuk belum ada dokumen</td><td>Manual<td></td></tr>
<tr><td></td><td>Hutang Pajak</td><td></td><td><td></td></tr>
<tr><td></td><td></td><td>Pajak yg harus dibayarkan :</td><td><td></td></tr>
<tr><td></td><td></td><td>PPN Out</td><td>Invoice Out<td>Total PPN dari Invoice Out</td></tr>
<tr><td></td><td></td><td>PPh Pasal 21</td><td>Utilisasi PPH 21<td>Masa PPH 21 bulan sebelumnya</td></tr>
<tr><td></td><td></td><td>PPh Pasal 25</td><td>Utilisasi PPH 25<td>Masa PPH 25 bulan sebelumnya</td></tr>
<tr><td></td><td></td><td>PPh Pasal 23</td><td>WO<td>WO Jasa</td></tr>
<tr><td></td><td></td><td>PPh Pasal 22</td><td>WO<td>Masa PPH 22 bulan sebelumnya</td></tr>
<tr><td></td><td></td><td>PPh Pasal 29</td><td>Utilisasi PPH 29<td>Masa PPH 29 Tahun sebelumnya</td></tr>
<tr><td></td><td></td><td>PPh Pasal 4 (2)</td><td>WO<td>Masa PPH 4 bulan sebelumnya</td></tr>
<tr><td></td><td></td><td>PPN Impor</td><td>WO<td>Masa PPN Import bulan sebelumnya</td></tr>
<tr><td></td><td>Hutang Bank</td><td></td><td><td></td></tr>
<tr><td></td><td></td><td>Balance Loan dalam 1 thn kedepan</td><td>Loan bank<td>Total Loan Bank yg akan jatuh tempo 1 thn kedepan</td></tr>
<tr><td></td><td>Hutang Sewa Pembiayaan </td><td></td><td><td></td></tr>
<tr><td></td><td></td><td>Balance Leasing dalam 1 tahun kedepan</td><td>Leasing<td>Total Leasing yg akan jatuh tempo 1 thn kedepan</td></tr>
<tr><td></td><td>Hutang lainnya</td><td></td><td><td></td></tr>
<tr><td></td><td></td><td>Pinjaman Holding</td><td>Record Treasury<td>Pinjaman ke anak Usaha</td></tr>
<tr><td></td><td></td><td>Pinjaman Pemegang Saham</td><td>Record Treasury<td>Pinjaman ke Pemegang saham</td></tr>
<tr><td></td><td></td><td>Pinjaman Lainnya</td><td>Reord Treasury<td>Pinjaman ke Pihak ketiga</td></tr>
<tr><td></td><td></td><td></td><td><td></td></tr>
<tr><td>Liabilitas Jangka Panjang</td><td></td><td></td><td><td></td></tr>
<tr><td></td><td>Hutang Bank</td><td></td><td><td></td></tr>
<tr><td></td><td></td><td>Balance Loan diatas 1 thn kedepan</td><td>Loan bank<td>Total Loan Bank yg akan jatuh tempo lebih 1 thn kedepan</td></tr>
<tr><td></td><td>Hutang Sewa Pembiayaan </td><td></td><td><td></td></tr>
<tr><td></td><td></td><td>Balance Leasing diatas 1thn kedepan</td><td>Leasing<td>Total Leasing yg akan jatuh tempo lebih 1 thn kedepan</td></tr>
<tr><td>EKUITAS</td><td></td><td></td><td><td></td></tr>
<tr><td></td><td>Modal Saham</td><td></td><td><td></td></tr>
<tr><td></td><td></td><td>Modal saham</td><td>Record Treasury<td>Modal saham</td></tr>
<tr><td></td><td>Tambahan Modal disetor</td><td></td><td><td></td></tr>
<tr><td></td><td></td><td>Tambahan Modal</td><td>Record Treasury<td>Tambahan modal</td></tr>
<tr><td></td><td>Saldo Laba Ditahan</td><td></td><td><td></td></tr>
<tr><td></td><td></td><td>Saldo LabaTahun Lalu</td><td>Neraca Masa Sebelumnya<td></td></tr>
<tr><td></td><td>Laba Tahun Berjalan</td><td></td><td><td></td></tr>
<tr><td></td><td></td><td>Laba Rugi tahun berjalan</td><td>Laba Rugi<td>Laba Rugi tahun berjalan</td></tr>
<tr><td></td><td></td><td></td><td><td></td></tr>
<tr><td></td><td>TOTAL KEWAJIBAN DAN MODAL SAHAM</td><td></td><td><td></td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
