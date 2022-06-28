@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">Operation Report - {{ $project->prj_name }}</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <a href="{{ route('general.operation.report_add', $project->id) }}" class="btn btn-primary btn-sm">Add Record</button>
                    <a href="{{ route('general.operation.index') }}" class="btn btn-sm btn-icon btn-success"><i class="fa fa-arrow-left"></i></a>
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
                                <th class="text-center">Report By</th>
                                <th class="text-center">Report Date</th>
                                <th class="text-center">Division</th>
                                <th class="text-center">Subject</th>
                                <th class="text-center">Approval</th>
                                <th class="text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reports as $i => $item)
                                <tr>
                                    <td align="center">{{ $i+1 }}</td>
                                    <td align="center">
                                        {{ $item->created_by }}
                                        <a href="{{ route('general.operation.report.print', $item->id) }}" target="_blank"><i class="fa fa-print text-primary text-hover-dark-50"></i></a>
                                    </td>
                                    <td align="center">{{ $item->report_date }}</td>
                                    <td align="center">{{ strtoupper($project->prefix) }}</td>
                                    <td align="center">
                                        <a href="{{ route('general.operation.report.detail', $item->id) }}?act=view">{{ $item->report_no }}</a>
                                    </td>
                                    <td align="center">
                                        @if (empty($item->approved_at))
                                            <a href="{{ route('general.operation.report.detail', $item->id) }}?act=appr">waiting</a>
                                        @else
                                            Approved at {{ $item->approved_at }} <br> by {{ $item->approved_by }}
                                        @endif
                                    </td>
                                    <td align="center">
                                        <a href="{{ route('general.operation.report.delete', $item->id) }}" onclick="return confirm('delete?')" class="btn btn-xs btn-icon btn-danger"><i class="fa fa-trash"></i></a>
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
