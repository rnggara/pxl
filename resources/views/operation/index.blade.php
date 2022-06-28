@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">Operation Report</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <a href="{{ route('general.operation.templates') }}" class="btn btn-primary btn-sm"><i class="fa fa-chevron-circle-right"></i> Templates</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered table-hover display table-responsive-sm">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Project Code</th>
                                <th class="text-center">Project Name</th>
                                <th class="text-center">Settings</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($projects as $i => $item)
                                <tr>
                                    <td align="center">{{ $i+1 }}</td>
                                    <td align="center">
                                        <a href="{{ route('general.operation.setting', ['type' => 'report', 'id' => $item->id]) }}" >{{ $item->prefix ?? sprintf("%03d", $item->prj_code) }}</a>
                                    </td>
                                    <td align="center">{{ $item->prj_name }}</td>
                                    <td align="center">
                                        <a href="{{ route('general.operation.setting', ['type' => 'setting', 'id' => $item->id]) }}" class="btn btn-success">Setting</a>
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
