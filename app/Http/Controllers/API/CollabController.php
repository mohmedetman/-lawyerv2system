<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Collab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CollabController extends Controller
{
    public function addCollab(Request $request){
        // Validate the incoming request
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg', // Adjust max file size as necessary
            'company_url' => 'required|url',
        ]);
        if($request->file('image')){
            // Get the file path after storage
            $path = $request->file('image')->store('public/images');
        
            // Determine the base URL based on the environment
            if (app()->isLocal()) {
                // For local development
                $baseUrl = url('/');
            } else {
                // For production or any other environment
                $baseUrl = config('app.url');
            }
        
            // Concatenate the base URL with the file path
            $url = $baseUrl . Storage::url($path);
            //$url = request()->url() . Storage::url($path); // This line is incorrect, use the $baseUrl variable instead
             $base_url_replace = str_replace('/storage', '/storage/app/public', $url);
        }else{
            $path = "";
            $base_url_replace = "";
        }
    
        
    
        // Save the collab details
        Collab::create([
            'image_path' => $path,
            'image_url' => $base_url_replace,
            'company_url' => $request->company_url
        ]);
    
        return response()->json([
            'message' => 'Collab added successfully',
            'image_url' => $base_url_replace // You may return the generated URL for the client to use
        ]);
    }
    

    public function deleteCollab($collab_id){
        $collab = Collab::find($collab_id);
        Storage::delete($collab->image_path);
        $collab->delete();
        return response()->json([
            'message' => 'Collab deleted successfully'
        ]);
    }

    public function getAllCollab(){
        $collab = Collab::all();
        return response()->json([
            'collabs' => $collab
        ]);
    }
}
