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
use App\Models\CostCenter;
use App\Exports\ExcelExport;
use Maatwebsite\Excel\Facades\Excel;

class AssetController extends Controller
{
    public function index(){
        $assetData = AssetHeader::get();
        $dropdownUom = Dropdown::where('category','UOM')->get();
        $assetCategory = AssetCategory::get();
        $dept = Department::get();
        $locHeader = LocHeader::get();
        $locDetail = LocDetail::get();
        $costCenter = CostCenter::get();

        return view("asset.main",compact("assetData","dropdownUom","assetCategory","dept","locHeader","locDetail","costCenter"));
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

    public function disposal($id){
        $id = decrypt($id);
    
        try {
            // Find the AssetHeader model by ID
            $assetHeader = AssetHeader::findOrFail($id);
    
            // Check if the status is already set to 0
            if ($assetHeader->status != 0) {
                // Update the status attribute to 0
                $assetHeader->status = 0;
                
                // Save the AssetHeader
                $assetHeader->save();
    
                // Update status in associated AssetDetails
                AssetDetail::where('asset_header_id', $id)->update(['status' => 0]);
    
                return redirect()->back()->with('status', 'Asset disposed successfully');
            } else {
                // Status is already 0, no update needed
                return redirect()->back()->with('status', 'Asset is already disposed.');
            }
        } catch (\Exception $e) {
            // Handle any exception that may occur during the update
            return redirect()->back()->with('failed', 'Failed to dispose asset. Please try again.');
        }
    }
    
    public function active($id){
        $id = decrypt($id);
    
        try {
            // Find the AssetHeader model by ID
            $assetHeader = AssetHeader::findOrFail($id);
    
            // Check if the status is already set to 1
            if ($assetHeader->status != 1) {
                // Update the status attribute to 1
                $assetHeader->status = 1;
                
                // Save the AssetHeader
                $assetHeader->save();
    
                // Update status in associated AssetDetails
                AssetDetail::where('asset_header_id', $id)->update(['status' => 1]);
    
                return redirect()->back()->with('status', 'Asset activated successfully');
            } else {
                // Status is already 1, no update needed
                return redirect()->back()->with('status', 'Asset is already active.');
            }
        } catch (\Exception $e) {
            // Handle any exception that may occur during the update
            return redirect()->back()->with('failed', 'Failed to activate asset. Please try again.');
        }
    }
    

    public function detailStore(Request $request){
        // Validate the request data
        $request->validate([
            'asset_no' => 'required|string|max:255',
            'sub_asset' => 'required|integer',
            'desc' => 'required|string|max:255',
            'qty' => 'required|numeric',
            'uom' => 'required|string|max:255',
            'date' => 'required|date',
            'asset_type' => 'required|integer',
            'cost' => 'required',
            'img' => 'required|file|mimes:jpeg,png,jpg,gif|max:2048',
            'bv_end' => 'required',
        ]);

        try {
            // Remove commas from the 'cost' and 'bv_end' fields
            $cost = (int) str_replace(',', '', $request->cost);
            $bvEnd = (int) str_replace(',', '', $request->bv_end);

            // Check if sub asset already exists
            $existingDetail = AssetDetail::where('asset_header_id', $request->id)
                ->where('sub_asset', $request->sub_asset)
                ->first();

            if ($existingDetail) {
                return redirect()->back()->with('failed', 'Sub Asset already exists for this Asset Header.');
            }

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

            // Create a new AssetDetail instance and fill it with the validated data
            $assetDetail = new AssetDetail([
                'asset_header_id' => $request->id,
                'asset_no' => $request->asset_no,
                'sub_asset' => $request->sub_asset,
                'desc' => $request->desc,
                'qty' => $request->qty,
                'uom' => $request->uom,
                'asset_type' => $request->asset_type,
                'date' => $request->date,
                'cost' => $cost,
                'po_no' => $request->po_no,
                'serial_no' => $request->serial_no,
                'img' => $imgPath,
                'status' => 1,
                'bv_endofyear' => $bvEnd,
            ]);

            // Save the AssetDetail instance to the database
            $assetDetail->save();

            return redirect()->back()->with('status', 'Asset detail created successfully');
        } catch (\Exception $e) {
            // Handle any exception that may occur during the creation
            return redirect()->back()->with('failed', 'Failed to create asset detail. Please try again.');
        }
    }

    public function detailUpdate(Request $request, $id)
    {
        try {
            $assetDetail = AssetDetail::findOrFail($id);
    
            // Validate the request data
            $request->validate([
                'asset_no' => 'required|string|max:255',
                'sub_asset' => 'required|numeric',
                'desc' => 'required|string|max:255',
                'qty' => 'required|numeric',
                'uom' => 'required|string|max:255',
                'date' => 'required|date',
                'asset_type' => 'required|numeric',
                'costEdit' => 'required',
                'po_no' => 'nullable|string|max:255',
                'serial_no' => 'nullable|string|max:255',
                'img' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
                'bv_endEdit' => 'required',
            ]);
    
            // Remove commas from the 'costEdit' and 'bv_endEdit' fields and turn them into integers
            $cost = (int) str_replace(',', '', $request->costEdit);
            $bvEnd = (int) str_replace(',', '', $request->bv_endEdit);
    
            // Compare current values with the new values
            $changes = [
                'asset_no' => $request->asset_no,
                'sub_asset' => $request->sub_asset,
                'desc' => $request->desc,
                'qty' => $request->qty,
                'uom' => $request->uom,
                'date' => $request->date,
                'asset_type' => $request->asset_type,
                'cost' => $cost,
                'po_no' => $request->po_no,
                'serial_no' => $request->serial_no,
                'bv_endofyear' => $bvEnd,
            ];
    
            $hasChanges = collect($changes)->some(function ($value, $key) use ($assetDetail) {
                return $assetDetail->$key != $value;
            });
    
            if ($hasChanges) {
                // Handle file upload and update image path
                if ($request->hasFile('img')) {
                    // Delete the old image
                    if ($assetDetail->img) {
                        unlink(public_path($assetDetail->img));
                    }
    
                    // Upload the new image
                    $file = $request->file('img');
                    $fileName = uniqid() . '_' . $file->getClientOriginalName();
                    $destinationPath = public_path('images');
                    $file->move($destinationPath, $fileName);
    
                    // Set the new image path
                    $imgPath = 'images/' . $fileName;
                } else {
                    // No image change
                    $imgPath = $assetDetail->img;
                }
    
                // Update the model with the new values
                $assetDetail->update([
                    'asset_no' => $request->asset_no,
                    'sub_asset' => $request->sub_asset,
                    'desc' => $request->desc,
                    'qty' => $request->qty,
                    'uom' => $request->uom,
                    'date' => $request->date,
                    'asset_type' => $request->asset_type,
                    'cost' => $cost,
                    'po_no' => $request->po_no,
                    'serial_no' => $request->serial_no,
                    'img' => $imgPath,
                    'bv_endofyear' => $bvEnd,
                ]);
    
                return redirect()->back()->with('status', 'Asset detail updated successfully');
            } else {
                return redirect()->back()->with('failed', 'No changes made');
            }
        } catch (\Exception $e) {
            // Handle any exception that may occur during the update
            return redirect()->back()->with('failed', 'Failed to update asset detail. Please try again.');
        }
    }

    public function detailDelete($id){
        try {
            $assetDetail = AssetDetail::findOrFail($id);
            
            // Delete the associated image file
            if ($assetDetail->img) {
                unlink(public_path($assetDetail->img));
            }
    
            // Delete the AssetDetail
            $assetDetail->delete();
    
            return redirect()->back()->with('status', 'Asset detail deleted successfully');
        } catch (\Exception $e) {
            dd($e);
            // Handle any exception that may occur during the delete
            return redirect()->back()->with('failed', 'Failed to delete asset detail. Please try again.');
        }
    }

    public function detailDisposal($idHeader,$id){
        $idHeader = decrypt($idHeader);
        $id = decrypt($id);
    
        try {
            // Find the AssetHeader model by ID
            $assetDetail = AssetDetail::findOrFail($id);
    
            // Check if the status is already set to 1
            if ($assetDetail->status != 0) {
                // Update the status attribute to 1
                $assetDetail->status = 0;
    
                // Save the AssetHeader
                $assetDetail->save();
    
                return redirect()->back()->with('status', 'Asset Detail disposed successfully');
            } else {
                // Status is already 1, no update needed
                return redirect()->back()->with('status', 'Asset Detail is already disposed.');
            }
        } catch (\Exception $e) {
            dd($e);
            // Handle any exception that may occur during the update
            return redirect()->back()->with('failed', 'Failed to activate asset. Please try again.');
        }
    }

    public function detailActive($idHeader, $id)
    {
        $idHeader = decrypt($idHeader);
        $id = decrypt($id);

        try {
            // Find the AssetDetail model by ID
            $assetDetail = AssetDetail::findOrFail($id);

            // Find the related AssetHeader model by ID
            $assetHeader = AssetHeader::findOrFail($idHeader);

            // Check if the AssetHeader status is 0 (disposed)
            if ($assetHeader->status == 0) {
                return redirect()->back()->with('failed', 'Cannot activate Asset Detail. The corresponding Asset is disposed.');
            }

            // Check if the status is already set to 1
            if ($assetDetail->status != 1) {
                // Update the status attribute to 1
                $assetDetail->status = 1;

                // Save the AssetDetail
                $assetDetail->save();

                return redirect()->back()->with('status', 'Asset Detail activated successfully');
            } else {
                // Status is already 1, no update needed
                return redirect()->back()->with('status', 'Asset Detail is already active.');
            }
        } catch (\Exception $e) {
            dd($e);
            // Handle any exception that may occur during the update
            return redirect()->back()->with('failed', 'Failed to activate Asset Detail. Please try again.');
        }
}
    public function excelFormat()
    {
        return Excel::download(new ExcelExport, 'Format.xlsx');
    }
  
}
