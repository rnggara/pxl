@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                Chart Of Accounts - {{$coa->name}}
            </div>
            <div class="card-toolbar">

                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8 mx-auto row">
                    <div class="col-md-4">
                        <input type="date" id="start-date" class="form-control mr-3" value="{{date('Y')."-01-01"}}">
                    </div>
                    <div class="col-md-4">
                        <input type="date" id="end-date" class="form-control" value="{{date('Y')."-".date('m')."-".date('t')}}">
                    </div>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" id="btn-search" class="btn btn-primary" ><i class="fa fa-search"></i>Search</button>
                    </div>
                </div>
            </div>
            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <table class="table table-bordered table-hover display font-size-sm" id="table-data" style="margin-top: 13px !important; width: 100%;">
                    <thead>
                    <tr>
                        <th nowrap="nowrap">#</th>
                        <th nowrap="nowrap" class="text-left">Date</th>
                        <th nowrap="nowrap" class="text-center">Description</th>
                        <th nowrap="nowrap" class="text-center">Debit</th>
                        <th nowrap="nowrap" class="text-center">Credit</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script>
        $(document).ready(function () {

            $("#btn-search").click(function(){
                $('#table-data').DataTable().clear();
                $('#table-data').DataTable().destroy();
                $.ajax({
                    fixedHeader: true,
                    fixedHeader: {
                        headerOffset: 90
                    },
                    url: "{{route('coa.find')}}",
                    type: "post",
                    dataType: "json",
                    cache: false,
                    data: {
                        "_token" : "{{csrf_token()}}",
                        'start' : $("#start-date").val(),
                        'end' : $("#end-date").val(),
                        'code' : '{{$coa->code}}'
                    },
                    success: function(response){
                        console.log(response)
                        $('#table-data').DataTable({
                            responsive: true,
                            data: response.data
                        });
                    }
                })
            })

            var val = []
            val['data'] = src

            console.log(val)

            console.log(hisdata)
            $('#table-data').DataTable({
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            });
        });
    </script>
@endsection
