@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <a href="#" class="text-black-50">Items of {{$swt->subject}}</a>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" onclick="addItems({{$swt->id}})" data-target="#addLeads"><i class="fa fa-plus"></i>Add Items</button>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-5 col-sm-5">
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;" id="table-items">
                            <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th nowrap="nowrap" class="text-left">Serial Number</th>
                                <th nowrap="nowrap" class="text-center">Category</th>
                                <th nowrap="nowrap" class="text-center">Type</th>
                                <th nowrap="nowrap" class="text-center">Label</th>
                                <th nowrap="nowrap" class="text-center">Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addLeads" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content" id="item-add">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Items <span id="title-items"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="POST" action="{{URL::route('te.subwt.update_items')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mt-10">
                                <table class="display table table-bordered table-hover table-responsive-xl table-striped" id="table-add-list" data-page-length="25">
                                    <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Serial Number</th>
                                        <th class="text-center">Category</th>
                                        <th class="text-center">Type</th>
                                        <th class="text-center">Label</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="items" id="items">
                        <input type="hidden" name="id_pd" value="{{$swt->id}}">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="viewItems" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content" id="modalView">

            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script>
        function view_items(x) {
            $("#modalView").html(" ")
            $("#viewItems").modal('show')
            $.ajax({
                url: "{{route('te.el.items_detail')}}/" + x,
                type: "get",
                cache: false,
                success: function(response){
                    $("#modalView").append(response)
                }
            })
        }
        function items_check(x) {
            if ($("#items").val() == "" || $("#items").val() == undefined || $("#items").val() == "null"){
                items = []
            } else {
                var items = JSON.parse($("#items").val())
            }
            var i = $(x).val()
            if (x.checked){
                if (items.length > 0) {
                    if (!items.includes(i)){
                        items.push(i)
                    }
                } else {
                    items.push(i)
                }
            } else {
                var index = items.indexOf(i)
                if (index >= 0){
                    items.splice(index, 1)
                }
            }

            $("#items").val(JSON.stringify(items))
        }

        function addItems(x){
            $("#table-add-list").DataTable().destroy()
            $.ajax({
                url: "{{route('te.el.items')}}/"+x+"/subwt",
                type: "GET",
                dataType: "json",
                cache: false,
                success: function(response){
                    $("#items").val(JSON.stringify(response.items))
                    $("#table-add-list").DataTable({
                        "data" : response.data,
                        "columns" : [
                            { "data" : "key" },
                            { "data" : "serial_number" },
                            { "data" : "category" },
                            { "data" : "type" },
                            { "data" : "label" },
                            { "data" : "status" },
                        ],
                        'columnDefs' : [
                            {"targets" : "_all", "className" : "dt-center"}
                        ]
                    })
                }
            })
        }

        $(document).ready(function () {
            $("select.select2").select2({
                width: "100%"
            })

            $.ajax({
                url: "{{route('te.subwt.get_items', $swt->id)}}",
                type: "GET",
                dataTpe: "json",
                cache: false,
                success: function(response){
                    console.log(JSON.parse(response))
                    $('#table-items').DataTable({
                        responsive: true,
                        fixedHeader: true,
                        fixedHeader: {
                            headerOffset: 90
                        },
                        "data" : JSON.parse(response),
                        "columns" : [
                            { "data" : "key" },
                            { "data" : "serial_number" },
                            { "data" : "category" },
                            { "data" : "type" },
                            { "data" : "subject" },
                            { "data" : "status" },
                        ],
                        'columnDefs' : [
                            {"targets" : "_all", "className" : "dt-center"}
                        ]
                    });
                }
            })

        })

    </script>
@endsection
