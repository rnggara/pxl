@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Bank Transfer List Remarks</h3><br>
            </div>
            <div class="card-toolbar">
                <a href="javascript:window.frames['print_btl'].print();" class="btn btn-primary btn-xs"><i class="fa fa-print"></i> Print</a>
                <?php
                    $periode = explode("-", date('Y-m', strtotime($data['periode'])));
                ?>
                <iframe src="{{route('payroll.print_btl')}}?act=print&t={{$data['t']}}&m={{$periode[1]}}&y={{$periode[0]}}" name="print_btl" id="print_btl" frameborder="0" height="0" width="0"></iframe>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <form action="{{route('payroll.remarks_save')}}" method="post">
                        @csrf
                        <div class="col-md-12">
                            <table class="table display table-responsive-xl">
                                <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Employee Name</th>
                                    <th class="text-center">Bank Account</th>
                                    <th class="text-center">THP</th>
                                    <th class="text-center">Remarks</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data['data'] as $key => $value)
                                    <tr>
                                        <td class="text-center">{{$key + 1}}</td>
                                        <td>{{$value['emp_name']}}</td>
                                        <?php
                                        /** @var TYPE_NAME $value */
                                        $bank_code = (isset($data['bank_code'][$value['bank_code']])) ? $data['bank_code'][$value['bank_code']] : "";
                                        ?>
                                        <td class="text-center">{{"[".$bank_code."] ".$value['bank_account']}}</td>
                                        <td align="right">
                                            <input type="number" name="thp[{{$value['emp_id']}}]" class="form-control" value="{{(isset($remarks[$value['emp_id']]) ? $remarks[$value['emp_id']]->thp : str_replace(",", "", $value['thp']))}}">
                                            <input type="hidden" name="thp_old[{{$value['emp_id']}}]" value="{{$value['thp']}}">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="remarks[{{$value['emp_id']}}]" value="{{(isset($remarks[$value['emp_id']]) ? $remarks[$value['emp_id']]->remarks : "")}}">
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12 mt-10">
                            <div class="text-right">
                                <input type="hidden" name="periode" value="{{date('Y-m', strtotime($data['periode']))}}">
                                <button type="submit" class="btn btn-success btn-xs"><i class="fa fa-save"></i> Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>

        $(document).ready(function(){


            $("table.display").DataTable({
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                },
                bInfo: false,
            })

            $("select.select2").select2({
                width: "100%"
            })
        })

    </script>
@endsection
