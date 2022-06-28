@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">Contract Template</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <a href="{{ route('hrd.contract.add_template') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Template</a>
                    <button class="btn btn-info" data-toggle="modal" data-target="#modalFields"><i class="fa fa-cog"></i> Fields</button>
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
                                <th class="text-center">Template Name</th>
                                <th class="text-center">Template Target</th>
                                <th class="text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($templates as $i => $item)
                                <tr>
                                    <td align="center">{{ $i+1 }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td align="center">
                                        @if (empty($item->targets))
                                            All
                                        @else
                                            {{ $emptypes[$item->targets] ?? "-" }}
                                        @endif
                                    </td>
                                    <td align="center">
                                        <a href="{{ route("hrd.contract.add_template", $item->id) }}" class="btn btn-info btn-xs btn-icon"><i class="fa fa-eye"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal-->
    <div class="modal fade" id="modalFields" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title">Fields</h1>
                    <button class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <h3>Add Fields</h3>
                            <hr>
                            <div class="form-group row">
                                <h3 class="col-form-label col-3">Field Name</h3>
                                <div class="col-9">
                                    <input type="text" class="form-control" name="f_name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <h3 class="col-form-label col-3">Field Description</h3>
                                <div class="col-9">
                                    <input type="text" class="form-control" name="desc">
                                </div>
                            </div>
                            <div class="form-group row">
                                <h3 class="col-form-label col-3">Field Type</h3>
                                <div class="col-9">
                                    <select name="f_type" class="form-control select2">
                                        <option value="text">Text</option>
                                        <option value="int">Number</option>
                                        <option value="currency">Currency</option>
                                        <option value="time">Time</option>
                                        <option value="date">Date</option>
                                        <option value="position">Position</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <h3 class="col-form-label col-3">Field Length</h3>
                                <div class="col-9">
                                    <input type="number" min="1" step=".01" class="form-control" name="f_length">
                                </div>
                            </div>
                            <div class="form-group row">
                                <h3 class="col-form-label col-3">Employee Field</h3>
                                <div class="col-9">
                                    <select name="emp_field" class="form-control select2" data-placeholder="Employee Field (optional)">
                                        <option value=""></option>
                                        <option value="salary">Salary</option>
                                        <option value="voucher">Voucher</option>
                                        <option value="fld_bonus">Field Bonus</option>
                                        <option value="expire">Expire</option>
                                        <option value="position">Position</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <h3 class="col-form-label col-3"></h3>
                                <div class="col-9 text-right">
                                    <button type="button" id="btn-add-fields" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add</button>
                                </div>
                            </div>

                            <hr>
                        </div>
                        <div class="col-12">
                            <table class="table tabl-bordered table-hover" id="table-fields">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Field Name</th>
                                        <th class="text-center">Field Type</th>
                                        <th class="text-center">Field Length</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalGenerate" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content" id="modal-content-fld">

            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{ asset("assets/jquery-number/jquery.number.js") }}"></script>
    <script>

        var t = $("#table-fields").DataTable({
            ajax : {
                url : "{{ route('hrd.contract.indexPost') }}",
                type : "post",
                dataType : "json",
                data : {
                    _token : "{{ csrf_token() }}",
                    type : "table"
                }
            },
            columnDefs : [
                {"targets" : "_all", "className" : "text-center"}
            ]
        })

        function _delete(x){
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!"
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url : "{{ route('hrd.contract.indexPost') }}",
                        type : "post",
                        dataType : "json",
                        data : {
                            _token : "{{ csrf_token() }}",
                            id : x,
                            type : "delete"
                        },
                        success : function(response){
                            if(response.success){
                                // reload datatable
                                t.ajax.reload()
                            } else {
                                Swal.fire("Error", "Please contact your Sys Admin", "error")
                            }
                        }
                    })
                }
            });
        }

        function _set_id_template(id){
            $("#modal-content-fld").html("")
            $.ajax({
                url : "{{ route('hrd.contract.indexPost') }}",
                type : "post",
                data : {
                    _token : "{{ csrf_token() }}",
                    id : id,
                    type : "modal"
                },
                success : function(response){
                    $("#modal-content-fld").html(response)
                    $(".number").number(true, 2)
                    $("select.select2").select2({
                        width : "100%"
                    })

                    $("#emp-name").change(function(){
                        console.log($(this).val())
                        $.ajax({
                            url : "{{ route('hrd.contract.indexPost') }}",
                            type : "post",
                            dataType : "json",
                            data : {
                                _token : "{{ csrf_token() }}",
                                id : $(this).val(),
                                type : "emp"
                            },
                            success : function(data){
                                var nik = $("#modal-content-fld").find("input[name=nik]")
                                var address = $("#modal-content-fld").find("textarea[name=address]")
                                nik.val(data.nik)
                                $("#jk").val(data.gender).trigger('change')
                                $("#tmpt").val(data.emp_tmpt_lahir)
                                $("#tgl").val(data.emp_lahir)
                                address.val(data.address)
                                $(".field_emp").each(function(){
                                    var id = $(this).attr('id')
                                    var _val = data[id]
                                    if(id == "salary"){
                                        _val = parseFloat(atob(data[id])) + parseFloat(atob(data['transport'])) + parseFloat(atob(data['meal'])) + parseFloat(atob(data['house'])) + parseFloat(atob(data['transport']))
                                        console.log(_val)
                                    }
                                    $(this).val(_val)
                                    $("#emp-type").val(data.emp_type).trigger('change')
                                    $("#emp-div").val(data.division).trigger('change')
                                })
                            }
                        })
                    })
                }
            })
        }

        $(document).ready(function(){
            $("table.display").DataTable()
            $(".number").number(true, 2)
            $("select.select2").select2({
                width : "100%"
            })

            $("#btn-add-fields").click(function(){
                var _i = $(this).html()
                var _name = $("input[name=f_name]").val()
                if(_name == ""){
                    return Swal.fire('Required', "Field Name is Required", "warning")
                }

                var _length = $("input[name=f_length]").val()
                if(_length == ""){
                    return Swal.fire('Required', "Field Length is Required", "warning")
                }
                $.ajax({
                    url : "{{ route('hrd.contract.indexPost') }}",
                    type : "post",
                    dataType : "json",
                    data : {
                        _token : "{{ csrf_token() }}",
                        f_name : $("input[name=f_name]").val(),
                        f_type : $("select[name=f_type]").val(),
                        f_length : $("input[name=f_length]").val(),
                        desc : $("input[name=desc]").val(),
                        emp_field : $("input[name=emp_field]").val(),
                        type : "add"
                    },
                    beforeSend : function(){
                        $("#btn-add-fields").prop('disabled', true).text("Loading...").addClass('spinner spinner-left')
                    },
                    success : function(response){
                        $("#btn-add-fields").prop('disabled', false).html(_i).removeClass('spinner spinner-left')
                        if(response.success){
                            // reload datatable
                            t.ajax.reload()
                        } else {
                            Swal.fire("Error", "Please contact your Sys Admin", "error")
                        }
                    }
                })
            })


        })
    </script>
@endsection
