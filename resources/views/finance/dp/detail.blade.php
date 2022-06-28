@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <div class="card-title">
                <div class="row">
                    <div class="col-12">
                        <h3>{{ $item->name }}</h3>
                    </div>
                    <div class="col-12">
                        <span class="font-size-sm">Amount : {{ number_format($dp->amount, 2) }}</span>
                    </div>
                </div>
            </div>
            {{-- <h3 class="card-title">{{ $item->name }}</h3> --}}
            <div class="card-toolbar">
                <div class="btn-group">
                    <a href="{{ route('finance.dp.index') }}" class="btn btn-success btn-sm btn-icon"><i class="fa fa-arrow-left"></i></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-8 mx-auto">
                    <form action="{{ route('finance.dp.update') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-12 text-right">
                                {{-- <button type="submit" class="btn btn-sm btn-primary">Save</button> --}}
                            </div>
                            <div class="col-12">
                                <table class="table table-bordered table-hover table-responsive-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Year</th>
                                            <th class="text-center">From Value</th>
                                            <th class="text-center">Deprecrated Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $balance = $dp->amount;
                                            $pctg_val = $dp->amount/$dp->start_time;
                                        @endphp
                                        @for($i = 0; $i < ($dp->start_time + 1); $i++)
                                            <tr>
                                                <td align="center">{{ $dp->start + $i }}</td>
                                                <td align="right">
                                                    {{ number_format(($balance < 0) ? 0 : $balance, 2) }}
                                                </td>
                                                <td align="right">
                                                    {{ number_format(($i == 0) ? 0 : $pctg_val, 2) }}
                                                </td>
                                            </tr>
                                            @php
                                                $balance -= $pctg_val;
                                            @endphp
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-12 text-right">
                                <input type="hidden" name="id_dp" value="{{ $dp->id }}">
                                {{-- <button type="submit" class="btn btn-sm btn-primary">Save</button> --}}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{ asset('assets/jquery-number/jquery.number.js') }}"></script>
    <script>
        $(document).ready(function(){
            $(".number").number(true, 2)
            $("table").DataTable({
                 searching : false,
                 paging : false,
                 bInfo : false,
            })
        })
    </script>
@endsection
