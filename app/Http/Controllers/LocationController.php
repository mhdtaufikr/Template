<?php

namespace App\Http\Controllers;
use App\Models\LocHeader;
use App\Models\LocDetail;

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


    public function update(Request $request, $id){
        dd($request);
    }

    public function delete($id){

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
    
            return redirect()->back()->with('status', 'Location detail created successfully');
        } catch (\Exception $e) {
            // Handle any exception that may occur during the creation
            return redirect()->back()->with('failed', 'Failed to create location detail. Please try again.');
        }
    }
    
    

    


}
