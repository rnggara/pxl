@extends('layouts.template')

@section('content')
<div class="card card-custom gutter-b">
    <div class="card-header">
        <h3 class="card-title">Crew Location Notifications</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <table class="table table-bordered display" data-page-length="50">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Project Name</th>
                            <th class="text-center">Notification Default</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($projects as $i => $item)
                            <tr>
                                <td align="center">{{ $i + 1 }}</td>
                                <td>{{ $item->prj_name }}</td>
                                <td>
                                    <div class="row">
                                        <div class="col-7">
                                            <input type="text" class="form-control" onchange="_change(this)" data-id="{{ $item->id }}" value="{{ $item->crew_notification }}">
                                        </div>
                                        <div class="col-5">
                                            <label class="col-form-label">Days</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
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

    function _change(e){
        var div = $(e).parent()
        $.ajax({
            url : "{{ route('hrd.crewnotif.index') }}",
            type : "post",
            dataType : "json",
            data : {
                _token : "{{ csrf_token() }}",
                id : $(e).data('id'),
                days : $(e).val(),
            },
            beforeSend : function(){
                KTApp.block(div, {})
            },
            success : function(response){
                KTApp.unblock(div);
                if(!response.success){
                    Swal.fire("Error", response.message, "error")
                }
            }
        })
    }

    $(document).ready(function(){
        $("table.display").DataTable()
    })

</script>

@endsection
