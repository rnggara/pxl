@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Trial Balance</h3><br>

            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 col-sm-12 mx-auto">
                    <div class="row">
                        <div class="col-md-4 col-sm-4 mx-auto">
                            <select name="month" id="month" class="form-control select2">
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ ($i == date("n")) ? "SELECTED" : "" }}>{{ date("F", strtotime(date("Y")."-$i")) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4 col-sm-4 mx-auto">
                            <select name="year" id="year" class="form-control select2">
                                @for ($i = 2010; $i <= date("Y"); $i++)
                                    <option value="{{ $i }}" {{ ($i == date("Y")) ? "SELECTED" : "" }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4 col-sm-4 mx-auto">
                            <button type="button" id="btn-search" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12" id="show-table">

                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script>
        $(document).ready(function(){

            $("select.select2").select2({
                width: "100%"
            })

            $("#btn-search").click(function(){
                $.ajax({
                    ajax : "{{ route('report.tb.index') }}",
                    type : "post",
                    data : {
                        _token : "{{ csrf_token() }}",
                        month : $("#month").val(),
                        year : $("#year").val()
                    },
                    beforeSend : function(){
                        $("#btn-search").prop('disabled', true).addClass('spinner spinner-right')
                    },
                    success : function(response){
                        $("#show-table").html(response)
                        $("table.display").DataTable({
                            fixedHeader: true,
                            fixedHeader: {
                                headerOffset: 90
                            },
                            paging : false,
                            ordering : false,
                        })

                        $("#btn-search").prop('disabled', false).removeClass('spinner spinner-right')
                    }
                })
            })
        })
    </script>
@endsection
