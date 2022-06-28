@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">Employee Activity</h3>
            <div class="card-toolbar">
                <a href="" class="btn btn-icon btn-sm btn-success"><i class="fa fa-arrow-left"></i></a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <h3 class="text-center">Today's Cypher Activity Score</h3>
                    <div id="chart-emp" style="height: 500px"></div>
                </div>
                <div class="col-12">
                    <hr>
                </div>
                <div class="col-12">
                    <table class="table table-bordered table-responsive-sm display">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">username</th>
                                <th class="text-center">status</th>
                                <th class="text-center">hour(s)</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
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
    <script>
        $(document).ready(function(){
            $.ajax({
                url : "{{ route('employee.activity.get') }}?chart=on",
                type : "get",
                dataType : "json",
                success : function(response){
                    console.log(response)
                    am4core.ready(function() {

                        // Themes begin
                        am4core.useTheme(am4themes_animated);
                        // Themes end

                        // Create chart instance
                        var chart = am4core.create("chart-emp", am4charts.XYChart);
                        chart.data = response.data

                        // Create axes

                        var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
                        categoryAxis.dataFields.category = "name";
                        categoryAxis.renderer.grid.template.location = 0;
                        categoryAxis.renderer.minGridDistance = 30;

                        categoryAxis.renderer.labels.template.adapter.add("dy", function(dy, target) {
                            if (target.dataItem && target.dataItem.index & 2 == 2) {
                                return dy + 25;
                            }
                            return dy;
                        });

                        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

                        // Create series
                        var series = chart.series.push(new am4charts.ColumnSeries());
                        series.dataFields.valueY = "hour";
                        series.dataFields.categoryX = "name";
                        series.name = "Hours";
                        series.columns.template.tooltipText = "{categoryX}: [bold]{valueY} hour(s)[/]";
                        series.columns.template.fillOpacity = .8;

                        series.columns.template.adapter.add("fill", function(fill, target){
                            return chart.colors.getIndex(target.dataItem.index);
                        });

                        var columnTemplate = series.columns.template;
                        columnTemplate.strokeWidth = 2;
                        columnTemplate.strokeOpacity = 1;
                    });
                }
            })

            $("table.display").DataTable({
                ajax : {
                    url : "{{ route('employee.activity.get') }}",
                    type : "get",
                },
                columns : [
                    {"data" : "i"},
                    {"data" : "name"},
                    {"data" : "username"},
                    {"data" : "status"},
                    {"data" : "hour"},
                ],
                columnDefs : [
                    {"targets" : [0,2,3,4], "className" : "text-center"}
                ],
                pageLength : 100
            })
        })
    </script>
@endsection
