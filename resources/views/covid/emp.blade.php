@extends('layouts.template')

@section('css')
    <style>
        @media print {
        body * {
            visibility: hidden;
        }
        #section-to-print, #section-to-print * {
            visibility: visible;
        }
        #section-to-print {
            position: absolute;
            left: 0;
            top: 0;
        }
        }
    </style>
@endsection

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">Detail - {{ $emp->nama_emp }}</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <a href="{{ route('general.covid.setting') }}" class="btn btn-icon btn-success"><i class="fa fa-arrow-left"></i></a>
                    @if (!empty($type))
                        <button type="button" class="btn btn-icon btn-primary" onclick="print()"><i class="fa fa-print"></i></button>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body" id="section-to-print">
            <div class="row">
                <div class="col-md-7 col-sm-12">
                    <h3>Informasi Karyawan</h3>
                </div>
                <div class="col-md-5 col-sm-12 text-md-right text-sm-left">
                    <form action="{{ route('general.covid.emp_update', ["type" => "negatif", "id" => $emp->id]) }}" method="post">
                        <div class="form-group row">
                            <label class="{{ (empty($type)) ? 'col-6' : 'col-9' }} col-form-label">Dinyatakan Negatif pada tanggal</label>
                            @if (empty($type))
                                <div class="col-4">
                                    <input type="date" class="form-control" name="_negatif" value="{{ $emp->tanggal_negatif }}">
                                </div>
                                <div class="col-2">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            @else
                                <div class="col-3">
                                    <label for="" class="col-form-label">:
                                        {{ (!empty($emp->tanggal_negatif)) ? date("m/d/Y", strtotime($emp->tanggal_negatif)) : "N/A" }}
                                    </label>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
                <div class="col-12">
                    <hr>
                </div>
                <div class="col-md-6 col-sm-12">
                    <table>
                        <tr>
                            <td>Nama Karyawan</td>
                            <td> : </td>
                            <td>{{ $emp->nama_emp }}</td>
                        </tr>
                        <tr>
                            <td>Jabatan</td>
                            <td> : </td>
                            <td>{{ $emp->jabatan }}</td>
                        </tr>
                        <tr>
                            <td>Perusahaan</td>
                            <td> : </td>
                            <td>{{ $company->company_name }}</td>
                        </tr>
                        <tr>
                            <td>Tanggal Terinfeksi</td>
                            <td> : </td>
                            <td>{{ date("d F Y", strtotime($emp->tanggal_infeksi)) }}</td>
                        </tr>
                        <tr>
                            <td>Employee</td>
                            <td> : </td>
                            <td>
                                <form action="{{ route('general.covid.emp_update', ["type" => "employee", "id" => $emp->id]) }}" method="post">
                                    @csrf
                                        @if (empty($type))
                                        <div class="form-group row">
                                            <div class="col-9">
                                                <select name="_emp_id" class="form-control select2" data-placeholder="Select Employee">
                                                    <option value=""></option>
                                                    @foreach ($kar as $idemp => $item)
                                                        <option value="{{ $idemp }}" {{ ($emp->id_emp == $idemp) ? "selected" : "" }}>{{ $item }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-3">
                                                <button type="submit" class="btn btn-primary">Save</button>
                                            </div>
                                        </div>
                                        @else
                                        {{ (isset($kar[$emp->id_emp])) ? $kar[$emp->id_emp] : "N/A" }}
                                        @endif
                                </form>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-3 col-sm-12">
                    <form action="{{ route('general.covid.emp_update', ["type" => "bawaan", "id" => $emp->id]) }}" method="post">
                        <div class="row">
                            <div class="col-12">
                                <h4>Penyakit Bawaan</h4>
                                @if (empty($type))
                                <textarea name="_bawaan" class="form-control" id="" cols="30" rows="10">{!! $emp->penyakit_bawaan !!}</textarea>
                                <div class="text-right">
                                    @csrf
                                    <button type="submit" class="btn btn-primary mt-5">Save</button>
                                </div>
                                @else
                                <div class="">
                                    {!! $emp->penyakit_bawaan !!}
                                </div>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-3 col-sm-12">
                    <form action="{{ route('general.covid.emp_update', ["type" => "kondisi", "id" => $emp->id]) }}" method="post">
                        <div class="row">
                            <div class="col-12">
                                <h4>Kondisi Saat Ini</h4>
                                @if (empty($type))
                                <textarea name="_kondisi" class="form-control" id="" cols="30" rows="10">{!! $emp->kondisi !!}</textarea>
                                <div class="text-right">
                                    @csrf
                                    <button type="submit" class="btn btn-primary mt-5">Save</button>
                                </div>
                                @else
                                {!! $emp->kondisi !!}
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <hr>
            <form action="{{ route('general.covid.emp_update', ["type" => "obat", "id" => $emp->id]) }}" method="post">
                <div class="row">
                    <div class="col-12">
                        <h3>Informasi Obat</h3>
                    </div>
                    <div class="col-12">
                        <hr>
                    </div>
                    <div class="col-4">
                        <h4>Office</h4>
                        @if (empty($type))
                            <textarea name="_office" class="form-control" id="" cols="30" rows="10">{!! $emp->obat_office !!}</textarea>
                        @else
                            {!! $emp->obat_office ?? "N/A" !!}
                        @endif
                    </div>
                    <div class="col-4">
                        <h4>Dokter</h4>
                        @if (empty($type))
                            <textarea name="_dokter" class="form-control" id="" cols="30" rows="10">{!! $emp->obat_dokter !!}</textarea>
                        @else
                            {!! $emp->obat_dokter ?? "N/A" !!}
                        @endif
                    </div>
                    <div class="col-4">
                        <h4>Bawaan</h4>
                        @if (empty($type))
                            <textarea name="_bawaan" class="form-control" id="" cols="30" rows="10">{!! $emp->obat_bawaan !!}</textarea>
                        @else
                            {!! $emp->obat_bawaan ?? "N/A" !!}
                        @endif
                    </div>
                    <div class="col-12 mt-5 text-right">
                        @csrf
                        @empty($type)
                        <button type="submit" class="btn btn-primary">Save</button>
                        @endempty
                    </div>
                </div>
            </form>
            <hr>
            <div class="row">
                <div class="col-12">
                    <h3>Informasi Swab Antigen/PCR</h3>
                </div>
            </div>
            <hr>
            <form action="{{ route('general.covid.emp_update', ["type" => "test", "id" => $emp->id]) }}" method="post" enctype="multipart/form-data">
                <div class="row">
                    @for ($i = 1; $i <= 3; $i++)
                        @php
                            $field = "test_$i";
                            $test = $emp->$field;
                            $metode = null;
                            $tanggal = null;
                            $tempat = null;
                            $hasil = null;
                            $file = null;
                            if(!empty($test)){
                                $js = json_decode($test, true);
                                if(!empty($js)){
                                    $metode = $js['metode'];
                                    $tanggal = $js['tanggal'];
                                    $tempat = $js['tempat'];
                                    $hasil = $js['hasil'];
                                    if(isset($js['file'])){
                                        $file = $js['file'];
                                    }
                                }
                            }
                        @endphp
                        <div class="col-md-4 col-sm-12">
                            <h4>Test Swab Antigen/PCR ke {{ $i }}</h4>
                            <div class="row">
                                <div class="col-12">
                                    <hr>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label">Metode</label>
                                <div class="col-9">
                                    @if (empty($type))
                                    <select name="_metode[{{ $i }}]" class="form-control select2" data-placeholder="Metode">
                                        <option value=""></option>
                                        <option value="Antigen" {{ ($metode == "Antigen") ? "selected" : "" }}>Antigen</option>
                                        <option value="PCR" {{ ($metode == "PCR") ? "selected" : "" }}>PCR</option>
                                    </select>
                                    @else
                                        <label class="col-form-label">: {{ $metode ?? "N/A" }}</label>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label">Tanggal</label>
                                <div class="col-9">
                                    @if (empty($type))
                                    <input type="date" class="form-control" name="_tanggal[{{ $i }}]" value="{{ $tanggal }}">
                                    @else
                                    <label class="col-form-label">: {{ $tanggal ?? "N/A" }}</label>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label">Tempat</label>
                                <div class="col-9">
                                    @if (empty($type))
                                    <input type="text" class="form-control" name="_tempat[{{ $i }}]" placeholder="Tempat Test" value="{{ $tempat }}">
                                    @else
                                    <label class="col-form-label">: {{ $tempat ?? "N/A" }}</label>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label">Hasil</label>
                                <div class="col-9">
                                    @if (empty($type))
                                    <select name="_hasil[{{ $i }}]" class="form-control select2" data-placeholder="Hasil">
                                        <option value=""></option>
                                        <option value="-1" {{ ($hasil == -1) ? "selected" : "" }}>Negatif</option>
                                        <option value="1" {{ ($hasil == 1) ? "selected" : "" }}>Positif</option>
                                    </select>
                                    @else
                                    <label class="col-form-label">: {{ ($hasil != "") ? (($hasil == 1) ? "Positif" : "Negatif") : "N/A" }}</label>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label">File</label>
                                <div class="col-9">
                                    @if (empty($type))
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="_file[{{ $i }}]">
                                        <span class="custom-file-label">Choose file</span>
                                    </div>
                                    @if(!empty($file))
                                        <a href="{{ route('download', $file) }}" class="btn btn-icon btn-primary btn-xs mt-2"><i class="fa fa-download"></i></a>
                                    @endif
                                    @else
                                        @if (!empty($file))
                                            <label class="col-form-label">: <i class="fa fa-check text-success"></i> Attached</label>
                                        @else
                                        <label class="col-form-label">: N/A</label>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endfor
                    <div class="col-12 mt-5 text-right">
                        @empty($type)
                        @csrf
                        <button type="submit" class="btn btn-primary">Save</button>
                        @endempty
                    </div>
                </div>
            </form>
            @if (!empty($type))
                <hr>
                <div class="row">
                    <div class="col-12">
                        <h3>Informasi Vaksin</h3>
                    </div>
                </div>
                <hr>
                <div class="row">
                    @if (count($vac) == 0)
                        <div class="col-md-6 col-sm-12 mx-auto">
                            <div class="alert alert-outline-2x alert-outline-dark alert-custom">
                                <label class="alert-text text-center">No data available</label>
                            </div>
                        </div>
                    @else
                    <div class="col-md-6 col-sm-12 mx-auto">
                        @foreach ($vac as $item)
                            <div class="alert alert-custom alert-outline-2x alert-outline-dark">
                                <table>
                                    <tr>
                                        <td>Tanggal Vaksin</td>
                                        <td> : </td>
                                        <td>{{ date("m/d/Y", strtotime($item->date_time)) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Jenis Vaksin</td>
                                        <td> : </td>
                                        <td>{{ $item->vaccine_type }}</td>
                                    </tr>
                                    <tr>
                                        <td>Vaksin ke</td>
                                        <td> : </td>
                                        <td>{{ $item->vaccine_i }}</td>
                                    </tr>
                                </table>
                            </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{ asset('theme/tinymce/tinymce.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            tinymce.init({
                selector : "textarea",
                mode : "textareas",
                menubar : false,
            })

            $("select.select2").select2({
                width : "100%"
            })
        })
    </script>
@endsection
