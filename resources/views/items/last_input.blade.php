@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">Last Input Item</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-hover table-bordered" id="table-item">
                        <thead>
                            <th class="text-center">#</th>
                            <th class="text-left">Item Name</th>
                            <th class="text-left">Category</th>
                            <th class="text-center">Code</th>
                            <th class="text-center">UoM</th>
                            <th class="text-center">Created By</th>
                            <th class="text-center" nowrap="nowrap">Created Date</th>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script>
        $(document).ready(function () {
            $("#table-item").DataTable({
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                },
                pageLength: 100,
                ajax : {
                    url: "{{route('items.last.input.list')}}",
                    type: "post",
                    dataType: "json",
                    data : {
                        _token: "{{csrf_token()}}"
                    }
                },
                columns : [
                    {"data" : "i"},
                    {"data" : "name"},
                    {"data" : "category"},
                    {"data" : "code"},
                    {"data" : "uom"},
                    {"data" : "by"},
                    {"data" : "date"},
                ],
                columnDefs : [
                    {targets : [0, 2, 3, 4, 5], className : "text-center"},
                    {targets : [6], className: "text-nowrap text-center" }
                ]
            })
        })
    </script>
@endsection
