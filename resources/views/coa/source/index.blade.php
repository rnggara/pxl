@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">Source {{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}</h3>
            <div class="card-toolbar">
                <div class="card-button">
                    {{-- <a href="{{ route('coa.index') }}" class="btn btn-success btn-icon"><i class="fa fa-arrow-left"></i></a> --}}
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <ul class="nav nav-tabs">
                        @foreach ($src as $id => $item)
                        <li class="nav-item">
                            <a href="#" id="nav_{{ $id }}" onclick="_source({{ $id }})" class="nav-link" data-toggle="tab">{{ $item }}</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-12 mt-5">
                    <div class="row" id="alert-show">
                        <div class="col-8 mx-auto">
                            <div class="alert alert-custom alert-notice alert-light-dark fade show" role="alert">
                                <div class="alert-icon"><i class="flaticon-info"></i></div>
                                <div class="alert-text">Click the source to see the data!</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12" id="data-show"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalSigned" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title">Signed Code</h1>
                </div>
                <form action="{{ route('coa.source.sign') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-4">#Paper</label>
                                    <div class="col-8">
                                        <input type="text" readonly class="form-control" id="paper">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-4">{{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}</label>
                                    <div class="col-8">
                                        <select name="code" id="code" class="form-control" required>

                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id" id="id-item">
                        <input type="hidden" name="type" id="type-item">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" >Signed</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="modal fade" id="modalAction" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title">Action</h1>
                    </div>
                    <form action="{{ route('coa.source.sign') }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="" class="col-form-label col-4">WO Type</label>
                                        <div class="col-8">
                                            <input type="text" readonly class="form-control" id="paper">
                                        </div>
                                    </div>
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
                            <input type="hidden" name="type" id="type-item">
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

        function _signed(id, type, code, paper, tc){
            $("#modalSigned").modal('show')
            var newOption = new Option("Choose here", "", true)
            console.log(code)
            if (code !== null) {
                newOption  = new Option(tc, code, true)
            }
            $("#code").find('option').remove()
            console.log(newOption)
            $("#code").append(newOption).trigger('change');
            console.log($("#code"))
            $("#id-item").val(id)
            $("#type-item").val(type)
            $("#paper").val(paper)
        }

        function _source(id){
            _post()
            $.ajax({
                url: "{{ route('coa.source.data') }}",
                type: "post",
                data: {
                    _token : "{{ csrf_token() }}",
                    id : id
                },
                cache: false,
                success: function(response){
                    swal.close()
                    $("#alert-show").hide()
                    $("#data-show").html(response)
                    var table = $("#data-show").find('table')
                    table.DataTable()

                    $("#code").select2({
                        width: "100%",
                        ajax : {
                            url : "{{ route('coa.source.item') }}/"+id,
                            type: "get",
                            dataType: "json",
                        }
                    })
                }
            })
        }

        $(document).ready(function(){
            @if (\Session::get('msg'))
                var btn = "{{ \Session::get('msg') }}"
                $(btn).click()
            @endif
        })
    </script>
@endsection
