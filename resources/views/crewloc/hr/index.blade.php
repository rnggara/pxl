@extends('layouts.template')

@section('content')
<div class="card card-custom gutter-b">
    <div class="card-header">
        <h3 class="card-title">Crew Location HR</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <table class="table table-bordered display" data-page-length="50">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Employee Name</th>
                            <th class="text-center">Last Project</th>
                            <th class="text-center">From Date</th>
                            <th class="text-center">Scheduled for(Project/Date)</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php
                        $i = 0;
                    @endphp
                    @foreach($kruoff as $key => $val)
                        @if (isset($available[$val->id]))
                            @php
                                $return_dt = $available[$val->id]->return_dt;
                                $prj_name = $prj[$available[$val->id]->id];
                                $days = 0;
                                $months = 0;
                                $d1 = date_create($return_dt);
                                $d2 = date_create(date("Y-m-d"));
                                $diff = date_diff($d2, $d1);
                                $days = $diff->format("%d");
                                $months = $diff->format("%m");
                            @endphp
                            <tr>
                                <td class="text-center">{{($i+=1)}}</td>
                                <td>
                                    <b class="font-size-h6-sm">{{$val->emp_name}}</b>
                                </td>
                                <td>
                                    {{$prj_name}}
                                </td>
                                <td align="center" class="text-nowrap">
                                    @php
                                        $d1 = date_create(date("Y-m-d"));
                                        $d2 = date_create($return_dt);
                                        $diff = date_diff($d1, $d2);
                                        $days = $diff->format("%d");
                                        $months = $diff->format("%m");
                                    @endphp
                                    {{ date("d-M-Y", strtotime($return_dt)) }} <br>
                                    <i class="text-muted">
                                        {{ ($months > 0) ? $months." month(s) " : "" }}{{ $days }} day(s)
                                    </i>
                                </td>
                                <td align="center">
                                    @if (isset($next[$val->id]))
                                        @php
                                            $upcoming = $next[$val->id][0];
                                        @endphp

                                        {{ $upcoming->project_name }} <br>
                                        <label class="label label-inline label-primary">
                                            {{ date("d-M-Y", strtotime($upcoming->departure_dt)) }}
                                        </label>
                                    @else
                                        N/A
                                    @endif
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
@endsection

@section('custom_script')


<script>

    $(document).ready(function(){
        $("table.display").DataTable()
    })

</script>

@endsection
