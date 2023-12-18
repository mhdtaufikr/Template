<?php

namespace App\Http\Controllers;
use App\Models\CostCenter;

use Illuminate\Http\Request;

class CostCenterController extends Controller
{
    public function index(){
        $costCenterData = CostCenter::get();
        return view("cost.index", compact("costCenterData"));
    }

    public function store(Request $request)
    {
        $request->validate([
            'costctr' => 'required|numeric',
            'coar' => 'required|numeric',
            'cocd' => 'required|numeric',
            'cctc' => 'required|string|max:255',
            'pic' => 'required|string|max:255',
            'remarks' => 'required|string|max:255',
        ]);
    
        // Check if a CostCenter with the same cost_ctr already exists
        $existingCostCenter = CostCenter::where('cost_ctr', $request->costctr)->first();
    
        if ($existingCostCenter) {
            // CostCenter with the same cost_ctr already exists, handle accordingly
            return redirect()->back()->with('failed', 'Cost center with this cost_ctr already exists.');
        }
    
        // CostCenter with the same cost_ctr does not exist, create a new record
        try {
            CostCenter::create([
                'cost_ctr' => $request->costctr,
                'coar' => $request->coar,
                'cocd' => $request->cocd,
                'cctc' => $request->cctc,
                'pic' => $request->pic,
                'user_pic' => $request->userpic,
                'remarks' => $request->remarks,
            ]);
    
            return redirect()->back()->with('status', 'Cost center created successfully');
        } catch (\Exception $e) {
            // Handle any exception that may occur during the creation
            return redirect()->back()->with('failed', 'Failed to create cost center. Please try again.');
        }
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'costctr' => 'required|numeric',
            'coar' => 'required|numeric',
            'cocd' => 'required|numeric',
            'cctc' => 'required|string|max:255',
            'pic' => 'required|string|max:255',
            'remarks' => 'required|string|max:255',
        ]);
    
        $costCenter = CostCenter::findOrFail($id);
    
        // Check if the new values are different from the existing values
        if (
            $costCenter->cost_ctr != $request->costctr ||
            $costCenter->coar != $request->coar ||
            $costCenter->cocd != $request->cocd ||
            $costCenter->cctc != $request->cctc ||
            $costCenter->pic != $request->pic ||
            $costCenter->user_pic != $request->userpic ||
            $costCenter->remarks != $request->remarks
        ) {
            try {
                // Update the model attributes
                $costCenter->update([
                    'cost_ctr' => $request->costctr,
                    'coar' => $request->coar,
                    'cocd' => $request->cocd,
                    'cctc' => $request->cctc,
                    'pic' => $request->pic,
                    'user_pic' => $request->userpic,
                    'remarks' => $request->remarks,
                ]);
    
                return redirect()->back()->with('status', 'Cost center updated successfully');
            } catch (\Exception $e) {
                // Handle any exception that may occur during the update
                return redirect()->back()->with('failed', 'Failed to update cost center. Please try again.');
            }
        } else {
            // No changes, so no update is needed
            return redirect()->back()->with('failed', 'No changes made to the cost center.');
        }
    }

    public function delete($id)
    {
        try {
            $costCenter = CostCenter::findOrFail($id);
            $costCenter->delete();

            return redirect()->back()->with('status', 'Cost center deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('failed', 'Failed to delete cost center. Please try again.');
        }
    }

}
