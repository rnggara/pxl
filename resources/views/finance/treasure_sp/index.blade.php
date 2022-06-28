@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">SP List</h3>
            <div class="card-toolbar">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addSpModal"><i class="fa fa-plus"></i> Add SP</button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table table-hover table-bordered" id="table-sp">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">SP Number</th>
                                <th class="text-center">From</th>
                                <th class="text-center">To</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal-->
    <div class="modal fade" id="addSpModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Add SP</h3>
                </div>
                <form action="{{ route('treasure.sp.add') }}" method="POST" id="form-add">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="" class="col-form-label col-3">SP #</label>
                            <div class="col-9">
                                <input type="text" class="form-control" name="sp_num" value="{{ $spNum }}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-form-label col-3">From</label>
                            <div class="col-9">
                                <input type="date" class="form-control" name="date_from" id="date-from" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-form-label col-3">To</label>
                            <div class="col-9">
                                <input type="date" class="form-control" name="date_to" id="date-to" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-form-label col-3">Opening Balance</label>
                            <div class="col-9">
                                <input type="text" class="form-control number" id="bl" name="balance" value="" placeholder="Please elect From Date first!" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_bank" value="{{ $treasury->id }}">
                        <button class="btn btn-light-primary" data-dismiss="modal" type="button">Close</button>
                        <button class="btn btn-primary" type="submit" id="btn-add">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script src="{{ asset('assets/jquery-number/jquery.number.js') }}"></script>
    <script>
        $(document).ready(function(){
            $("#btn-add").one('click', function (event) {
                   event.preventDefault();
                   //do something
                   $("#form-add").submit()
                   $(this).prop('disabled', true);
             });
            $(".number").number(true, 2)
            $("#date-from").change(function(){
                $("#date-to").val($(this).val())
                $.ajax({
                    url : "{{ route('treasure.sp.index', $treasury->id) }}?d="+$(this).val(),
                    type : "get",
                    dataType : "json",
                    success : function(response){
                        $("#bl").val(response)
                    }
                })
            })
            $("#table-sp").DataTable({
                pageLength: 100,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                },
                ajax: {
                    url: "{{ route('treasure.sp.list', $treasury->id) }}",
                    type: "get",
                    dataType: "json",
                },
                columns : [
                    {"data":"i"},
                    {"data":"num"},
                    {"data":"from"},
                    {"data":"to"},
                    {"data":"action"},
                ],
                columnDefs : [
                    {targets: "_all", className : "text-center"}
                ]
            })
        })
    </script>

@endsection
