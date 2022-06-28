@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <a href="javascript:" class="text-black-50">Create prognosis - <span class="text-primary">{{$project->prj_name}}</span></a>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{route('marketing.project')}}" class="btn btn-success btn-xs"><i class="fa fa-arrow-left"></i></a>
                </div>
                <!--end::Button-->
            </div>
        </div>
    </div>
    @foreach($tables as $table)
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>{{ucwords(str_replace("_", " ", $table))}}</h3>
            </div>
            <div class="card-toolbar">
                <button class="btn btn-xs btn-primary" onclick="addModal('{{$table}}')"><i class="fa fa-plus"></i></button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-hover table-bordered">
                        <thead class="bg-warning text-white">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-left">{{strtoupper(str_replace("_", " ", $table))}}</th>
                                <th class="text-center">PASS CODE PROJECT</th>
                                <th class="text-right">NOMINAL</th>
                                <th class="text-center">%</th>
                                <th class="text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        /** @var TYPE_NAME $table */
                        /** @var TYPE_NAME $prognosis */
                        $i = 1;
                        $total[$table] = 0;
                        $total_sum[$table] = 0;
                        $percent[$table] = 0;
                        foreach ($prognosis as $item){
                            if ($item->category == $table){
                                $total_sum[$table] += $item->amount;
                            }
                        }
                        ?>
                        @foreach($prognosis as $item)
                            @if($item->category == $table)
                                <tr>
                                    <td align="center">{{$i++}}</td>
                                    <td>{{strtoupper($item->subject)}}</td>
                                    <td align="center">
                                        <span class="label label-md label-primary label-inline">{{$item->RCTR}}</span>
                                    </td>
                                    <td align="right">
                                        @if($table == "operating_expenses")
                                            {{number_format(($item->amount/100) * $totalsales), 2}}
                                            <?php
                                            /** @var TYPE_NAME $item */
                                            /** @var TYPE_NAME $totalsales */
                                            $total[$table] += ($item->amount/100) * $totalsales
                                            ?>
                                        @else
                                            {{number_format($item->amount, 2)}}
                                            <?php
                                            /** @var TYPE_NAME $item */
                                            $total[$table] += $item->amount
                                            ?>
                                        @endif
                                    </td>
                                    <td align="center">
                                        @if($table == "operating_expenses")
                                            {{number_format($item->amount, 2)}} %
                                            <?php
                                            /** @var TYPE_NAME $item */
                                            $percent[$table] += $item->amount;
                                            ?>
                                        @else
                                            @if ($totalsales > 0)
                                                {{number_format(($item->amount / $totalsales) * 100, 2)}} %
                                                <?php
                                                /** @var TYPE_NAME $item */
                                                $perc = ($item->amount / $totalsales) * 100;
                                                $percent[$table] += $perc;
                                            ?>
                                            @endif
                                        @endif
                                    </td>
                                    <td align="center">
                                        <button onclick="delete_item('{{$item->id}}')" class="btn btn-xs btn-danger btn-icon"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                        <tfoot class="bg-secondary">
                            <tr>
                                <td colspan="3" align="center"><b>TOTAL {{strtoupper($table)}}</b></td>
                                <td align="right">{{number_format($total[$table], 2)}}</td>
                                <td align="center">
                                    @if($percent[$table] > 0)
                                        {{number_format($percent[$table], 2)}} %
                                    @endif
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    <div class="card card-custom gutter-b">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <?php
                    if (count($prognosis) > 0){
                        /** @var TYPE_NAME $tables */
                        $net = $totalsales;
                        foreach ($tables as $table){
                            if ($table != "sales"){
                                $net -= ($total[$table]);
                            }
                        }
                        if($totalsales > 0){
                            $netper = $net / $totalsales * 100;
                            $tax = $net * (30/100);
                            $taxper = $tax / $totalsales * 100;
                            $profit = $net - $tax;
                            $profitper = $profit/$totalsales * 100;
                        } else {
                            $netper = 0;
                            $tax = 0;
                            $taxper = 0;
                            $profit = 0;
                            $profitper = 0;
                        }

                    } else {
                        $net = 0;
                        $netper = 0;
                        $tax = 0;
                        $taxper = 0;
                        $profit = 0;
                        $profitper = 0;
                    }
                    ?>
                    <table class="table table-bordered">
                        <tr>
                            <th colspan="3" class="text-center">NET BEFORE TAX</th>
                            <th class="text-right">
                                {{number_format($net, 2)}}
                            </th>
                            <th class="text-right">
                                {{round(number_format($netper, 2))}} %
                            </th>
                            <th></th>
                        </tr>
                        <tr>
                            <th colspan="3" class="text-center">TAX (30%)</th>
                            <th class="text-right">
                                {{number_format($tax, 2)}}
                            </th>
                            <th class="text-right">
                                {{round(number_format($taxper, 2))}} %
                            </th>
                            <th></th>
                        </tr>
                        <tr>
                            <th colspan="3" class="text-center">NET PROFIT AFTER TAX</th>
                            <th class="text-right">
                                {{number_format($profit, 2)}}
                            </th>
                            <th class="text-right">
                                {{round(number_format($profitper, 2))}} %
                            </th>
                            <th></th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addPrognosis" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add <span id="add-title"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="POST" action="{{route('marketing.prognosis.add')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="alert alert-custom alert-light-warning">
                                <div class="alert-icon">
                                    <i class="flaticon2-warning"></i>
                                </div>
                                <div class="alert-text">
                                    Please select between List prognosis or Other subject for Prognosis Subject
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Pass Code Project</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="code_project" name="code_project" readonly/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">List Items</label>
                                    <div class="col-md-8">
                                        <select name="list_prognosis" class="form-control select2" id="list">
                                            <option value="">Select Prognosis</option>
                                            @foreach($prognosis as $item)
                                                <option value="{{strtoupper($item->subject)}}">{{strtoupper($item->subject)}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Add Other Item</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="other" name="subject"/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4"><span id="modal-amount"></span></label>
                                    <div class="col-md-8">
                                        <input type="text" id="allownumericwithdecimal" required class="form-control" name="amount"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <input type="hidden" name="type" id="type">
                        <input type="hidden" name="project" value="{{$project->id}}">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" id="btn-save-prognosis" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')

    <script>

        function addModal(x){
            var jsonnum = "{{json_encode($num)}}".replaceAll("&quot;", "\"")
            var num = JSON.parse(jsonnum)
            console.log(num[x])
            $("#addPrognosis").modal('show')
            var a = x.split("_")
            var code = ""
            for (const i in a) {
                if (a.length > 1){
                    var b = a[i][0].toLowerCase().replace(/\b[a-z]/g, function(letter) {
                        return letter.toUpperCase();
                    });
                } else {
                    var c = a[i][0].toLowerCase().replace(/\b[a-z]/g, function(letter) {
                        return letter.toUpperCase();
                    });
                    var d = a[i][1].toLowerCase().replace(/\b[a-z]/g, function(letter) {
                        return letter.toUpperCase();
                    });
                    var b = c+d
                }
                code += b
            }
            $("#code_project").val(code+"{{$project->id}}"+num[x])
            var str = x;
            str = str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                return letter.toUpperCase();
            });
            $("#type").val(x)
            $("#add-title").text(str.replaceAll("_", " "))
            var amount = "Amount"
            if (x === "operating_expenses"){
                amount = "Percentage"
            }

            $("#modal-amount").html(amount)
        }

        function delete_item(x){
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    location.href = "{{route('marketing.prognosis.delete')}}/" + x
                }
            })
        }

        $(document).ready(function () {
            $("#allownumericwithdecimal").on("keypress keyup blur",function (event) {
                //this.value = this.value.replace(/[^0-9\.]/g,'');
                $(this).val($(this).val().replace(/[^0-9\.]/g,''));
                if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                    event.preventDefault();
                }
            });

            $("#btn-save-prognosis").click(function(e){
                if ($("#list option:selected").val() === "" && $("#other").val() === ""){
                    e.preventDefault()
                    Swal.fire('Uncompleted form', 'Please complete the form given!', 'warning')
                } else if ($("#list option:selected").val() !== "" && $("#other").val() !== ""){
                    e.preventDefault()
                    Swal.fire('Warning', 'Please follow the instruction given in the form!', 'warning')
                }
            })

            $("select.select2").select2({
                width: "100%"
            })


            $('.display').DataTable({
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            });

        })

    </script>
@endsection
