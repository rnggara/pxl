<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PKWT - {{ $emp->emp_name }}</title>
</head>
<style>
    .content > p {
        text-indent: 0pt;
    }
</style>
<body>
    <table style="width: 100%">
        <tr>
            <th align="center">
                <h3><u>PERJANJIAN KERJA WAKTU TERTENTU</u></h3>
            </th>
        </tr>
        <tr>
            <th align="center">
                <h3>No : {{ $ctid->pkwt_num }}</h3>
            </th>
        </tr>
    </table>
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
                        <td>: {{ $ctid->pihak_pertama }}</td>
                    </tr>
                    <tr>
                        <td>Jabatan</td>
                        <td>: {{ $ctid->jabatan }}</td>
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
                <img style="max-width: 200px" src="{{ str_replace("public", "public_html", asset("media/user/signature/$ctid->hr_signature")) }}" alt="">
                <br>
                <u>{{ $ctid->pihak_pertama }}</u>
                <br>
                {{ $ctid->jabatan }}
            </td>
            <td align="right">
                PIHAK KEDUA
                <br>
                <br>
                <img style="max-width: 200px" src="{{ str_replace("public", "public_html", asset("media/user/signature/$ctid->emp_signature")) }}" alt="">
                <br>
                <br>
                <u>{{ $emp->emp_name }}</u>
                <br>
                Karyawan Ybs
            </td>
        </tr>
    </table>
    <script>var HOST_URL = "https://keenthemes.com/metronic/tools/preview";</script>
    <!--begin::Global Config(global config for global JS scripts)-->
    <script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1200 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#6993FF", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#F3F6F9", "dark": "#212121" }, "light": { "white": "#ffffff", "primary": "#E1E9FF", "secondary": "#ECF0F3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#212121", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#ECF0F3", "gray-300": "#E5EAEE", "gray-400": "#D6D6E0", "gray-500": "#B5B5C3", "gray-600": "#80808F", "gray-700": "#464E5F", "gray-800": "#1B283F", "gray-900": "#212121" } }, "font-family": "Poppins" };</script>
    <script src="{{asset('theme/assets/plugins/global/plugins.bundle.js?v=7.0.5')}}"></script>
    <script src="{{asset('theme/assets/plugins/custom/prismjs/prismjs.bundle.js?v=7.0.5')}}"></script>
    <script src="{{asset('theme/assets/js/scripts.bundle.js?v=7.0.5')}}"></script>
    <script>
        $(document).ready(function(){
            $(".content").find("p").each(function(){
                $(this).css('text-indent' ,"")
                $(this).css("margin", "")
            })
        })
    </script>
</body>
</html>
