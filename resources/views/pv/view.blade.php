@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">Detail Pressure Vessel [{{ "ID - ".sprintf("%03d", $ref->id) }}]</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <a href="{{ route('te.pv.index') }}" class="btn btn-sm btn-icon btn-success"><i class="fa fa-arrow-left"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        @foreach ($row as $key => $item)
            <div class="col-md-6 col-sm-12">
                <div class="card card-custom gutter-b card-stretch">
                    <div class="card-header">
                        <h3 class="card-title">{{ str_replace("_", " ", $key) }}</h3>
                    </div>
                    <div class="card-body">
                        @foreach ($item as $field)
                            <div class="row">
                                <label class="col-form-label col-md-3 col-sm-12">{{ ucwords(str_replace("_", " ", $field)) }}</label>
                                <label class="col-form-label col-md-9 col-sm-12 font-weight-bold">: {{ $ref->$field ?? "N/A" }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="row">
        @foreach ($cal as $key => $item)
            <div class="col-md-4 col-sm-12">
                <div class="card card-custom gutter-b card-stretch">
                    <div class="card-header">
                        <h3 class="card-title">{{ str_replace("_", " ", $key) }}</h3>
                    </div>
                    <div class="card-body">
                        @foreach ($item as $field)
                            <div class="row">
                                <label class="col-form-label col-md-4 col-sm-12">{{ ucwords(str_replace("_", " ", $field)) }}</label>
                                <label class="col-form-label col-md-8 col-sm-12 font-weight-bold">: {{ $ref->$field ?? "N/A" }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@section('custom_script')
    <script>
        $(document).ready(function(){

        })
    </script>
@endsection
