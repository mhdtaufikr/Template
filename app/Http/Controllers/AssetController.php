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
use App\Imports\AssetImport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Throwable;
use Illuminate\Support\Facades\File;
use App\Exports\ExcelExportDetail;
use App\Imports\AssetDetailImport;
use Illuminate\Support\Facades\View;
use App\Models\Rule;

use App\Exports\AssetExport;



class AssetController extends Controller
{
    public function index(){
        $assetData =[];
        $assetNo = AssetHeader::select('asset_no')->pluck('asset_no');
        $dropdownUom = Dropdown::where('category','UOM')->get();
        $assetCategory = AssetCategory::get();
        $dept = Department::get();
        $locHeader = LocHeader::get();
        $locDetail = LocDetail::get();
        $costCenter = CostCenter::get();
        $status = Dropdown::where('category','Status')->get();

        return view("asset.main", compact('assetNo',"assetData", "dropdownUom", "assetCategory", "dept", "locHeader", "locDetail", "costCenter",'status'));
    }

    public function delete($id){
        try {
            $assetHeader = AssetHeader::findOrFail($id);
            $assetDetail = AssetDetail::where('asset_header_id', $id)->get();

            // Delete the associated image file
            if ($assetHeader->img) {
                unlink(public_path($assetHeader->img));
            }

            // Delete the assetDetails
            $assetDetail->each->delete(); // Remove the parentheses here
            $assetHeader->delete();

            return redirect()->back()->with('status', 'Asset detail deleted successfully');
        } catch (\Exception $e) {
            dd($e);
            // Handle any exception that may occur during the delete
            return redirect()->back()->with('failed', 'Failed to delete asset detail. Please try again.');
        }
    }


    // AssetHeader model
    public function details()
    {
        return $this->hasMany(AssetDetail::class, 'asset_header_id');
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
            $assetType = substr($request->asset_no, 0, 2);
            $assetCategory = AssetCategory::where('class', $assetType)->first();
            // Create a new AssetHeader instance and fill it with the validated data
            $assetHeader = new AssetHeader([
                'asset_no' => $request->asset_no,
                'desc'  => $request->desc,
                'qty' => $request->qty,
                'uom' => $request->uom,
                'asset_type' => $assetCategory->desc,
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
            'plant' => 'required|string|max:255',
            'loc' => 'required|string|max:255',
            'dept' => 'required|string|max:255',
            'cost_center' => 'required|string|max:255',
            'img' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048', // Assuming it's an image file
            'bv_end' => 'required',
        ]);

         // Get the first two numbers of 'asset_no'
         $assetClass = substr($request->asset_no , 0, 2);

         // Query 'asset_categories' to get the 'class'
         $assetCategory = AssetCategory::where('class', $assetClass)->first();

        if ($assetCategory === null) {
            return redirect()->back()->with('failed', 'Asset type not found.');
        }

        $assetCategory = $assetCategory->desc;

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
                ($request->hasFile('img'))
            ) {
                // Update the attributes
                $assetHeader->asset_no = $request->asset_no;
                $assetHeader->desc = $request->desc;
                $assetHeader->qty = $request->qty;
                $assetHeader->uom = $request->uom;
                $assetHeader->acq_date = $request->date;
                $assetHeader->acq_cost = (int) str_replace(',', '', $request->cost_edit);
                $assetHeader->asset_type = $assetCategory;
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

        $status = Dropdown::where('category','Status')->get();
        $assetHeaderData = AssetHeader::where('id', $id)->first();
        $assetDetailData = AssetDetail::where('asset_header_id', $id)->get();
        $dropdownUom = Dropdown::where('category','UOM')->get();
        $assetCategory = AssetCategory::get();
        $locHeader = LocHeader::get();
        $locDetail = LocDetail::get();
        $dept = Department::get();
        return view('asset.detail', compact('assetHeaderData','assetDetailData','dropdownUom','assetCategory','locHeader','locDetail','dept','status'));
    }

    public function status(Request $request, $id) {
        $id = decrypt($id);

        try {
            // Find the AssetHeader model by ID
            $assetHeader = AssetHeader::findOrFail($id);

            // Get the status from the request
            $status = $request->input('status');

            // Append the current month and year to the existing remark
            $remark = $request->input('remark') . ' (' . now()->format('F Y') . ')';
            // Update the status attribute
            $assetHeader->status = $status;

            // Set the remark in the model
            $assetHeader->remarks = $remark;

            // Save the AssetHeader
            $assetHeader->save();

            // Update status in associated AssetDetails
            AssetDetail::where('asset_header_id', $id)->update(['status' => $status]);

            $statusText = ($status == 1) ? 'Active' : (($status == 0) ? 'Deactive' : 'Disposal');

            return redirect()->back()->with('status', "Asset $statusText updated successfully");
        } catch (\Exception $e) {
            // Handle any exception that may occur during the update
            return redirect()->back()->with('failed', 'Failed to update asset status. Please try again.');
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
                'asset_type' => 'required',
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
            dd($e);
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

    public function statusDetail(Request $request, $idHeader, $id){
        $idHeader = decrypt($idHeader);
        $id = decrypt($id);

        try {
            // Find the AssetDetail model by ID
            $assetDetail = AssetDetail::findOrFail($id);

            // Get the status and remark from the request
            $status = $request->input('status');
            $remark = $request->input('remark') . ' (' . now()->format('F Y') . ')';

            // Check if the status is different from the current status
            if ($assetDetail->status != $status) {
                // Update the status attribute
                $assetDetail->status = $status;

                // Set the remark in the model
                $assetDetail->remarks = $remark;

                // Save the AssetDetail
                $assetDetail->save();

                $statusText = ($status == 1) ? 'Active' : (($status == 0) ? 'Deactive' : 'Disposal');
                return redirect()->back()->with('status', "Asset Detail $statusText updated successfully");
            } else {
                // Status is the same, no update needed
                $statusText = ($status == 1) ? 'Active' : (($status == 0) ? 'Deactive' : 'Disposal');
                return redirect()->back()->with('status', "Asset Detail is already $statusText.");
            }
        } catch (\Exception $e) {
            // Handle any exception that may occur during the update
            return redirect()->back()->with('failed', 'Failed to update asset detail status. Please try again.');
        }
    }

    public function excelFormat()
    {
        // Add your desired note to be displayed in cell E2
        $note = "ex : 31/01/2017";

        // Download Excel file with the note in cell E2
        return Excel::download(new ExcelExport($note), 'Format.xlsx');
    }

    public function excelFormatDetail()
    {
        // Add your desired note to be displayed in cell E2
        $note = "ex : 31/01/2017";

        // Download Excel file with the note in cell E2
        return Excel::download(new ExcelExportDetail($note), 'FormatDetail.xlsx');
    }


    public function excelDataDetail(Request $request, $id){
        $request->validate([
            'excel-file' => 'required|file|mimes:xlsx',
        ]);

        try {


            // Import data using AssetDetailImport class
            Excel::import(new AssetDetailImport($id), $request->file('excel-file'));



            return redirect()->back()->with('status', 'Assets imported successfully');
        } catch (Throwable $e) {
            dd($e);

            // Log or handle the error as needed
            // You can also use $e->getMessage() to get the error message

            return redirect()->back()->with('failed', 'Error importing assets. Please check the data format.');
        }
    }

    public function excelData(Request $request)
    {
        $request->validate([
            'excel-file' => 'required|file|mimes:xlsx',
        ]);

        try {
            // Start a database transaction
            DB::beginTransaction();

            // Import data using AssetImport class
            Excel::import(new AssetImport, $request->file('excel-file'));

            // If everything is successful, commit the transaction
            DB::commit();

            return redirect()->back()->with('status', 'Assets imported successfully');
        } catch (Throwable $e) {
            dd($e,'tt');
            // If an error occurs, rollback the transaction
            DB::rollBack();

            // Log or handle the error as needed
            // You can also use $e->getMessage() to get the error message

            return redirect()->back()->with('failed', 'Error importing assets. Please check the data format.');
        }
    }

    public function generateQRCodesAndReturnPDF(Request $request)
    {
        $assetIds = explode(',', $request->input('assetIds'));

        // Fetch asset information from the database
        $assets = AssetHeader::whereIn('id', $assetIds)->get();
        $rules = Rule::where('rule_name','UrlQr')->first()->rule_value;
        $segment = $assets->first()->segment;
        // Initialize an array to store the data to be compacted
        $data = [
            'assetIds' => $assetIds,
            'assets' => $assets,
            'segment' => $segment,
            'rule'  => $rules,
        ];
        // Initialize the PDF
        $pdf = Pdf::loadView('asset.pdf', $data)->setPaper('a4', 'landscape');

        // Save the PDF (you may want to customize the storage path)
        $pdfPath = public_path("pdfs/qr_codes.pdf");
        $pdf->save($pdfPath);

        return response()->file($pdfPath);
    }

    public function generateQRCodesDetailAndReturnPDF($id, Request $request)
{
    $assetIds = explode(',', $request->input('assetIds'));

    // Fetch asset information from the database
    $assets = AssetDetail::whereIn('id', $assetIds)->where('asset_header_id', $id)->get();
    $assetsHeader = AssetHeader::where('id', $assets->first()->asset_header_id)->first();
    $rules = Rule::where('rule_name','UrlQrDetail')->first()->rule_value;
    $segment = $assetsHeader->segment;
    // Initialize an array to store the data to be compacted
    $data = [
        'assetIds' => $assetIds,
        'assets' => $assets,
        'segment' => $segment,
        'rule'  => $rules,
    ];
    // Use the existing PDF view for details
    $pdf = Pdf::loadView('asset.qr_codes_detail',$data)->setPaper('a4', 'landscape');;

    // Save the PDF (you may want to customize the storage path)
    $pdfPath = public_path("pdfs/qr_codes_detail.pdf");
    $pdf->save($pdfPath);

    return response()->file($pdfPath);
}

    public function assetPublic($id){

        $assetHeaderData = AssetHeader::where('id', $id)->first();
        $assetDetailData = AssetDetail::where('asset_header_id', $id)->get();
        $dropdownUom = Dropdown::where('category','UOM')->get();
        $assetCategory = AssetCategory::get();
        $locHeader = LocHeader::get();
        $locDetail = LocDetail::get();
        $dept = Department::get();
        return view('public.asset', compact('assetHeaderData','assetDetailData','dropdownUom','assetCategory','locHeader','locDetail','dept'));

    }

    public function assetPublicDtl($id){

        $assetDetailData = AssetDetail::where('id', $id)->first();
        return view('public.assetdtl', compact('assetDetailData'));

    }

    public function searchBy(Request $request)
{
    // Validation
    $request->validate([
        'searchBy' => 'required',
        // Add other validation rules for your form fields
    ]);
    $assetNo = AssetHeader::select('asset_no')->pluck('asset_no');
    // Retrieve data for dropdowns
    $status = Dropdown::where('category', 'Status')->get();
    $dropdownUom = Dropdown::where('category', 'UOM')->get();
    $assetCategory = AssetCategory::get();
    $dept = Department::get();
    $locHeader = LocHeader::get();
    $locDetail = LocDetail::get();
    $costCenter = CostCenter::get();

    // Retrieve selected search criteria
    $searchBy = $request->input('searchBy');
    $assetNumbers = $request->input('assetNo');
    // Additional variables for department and loc_detail_id
    $departmentId = $request->input('department');
    $locHeaderId = $request->input('destination');
    $locDetailId = $request->input('location');
    $assetCategorySearch = $request->input('assetCategory');

    // Initializing $assetData
    $assetData = null;

    // Additional variables to store the names
    $locHeaderName = $locDetailName = null;

    // Querying names based on IDs
    $locHeaderName = $locHeaderId ? LocHeader::find($locHeaderId)->name : null;
    $locDetailName = $locDetailId ? LocDetail::find($locDetailId)->name : null;

    // Switch statement to handle different search criteria
    switch ($searchBy) {
        case 'assetNo':
            $assetData = $this->searchByAssetNo($request);
            break;

        case 'destination':
            $assetData = $this->searchByDestination($request, $locHeaderName, $locDetailName);
            break;

        case 'department':
            $assetData = $this->searchByDepartment($departmentId);
            break;

        case 'dateRange':
            $assetData = $this->searchByDateRange($request);
            break;

        case 'assetCategory':
            $assetData = $this->searchByAssetCategory($assetCategorySearch);
            break;

        default:
            break;
    }

    // Returning the view with the retrieved data and names
    return view("asset.main", compact('assetNo',"status", "assetData", "dropdownUom", "assetCategory", "dept", "locHeader", "locDetail", "costCenter", "locHeaderName", "locDetailName"));
}

private function searchByAssetNo(Request $request)
{
    $assetNumbers = $request->input('assetNo');

    // Initialize an empty collection to store the results
    $assetHeaders = collect();

    foreach ($assetNumbers as $assetNumber) {
        // Try to find AssetHeader by asset_no
        $assetHeader = AssetHeader::where('asset_no', $assetNumber)->first();

        if ($assetHeader) {
            $assetHeaders->push($assetHeader);
        } else {
            // If AssetHeader not found, look for it in details
            $headerId = AssetDetail::where('asset_no', $assetNumber)
                ->pluck('asset_header_id')
                ->first();

            if ($headerId) {
                // If header_id found in details, query AssetHeader by header_id
                $assetHeader = AssetHeader::find($headerId);

                if ($assetHeader) {
                    $assetHeaders->push($assetHeader);
                }
            }
        }
    }
    return $assetHeaders;
}



private function searchByDestination(Request $request, $locHeaderName, $locDetailName)
{
    $query = AssetHeader::where('plant', $locHeaderName);

    if ($locDetailName) {
        $query->where('loc', $locDetailName);
    }

    return $query->get();
}

private function searchByDepartment($departmentId)
{
    return AssetHeader::where('dept', $departmentId)->get();
}

private function searchByDateRange(Request $request)
{
    $startDate = $request->input('startDate');
    $endDate = $request->input('endDate');

    return AssetHeader::whereBetween('acq_date', [$startDate, $endDate])->get();
}

private function searchByAssetCategory($assetCategorySearch)
{
    return AssetHeader::where('asset_type', $assetCategorySearch)->get();
}



public function exportToExcel(Request $request)
{
    // Retrieving selected search criteria
    $searchBy = $request->input('searchBy');
    $departmentId = $request->input('department');
    $locHeaderId = $request->input('destination');
    $locDetailId = $request->input('location');
    $assetCategorySearch = $request->input('assetCategory');

    // Additional variables to store the names
    $locHeaderName = null;
    $locDetailName = null;

    // Querying names based on IDs
    if ($locHeaderId) {
        $locHeaderName = LocHeader::find($locHeaderId)->name;
    }

    if ($locDetailId) {
        $locDetailName = LocDetail::find($locDetailId)->name;
    }

    // Initialize a collection to store both header and detail assets
    $exportData = collect();

    if ($searchBy === null) {
        // Retrieve all records
        $headerAssets = AssetHeader::all();
    } else {
        switch ($searchBy) {
            case 'assetNo':
                $headerAssets = AssetHeader::where('asset_no', $request->input('assetNo'))->get();
                break;

            case 'destination':
                $headerAssets = AssetHeader::where('plant', $locHeaderName);
                if ($locDetailId) {
                    $headerAssets->where('loc', $locDetailName);
                }
                $headerAssets = $headerAssets->get();
                break;

            case 'department':
                $headerAssets = AssetHeader::where('dept', $departmentId)->get();
                break;

            case 'dateRange':
                $startDate = $request->input('startDate');
                $endDate = $request->input('endDate');
                $headerAssets = AssetHeader::whereBetween('acq_date', [$startDate, $endDate])->get();
                break;

            case 'assetCategory':
                $headerAssets = AssetHeader::where('asset_type', $assetCategorySearch)->get();
                break;

            default:
                break;
        }
    }

   // Common array structure for both header and detail assets
$commonRow = [
    'Main Asset' => null,
    'Asset No' => null,
    'Sub Asset' => null,
    'Detail Desc' => null,
    'Detail Quantity' => null,
    'Detail Unit of Measure' => null,
    'Detail Asset Type' => null,
    'Detail Date' => null,
    'Detail Cost' => null,
    'Detail Purchase Order No' => null,
    'Detail Serial No' => null,
    'Detail Image' => null,
    'Detail Status' => null,
    'Detail Remarks' => null,
    'Detail Book Value at End of Year' => null,
    'Department' => null,
    'Plant' => null,
    'Location' => null,
    'Cost Center' => null,
    'Segment' => null,
    'Image' => null,
    'Status' => null,
    'Remarks' => null,
    'Book Value at the End of Year' => null,
    'created_at' => null,
    'updated_at' => null,
    // Add more common columns as needed
];

foreach ($headerAssets as $headerAsset) {
    // Fill in the values for header asset in the common row
    $commonRow['Main Asset'] = $headerAsset->asset_no;
    $commonRow['Asset No'] = $headerAsset->asset_no;
    $commonRow['Sub Asset'] = null; // No sub-asset for header
    $commonRow['Detail Desc'] = $headerAsset->desc;
    $commonRow['Detail Quantity'] = $headerAsset->qty;
    $commonRow['Detail Unit of Measure'] = $headerAsset->uom;
    $commonRow['Detail Asset Type'] = $headerAsset->asset_type;
    $commonRow['Detail Date'] = $headerAsset->acq_date;
    $commonRow['Detail Cost'] = $headerAsset->acq_cost;
    $commonRow['Detail Purchase Order No'] = $headerAsset->po_no;
    $commonRow['Detail Serial No'] = $headerAsset->serial_no;
    $commonRow['Detail Image'] = $headerAsset->img;
    $commonRow['Detail Status'] = $headerAsset->status;
    $commonRow['Detail Remarks'] = $headerAsset->remarks;
    $commonRow['Detail Book Value at End of Year'] = $headerAsset->bv_endofyear;
    $commonRow['Department'] = $headerAsset->dept;
    $commonRow['Plant'] = $headerAsset->plant;
    $commonRow['Location'] = $headerAsset->loc;
    $commonRow['Cost Center'] = $headerAsset->cost_center;
    $commonRow['Segment'] = $headerAsset->segment;
    $commonRow['Image'] = $headerAsset->img;
    $commonRow['Status'] = $headerAsset->status;
    $commonRow['Remarks'] = $headerAsset->remarks;
    $commonRow['Book Value at the End of Year'] = $headerAsset->bv_endofyear;
    $commonRow['created_at'] = $headerAsset->created_at;
    $commonRow['updated_at'] = $headerAsset->updated_at;

    // Add the header row to the collection
    $exportData->push($commonRow);

    // Find detail assets for the current header asset
    $detailAssets = AssetDetail::where('asset_header_id', $headerAsset->id)->get();

    // Check if there are detail assets
    if ($detailAssets->isNotEmpty()) {
        foreach ($detailAssets as $detailAsset) {
            // Fill in the values for detail asset in the common row
            $commonRow['Main Asset'] = $headerAsset->asset_no; // No repeated header ID for details
            $commonRow['Asset No'] = $detailAsset->asset_no;
            $commonRow['Sub Asset'] = $detailAsset->sub_asset;
            $commonRow['Detail Desc'] = $detailAsset->desc;
            $commonRow['Detail Quantity'] = $detailAsset->qty;
            $commonRow['Detail Unit of Measure'] = $detailAsset->uom;
            $commonRow['Detail Asset Type'] = $detailAsset->asset_type;
            $commonRow['Detail Date'] = $detailAsset->acq_date;
            $commonRow['Detail Cost'] = $detailAsset->acq_cost;
            $commonRow['Detail Purchase Order No'] = $detailAsset->po_no;
            $commonRow['Detail Serial No'] = $detailAsset->serial_no;
            $commonRow['Detail Image'] = $detailAsset->img;
            $commonRow['Detail Status'] = $detailAsset->status;
            $commonRow['Detail Remarks'] = $detailAsset->remarks;
            $commonRow['Detail Book Value at End of Year'] = $detailAsset->bv_endofyear;
            $commonRow['Department'] = $headerAsset->dept; // No department for details
            $commonRow['Plant'] = $headerAsset->plant; // No plant for details
            $commonRow['Location'] = $headerAsset->loc; // No location for details
            $commonRow['Cost Center'] = $headerAsset->cost_center; // No cost center for details
            $commonRow['Segment'] = $headerAsset->segment; // No segment for details
            $commonRow['Image'] = $detailAsset->img; // No image for details
            $commonRow['Status'] = $detailAsset->status; // No status for details
            $commonRow['Remarks'] = $detailAsset->remarks; // No remarks for details
            $commonRow['Book Value at the End of Year'] = $detailAsset->bv_endofyear; // No book value for details
            $commonRow['created_at'] = $detailAsset->created_at;
            $commonRow['updated_at'] = $detailAsset->updated_at;

            // Add the detail row to the collection
            $exportData->push($commonRow);
        }
    }
}

// Export to Excel (You need to implement the logic for exporting to Excel)
// Example using Laravel Excel:
return Excel::download(new AssetExport($exportData), 'assets.xlsx');


}




}
