@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">CSMS - STEP</h3>
            <div class="card-toolbar btn-group">
                <button type="button" data-toggle="modal" data-target="#addCsms" class="btn btn-primary"><i class="fa fa-plus"></i> Add new Step</button>
                <?php
                if(count($step) == 0){
                    $route = route('qhse.csms.index');
                } else {
                    $route = route('qhse.csms.view', ["type" => "detail", "id" => $csms->id]);
                }
                ?>
                <a href="{{ $route }}" class="btn btn-success "><i class="fa fa-arrow-left"></i></a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered table-hover display">
                        <thead>
                            <tr>
                                <th class="text-center" width="10%">#</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Input</th>
                                <th class="text-center" width="10%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($step as $i => $item)
                                <tr>
                                    <td align="center">{{ $i+1 }}</td>
                                    <td>
                                        <div class="row">
                                            <div class="col-12">
                                                <a href="{{ route('qhse.csms.input_step', $item->id) }}" class="btn btn-primary">{{ $item->name }}</a>
                                            </div>
                                            @if (count($step) > 1)
                                                <div class="col-12 mt-5">
                                                    <div class="btn-group">
                                                        @if ($i > 0)
                                                        <button type="button" {{ (isset($step[$i-1]) ? "onclick=change_order(".$step[$i-1]['id'].",".$item->id.")" : '') }} class="btn btn-icon btn-primary"><i class="fa fa-arrow-up"></i></button>
                                                        @endif

                                                        @if ($i < (count($step) - 1))
                                                        <button type="button" {{ (isset($step[$i+1]) ? "onclick=change_order(".$step[$i+1]['id'].",".$item->id.")" : '') }} class="btn btn-icon btn-danger"><i class="fa fa-arrow-down"></i></button>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td align="center">
                                        @if (isset($input_step[$item->id]))
                                            @foreach ($input_step[$item->id] as $input)
                                                <div class="row mb-5">
                                                    <div class="col-12">
                                                        <label for="" class="label label-inline label-primary">
                                                            @php
                                                                /** @var TYPE_NAME $value */
                                                                if ($input == 'ud'){
                                                                    $name = 'Upload Document';
                                                                } elseif ($input == 'ms'){
                                                                    $name = 'Meeting Schedule';
                                                                } elseif ($input == 'ol'){
                                                                    $name = 'Outbox List';
                                                                } elseif ($input == 'tt'){
                                                                    $name = 'Tasks';
                                                                } elseif ($input == 'ba'){
                                                                    $name = 'Berita Acara';
                                                                } elseif ($input == 'pe'){
                                                                    $name = 'Pernyataan Efektif dari OJK';
                                                                } elseif ($input == 'su'){
                                                                    $name = 'Table';
                                                                } elseif ($input == 'link'){
                                                                    $name = 'Cypher Links';
                                                                } else {
                                                                    $name = "";
                                                                }
                                                            @endphp
                                                            {{ $name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                              <label for="" class="label label-xl label-inline label-primary">N/A</label>
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
                <form action="{{ route('qhse.csms.add.step') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="" class="col-form-label col-3">Name</label>
                            <div class="col-9">
                                <input type="text" class="form-control" name="name" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_csms" value="{{ $csms->id }}">
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
                location.href = "{{ route('qhse.csms.change')}}/step/"+x+"/"+y
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
              location.href = "{{ route('qhse.csms.delete')}}/step/"+x
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
    })
</script>
@endsection
