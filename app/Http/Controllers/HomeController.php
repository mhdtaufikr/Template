<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssetHeader;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $statusRows = AssetHeader::select('status', DB::raw('COUNT(*) as total'), DB::raw('COALESCE(SUM(acq_cost), 0) as acq_cost'))
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $countStatusOne = (int) optional($statusRows->get(1))->total;
        $countStatusZero = (int) optional($statusRows->get(0))->total;
        $countStatusDisposal = (int) optional($statusRows->get(2))->total;
        $sumAcqCostStatusOne = (float) optional($statusRows->get(1))->acq_cost;
        $sumAcqCostStatusZero = (float) optional($statusRows->get(0))->acq_cost;
        $sumAcqCostStatusDisposal = (float) optional($statusRows->get(2))->acq_cost;

        $totalAsset = AssetHeader::count();
        $activeControlledAsset = $countStatusOne + $countStatusZero;
        $sumAcqCostTotal = (float) AssetHeader::sum('acq_cost');
        $sumBookValueTotal = (float) AssetHeader::sum('bv_endofyear');
        $thisYearAsset = AssetHeader::whereYear('created_at', date('Y'))->count();
        $thisMonthAsset = AssetHeader::whereYear('created_at', date('Y'))->whereMonth('created_at', date('m'))->count();

        $statusChartData = collect([
            ['status' => 'Active', 'total' => $countStatusOne, 'value' => $sumAcqCostStatusOne, 'color' => '#10b981'],
            ['status' => 'Deactive', 'total' => $countStatusZero, 'value' => $sumAcqCostStatusZero, 'color' => '#f59e0b'],
            ['status' => 'Disposal', 'total' => $countStatusDisposal, 'value' => $sumAcqCostStatusDisposal, 'color' => '#ef4444'],
        ])->filter(fn ($row) => $row['total'] > 0)->values();

        $assetTypeChartData = AssetHeader::select(
                DB::raw("COALESCE(NULLIF(asset_type, ''), 'Unclassified') as category"),
                DB::raw('COUNT(*) as total'),
                DB::raw('COALESCE(SUM(bv_endofyear), 0) as book_value')
            )
            ->groupBy('category')
            ->orderByDesc('book_value')
            ->limit(8)
            ->get()
            ->map(fn ($row) => [
                'category' => $row->category,
                'total' => (int) $row->total,
                'book_value' => (float) $row->book_value,
            ]);

        $departmentChartData = AssetHeader::select(
                DB::raw("COALESCE(NULLIF(dept, ''), 'Unassigned') as department"),
                DB::raw('COUNT(*) as total'),
                DB::raw('COALESCE(SUM(acq_cost), 0) as acq_cost')
            )
            ->whereIn('status', [1, 0])
            ->groupBy('department')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(fn ($row) => [
                'department' => $row->department,
                'total' => (int) $row->total,
                'acq_cost' => (float) $row->acq_cost,
            ]);

        $yearlyAcquisitionData = AssetHeader::select(
                DB::raw('YEAR(acq_date) as year'),
                DB::raw('COUNT(*) as total'),
                DB::raw('COALESCE(SUM(acq_cost), 0) as acq_cost')
            )
            ->whereNotNull('acq_date')
            ->whereIn('status', [1, 0])
            ->groupBy('year')
            ->orderBy('year')
            ->get()
            ->map(fn ($row) => [
                'year' => (string) $row->year,
                'total' => (int) $row->total,
                'acq_cost' => (float) $row->acq_cost,
            ]);

        $registryTrendData = AssetHeader::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('COUNT(*) as total')
            )
            ->whereNotNull('created_at')
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $registryTrendData = collect(range(11, 0))->map(function ($offset) use ($registryTrendData) {
            $date = now()->subMonths($offset);
            $key = $date->format('Y-m');

            return [
                'month' => $date->format('M y'),
                'total' => (int) optional($registryTrendData->get($key))->total,
            ];
        });

        $plantChartData = AssetHeader::select(
                DB::raw("COALESCE(NULLIF(plant, ''), 'Unassigned') as plant"),
                DB::raw('COUNT(*) as total')
            )
            ->whereIn('status', [1, 0])
            ->groupBy('plant')
            ->orderByDesc('total')
            ->limit(8)
            ->get()
            ->map(fn ($row) => [
                'plant' => $row->plant,
                'total' => (int) $row->total,
            ]);

        $latestAssets = AssetHeader::select('asset_no', 'desc', 'asset_type', 'dept', 'plant', 'loc', 'created_at', 'acq_cost')
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        $topBookValueAssets = AssetHeader::select('asset_no', 'desc', 'asset_type', 'bv_endofyear')
            ->orderByDesc('bv_endofyear')
            ->limit(6)
            ->get();

        return view('home.index', compact(
            'countStatusOne',
            'countStatusZero',
            'countStatusDisposal',
            'sumAcqCostStatusOne',
            'sumAcqCostStatusZero',
            'sumAcqCostStatusDisposal',
            'totalAsset',
            'activeControlledAsset',
            'sumAcqCostTotal',
            'sumBookValueTotal',
            'thisYearAsset',
            'thisMonthAsset',
            'statusChartData',
            'assetTypeChartData',
            'departmentChartData',
            'yearlyAcquisitionData',
            'registryTrendData',
            'plantChartData',
            'latestAssets',
            'topBookValueAssets'
        ));
    }
}
