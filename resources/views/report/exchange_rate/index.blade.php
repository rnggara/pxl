@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">Exchange Rate</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <a href="{{ route('report.er.insert') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered table-hover table-responsive-sm display">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Date Time</th>
                                <th class="text-center">Created By</th>
                                <th class="text-center">Others</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rates as $i => $rate)
                                @php
                                    $jsRate = [];
                                    if(!empty($rate->rates)){
                                        $jsRate = json_decode($rate->rates, true);
                                    }

                                    $usd = 0;
                                    if(isset($jsRate['USD'])){
                                        $usd = $jsRate['USD'];
                                    }
                                @endphp
                                <tr>
                                    <td align="center">{{ $i+1 }}</td>
                                    <td align="center">{{ date("d F Y @ H:i", strtotime($rate->date_rate)) }}</td>
                                    <td align="center">{{ $rate->created_by }}</td>
                                    <td align="center">
                                        <button type="button" class="btn btn-sm btn-icon btn-primary" onclick="_get({{ $rate->id }})"><i class="fa fa-file-alt"></i></button>
                                    </td>
                                    <td align="center">
                                        <button type="button" onclick="_delete({{ $rate->id }})" class="btn btn-sm btn-icon btn-danger"><i class="fa fa-trash"></i></button>
                                        {{-- <button type="button" onclick="_copy({{ $rate->id }})" class="btn btn-sm btn-icon btn-info"><i class="fa fa-copy"></i></button> --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalNew" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title">Add New</h1>
                </div>
                <form action="{{ route('report.er.add') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-form-label col-3">Date</label>
                            <div class="col-9">
                                <input type="date" class="form-control" name="_date" required value="{{ date("Y-m-d") }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-3">USD - IDR</label>
                            <div class="col-9">
                                <input type="text" class="form-control number" id="usd-to-idr" name="rate[USD]" required>
                            </div >
                        </div>
                        <div id="row-add"></div>
                        <div class="form-group row">
                            {{-- <label class="col-form-label col-3">USD - IDR</label> --}}
                            <div class="col-3">
                                <button type="button" class="btn btn-icon btn-outline-primary btn-sm btn-circle" id="btn-add-rate"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalRate" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title">Add Rate</h1>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-form-label col-3">Currency</label>
                        <div class="col-9">
                            <select name="_curr" id="currency-sel" class="form-control">

                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-3">Rate to IDR</label>
                        <div class="col-9">
                            <input type="text" class="form-control number" id="rate-to-idr" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="btn-update-rate">Add</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalOthers" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">

            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{ asset('assets/jquery-number/jquery.number.js') }}"></script>
    <script>
        var curr_sel = ["USD"]

        function _delete(id){
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!"
            }).then(function(result) {
                if (result.value) {
                    window.location.href = "{{ route('report.er.delete') }}/"+id
                }
            });
        }

        function _copy(id){
            Swal.fire({
                title: "Are you sure?",
                text: "Copy this data",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes"
            }).then(function(result) {
                if (result.value) {
                    window.location.href = "{{ route('report.er.copy') }}/"+id
                }
            });
        }

        function _get(id){
            $("#modalOthers").modal('show')
            $.ajax({
                url : "{{ route('report.er.get') }}/"+id,
                type : "get",
                success : function(response){
                    $("#modalOthers .modal-content").html(response)
                }
            })
        }

        function _remove(btn, curr){
            var row = $(btn).parents("div.form-group")
            row.remove()
            const index = curr_sel.indexOf(curr);
            if (index > -1) {
                curr_sel.splice(index, 1);
            }
        }

        $(document).ready(function(){
            $(".number").number(true, 2)
            $("table.display").DataTable()
            $("select.select2").select2({
                width:"100%"
            })

            $("#btn-add-rate").click(function(){
                var rate = $("#usd-to-idr").val().replaceAll(",", "")
                if(rate != "" && rate > 0){
                    $("#modalRate").modal('show')
                    var options = $("#currency-sel").find('option')
                    options.remove()
                    var currency = JSON.parse("{{ $list_currency }}".replaceAll("&quot;", "\""))
                    for (let index = 0; index < curr_sel.length; index++) {
                        delete currency[curr_sel[index]]
                    }
                    var data = [];
                    for (const key in currency) {
                        var row = {}
                        row.id = key
                        row.text = "["+key+"] " + currency[key]
                        data.push(row)
                    }

                    $("#currency-sel").select2({
                        data : data,
                        width : "100%"
                    })
                } else {
                    Swal.fire("Rate usd empty", "usd to idr cannot empty or zero", 'error')
                }
            })

            $("#btn-update-rate").click(function(){
                var rate = $("#rate-to-idr").val().replaceAll(",", "")
                console.log(rate)
                var curr = $("#currency-sel").val()
                if(rate != "" && rate > 0){
                    var _append = `<div class="form-group row">
                            <label class="col-form-label col-3">`+curr+` - IDR</label>
                            <div class="col-7">
                                <input type="text" class="form-control number" name="rate[`+curr+`]" required value="`+rate+`">
                            </div>
                            <div class="col-2">
                                <button type="button" onclick="_remove(this, '`+curr+`')" class="btn btn-icon btn-sm btn-danger btn-circle"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>`
                    $("#row-add").append(_append)
                    $(".number").number(true, 2)
                    $("#rate-to-idr").val('')
                    $("#modalRate").modal('hide')
                    curr_sel.push(curr)
                } else {
                    Swal.fire("Rate empty", "rate to idr cannot empty or zero", 'error')
                }
            })
        })
    </script>
@endsection
