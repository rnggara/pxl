<div class="row">
    <div class="col-12 mx-auto">
        <form action="{{ route('home.sell.confirmation') }}" method="post">
            <div class="card-body border pt-4 d-flex flex-column justify-content-between mb-10" >
                <!--begin::Toolbar-->
                <!--end::Toolbar-->
                <!--begin::User-->
                <div class="d-flex align-items-center justify-content-between head-plant">
                    <!--begin::Pic-->
                    <div class="flex-shrink-0 mr-4 mt-lg-0 mt-3">
                        <div class="symbol symbol-lg-150">
                            @if ($city['address'])
                            <img alt="Pic" src="{{ $city['address'] }}">
                            @else
                            <span class="symbol-label font-size-h4">
                                {{ $city['name'][0] }}
                            </span>
                            @endif
                        </div>
                    </div>
                    <!--end::Pic-->
                    <!--begin::Title-->
                    @if (in_array($ppstock['id'], $shops))
                    <div class="d-flex flex-column">
                        <span class="text-dark font-weight-bold font-size-h4 mb-0">{{ ucwords($city->name) }}</span>
                        <span class="font-weight-bold">{{ ucwords(strtolower($plant->name)) }} at {{ $ppstock['qty'] }}Nc/piece</span>
                    </div>
                    <div class="d-flex">
                        <input type="number" name="qty" class="form-control w-100px plant-qty" data-pps="{{ $ppstock['qty'] }}" min="1" value="1" max="{{ $max }}">
                    </div>
                    <div class="d-flex">
                        @csrf
                        <input type="hidden" name="city_id" value="{{ $city->id }}">
                        <input type="hidden" name="plant_id" value="{{ $plant->id }}">
                        <button type="submit" class="btn btn-primary p-10">
                            Sell
                            <br>
                            <span class="total-price">{{ ($ppstock['qty'] > 0) ? $ppstock['qty'] * 1 : 0 }} Nc</span>
                        </button>
                    </div>
                    @else
                    <div class="d-flex flex-column">
                        <span class="text-dark font-weight-bold font-size-h4 mb-0">{{ ucwords($city->name) }}</span>
                    </div>
                    <div class="d-flex">
                        <div class="alert alert-danger" role="alert">
                            You need to visit the shop first to reveal its price
                        </div>
                    </div>
                    @endif
                    <!--end::Title-->
                </div>
            </div>
        </form>
    </div>
</div>
