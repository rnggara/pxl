@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <a href="#" class="text-black-50">Pressure Vessel</a>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{route('te.pv.export')}}" target="_blank" class="btn btn-success"><i class="fa fa-file-csv"></i>Export</a>
                    <a href="{{route('te.pv.add_record')}}" class="btn btn-primary"><i class="fa fa-plus"></i>Add Record</a>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-5 col-sm-5">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mx-auto">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important;">
                            <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">ID</th>
                                @foreach ($columns as $item)
                                    @if (!in_array($item, array('id','created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'company_id')))
                                        <th class="text-center">{{ ucwords(str_replace("_", " ", $item)) }}</th>
                                    @endif
                                @endforeach
                                <th class="text-center">Thinning RBI Date</th>
                                <th nowrap="nowrap" class="text-center"></th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $key => $item)
                                    <tr>
                                        <td align="center">{{ $key + 1 }}</td>
                                        <td align="center" class="text-nowrap">
                                            <a href="{{ route('te.pv.view', $item->id) }}">ID - {{ sprintf("%03d", $item->id) }}</a>
                                        </td>
                                        @foreach ($columns as $col)
                                            @if (!in_array($col, array('id','created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'company_id')))
                                                <td align="center">{{ $item[$col] }}</td>
                                            @endif
                                        @endforeach
                                        <td align="center">
                                            {{ date("D, d F Y", strtotime($item->thinning_df_rbi_date)) }}
                                        </td>
                                        <td align="center" class="text-nowrap">
                                            <a href="{{ route('te.pv.chart', $item->tag) }}" class="btn btn-xs btn-info btn-icon" data-toggle="tooltip" title="Line Chart"><i class="fa fa-chart-line"></i></a>
                                            <a href="{{ route('te.pv.duplicate', base64_encode(Str::random(6)."-".$item->id)) }}" class="btn btn-icon btn-xs btn-primary" data-toggle="tooltip" title="Duplicate"><i class="fa fa-copy"></i></a>
                                            <a href="{{ route('te.pv.delete', $item->id) }}" data-toggle="tooltip" title="Delete" onclick="return confirm('delete?')" class="btn btn-icon btn-xs btn-danger"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script>
        function button_delete(x){
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
                    $.ajax({
                        url : "{{URL::route('te.el.deleteCategory')}}/" + x,
                        type: "get",
                        dataType: "json",
                        cache: "false",
                        success: function(response){
                            if (response.error == 0){
                                location.reload()
                            } else {
                                Swal.fire('Error occured', 'Please contact your administrator!', 'error')
                            }
                        }
                    })
                }
            })
        }

        $(document).ready(function () {
            $("select.select2").select2({
                width: "100%"
            })

            @if (\Session::get('success'))
                Swal.fire('Success', '{{ \Session::get('success') }}', 'success')
            @endif

            @if (\Session::get('error'))
                Swal.fire('Error', '{{ \Session::get('error') }}', 'error')
            @endif

            @if (\Session::get('delete'))
                Swal.fire('Delete', '{{ \Session::get('delete') }}', 'success')
            @endif


            $('.display').DataTable({
            });
        })

    </script>
@endsection
