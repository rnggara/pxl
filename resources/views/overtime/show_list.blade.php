@extends('layouts.template')
@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">Overtime</h3>
                    <!-- <span class="d-block text-muted pt-2 font-size-sm">Datatable initialized from HTML table</span> -->
            </div>
        </div>
        <hr>
        <div class="card-body">
            <form method="post" action="{{route('overtime.ot')}}">
                @csrf
                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-right">Overtime Periode :</label>
                    <div class="col-md-3">
                        <select name="month" id="" class="form-control">
                            @foreach($months as $key => $month)
                                @if($key == $s_month)
                                    <option value="{{$key}}" SELECTED>{{$month}}</option>
                                @else
                                    <option value="{{$key}}">{{$month}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="year" id="" class="form-control">
                            @for($y=2010; $y <= (($now->year)+5); $y++)
                                @if($y == $s_year)
                                    <option value="{{$y}}" SELECTED>{{$y}}</option>
                                @else
                                    <option value="{{$y}}">{{$y}}</option>
                                @endif

                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" name="show" class="btn btn-primary"><i class="fa fa-search"></i> Show</button>
                    </div>
                </div>
            </form>
            <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                <thead>
                <tr>
                    <th nowrap="nowrap" class="text-center" width=10%">#</th>
                    <th nowrap="nowrap" class="text-left" width="50%">Employee</th>
                    <th nowrap="nowrap" class="text-center" width="20%">Level</th>
                    <th nowrap="nowrap" class="text-center" width="20%">Division</th>
                </tr>
                </thead>
                <tbody>
                @php $i = 0 @endphp
                @foreach($employees as $key => $value)
                    @if($value->company_id == \Session::get('company_id'))
                        @php $i++ @endphp
                        <tr>
                            <td nowrap="nowrap" class="text-center">{{$i}}</td>
                            <td nowrap="nowrap" class="text-left">
                                <a href="{{route('overtime.detail',['id'=>$value->id,'year'=>$s_year,'month'=>$s_month])}}">{{$value->emp_name}}</a>
                            </td>
                            <td nowrap="nowrap" class="text-center">{{$value->empType}}</td>
                            <td nowrap="nowrap" class="text-center">{{(isset($divName['name'][$value->division]))?$divName['name'][$value->division]:''}}</td>
{{--                            <td nowrap="nowrap" class="text-center">--}}
{{--                                @php $daysOvt = 0 @endphp--}}
{{--                                @foreach($overtimes as $key2 => $value2)--}}
{{--                                    @if($value->id == $value2->emp_id)--}}
{{--                                        @php $daysOvt += 1; @endphp--}}
{{--                                    @endif--}}
{{--                                @endforeach--}}
{{--                                @if($daysOvt == 0)--}}
{{--                                    <a href="{{route('overtime.detail',['id'=>$value->id,'year'=>$s_year,'month'=>$s_month])}}">Blank</a>--}}
{{--                                @else--}}
{{--                                    <a href="{{route('overtime.detail',['id'=>$value->id,'year'=>$s_year,'month'=>$s_month])}}">{{$daysOvt}} day(s)</a>--}}
{{--                                @endif--}}
{{--                            </td>--}}
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection
@section('custom_script')
    <script>
        $(document).ready(function () {
            $('.display').DataTable({
                responsive: true,
            });
        });
    </script>
@endsection
