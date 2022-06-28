@extends('layouts.template')

@section('css')
<style>
    @media print {
    body * {
        visibility: hidden;
    }
    #print-page, #print-page * {
        visibility: visible;
    }
    #print-page {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        background-color: #fff;
        padding: 10px
    }
    }
</style>
@endsection

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">SP Detail</h3>
            <div class="card-toolbar btn-group">
                <button type="button" onclick="print()" class="btn btn-sm btn-primary"><i class="fa fa-print"></i>Print</button>
                <a href="{{ route('treasure.sp.index', $sp->bank) }}" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i></a>
            </div>
        </div>
        <div class="card-body">
            <div class="row" id="print-page">
                <div class="col-12">
                    <div class="row mb-5">
                        <div class="col-12">
                            <table width="100%">
                                <tr>
                                    <td>
                                        <table>
                                            <tr>
                                                <td>SP#</td>
                                                <td>: <b>{{ $sp->num }}</b></td>
                                            </tr>
                                            <tr>
                                                <td>From</td>
                                                <td>: <b>{{ date('d F Y', strtotime($sp->date1)) }}</b></td>
                                            </tr>
                                            <tr>
                                                <td>To</td>
                                                <td>: <b>{{ date('d F Y', strtotime($sp->date2)) }}</b></td>
                                            </tr>
                                            <tr>
                                                <td>Bank</td>
                                                <td>: <b>{{ $treasury->source }}</b></td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td align="right" class="view-hide">
                                        <img src="{{str_replace('public', 'public_html', asset('images/'.\Session::get('company_app_logo')))}}" style="max-height: 100px">
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <table class="table table-hover table-bordered display">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-left">Description</th>
                                <th class="text-center">Credit</th>
                                <th class="text-center">Debit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $balance = 0;
                                $cashIn = 0;
                                $cashOut = 0;
                            @endphp
                            @foreach ($history as $i => $item)
                                <tr>
                                    <td align="center">{{ $i+1 }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td align="right">{{ ($item->IDR > 0) ? number_format($item->IDR, 2) : number_format(0, 2) }}</td>
                                    <td align="right">{{ ($item->IDR < 0) ? number_format($item->IDR, 2) : number_format(0, 2) }}</td>
                                    @php
                                        $balance += $item->IDR;
                                        if ($item->IDR > 0){
                                            $cashIn += $item->IDR;
                                            $cashOut += 0;
                                        } else {
                                            $cashIn += 0;
                                            $cashOut += $item->IDR;
                                        }
                                    @endphp
                                </tr>
                            @endforeach
                        </tbody>
                        <tr>
                            <th colspan="2">Total</th>
                            <th class="text-right">{{number_format($cashIn, 2)}}</th>
                            <th class="text-right">{{number_format($cashOut, 2)}}</th>
                        </tr>
                        <tr>
                            <th colspan="3">Total Amount</th>
                            <th class="text-right">{{ number_format($balance, 2) }}</th>
                        </tr>
                        <tr>
                            <th colspan="3">Opening Balance</th>
                            <th class="text-right">{{ number_format($sp->saldo, 2) }}</th>
                        </tr>
                        <tr>
                            <th colspan="3">Current Balance</th>
                            <th class="text-right">{{ number_format($sp->saldo + $balance, 2) }}</th>
                        </tr>
                        <tr>
                            <th colspan="3">Hold Amount</th>
                            <th class="text-right">({{ number_format($treasury->actual_idr, 2) }})</th>
                        </tr>
                        <tr>
                            <th colspan="3">Available Balance</th>
                            <th class="text-right">{{ number_format(($sp->saldo + $balance) - $treasury->actual_idr, 2) }}</th>
                        </tr>
                        <tfoot>

                        </tfoot>
                    </table>
                </div>
                <div class="col-12 mt-10 view-hide">
                    <table class="table table-bordered">
                        <tr>
                            <td align="center">
                                Prepared By
                                <br><br><br><br><br><br><br><br>
                                _______________
                            </td>
                            <td align="center">
                                Acknowledge By
                                <br><br><br><br><br><br><br><br>
                                _______________
                            </td>
                            <td align="center">
                                Approved By
                                <br><br><br><br><br><br><br><br>
                                _______________
                            </td>
                        </tr>
                    </table>
                </div>
                @if (empty($sp->approved_by))
                <div class="col-12 text-right">
                    <button type="button" class="btn btn-primary" id="btn-approve">Approve</button>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
<script>
    $(document).ready(function(){
        $(".view-hide").css('visibility', "hidden")
        $("#btn-approve").click(function(){
            $.ajax({
                url : "{{ route('treasure.sp.approve') }}",
                type: "post",
                dataType: "json",
                startTime: performance.now(),
                data : {
                    _token: "{{ csrf_token() }}",
                    sp : "{{ $sp->id }}",
                    bank: "{{ $sp->bank }}"
                },
                success: function(response){
                    //Calculate the difference in milliseconds.
                    var time = performance.now() - this.startTime;

                    //Convert milliseconds to seconds.
                    var seconds = time / 1000;

                    //Round to 3 decimal places.
                    seconds = seconds.toFixed(3);

                    //Write the result to the HTML document.
                    var result = 'AJAX request took ' + seconds + ' seconds to complete.';
                    // document.body.innerHTML = result;
                    //Or log it to the console.
                    var nTimer = 2000 + time
                    Swal.fire({
                        title: 'Proccessing',
                        timer: 1500 + time,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading()
                            timerInterval = setInterval(() => {
                            const content = Swal.getContent()
                            if (content) {
                                const b = content.querySelector('b')
                                if (b) {
                                b.textContent = Swal.getTimerLeft()
                                }
                            }
                            }, 100)
                        }
                        }).then((result) => {
                        /* Read more about handling dismissals below */
                        if (response.error == 0){
                            Swal.fire({
                                title: 'Success',
                                icon: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload()
                                }
                            })
                        } else {
                            Swal.fire('Error occured', 'Please contact your administrator', 'error')
                        }
                    })
                }
            })
        })
        $(".display").DataTable({
            paging: false,
            bInfo: false,
            sorting: false,
            searching: false
        })
    })
</script>

@endsection
