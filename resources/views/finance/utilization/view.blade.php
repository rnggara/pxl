@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Utilization Detail - {{$util->subject}}</h3><br>

            </div>
        </div>
        <div class="card-body">
            {{--            <h5><span class="span">This page contains a list of Travel Order which has been formed.</span></h5>--}}
            <table class="table display">
                <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-left">Utilization Name</th>
                    <th class="text-center">Amount</th>
                    <th class="text-center">Actual Amount</th>
                    <th class="text-center">Occurance Date</th>
                    <th class="text-center">Progress</th>
                    <th class="text-center"></th>
                </tr>
                </thead>
                <tbody>
                    @foreach($items as $key => $item)
                        <tr>
                            <td align="center">{{$key + 1}}</td>
                            <td>{{$item->subject}}</td>
                            <td align="center">{{$item->currency.". ".number_format($item->amount, 2)}}</td>
                            <td align="center">
                                @if($item->amount_back == 0)
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="number" id="amount{{$item->id}}" value="0" class="form-control"/>
                                            <div class="input-group-append">
                                                <button class="btn btn-secondary" onclick="button_change_amount({{$item->id}})" id="btn-go{{$item->id}}" type="button">Go</button>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    {{$item->currency.". ".number_format($item->amount_back, 2)}}
                                @endif
                            </td>
                            <td align="center">
                                {{date('d F Y', strtotime($item->pay_date))}}
                            </td>
                            <td align="center">{{$item->progress}}</td>
                            <td align="center">
                                <a href="{{URL::route('util.delete.instance', $item->id)}}" class="btn btn-danger btn-xs btn-icon"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

@section('custom_script')
    <script src="{{asset('theme/assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js?v=7.0.5')}}"></script>
    <script>

        function button_change_amount(x){
            console.log('clicked')
            var amount = document.getElementById("amount" + x).value
            console.log(amount)
            $.ajax({
                url: "{{URL::route('util.change_amount_instance')}}",
                type: "post",
                dataType: "json",
                data: {
                    '_token' : '{{csrf_token()}}',
                    'id' : x,
                    'amount' : amount
                },
                cache: false,
                success: function(response){
                    if (response.error == 0){
                        location.reload()
                    } else {
                        Swal.fire("Error occured", "Please contact your administrator", 'error')
                    }
                }
            })
        }
        $(document).ready(function(){

            $("table.display").DataTable({
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            })
            $("select.select2").select2({
                width: "100%"
            })

            $("#custom-field").hide()

            $("#rtype").change(function(){
                var v = this.value
                if (v == "custom"){
                    $("#custom-field").show()
                } else {
                    $("#custom-field").hide()
                }
            })

        })
    </script>
@endsection

