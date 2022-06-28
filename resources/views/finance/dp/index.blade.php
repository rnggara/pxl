@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">Depreciation</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalAdd"><i class="fa fa-plus"></i> Add</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered table-hover table-responsive-sm">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%">#</th>
                                <th class="text-center">Item</th>
                                <th class="text-center" style="width: 10%">Start/Time</th>
                                <th class="text-center" style="width: 10%">Year</th>
                                <th class="text-center" style="width: 10%">Value</th>
                                <th class="text-center" style="width: 10%">Current Value</th>
                                <th class="text-center" style="width: 10%">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dp as $i => $value)
                                @if (isset($items[$value->item_id]))
                                @php
                                    $date1 = date_create("$value->start-$value->start_mnth");
                                    $date2 = date_create(date("Y-m"));
                                    $interval = date_diff($date1, $date2);
                                    $mnthDiff = $interval->format("%y years %m months");
                                    $yearDiff = $interval->format("%y");

                                    $balance = $value->amount;
                                    $dep_val = $value->amount / $value->start_time;
                                    for ($i=0; $i < $yearDiff; $i++) {
                                        $balance -= $dep_val;
                                    }

                                @endphp
                                <tr>
                                    <td align="center">{{ $i+1 }}</td>
                                    <td align="center">
                                        <a href="{{ route('finance.dp.detail', $value->id) }}" class="label label-inline label-primary font-size-lg bg-hover-light-primary text-hover-primary">{{ $items[$value->item_id] }}</a>
                                    </td>
                                    <td align="center">{{ date("F Y", strtotime("$value->start-$value->start_mnth")) }}</td>
                                    <td align="center">
                                        {{ $mnthDiff }}
                                    </td>
                                    <td align="right">{{ number_format($value->amount, 2) }}</td>
                                    <td align="right">{{ number_format($balance, 2) }}</td>
                                    <td align="center">
                                        <button type="button" onclick="_edit({{ $value->id }})" class="btn btn-primary btn-icon btn-sm"><i class="fa fa-edit"></i></button>
                                        <button type="button" onclick="_delete({{ $value->id }})" class="btn btn-danger btn-icon btn-sm"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalAdd" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title">Add Depreciation</h1>
                </div>
                <form action="{{ route('finance.dp.add') }}" method="post">
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="" class="col-form-label col-3">Item</label>
                            <div class="col-9">
                                <select name="item_id" class="form-control" id="item-list" required></select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-form-label col-3">Amount</label>
                            <div class="col-9">
                                <input type="text" class="form-control number" name="amount" required placeholder="Amount">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-form-label col-3">Start/Time</label>
                            <div class="col-3">
                                <select name="mnth" class="form-control select2" id="" data-placeholder="Month">
                                    @for($i = 1; $i < 12; $i++)
                                        <option value="{{ $i }}" {{ ($i == date("n")) ? "selected" : ""  }}>{{ date("F", strtotime(date("Y")."-".$i)) }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-3">
                                <input type="number" min="1" value="{{ date('Y') }}" class="form-control" name="year" required>
                            </div>
                            <div class="col-3">
                                <input type="number" min="1" value="1" class="form-control" name="year_time" required placeholder="Time">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-form-label col-3">{{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}</label>
                            <div class="col-9">
                                <select name="tc_id" class="form-control" id="tc_id" required></select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @csrf
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalEdit" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" id="edit-content">

            </div>
        </div>
    </div>
@endsection

@section('custom_script')
<script src="{{ asset('assets/jquery-number/jquery.number.js') }}"></script>
    <script>
        function _delete(id){
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!"
            }).then(function(result) {
                if (result.value) {
                    window.location.href = "{{ route('finance.dp.delete') }}/"+id;
                }
            });
        }
        function _edit(id){
            $("#modalEdit").modal('show')
            $.ajax({
                url : "{{ route('finance.dp.get') }}/"+id,
                type : "get",
                cache: false,
                success : function(response){
                    $("#edit-content").html(response)

                    $("#item-list-edit").select2({
                        width: "100%",
                        placeholder: "Select Item",
                        ajax : {
                            url : "{{ route("finance.dp.items") }}",
                            type : "get",
                            dataType : "json"
                        }
                    })

                    var data = {
                        id: $("#item-edit").attr('data-id'),
                        text: $("#item-edit").attr('data-text')
                    };

                    var newOption = new Option(data.text, data.id, true, false);
                    $('#item-list-edit').append(newOption).trigger('change');

                    $("#tc_id_edit").select2({
                        width: "100%",
                        placeholder: "{{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}",
                        ajax : {
                            url : "{{ route("finance.dp.tc") }}",
                            type : "get",
                            dataType : "json"
                        }
                    })

                    var data = {
                        id: $("#tc-edit").attr('data-id'),
                        text: $("#tc-edit").attr('data-text')
                    };

                    var newOption = new Option(data.text, data.id, true, false);
                    $('#tc_id_edit').append(newOption).trigger('change');
                }
            })
        }

        $(document).ready(function(){
            @if (\Session::get('msg'))
                Swal.fire('Delete', 'Item deleted', 'success')
            @endif

            @if (!empty($id))
                $("#item-list").select2({
                    width: "100%"
                })
                $("#modalAdd").modal('show')
                var option = new Option('{{ $item_sel->name }}', {{ $item_sel->id }}, true)
                $("#item-list").append(option).trigger('change')
                $("#item-list").prop('readonly', true)
                $("#modalAdd button[type=button]").hide()
            @else
                var sel_item = $("#item-list").select2({
                    width: "100%",
                    placeholder: "Select Item",
                    ajax : {
                        url : "{{ route("finance.dp.items") }}",
                        type : "get",
                        dataType : "json"
                    }
                })
            @endif

            $(".number").number(true, 2)
            $("table").DataTable()

            $("select[name=mnth]").select2({
                width: "100%"
            })

            $("#tc_id").select2({
                width: "100%",
                placeholder: "Choose here",
                ajax : {
                    url : "{{ route("finance.dp.tc") }}",
                    type : "get",
                    dataType : "json"
                }
            })
        })
    </script>
@endsection
