<div class="row">
    <div class="mx-auto">
        <div class="card-body border pt-4 d-flex flex-column justify-content-between mb-10" >
            <!--begin::Toolbar-->
            <!--end::Toolbar-->
            <!--begin::User-->
            <div class="d-flex align-items-center">
                <!--begin::Pic-->
                <div class="flex-shrink-0 mr-4 mt-lg-0 mt-3">
                    <div class="symbol symbol-lg-150">
                        @if ($plant->picture)
                        <img alt="Pic" src="{{ str_replace("public", "public_html", asset('media/asset/'.$plant->picture)) }}">
                        @else
                        <span class="symbol-label font-size-h4">
                            {{ $plant->name[0] }}
                        </span>
                        @endif
                    </div>
                </div>
                <!--end::Pic-->
                <!--begin::Title-->
                <div class="d-flex flex-column">
                    <span class="text-dark font-weight-bold font-size-h4 mb-0">{{ ucwords($plant->name) }}</span>
                </div>
                <!--end::Title-->
            </div>
            @csrf
            <h3 class="font-weight-boldest mt-7">
                Congratulations
            </h3>
            <p class="mt-1">
                You farmed a {{ ucwords($plant->name) }}! <br>
                It is now in your bag
            </p>
        </div>
    </div>
</div>
