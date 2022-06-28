<div class="modal-header">
    <h1 class="modal-title">List {{ ($type == "crew") ? "Crew" : "Employee" }}</h1>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-12">
            <table class="table table-bordered table-hover table-responsive-xl display">
                <thead>
                    <tr>
                        @if ($type == "crew")
                        <th colspan="2" class="text-center">{{ $project->prj_name }}</th>
                        @else
                        <th colspan="2" class="text-center">{{ $project->name }}</th>
                        @endif
                    </tr>
                    <tr>
                        <th class="text-center">{{ ($type == "crew") ? "Crew" : "Employee" }} Name</th>
                        @if ($type == "crew")
                        <th class="text-center">Period</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($emp as $i => $item)
                        <tr>
                            <td align="center">
                                {{ $item->emp_name }}
                            </td>
                            @if ($type == "crew")
                            <td align="center">
                                @php
                                    if (isset($dep_dt[$item->id])) {
                                        $date1 = date_create(date("Y-m-d"));
                                        $date2 = date_create($dep_dt[$item->id][0]);
                                        $ddiff = date_diff($date2, $date1);
                                        echo $ddiff->format("%a days");
                                    } else {
                                        echo "N/A";
                                    }
                                @endphp
                            </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-light-primary" data-dismiss="modal">Close</button>
</div>
