<?php

namespace App\Http\Controllers;
use App\Models\LocHeader;
use App\Models\LocDetail;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class LocationController extends Controller
{
  public function index(){
    $locData = LocHeader::get();
    return view("location.index",compact("locData"));
  }

    public function store(Request $request)
    {
        $request->validate([
            'location' => 'required|string|max:255',
        ]);

        // Check if a Location with the same name already exists
        $existingLocation = LocHeader::where('name', $request->location)->first();

        if ($existingLocation) {
            // Location with the same name already exists, handle accordingly
            return redirect()->back()->with('failed', 'Location with this name already exists.');
        }

        // Location with the same name does not exist, create a new record
        try {
            LocHeader::create([
                'name' => $request->location,
            ]);

            return redirect()->back()->with('status', 'Location created successfully');
        } catch (\Exception $e) {
            // Handle any exception that may occur during the creation
            return redirect()->back()->with('failed', 'Failed to create location. Please try again.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'location' => 'required|string|max:255',
        ]);
    
        try {
            $locHeader = LocHeader::findOrFail($id);
    
            // Get the original values
            $originalValues = $locHeader->getOriginal();
    
            // Update the name attribute
            $locHeader->name = $request->location;
    
            // Compare the original values with the new values
            if ($originalValues != $locHeader->getAttributes()) {
                // Save the LocHeader
                $locHeader->save();
    
                return redirect()->back()->with('status', 'Location header updated successfully');
            } else {
                // No changes, so no update is needed
                return redirect()->back()->with('failed', 'No changes made to the location header.');
            }
        } catch (\Exception $e) {
            // Handle any exception that may occur during the update
            return redirect()->back()->with('failed', 'Failed to update location header. Please try again.');
        }
    }
    

    public function delete($id)
    {
        try {
            // Use database transaction
            DB::beginTransaction();
    
            // Find the LocHeader model by ID
            $locHeader = LocHeader::findOrFail($id);
        
            // Delete the related LocDetail records based on loc_header_id
            LocDetail::where('loc_header_id', $id)->delete();
            
            // Delete the LocHeader record
            $locHeader->delete();
    
            // Commit the transaction
            DB::commit();
    
            return redirect()->back()->with('status', 'Location header and related details deleted successfully');
        } catch (\Exception $e) {

            dd($e);
            // Rollback the transaction in case of exception
            DB::rollback();
    
            return redirect()->back()->with('failed', 'Failed to delete location header and related details. Please try again.');
        }
    }
    

    

    public function detail($id){
        $id = decrypt($id);
        $location = LocHeader::where('id',$id)->first();
        $locDetailData = LocDetail::where('loc_header_id',$id)->get();
        return view('location.detail',compact('locDetailData','location'));
    }
    public function storeDetail(Request $request, $id)
    {
        $request->validate([
            'location' => 'required|string|max:255|unique:loc_headers,name',
        ]);
    
        try {
            // Validate that the location doesn't exist in LocDetail for the given loc_header_id
            $locHeader = LocHeader::findOrFail($id);
    
            $existingLocDetail = LocDetail::where('loc_header_id', $locHeader->id)
                ->where('name', $request->location)
                ->first();
    
            if ($existingLocDetail) {
                return redirect()->back()->with('failed', 'Location detail with the same name already exists.');
            }
    
            // Create a new LocDetail with the given loc_header_id
            $locDetail = LocDetail::create([
                'loc_header_id' => $locHeader->id,
                'name' => $request->location,
            ]);
    
            return redirect()->back()->with('status', 'Location detail created success  fully');
        } catch (\Exception $e) {
            // Handle any exception that may occur during the creation
            return redirect()->back()->with('failed', 'Failed to create location detail. Please try again.');
        }
    }
    
    public function updateDetail(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            // Find the LocDetail model by ID
            $locDetail = LocDetail::findOrFail($id);

            // Get the original attributes
            $originalAttributes = $locDetail->getOriginal();

            // Update the name attribute
            $locDetail->name = $request->name;

            // Check if any changes have been made to the model attributes
            if ($locDetail->getAttributes() != $originalAttributes) {
                // Save the LocDetail
                $locDetail->save();

                return redirect()->back()->with('status', 'Location detail updated successfully');
            } else {
                // No changes, so no update is needed
                return redirect()->back()->with('failed', 'No changes made to the location detail.');
            }
        } catch (\Exception $e) {
            // Handle any exception that may occur during the update
            return redirect()->back()->with('failed', 'Failed to update location detail. Please try again.');
        }
    }

    

    public function deleteDetail($id){
        try {
            $detailLocation = LocDetail::findOrFail($id);
            $detailLocation->delete();
    
            return redirect()->back()->with('status', 'Location Detail deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('failed', 'Failed to delete Location Detail. Please try again.');
        }
    }
    


}
