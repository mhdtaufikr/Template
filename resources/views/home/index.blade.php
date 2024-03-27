@extends('layouts.master')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div  class="container-xl px-4">
            <div  class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-2">
                        <h1 class="page-header-title">
                            {{-- <div class="page-header-icon"><i data-feather="file"></i></div> --}}
                            {{-- <label id="lblGreetings"></label> --}}
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Main page content-->
    <div class="container-xl px-4 mt-n10">
        <div style="margin-top: -170px" class="row">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-body">
                        <!-- Grafik Pie -->
                        <div id="chartContainer" style="height: 330px; max-width: 920px; margin: 0px auto;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="card">
                    <div class="card-body">
                        <!-- Grafik Line/Bar -->
                        <div id="chartContainer2" style="height: 330px; max-width: 920px; margin: 0px auto;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-5 mt-2">
                    <div class="card">
                        <div class="card-body">
                            <!-- Map chart for Location Distribution -->
                            <div id="chartContainer4" style="height: 320px; max-width: 920px; margin: 0px auto;"></div>
                        </div>
                    </div>
            </div>
            <div class="col-md-7 mt-2">
                <div class="card">
                    <div class="card-body">
                    <!-- Bar chart for Quantity by Department -->
                    <div id="chartContainer3" style="height: 320px; max-width: 920px; margin: 0px auto;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    var myDate = new Date();
    var hrs = myDate.getHours();

    var greet;

    if (hrs < 12)
        greet = 'Good Morning';
    else if (hrs >= 12 && hrs <= 17)
        greet = 'Good Afternoon';
    else if (hrs >= 17 && hrs <= 24)
        greet = 'Good Evening';

    document.getElementById('lblGreetings').innerHTML =
        '<b>' + greet + '</b> and welcome to Asset Management!';
</script>

<!-- Script untuk Grafik Pie dan Line/Bar -->
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script>
    window.onload = function() {
        var assetDistribution = @json($assetDistribution); // Ambil data distribusi aset dari controller
        var chartData = @json($chartData); // Ambil data acquisition cost dari controller

        var pieChart = new CanvasJS.Chart("chartContainer", {
            theme: "light2",
            exportEnabled: true,
            animationEnabled: true,
            title: {
                text: "Asset Type Distribution"
            },
            data: [{
                type: "pie",
                startAngle: 25,
                toolTipContent: "<b>{label}</b>: {y}",
                showInLegend: "true",
                legendText: "{label}",
                indexLabelFontSize: 16,
                indexLabel: "{label} - {y}",
                dataPoints: assetDistribution.map(function(item) {
                    return { y: item.total, label: item.asset_type };
                })
            }]
        });
        pieChart.render();

        var lineChart = new CanvasJS.Chart("chartContainer2", {
    animationEnabled: true,
    title: {
        text: "Acquisition Cost Analysis"
    },
    axisX: {
        title: "Acquisition Year"
    },
    axisY: {
        title: "Acquisition Cost (Rp)"
    },
    data: [{
        type: "line", // You can change this to "bar" for a bar chart
        xValueType: "number", // Use "number" since the x-axis represents years as integers
        xValueFormatString: "####", // Format for displaying only the year on the x-axis
        // Format for displaying acquisition cost in rupiah on the y-axis without decimal places
        toolTipContent: "Year: {x}<br>Acquisition Cost: Rp. {y}", // Tooltip content includes both year and acquisition cost with the currency symbol
        dataPoints: chartData // Your chart data array with x and y values
    }]
    });
    lineChart.render();

    var quantityByDepartment = @json($quantityByDepartment);

var barChart = new CanvasJS.Chart("chartContainer3", {
    animationEnabled: true,
    title: {
        text: "Quantity by Department"
    },
    axisX: {
        title: "Department"
    },
    axisY: {
        title: "Quantity"
    },
    data: [{
        type: "bar",
        dataPoints: quantityByDepartment.map(function(item) {
            return { y: item.total, label: item.dept };
        })
    }]
});
barChart.render();
var locationDistribution = @json($locationDistribution);
var barChart = new CanvasJS.Chart("chartContainer4", {
    animationEnabled: true,
    title: {
        text: "Location Distribution"
    },
    axisX: {
        title: "Location",
        interval: 1
    },
    axisY: {
        title: "Quantity"
    },
    data: [{
        type: "column",
        dataPoints: locationDistribution.map(function(item) {
            return { label: item.plantName, y: item.total };
        })
    }]
});

barChart.render();






    }
</script>
@endsection
