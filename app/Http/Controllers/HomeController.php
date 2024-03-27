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
            ->get();
            // Fetch data from asset_headers table
        $assetData = AssetHeader::select('acq_date', 'acq_cost')->orderBy('acq_date')->get();

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
            ->get();

        // Fetch data for location distribution
        $locationDistribution = AssetHeader::selectRaw('IFNULL(NULLIF(plant, ""), "N/A") AS plantName, COUNT(*) AS total')
        ->groupBy('plantName')
        ->get();


        return view('home.index', compact('assetDistribution','chartData','assetDistribution','quantityByDepartment','locationDistribution'));
    }
}

