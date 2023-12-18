<?php

namespace App\Http\Controllers;
use App\Models\AssetCategory;

use Illuminate\Http\Request;

class AssetCategoryController extends Controller
{
    public function index(){
        $assetData = AssetCategory::get();
        return view("asset.index", compact("assetData"));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class' => 'required|numeric', 
            'description' => 'required',
        ]);
    
        $existingAssetCategory = AssetCategory::where('class', $request->class)->first();
    
        if ($existingAssetCategory) {
            // Class already exists, handle accordingly
            return redirect()->back()->with('failed', 'Asset category with this class already exists.');
        }
    
        try {
            // Class does not exist, create a new record
            AssetCategory::create([
                'class' => $request->class,
                'desc' => $request->description,
            ]);
    
            return redirect()->back()->with('status', 'Asset category created successfully');
        } catch (\Exception $e) {
            // Handle any exception that may occur during the creation
            return redirect()->back()->with('failed', 'Failed to create asset category. Please try again.');
        }
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'class' => 'required|numeric',
        'description' => 'required',
    ]);

    $assetCategory = AssetCategory::findOrFail($id);

    // Get the original attributes
    $originalAttributes = $assetCategory->getOriginal();

    // Compare the original attributes with the new values
    if ($originalAttributes['class'] != $request->class || $originalAttributes['desc'] != $request->description) {
        try {
            // Update the model attributes
            $assetCategory->update([
                'class' => $request->class,
                'desc' => $request->description,
            ]);

            return redirect()->back()->with('status', 'Asset category updated successfully');
        } catch (\Exception $e) {
            // Handle any exception that may occur during the update
            return redirect()->back()->with('failed', 'Failed to update asset category. Please try again.');
        }
    } else {
        // No changes, so no update is needed
        return redirect()->back()->with('failed', 'No changes made to the asset category.');
    }
}
public function delete($id)
{
    try {
        $assetCategory = AssetCategory::findOrFail($id);
        $assetCategory->delete();

        return redirect()->back()->with('status', 'Asset category deleted successfully');
    } catch (\Exception $e) {
        return redirect()->back()->with('failed', 'Failed to delete asset category. Please try again.');
    }
}

}
