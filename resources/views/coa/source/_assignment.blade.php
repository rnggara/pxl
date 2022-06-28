@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">Assignment {{ $data['typewo']->name }}</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <a href="{{ route('coa.source.index') }}" class="btn btn-success btn-icon"><i class="fa fa-arrow-left"></i></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered table-hover display">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">#{{ $data['category'] }}</th>
                                <th class="text-center">Description</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['table'] as $i => $item)
                                @php
                                    $tc_code = (empty($item['tc_id'])) ? null : $data['coa'][$item['tc_id']];
                                    $tc_name = (empty($item['tc_id'])) ? null : $data['coa_name'][$item['tc_id']];
                                    $tc = "[$tc_code] $tc_name";
                                @endphp
                                <tr>
                                    <td align="center">{{ $i+1 }}</td>
                                    <td align="center">{{ $item['paper'] }}</td>
                                    <td align="center">{{ $item['description'] }}</td>
                                    <td align="right">{{ number_format($item['amount'], 2) }}</td>
                                    <td align="center">
                                        @if (empty($item['tc_id']))

                                        @endif
                                        <a href="#" onclick="_signed({{ $item['id'] }}, {{ (empty($item['tc_id'])) ? 'null' : $item['tc_id'] }}, {{ (empty($item['tc_id'])) ? 'null' : "'$tc'" }})">
                                            {{ (empty($tc_code)) ? "unassigned" : $tc }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="modal fade" id="modalSigned" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title">Assign</h1>
                    </div>
                    <form action="{{ route('coa.source.sign') }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="" class="col-form-label col-4">{{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}</label>
                                        <div class="col-8">
                                            <select name="code" id="code" class="form-control" required>
                                                <option value="">Select {{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}</option>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="id" id="id-item">
                            <input type="hidden" name="type" value="{{ $data['src']->id }}">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" >Signed</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        function _signed(id, code, tc){
            $("#modalSigned").modal('show')
            var newOption = new Option("Choose here", "", true)
            if(tc !== null){
                newOption = new Option(tc, code, true)
            }
            $("#code").find('option').remove()
            $("#code").append(newOption).trigger('change')
            $("#id-item").val(id)
        }
        $(document).ready(function(){
            $("#code").select2({
                width: "100%",
                ajax : {
                    url : "{{ route('coa.source.item') }}/{{ $data['src']->id }}",
                    type: "get",
                    dataType: "json",
                }
            })

            $("table.display").DataTable()
        })
    </script>
@endsection
