<?php

namespace Modules\Case\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\CaseFileEmployee;
use App\Http\Resources\CaseFileResource;
use App\Http\Resources\CaseFileUser;
use App\Models\Contact;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\CaseFile;

class CaseController extends Controller
{

    public function addCaseFile(Request $request){
        $token = request()->bearerToken();
        $personal_token = PersonalAccessToken::find($token)->tokenable_type;
        $rules = [
            'court_en' => 'required_without:court_ar|string|max:255',
            'court_ar' => 'required_without:court_en|string|max:255',
            "customer_id" => 'required|integer|exists:customers,id',
            "employee_id" => 'nullable|integer|exists:employees,id',
            "case_type_id" => 'required|integer|exists:case_types,id',
            "case_degree_id" => 'required|integer|exists:case_degrees,id',

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
            'case_degree_id'=>$request->case_degree_id,
            'case_type_id'=>$request->case_type_id,
            'created_by'=> Auth::user()->id ,
            'employee_id' => ($personal_token == "App\Models\Employee") ? Auth::user()->id : (isset($request->employee_id) ? $request->employee_id : null),
            "customer_id" =>$request->customer_id ,
            'model_type' => $personal_token == "App\Models\Lawyer" ? "Lawyer" : "Employee",
            'court_ar' => $request->court_ar,
            'lawyer_id' => $personal_token == "App\Models\Lawyer" ? Auth::user()->id : Employee::where('id',PersonalAccessToken::find($token)->tokenable_id)->first()->lawyer_id,
            'permission' => $personal_token == "App\Models\Lawyer" ?$request->permission : null,
            'status' => $personal_token == "App\Models\Lawyer" ? 'confirmed': 'pending',
            'actions' =>$request->actions
        ]);
        return response()->json([
            'message' => 'Case File created successfully'
        ],201);

    }

    public function editCaseFile(Request $request, $id)
    {
        $token = request()->bearerToken();
        $personal_token = PersonalAccessToken::find($token)->tokenable_type;
        $caseFile = CaseFile::find($id);
        if (!$caseFile) {
            return response()->json(['message' => 'Case File not found'], 404);
        }
        $rules = [
            'court_en' => 'nullable|string|max:255',
            'court_ar' => 'nullable|string|max:255',
            "customer_id" => 'nullable|integer|exists:customers,id',
            "employee_id" => 'nullable|integer|exists:employees,id',
            "case_type_id" => 'nullable|integer|exists:case_types,id',
            "case_degree_id" => 'nullable|integer|exists:case_degrees,id',
        ];

        if ($personal_token == "App\Models\Lawyer") {
            $rules['permission'] = ['nullable', 'string', 'max:255', Rule::in(['me', 'another'])];
        }

        $validator = Validator::make($request->all(), $rules, [
            'permission.in' => 'The permission field must be one of the following options: me, another.',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        $caseFile->update([
            'court_en' => $request->court_en ?? $caseFile->court_en,
            'court_ar' => $request->court_ar ?? $caseFile->court_ar,
            'customer_id' => $request->customer_id ?? $caseFile->customer_id,
            'employee_id' => $request->employee_id ?? $caseFile->employee_id,
            'case_type_id' => $request->case_type_id ?? $caseFile->case_type_id,
            'case_degree_id' => $request->case_degree_id ?? $caseFile->case_degree_id,
            'model_type' => $personal_token == "App\Models\Lawyer" ? "Lawyer" : "Employee",
            'lawyer_id' => $personal_token == "App\Models\Lawyer" ? Auth::user()->id : $caseFile->lawyer_id,
            'permission' => $request->permission ?? $caseFile->permission,
            'status' => $personal_token == "App\Models\Lawyer" ? 'confirmed' : 'pending',
            'actions' => $request->actions ?? $caseFile->actions,
        ]);

        return response()->json(['message' => 'Case File updated successfully'], 200);
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
