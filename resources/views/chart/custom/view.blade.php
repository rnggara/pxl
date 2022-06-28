@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <a href="#" class="text-black-50">Custom Chart - <span class="text-primary">View</span></a>
            </div>
            <div class="card-toolbar">
                <a href="{{route('chart.custom.index')}}" class="btn btn-xs btn-success"><i class="fa fa-arrow-left"></i></a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h3>{{$chart->name}}</h3>
                </div>
            </div>
            <div class="overflow" id="table-calendar">
                <div id="chartdiv" style="height: 500px; width: 100%"></div>
                <div id="linechart_material"></div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered table-responsive-xl" id="table-show">
                        <thead>
                            <tr id="tr-head">
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('custom_script')
    <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
    <script>
        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }
        $(document).ready(function () {
            // $('.display').DataTable({
            //     responsive: true,
            //     fixedHeader: true,
            //     fixedHeader: {
            //         headerOffset: 90
            //     }
            // });
            $("#table-show").hide()

            $.ajax({
                url: "{{route('chart.custom.get_data', $chart->id)}}",
                type: 'GET',
                dataType: "json",
                cache: false,
                success: function(response){
                    $("#table-show").show()
                    var th = ""
                    var columns = [{"field": "date", "num": 0}]
                    var dataColumns = []
                    var num = []
                    if (response.data.length == 0){
                        Swal.fire('Information', 'No data available', 'info')
                    }
                    for (const key in response.bLines) {
                        columns.push({
                            field: response.bLines[key].type,
                            num: 1
                        })
                        num.push(parseInt(key)+1)
                    }

                    console.log(dataColumns)

                    for (const key in columns) {
                        th += "<th class='text-center'>"+columns[key]+"</th>"
                        var render = $.fn.dataTable.render.text()
                        if (columns.num == 1){
                            render = $.fn.dataTable.render.number(',', '.', 2, '')
                        }
                        dataColumns.push({
                            data: columns[key].field.toLowerCase(),
                            title: capitalizeFirstLetter(columns[key].field)
                        })
                    }

                    $("#tr-head").append(th)
                    $("#table-show").DataTable({
                        responsive: true,
                        fixedHeader: true,
                        fixedHeader: {
                            headerOffset: 90
                        },
                        data: response.data,
                        columns: dataColumns,
                        "columnDefs": [
                            {"className": "dt-center num", "targets": num},
                            {"className": "dt-center", "targets": "_all"},
                        ],
                    })

                    $("td.num").each(function(){
                        $(this).text(numeral($(this).text()).format(0,0))
                    })

                    var data = response.data

                    // Themes begin
                    am4core.useTheme(am4themes_animated);
                    // Themes end

                    // Create chart instance
                    var chart = am4core.create("chartdiv", am4charts.XYChart);

                    chart.colors.step = 2;
                    chart.maskBullets = false;

                    var newData = []

                    // Add data
                    chart.data = data

                    // Create axes
                    var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
                    dateAxis.renderer.grid.template.location = 0;
                    dateAxis.renderer.minGridDistance = 50;
                    dateAxis.renderer.grid.template.disabled = true;
                    dateAxis.renderer.fullWidthTooltip = true;

                    var distanceAxis = chart.yAxes.push(new am4charts.ValueAxis());
                    distanceAxis.title.text = "Month";
                    //distanceAxis.renderer.grid.template.disabled = true;

                    var durationAxis = chart.yAxes.push(new am4charts.ValueAxis());
                    durationAxis.title.text = "Amount";
                    durationAxis.baseUnit = "unit";
                    //durationAxis.renderer.grid.template.disabled = true;
                    durationAxis.renderer.opposite = true;
                    durationAxis.syncWithAxis = distanceAxis;

                    /* durationAxis.durationFormatter.durationFormat = "hh'h' mm'min'"; */

                    var latitudeAxis = chart.yAxes.push(new am4charts.ValueAxis());
                    latitudeAxis.renderer.grid.template.disabled = true;
                    latitudeAxis.renderer.labels.template.disabled = true;
                    latitudeAxis.syncWithAxis = distanceAxis;

                    var show = response.bLines

                    for (var i = 0; i < show.length; i++) {
                        var salesSeries = chart.series.push(new am4charts.LineSeries());
                        salesSeries.dataFields.valueY = show[i].type;
                        salesSeries.dataFields.dateX = "date";
                        salesSeries.yAxis = durationAxis;
                        salesSeries.name = show[i].label;
                        salesSeries.strokeWidth = 2;
                        salesSeries.propertyFields.strokeDasharray = "dashLength";
                        salesSeries.tooltipText = show[i].label + ": {valueY} ({dateX})";
                        salesSeries.showOnInit = true;

                        var salesBullet = salesSeries.bullets.push(new am4charts.Bullet());
                        var salesRectangle = salesBullet.createChild(am4core.Rectangle);
                        salesBullet.horizontalCenter = "middle";
                        salesBullet.verticalCenter = "middle";
                        salesBullet.width = 7;
                        salesBullet.height = 7;
                        salesRectangle.width = 7;
                        salesRectangle.height = 7;

                        var salesState = salesBullet.states.create("hover");
                        salesState.properties.scale = 1.2;
                    }

                    // Add legend
                    chart.legend = new am4charts.Legend();

                    // Add cursor
                    chart.cursor = new am4charts.XYCursor();
                    chart.cursor.fullWidthLineX = true;
                    chart.cursor.xAxis = dateAxis;
                    chart.cursor.lineX.strokeOpacity = 0;
                    chart.cursor.lineX.fill = am4core.color("#000");
                    chart.cursor.lineX.fillOpacity = 0.1;
                }
            })
        })

    </script>
@endsection
