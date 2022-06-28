@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Treasury</h3><br>

            </div>
        </div>
        <div class="card-body">
            @if (!empty($type))
            <form action="{{ route('hiscoa.result') }}" method="post">
                <div class="row">
                    <div class="col-12">
                        <h3>WO</h3>
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    @if ($type == 1)
                                        <th>Description</th>
                                        <th style="width: 5%">Project</th>
                                        <th>Date</th>
                                        <th>Debit TC</th>
                                        <th>Debit Amount</th>
                                        <th>Credit TC</th>
                                        <th>Credit Amount</th>
                                    @else
                                        <th>Result</th>
                                    @endif
                                </tr>
                            </thead>
                            @if (!empty($wo))
                                @foreach ($wo as $i => $item)
                                    @if (!empty($item))
                                        @foreach ($item as $val)
                                        <tr>
                                            <td>
                                                {{ $i }}
                                            </td>
                                            @if ($type == 1)
                                                @if (empty($val))
                                                    <td colspan="7">N/A</td>
                                                @else
                                                    <td>
                                                        {!! $val['desc'] !!}
                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="wo[{{ $i }}][{{ $val['id_his'] }}][id_his]" value="{{ $val['id_his'] }}">
                                                        <input type="text" name="wo[{{ $i }}][{{ $val['id_his'] }}][project]" class="form-control" value="{{ $val['project'] }}">
                                                    </td>
                                                    <td>
                                                        {{ date("F", strtotime($val['date_input'])) }}
                                                    </td>
                                                    <td>
                                                        <input type="text" name="wo[{{ $i }}][{{ $val['id_his'] }}][debit_tc]" value="20000000000" class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type="integer" step=".01" value="{{ abs($val['amount']) }}" class="form-control" name="wo[{{ $i }}][{{ $val['id_his'] }}][debit_amt]">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="wo[{{ $i }}][{{ $val['id_his'] }}][credit_tc]" value="" class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="number form-control" name="wo[{{ $i }}][{{ $val['id_his'] }}][credit_amt]">
                                                    </td>
                                                @endif
                                            @else
                                                <td>
                                                    {{ ($val == 1) ? "Done" : "Not found" }}
                                                </td>
                                            @endif
                                        </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="{{ ($type == 1) ? 8 : 2 }}" align="center">No data found</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h3>PO</h3>
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    @if ($type == 1)
                                        <th>Description</th>
                                        <th style="width: 5%">Project</th>
                                        <th>Date</th>
                                        <th>Debit TC</th>
                                        <th>Debit Amount</th>
                                        <th>Credit TC</th>
                                        <th>Credit Amount</th>
                                    @else
                                        <th>Result</th>
                                    @endif
                                </tr>
                            </thead>
                            @if (!empty($po))
                                @foreach ($po as $i => $item)
                                    @if (!empty($item))
                                        @foreach ($item as $val)
                                        <tr>
                                            <td>
                                                {{ $i }}
                                            </td>
                                            @if ($type == 1)
                                                @if (empty($val))
                                                    <td colspan="7">N/A</td>
                                                @else
                                                    <td>
                                                        {!! $val['desc'] !!}
                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="po[{{ $i }}][{{ $val['id_his'] }}][id_his]" value="{{ $val['id_his'] }}">
                                                        <input type="text" name="po[{{ $i }}][{{ $val['id_his'] }}][project]" class="form-control" value="{{ $val['project'] }}">
                                                    </td>
                                                    <td>
                                                        {{ date("F", strtotime($val['date_input'])) }}
                                                    </td>
                                                    <td>
                                                        <input type="text" name="po[{{ $i }}][{{ $val['id_his'] }}][debit_tc]" value="10000000000" class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type="integer" step=".01" value="{{ abs($val['amount']) }}" class="form-control" name="po[{{ $i }}][{{ $val['id_his'] }}][debit_amt]">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="po[{{ $i }}][{{ $val['id_his'] }}][credit_tc]" value="" class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="number form-control" name="po[{{ $i }}][{{ $val['id_his'] }}][credit_amt]">
                                                    </td>
                                                @endif
                                            @else
                                                <td>
                                                    {{ ($val == 1) ? "Done" : "Not found" }}
                                                </td>
                                            @endif
                                        </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="{{ ($type == 1) ? 8 : 2 }}" align="center">No data found</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h3>History</h3>
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    @if ($type == 1)
                                        <th>Description</th>
                                        <th style="width: 5%">Project</th>
                                        <th>Date</th>
                                        <th>Debit TC</th>
                                        <th>Debit Amount</th>
                                        <th>Credit TC</th>
                                        <th>Credit Amount</th>
                                    @else
                                        <th>Result</th>
                                    @endif
                                </tr>
                            </thead>
                            @if (!empty($history))
                                @foreach ($history as $i => $item)
                                    @if (!empty($item))
                                        @foreach ($item as $val)
                                        <tr>
                                            <td>
                                                {{ $i }}
                                            </td>
                                            @if ($type == 1)
                                                @if (empty($val))
                                                    <td colspan="7">N/A</td>
                                                @else
                                                    <td>
                                                        {!! $val['desc'] !!}
                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="history[{{ $i }}][{{ $val['id_his'] }}][id_his]" value="{{ $val['id_his'] }}">
                                                        <input type="text" name="history[{{ $i }}][{{ $val['id_his'] }}][project]" class="form-control" value="{{ $val['project'] }}">
                                                    </td>
                                                    <td>{{ date("d-M-Y", strtotime($val['date_input'])) }}</td>
                                                    <td>
                                                        <input type="text" name="history[{{ $i }}][{{ $val['id_his'] }}][debit_tc]" value="" class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type="integer" step=".01" value="{{ abs($val['amount']) }}" class="form-control" name="history[{{ $i }}][{{ $val['id_his'] }}][debit_amt]">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="history[{{ $i }}][{{ $val['id_his'] }}][credit_tc]" value="" class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="number form-control" name="history[{{ $i }}][{{ $val['id_his'] }}][credit_amt]">
                                                    </td>
                                                @endif
                                            @else
                                                <td>
                                                    {{ ($val == 1) ? "Done" : "Not found" }}
                                                </td>
                                            @endif
                                        </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="{{ ($type == 1) ? 8 : 2 }}" align="center">No data found</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-right">
                        @csrf
                        @if ($type == 1)
                        <button type="submit" class="btn btn-primary" name="submit" value="2">Save</button>
                        @else
                            <a href="{{ route('hiscoa.index') }}" class="btn btn-primary">New</a>
                        @endif
                    </div>
                </div>
            </form>
            @else
            <form action="" method="post">
                <div class="form-group">
                    <label class="col-form-label font-weight-bold">WO</label>
                    <div class="">
                        <textarea name="wo" class="form-control" id="" cols="30" rows="10"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-form-label font-weight-bold">PO</label>
                    <div class="">
                        <textarea name="po" class="form-control" id="" cols="30" rows="10"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-form-label font-weight-bold">Treasure History</label>
                    <div class="">
                        <textarea name="history" class="form-control" id="" cols="30" rows="10"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    @csrf
                    <button type="submit" name="submit" value="1" class="btn btn-primary">Submit</button>
                </div>
            </form>
            @endif
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{asset('theme/assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js?v=7.0.5')}}"></script>
    <script src="{{asset('theme/assets/js/pages/crud/forms/widgets/typeahead.js?v=7.0.5')}}"></script>
    <link href="{{asset('theme/jquery-ui/jquery-ui.css')}}" rel="Stylesheet">
    <script src="{{asset('theme/jquery-ui/jquery-ui.js')}}"></script>
    <script src="{{asset('assets/jquery-number/jquery.number.js')}}"></script>
    <script>
        $(document).ready(function(){
            $("select.select2").select2({
                width : "100%"
            })
            $(".number").number(true, 2)
        })
    </script>
@endsection
