@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">CSMS - INPUT</h3>
            <div class="card-toolbar btn-group">
                <button type="button" data-toggle="modal" data-target="#addCsms" class="btn btn-primary"><i class="fa fa-plus"></i> Add new Input</button>
                <a href="{{ route('qhse.csms.view', ["type" => "step", "id"=> $step->id_csms]) }}" class="btn btn-success "><i class="fa fa-arrow-left"></i></a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered table-hover display">
                        <thead>
                            <tr>
                                <th class="text-center" width="10%">#</th>
                                <th class="text-center">Type</th>
                                <th class="text-center" width="10%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($input as $i => $item)
                                <tr>
                                    <td align="center">{{ $i+1 }}</td>
                                    <td>
                                        <label for="" class="label label-xl label-inline label-primary">
                                            @php
                                                /** @var TYPE_NAME $value */
                                                if ($item->type == 'ud'){
                                                    $name = 'Upload Document';
                                                } elseif ($item->type == 'ms'){
                                                    $name = 'Meeting Schedule';
                                                } elseif ($item->type == 'ol'){
                                                    $name = 'Outbox List';
                                                } elseif ($item->type == 'tt'){
                                                    $name = 'Tasks';
                                                } elseif ($item->type == 'ba'){
                                                    $name = 'Berita Acara';
                                                } elseif ($item->type == 'pe'){
                                                    $name = 'Pernyataan Efektif dari OJK';
                                                } elseif ($item->type == 'su'){
                                                    $name = 'Table';
                                                } elseif ($item->type == 'link'){
                                                    $name = 'Cypher Links';
                                                } else {
                                                    $name = "";
                                                }
                                            @endphp
                                            {{ $name }}
                                        </label>
                                        @if (count($input) > 1)
                                                <div class="row mt-5">
                                                    <div class="col-12">
                                                        <div class="btn-group">
                                                            @if ($i > 0)
                                                            <button type="button" {{ (isset($input[$i-1]) ? "onclick=change_order(".$input[$i-1]['id'].",".$item->id.")" : '') }} class="btn btn-icon btn-primary"><i class="fa fa-arrow-up"></i></button>
                                                            @endif

                                                            @if ($i < (count($input) - 1))
                                                            <button type="button" {{ (isset($input[$i+1]) ? "onclick=change_order(".$input[$i+1]['id'].",".$item->id.")" : '') }} class="btn btn-icon btn-danger"><i class="fa fa-arrow-down"></i></button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
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
                    <h3 class="modal-title">Add Step</h3>
                </div>
                <form action="{{ route('qhse.csms.add.input') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="" class="col-form-label col-3">Type</label>
                            <div class="col-9">
                                <select name="type" class="form-control select2" required>
                                    <option value="">Select Type</option>
                                    <option value='ud'>Upload Document</option>
                                    <option value='ms'>Meeting Schedule</option>
                                    <option value='tt'>Tasks</option>
                                    <option value='su'>Table</option>
                                    <option value="link">Cypher4 Links</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_step" value="{{ $step->id }}">
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
    function change_order(x, y){
        Swal.fire({
            title: 'Are you sure?',
            text: "Change order of this item",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, change it!'
        }).then((result) => {
            if (result.isConfirmed) {
                location.href = "{{ route('qhse.csms.change')}}/input/"+x+"/"+y
            }
        })
    }
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
              location.href = "{{ route('qhse.csms.delete')}}/input/"+x
            }
          })
    }
    $(document).ready(function(){
        $("table.display").DataTable({
            paging: false,
            sorting: false,
            searching: false,
            bInfo: false
        })
        $("select.select2").select2({
            width: "100%"
        })
    })
</script>
@endsection
