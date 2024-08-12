<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BailiffsPapersResource;
use App\Http\Resources\CaseFileEmployee;
use App\Http\Resources\CaseFileResource;
use App\Models\BailiffsPapers;
use App\Models\CaseFile;
use App\Models\Employee;
use App\Models\TemporaryBailiffPapers;
use App\Models\TemporaryCaseFile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Sanctum\PersonalAccessToken;


class BailiffController extends Controller
{
    public function addBailiffPaper(Request $request){    $token = request()->bearerToken();
        $personal_token = PersonalAccessToken::find($token)->tokenable_type;
        $validator = Validator::make($request->all(), [
            'bailiffs_pen_en' => 'required_without:bailiffs_pen_ar|string',
            'bailiffs_pen_ar' => 'required_without:bailiffs_pen_en|string',
            'delivery_time' => 'required|date',
            'session_time' => 'required|date',
            'bailiffs_num' => 'required|integer',
            'permission' => 'required|string',
            'announcment_time' => 'required|date',
            'bailiff_reply' => 'nullable|string',
            "user_type" => 'nullable|string|max:255|in:user,employee',
            "user_id" => 'required|integer|exists:users,id',
            "employee_id" => 'nullable|integer|exists:employees,id',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }
        $user = Auth::user();
        if ($personal_token == "App\Models\Lawyer") {
            $rules['permission'] = ['required', 'string', 'max:255', Rule::in(['me', 'another'])];
        }
        $validator = Validator::make($request->all(), [
            'permission.in' => 'The permission field must be one of the following options: me, another.',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }
        if ($personal_token == "App\Models\Lawyer") {
            $user = Auth::user();
//            if($request->permission =="another" && $request->user_type == "employee" && !isset($request->employee_id) ) {
//                return response()->json("employee should chose", 400);
//            }
//            if($request->permission =="another" && $request->user_type == "user" && !isset($request->user_id) ) {
//                return response()->json("user should chose", 400);
//            }
//            if($request->permission =="another" && !isset($request->user_id) && !isset($request->employee_id) ) {
//                return response()->json("should chose employee  or user ", 400);
//            }
            if($request->permission == "another" && !isset($request->employee_id)) {
                return response()->json("employee should be choice", 400);

            }
        }
        BailiffsPapers::create([
                        'bailiffs_pen_en' => $request->bailiffs_pen_en,
                        'bailiffs_pen_ar' => $request->bailiffs_pen_ar,
                        "lawyer_id" =>  $personal_token == "App\Models\Lawyer" ? $user->id  : $user->lawyer_id,
                        'employee_id' => ($personal_token == "App\Models\Employee") ? $user->id : ($request->permission == "another" ? $request->employee_id : 0) ,
//                        'model_type' => $personal_token == "App\Models\Lawyer" ? "Lawyer" : "Employee",
                        'delivery_time' => $request->Delivery_time,
                        'session_time' => $request->session_time,
                        'bailiffs_num' => $request->bailiffs_num,
                        'permission' => $personal_token == "App\Models\Lawyer" ?$request->permission : null,
                        'announcment_time' => $request->announcment_time,
                        'bailiff_reply' => $request->bailiff_reply,
                         'user_id' =>$request->user_id,
                         'status' => $personal_token == "App\Models\Lawyer" ? 'confirmed': 'pending',
        ]);
        return response()->json([
                        'message' => 'Bailiffs Papers created successfully'
                    ],201);
    }

    public function editBailiffPaper(Request $request,$bailiff_id){
        $user = Auth::user();
        $client = User::find($request->user_id);
        $bailiff = BailiffsPapers::find($bailiff_id);
        if($user){
            if($user->user_type == 'محامي'){
                if($bailiff->status != 'rejected'){
                    $bailiff->update([
                    'bailiffs_pen_en' => $request->bailiffs_pen_en,
                    'bailiffs_pen_ar' => $request->bailiffs_pen_ar,
                    'user_code' => $client->code,
                    'user_id' => $client->id,
                    'user_name' => $client->name,
                    'delivery_time' => $request->Delivery_time,
                    'session_time' => $request->session_time,
                    'bailiffs_num' => $request->bailiffs_num,
                    'announcment_time' => $request->announcment_time,
                    'bailiff_reply' => $request->bailiff_reply,
                    'permission' => $request->permission,

                    ]);
                    return response()->json([
                        'message' => 'Bailiffs Papers updated successfully'
                    ]);
                }

            }else{
                if($bailiff->status == 'pending' || $bailiff->status == 'confirmed' && $bailiff->permission != " "){
                    TemporaryBailiffPapers::create([
                        'bailiffs_pen_en' => $request->bailiffs_pen_en,
                        'bailiffs_pen_ar' => $request->bailiffs_pen_ar,
                        'bailiff_id' => $bailiff->id,
                        'user_code' => $client->code,
                        'user_id' => $client->id,
                        'user_name' => $client->name,
                        'delivery_time' => $request->Delivery_time,
                        'session_time' => $request->session_time,
                        'bailiffs_num' => $request->bailiffs_num,
                        'announcment_time' => $request->announcment_time,
                        'bailiff_reply' => $request->bailiff_reply,
                        'permission' => $bailiff->permission

                    ]);
                    return response()->json([
                        'message' => 'Bailiffs Papers updated successfully'
                    ]);
                }

            }
        }

    }

    public function deleteBailiffPaper($bailiff_id){
        $token = request()->bearerToken();
        $personal_token = PersonalAccessToken::find($token)->tokenable_type;
        $user = Auth::user();
        if($personal_token == "App\Models\Employee"){
            return response()->json('Not Allowed');
        }
        if($personal_token == "App\Models\Lawyer"){
            $bailiff = BailiffsPapers::where('id', $bailiff_id)
                ->where('lawyer_id', $user->id)
                ->first();
//            dd($user->id,PersonalAccessToken::find(\request()->bearerToken()));
            if(!isset($bailiff)){
                return response()->json(['error'=>'bailiff not found'], 404);
            }
            $bailiff->delete();
            return response()->json(['success'=>'case deleted'], 200);

        }
    }
    public function getAllBailiffPapers(){
        $token = request()->bearerToken();
        $personal_token = PersonalAccessToken::find($token)->tokenable_type;
        $user = Auth::user();
        if($personal_token == "App\Models\Lawyer"){
            $bailiffsPapers = BailiffsPapers::
            with(['lawyer','user'])
                ->where('status','!=','rejected')
                ->where('lawyer_id',$user->id)
                ->get();
            return response()->json([
                'BailiffsPapers' => BailiffsPapersResource::collection($bailiffsPapers)
            ]);
        }
        elseif ($personal_token == "App\Models\Employee"){
//                        dd($user->lawyer_id);

            $bailiffsPapers = BailiffsPapers::
            with(['lawyer','user'])
                ->where('status','!=','rejected')
                ->where('lawyer_id',$user->lawyer_id)
                ->where('employee_id',$user->id)
                ->get();

            return response()->json([
                'BailiffsPapers' => BailiffsPapersResource::collection($bailiffsPapers)
            ]);
        }
        elseif ($personal_token == "App\Models\User"){
            $bailiffsPapers = BailiffsPapers::
               with(['lawyer','user'])
                ->where('status','!=','rejected')
                ->where('user_id',$user->id)
                ->get();
            return response()->json([
                'BailiffsPapers' => BailiffsPapersResource::collection($bailiffsPapers)
            ]);
        }

    }
    public function getAllPendingBailiffPapersLawyerSide(){

    }

    public function getAllPendingBailiffPapersEmployeeSide(){
        $user = Auth::user();
        if($user){
            if($user->user_type == 'موظف'){
                return response()->json([
                    'bailiffPapers' => BailiffsPapers::where('status','pending')->orWhere('status','confirmed')->Where('permission',$user->code)->get()
                ]);
            }
        }
    }

    public function confirmBailiffPaper($bailiff_id){
        $token = request()->bearerToken();
        $personal_token = PersonalAccessToken::find($token)->tokenable_type;
        $user = Auth::user();
        if($personal_token == "App\Models\Lawyer"){
            $bailiffsPapers = BailiffsPapers::where('id', $bailiff_id)
                ->where('lawyer_id', $user->id)
                ->first();
            if(!isset($bailiffsPapers)){
                return response()->json(['error'=>'bailiffsPapers not found'], 404);
            }
            $bailiffsPapers->update(['status' => 'confirmed']);
            return response()->json(['success'=>'bailiffsPapers updated'], 201);

        }
}


    public function rejectBailiffPaper($bailiff_id){
        $token = request()->bearerToken();
        $personal_token = PersonalAccessToken::find($token)->tokenable_type;
        $user = Auth::user();
        if($personal_token == "App\Models\Lawyer") {
            $bailiffsPapers = BailiffsPapers::where('id', $bailiff_id)
                ->where('lawyer_id', $user->id)
                ->first();
            if (!isset($bailiffsPapers)) {
                return response()->json(['error' => 'bailiffsPapers not found'], 404);
            }
            $bailiffsPapers->update(['status' => 'rejected']);
            return response()->json(['success' => 'bailiffsPapers updated'], 201);


        }
    }

    public function getBailifPaperByBailifId($bailiff_id){
        $user = Auth::user();
        if($user){
            return response()->json([
                'BailifPaper' => BailiffsPapers::find($bailiff_id)
            ]);
        }
    }


    public function getClientBailiffPapers(){
        return response()->json([
                'BailifPapers' => BailiffsPapers::where('user_id',Auth::id())->where('status','confirmed')->get()
            ]);
    }
}
