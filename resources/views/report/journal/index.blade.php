@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Sales & Receivables Journal</h3><br>

            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 col-sm-12 mx-auto">
                    <div class="row">
                        <div class="col-md-4 col-sm-4 mx-auto">
                            <input type="date" class="form-control" id="from_dt" value="{{ date("Y")."-01-01" }}">
                        </div>
                        <div class="col-md-4 col-sm-4 mx-auto">
                            <input type="date" class="form-control" id="to_dt" value="{{ date("Y-m-d") }}">
                        </div>
                        <div class="col-md-4 col-sm-4 mx-auto">
                            <button type="button" id="btn-search" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-md-12 col-sm-12" id="show-table">

                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script src="{{ asset("assets/jquery-number/jquery.number.js") }}"></script>
    <script>
        $(document).ready(function(){

            $("select.select2").select2({
                width: "100%"
            })

            $("#btn-search").click(function(){
                $.ajax({
                    ajax : "{{ route('report.journal.index') }}",
                    type : "post",
                    data : {
                        _token : "{{ csrf_token() }}",
                        from_dt : $("#from_dt").val(),
                        to_dt : $("#to_dt").val()
                    },
                    beforeSend : function(){
                        $("#btn-search").prop('disabled', true).addClass('spinner spinner-right')
                    },
                    success : function(response){
                        $("#show-table").html(response)
                        var table = $("table.display").DataTable({
                            fixedHeader: true,
                            fixedHeader: {
                                headerOffset: 90
                            },
                            paging : false,
                            ordering : false,
                            searching : false
                        })


                        $(".number").number(true, 2)

                        $("#btn-search").prop('disabled', false).removeClass('spinner spinner-right')
                    }
                })
            })
        })
    </script>
@endsection
