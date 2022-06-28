@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">Chart of {{ $items[0]->tag }}</h3>
            <div class="card-toolbar">
                <div class="btn-group">

                </div>
            </div>
        </div>
    </div>
    <div class="card card-custom gutter-b" id="card-1">
        <div class="card-header">
            <h3 class="card-title">D.thin.f & D.elin.f x RBI Date</h3>
            <div class="card-toolbar">
                <div class="btn-group">

                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="chartdiv" style="height: 500px"></div>
        </div>
    </div>
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">D.thin.f x RBI Date</h3>
            <div class="card-toolbar">
                <div class="btn-group">

                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="chartdiv1" style="height: 500px"></div>
        </div>
    </div>
@endsection

@section('custom_script')

    <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>

    <script>

        function charts(target, source){
            var div = $("#"+target).parents('div.card')
            KTApp.block(div)
            try {
                $.ajax({
                url : "{{ route('te.pv.chart.data', $items[0]->tag) }}",
                type : "get",
                dataType : "json",
                success : function(response){
                    var data = response.data
                    am4core.ready(function() {

                        // Themes begin
                        am4core.useTheme(am4themes_animated);
                        // Themes end

                        // Create chart instance
                        var chart = am4core.create(target, am4charts.XYChart);

                        chart.legend = new am4charts.Legend();

                        chart.data = data

                        console.log(chart.data)

                        // Create axes
                        var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
                        dateAxis.renderer.minGridDistance = 50;

                        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

                        function create_series(valY){
                            // Create series
                            var series = chart.series.push(new am4charts.LineSeries());
                            series.dataFields.valueY = valY;
                            series.dataFields.dateX = "date";
                            series.strokeWidth = 4;
                            series.minBulletDistance = 10;
                            series.tooltipText = "[bold]{date.formatDate()}:[/] {valueY}";
                            series.tooltip.pointerOrientation = "vertical";
                            series.name = valY;
                        }

                        for (let index = 0; index < source.length; index++) {
                            create_series(source[index])

                        }

                        // Add cursor
                        chart.cursor = new am4charts.XYCursor();
                        chart.cursor.xAxis = dateAxis;
                    });
                }
            })
            } catch (error) {

            }
            KTApp.unblock(div)
        }

        $(document).ready(function(){
            charts('chartdiv', ['d.thin.f', 'd.elin.f'])
            charts('chartdiv1', ['d.thin.f'])
        })
    </script>
@endsection
