<div class="row">
    <div class="col-12 text-center">
        <!--<img src="{{ $city->address }}" class="w-50" alt="">-->
        <img src="https://eironjayasejati.com/cypherApp/public_html/media/asset/cities/market_banner.png" class="w-100" alt="">
    </div>
    <!--<div class="col-12 text-center mt-5">-->
    <!--    <h2>-->
    <!--        {{ $city->name }}-->
    <!--    </h2>-->
    <!--</div>-->
    <div class="col-12 text-center mt-5">
        <h2>
            Market District
        </h2>
    </div>
    <div class="col-12">
        <table class="table table-bordered table-hover" id="table-plants">
            <thead>
                <tr>
                    <th>Shops</th>
                    <th>Demand</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($qty as $item)
                    <tr>
                        <td>
                            @if ($item->picture)
                                <img alt="Pic" width='30px' src="{{ str_replace("public", "public_html", asset('media/asset/'.$item->picture)) }}">
                            @endif
                            &nbsp;&nbsp;{{ $item->item_name }}
                        </td>
                        <td align="center">
                            {{ $item->demand }}
                            <br>
                            Your daily quota is : {{ $item->quota }}
                        </td>
                        <td align="center">
                            @if (in_array($item->id, $shops))
                                {{ number_format($item->qty) }} <img src='images/n_c_icon.png' height='15px' /> Nc
                            @else
                                @if (\Auth::user()->open_shop_credit > 0)
                                    <form action="{{ route('home.city.visit') }}" method="post">
                                        <input type="hidden" name="id_qty_wh" value="{{ $item->id }}">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm" name="submit" value="balance">
                                            Visit ( {{ floor($item->price_min / 1) }} <img src='images/n_c_icon.png' height='15px' /> Nc)
                                        </button>
                                        <br>
                                        <button type="submit" class="btn btn-primary btn-sm mt-5" name="submit" value="energy">
                                            Visit ( 1 <i class="fas fa-bolt text-warning"></i>)
                                        </button>
                                    </form>
                                @else
                                    You already used your daily limit to visit
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
