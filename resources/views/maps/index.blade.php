@extends('layouts.template')

@section('css')
    <link href="{{ asset('assets/plugins/custom/leaflet/leaflet.bundle.css?v=7.0.5') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">{{ Session::get('company_tag') }} Map</h3>
            <div class="card-toolbar">
                <div class="btn-group">

                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 mt-5">
                    <div class="row">
                        @foreach ($arr as $item)
                            @if (isset($company_name[$item]))
                            <div class="col-md-3">
                                <div class="alert alert-custom alert-outline-2x" style="background: transparent; border-color: {{ $company_bg[$item] }}">
                                    <div class="alert-icon">
                                        <span class="svg-icon svg-icon-2x"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="24" width="24" height="0"/><path style="fill: {{ $company_bg[$item] }}" d="M5,10.5 C5,6 8,3 12.5,3 C17,3 20,6.75 20,10.5 C20,12.8325623 17.8236613,16.03566 13.470984,20.1092932 C12.9154018,20.6292577 12.0585054,20.6508331 11.4774555,20.1594925 C7.15915182,16.5078313 5,13.2880005 5,10.5 Z M12.5,12 C13.8807119,12 15,10.8807119 15,9.5 C15,8.11928813 13.8807119,7 12.5,7 C11.1192881,7 10,8.11928813 10,9.5 C10,10.8807119 11.1192881,12 12.5,12 Z" fill="#000000" fill-rule="nonzero"/></g></svg></span>
                                    </div>
                                    <div class="alert-text">
                                        <div class="text-dark font-weight-bold id-comps" data-id='{{ $item }}'>{{ $company_name[$item] }}</div>
                                    </div>
                                </div>
                            </div>
                                {{-- <div class="col-md-2 mb-4 d-flex align-items-stretch">
                                    <div class="d-flex flex-grow-1 align-items-center bg-hover-dark-o-3 p-4 rounded" style="background-color: {{ $company_bg[$item] }}">
                                        <div class="mr-4 flex-shrink-0 text-left">
                                            <span class="svg-icon svg-icon-white svg-icon-2x"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="24" width="24" height="0"/><path d="M5,10.5 C5,6 8,3 12.5,3 C17,3 20,6.75 20,10.5 C20,12.8325623 17.8236613,16.03566 13.470984,20.1092932 C12.9154018,20.6292577 12.0585054,20.6508331 11.4774555,20.1594925 C7.15915182,16.5078313 5,13.2880005 5,10.5 Z M12.5,12 C13.8807119,12 15,10.8807119 15,9.5 C15,8.11928813 13.8807119,7 12.5,7 C11.1192881,7 10,8.11928813 10,9.5 C10,10.8807119 11.1192881,12 12.5,12 Z" fill="#000000" fill-rule="nonzero"/></g></svg></span>
                                        </div>
                                        <div class="text-white font-weight-bold id-comps" data-id='{{ $item }}'>{{ $company_name[$item] }}</div>
                                    </div>
                                </div> --}}
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="col-12 border p-5">
                    <div id="kt_leaflet_6" style="height:800px;"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalCrewLoc" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" id="crewList">

            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{ asset('theme/assets/plugins/custom/leaflet/leaflet.bundle.js') }}"></script>
    <script>

        var dt

        function listCrew(x, y){
            $.ajax({
                url : "{{ route('general.maps.employees') }}/" + x + "/" + y,
                type : "get",
                cache : false,
                success : function(response){
                    $("#crewList").html(response)
                    $("table.display").DataTable()
                }
            })
        }

        var demo6 = function () {
            var data = [
                { "loc": [-6.184182276788618, 106.9958928126219], "title": "black" },
                { "loc": [-6.737256580538595, 108.53869953165832], "title": "blue" },
            ];

            // init leaflet map
            var leaflet = new L.Map('kt_leaflet_6', {
                zoomSnap : 0.1,
                minZoom: 5.6,
            }).setView([-2.232555671751522, 117.63552256391021], 5);
            leaflet.setZoom(5.6)


            leaflet.addLayer(new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'));

            // add scale layer
            L.control.scale().addTo(leaflet);

            var comps = []
            $(".id-comps").each(function(){
                comps.push($(this).attr("data-id"))
            })
            console.log(comps)

            // set markers crew
            $.ajax({
                url: "{{ route('general.maps.markers.crew') }}",
                type: "post",
                dataType: "json",
                data: {
                    _token : "{{ csrf_token() }}",
                    comps : comps
                },
                cache: false,
                success: function(response){
                    dt = response
                    if (response.success) {
                        var data = response.data
                        data.forEach(function (item) {
                            var mar_col = ['danger', 'success', 'primary', 'info', 'warning']
                            var key = Math.floor(Math.random() * mar_col.length)
                            var class_svg = mar_col[key]
                            // set custom SVG icon marker
                            var leafletIcon = L.divIcon({
                                html: `<span class="svg-icon svg-icon-3x"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="24" width="24" height="0"/><path style="fill: `+item.bg+`" d="M5,10.5 C5,6 8,3 12.5,3 C17,3 20,6.75 20,10.5 C20,12.8325623 17.8236613,16.03566 13.470984,20.1092932 C12.9154018,20.6292577 12.0585054,20.6508331 11.4774555,20.1594925 C7.15915182,16.5078313 5,13.2880005 5,10.5 Z M12.5,12 C13.8807119,12 15,10.8807119 15,9.5 C15,8.11928813 13.8807119,7 12.5,7 C11.1192881,7 10,8.11928813 10,9.5 C10,10.8807119 11.1192881,12 12.5,12 Z" fill="#000000" fill-rule="nonzero"/></g></svg></span>`,
                                bgPos: [10, 10],
                                iconAnchor: [20, 37],
                                popupAnchor: [0, -37],
                                className: 'leaflet-marker'
                            });
                            var marker = L.marker(item.loc, { icon: leafletIcon }).addTo(leaflet);
                            marker.bindPopup(item.title, { closeButton: false });
                        })
                    }
                }
            })

            // set markers office
            $.ajax({
                url: "{{ route('general.maps.markers.office') }}",
                type: "post",
                dataType: "json",
                data: {
                    _token : "{{ csrf_token() }}",
                    comps : comps
                },
                cache: false,
                success: function(response){
                    dt = response
                    if (response.success) {
                        var data = response.data
                        data.forEach(function (item) {
                            var mar_col = ['danger', 'success', 'primary', 'info', 'warning']
                            var key = Math.floor(Math.random() * mar_col.length)
                            var class_svg = mar_col[key]
                            // set custom SVG icon marker
                            var leafletIcon = L.divIcon({
                                // html: `<span class="svg-icon svg-icon-3x"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><path style="fill: `+item.bg+`" d="M13.5,21 L13.5,18 C13.5,17.4477153 13.0522847,17 12.5,17 L11.5,17 C10.9477153,17 10.5,17.4477153 10.5,18 L10.5,21 L5,21 L5,4 C5,2.8954305 5.8954305,2 7,2 L17,2 C18.1045695,2 19,2.8954305 19,4 L19,21 L13.5,21 Z M9,4 C8.44771525,4 8,4.44771525 8,5 L8,6 C8,6.55228475 8.44771525,7 9,7 L10,7 C10.5522847,7 11,6.55228475 11,6 L11,5 C11,4.44771525 10.5522847,4 10,4 L9,4 Z M14,4 C13.4477153,4 13,4.44771525 13,5 L13,6 C13,6.55228475 13.4477153,7 14,7 L15,7 C15.5522847,7 16,6.55228475 16,6 L16,5 C16,4.44771525 15.5522847,4 15,4 L14,4 Z M9,8 C8.44771525,8 8,8.44771525 8,9 L8,10 C8,10.5522847 8.44771525,11 9,11 L10,11 C10.5522847,11 11,10.5522847 11,10 L11,9 C11,8.44771525 10.5522847,8 10,8 L9,8 Z M9,12 C8.44771525,12 8,12.4477153 8,13 L8,14 C8,14.5522847 8.44771525,15 9,15 L10,15 C10.5522847,15 11,14.5522847 11,14 L11,13 C11,12.4477153 10.5522847,12 10,12 L9,12 Z M14,12 C13.4477153,12 13,12.4477153 13,13 L13,14 C13,14.5522847 13.4477153,15 14,15 L15,15 C15.5522847,15 16,14.5522847 16,14 L16,13 C16,12.4477153 15.5522847,12 15,12 L14,12 Z" fill="#000000"/><rect fill="#FFFFFF" x="13" y="8" width="3" height="3" rx="1"/><path d="M4,21 L20,21 C20.5522847,21 21,21.4477153 21,22 L21,22.4 C21,22.7313708 20.7313708,23 20.4,23 L3.6,23 C3.26862915,23 3,22.7313708 3,22.4 L3,22 C3,21.4477153 3.44771525,21 4,21 Z" fill="#000000" opacity="0.3"/></g></svg></span>`,
                                html: `<span class="svg-icon svg-icon-3x"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="24" width="24" height="0"/><path style="fill: `+item.bg+`" d="M5,10.5 C5,6 8,3 12.5,3 C17,3 20,6.75 20,10.5 C20,12.8325623 17.8236613,16.03566 13.470984,20.1092932 C12.9154018,20.6292577 12.0585054,20.6508331 11.4774555,20.1594925 C7.15915182,16.5078313 5,13.2880005 5,10.5 Z M12.5,12 C13.8807119,12 15,10.8807119 15,9.5 C15,8.11928813 13.8807119,7 12.5,7 C11.1192881,7 10,8.11928813 10,9.5 C10,10.8807119 11.1192881,12 12.5,12 Z" fill="#000000" fill-rule="nonzero"/></g></svg></span>`,
                                bgPos: [10, 10],
                                iconAnchor: [20, 37],
                                popupAnchor: [0, -37],
                                className: 'leaflet-marker'
                            });
                            var marker = L.marker(item.loc, { icon: leafletIcon }).addTo(leaflet);
                            marker.bindPopup(item.title, { closeButton: false });
                        })
                    }
                }
            })
        }

        $(document).ready(function(){
            demo6()
        })

        function getRandomColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }
    </script>
@endsection
