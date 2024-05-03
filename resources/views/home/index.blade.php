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
    <div class="container-xs px-4 mt-n10">
        <div style="margin-top: -170px" class="row">
            <div class="col-md-3 mb-2">
                <div class="card card-waves border-success">
                    <div class="card-body text-center">
                        <div style="margin: -10px" class="row align-items-center">
                            <div class="col-md-6">
                                <h5 class="card-title mb-3">Active Assets</h5>
                               <strong> <h1 class="card-text mb-0">{{$countStatusOne}} </h1> </strong>
                            </div>
                            <div class="col-md-6">
                                <i class="fas fa-check text-success" style="font-size: 35px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-2">
                <div class="card card-waves border-danger">
                    <div class="card-body text-center">
                        <div style="margin: -10px" class="row align-items-center">
                            <div class="col-md-6">
                                <h5 style="font-size: 16px" class="card-title mb-3">Deactive Assets</h5>
                               <strong> <h1 class="card-text mb-0">{{$countStatusZero}}</h1> </strong>
                            </div>
                            <div class="col-md-6">
                                <i class="fas fa-ban text-danger" style="font-size: 35px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="card card-waves border-warning">
                    <div class="card-body text-center">
                        <div style="margin: -10px" class="row align-items-center">
                            <div class="col-md-6">
                                <h5 style="font-size: 16px" class="card-title mb-3">Acquisition Cost</h5>
                                <strong>
                                    <h1 style="font-size: 16px; padding-bottom: 10px;" class="card-text mb-0">Rp {{ number_format($totalAcqCost, 0, ',', '.') }}</h1>
                                </strong>
                            </div>
                            <div class="col-md-6">
                                <strong  class="text-warning" style="font-size: 35px;">Rp</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="card card-waves border-primary">
                    <div class="card-body text-center">
                        <div style="margin: -10px" class="row align-items-center">
                            <div class="col-md-6">
                                <h5 class="card-title mb-3">Total Assets</h5>
                               <strong> <h1 class="card-text mb-0">{{$totalAsset}}</h1> </strong>
                            </div>
                            <div class="col-md-6">
                                <i class="fas fa-boxes text-primary"  style="font-size: 35px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div style="margin-top: -5px;" class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 style="font-size: 1rem;">A. Refreshment MKM Fixed Asset Management Policy</h5>
                                <p style="font-size: 0.8rem;">MKM Asset Management refers to the MKM Policy issued since 2015 (attached)</p>
                                <h6 style="font-size: 0.9rem;margin-top: -14px;">1. Definition</h6>
                                <p style="font-size: 0.8rem;">Assets with a useful life of more than one year and used in the continuity of MKM business</p>
                                <h6 style="font-size: 0.9rem;margin-top: -14px;">2. Criteria</h6>
                                <ol style="font-size: 0.8rem; list-style-type:lower-alpha" >
                                    <li> Estimated lifetime more than 1 year</li>
                                    <li> Clear lifetime limitation</li>
                                    <li> Used in the business process at MKM</li>
                                    <li> Provides future economic benefit</li>
                                    <li> For MKM business needs & not for sale purposes</li>
                                    <li> Tangible</li>
                                    <li> Intangible Asset can be software / Applications</li>
                                </ol>
                            </div>
                            <div class="col-md-6">
                                <h6 style="font-size: 0.9rem;">3. Asset Category</h6>
                                <div class="d-flex justify-content-center">
                                    <img src="{{ asset('images\MKM Fixed Asset Classification.png') }}" class="d-block" style="max-width: 630px;" alt="Image">
                                </div>
                                <p style="font-size: 0.8rem;">For items below 10 million, categorized as Low Value Asset under the following conditions:</p>
                                <ol  style="font-size: 0.8rem; margin-bottom: -5px;">
                                    <li style="margin-top: -14px;">Estimated lifetime more than 1 year</li>
                                    <li>Have value and can be sold</li>
                                    <li>Electronic items or communication tools</li>
                                </ol>
                                <span style="font-size: 0.8rem;">Allocation uses Fixed cost budget (submitting Asset Registration Form)</span>
                                <span style="font-size: 0.8rem;">Categorized as an Asset for control during usage</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>




                <div class="col-md-5 mt-1">
                    <div class="card">
                        <div class="card-header border-bottom">
                            <ul class="nav nav-tabs card-header-tabs" id="cardTab" role="tablist">
                                <li  style="margin-bottom: 15px" class="nav-item">
                                    <a style="margin-left: 10px" class="nav-link active" id="chart1-tab" data-bs-toggle="tab" href="#chart1" role="tab" aria-controls="chart1" aria-selected="true">Asset Qty</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="chart2-tab" data-bs-toggle="tab" href="#chart2" role="tab" aria-controls="chart2" aria-selected="false">Book Value</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="cardTabContent">
                                <div class="tab-pane fade show active" id="chart1" role="tabpanel" aria-labelledby="chart1-tab">
                                    <!-- Chart 1 content -->
                                    <div id="chartContainer3"  style="height: 235px; max-width: 920px; margin: 0px auto;"></div>
                                </div>
                                <div class="tab-pane fade" id="chart2" role="tabpanel" aria-labelledby="chart2-tab">
                                    <!-- Chart 2 content -->
                                    <div id="chartContainer4"  style="height: 235px; max-width: 920px; margin: 0px auto;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            <div class="col-md-7 mt-1">
                <div class="card">
                    <div class="card-header border-bottom">
                        <ul class="nav nav-tabs card-header-tabs" id="cardTab" role="tablist">
                            <li style="margin-bottom: 15px" class="nav-item">
                                <a style="margin-left: 10px" class="nav-link active" id="overview-tab" href="#overview" data-bs-toggle="tab" role="tab" aria-controls="overview" aria-selected="true">5 Years</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="example-tab" href="#example" data-bs-toggle="tab" role="tab" aria-controls="example" aria-selected="false">Summary</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="cardTabContent">
                            <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                                <div id="chartContainer5" style="height: 235px; max-width: 920px; margin: 0px auto;"></div>
                            </div>
                            <div class="tab-pane fade" id="example" role="tabpanel" aria-labelledby="example-tab">
                                <div id="chartContainer2" style="height: 235px; max-width: 920px; margin: 0px auto;"></div>
                            </div>
                        </div>
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
        var assetDistribution = @json($assetDistribution); // Asset distribution data from the controller

// Calculate the total count of assets
var totalCount = assetDistribution.reduce(function(sum, item) {
    return sum + item.total;
}, 0);

var chartData = @json($chartData);

        var lineChart = new CanvasJS.Chart("chartContainer2", {
            exportEnabled: true,
            animationEnabled: true,
            width: 900, // Set the width to 920 pixels
            height: 235, // Set the height to 285 pixels
    title: {
        text: "Acquisition Cost Analysis"
    },
    axisX: {
        title: "Acquisition Year",
        valueFormatString: "####", // Format the X-axis labels as years
        labelAngle: 0 // Rotate X-axis labels for better readability
    },
    axisY: {
        title: "Acquisition Cost (Rp)",
        labelFormatter: function (e) {
            if (e.value >= 1000000000) {
                return (e.value / 1000000000).toFixed(0) + "B"; // Convert to billions without decimal places
            } else if (e.value >= 1000000) {
                return (e.value / 1000000).toFixed(0) + "M"; // Convert to millions without decimal places
            } else {
                return e.value;
            }
        }
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
    exportEnabled: true,
            animationEnabled: true,
    title: {
        text: "Asset Quantity by Department"
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
var barChartData = @json($barChartDatatype);
var dataPoints = barChartData.map(function(item, index) {
    return { y: item.y, label: item.label };
});

function getColor(index) {
    // Define an array of colors for the columns

    return colors[index % colors.length];
}
var currentYear = new Date().getFullYear();

var barChart = new CanvasJS.Chart("chartContainer4", {
    exportEnabled: true,
    animationEnabled: true,
    width: 600, // Set the width to 920 pixels
    height: 235, // Set the height to 285 pixels
    theme: "light2",
    title: {
        text: "Book Value End of Year (" + currentYear + ")"
    },
    axisY: {
        title: "BV End of Year",
        labelFormatter: function (e) {
            if (e.value >= 1000000000) {
                return (e.value / 1000000000).toFixed(0) + " " + "Bio"; // Convert to billions without decimal places
            } else if (e.value >= 1000000) {
                return (e.value / 1000000).toFixed(0) + "M"; // Convert to millions without decimal places
            } else {
                return e.value;
            }
        }
    },
    axisX: {
        labelAngle: -30,
        labelFontSize: 12,
        interval: 1
    },
    legend: {
        horizontalAlign: "center",
        verticalAlign: "top",
        fontSize: 14
    },
    data: [{
        type: "column",
        showInLegend: true,
        legendText: "by Asset Type", // Use the legendText property from data points for legend text
        legendMarkerColor: "{legendMarkerColor}", // Use the legendMarkerColor property from data points for marker color
        dataPoints: dataPoints
    }]
});

barChart.render();
// Assuming $lineChartDataLast5Years contains the last 5 years' chart data
var lineChartDataLast5Years = @json($barChartData);

// Extract data points for CanvasJS chart
var dataPointsLast5Years = lineChartDataLast5Years.map(function(item) {
    return { x: item.label, y: item.y };
});

var lineChartLast5Years = new CanvasJS.Chart("chartContainer5", {
    animationEnabled: true,
    exportEnabled: true,
    title: {
        text: "Acquisition Cost Analysis (Last 5 Years)"
    },
    axisX: {
        title: "Acquisition Year",
        interval: 1,
        valueFormatString: "####", // Format the X-axis labels as years
        labelAngle: 0 // Rotate X-axis labels for better readability
    },
    axisY: {
        title: "Acquisition Cost (Rp)",
        labelFormatter: function (e) {
            if (e.value >= 1000000000) {
                return (e.value / 1000000000).toFixed(0) +  " " + "Bio"; // Convert to billions without decimal places
            } else if (e.value >= 1000000) {
                return (e.value / 1000000).toFixed(0) + "M"; // Convert to millions without decimal places
            } else {
                return e.value;
            }
        }
    },
    data: [{
        type: "line",
        xValueType: "number",
        toolTipContent: "Year: {x}<br>Acquisition Cost: Rp. {y}",
        dataPoints: dataPointsLast5Years
    }]
});

lineChartLast5Years.render();
    }
</script>
@endsection
