@extends('layouts.template')

@section('css')
<style>
    @media print {
        body * {
            visibility: hidden;
        }

        #print-section, #print-section * {
            visibility: visible;
        }

        .view-hide {
            display: none;
        }

        #print-section {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
    }

    #print-section * {
        font-size: 20px;
    }


</style>
@endsection

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title"></h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <a href="{{ route('oletter.index') }}" class="btn btn-icon btn-success"><i class="fa fa-arrow-left"></i></a>
                    <button type="button" class="btn btn-info btn-icon" onclick="print()"><i class="fa fa-print"></i></button>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="print-section">
        <div class="col-12 mx-auto">
            <div class="card card-custom gutter-b">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <div class="d-flex">
                                <div class="symbol symbol-150 mr-3">
                                    <img src='{{str_replace("public", "public_html", asset('images/'.$comp_ba->app_logo))}}' height='30px' alt="Company Logo"/>
                                </div>
                                <div class="d-flex flex-column">
                                    <span><h1>{{ $comp_ba->company_name }}</h1></span>
                                    <span>{!! $comp_ba->address !!}</span>
                                    <span>Phone : {!! $comp_ba->phone !!}</span>
                                    <span>Email : {!! $comp_ba->email !!}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            @if (!empty($ol->approved_at))
                                <img style="width: 200px; margin-top: -10px" src="{{ str_replace("public", "public_html", asset("images/complete-blue.png")) }}"/>
                            @endif
                        </div>
                        <div class="col-12">
                            <div class="separator separator-solid separator-border-2 separator-dark"></div>
                        </div>
                        <div class="col-12 mt-5 text-center font-size-h1 font-weight-bold">
                            @php
                                $ba = "BERITA ACARA";
                                if($type == "st"){
                                    $ba = "SURAT TUGAS";
                                } elseif( $type=="ap"){
                                    $ba = "SURAT PENYELESAIAN PEKERJAAN";
                                } elseif($type == "sp"){
                                    $ba = "SURAT PELAPORAN";
                                }
                            @endphp
                            <u>{{ $ba }}</u>
                        </div>
                        <div class="col-12 text-center font-weight-bold">
                            {{ $ol->ba_num }} : {{ strtoupper($ol->title) }}
                        </div>
                        <div class="col-12 mt-5">
                            @if (!empty($type))
                                @if ($type == "st")
                                    Surat ini menjelaskan bahwa staff yang bersangkutan ditugaskan menyelesaikan hal-hal sebagai berikut :
                                @elseif($type == "ap")
                                    Bersama surat ini, menerangkan bahwa telah dilakukan penyelesaian masalah sebagai berikut :
                                @elseif($type == "sp")
                                    Surat ini menjelaskan bahwa staff ini ditugaskan untuk melakukan investigasi pelaporan dengan hasil sbb :
                                @else
                                    {!! $ol->description !!}
                                @endif
                            @endif
                        </div>
                        <div class="col-12 mt-5">
                            <table class="table table-bordered">
                                <tr>
                                    <th class="text-center" style="width: 5%">No</th>
                                    <th class="text-center">Problems</th>
                                    @if (!empty($type) && in_array($type, ["ap", "hse", "p"]))
                                        <th class="text-center">Actions</th>
                                    @endif
                                </tr>
                                @php
                                    $attachment = [];
                                    if(!empty($ol->attachments)){
                                        $attachment = json_decode($ol->attachments, true);
                                    }
                                @endphp
                                @if (count($detail) == 0)
                                    <tr>
                                        <td align="center" colspan="3">No Data Available</td>
                                    </tr>
                                @else
                                    @foreach ($detail as $i => $item)
                                        <tr>
                                            <td align="center" style="vertical-align: center">{{ $i+1 }}</td>
                                            <td style="vertical-align: top">
                                                <div class="col-12">
                                                    <div class="symbol mr-3">
                                                        <img alt="Pic" style="width: 100%; max-width: 600px; height: 100%; max-height: 600px" src="{{ str_replace("public", "public_html", asset($file_address[$item['problems_attachment']])) }}"/>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    {!! $item['problems'] !!}
                                                </div>
                                            </td>
                                            @if (!empty($type) && in_array($type, ["ap", "hse", "p"]))
                                                <td style="vertical-align: top">
                                                    <div class="col-12">
                                                        <div class="symbol mr-3">
                                                            <img alt="Pic" style="width: 100%; max-width: 600px; height: 100%; max-height: 600px" src="{{ str_replace("public", "public_html", asset($file_address[$item['actions_attachment']])) }}"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        {!! $item['actions'] !!}
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @endif
                            </table>
                        </div>
                        @if (!empty($type) && $type == "hse")
                            <div class="col-12 text-right">
                                <form action="{{ route('oletter.approve') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $ol->id }}">
                                    @if (empty($ol->approved_at))
                                        <button type="submit" name="submit" value="approve" class="btn btn-primary view-hide">Approve</button>
                                    @endif
                                </form>
                            </div>
                        @endif
                        <div class="col-12 mt-3">
                            <table style="width: 100%; table-layout: fixed">
                                <tr>
                                    @if ($type == "sp")
                                    <td align="center" style="width: 50%; vertical-align:bottom">
                                        <div class="symbol symbol-150 mr-3">
                                            @if (!empty($sign_created))
                                                <img alt="Approved By Signature" src="{{ str_replace("public", "public_html", asset("media/user/signature/".$sign_created)) }}"/>
                                            @else
                                                <br><br><br><br><br><br>.............................
                                            @endif
                                        </div>
                                        <br>
                                        <span>{{ $ol->created_by }}</span>
                                    </td>
                                    @endif
                                    @if (!empty($type) && in_array($type, ["st", "ap", "p"]))
                                        @if (!in_array($type, ["st"]))
                                            <td align="center" style="width: 50%; vertical-align:bottom">
                                                <div class="symbol symbol-150 mr-3">
                                                    @if (!empty($sign_created))
                                                        <img alt="Approved By Signature" src="{{ str_replace("public", "public_html", asset("media/user/signature/".$sign_created)) }}"/>
                                                    @else
                                                        <br><br><br><br><br><br>.............................
                                                    @endif
                                                </div>
                                                <br>
                                                <span>{{ $ol->created_by }}</span>
                                            </td>
                                        @endif
                                        <td align="center" style="width: 50%; vertical-align:bottom">
                                            <div class="symbol symbol-150 mr-3">
                                                @if (!empty($sign_fol_up))
                                                    <img alt="Approved By Signature" src="{{ str_replace("public", "public_html", asset("media/user/signature/".$sign_fol_up)) }}"/>
                                                @else
                                                    <br><br><br><br><br><br>.............................
                                                @endif
                                            </div>
                                            <br>
                                            <span>{{ $ol->problems_by }}</span>
                                        </td>
                                        @if ($type == "st")
                                        <td align="center" style="width: 50%; vertical-align:bottom">
                                            <div class="symbol symbol-150 mr-3">
                                                <br><br><br><br><br><br>.............................
                                            </div>
                                            <br>
                                            <span>Registrasi Security</span>
                                        </td>
                                        @endif
                                        @if ($type == "ap" || $type == "p")
                                        <td align="center" style="width: 50%; vertical-align:bottom">
                                            @if (empty($ol->man_approve_at))
                                                <form action="{{ route('oletter.form_update') }}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="_id" value="{{ $ol->id }}">
                                                    <input type="hidden" name="_type" value="man-approve">
                                                    <button type="submit" name="status" value="done" class="btn btn-primary mb-10 view-hide">Approve</button>
                                                </form>
                                            @else
                                                <img style="width: 400px" src="{{ str_replace("public", "public_html", asset("images/approved.png")) }}"/>
                                            @endif
                                            <br>
                                            <span>{{ $ol->ba_by }}</span>
                                        </td>
                                        @endif
                                        @if ($type == "p")
                                        <td align="center" style="width: 50%; vertical-align:bottom">
                                            <div class="symbol symbol-150 mr-3">
                                                @if (!empty($sign_approved))
                                                    <img alt="Approved By Signature" src="{{ str_replace("public", "public_html", asset("media/user/signature/".$sign_approved)) }}"/>
                                                @else
                                                    <br><br><br><br><br><br>.............................
                                                @endif
                                            </div>
                                            <br>
                                            <span>{{ $ol->approved_by }}</span>
                                        </td>
                                        @endif
                                    @endif
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        $(document).ready(function(){
            // $(".view-hide").css('display', 'none')
        })
    </script>
@endsection
