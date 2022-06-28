<link href="{{ asset('assets/plugins/custom/leaflet/leaflet.bundle.css?v=7.0.5') }}" rel="stylesheet" type="text/css" />
<div class="card card-custom gutter-b">
    <div class="card-header">
        <h3 class="card-title">Crew Location Maps</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <div id="kt_leaflet_6" style="height:400px;"></div>
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

<script src="{{ asset('theme/assets/plugins/custom/leaflet/leaflet.bundle.js') }}"></script>
<script>
    var dt

    function listCrew(x){
        $.ajax({
            url : "{{ route('crewloc.crew') }}/" + x,
            type : "get",
            cache : false,
            success : function(response){
                $("#crewList").html(response)
                $("table.display").DataTable()
            }
        })
    }

    var demo6 = function () {
		// add sample location data
        console.log(dt)
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

		// set markers
        $.ajax({
            url: "{{ route('crewloc.markers') }}",
            type: "get",
            dataType: "json",
            cache: false,
            success: function(response){
                dt = response
                if (response.success == false) {
                    Swal.fire(response.messages, response.data, 'error')
                } else {
                    var data = response.data
                    data.forEach(function (item) {
                        var mar_col = ['danger', 'success', 'primary', 'info', 'warning']
                        var key = Math.floor(Math.random() * mar_col.length)
                        var class_svg = mar_col[key]
                        // set custom SVG icon marker
                        var leafletIcon = L.divIcon({
                            html: `<span class="svg-icon svg-icon-`+class_svg+` svg-icon-3x"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="24" width="24" height="0"/><path d="M5,10.5 C5,6 8,3 12.5,3 C17,3 20,6.75 20,10.5 C20,12.8325623 17.8236613,16.03566 13.470984,20.1092932 C12.9154018,20.6292577 12.0585054,20.6508331 11.4774555,20.1594925 C7.15915182,16.5078313 5,13.2880005 5,10.5 Z M12.5,12 C13.8807119,12 15,10.8807119 15,9.5 C15,8.11928813 13.8807119,7 12.5,7 C11.1192881,7 10,8.11928813 10,9.5 C10,10.8807119 11.1192881,12 12.5,12 Z" fill="#000000" fill-rule="nonzero"/></g></svg></span>`,
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
