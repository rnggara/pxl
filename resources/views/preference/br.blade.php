<div class="card-header py-3">
    <div class="row">
        <div class="card-title align-items-start flex-column col-md-10">
            <h3 class="card-label font-weight-bolder text-dark">Budger Request Locking</h3>
        </div>
        <div class="card-toolbar text-right">

        </div>
    </div>

</div>
<div class="row col-md-12 mx-auto m-5">
    <div class="col-md-12">
        <table class="table table-responsive-xl display">
            <thead>
                <tr>
                    <th>Budget Request</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($br_list as $key => $value)
                    <tr>
                        <td>{{$value->name}}</td>
                        <td>
                            @if(isset($br_pref[$value->id]) && $br_pref[$value->id]->unlocked == 1)
                                <a href="{{route('pref.br_update', $value->id)}}" class="btn btn-success btn-xs"><i class="fa fa-unlock"></i> unlocked</a>
                            @else
                                <a href="{{route('pref.br_update', $value->id)}}" class="btn btn-warning btn-xs"><i class="fa fa-lock"></i> locked</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
