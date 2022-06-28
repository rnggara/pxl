@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Schedule Payment</h3> <br>

            </div>
            <div class="card-toolbar">
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div class="card card-custom bg-primary m-5">
                <div class="separator separator-solid separator-white opacity-20"></div>
                <div class="card-header">
                    <div class="card-title text-white">
                        <span class="">Date : {{date('d F Y', strtotime($date))}}</span>
                    </div>
                </div>
                <div class="card-body text-white">

                </div>
            </div>
            <div class="separator separator-dashed separator-border-2 separator-primary"></div>
            <div class="m-5">
                <form method="post" action="{{URL::route('sp.confirm')}}">
                    @csrf
                    @foreach($type as $key => $value)
                        <h3>{{strtoupper($value)}}</h3>
                        <hr>
                        <table class="table display table-responsive-xl">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    @if($value == "INVOICE IN")
                                        <th class="text-center" style="width:150px">
                                            @switch($value)
                                                @case("INVOICE IN")
                                                    Paper Number
                                                    @break
                                                @case("LEASING")
                                                    Subject
                                                    @break
                                                @case("LOAN")
                                                    Subject
                                                    @break
                                                @case("UTIL")
                                                    Subject
                                                    @break
                                                @case("SALARY")
                                                    Subject
                                                    @break
                                            @endswitch
                                        </th>
                                    @endif
                                    <th class="text-center">
                                        @switch($value)
                                            @case("INVOICE IN")
                                                Supplier
                                                @break
                                            @case("LEASING")
                                                Subject
                                                @break
                                            @case("LOAN")
                                                Subject
                                                @break
                                            @case("UTIL")
                                                Subject
                                                @break
                                            @case("SALARY")
                                                Subject
                                                @break
                                        @endswitch
                                    </th>
                                    <th class="text-center">
                                        @switch($value)
                                            @case("INVOICE IN")
                                                Bank Account
                                                @break
                                            @case("LEASING")
                                                Vendor
                                                @break
                                            @case("LOAN")
                                                Bank
                                                @break
                                            @case("UTIL")
                                                Category
                                                @break
                                            @case("SALARY")
                                                Periode
                                                @break
                                        @endswitch
                                    </th>
                                    <th class="text-center">
                                        Amount
                                    </th>
                                    <th class="text-center">
                                        Bank Deposit
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i[$key] = 0; ?>
                                @foreach($items as $item)
                                    @if($item['type'] == $value)
                                        <tr>
                                            <td align="center">{{$i[$key] + 1}} <?php $i[$key]++ ?></td>
                                            @if($value == "INVOICE IN")
                                                <td class="text-center" style="width:150px">
                                                    @switch($value)
                                                        @case("INVOICE IN")
                                                            {{$item['paper']}}
                                                        @break
                                                    @endswitch
                                                </td>
                                            @endif
                                            <td class="text-center">
                                                {!! $item['row1'] !!}
                                            </td>
                                            <td class="text-center">
                                                {!! $item['row2'] !!}
                                            </td>
                                            <td class="text-center">
                                                {{number_format($item['row3'], 2)}}
                                                <br>
                                                <span class="text-danger font-size-sm">* Insert IDR converted value if the amount is in another currency</span>
                                                <input type="text" class="form-control number" name="_to_idr[]" placeholder="converted value to IDR">
                                                <br>
                                            </td>
                                            <td class="text-center">
                                                <input type="hidden" name="id[]" value="{{$item['id']}}">
                                                <input type="hidden" name="type[]" value="{{$item['type']}}">
                                                <select name="source[]" class="form-control select2 src" id="">
                                                    <option value="">SELECT SOURCE</option>
                                                    @foreach($source as $bank)
                                                        <option value="{{$bank->id}}">{{"[".$bank->currency."] ".$bank->source}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    @endforeach
                    <div class="m-5">
                        <div class="row">
                            <span class="col-md-8"></span>
                            <div class="col-md-4">
                                <input type="hidden" name="date_input" value="{{$date}}">
                                <button type="submit" id="btn-save" class="btn btn-success btn-xs"><i class="fa fa-check"></i> Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{ asset("assets/jquery-number/jquery.number.js") }}"></script>
    <script>
        function button_approve(x){
            Swal.fire({
                title: "Approve",
                text: "Are you sure you want to approve?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Submit",
                cancelButtonText: "Cancel",
                reverseButtons: true,
            }).then(function(result){
                if(result.value){
                    $.ajax({
                        url: "{{URL::route('treasury.approve')}}",
                        type: "POST",
                        dataType: "json",
                        data: {
                            '_token' : '{{csrf_token()}}',
                            'val' : x
                        },
                        cache: false,
                        success: function(response){
                            if (response.error == 0) {
                                location.reload()
                            } else {
                                Swal.fire({
                                    title: "Error Occured",
                                    icon: "error"
                                })
                            }
                        }
                    })
                }
            })
        }
        $(document).ready(function(){
            $("select.select2").select2({
                width: "100%"
            })
            $("table.display").DataTable({
                paging: false,
                bInfo: false,
                searching: false,
                sorting: false,
            })
            $(".number").number(true, 2)
            $("#btn-save").click(function(e){
            e.preventDefault()
            var form = $(this).parents("form")
            var source = $("select.src")
            is_selected = 0
            source.each(function(){
                if($(this).val() != ""){
                    is_selected++
                }
            })
            if(is_selected > 0){
                // form submit
                form.submit()
                $(this).prop('disabled', true)
            } else {
                Swal.fire("Source required", "Please select minimal 1 source", "error")
            }
            console.log(is_selected)
        })
        })
    </script>
@endsection
