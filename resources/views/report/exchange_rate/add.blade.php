@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">Insert New Currency Exchange Value</h3>
            <div class="card-toolbar">
                <div class="btn-group">

                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('report.er.insert_save') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-8 mx-auto">
                        @php
                            $curr = json_decode($list_currency, true)
                        @endphp
                        <div class="row">
                            @foreach ($curr as $key => $item)
                                <div class="col-3 p-5">
                                    <div class="form-group">
                                        <label class="col-form-label">{{ $key }} -> IDR</label>
                                        <input type="text" class="form-control number" name="curr[{{ $key }}]" value="{{ isset($rates[$key]) ? $rates[$key] : 0 }}">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12 p-5 text-right">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{ asset('assets/jquery-number/jquery.number.js') }}"></script>
    <script>
        $(document).ready(function(){
            $(".number").number(true, 2)
        })
    </script>
@endsection
