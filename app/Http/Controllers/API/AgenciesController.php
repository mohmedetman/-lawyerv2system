<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AgenciesIndex;
use App\Models\Employee;
use App\Models\Lawyer;
use App\Models\TemporaryAgencyIndex;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Sanctum\PersonalAccessToken;

class AgenciesController extends Controller
{
    public function addAgenciesIndex(Request $request){
        $token = request()->bearerToken();
        $personal_token = PersonalAccessToken::find($token)->tokenable_type;
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'agencies_num_en' => 'required_without:agencies_num_ar|string|max:255',
            'office_doc_en' => 'required_without:office_doc_ar|string|max:255',
            'agencies_type_en' => 'required_without:agencies_type_ar|string|max:255',
            'agencies_num_ar' => 'required_without:agencies_num_en|string|max:255',
            'office_doc_ar' => 'required_without:office_doc_en|string|max:255',
            'agencies_type_ar' => 'required_without:agencies_type_en|string|max:255',
            'date' => 'required|date',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate the image
        ]);
        if ($personal_token == "App\Models\Lawyer") {
            $rules['permission'] = ['required', 'string', 'max:255', Rule::in(['me', 'another'])];
        }
        $validator = Validator::make($request->all(), $rules, [
            'permission.in' => 'The permission field must be one of the following options: me, another.',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }
        $user = Auth::user();
        $imageName = '' ;
        if (isset($request->image)) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/agencies'), $imageName);
        }
        $user = Auth::user();
        $personal_token = get_class($user);
        $employee_id = null;
        if ($personal_token == Lawyer::class) {
            if ($request->permission == "another" && !isset($request->employee_id)) {
                return response()->json("employee should be chosen", 400);
            }
            if ($request->permission != "another") {
                $employee_id = null;
            } else {
                $employee_id = $request->employee_id;
            }
        }
        if ($personal_token == Employee::class) {
            $employee_id = $user->id;
        }
        AgenciesIndex::create([
            'user_id' =>$request->user_id,
            'agencies_num_en' => $request->agencies_num_en,
            'office_doc_en' => $request->office_doc_en,
            'agencies_type_en' => $request->agencies_type_en,
            'agencies_num_ar' => $request->agencies_num_ar,
            'office_doc_ar' => $request->office_doc_ar,
            'agencies_type_ar' => $request->agencies_type_ar,
            'date' => Carbon::createFromFormat('m/d/Y', $request->input('date'))->format('Y-m-d'),
            'employee_id' => ($personal_token == "App\Models\Employee") ? Auth::user()->id : $employee_id ,
            'agencies_imageUrl' => $imageName,
            'lawyer_id' => $personal_token == "App\Models\Lawyer" ? Auth::user()->id : Employee::where('id',PersonalAccessToken::find($token)->tokenable_id)->first()->lawyer_id,
            'permission' => $personal_token == "App\Models\Lawyer" ?$request->permission : null,
            'status' => $personal_token == "App\Models\Lawyer" ? 'confirmed': 'pending',
        ]);

    }

    public function editAgenciesIndex(Request $request,$ageny_id){
        $user = Auth::user();
        $client = User::find($request->user_id);
        $ageny = AgenciesIndex::find($ageny_id);

        if($request->file('image')){
            Storage::delete($ageny->agencies_imagePath);
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
           $path = $ageny->agencies_imagePath;
           $base_url_replace = $ageny->agencies_imageUrl;
       }
        if($user){
            if($user->user_type == 'محامي'){
                if($ageny->status != 'rejected'){
                    $ageny->update([
                        'user_code' => $client->code,
                        'user_id' => $client->id,
                        'user_name' => $client->name,
                        'agencies_num_en' => $request->agencies_num_en,
                        'office_doc_en' => $request->office_doc_en,
                        'agencies_type_en' => $request->agencies_type_en,
                        'agencies_num_ar' => $request->agencies_num_ar,
                        'office_doc_ar' => $request->office_doc_ar,
                        'agencies_type_ar' => $request->agencies_type_ar,
                        'date' => $request->date,
                        'agencies_imagePath' => $path,
                        'agencies_imageUrl' => $base_url_replace,
                        'permission' => $request->permission,

                    ]);
                    return response()->json([
                        'message' => 'AgenciesIndex updated successfully'
                    ]);
                }

            }else{
                if($ageny->status == "confirmed" && $ageny->permission != " "){
                    TemporaryAgencyIndex::create([
                        'user_code' => $client->code,
                        'user_id' => $client->id,
                        'user_name' => $client->name,
                        'agency_id' => $ageny->id,
                        'agencies_num_en' => $request->agencies_num_en,
                        'office_doc_en' => $request->office_doc_en,
                        'agencies_type_en' => $request->agencies_type_en,
                        'agencies_num_ar' => $request->agencies_num_ar,
                        'office_doc_ar' => $request->office_doc_ar,
                        'agencies_type_ar' => $request->agencies_type_ar,
                        'date' => $request->date,
                        'agencies_imagePath' => $path,
                        'agencies_imageUrl' => $base_url_replace,
                        'permission' => $ageny->permission,
                    ]);
                    return response()->json([
                        'message' => 'AgenciesIndex updated successfully'
                    ]);
                }

            }
        }

    }

    public function deleteAgenciesIndex($ageny_id){
        $user = Auth::user();
        $ageny = AgenciesIndex::find($ageny_id);
        Storage::delete($ageny->agencies_imagePath);
        if($user){
            if($user->user_type == 'محامي'){
                $ageny->delete();
                return response()->json([
                    'message' => 'AgenciesIndex deleted successfully'
                ]);
            }
        }

    }

    public function getAllAgenciesIndex(){
        $user = Auth::user();
        if($user){
            $agencies = AgenciesIndex::where('status','!=','rejected')->get();
            $merged_agencies = $agencies->map(function($agency){
                return[
                    'id'=>$agency->id,
                    'user_code' => $agency->user_code,
                    'user_name' => $agency->user_name,
                    'agencies_num_en' => $agency->agencies_num_en,
                    'office_doc_en' => $agency->office_doc_en,
                    'agencies_type_en' => $agency->agencies_type_en,
                    'agencies_num_ar' => $agency->agencies_num_ar,
                    'office_doc_ar' => $agency->office_doc_ar,
                    'agencies_type_ar' => $agency->agencies_type_ar,
                    'date' => $agency->date,
                    'status' => $agency->status,
                    'permission' => $agency->permission,
                    'employee_name' => User::where('code',$agency->permission)->value('name'),
                    'agencies_imagePath' => $agency->agencies_imagePath,
                    'agencies_imageUrl' => $agency->agencies_imageUrl
                ];
            });

            return response()->json([
                'AgenciesIndex' => $merged_agencies
            ]);
        }

    }

    public function getAllPendingAgenciesIndexLawyerSide(){
        $user = Auth::user();
        if($user){
            if($user->user_type == 'محامي'){
                return response()->json([
                    'cases' => TemporaryAgencyIndex::where('status','pending')->get()
                ]);
            }
        }
    }

    public function getAllPendingAgenciesIndexemployeeSide(){
        $user = Auth::user();
        if($user){
            if($user->user_type == 'موظف'){
                return response()->json([
                    'cases' => AgenciesIndex::where('status','pending')->orWhere('status','confirmed')->Where('permission',$user->code)->get()
                ]);
            }
        }
    }

    public function confirmAgenciesIndex($ageny_id){
        $user = Auth::user();
        $tempageny = TemporaryAgencyIndex::find($ageny_id);
        $agency = AgenciesIndex::find($tempageny->agency_id);

        if($user){
            if($user->user_type == 'محامي'){
                $agency->update([
                    'user_code' => $tempageny->user_code,
                        'user_id' => $tempageny->user_id,
                        'user_name' => $tempageny->user_name,
                        'agencies_num_en' => $tempageny->agencies_num_en,
                        'office_doc_en' => $tempageny->office_doc_en,
                        'agencies_type_en' => $tempageny->agencies_type_en,
                        'agencies_num_ar' => $tempageny->agencies_num_ar,
                        'office_doc_ar' => $tempageny->office_doc_ar,
                        'agencies_type_ar' => $tempageny->agencies_type_ar,
                        'date' => $tempageny->date,
                        'agencies_imagePath' => $tempageny->agencies_imagePath,
                        'agencies_imageUrl' => $tempageny->agencies_imageUrl,
                        'status' => 'confirmed'
                   ]);
                   $tempageny->update([
                    'status' => 'confirmed'
                   ]);
                   return response()->json([
                    'message' => 'AgenciesIndex confirmed successfully'
                   ]);
            }
        }

    }

    public function rejectAgenciesIndex($ageny_id){
        $user = Auth::user();
        $tempageny = TemporaryAgencyIndex::find($ageny_id);
        $agency = AgenciesIndex::where('status','pending')->orWhere('status','confirmed')->where('permission',$tempageny->permission)->where('agencies_num_en',$tempageny->agencies_num_en)->orWhere('agencies_num_ar',$tempageny->agencies_num_ar)->first();

        if($user){
            if($user->user_type == 'محامي'){
                $tempageny->update([
                    'status' => 'rejected'
                 ]);
                 return response()->json([
                  'message' => 'AgenciesIndex rejected successfully'
                 ]);
            }
        }

    }

    public function getAllAgenciesIndexByAgencyId($agency_id){
        $user = Auth::user();
        if($user){
            return response()->json([
                'AgencyIndex' => AgenciesIndex::find($agency_id)
            ]);
        }
    }
}
