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

        public function auditStore(Request $request) {
            // Validate the incoming request
            $request->validate([
                'audit_signature' => 'nullable|string',
                'controlling_signature' => 'nullable|string',
                'condition' => 'required|array',
                'remarks' => 'required|array',
                'user_signature' => 'required|array',
                'img' => 'required', // Remove array validation, handle it in the code
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
                foreach ($request->input('condition') as $asset_id => $conditions) {
                    $condition = $conditions[0]; // Assuming the condition is stored as an array
                    $remark = $request->input('remarks')[$asset_id][0]; // Get corresponding remark
                    $user_signature = $this->saveBase64Image($request->input('user_signature')[$asset_id], 'signature'); // Save user signature
                    $img_files = $request->file('img')[$asset_id]; // Can be a single file or an array

                    // Ensure img_files is an array
                    if (!is_array($img_files)) {
                        $img_files = [$img_files];
                    }

                    // Save the images
                    $img_paths = [];
                    foreach ($img_files as $img_file) {
                        if ($img_file) { // Check if the img_file is not empty
                            $img_name = uniqid() . '.' . $img_file->getClientOriginalExtension();
                            $destination_path = public_path('audit/img/' . $img_name);
                            $img_file->move(public_path('audit/img'), $img_name);
                            $img_paths[] = 'audit/img/' . $img_name; // Store relative path
                        }
                    }

                    // Insert data into the audit_details table
                    AuditDetail::create([
                        'audit_id' => $audit->id,
                        'asset_id' => $asset_id,
                        'condition' => $condition,
                        'remark' => $remark,
                        'signature' => $user_signature,
                        'img' => json_encode($img_paths), // Save the image paths as JSON
                    ]);
                }

                // Commit the transaction
                DB::commit();

                // Redirect or return response
                return redirect()->route('audits.index')->with('success', 'Audit created successfully.');
            } catch (\Exception $e) {
                // Rollback the transaction on error
                DB::rollback();

                // Log the error for further debugging
                \Log::error('Audit creation failed: ' . $e->getMessage());

                // Redirect or return response with error
                return redirect()->route('audits.index')->with('error', 'Audit creation failed.');
            }
        }

        private function calculateStatus($request) {
            if (!empty($request->input('audit_signature')) || !empty($request->input('controlling_signature'))) {
                return 1; // At least one signature is provided, set status to 1
            } else {
                return 0; // No signature is provided, set status to 0
            }
        }

        private function saveBase64Image($base64_image, $type) {
            if (strpos($base64_image, 'data:image') === 0) {
                $image_data = explode(',', $base64_image);
                if (isset($image_data[1])) {
                    $image_content = base64_decode($image_data[1]);
                    $image_name = uniqid() . '.png'; // You can use a different naming convention if needed
                    if ($type == 'signature') {
                        $image_path = public_path('audit/signature/' . $image_name);
                        file_put_contents($image_path, $image_content);
                        return 'audit/signature/' . $image_name; // Return the relative path to be stored in the database
                    }
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


}
