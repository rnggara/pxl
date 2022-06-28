<div class="row">
    @foreach ($plants as $item)
        <div class="col-md-4 col-xs-12">
            @if ($item->id == 1 || isset($my_seed[$item->id]))
            <form action="{{ route('home.plants.select') }}" method="post">
                <div class="card-body border pt-4 d-flex flex-column justify-content-between mb-10" >
                    <!--begin::Toolbar-->
                    <!--end::Toolbar-->
                    <!--begin::User-->
                    <div class="d-flex align-items-center">
                        <!--begin::Pic-->
                        <div class="flex-shrink-0 mr-4 mt-lg-0 mt-3">
                            <div class="symbol symbol-lg-150">
                                @if ($item->picture)
                                <img alt="Pic" src="{{ str_replace("public", "public_html", asset('media/asset/'.$item->picture)) }}">
                                @else
                                <span class="symbol-label font-size-h4">
                                    {{ $item->name[0] }}
                                </span>
                                @endif
                            </div>
                        </div>
                        <!--end::Pic-->
                        <!--begin::Title-->
                        <div class="d-flex flex-column">
                            <span class="text-dark font-weight-bold font-size-h4 mb-0">{{ ucwords($item->name) }}</span>
                            <span class="text-muted font-weight-bold">Ripe in : {{ $item->minimal_stock }} minutes</span>
                        </div>
                        <!--end::Title-->
                    </div>
                    <input type="hidden" name="item_id" value="{{ $item->id }}">
                    <input type="hidden" name="timezone" class="tmz">
                    @csrf
                    <button type="submit" class="btn btn-block btn-sm btn-light-success font-weight-bolder text-uppercase py-4">Plant!! {{ ($item->id != 1) ? "-1 Seed" : "" }}</button>
                </div>
            </form>
            @else
            <div class="card-body border pt-4 d-flex flex-column justify-content-between mb-10" >
                <!--begin::Toolbar-->
                <!--end::Toolbar-->
                <!--begin::User-->
                <div class="d-flex align-items-center">
                    <!--begin::Pic-->
                    <div class="flex-shrink-0 mr-4 mt-lg-0 mt-3">
                        <div class="symbol symbol-lg-150">
                            @if ($item->picture)
                            <img alt="Pic" src="{{ str_replace("public", "public_html", asset('media/asset/'.$item->picture)) }}">
                            @else
                            <span class="symbol-label font-size-h4">
                                {{ $item->name[0] }}
                            </span>
                            @endif
                        </div>
                    </div>
                    <!--end::Pic-->
                    <!--begin::Title-->
                    <div class="d-flex flex-column">
                        <span class="text-dark font-weight-bold font-size-h4 mb-0">{{ ucwords($item->name) }}</span>
                        <span class="text-muted font-weight-bold">Ripe in : {{ $item->minimal_stock }} minutes</span>
                    </div>
                    <!--end::Title-->
                </div>
                @csrf
                <button type="button" style="cursor: no-drop" class="btn btn-block btn-sm btn-secondary font-weight-bolder text-uppercase py-4 disabled">Need Seed</button>
            </div>
            @endif
        </div>
    @endforeach
</div>
