@extends('layouts.template')
@section('css')
    <style type="text/css">
        @media print {
              body * {
                visibility: hidden;
              }
              #section-to-print, #section-to-print * {
                visibility: visible;
              }
              #section-to-print {
              }
            }
    </style>
@endsection
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title"></div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" onclick="print()" class="btn btn-primary" ><i class="fa fa-print"></i>&nbsp;Print</button>
                    <a href="{{route('csr.index')}}" class="btn btn-success" ><i class="fa fa-arrow-left"></i>&nbsp;Back</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div align="center" style="border: white" id="section-to-print">
                <table border='0' width='800px'>
                    <tr>
                        <td colspan="3" align="center">
                            <img src="{{str_replace("public", "public_html", asset('images/'.Session::get('company_app_logo')))}}" alt="Company Logo" width='200px' style='margin:20px;'>
                            <br />
                        </td>
                    </tr>
                    <tr>
                        <td colspan='3' align='center'>
                            <br /><br />
                            Corporate Social Responsibility Report
                            <br />
                            {{$csr->id."/".strtoupper(Session::get('company_tag'))."-CSR/".date("m/y",strtotime($csr->date))}}
                            <br /><br />
                            {{$csr->title}}
                            <hr />
                            <br /><br />
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <p>{{strip_tags($csr->deskripsi)}}</p>
                            <hr />
                            <br />
                            <br />
                            <h4>Attachments :</h4>
                            <p>
                            {!!$pic_follow!!}
                            </p>
                            <br />
                            <hr />
                            <br /><br />
                        </td>
                    </tr>
                    <tr>
                        <td align='center'>
                            prepared by, <br />
                            <br />
                            <br />
                            ( {{$csr->author}} )
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
@endsection
