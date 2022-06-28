@extends('layouts.template')

@section('css')
    <style>
        .b-right {
            border-right: 1px solid rgb(0,0,0,0.1);
        }
    </style>
@endsection

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <ul class="nav nav-tabs nav-bold nav-tabs-line">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#protocol-tab">Covid Protocol Setting</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#emp-active">Employee Positive</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#emp-bank">Employee Bank</a>
                    </li>
                </ul>
            </div>
            <div class="card-toolbar">
                <div class="btn-group">
                    <a href="{{ route('general.covid.index') }}" class="btn btn-icon btn-success"><i class="fa fa-arrow-left"></i></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">

                </div>
            </div>
            <div class="row mt-5">
                <div class="col-12">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="protocol-tab" role="tabpanel" aria-labelledby="protocol-tab">
                            <table class="table table-bordered table-responsive-sm display">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Protocol#</th>
                                        <th class="text-center">Acknowledge</th>
                                        <th class="text-center">Approve</th>
                                        <th class="text-center">View/Approve</th>
                                        <th class="text-center"><a href="{{ route('general.covid.add') }}" class="btn btn-icon btn-xs btn-primary"><i class="fa fa-plus"></i></a></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($protocols as $i => $item)
                                        <tr>
                                            <td align="center">{{ $i + 1 }}</td>
                                            <td align="center">{{ $item->protocol_num }}</td>
                                            <td align="center">
                                                @if (empty($item->acknowledge_by))
                                                    N/A
                                                @else
                                                    {{ date("d F Y", strtotime($item->acknowledge_at)) }}
                                                    <br> {{ $item->acknowledge_by }}
                                                @endif
                                            </td>
                                            <td align="center">
                                                @if (empty($item->approved_by))
                                                    N/A
                                                @else
                                                    {{ date("d F Y", strtotime($item->approved_at)) }}
                                                    <br> {{ $item->approved_by }}
                                                @endif
                                            </td>
                                            <td align="center">
                                                <a href="{{ route('general.covid.view', $item->id) }}" class="btn btn-primary btn-icon btn-xs"><i class="fa fa-eye"></i></a>
                                            </td>
                                            <td align="center">
                                                <a href="{{ route('general.covid.delete', $item->id) }}" onclick="return confirm('Delete this protocol?')" class="btn btn-danger btn-icon btn-xs"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="emp-active" role="tabpanel" aria-labelledby="emp-active">
                            <div class="row">
                                <div class="col-12 mb-5 text-right">
                                    <div class="btn-group">
                                        <button type="button" data-toggle="modal" data-target="#modalEmp" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add</button>
                                        <a href="{{ route('general.covid.emp_export', ["type" => 'positive']) }}" class="btn btn-sm btn-success"><i class="fa fa-file-csv"></i> Export</a>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-bordered table-responsive-sm display">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Nama Karyawan</th>
                                        <th class="text-center">Jabatan</th>
                                        <th class="text-center">Perusahaan</th>
                                        <th class="text-center">Tanggal Terinfeksi</th>
                                        <th class="text-center">Lama Terpapar</th>
                                        <th class="text-center">Penyakit Bawaan</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $num = 1;
                                    @endphp
                                    @foreach ($emp as $i => $item )
                                        @if (empty($item->tanggal_negatif))
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
                                                <td class="text-center">
                                                    {{ $num++ }}
                                                </td>
                                                <td class="text-center" nowrap="nowrap">
                                                    {{ $item->nama_emp }}
                                                </td>
                                                <td class="text-center">{{ $item->jabatan }}</td>
                                                <td class="text-center">{{ $companies[$item->perusahaan] }}</td>
                                                <td class="text-center">{{ date("m/d/Y", strtotime($item->tanggal_infeksi)) }}</td>
                                                <td class="text-center">{{ $lama_terpapar }}</td>
                                                <td class="text-center">{!! $item->penyakit_bawaan !!}</td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <a href="{{ route('general.covid.emp_detail', $item->id) }}" class="btn btn-icon btn-primary mr-2 btn-xs"><i class="fa fa-eye"></i></a>
                                                        <a href="{{ route('general.covid.emp_delete', $item->id) }}" onclick="return confirm('delete?')" class="btn btn-icon btn-danger btn-xs"><i class="fa fa-trash"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="emp-bank" role="tabpanel" aria-labelledby="emp-bank">
                            <div class="row">
                                <div class="col-12 mb-5 text-right">
                                    <a href="{{ route('general.covid.emp_export', ["type" => 'bank']) }}" class="btn btn-sm btn-success"><i class="fa fa-file-csv"></i> Export</a>
                                </div>
                            </div>
                            <table class="table table-bordered table-responsive display">
                                <thead class="table-success">
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
                                </thead>
                                <tbody>
                                    @php
                                        $num = 1;
                                    @endphp
                                    @foreach ($emp as $i => $item )
                                        @if (!empty($item->tanggal_negatif))
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
                                                <td class="text-center">
                                                    {{ $num++ }}
                                                </td>
                                                <td class="text-center" nowrap="nowrap">
                                                    {{ $item->nama_emp }}
                                                    <div class="btn-group">
                                                        <a href="{{ route('general.covid.emp_view', ['id' => $item->id, 'type' => 'view']) }}" class="btn btn-icon btn-primary mr-2 btn-xs"><i class="fa fa-print"></i></a>
                                                        <a href="{{ route('general.covid.emp_detail', $item->id) }}" class="btn btn-icon btn-success mr-2 btn-xs"><i class="fa fa-pencil-alt"></i></a>
                                                        <a href="{{ route('general.covid.emp_delete', $item->id) }}" onclick="return confirm('delete?')" class="btn btn-icon btn-danger btn-xs"><i class="fa fa-trash"></i></a>
                                                    </div>
                                                </td>
                                                <td class="text-center">{{ $item->jabatan }}</td>
                                                <td class="text-center">{{ $companies[$item->perusahaan] }}</td>
                                                <td class="text-center">{{ date("m/d/Y", strtotime($item->tanggal_infeksi)) }}</td>
                                                <td class="text-center">{{ $lama_terpapar }}</td>
                                                <td class="text-center">{{ $item->tanggal_negatif }}</td>
                                                <td class="text-center">{!! $item->penyakit_bawaan !!}</td>
                                                <td >{!! $item->obat_office !!}</td>
                                                <td >{!! $item->obat_dokter !!}</td>
                                                <td >{!! $item->obat_bawaan !!}</td>
                                                <td class="text-center">{!! $item->kondisi !!}</td>
                                                @for($j = 1; $j <= 3; $j++)
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
                                                    <td class="text-center">{{ $metode[$j] }}</td>
                                                    <td class="text-center">{{ $tanggal[$j] }}</td>
                                                    <td class="text-center">{{ $tempat[$j] }}</td>
                                                    <td class="text-center">{{ $hasil[$j] }}</td>
                                                @endfor
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalEmp" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title">Add Data</h1>
                </div>
                <form action="{{ route('general.covid.emp_add') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-3 col-form-label">Nama Karyawan</label>
                            <div class="col-9">
                                <input type="text" class="form-control required" name="_nama" placeholder="Nama Karwayan" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-3 col-form-label">Jabatan</label>
                            <div class="col-9">
                                <input type="text" class="form-control required" name="_jabatan" placeholder="Jabatan" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-3 col-form-label">Perusahaan</label>
                            <div class="col-9">
                                <select name="_perusahaan" class="form-control select2 required" data-placeholder="Select Perusahaan" id="" required>
                                    <option value="">Select Perusahaan</option>
                                    @foreach ($companies as $id => $item)
                                        <option value="{{ $id }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-3 col-form-label">Tanggal Terinfeksi</label>
                            <div class="col-9">
                                <input type="date" class="form-control required" name="_tanggal" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-3 col-form-label">Penyakit Bawaan</label>
                            <div class="col-9">
                                <textarea name="_penyakit" class="form-control" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" id="btn-submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{ asset('theme/tinymce/tinymce.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            tinymce.init({
                selector : "textarea",
                menubar : false
            })
            $("table.display").DataTable()

            $("select.select2").select2({
                width : "100%"
            })

            $("#btn-submit").click(function(e){
                e.preventDefault()
                var form = $(this).parents('form')
                var inputs = form.find(".required")

                var sub = true
                for (let index = 0; index < inputs.length; index++) {
                    if(inputs[index].value == ""){
                        var nama = $(inputs[index]).attr('name').replaceAll('_', "")
                        Swal.fire("Field Required", nama + " is required", 'warning')
                        sub = false
                        break
                    }
                }

                if(sub){
                    form.submit()
                }
            })
        })
    </script>
@endsection
