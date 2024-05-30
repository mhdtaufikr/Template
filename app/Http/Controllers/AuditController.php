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
            'signature' => 'required|string',
            'condition' => 'required|array',
            'Remarks' => 'required|array',
        ]);

        // Start a database transaction
        DB::beginTransaction();

        try {
            $audit_no = 'AUD' . time();
            // Insert data into the audits table
            $audit = Audit::create([
                'audit_no' => $audit_no,  // or any other logic to generate audit_no
                'audit_date' => now(),
                'created_by' => Auth::id(),
                'signature' => $request->input('signature'),
                'status' => '1',  // or any other default status
            ]);

            // Iterate over the assets in the request
            foreach ($request->input('condition') as $asset_id => $conditions) {
                $condition = $conditions[0]; // Assuming the condition is stored as an array
                $remark = $request->input('Remarks')[$asset_id][0]; // Get corresponding remark

                // Insert data into the audit_details table
                AuditDetail::create([
                    'audit_id' => $audit->id,
                    'asset_id' => $asset_id,
                    'condition' => $condition,
                    'remark' => $remark,
                ]);
            }

            // Commit the transaction
            DB::commit();

            // Redirect or return response
            return redirect()->route('audits.index')->with('success', 'Audit created successfully.');
        } catch (\Exception $e) {
            dd($e);
            // Rollback the transaction on error
            DB::rollback();

            // Log the error for further debugging


            // Redirect or return response with error
            return redirect()->route('audits.index')->with('error', 'Audit creation failed.');
        }
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
