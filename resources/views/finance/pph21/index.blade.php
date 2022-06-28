@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>SPT PPH21</h3><br>
            </div>
        </div>
        <div class="card-body">
            <div class="row mx-auto">
                <div class="col-md-4 mx-auto">
                    <div class="form-group row">
                        <div class="col-md-9">
                            <select name="year" id="year" class="form-control select2">
                                @foreach($years as $year)
                                    <option value="{{$year}}" {{($year == date('Y')) ? "SELECTED" : ""}}>{{$year}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="button" id="btn-find-spt" class="btn btn-xs btn-light-primary"><i class="fa fa-search"></i> Search</button>
                        </div>
                    </div>
                </div>
            </div>
            <div id="result">

            </div>
        </div>
    </div>

@endsection

@section('custom_script')
    <script>
        $(document).ready(function(){
            $("#btn-find-spt").click(function(){
                $("#result").html("")
                $.ajax({
                    url: "{{route('pph21.find')}}",
                    type: "POST",
                    data: {
                        _token: "{{csrf_token()}}",
                        year: $("#year").val()
                    },
                    cache: false,
                    success: function(response){
                        $("#result").append(response)
                        $("table.display").DataTable()
                    }
                })
            })
            $("select.select2").select2({
                width: "100%"
            })

        })
    </script>
@endsection

