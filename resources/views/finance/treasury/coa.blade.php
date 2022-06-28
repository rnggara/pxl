@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>TC Assignment - {{strtoupper("[".$treasury->currency."] ".$treasury->source)}}</h3><br>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{URL::route('treasury.history', base64_encode($treasury->id))}}" class="btn btn-success btn-xs"><i class="fa fa-arrow-circle-left"></i></a>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div class="card card-custom m-5">
                <div class="separator separator-solid separator-white opacity-20"></div>
                <div class="card-body">
                    <table>
                        <tr>
                            <td><label for="">Cash In</label></td>
                            <td>&nbsp;<label for="">:</label>&nbsp;</td>
                            <td width="70%">
                                <label class="text-success">
                                    {{(empty($cashIn[$treasury->id])) ? number_format(0, 2) : number_format(array_sum($cashIn[$treasury->id]), 2)}}
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="">Cash Out</label></td>
                            <td>&nbsp;<label for="">:</label>&nbsp;</td>
                            <td>
                                <label class="text-danger">
                                    {{(empty($cashOut[$treasury->id])) ? number_format(0, 2) : number_format(array_sum($cashOut[$treasury->id]), 2)}}
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="">Only Show Year</label>
                            </td>
                            <td></td>
                            <td>
                                <div class="row">
                                    <div class="col-md-6">
                                        <select name="year" id="" class="form-control">
                                            @foreach($y as $value)
                                                <option value="{{$value}}" {{($value == date('Y')) ? "selected" : ""}}>{{$value}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="button" class="btn btn-success btn-xs">Set Year</button>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="separator separator-dashed separator-border-2 separator-primary"></div>
            <div class="alert alert-warning m-5">
                <i class="fa fa-info-circle text-white"></i>
            </div>
            <div class="m-5">
                <form action="{{URL::route('treasury.setcoa')}}" method="POST">
                    @csrf
                    <table class="table display">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Date</th>
                            <th class="text-center">Activity</th>
                            <th class="text-center">Credit</th>
                            <th class="text-center">Debit</th>
                            <th class="text-center">Balance</th>
                            <th class="text-center">File</th>
                            <th class="text-center">TC</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tre_his as $key => $value)
                            <tr>
                                <td align="center">{{$key + 1}}</td>
                                <td align="center">{{date('d F Y', strtotime($value->date_input))}}</td>
                                <td align="center">{{$value->description}}</td>
                                <td align="center">
                                    <label class="text-success">{{($value->IDR > 0) ? number_format($value->IDR, 2) : number_format(0, 2)}}</label>
                                </td>
                                <td align="center">
                                    <label class="text-danger">{{($value->IDR < 0) ? number_format(str_replace("-", "", $value->IDR), 2) : number_format(0, 2)}}</label>
                                </td>
                                <td align="center">
                                    <label class="font-weight-bold">{{number_format($balance, 2)}}</label>
                                    <?php $balance -= $value->IDR ?>
                                </td>
                                <td align="center" class="text-center">
                                    @if(isset($coa_his[$value->id]['file_hash']) && !empty($coa_his[$value->id]['file_hash']))
                                        <a href="{{route('download', $coa_his[$value->id]['file_hash'])}}" target="_blank" class="fa fa-download"></a>
                                    @else
                                        no file attached
                                    @endif
                                </td>
                                <td align="center" nowrap="nowrap">
                                    @if(isset($coa_his[$value->id]['coa']))
                                        <a href="{{route('treasury.viewcoa', $value->id)}}" class="btn btn-xs btn-secondary btn-icon"><i class="fa fa-check"></i></a>
                                    @else
                                        <a href="{{route('treasury.viewcoa', $value->id)}}" class="btn btn-xs btn-primary"><i class="fa fa-clipboard-check"></i> Assign</a>
                                    @endif
{{--                                    <input type="text" class="form-control autocomplete" name="coa[]" id="coa{{$value->id}}">--}}
{{--                                    <input type="hidden" id="id_his{{$key + 1}}" value="{{$value->id}}">--}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="6"></td>
                                <td align="right">
                                    @foreach($tre_his as $value)
                                        <input type="hidden" name="idHis[]" value="{{$value->id}}">
                                        <input type="hidden" name="coa_code[{{$value->id}}]" value="" id="coa_code{{$value->id}}">
                                    @endforeach
                                        <input type="hidden" name="id_t" value="{{$treasury->id}}">
                                    <button class="btn btn-success btn-xs"><i class="fa fa-check"></i> Save</button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{asset('theme/assets/js/pages/crud/forms/widgets/typeahead.js?v=7.0.5')}}"></script>
    <link href="{{asset('theme/jquery-ui/jquery-ui.css')}}" rel="Stylesheet">
    <script src="{{asset('theme/jquery-ui/jquery-ui.js')}}"></script>
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
        function button_reject(x){
            Swal.fire({
                title: "Reject",
                text: "Are you sure you want to reject?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Submit",
                cancelButtonText: "Cancel",
                reverseButtons: true,
            }).then(function(result){
                if(result.value){
                    $.ajax({
                        url: "{{URL::route('treasury.reject')}}",
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

            $("input[type=text]").each(function(i){
                $(this).autocomplete({
                    source: "{{route('coa.get')}}",
                    minLength: 1,
                    select: function(event, ui){
                        $(this).val(ui.item.label)
                    }
                })
                $(this).change(function(){
                    var id_his = "#id_his" + i
                    var id = $(id_his).val()
                    var coaEl = "#coa_code" + id
                    var txt = $(this).val().split(" ")
                    var newtxt = txt[0].replace(/\[|\]/g,'')
                    $(coaEl).val(newtxt)
                    console.log($(coaEl).val())
                })
            })
            $("table.display").DataTable({
                pageLength: 100,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            })
        })
    </script>
@endsection
