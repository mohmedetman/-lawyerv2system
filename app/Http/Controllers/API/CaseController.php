<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CaseFileEmployee;
use App\Http\Resources\CaseFileResource;
use App\Http\Resources\CaseFileUser;
use App\Models\CaseFile;
use App\Models\Contact;
use App\Models\Employee;
use App\Models\Lawyer;
use App\Models\TemporaryCaseFile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Sanctum\PersonalAccessToken;

class CaseController extends Controller
{
    public function addCaseFile(Request $request){
        $auth =Auth::guard('lawyer')->check();
        dd($auth);
        $token = request()->bearerToken();
        $personal_token = PersonalAccessToken::find($token)->tokenable_type;
        $rules = [
                'court_en' => 'required_without:court_ar|string|max:255',
                'court_ar' => 'required_without:court_en|string|max:255',
                'user_status_en' => 'required_without:user_status_ar|string|max:255',
                'user_status_ar' => 'required_without:user_status_en|string|max:255',
                'decision_en' => 'required_without:decision_ar|string|max:255',
                'decision_ar' => 'required_without:decision_en|string|max:255',
                'enemy_status_en' => 'required_without:enemy_status_ar|string|max:255',
                'last_session_en' => 'required_without:last_session_ar|string|max:255',
                'enemy_status_ar' => 'required_without:enemy_status_en|string|max:255',
                'last_session_ar' => 'required_without:last_session_en|string|max:255',
//                 "user_type" => 'nullable|string|max:255|in:user,employee',
                 "user_id" => 'required|integer|exists:users,id',
                 "employee_id" => 'nullable|integer|exists:employees,id',
        ];
        if ($personal_token == "App\Models\Lawyer") {
                $rules['permission'] = ['required', 'string', 'max:255', Rule::in(['me', 'another'])];
            }
        $validator = Validator::make($request->all(), $rules, [
                'permission.in' => 'The permission field must be one of the following options: me, another.',
            ]);
        if ($validator->fails()) {
                return response()->json($validator->errors()->first(), 400);
            }
        if ($personal_token == "App\Models\Lawyer") {
                $user = Auth::user();
                if($request->permission == "another" && !isset($request->employee_id)) {
                    return response()->json("employee should be choice", 400);

                }
            }
        CaseFile::create([
                'court_en' => $request->court_en,
                'created_by'=>Auth::user()->id ,
                'employee_id' => ($personal_token == "App\Models\Employee") ? Auth::user()->id : (isset($request->employee_id) ? $request->employee_id : null),
//                'user_id' =>  (isset($request->user_id) ? $request->user_id : null),
                 "user_id" =>$request->user_id ,
                'model_type' => $personal_token == "App\Models\Lawyer" ? "Lawyer" : "Employee",
                'user_status_en' => $request->user_status_en,
                'enemy_status_en' => $request->enemy_status_en,
                'last_session_en' => $request->last_session_en,
                'decision_en' => $request->decision_en,
                'court_ar' => $request->court_ar,
                'user_status_ar' => $request->user_status_ar,
                'enemy_status_ar' => $request->enemy_status_ar,
                'last_session_ar' => $request->last_session_ar,
                'decision_ar' => $request->decision_ar,
//                "user_type" =>  $personal_token == "App\Models\Lawyer" ?($request->permession == "another" ? $request->user_type : "lawyer") : "employee" ,
                'lawyer_id' => $personal_token == "App\Models\Lawyer" ? Auth::user()->id : Employee::where('id',PersonalAccessToken::find($token)->tokenable_id)->first()->lawyer_id,
                'permission' => $personal_token == "App\Models\Lawyer" ?$request->permission : null,
                'status' => $personal_token == "App\Models\Lawyer" ? 'confirmed': 'pending',
            ]);
        return response()->json([
                'message' => 'Case File created successfully'
            ],201);

    }
    public function editCaseFile(Request $request, $id){
        $token = request()->bearerToken();
        $personal_token = PersonalAccessToken::find($token)->tokenable_type;

        if ($personal_token == "App\Models\Employee" || $personal_token == "App\Models\Lawyer") {
            $rules = [
                'court_en' => 'nullable|string|max:255',
                'court_ar' => 'nullable|string|max:255',
                'user_status_en' => 'nullable|string|max:255',
                'user_status_ar' => 'nullable|string|max:255',
                'decision_en' => 'nullable|string|max:255',
                'decision_ar' => 'nullable|string|max:255',
                'enemy_status_en' => 'nullable|string|max:255',
                'last_session_en' => 'nullable|string|max:255',
                'enemy_status_ar' => 'nullable|string|max:255',
                'last_session_ar' => 'nullable|string|max:255',
            ];

            if ($personal_token == "App\Models\Lawyer") {
                $rules['permission'] = ['sometimes', 'required', 'string', 'max:255', Rule::in(['me', 'another'])];
            }

            $validator = Validator::make($request->all(), $rules, [
                'permission.in' => 'The permission field must be one of the following options: me, another.',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->first(), 400);
            }

            if ($personal_token == "App\Models\Lawyer") {
                $user = Auth::user();
                $employee = Employee::where('id', $request->employee_id)->where('lawyer_id', $user->id)->first();
                if (!$employee && $request->employee_id) {
                    return response()->json(['employee' => 'not found'], 404);
                }
            }

            $caseFile = CaseFile::find($id);
            if (!$caseFile) {
                return response()->json(['message' => 'Case File not found'], 404);
            }

            $caseFile->update([
                'court_en' => $request->court_en ?? $caseFile->court_en,
                'court_ar' => $request->court_ar ?? $caseFile->court_ar,
                'user_status_en' => $request->user_status_en ?? $caseFile->user_status_en,
                'user_status_ar' => $request->user_status_ar ?? $caseFile->user_status_ar,
                'decision_en' => $request->decision_en ?? $caseFile->decision_en,
                'decision_ar' => $request->decision_ar ?? $caseFile->decision_ar,
                'enemy_status_en' => $request->enemy_status_en ?? $caseFile->enemy_status_en,
                'last_session_en' => $request->last_session_en ?? $caseFile->last_session_en,
                'enemy_status_ar' => $request->enemy_status_ar ?? $caseFile->enemy_status_ar,
                'last_session_ar' => $request->last_session_ar ?? $caseFile->last_session_ar,
                'lawyer_id' => $personal_token == "App\Models\Lawyer" ? Auth::user()->id : $caseFile->lawyer_id,
                'employee_id' => $personal_token == "App\Models\Employee" ? Auth::user()->id : $request->employee_id ?? $caseFile->employee_id,
                'model_type' => $personal_token,
                'permission' => $request->permission ?? $caseFile->permission,
                'status' => $personal_token == "App\Models\Lawyer" ? 'confirmed' : 'pending',
            ]);
//            $caseFile['user']

            return response()->json([
                'message' => 'Case File updated successfully',
                'case_file' => $caseFile,
            ]);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }

    public function deleteCaseFile($case_id){
        $token = request()->bearerToken();
        $personal_token = PersonalAccessToken::find($token)->tokenable_type;
        $user = Auth::user();
        if($personal_token == "App\Models\Employee"){
            return response()->json('Not Allowed');
        }
        if($personal_token == "App\Models\Lawyer"){
            $case_file = CaseFile::where('id', $case_id)
                ->where('lawyer_id', $user->id)
                ->first();
            if(!isset($case_file)){
                return response()->json(['error'=>'case not found'], 404);
            }
            $case_file->delete();
            return response()->json(['success'=>'case deleted'], 200);

        }
    }

    public function getAllCaseFile(){
        $token = request()->bearerToken();
        $personal_token = PersonalAccessToken::find($token)->tokenable_type;
        $user = Auth::user();
         if($personal_token == "App\Models\Lawyer"){
                 $case_file = CaseFile::with('lawyer')
                     ->where('status','!=','rejected')
                     ->where('lawyer_id',$user->id)
                     ->get();
                 return response()->json([CaseFileResource::collection($case_file)]);
             }
         elseif ($personal_token == "App\Models\Employee"){
             $case_file = CaseFile::
                    with('lawyer')
                    ->where('status','confirmed')
                    ->where('employee_id',$user->id)
                     ->get();
                 return response()->json([CaseFileEmployee::collection($case_file)]);
             }
         elseif ($personal_token == "App\Models\User"){
             $case_file = CaseFile::with('lawyer')
                 ->where('status','confirmed')
                 ->where('lawyer_id',$user->lawyer_id)
                 ->where('user_id',$user->id)
                 ->get();
             return response()->json([CaseFileUser::collection($case_file)]);
         }

    }
    public function confirmCaseFile($case_id){
        $token = request()->bearerToken();
        $personal_token = PersonalAccessToken::find($token)->tokenable_type;
        $user = Auth::user();
        if($personal_token == "App\Models\Lawyer"){
            $case_file = CaseFile::where('id', $case_id)
                ->where('lawyer_id', $user->id)->first();
            if(!isset($case_file)){
                return response()->json(['error'=>'case not found'], 404);
            }
            $case_file->update(['status' => 'confirmed']);
            return response()->json(['success'=>'case updated'], 201);

        }

    }
    public function rejectCaseFile($case_id){
        $token = request()->bearerToken();
        $personal_token = PersonalAccessToken::find($token)->tokenable_type;
        $user = Auth::user();
        if($personal_token == "App\Models\Employee"){
            return response()->json('Not Allowed');
        }
        if($personal_token == "App\Models\Lawyer") {
            $case_file = CaseFile::where('id', $case_id)
                ->where('lawyer_id', $user->id)->first();
            if (!isset($case_file)) {
                return response()->json(['error' => 'case not found'], 404);
            }
            $case_file->update(['status' => 'rejected']);
            return response()->json(['success' => 'case updated'], 201);
        }
    }
    public function getAllPendingCaseFileSide(){
        $token = request()->bearerToken();
        $personal_token = PersonalAccessToken::find($token)->tokenable_type;
        $user = Auth::user();
        if($personal_token == "App\Models\Lawyer"){
            $case_file = CaseFile::with('lawyer')
                ->where('status','!=','rejected')
                ->Where('status','pending')
                ->where('lawyer_id',$user->id)
                ->get();
            return response()->json([CaseFileResource::collection($case_file)]);
        }
    }
    public function getCaseFileById($id)
{
    $token = request()->bearerToken();
    $personal_token = PersonalAccessToken::find($token)->tokenable_type;
    $user = Auth::user();
    if($personal_token == "App\Models\Employee"){
       $case = CaseFile::where('id', $id)
           ->where('employee_id',$user->id)
           ->where('status','!=','rejected')
           ->first();
       if(!isset($case)){
           return response()->json(['error'=>'case not found'], 404);
       }
       return response()->json([CaseFileResource::make($case)]);
    }
    if($personal_token == "App\Models\Lawyer"){
        $case = CaseFile::where('id', $id)
            ->where('lawyer_id',$user->id)
            ->first();
        if(!isset($case)){
            return response()->json(['error'=>'case not found'], 404);
        }
        return response()->json([CaseFileResource::make($case)]);    }

}
    public function getAllPendingCaseFileemployeeSide(){
        $token = request()->bearerToken();
        $personal_token = PersonalAccessToken::find($token)->tokenable_type;
        $user = Auth::user();
        if($personal_token == "App\Models\Employee"){
            $case_file = CaseFile::with('lawyer')
                ->where('status','!=','rejected')
                ->Where('status','pending')
                ->where('employee_id',$user->id)
                ->get();
            return response()->json([CaseFileResource::collection($case_file)]);
        }
    }
    public function getCaseFileBycaseId($case_id){
        $user = Auth::user();
        if($user){
            return response()->json([
                'caseFile' => CaseFile::find($case_id)
            ]);
        }

    }


    public function storeContactsMessages(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:255' ,
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }
        Contact::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message
        ]);

        return response()->json([
            'message' => 'Contacts Messages added successfully'
           ]);
    }
    public function getAllContactsMessages(){
        return response()->json([
            'contactsMessages' => Contact::all()
           ]);
    }
}
