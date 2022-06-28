@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">Transaction Summary</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <button type="button" data-toggle="modal" data-target="#modalUpdate" class="btn btn-primary btn-sm">Update All</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered table-hover display">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Transaction</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Period</th>
                                <th class="text-center" style="width: 10%">Last Update</th>
                                <th class="text-center">File Excel</th>
                                <th class="text-center" style="width: 10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($coa as $i => $item)
                            @php
                                $amount = 0;
                                $last_date = null;
                                if (isset($coa_his[$item->code])) {
                                    $last_date = $coa_his[$item->code]['date'];
                                    $amount = array_sum($coa_his[$item->code]['amount']);
                                }
                                $jsSrc = json_decode($item->source);
                                $id_src = $jsSrc[0];
                                $period_start = "N/A";
                                $period_end = "N/A";
                                if(isset($ts[$id_src])){
                                    $period_start = date("F Y", strtotime($ts[$id_src]->start_date));
                                    $period_end = date("F Y", strtotime($ts[$id_src]->end_date));
                                    $file = $ts[$id_src]->_file;
                                }
                            @endphp
                                <tr>
                                    <td align="center">{{ $i+1 }}</td>
                                    <td>{{ $src_desc[$id_src] }}</td>
                                    <td align="right">
                                        Rp. {{ number_format($amount, 2) }}
                                    </td>
                                    <td align="center">
                                        {{ (!empty($last_date)) ?  $last_date : "N/A" }}
                                    </td>
                                    <td align="center" class="text-nowrap">
                                        @if ($period_start != "N/A")
                                            {{ $period_start }} - {{ $period_end }}
                                        @else
                                            {{ $period_start }}
                                        @endif
                                    </td>
                                    <td align="center">
                                        @if (!empty($file))
                                            <a href="{{ str_replace("public", "public_html", asset('media/summary/'.$file)) }}" downlaod class="btn btn-icon btn-xs btn-success"><i class="fa fa-file-csv"></i></a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td align="center">
                                        <button type="button" onclick="_update_modal('{{ $src_name[$id_src] }}')" class="btn btn-primary btn-sm">Update</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalUpdate" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title">Update Transaction</h1>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-times font-size-h5"></i>
                    </button>
                </div>
                <form action="{{ route('fts.update') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-9">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Start Date</label>
                                            <input type="date" class="form-control" value="{{ date("Y") }}-01-01" name="start_date">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="col-form-label">End Date</label>
                                            <input type="date" class="form-control" value="{{ date("Y") }}-12-31" name="end_date">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Project Sales <button type="button" onclick="_select_all(this)" class="btn label label-inline label-primary bg-hover-primary-o-4">Select All</button></label>
                                            <select name="sales[]" multiple class="form-control select2">
                                                @foreach ($project_sales as $item)
                                                    <option value="{{ $item->id }}">{{ $item->prj_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Project Cost <button type="button" onclick="_select_all(this)" class="btn label label-inline label-primary bg-hover-primary-o-4">Select All</button></label>
                                            <select name="cost[]" multiple class="form-control select2">
                                                @foreach ($project_cost as $item)
                                                    <option value="{{ $item->id }}">{{ $item->prj_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="row mt-11">
                                    <div class="col-12 text-right">
                                        <input type="hidden" id="src_name" name="name">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        function _update_modal(name){
            $("#modalUpdate").modal('show')
            $("#src_name").show()
            $("#src_name").val(name)
        }

        function _select_all(btn){
            var label = $(btn).parent()
            var parent = label.parent()
            var select = parent.find('select.select2')
            var options = select.find('option')
            // console.log(options)
            var id = []
            options.each(function(){
                id.push($(this).val())
            })

            select.val(id).trigger('change')
        }
        $(document).ready(function(){
            $('#modalUpdate').on('hidden.bs.modal', function () {
                $("#src_name").val('')
                var select = $("#modalUpdate").find('select')
                select.each(function(){
                    $(this).val(null).trigger('change')
                })
            })
            $("#src_name").hide()
            @if (\Session::get('msg'))
                Swal.fire('Data updated', 'Successfully update {{ \Session::get('msg') }} data', 'success')
            @endif

            $("select.select2").select2({
                width: "100%",
                placeholder: 'Select Project',
                allowClear: true,
            })
            $('.select2-search__field').css('width', '100%');
        })
    </script>
@endsection
