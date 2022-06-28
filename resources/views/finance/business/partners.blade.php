@extends('layouts.template')

@section('css')
<style>
        @media print {
            @page {
                size : landscape;
            }
            body * {
                visibility: hidden;
            }

            #search-div, #search-div * {
                visibility: visible;
            }

            .print-hide {
                display: none;
            }

            #search-div {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    </style>
@endsection

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Business Balance Partners</h3><br>

            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button href="" id="btn-export" class="btn btn-sm btn-light-success btn-icon"><i class="fa fa-file-csv"></i></button>
                    <button type="button" onclick="print()" id="btn-print" class="btn btn-sm btn-primary btn-icon"><i class="fa fa-print"></i></button>
                    <a href="{{route('business.index')}}" class="btn btn-sm btn-success btn-icon"><i class="fa fa-arrow-left"></i></a>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <form action="{{ route('business.balance.partners.search') }}" method="post" id="form-export">
                        @csrf
                           <div class="form-group row">
                                <div class="col-md-4">
                                    <select name="mnth" id="mnth" class="form-control select2">
                                        @foreach($mnth as $key => $item)
                                            <option value="{{$key}}" {{($key == date('m')) ? "SELECTED" : ""}}>{{$item}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select name="year" id="year" class="form-control select2">
                                        @for($i=$yearbefore; $i <= $yearafter; $i++)
                                            <option value="{{$i}}" {{($i == date("Y")) ? "SELECTED" : ""}}>{{$i}}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="hidden" name="view" value="export">
                                    <button type="button" class="btn btn-sm btn-success" id="search-payment"><i class="fa fa-search"></i> Search</button>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
            {{--            <h5><span class="span">This page contains a list of Travel Order which has been formed.</span></h5>--}}
            <div class="row" id="search-div">

            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{asset('theme/assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js?v=7.0.5')}}"></script>
    <script>

        $(document).ready(function(){

            $("#btn-print").hide()
            $("#btn-export").hide()

            $("#search-payment").click(function(){
                var mnth = $("#mnth option:selected").val()
                var year = $("#year option:selected").val()
                $.ajax({
                    url: "{{route('business.balance.partners.search')}}",
                    type: "post",
                    data: {
                        _token: "{{csrf_token()}}",
                        mnth: mnth,
                        year: year
                    },
                    success: function (response) {
                        $("#search-div").html(" ")
                        $("#search-div").append(response)
                        $("#btn-print").show()
                        $("#btn-export").show()

                        $("#btn-export").click(function(){
                            $("#form-export").submit()
                        })
                    }
                })
            })

            $("table.display").DataTable({
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            })
            $("select.select2").select2({
                width: "100%"
            })
        })
    </script>
@endsection
