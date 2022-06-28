@extends('layouts.template')
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.css" type="text/css" media="screen" />
@endsection
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Schedule Payment - {{strtoupper("[".$treasury->currency."] ".$treasury->source)}}</h3><br>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    @if($sp->approved_date != null)
                        <a href="{{route('treasury.printsp', $sp->id)}}" data-fancybox data-options='{"type" : "iframe", "iframe" : {"preload" : false, "css" : {"width" : "1391px", "margin" : "15px", "overflow" : "auto"}}}' class="btn btn-xs btn-info"><i class="fa fa-eye"></i> Preview</a>
                        <a href="javascript:window.frames['treasury_print'].print();" class="btn btn-xs btn-primary"><i class="fa fa-print"></i> Print</a>
                        <iframe src="{{route('treasury.printsp', $sp->id)}}" id="treasury_print" name="treasury_print" style="display: none" width='950' height='0' frameborder='0'></iframe>
                    @endif
                </div>
                <a href="{{route('treasury.history', base64_encode($treasury->id))}}" class="btn btn-xs btn-light-success ml-3"><i class="fa fa-arrow-circle-left"></i></a>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div class="card card-custom m-5 bg-secondary">
                <div class="separator separator-solid separator-white opacity-20"></div>
                <div class="card-body">
                    <table>
                        <tr>
                            <td>No</td>
                            <td>: {{$sp->num}}</td>
                        </tr>
                        <tr>
                            <td>Date</td>
                            <td>: {{date('d F Y', strtotime($sp->date1))}} - {{date('d F Y', strtotime($sp->date2))}}</td>
                        </tr>
                        <tr>
                            <td>Bank</td>
                            <td>: {{$treasury->source}}</td>
                        </tr>
                        <tr>
                            <td>Currency</td>
                            <td>: {{$treasury->currency}}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="separator separator-dashed separator-border-2 separator-primary"></div>
            <div class="m-5">
                <table class="table display">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Bank</th>
                        <th class="text-center">Description</th>
                        <th class="text-center">Debit</th>
                        <th class="text-center">Credit</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sumdebit = 0;
                            $sumcredit = 0;
                        ?>
                        @foreach($his as $key => $value)
                            <tr>
                                <td align="center">{{$key + 1}}</td>
                                <td align="center">{{$value->date_input}}</td>
                                <td align="center">{{$treasury->source}}</td>
                                <td align="center">{{$value->description}}</td>
                                <td align="right">
                                    {{($value->IDR < 0) ? number_format(abs($value->IDR)) : number_format(0)}}
                                </td>
                                <td align="right">
                                    {{($value->IDR > 0) ? number_format(abs($value->IDR)) : number_format(0)}}
                                </td>
                                <?php
                                /** @var TYPE_NAME $value */
                                if ($value->IDR < 0){
                                    $sumdebit += $value->IDR;
                                    $sumcredit += 0;
                                } else {
                                    $sumdebit += 0;
                                    $sumcredit += $value->IDR;
                                }
                                ?>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td align="right" colspan="4">Sub Total</td>
                            <td align="right">{{number_format(abs($sumdebit))}}</td>
                            <td align="right">{{number_format(abs($sumcredit))}}</td>
                        </tr>
                        <tr>
                            <td align="right" colspan="4">Current Balance</td>
                            <td align="right"></td>
                            <td align="right">{{number_format(abs($sumcredit) - abs($sumdebit))}}</td>
                        </tr>
                        <tr>
                            <td align="right" colspan="4">Hold Amount</td>
                            <td align="right"></td>
                            <td align="right">({{number_format($treasury->account_idr)}})</td>
                        </tr>
                        <tr>
                            <td align="right" colspan="4">Available Balance</td>
                            <td align="right"></td>
                            <td align="right">{{number_format((abs($sumcredit) - abs($sumdebit)) - $treasury->account_idr)}}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="row m-5">
                @if($sp->approved_date == null)
                    <div class="col-md-12 text-right">
                        <button type="button" class="btn btn-primary btn-xs" id="btn-approve">Approve</button>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.js"></script>
    <script>
        $(document).ready(function(){
            $("table.display").DataTable({
                pageLength: 100,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                },
                lengthChange: false,
                pagination: false,
                bInfo: false,
                bPaginate: false,
                bFilter: false,
                ordering: false
            })

            $("a.iframe").fancybox({
                'frameWidth': 500, // set the width
                'frameHeight': 100, // set the height
                fitToView   : false,
                autoSize    : false,
                'transitionIn'	: 'none',
                'transitionOut'	: 'none',
                'type':'iframe'
            })

            $("#btn-approve").click(function(){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Approve current SP!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, approve it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url : "{{URL::route('treasury.apprsp')}}",
                            type: "post",
                            dataType: "json",
                            data : {
                                _token : "{{csrf_token()}}",
                                id : "{{$sp->id}}"
                            },
                            cache: "false",
                            success: function(response){
                                if (response.error == 0){
                                    location.reload()
                                } else {
                                    Swal.fire('Error occured', 'Please contact your administrator!', 'error')
                                }
                            }
                        })
                    }
                })
            })

        })
    </script>
@endsection
