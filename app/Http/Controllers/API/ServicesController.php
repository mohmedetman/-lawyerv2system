<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\LawyerService;
use App\Models\Service;
use App\Models\SocialContact;
use App\Models\SubServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ServicesController extends Controller
{


    public function addServices(Request $request){

        $request->validate([
            "title_en"=>"required_without:title_ar|string",
            "title_ar"=>"required_without:title_en|string",
            'description_ar'=>'required_without:description_en|string',
            'description_en'=>'required_without:description_ar|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $imageName = '' ;
         if (isset($request->image)) {
             $imageName = time() . '.' . $request->image->extension();
             $request->image->move(public_path('images/services'), $imageName);
         }
        $user = Auth::user();
        Service::create([
            'lawyer_id' => $user->id,
            'title_ar' => $request->title_ar ,
            'title_en' => $request->title_en,
            'description_ar' => $request->description_ar,
            'description_en' => $request->description_en,
            'image'=>$imageName,
        ]);
        return response()->json([
            'message' => 'service added successfully',

        ]);
    }

   public function updateSpecificService(Request $request,$service_id){
       $request->validate([
           "title_en" => "nullable|string",
           "title_ar" => "nullable|string",
           'description_ar' => 'nullable|string',
           'description_en' => 'nullable|string',
           'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
       ]);
       $service = Service::findOrFail($service_id);
       $imageName = $service->image;
       if ($request->hasFile('image')) {
           $oldImagePath = public_path('images/services') . '/' . $service->image;
           if (file_exists($oldImagePath)) {
               unlink($oldImagePath);
           }
           $imageName = time() . '.' . $request->image->extension();
           $request->image->move(public_path('images/services'), $imageName);
       }

       $service->update([
           'lawyer_id' => Auth::user()->id,
           'title_ar' => $request->title_ar ?? $service->title_ar,
           'title_en' => $request->title_en ?? $service->title_en,
           'description_ar' => $request->description_ar ?? $service->description_ar,
           'description_en' => $request->description_en ?? $service->description_en,
           'image' => $imageName,
       ]);
       return response()->json([
           'message' => 'Service updated successfully',
       ]);
}

    public function deleteSpecificService($service_id , Request $request){
        $service = Service::findOrFail($service_id);
        $imageName = $service->image;
            $oldImagePath = public_path('images/services') . '/' . $service->image;
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
        }
            $service->delete();
        return response()->json([
            'message' => 'Service Deleted successfully',
        ]);

    }

    public function getAllServices(){
        return response()->json([
            'Services' => ServiceResource::collection( Service::all())
        ]);
    }
    public function addSubService(Request $request , $service_id){

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg', // Adjust max file size as necessary
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
            //$base_url_replace = str_replace('/store/storage', '/storage', $url);
        }else{
           $path = "";
           $base_url_replace = "";
        }

        SubServices::create([
            'title_en' => $request->title_en,
            'image_path' => $path,
            'image_url' => $base_url_replace,
            'description_en' => $request->description_en,
            'title_ar' => $request->title_ar ,
            'description_ar' => $request->description_ar,
            'service_id' => $service_id
        ]);
        return response()->json([
            'message' => 'subService added successfully',
            'image_url' => $base_url_replace
        ]);
    }

    public function updateSubService($sub_service_id , Request $request){
        $sub_service = SubServices::find($sub_service_id);

        if($request->file('image')){
            $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg', // Adjust max file size as necessary
        ]);
            Storage::delete($sub_service->image_path);
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
            //$base_url_replace = str_replace('/store/storage', '/storage', $url);
        }else{
            $path = $sub_service->image_path;
           $base_url_replace = $sub_service->image_url;
        }

        $sub_service->update([
            'title_en' => $request->title_en,
            'image_path' => $path,
            'image_url' => $base_url_replace,
            'description_en' => $request->description_en,
            'title_ar' => $request->title_ar ,
            'description_ar' => $request->description_ar,
            'service_id' => $sub_service->service_id
        ]);
        return response()->json([
            'message' => 'subService updated successfully',
            'image_url' => $base_url_replace
        ]);
    }
    public function deleteSubService($sub_service_id , Request $request){
        $sub_service = SubServices::find($sub_service_id);
        Storage::delete($sub_service->image_path);
        $sub_service->delete();
        return response()->json([
            'message' => 'subService deleted successfully'
        ]);
    }
    public function getAllSubServices(){
        return response()->json([
            'SubServices' => SubServices::all()
        ]);
    }

    public function getAllSubServicesRelatedToService($service_id){
        $subservices = SubServices::where('service_id' , $service_id)->get();
        return response()->json([
            'subservices' => $subservices
        ]);
    }

    public function getSpecificService($service_id){
        return response()->json([
            'Service' => LawyerService::find($service_id)
        ]);
    }




    public function addSocialContacts(Request $request){

        $socialContact = SocialContact::count();
        if($socialContact < 1){
            SocialContact::create([
            'facebook_url' => $request->facebook_url,
            'instgram_url' => $request->instgram_url,
            'twitter_url' => $request->twitter_url,
            'whatsapp_url' => $request->whatsapp_url,
            'linkedin_url'=> $request->linkedin_url
            ]);
             return response()->json([
                'message' => 'SocialContacts added successfully'
            ]);
        }else{
           $social_Contact = SocialContact::orderBy('id', 'desc')->first();
           $social_Contact->update([
                'facebook_url' => $request->facebook_url,
                'instgram_url' => $request->instgram_url,
                'twitter_url' => $request->twitter_url,
                'whatsapp_url' => $request->whatsapp_url,
                'linkedin_url'=> $request->linkedin_url
           ]);
           return response()->json([
                'message' => 'SocialContacts updated successfully'
            ]);
        }


    }

    public function getAllSocialContacts(){
        return response()->json([
            'SocialContacts' => SocialContact::all()
        ]);
    }


























 }

