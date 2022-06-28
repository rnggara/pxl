@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">Covid Protocol</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <a href="{{ route('general.covid.setting') }}" class="btn btn-icon btn-secondary"><i class="fa fa-cog"></i></a>
                </div>
            </div>
        </div>
    </div>
    @if (count($emp) == 0)
        <div class="row">
            <div class="col-8 mx-auto">
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 text-center">
                                <h3>There is no data</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            @foreach ($emp as $item)
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
                $lama_hari = ($mdiff * 30) + $ddiff;
                $bg_card = 'light-warning';
                if($lama_hari <= 7){
                    $bg_card = 'light-danger';
                }
            @endphp
                <div class="col-md-3 col-sm-12 mx-auto">
                    <div class="card card-custom gutter-b card-stretch bg-{{ $bg_card }} cursor-pointer bg-hover-warning-o-1" onclick="_see_more('{{ route('general.covid.emp_view', ['id' => $item->id, 'type' => 'view']) }}')">
                        <div class="card-body">
                            <h3>{{ $item->nama_emp }}</h3>
                            <span class="text-muted">{{ $item->jabatan }}</span> <br>
                            <label class="font-weight-bold">{{ $companies[$item->perusahaan] }}</label> <br>
                            <label class="font-weight-bold">{{ date("m/d/Y", strtotime($item->tanggal_infeksi)) }} ({{ $lama_terpapar }})</label> <br>
                            <label class="font-weight-bold">Kondisi saat ini : {!! $item->kondisi !!}</label> <br>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    @if (count($protocols) == 0)
        <div class="row">
            <div class="col-8 mx-auto">
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 text-center">
                                <h3>There is no protocol yet</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        @foreach ($protocols as $item)
            <div class="row">
                <div class="col-md-8 col-sm-12 mx-auto">
                    <div class="card card-custom gutter-b">
                        <div class="card-body">
                            {!! $item->content !!}
                            @if (!empty($item->content_eng))
                                @php
                                    $attach = str_replace("public", "public_html", asset($item->content_eng));
                                @endphp
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <img src="{{ $attach }}" style="width: 100%">
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

@endsection

@section('custom_script')
    <script>
        function _see_more(link){
            window.open(
                link, '_blank'
            )
            // window.location.href = link
        }

        $(document).ready(function(){

        })
    </script>
@endsection
