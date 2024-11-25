<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\AuditDetail;
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
use App\Exports\ExcelExportSearch;
use App\Imports\AssetDetailImport;
use Illuminate\Support\Facades\View;
use App\Models\Rule;
use Illuminate\Support\Facades\Auth;


class AuditController extends Controller
{
   public function index(){
    $assetNo = AssetHeader::pluck('asset_no');
    $item = Audit::get();
    return view('audit.index', compact('assetNo','item'));
   }

   public function scanAudit(Request $request){
    $data = [];

    foreach ($request->asset as $value) {
        $asset = AssetHeader::where('asset_no', $value)->first();
        if ($asset) {
            $data[] = [
                'assetHeaderData' => $asset,
                'assetDetailData' => AssetDetail::where('asset_header_id', $asset->id)->get(),

            ];
        }
    }
                $dropdownUom = Dropdown::where('category', 'UOM')->get();
                $assetCategory = AssetCategory::all();
                $locHeader = LocHeader::orderBy('name')->get();
                $locDetail = LocDetail::orderBy('name')->get();
                $dept = Department::all();
                $status = Dropdown::where('category', 'Status')->get();

    return view('audit.form', compact('data','dropdownUom','assetCategory','locHeader','locDetail','dept','status'));
}

public function auditStore(Request $request)
{
    // Validate the incoming request
    $request->validate([
        'audit_signature' => 'nullable|string',
        'controlling_signature' => 'nullable|string',
        'condition' => 'required|array',
        'remarks' => 'required|array',
        'user_signature' => 'required|array',
    ]);

    // Start a database transaction
    DB::beginTransaction();

    try {
        $audit_no = 'AUD' . time();
        // Insert data into the audits table
        $audit = Audit::create([
            'audit_no' => $audit_no,
            'audit_date' => now(),
            'created_by' => Auth::id(),
            'signature_ctl' => $this->saveBase64Image($request->input('controlling_signature'), 'signature'),
            'signature_aud' => $this->saveBase64Image($request->input('audit_signature'), 'signature'),
            'status' => $this->calculateStatus($request),
        ]);

        // Iterate over the assets in the request
        foreach ($request->input('condition') as $asset_id => $condition) {
            $remark = $request->input('remarks')[$asset_id]; // Get corresponding remark
            $user_signature = $this->saveBase64Image($request->input('user_signature')[$asset_id], 'signature'); // Save user signature

            // Handle the image upload using img_hidden
            $img_paths = [];
            if (isset($request->img_hidden[$asset_id]) && is_array($request->img_hidden[$asset_id])) {
                foreach ($request->img_hidden[$asset_id] as $file) {
                    if ($file instanceof \Illuminate\Http\UploadedFile) {
                        $file_name = uniqid() . '_' . $file->getClientOriginalName();
                        $destination_path = public_path('images/audit');

                        // Ensure the directory exists
                        if (!file_exists($destination_path)) {
                            mkdir($destination_path, 0755, true);
                        }

                        // Move the file
                        $file->move($destination_path, $file_name);

                        // Add the relative path to the image paths array
                        $img_paths[] = 'images/audit/' . $file_name;

                        // Log successful file upload
                        \Log::info("File uploaded: {$file_name} to {$destination_path}");
                    }
                }
            }

            // Insert a single record in the audit_details table with JSON-encoded image paths
            AuditDetail::create([
                'audit_id' => $audit->id,
                'asset_id' => $asset_id,
                'condition' => $condition,
                'availability' => $request->availability[$asset_id],
                'remark' => $remark,
                'signature' => $user_signature,
                'img' => !empty($img_paths) ? json_encode($img_paths) : null, // Store JSON array of image paths or null
            ]);
        }

        // Commit the transaction
        DB::commit();

        // Redirect or return response
        return redirect()->route('audits.index')->with('status', 'Audit created successfully.');
    } catch (\Exception $e) {
        // Rollback the transaction on error
        DB::rollback();

        // Log the error for debugging
        \Log::error('Audit creation failed: ' . $e->getMessage());

        // Redirect or return response with error
        return redirect()->route('audits.index')->with('failed', 'Audit creation failed.');
    }
}



private function calculateStatus($request)
{
    if (!empty($request->input('audit_signature')) || !empty($request->input('controlling_signature'))) {
        return 1; // At least one signature is provided, set status to 1
    } else {
        return 0; // No signature is provided, set status to 0
    }
}

private function saveBase64Image($base64_image, $type)
{
    if (strpos($base64_image, 'data:image') === 0) {
        $image_data = explode(',', $base64_image);
        if (isset($image_data[1])) {
            $image_content = base64_decode($image_data[1]);
            $image_name = uniqid() . '.png'; // You can use a different naming convention if needed
            $sub_directory = $type == 'signature' ? 'images/audit/signature' : 'images/audit/img';
            $image_path = public_path($sub_directory . '/' . $image_name);
            file_put_contents($image_path, $image_content);
            return  $sub_directory . '/' . $image_name; // Return the relative path to be stored in the database
        }
    }
    return null; // Return null if the image data is invalid
}


    public function auditDetail($id) {
        $id = decrypt($id);
        $data = [];

        // Assuming $id is the audit ID
        // Retrieve the audit data
        $audit = Audit::findOrFail($id);

        // Retrieve the asset details associated with the audit
        $assetDetails = AuditDetail::where('audit_id', $id)->get();


        // Iterate over each asset detail
        foreach ($assetDetails as $assetDetail) {
            $asset = AssetHeader::where('asset_no', $assetDetail->asset_id)->first();

            if ($asset) {
                $data[] = [
                    'audit' => $audit,
                    'assetHeaderData' => $asset,
                    'assetDetailData' => $assetDetail,
                ];
            }
        }

        // Pass the data to your view
        return view('audit.detail', compact('data'));
    }

    public function auditPdf($id)
    {
        $id = decrypt($id);

        // Retrieve the audit data using the decrypted ID
        $audit = Audit::with('user')->findOrFail($id); // Eager loading the user relationship
        $auditDetails = AuditDetail::where('audit_id', $id)->get();

        // Load the view and pass the audit data to it
        $pdf = PDF::loadView('audit.pdf', compact('audit', 'auditDetails'))->setPaper('a4', 'landscape');

        // Return the generated PDF
        return $pdf->download('audit_' . $id . '.pdf');
    }





}
