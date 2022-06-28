@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">My Zakat Mal</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3 mx-auto">
                            <label for="" class="col-form-label font-weight-bold">{{ date("d F Y") }}</label>
                            <div class="form-group input-group">
                                <input type="text" class="form-control" readonly value="{{ number_format($balance, 2) }}">
                                @if($balance != 0)
                                <div class="input-group-append">
                                    <button type="button" data-toggle="modal" data-target="#modalPay" class="btn btn-primary">Pay</button>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-8 mx-auto">
                    <table class="table table-bordered table-hover display">
                        <thead>
                            <tr>
                                <th class="text-center">Date</th>
                                <th class="text-center">Description</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $bl = $balance;
                            @endphp
                            @foreach ($zakat as $i => $item)
                                <tr>
                                    <td align="center">{{ date("d F Y", strtotime($item->created_at)) }}</td>
                                    <td>{!! $item->description !!}</td>
                                    <td align="right">{{ number_format($item->amount, 2) }}</td>
                                    <td align="right">{{ number_format($bl, 2) }}</td>
                                </tr>
                                @php
                                    $bl -= $item->amount;
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalPay" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title">Payment Zakat</h1>
                </div>
                <form action="{{ route('user.pay.zakat') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="" class="col-form-label col-3">Amount</label>
                            <div class="col-9">
                                <input type="text" class="form-control number" readonly value="{{ number_format($balance, 2) }}" id="amount">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-form-label col-3">Payment</label>
                            <div class="col-9">
                                <input type="text" class="form-control number" name="payment_amount" id="payment">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Pay</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{ asset('assets/jquery-number/jquery.number.js') }}"></script>
    <script>
        $(document).ready(function(){
            $(".number").number(true, 2)

            $("#payment").on('change', function(){
                var val = $(this).val().replaceAll(",", "")
                var amount = $("#amount").val().replaceAll(",", "")
                if(parseInt(val) > parseInt(amount)){
                    $(this).val(amount, 2)
                }
            })

            $("table.display").DataTable({
                sorting: false,
                bInfo: false,
                searching: false,
                lengthChange: false
            })

            @if(\Session::get('msg') == "success")
            Swal.fire("Terima Kasih", "Semoga selalu diberi keberkahan", "success");
            @endif
        })
    </script>
@endsection
