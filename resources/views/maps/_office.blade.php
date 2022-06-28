<div class="modal-header">
    <h1 class="modal-title">List Employee</h1>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-12">
            <table class="table table-bordered table-hover table-responsive-xl display">
                <thead>
                    <tr>
                        <th colspan="2" class="text-center">{{ $project->prj_name }}</th>
                    </tr>
                    <tr>
                        <th class="text-center">Employee Name</th>
                        <th class="text-center">Period</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($emp as $i => $item)
                        <tr>
                            <td align="center">
                                {{ $item->emp_name }}
                            </td>
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
