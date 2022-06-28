@extends('layouts.template')

@section('content')
    <div class="card gutter-b card-custom card-stretch">
        <div class="card-header">
            <h3 class="card-title">Balance Sheet List</h3>
            <div class="card-toolbar">
                <a href="{{ route('bs.index') }}" class="btn btn-success"><i class="fa fa-search"></i>Search Balance Sheet</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered table-hover table-responsive-sm display">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Period</th>
                                <th class="text-center">Created At</th>
                                <th class="text-center">File</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list as $i => $item)
                                <tr>
                                    <td align="center">{{ $i+1 }}</td>
                                    <td align="center">
                                        <span class="label label-inline label-primary font-weight-bold font-size-lg">{{ date("d F Y", strtotime($item->date_from)) }} - {{ date("d F Y", strtotime($item->date_to)) }}</span>
                                    </td>
                                    <td align="center">
                                        {{ date("d F Y", strtotime($item->created_at)) }}
                                    </td>
                                    <td align="center">
                                        <a href="{{ asset($item->file) }}" class="btn btn-icon btn-xs btn-success" target="_blank" download>
                                            <i class="fa fa-file-download"></i>
                                        </a>
                                        <a href="{{ route('bs.list.delete', $item->id) }}" class="btn btn-icon btn-xs btn-danger" onclick="return confirm('delete?')"><i class="fa fa-trash"></i></a>
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
        $(document).ready(function(){
            $("table.display").DataTable()
        })
    </script>

@endsection
