<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssetHeader;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil data distribusi jenis aset dari model Asset
        $assetDistribution = AssetHeader::select('asset_type', \DB::raw('count(*) as total'))
        ->groupBy('asset_type')
        ->whereIn('status', [1, 0])
        ->get();

        // Calculate the total count of assets
        $totalCount = $assetDistribution->sum('total');

        // Calculate the percentage for each asset type
        foreach ($assetDistribution as $asset) {
            $asset->percentage = number_format(($asset->total / $totalCount) * 100, 2) . '%';
        }
            // Fetch data from asset_headers table
        $assetData = AssetHeader::select('acq_date', 'acq_cost')->whereIn('status', [1, 0])->orderBy('acq_date')->get();

        $chartData = [];
        $acquisitionCostByYear = []; // Store the summed acquisition costs by year

        foreach ($assetData as $asset) {
            // Extracting the year from the acquisition date
            $year = date('Y', strtotime($asset->acq_date));
            // Summing up acquisition costs for the same year
            if (isset($acquisitionCostByYear[$year])) {
                $acquisitionCostByYear[$year] += (float) $asset->acq_cost;
            } else {
                $acquisitionCostByYear[$year] = (float) $asset->acq_cost;
            }
        }

        // Prepare data points using unique years and summed acquisition costs
        foreach ($acquisitionCostByYear as $year => $totalCost) {
            $chartData[] = [
                'x' => intval($year), // Convert year to integer for better handling
                'y' => $totalCost
            ];
        }

        // Fetch data for quantity by department
        $quantityByDepartment = AssetHeader::select('dept', \DB::raw('count(*) as total'))
            ->groupBy('dept')
            ->whereIn('status', [1, 0])
            ->get();

        // Get the current year
        $currentYear = date('Y');

        // Calculate the start year for the last 5 years
        $startYear = $currentYear - 4; // Since we want data for the last 5 years

        // Fetch data from asset_headers table for the last 5 years based on acquisition date
        $assets = AssetHeader::select('acq_date', 'acq_cost')
            ->whereYear('acq_date', '>=', $startYear)
            ->whereIn('status', [1, 0])
            ->get();

        $acquisitionData = [];

        // Prepare data points for the bar chart
        foreach ($assets as $asset) {
            $acqDate = $asset->acq_date;
            $acqCost = (float) $asset->acq_cost;

            // Sum up acquisition cost by year
            $acqYear = date('Y', strtotime($acqDate));
            if (isset($acquisitionData[$acqYear])) {
                $acquisitionData[$acqYear] += $acqCost;
            } else {
                $acquisitionData[$acqYear] = $acqCost;
            }
        }

        $barChartData = [];

        // Convert acquisitionData into barChartData format
        foreach ($acquisitionData as $year => $acqCost) {
            $barChartData[] = [
                'y' => $acqCost,
                'label' => $year
            ];
        }

        // Sort $barChartData by the "label" key in ascending order
        usort($barChartData, function($a, $b) {
            return $a['label'] - $b['label'];
        });

        // Fetch data from asset_headers table
        $assets = AssetHeader::select('asset_type', 'bv_endofyear')->get();

        $assetTypeData = [];

        // Prepare data points for the bar chart
        foreach ($assets as $asset) {
            $assetType = $asset->asset_type;
            $bvEndOfYear = (float) $asset->bv_endofyear;

            // Sum up BV end of year by asset type
            if (isset($assetTypeData[$assetType])) {
                $assetTypeData[$assetType] += $bvEndOfYear;
            } else {
                $assetTypeData[$assetType] = $bvEndOfYear;
            }
        }

        $barChartDatatype = [];

        // Convert assetTypeData into barChartData format
        foreach ($assetTypeData as $assetType => $bvEndOfYear) {
            $barChartDatatype[] = [
                'y' => $bvEndOfYear,
                'label' => $assetType
            ];
        }
       // Count and sum acq_cost for assets with status 1
       $countStatusOne = AssetHeader::where('status', 1)->count();
       $sumAcqCostStatusOne = AssetHeader::where('status', 1)->sum('acq_cost');

       // Count and sum acq_cost for assets with status 0
       $countStatusZero = AssetHeader::where('status', 0)->count();
       $sumAcqCostStatusZero = AssetHeader::where('status', 0)->sum('acq_cost');

       // Count and sum acq_cost for assets with status 1 or 0
       $totalAsset = AssetHeader::whereIn('status', [1, 0])->count();
       $sumAcqCostTotal = AssetHeader::whereIn('status', [1, 0])->sum('acq_cost');


        return view('home.index', compact('sumAcqCostStatusOne','sumAcqCostStatusZero','sumAcqCostTotal','assetDistribution','chartData','quantityByDepartment','barChartData','barChartDatatype','countStatusOne','countStatusZero','totalAsset'));
    }
}

