@extends('layouts.templateContract')

@section('css')
<style>
    .ct {
        font-family: "Times New Roman", Times, serif;
        font-size : 12pt;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="card card-custom gutter-b">
            <div class="card-body ct">
                <center>
                    <h3><u>PERJANJIAN KERJA WAKTU TERTENTU</u></h3>
                    <h3>No : {{ $pkwt_num }}</h3>
                </center>
                <table style="width: 100%">
                    <tr>
                        <td></td>
                        <td>Yang bertanda tangan di bawah ini : </td>
                    </tr>
                    <tr>
                        <td align="right" style="vertical-align: top">1</td>
                        <td>
                            <table style="width: 100%">
                                <tr>
                                    <td style="width: 30%">Nama</td>
                                    <td>: Eki Kurniawan</td>
                                </tr>
                                <tr>
                                    <td>Jabatan</td>
                                    <td>: HRD Manager</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Bertindak untuk dan atas nama</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <table style="width: 100%">
                                <tr>
                                    <td style="width: 30%">Perusahaan</td>
                                    <td>: {{ $comp->company_name }}</td>
                                </tr>
                                <tr>
                                    <td>Alamat</td>
                                    <td>: {{ $comp->address }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Selanjutnya disebut <b>PIHAK PERTAMA</b> atau <b>PERUSAHAAN</b></td>
                    </tr>
                    <tr>
                        <td align="right" style="vertical-align: top">2</td>
                        <td>
                            <table style="width: 100%">
                                <tr>
                                    <td style="width: 30%">Nama</td>
                                    <td>: {{ $emp->emp_name }}</td>
                                </tr>
                                <tr>
                                    <td>Jenis Kelamin</td>
                                    <td>: {{ ($jk == "M") ? "Laki - laki" : "Perempuan" }}</td>
                                </tr>
                                <tr>
                                    <td>TTL</td>
                                    <td>: {{ $tmpt_lahir }}, {{ date("d F Y", strtotime($tgl_lahir)) }}</td>
                                </tr>
                                <tr>
                                    <td>Alamat</td>
                                    <td>: {{ $address }}</td>
                                </tr>
                                <tr>
                                    <td>KTP</td>
                                    <td>: {{ $nik }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Dalam hal ini bertindak atas nama sendiri yang selanjutnya disebut <b>PIHAK KEDUA</b>.</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Pihak Pertama dan Pihak Kedua secara bersama-sama disebut sebagai "<b>Para Pihak</b>" dan masing-masing dapat disebut sebagai “<b>Pihak</b>”, dengan ini telah sepakat untuk membuat Perjanjian Kerja (“<b>Perjanjian</b>”) dengan ketentuan-ketentuan dan syarat-syarat sebagai  berikut :</td>
                    </tr>
                </table>
                <div class="content">
                    {!! $ct !!}
                </div>
                <table style="width: 100%">
                    <tr>
                        <td style="width: 50%"></td>
                        <td align="right">Jakarta, {{ date("d F Y") }}</td>
                    </tr>
                    <tr>
                        <td style="width: 50%">
                            PIHAK PERTAMA
                            <br>
                            <br>
                            <img style="max-width: 200px" src="{{ str_replace("public", "public_html", asset('media/user/signature/'.$ctid->hr_signature)) }}" alt="">
                            <br>
                            <u>Eki Kurniawan</u>
                            <br>
                            HRD Manager
                        </td>
                        <td align="right">
                            PIHAK KEDUA
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <u>{{ $emp->emp_name }}</u>
                            <br>
                            Karyawan Ybs
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="card card-custom gutter-b">
            <div class="card-header">
                <h3 class="card-title">Signature</h3>
            </div>
            <div class="card-body">
                <form method="POST" id="form-sign" action="{{ route('hrd.contract.approve') }}">
                    <div class="row">
                        {{-- <div class="col-12">
                            <h3>Detail Change</h3>
                            <table class="table table-borderless" style="width: 50%">
                                @php
                                    $js = json_decode($ctid->contents, true);
                                    $fld = $js['fld'];
                                @endphp
                                @foreach ($fld_emp as $item)
                                    @php
                                        $key = str_replace(" ", "_", strtolower($item->name));
                                    @endphp
                                    @if (isset($fld[$key]))
                                    @php
                                        $change = $fld[$key];
                                        if ($item->field_emp == "salary"){
                                            $total_salary = base64_decode($emp[$item->field_emp]) + base64_decode($emp['health']) + base64_decode($emp['transport']) + base64_decode($emp['house']) + base64_decode($emp['meal']);
                                            $fval = number_format($total_salary, 2) ;
                                        }
                                        elseif($item->field_emp == "voucher"){
                                            $fval = number_format($emp[$item->field_emp], 2) ;
                                        }
                                        else{
                                            $fval = $emp[$item->field_emp] ?? "-";
                                        }

                                        $nval = "";
                                        if($key == "position"){
                                            $nval .= $etype[$change['emp_type']];
                                            $nval .= " ".$ediv[$change['emp_div']];
                                        } else {
                                            $nval = $change;
                                        }
                                    @endphp
                                    @if ($nval != $fval)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td>:</td>
                                        <td>
                                            {{ $fval }}
                                        </td>
                                        <td align="center">
                                            <i class="fa fa-arrow-right"></i>
                                        </td>
                                        <td>
                                            {{ $nval }}
                                        </td>
                                    </tr>
                                    @endif
                                    @endif
                                @endforeach
                            </table>
                        </div> --}}
                        <div class="col-12">
                            <span class="font-weight-bold">Dengan menanda tangani Sertifikat Elektronik Anda dibawah ini, Anda menyetujui kontrak ini dan akan memiliki kekuatan hukum Republik Indonesia.</span>
                            <br>
                            <span class="text-danger">*Please read the contract before you sign</span>
                        </div>
                        <div class="col-12">
                            <div class="wrapper">
                                <canvas class="signature-pad border"></canvas>
                            </div>
                            <br>
                            <button type="button" class="btn btn-danger btn-sm" id="btn-sign-clear"><i class="fa fa-times-circle"></i>Clear</button>
                            <button type="submit" class="btn btn-primary btn-sm" name="submit" id="btn-next" value="appr"><i class="fa fa-chevron-right"></i> Next</button>
                        </div>
                        <div class="col-12 text-right">
                            @csrf
                            <input type="hidden" name="pkwt" value="{{ $pkwt_num }}">
                            <input type="hidden" name="ctid" value="{{ $ctid->id }}">
                            <input type="hidden" name="signature" id="sign-url">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('custom_script')
<script src="{{asset('theme/assets/js/signature_pad.js')}}"></script>
<script>
    $(document).ready(function(){

        var wrapper     = document.getElementById("form-sign"),
            canvas      = wrapper.querySelector("canvas"),
            signaturePad;

        signaturePad    = new SignaturePad(canvas);

        $("div.content").find('p').each(function(){
            console.log($(this))
            $(this).css("text-indent", "")
            $(this).css("margin", "")
        })

        $('#btn-sign-clear').click(function() {
            signaturePad.clear();
        });

        $("#btn-next").click(function(e){
            var isEmpty = signaturePad.isEmpty()
            var signUrl = signaturePad.toDataURL();
            $("#sign-url").val(signUrl)
            if(isEmpty){
                e.preventDefault()
                return Swal.fire("Signature Required", "Please draw your signature", 'warning')
            }
        })
    })
</script>
@endsection
