@extends('layouts.master')

@section('content')
@php
    $formatIdr = fn($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
    $shortIdr = function ($value) {
        $value = (float) $value;
        if ($value >= 1000000000000) return 'Rp ' . number_format($value / 1000000000000, 2, ',', '.') . ' T';
        if ($value >= 1000000000) return 'Rp ' . number_format($value / 1000000000, 2, ',', '.') . ' B';
        if ($value >= 1000000) return 'Rp ' . number_format($value / 1000000, 1, ',', '.') . ' M';
        return 'Rp ' . number_format($value, 0, ',', '.');
    };
@endphp

<main class="assetdash">
    <div class="container-fluid px-4 py-4">
        <div class="assetdash-hero mb-2">
            <div>
                <div class="assetdash-eyebrow">Asset Intelligence Dashboard</div>
                <h1>Fixed Asset Control Center</h1>
                <p>Registry movement, acquisition value, book value, and asset health overview.</p>
            </div>
            <div class="assetdash-hero-metrics">
                <div>
                    <span>Total Acquisition</span>
                    <strong>{{ $shortIdr($sumAcqCostTotal) }}</strong>
                </div>
                <div>
                    <span>Total Book Value</span>
                    <strong>{{ $shortIdr($sumBookValueTotal) }}</strong>
                </div>
            </div>
        </div>

        <div class="assetdash-kpis mb-2">
            @foreach ([
                ['label' => 'Total Assets', 'value' => number_format($totalAsset), 'sub' => 'All registered rows', 'icon' => 'fa-boxes', 'tone' => 'primary'],
                ['label' => 'Active Assets', 'value' => number_format($countStatusOne), 'sub' => $shortIdr($sumAcqCostStatusOne), 'icon' => 'fa-check-circle', 'tone' => 'success'],
                ['label' => 'Deactive Assets', 'value' => number_format($countStatusZero), 'sub' => $shortIdr($sumAcqCostStatusZero), 'icon' => 'fa-pause-circle', 'tone' => 'warning'],
                ['label' => 'Disposal Assets', 'value' => number_format($countStatusDisposal), 'sub' => $shortIdr($sumAcqCostStatusDisposal), 'icon' => 'fa-times-circle', 'tone' => 'danger'],
                ['label' => 'Registered This Year', 'value' => number_format($thisYearAsset), 'sub' => number_format($thisMonthAsset) . ' this month', 'icon' => 'fa-calendar-plus', 'tone' => 'info'],
            ] as $card)
                <div class="assetdash-kpi assetdash-kpi-{{ $card['tone'] }}">
                    <div>
                        <span>{{ $card['label'] }}</span>
                        <strong>{{ $card['value'] }}</strong>
                        <small>{{ $card['sub'] }}</small>
                    </div>
                    <i class="fas {{ $card['icon'] }}"></i>
                </div>
            @endforeach
        </div>

        <div class="row g-2 mb-2">
            <div class="col-12 col-xl-8">
                <div class="card assetdash-card h-100">
                    <div class="card-body">
                        <div class="assetdash-card-head">
                            <div>
                                <h5>Acquisition Cost Trend</h5>
                                <p>Yearly acquisition value with asset count overlay.</p>
                            </div>
                            <span class="assetdash-chip">{{ $activeControlledAsset }} active + deactive</span>
                        </div>
                        <div id="acquisitionTrendChart" class="assetdash-chart assetdash-chart-hero"></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-4">
                <div class="card assetdash-card h-100">
                    <div class="card-body">
                        <div class="assetdash-card-head">
                            <div>
                                <h5>Status Mix</h5>
                                <p>Asset population by lifecycle status.</p>
                            </div>
                        </div>
                        <div id="statusDonutChart" class="assetdash-chart"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-2 mb-2">
            <div class="col-12 col-xl-5">
                <div class="card assetdash-card h-100">
                    <div class="card-body">
                        <div class="assetdash-card-head">
                            <div>
                                <h5>Top Departments</h5>
                                <p>Asset quantity by department.</p>
                            </div>
                        </div>
                        <div id="departmentChart" class="assetdash-chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-7">
                <div class="card assetdash-card h-100">
                    <div class="card-body">
                        <div class="assetdash-card-head">
                            <div>
                                <h5>Book Value by Asset Type</h5>
                                <p>Highest remaining value categories.</p>
                            </div>
                        </div>
                        <div id="assetTypeValueChart" class="assetdash-chart"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-2 mb-2">
            <div class="col-12 col-xl-8">
                <div class="card assetdash-card h-100">
                    <div class="card-body">
                        <div class="assetdash-card-head">
                            <div>
                                <h5>Registry Upload Trend</h5>
                                <p>New asset rows registered in the last 12 months.</p>
                            </div>
                        </div>
                        <div id="registryTrendChart" class="assetdash-chart assetdash-chart-sm"></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-4">
                <div class="card assetdash-card h-100">
                    <div class="card-body">
                        <div class="assetdash-card-head">
                            <div>
                                <h5>Plant Distribution</h5>
                                <p>Controlled assets by plant.</p>
                            </div>
                        </div>
                        <div id="plantChart" class="assetdash-chart assetdash-chart-sm"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-2">
            <div class="col-12 col-xl-7">
                <div class="card assetdash-card h-100">
                    <div class="card-body">
                        <div class="assetdash-card-head">
                            <div>
                                <h5>Latest Registered Assets</h5>
                                <p>Most recent rows imported or registered into the asset master.</p>
                            </div>
                            <a href="{{ url('/asset') }}" class="btn btn-sm btn-primary">Open Asset Table</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle assetdash-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Asset No</th>
                                        <th>Description</th>
                                        <th>Dept</th>
                                        <th>Registered</th>
                                        <th class="text-end">Acq. Cost</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($latestAssets as $asset)
                                        <tr>
                                            <td><strong>{{ $asset->asset_no }}</strong><br><small>{{ $asset->asset_type ?: '-' }}</small></td>
                                            <td>{{ \Illuminate\Support\Str::limit($asset->desc, 46) }}</td>
                                            <td>{{ $asset->dept ?: '-' }}<br><small>{{ trim(($asset->plant ?: '') . ' ' . ($asset->loc ? '(' . $asset->loc . ')' : '')) ?: '-' }}</small></td>
                                            <td>{{ optional($asset->created_at)->format('d-M-Y H:i') }}</td>
                                            <td class="text-end">{{ $shortIdr($asset->acq_cost) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-5">
                <div class="card assetdash-card h-100">
                    <div class="card-body">
                        <div class="assetdash-card-head">
                            <div>
                                <h5>Highest Book Value Assets</h5>
                                <p>Large remaining value items to keep visible.</p>
                            </div>
                        </div>
                        <div class="assetdash-rank">
                            @php $maxBook = max((float) $topBookValueAssets->max('bv_endofyear'), 1); @endphp
                            @foreach ($topBookValueAssets as $asset)
                                <div class="assetdash-rank-item">
                                    <div class="d-flex justify-content-between gap-2">
                                        <div>
                                            <strong>{{ $asset->asset_no }}</strong>
                                            <span>{{ \Illuminate\Support\Str::limit($asset->desc, 44) }}</span>
                                        </div>
                                        <em>{{ $shortIdr($asset->bv_endofyear) }}</em>
                                    </div>
                                    <div class="assetdash-bar"><span style="width: {{ min(100, ((float) $asset->bv_endofyear / $maxBook) * 100) }}%"></span></div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    .assetdash {
        background: #eef3f8;
        min-height: calc(100vh - 3.625rem);
    }

    .assetdash .container-fluid {
        padding-top: .75rem !important;
    }

    .assetdash-hero {
        align-items: stretch;
        background: linear-gradient(135deg, #0f2742 0%, #0f6b75 48%, #1d5fd7 100%);
        border-radius: 8px;
        color: #fff;
        display: flex;
        gap: 1.5rem;
        justify-content: space-between;
        overflow: hidden;
        padding: .95rem 1.25rem;
        position: relative;
    }

    .assetdash-hero h1 {
        color: #fff !important;
        font-size: 1.35rem;
        font-weight: 800;
        margin: 0;
    }

    .assetdash-hero p {
        color: rgba(255, 255, 255, .78);
        font-size: .9rem;
        margin: .25rem 0 0;
    }

    .assetdash-eyebrow {
        color: #bfdbfe;
        font-size: .72rem;
        font-weight: 800;
        letter-spacing: .12em;
        margin-bottom: .15rem;
        text-transform: uppercase;
    }

    .assetdash-hero-metrics {
        display: grid;
        gap: .5rem;
        grid-template-columns: repeat(2, minmax(150px, 1fr));
        min-width: 340px;
    }

    .assetdash-hero-metrics div {
        background: rgba(255, 255, 255, .12);
        border: 1px solid rgba(255, 255, 255, .18);
        border-radius: 8px;
        padding: .65rem .75rem;
    }

    .assetdash-hero-metrics span,
    .assetdash-kpi span,
    .assetdash-kpi small {
        display: block;
    }

    .assetdash-hero-metrics span {
        color: rgba(255, 255, 255, .72);
        font-size: .72rem;
    }

    .assetdash-hero-metrics strong {
        color: #fff;
        font-size: .98rem;
    }

    .assetdash-kpis {
        display: grid;
        gap: .55rem;
        grid-template-columns: repeat(5, minmax(0, 1fr));
    }

    .assetdash-kpi,
    .assetdash-card {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(15, 23, 42, .05);
    }

    .assetdash-kpi {
        align-items: center;
        background: #fff;
        display: flex;
        justify-content: space-between;
        min-height: 82px;
        overflow: hidden;
        padding: .72rem .85rem;
        position: relative;
    }

    .assetdash-kpi strong {
        color: #0f172a;
        display: block;
        font-size: 1.25rem;
        line-height: 1.1;
        margin: .16rem 0;
    }

    .assetdash-kpi span {
        color: #475569;
        font-size: .68rem;
        font-weight: 800;
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    .assetdash-kpi small {
        color: #475569;
        font-size: .72rem;
    }

    .assetdash-kpi i {
        font-size: 1.25rem;
        opacity: .82;
    }

    .assetdash-kpi-primary i { color: #2563eb; }
    .assetdash-kpi-success i { color: #10b981; }
    .assetdash-kpi-warning i { color: #f59e0b; }
    .assetdash-kpi-danger i { color: #ef4444; }
    .assetdash-kpi-info i { color: #06b6d4; }

    .assetdash-card {
        background: #fff;
    }

    .assetdash-card-head {
        align-items: flex-start;
        display: flex;
        gap: 1rem;
        justify-content: space-between;
        margin-bottom: .45rem;
    }

    .assetdash-card-head h5 {
        color: #0f172a;
        font-size: .9rem;
        font-weight: 800;
        margin: 0;
    }

    .assetdash-card-head p {
        color: #475569;
        font-size: .75rem;
        margin: .1rem 0 0;
    }

    .assetdash-chip {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 999px;
        color: #1d4ed8;
        font-size: .7rem;
        font-weight: 700;
        padding: .25rem .55rem;
        white-space: nowrap;
    }

    .assetdash-chart {
        height: 210px;
        width: 100%;
    }

    .assetdash-chart-hero {
        height: 255px;
    }

    .assetdash-chart-sm {
        height: 180px;
    }

    .assetdash-card .card-body {
        padding: .9rem;
    }

    .assetdash-table th {
        color: #64748b;
        font-size: .68rem;
        letter-spacing: .05em;
        text-transform: uppercase;
    }

    .assetdash-table td {
        color: #0f172a;
        font-size: .76rem;
        vertical-align: middle;
    }

    .assetdash-table small {
        color: #64748b;
    }

    .assetdash-rank {
        display: flex;
        flex-direction: column;
        gap: .5rem;
    }

    .assetdash-rank-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: .55rem .65rem;
    }

    .assetdash-rank-item strong,
    .assetdash-rank-item span {
        display: block;
    }

    .assetdash-rank-item span {
        color: #64748b;
        font-size: .72rem;
    }

    .assetdash-rank-item em {
        color: #2563eb;
        font-size: .8rem;
        font-style: normal;
        font-weight: 800;
        white-space: nowrap;
    }

    .assetdash-bar {
        background: #e2e8f0;
        border-radius: 999px;
        height: 7px;
        margin-top: .35rem;
        overflow: hidden;
    }

    .assetdash-bar span {
        background: linear-gradient(90deg, #2563eb, #06b6d4);
        display: block;
        height: 100%;
    }

    @media (max-width: 1199.98px) {
        .assetdash-kpis {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 991.98px) {
        .assetdash-kpis {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 767.98px) {
        .assetdash-hero,
        .assetdash-card-head {
            flex-direction: column;
        }

        .assetdash-hero-metrics,
        .assetdash-kpis {
            grid-template-columns: 1fr;
            min-width: 0;
            width: 100%;
        }
    }
</style>

<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script>
    am5.ready(function () {
        const rupiah = value => {
            value = Number(value || 0);
            if (value >= 1000000000000) return 'Rp ' + (value / 1000000000000).toFixed(2) + ' T';
            if (value >= 1000000000) return 'Rp ' + (value / 1000000000).toFixed(2) + ' B';
            if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(1) + ' M';
            return 'Rp ' + value.toLocaleString('id-ID');
        };

        const applyTheme = root => {
            root.setThemes([am5themes_Animated.new(root)]);
            root.numberFormatter.set('numberFormat', '#,###');
        };

        const makeXY = (id, options = {}) => {
            const root = am5.Root.new(id);
            applyTheme(root);
            const chart = root.container.children.push(am5xy.XYChart.new(root, {
                panX: options.panX ?? false,
                panY: false,
                wheelX: options.wheelX ?? 'none',
                wheelY: options.wheelY ?? 'none',
                paddingLeft: 0,
            }));

            return { root, chart };
        };

        function drawAcquisitionTrend() {
            const data = @json($yearlyAcquisitionData);
            const { root, chart } = makeXY('acquisitionTrendChart', { panX: true, wheelX: 'panX', wheelY: 'zoomX' });

            const xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
                categoryField: 'year',
                renderer: am5xy.AxisRendererX.new(root, { minGridDistance: 34 }),
            }));
            xAxis.data.setAll(data);

            const costAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
                min: 0,
                renderer: am5xy.AxisRendererY.new(root, {}),
            }));
            costAxis.get('renderer').labels.template.adapters.add('text', text => text);

            const countAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
                min: 0,
                renderer: am5xy.AxisRendererY.new(root, { opposite: true }),
            }));

            const costSeries = chart.series.push(am5xy.ColumnSeries.new(root, {
                name: 'Acquisition Cost',
                xAxis,
                yAxis: costAxis,
                valueYField: 'acq_cost',
                categoryXField: 'year',
                tooltip: am5.Tooltip.new(root, { labelText: '{categoryX}\nCost: {valueY.formatNumber("#,###")}' }),
            }));
            costSeries.columns.template.setAll({
                fill: am5.color(0x2563eb),
                strokeOpacity: 0,
                cornerRadiusTL: 6,
                cornerRadiusTR: 6,
                width: am5.percent(70),
            });
            costSeries.data.setAll(data);

            const countSeries = chart.series.push(am5xy.LineSeries.new(root, {
                name: 'Asset Count',
                xAxis,
                yAxis: countAxis,
                valueYField: 'total',
                categoryXField: 'year',
                stroke: am5.color(0xf59e0b),
                tooltip: am5.Tooltip.new(root, { labelText: '{categoryX}\nAssets: {valueY}' }),
            }));
            countSeries.strokes.template.setAll({ strokeWidth: 3 });
            countSeries.bullets.push(() => am5.Bullet.new(root, {
                sprite: am5.Circle.new(root, { radius: 4, fill: am5.color(0xf59e0b), stroke: am5.color(0xffffff), strokeWidth: 2 }),
            }));
            countSeries.data.setAll(data);

            const legend = chart.children.push(am5.Legend.new(root, { centerX: am5.p50, x: am5.p50 }));
            legend.data.setAll(chart.series.values);
            chart.set('cursor', am5xy.XYCursor.new(root, { behavior: 'zoomX' }));
            costSeries.appear(800);
            countSeries.appear(800);
            chart.appear(800, 100);
        }

        function drawStatusDonut() {
            const data = @json($statusChartData);
            const root = am5.Root.new('statusDonutChart');
            applyTheme(root);
            const chart = root.container.children.push(am5percent.PieChart.new(root, { innerRadius: am5.percent(62) }));
            const series = chart.series.push(am5percent.PieSeries.new(root, {
                valueField: 'total',
                categoryField: 'status',
                tooltip: am5.Tooltip.new(root, { labelText: '{category}: {value} assets' }),
            }));
            series.slices.template.adapters.add('fill', (fill, target) => am5.color(target.dataItem.dataContext.color));
            series.slices.template.adapters.add('stroke', (stroke, target) => am5.color(target.dataItem.dataContext.color));
            series.labels.template.setAll({ fontSize: 11, text: '{category}' });
            series.data.setAll(data);
            chart.children.push(am5.Label.new(root, {
                text: '{{ number_format($totalAsset) }}\nAssets',
                centerX: am5.p50,
                centerY: am5.p50,
                x: am5.p50,
                y: am5.p50,
                textAlign: 'center',
                fontWeight: '800',
                fill: am5.color(0x0f172a),
            }));
            series.appear(800, 100);
        }

        function drawHorizontalBars(id, data, categoryField, valueField, color, tooltipText, valueLabelFormatter) {
            const { root, chart } = makeXY(id);
            const yAxis = chart.yAxes.push(am5xy.CategoryAxis.new(root, {
                categoryField,
                renderer: am5xy.AxisRendererY.new(root, { inversed: true, minGridDistance: 22 }),
            }));
            yAxis.get('renderer').labels.template.setAll({ fontSize: 11 });
            yAxis.data.setAll(data);

            const xAxis = chart.xAxes.push(am5xy.ValueAxis.new(root, {
                min: 0,
                renderer: am5xy.AxisRendererX.new(root, {}),
            }));

            const series = chart.series.push(am5xy.ColumnSeries.new(root, {
                xAxis,
                yAxis,
                valueXField: valueField,
                categoryYField: categoryField,
                tooltip: am5.Tooltip.new(root, { labelText: tooltipText }),
            }));
            series.columns.template.setAll({
                fill: am5.color(color),
                strokeOpacity: 0,
                cornerRadiusTR: 6,
                cornerRadiusBR: 6,
            });
            series.bullets.push(() => {
                const label = am5.Label.new(root, {
                    text: '',
                    centerX: am5.p100,
                    dx: -8,
                    fill: am5.color(0xffffff),
                    fontWeight: '700',
                    fontSize: 11,
                    populateText: true,
                    oversizedBehavior: 'hide',
                });

                label.adapters.add('text', function (text, target) {
                    const value = target.dataItem?.dataContext?.[valueField] || 0;
                    return valueLabelFormatter ? valueLabelFormatter(value) : Number(value).toLocaleString('en-US');
                });

                return am5.Bullet.new(root, {
                    locationX: 1,
                    sprite: label,
                });
            });
            series.data.setAll(data);
            series.appear(800);
            chart.appear(800, 100);
        }

        function drawRegistryTrend() {
            const data = @json($registryTrendData);
            const { root, chart } = makeXY('registryTrendChart');
            const xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
                categoryField: 'month',
                renderer: am5xy.AxisRendererX.new(root, { minGridDistance: 32 }),
            }));
            xAxis.get('renderer').labels.template.setAll({ fontSize: 10, rotation: -20, centerY: am5.p50 });
            xAxis.data.setAll(data);

            const yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, { min: 0, renderer: am5xy.AxisRendererY.new(root, {}) }));
            const series = chart.series.push(am5xy.ColumnSeries.new(root, {
                xAxis,
                yAxis,
                valueYField: 'total',
                categoryXField: 'month',
                tooltip: am5.Tooltip.new(root, { labelText: '{categoryX}: {valueY} registered' }),
            }));
            series.columns.template.setAll({ fill: am5.color(0x06b6d4), strokeOpacity: 0, cornerRadiusTL: 6, cornerRadiusTR: 6 });
            series.data.setAll(data);
            series.appear(800);
            chart.appear(800, 100);
        }

        drawAcquisitionTrend();
        drawStatusDonut();
        drawHorizontalBars('departmentChart', @json($departmentChartData), 'department', 'total', 0x2563eb, '{categoryY}: {valueX} assets');
        drawHorizontalBars('assetTypeValueChart', @json($assetTypeChartData), 'category', 'book_value', 0x10b981, '{categoryY}\nBook Value: {valueX.formatNumber("#,###")}', rupiah);
        drawRegistryTrend();
        drawHorizontalBars('plantChart', @json($plantChartData), 'plant', 'total', 0x6366f1, '{categoryY}: {valueX} assets');
    });
</script>
@endsection
