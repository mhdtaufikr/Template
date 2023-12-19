<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssetDetail;
use App\Models\AssetHeader;
use App\Models\Dropdown;
use App\Models\AssetCategory;
use App\Models\Department;
use App\Models\LocHeader;
use App\Models\LocDetail;

class AssetController extends Controller
{
    public function index(){
        $assetData = AssetHeader::get();
        $dropdownUom = Dropdown::where('category','UOM')->get();
        $assetCategory = AssetCategory::get();
        $dept = Department::get();
        $locHeader = LocHeader::get();
        $locDetail = LocDetail::get();

        return view("asset.main",compact("assetData","dropdownUom","assetCategory","dept","locHeader","locDetail"));
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'asset_no' => 'required|string|max:255',
            'desc' => 'required|string|max:255',
            'qty' => 'required|numeric',
            'uom' => 'required|string|max:255',
            'date' => 'required|date',
            'cost' => 'required',
            'asset_type' => 'required',
            'plant' => 'required|string|max:255',
            'loc' => 'required|string|max:255',
            'dept' => 'required|string|max:255',
            'cost_center' => 'required|string|max:255',
            'img' => 'required|file|mimes:jpeg,png,jpg,gif|max:2048', // Assuming it's an image file
            'bv_end' => 'required',
        ]);
    
        try {

            // Check if asset_no already exists
            $existingAsset = AssetHeader::where('asset_no', $request->asset_no)->first();

            if ($existingAsset) {
                return redirect()->back()->with('failed', 'Asset with the same asset_no already exists.');
            }

            // Remove commas from the 'cost' and 'bv_end' fields
            $cost = (int) str_replace(',', '', $request->cost);
            $bvEnd = (int) str_replace(',', '', $request->bv_end);            
    
           // Handle file upload
        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $fileName = uniqid() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('images');
            $file->move($destinationPath, $fileName);

            // Set the image path
            $imgPath = 'images/' . $fileName;
        } else {
            // Default value if no image is provided
            $imgPath = null;
        }
    
            // Create a new AssetHeader instance and fill it with the validated data
            $assetHeader = new AssetHeader([
                'asset_no' => $request->asset_no,
                'desc'  => $request->desc,
                'qty' => $request->qty,
                'uom' => $request->uom,
                'asset_type' => $request->asset_type,
                'acq_date' => $request->date,
                'acq_cost' => $cost,
                'po_no' => $request->po_no,
                'serial_no' => $request->serial_no,
                'plant' => $request->plant,
                'loc' => $request->loc,
                'dept' => $request->dept,
                'status' => 1,
                'cost_center' => $request->cost_center,
                'img' => $imgPath,
                'bv_endofyear' => $bvEnd,
            ]);
    
            // Save the AssetHeader instance to the database
            $assetHeader->save();
    
            return redirect()->back()->with('status', 'Asset header created successfully');
        } catch (\Exception $e) {
            dd($e);
            // Handle any exception that may occur during the creation
            return redirect()->back()->with('failed', 'Failed to create asset header. Please try again.');
        }
    }

    public function update(Request $request, $id) {
        // Find the AssetHeader model by ID
        $assetHeader = AssetHeader::findOrFail($id);
    
        $request->validate([
            'asset_no' => 'required|string|max:255',
            'desc' => 'required|string|max:255',
            'qty' => 'required|numeric',
            'uom' => 'required|string|max:255',
            'date' => 'required|date',
            'cost_edit' => 'required',
            'asset_type' => 'required',
            'plant' => 'required|string|max:255',
            'loc' => 'required|string|max:255',
            'dept' => 'required|string|max:255',
            'cost_center' => 'required|string|max:255',
            'img' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048', // Assuming it's an image file
            'bv_end' => 'required',
        ]);
    
        try {
            // Check if any changes have been made to the model attributes
            if (
                $assetHeader->asset_no != $request->asset_no ||
                $assetHeader->desc != $request->desc ||
                $assetHeader->qty != $request->qty ||
                $assetHeader->uom != $request->uom ||
                $assetHeader->acq_date != $request->date ||
                $assetHeader->acq_cost != (int) str_replace(',', '', $request->cost_edit) ||
                $assetHeader->asset_type != $request->asset_type ||
                $assetHeader->plant != $request->plant ||
                $assetHeader->loc != $request->loc ||
                $assetHeader->dept != $request->dept ||
                $assetHeader->cost_center != $request->cost_center ||
                $assetHeader->bv_endofyear != (int) str_replace(',', '', $request->bv_end) ||
                ($request->hasFile('img') && $assetHeader->img != null)
            ) {
                // Update the attributes
                $assetHeader->asset_no = $request->asset_no;
                $assetHeader->desc = $request->desc;
                $assetHeader->qty = $request->qty;
                $assetHeader->uom = $request->uom;
                $assetHeader->acq_date = $request->date;
                $assetHeader->acq_cost = (int) str_replace(',', '', $request->cost_edit);
                $assetHeader->asset_type = $request->asset_type;
                $assetHeader->plant = $request->plant;
                $assetHeader->loc = $request->loc;
                $assetHeader->dept = $request->dept;
                $assetHeader->cost_center = $request->cost_center;
                $assetHeader->bv_endofyear = (int) str_replace(',', '', $request->bv_end);
    
                // Handle file upload
                if ($request->hasFile('img')) {
                    // Delete old image file
                    if ($assetHeader->img) {
                        unlink(public_path($assetHeader->img));
                    }
    
                    // Upload and save the new image
                    $file = $request->file('img');
                    $fileName = uniqid() . '_' . $file->getClientOriginalName();
                    $destinationPath = public_path('images');
                    $file->move($destinationPath, $fileName);
    
                    // Set the new image path
                    $imgPath = 'images/' . $fileName;
                    $assetHeader->img = $imgPath;
                }
    
                // Save the AssetHeader
                $assetHeader->save();
    
                return redirect()->back()->with('status', 'Asset header updated successfully');
            } else {
                // No changes, so no update is needed
                return redirect()->back()->with('failed', 'No changes made to the asset header.');
            }
        } catch (\Exception $e) {
            // Handle any exception that may occur during the update
            return redirect()->back()->with('failed', 'Failed to update asset header. Please try again.');
        }
    }

    public function detail($id){
        $id = decrypt($id);

        $assetHeaderData = AssetHeader::where('id', $id)->first();
        $assetDetailData = AssetDetail::where('asset_header_id', $id)->get();
        $dropdownUom = Dropdown::where('category','UOM')->get();
        $assetCategory = AssetCategory::get();
        $locHeader = LocHeader::get();
        $locDetail = LocDetail::get();
        $dept = Department::get();
        return view('asset.detail', compact('assetHeaderData','assetDetailData','dropdownUom','assetCategory','locHeader','locDetail','dept'));
    }
    
    
    
}
