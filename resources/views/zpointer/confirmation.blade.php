@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">Sell Confirmation {{ ucwords(strtolower($plant->name)) }} at {{ $city->name }}</h3>
        </div>
        <div class="card-body">
            <div class="row">
                @if ($quota == 1)
                    @if ($confirmation)
                    <div class="col-4 mx-auto border p-10">
                        <form action="{{ route('home.sell.plant') }}" method="post">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex flex-column">
                                    <span class="">Current Qty : {{ $item_user->qty }}</span>
                                    <span class="">Qty to sell : {{ $qty }}</span>
                                    <span class="">Sell at : {{ $price_city->qty }} Nc/piece</span>
                                    <span class="">Profit : {{ $qty * $price_city->qty }} Nc</span>
                                    <span class="">Balance after sale : {{ $balance + ($qty * $price_city->qty) }} Nc</span>
                                </div>
                                <div class="d-flex">
                                    @csrf
                                    <input type="hidden" name="city_id" value="{{ $city->id }}">
                                    <input type="hidden" name="plant_id" value="{{ $plant->id }}">
                                    <input type="hidden" name="qty" value="{{ $qty }}">
                                    <input type="hidden" name="pcity" value="{{ $price_city->id }}">
                                    {{-- <input type="hidden" name="amount" value="{{ $qty * $price_city->qty }}"> --}}
                                    <button type="submit" class="btn btn-primary btn-block">
                                        Confirm
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    @else
                    <div class="col-4 mx-auto p-10">
                        <div class="alert alert-custom alert-outline-danger fade show mb-5" role="alert">
                            <div class="alert-icon"><i class="flaticon-warning"></i></div>
                            <div class="alert-text">
                                Insufficient Quantity.
                                <a href="{{ route("home") }}" class="btn btn-outline-danger btn-sm">Back to Home</a>
                            </div>
                        </div>
                    </div>
                    @endif
                @else
                <div class="col-4 mx-auto p-10">
                    <div class="alert alert-custom alert-outline-danger fade show mb-5" role="alert">
                        <div class="alert-icon"><i class="flaticon-warning"></i></div>
                        <div class="alert-text">
                            {{ ($quota == -1) ? "Your quantity sell is exceed your daily quota" : "You already used your daily quota" }}
                            <a href="{{ route("home") }}" class="btn btn-outline-danger btn-sm">Back to Home</a>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        $(document).ready(function(){

        })
    </script>
@endsection
