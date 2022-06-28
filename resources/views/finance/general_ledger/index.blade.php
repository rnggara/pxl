@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>General Ledger</h3><br>

            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <form action="{{route('gl.index')}}" method="post">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4">
                                <input type="date" name="from_date" id="start-date" class="form-control mr-3" value="{{date('Y')."-01-01"}}">
                            </div>
                            <div class="col-md-4">
                                <input type="date" name="to_date" id="end-date" class="form-control" value="{{date('Y')."-12-31"}}">
                            </div>
                            <div class="col-md-2">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <button type="submit" class="btn btn-primary" ><i class="fa fa-search"></i>Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <hr>
            <?php
                $sumdebit = 0;
                $sumcredit = 0;
            ?>
            @foreach($list_coa as $coa)
                @if(isset($data_history[$coa->code]))
                <div class="row">
                    <div class="col-md-12">
                        <h3>{{$coa->name}} [{{$coa->code}}]</h3>
                    </div>
                    <div class="col-md-12 mt-5">
                        <table class="table display table-responsive-xl" id="table-{{$coa->code}}">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Description</th>
                                    <th class="text-center">Credit</th>
                                    <th class="text-center">Debit</th>
                                    <th class="text-center">Balance</th>
                                    <th class="text-center">Job</th>
                                    <th class="text-center">Net Activity</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                    <?php
                                        $balance = 0;
                                        $balance_type = 0;
                                        $sum_credit = 0;
                                        $sum_debit = 0;
                                    ?>
                                    @foreach($data_history[$coa->code] as $key => $value)
                                        <tr>
                                            <td align="center">{{$key + 1}}</td>
                                            <td align="center">{{$value['date']}}</td>
                                            <td align="left">{{$value['desc']}}</td>
                                            <td align="right">
                                                <div class="show-credit{{$coa->code}}{{$value['id']}}">
                                                    <span class="credit">{{number_format($value['credit'])}}</span>
                                                </div>

                                                <?php
                                                /** @var TYPE_NAME $value */
                                                // if ($balance == 0){
                                                //     if ($value['debit'] != 0){
                                                //         $balance += $value['debit'];
                                                //         $sumdebit += $value['debit'];
                                                //         $balance_type = 1;
                                                //     }
                                                // } else {
                                                //     if ($balance_type == 1){
                                                //         $balance += $value['debit'];
                                                //         $sumdebit += $value['debit'];
                                                //     } else {
                                                //         $balance -= $value['debit'];
                                                //         $sumdebit += $value['debit'];
                                                //         if ($balance < 0){
                                                //             $balance_type = 2;
                                                //         }
                                                //     }
                                                // }
                                                ?>
                                                <div class="form-credit{{$coa->code}}{{$value['id']}}">

                                                </div>
                                            </td>
                                            <td align="right">
                                                <div class="show-debit{{$coa->code}}{{$value['id']}}">
                                                    <span class="debit">{{number_format($value['debit'])}}</span>
                                                </div>

                                                <?php
                                                /** @var TYPE_NAME $value */
                                                // if ($balance == 0){
                                                //     if ($value['credit'] != 0){
                                                //         $balance += $value['credit'];
                                                //         $sumcredit += $value['credit'];
                                                //         $balance_type = 2;
                                                //     }
                                                // } else {
                                                //     if ($balance_type == 2){
                                                //         $balance += $value['credit'];
                                                //         $sumcredit += $value['credit'];
                                                //     } else {
                                                //         $balance -= $value['credit'];
                                                //         $sumcredit += $value['credit'];
                                                //         if ($balance < 0){
                                                //             $balance_type = 1;
                                                //         }
                                                //     }
                                                // }

                                                $sum_credit += $value['credit'];
                                                $sum_debit += $value['debit'];
                                                ?>
                                                <div class="form-credit{{$coa->code}}{{$value['id']}}">

                                                </div>
                                            </td>
                                            <td align="right">
                                                @php
                                                    $balance += $value['credit'];
                                                    $balance -= $value['debit'];
                                                @endphp
                                                {{ number_format($balance, 2) }}
                                            </td>
                                            {{-- <td align="right">{{number_format($balance)}} {{($balance_type == 1) ? "(dt)" : "(ct)"}}</td> --}}
                                            <td align="center">
                                                {{ (empty($value['project'])) ? "N/A" : sprintf("%03d", $value['project']) }}
                                            </td>
                                            <td></td>
                                            <td align="center">
                                                <button type="button" onclick="editForm(this, '{{$coa->code}}', '{{$value['id']}}', '{{($value['debit'] == 0) ? 0 : $value['debit']}}', '{{($value['credit'] == 0) ? 0 : $value['credit']}}')" class="btn btn-xs btn-icon btn-primary"><i class="fa fa-edit"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <th class="text-right" colspan="3">Total</th>
                                        <th class="text-right">{{ number_format($sum_credit, 0) }}</th>
                                        <th class="text-right">{{ number_format($sum_debit, 0) }}</th>
                                        <th class="text-right">{{ number_format($balance, 0) }}</th>
                                        <th></th>
                                        <th class="text-right">{{ number_format($sum_credit - $sum_debit, 0) }}</th>
                                        <th></th>
                                    </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr>
                <div class="mb-10"></div>
                @endif
            @endforeach
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-12">
                        <h3>Total</h3>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-responsive-xl">
                            <tr>
                                <th class="text-center">Total Debit</th>
                                <th class="text-center">
                                    {{number_format($sumdebit)}}
                                </th>
                                <th class="text-center">Total Credit</th>
                                <th class="text-center">
                                    {{number_format($sumcredit)}}
                                </th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{asset('theme/assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js?v=7.0.5')}}"></script>
    <script src="{{asset('theme/assets/js/pages/crud/forms/widgets/typeahead.js?v=7.0.5')}}"></script>
    <link href="{{asset('theme/jquery-ui/jquery-ui.css')}}" rel="Stylesheet">
    <script src="{{asset('theme/jquery-ui/jquery-ui.js')}}"></script>
    <script>
        var edit = 0;
        function editForm(btn, x, y, a, b){
            if (edit == 0){
                $(".show-debit"+x+y).hide()
                $(".show-credit"+x+y).hide()
                var i = $(btn).find("i")
                i.removeClass('fa-edit')
                i.addClass('fa-check')
                $(btn).attr('onclick', 'saveForm('+y+')')
                $(".form-debit"+x+y).html(
                    "<input type='number' class='form-control' id='input-debit' value='"+a+"'>"
                )

                $(".form-credit"+x+y).html(
                    "<input type='number' class='form-control' id='input-credit' value='"+b+"'>"
                )
                edit = 1;
            } else {
                Swal.fire('Error', 'There is uncompleted process right now, please completed the process!', 'warning')
            }
        }

        function saveForm(x){
            if ($("#input-debit").val() != 0 && $("#input-credit").val() != 0){
                Swal.fire('Error', 'Can not create entity with debit and credit value', 'error')
            } else if($("#input-debit").val() == 0 && $("#input-credit").val() == 0){
                Swal.fire('Error', 'Can not create entity with debit and credit value', 'error')
            } else {
                $.ajax({
                    url: "{{route('gl.edit')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        _token: "{{csrf_token()}}",
                        debit: $("#input-debit").val(),
                        credit: $("#input-credit").val(),
                        id: x
                    },
                    cache: false,
                    success: function(response){
                        if (response.error == 0){
                            location.reload()
                        } else {
                            Swal.fire('Error occured', 'Please contact your administrator', 'error')
                        }
                    }
                })
            }
        }



        $(document).ready(function(){

            $(".form-edit").hide()


            $("table.display").DataTable({
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                },
                paging : false,
                bInfo: false,
                columns: [
                    {name: "No"},
                    {name: "Date", type: "date"},
                    {name: "Description"},
                    {name: "Reference ID"},
                    {name: "Debit"},
                    {name: "Credit"},
                    {name: "Balance"},
                    {name: ""},
                ]
            })

            $("select.select2").select2({
                width: "100%"
            })
        })

    </script>
@endsection
