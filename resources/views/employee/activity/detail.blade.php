@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">Activity Detail</h3>
            <div class="card-toolbar">
                <a href="{{ route('employee.activity.index') }}" class="btn btn-sm btn-success btn-icon"><i class="fa fa-arrow-left"></i></a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <div class="btn-group">
                        <button type="button" onclick="_chart('y')" class="btn btn-outline-primary">Yearly</button>
                        <button type="button" onclick="_chart('m')" class="btn btn-outline-primary">Monthly</button>
                        <button type="button" id="btn-weekly" onclick="_chart('w')" class="btn btn-outline-primary">Weekly</button>
                    </div>
                </div>
                <div class="col-12">
                    <h3 class="text-center">Activity of {{ $user->name }}</h3>
                    <h4 class="text-center" id="period-label"></h4>
                    <div id="chartdiv" style="height: 500px"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
<script>
    function _chart(type){
        Swal.fire({
            title: "Loading",
            text: "Searching data",
            allowOutsideClick: false,
            onOpen: function() {
                Swal.showLoading()
            }
        })
        $.ajax({
            url : "{{ route('employee.activity.detail-chart', $user->id) }}",
            type : "post",
            dataType : "json",
            data : {
                _token : "{{ csrf_token() }}",
                type : type
            },
            success : function(response){
                swal.close()
                am4core.ready(function() {

                    // Themes begin
                    am4core.useTheme(am4themes_animated);
                    // Themes end

                    var chart = am4core.create("chartdiv", am4charts.XYChart);

                    // Create axes
                    var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
                    dateAxis.renderer.grid.template.location = 0;
                    dateAxis.renderer.minGridDistance = 30;

                    // Create value axis
                    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

                    // Create series
                    var lineSeries = chart.series.push(new am4charts.LineSeries());
                    lineSeries.dataFields.valueY = "hour";
                    lineSeries.dataFields.dateX = "date";
                    lineSeries.name = "Hour";
                    lineSeries.tooltipText = "{hour}"
                    lineSeries.strokeWidth = 2;
                    lineSeries.minBulletDistance = 15;
                    // lineSeries.strokeDasharray = "5,4";
                    var bullet = lineSeries.bullets.push(new am4charts.CircleBullet());
                    bullet.circle.stroke = am4core.color("#fff");
                    bullet.circle.strokeWidth = 2;

                    // Add data
                    chart.data = response.data
                    chart.cursor = new am4charts.XYCursor();

                    var buttonContainer = chart.plotContainer.createChild(am4core.Container);
                    buttonContainer.shouldClone = false;
                    buttonContainer.align = "right";
                    buttonContainer.valign = "top";
                    buttonContainer.zIndex = Number.MAX_SAFE_INTEGER;
                    buttonContainer.marginTop = 5;
                    buttonContainer.marginRight = 5;
                    buttonContainer.layout = "horizontal";

                    var zoomInButton = buttonContainer.createChild(am4core.Button);
                    zoomInButton.label.text = "+";
                    zoomInButton.events.on("hit", function(ev) {
                    var diff = dateAxis.maxZoomed - dateAxis.minZoomed;
                    var delta = diff * 0.2;
                    dateAxis.zoomToDates(new Date(dateAxis.minZoomed + delta), new Date(dateAxis.maxZoomed - delta));
                    });

                    var zoomOutButton = buttonContainer.createChild(am4core.Button);
                    zoomOutButton.label.text = "-";
                    zoomOutButton.events.on("hit", function(ev) {
                    var diff = dateAxis.maxZoomed - dateAxis.minZoomed;
                    var delta = diff * 0.2;
                    dateAxis.zoomToDates(new Date(dateAxis.minZoomed - delta), new Date(dateAxis.maxZoomed + delta));
                    });
                });

                $("#period-label").text("From "+response.from+" to "+response.to)
            }
        })
    }
    $(document).ready(function(){
        $("#btn-weekly").click()
    })
</script>
@endsection
