@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">CSMS</h3>
            <div class="card-toolbar">
                <button type="button" data-toggle="modal" data-target="#addCsms" class="btn btn-primary"><i class="fa fa-plus"></i> Add new CSMS</button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered table-hover display">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Year</th>
                                <th class="text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($csms as $i => $item)
                                <tr>
                                    <td align="center">{{ $i+1 }}</td>
                                    <td>
                                        @php
                                            if(isset($step_csms[$item->id])){
                                                $type = "detail";
                                            } else {
                                                $type = "step";
                                            }
                                        @endphp
                                        <a href="{{ route('qhse.csms.view', ["type" => $type, "id" => $item->id]) }}">
                                            {{ $item->name }}
                                        </a>
                                    </td>
                                    <td align="center">
                                        <button type="button" class="btn btn-primary">{{ $item->year }}</button>
                                    </td>
                                    <td align="center">
                                        <button type="button" onclick="delete_item({{ $item->id }})" class="btn btn-sm btn-icon btn-danger"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addCsms" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Add CSMS</h3>
                </div>
                <form action="{{ route('qhse.csms.add') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="" class="col-form-label col-3">Name</label>
                            <div class="col-9">
                                <input type="text" class="form-control" name="name" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-form-label col-3">Year</label>
                            <div class="col-9">
                                <input type="number" class="form-control" name="year" value="{{ date('Y') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
<script>
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
              location.href = "{{ route('qhse.csms.delete')}}/csms/"+x
            }
          })
    }
    $(document).ready(function(){
        $("table.display").DataTable()
    })
</script>
@endsection
