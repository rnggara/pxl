<div class="row">
    <div class="col-12 text-center">
        <img src="https://eironjayasejati.com/cypherApp/public_html/media/asset/cities/portal_banner.png" class="w-100" alt="">
    </div>
    <div class="col-12 text-center mt-5">
        <h2>
            Portal Gate
        </h2>
    </div>
    @foreach ($city as $item)
    <div class="col-md-6 col-xs-12">
        <div class="card-body border cursor-pointer bg-hover-secondary pt-4 d-flex flex-column justify-content-between mb-10" onclick="_select_city({{ $item->id }})">
            <!--begin::Toolbar-->
            <!--end::Toolbar-->
            <!--begin::User-->
            <div class="d-flex align-items-center">
                <!--begin::Pic-->
                <div class="flex-shrink-0 mr-4 mt-lg-0 mt-3">
                    <div class="symbol symbol-lg-150">
                        <img alt="Pic" src="{{ $item->address }}">
                    </div>
                </div>
                <!--end::Pic-->
                <!--begin::Title-->
                <div class="d-flex flex-column">
                    <span class="text-dark font-weight-bold font-size-h4 mb-0">{{ ucwords($item->name) }}</span>
                    <br /><p><img src='assets/byr/danger.png' height='20px' />level {{ $item->longitude }} - {{ $item->latitude }}</p>
                </div>
                <!--end::Title-->
            </div>
        </div>
    </div>
    @endforeach
</div>
