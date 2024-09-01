<?php

namespace Modules\Case\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\CaseFileEmployee;
use App\Http\Resources\CaseFileResource;
use App\Http\Resources\CaseFileUser;
use App\Models\Contact;
use Modules\Case\Entities\CaseFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Sanctum\PersonalAccessToken;
use Modules\Case\Entities\CaseEmployee;
use Modules\Lawyer\Entities\Employee;
use function PHPUnit\Framework\returnArgument;

class CaseController extends Controller
{

    public function addCaseFile(Request $request)
    {
        $token = request()->bearerToken();
        $user = Auth::user();
        $personal_token = PersonalAccessToken::find($token)->tokenable_type;
        $rules = [
            'court_en' => 'required_without:court_ar|string|max:255',
            'court_ar' => 'required_without:court_en|string|max:255',
            "customer_id" => 'required|integer|exists:customers,id',
            "case_type_id" => 'required|integer|exists:case_types,id',
            "case_degree_id" => 'required|integer|exists:case_degrees,id',

        ];
        $flag = 0;
        if ($personal_token == "Modules\Lawyer\Entities\Lawyer") {
            $rules['permission'] = ['required', 'string', 'max:255', Rule::in(['me', 'another'])];
            $rules['employee_ids'] = ['required', 'array'];
            $rules['employee_ids.*'] = ['required', 'integer', 'exists:employees,id'];
        }
        $validator = Validator::make($request->all(), $rules, [
            'permission.in' => 'The permission field must be one of the following options: me, another.',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }
        if ($personal_token == "Modules\Lawyer\Entities\Lawyer") {
            $user = Auth::user();
            if ($request->permission == "another" && !isset($request->employee_ids)) {
                return response()->json("employee should be choice", 400);

            }
            $flag = 1;
        }
        try {
            DB::beginTransaction();
            $case_file = CaseFile::updateOrCreate([
                'court_en' => $request->court_en,
                'case_degree_id' => $request->case_degree_id,
                'case_type_id' => $request->case_type_id,
                'created_by' => Auth::user()->id,
                "customer_id" => $request->customer_id,
                'status' => $request->status ?? 'confirm',
                'model_type' => $personal_token == "Modules\Lawyer\Entities\Lawyer" ? "Lawyer" : "Employee",
                'court_ar' => $request->court_ar,
                'lawyer_id' => $personal_token == "Modules\Lawyer\Entities\Lawyer" ? Auth::user()->id : Auth::user()->lawyer_id,
                'permission' => $personal_token == "Modules\Lawyer\Entities\Lawyer" ? $request->permission : null,
                'actions' => $request->actions
            ]);
            if ($flag == 1) {
                foreach ($request->employee_ids as $key => $value) {
                    CaseEmployee::updateOrCreate([
                        'employee_id' => $value,
                        'status' => 'confirmed',
                        'case_id' => $case_file->id,
                    ], ['employee_id' => $value, 'case_id' => $case_file->id]);
                }

            }
            if ($flag == 0) {
                CaseEmployee::create([
                    'employee_id' => $user->id,
                    'case_id' => $case_file->id,
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return response()->json([
            'message' => 'Case File created successfully'
        ], 201);

    }

    public function editCaseFile(Request $request, $caseFileId)
    {
        $token = request()->bearerToken();
        $personal_token = PersonalAccessToken::find($token)->tokenable_type;

        $caseFile = CaseFile::findOrFail($caseFileId);

        $rules = [
            'court_en' => 'sometimes|required_without:court_ar|string|max:255',
            'court_ar' => 'sometimes|required_without:court_en|string|max:255',
            "customer_id" => 'sometimes|required|integer|exists:customers,id',
            "case_type_id" => 'sometimes|required|integer|exists:case_types,id',
            "case_degree_id" => 'sometimes|required|integer|exists:case_degrees,id',
        ];

        if ($personal_token == "Modules\Lawyer\Entities\Lawyer") {
            $rules['permission'] = ['sometimes', 'required', 'string', 'max:255', Rule::in(['me', 'another'])];
            $rules['employee_ids'] = ['sometimes', 'required', 'array'];
            $rules['employee_ids.*'] = ['sometimes', 'required', 'integer', 'exists:employees,id'];
        }

        $validator = Validator::make($request->all(), $rules, [
            'permission.in' => 'The permission field must be one of the following options: me, another.',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        $flag = $personal_token == "Modules\Lawyer\Entities\Lawyer" ? 1 : 0;

        try {
            DB::beginTransaction();

            $caseFile->update([
                'court_en' => $request->court_en ?? $caseFile->court_en,
                'case_degree_id' => $request->case_degree_id ?? $caseFile->case_degree_id,
                'case_type_id' => $request->case_type_id ?? $caseFile->case_type_id,
                'customer_id' => $request->customer_id ?? $caseFile->customer_id,
                'model_type' => $personal_token == "Modules\Lawyer\Entities\Lawyer" ? "Lawyer" : "Employee",
                'court_ar' => $request->court_ar ?? $caseFile->court_ar,
                'status' => $request->status ?? 'confirm',
                'lawyer_id' => $personal_token == "Modules\Lawyer\Entities\Lawyer" ? Auth::user()->id : Employee::where('id', PersonalAccessToken::find($token)->tokenable_id)->first()->lawyer_id,
                'permission' => $request->permission ?? $caseFile->permission,
                'actions' => $request->actions ?? $caseFile->actions,
            ]);

            if ($flag == 1) {
                CaseEmployee::where('case_id', $caseFile->id)->delete();
                foreach ($request->employee_ids ?? [] as $key => $value) {
                    CaseEmployee::updateOrCreate([
                        'employee_id' => $value,
                        'case_id' => $caseFile->id,
                    ], [
                        'employee_id' => $value,
                        'status' => 'confirmed',
                        'case_id' => $caseFile->id,
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return response()->json([
            'message' => 'Case File updated successfully'
        ], 200);
    }

    public function deleteCaseFile($case_id)
    {
        $token = request()->bearerToken();
        $personal_token = PersonalAccessToken::find($token)->tokenable_type;
        $user = Auth::user();
        if ($personal_token != "Modules\Lawyer\Entities\Lawyer") {
            return response()->json('Not Allowed');
        }
        $case_file = CaseFile::where('id', $case_id)
            ->where('lawyer_id', $user->id)
            ->first();
        if (!isset($case_file)) {
            return response()->json(['error' => 'case not found'], 404);
        }
        CaseEmployee::where('case_id', $case_file->id)->delete();
        $case_file->delete();
        return response()->json(['success' => 'case deleted'], 200);

    }

    public function getAllCaseFile()
    {
        $token = request()->bearerToken();
        $personal_token = PersonalAccessToken::find($token)->tokenable_type;
        $user = Auth::user();
        if ($personal_token == "Modules\Lawyer\Entities\Lawyer") {
            $case_file =
                CaseFile::
                with(['caseDegree', 'caseType', 'employee'])
                    ->where('status', '!=', 'rejected')
                    ->where('lawyer_id', $user->id)
                    ->get();
            return response()->json([CaseFileResource::collection($case_file)]);
        } elseif ($personal_token == "Modules\Lawyer\Entities\Employee") {
            $case_file = CaseFile::with(['caseDegree', 'caseType'])
                ->where('status', '!=', 'rejected')
                ->whereHas('employee', function ($query) use ($user) {
                    $query->where('status', '=', 'confirmed')
                        ->where('employee_id', $user->id)
                        ->where('lawyer_id', $user->lawyer_id);
                })
                ->get();
            return response()->json([CaseFileResource::collection($case_file)]);
        } elseif ($personal_token == "Modules\Customer\Entities\Customer") {
            $case_file = CaseFile::with(['caseDegree', 'caseType'])
                ->where('status', '!=', 'rejected')
                ->where('customer_id', $user->id)
                ->get();
            return response()->json([CaseFileResource::collection($case_file)]);
        }
    }

    public function confirmCaseFile(Request $request, $id)
    {
        $token = request()->bearerToken();
        $personal_token = PersonalAccessToken::find($token)->tokenable_type;
        $user = Auth::user();
        if ($personal_token == "Modules\Lawyer\Entities\Lawyer") {
            $case = CaseEmployee
                ::where('case_id', $id)
                ->where('employee_id', $request->employee_id)
                ->first();
            if (!$case) {
                return response()->json(['error' => 'Case not found'], 404);
            }
            $case->update(['status' => 'confirmed']);
            return response()->json(['success' => 'case updated'], 201);

        }

    }

    public function rejectCaseFile($case_id)
    {
        $token = request()->bearerToken();
        $personal_token = PersonalAccessToken::find($token)->tokenable_type;
        $user = Auth::user();
        if ($personal_token == "Modules\Employee\Entities\Employee") {
            return response()->json('Not Allowed');
        }
        if ($personal_token == "Modules\Lawyer\Entities\Lawyer") {
            $case_file = CaseFile::where('id', $case_id)
                ->where('lawyer_id', $user->id)->first();
            if (!isset($case_file)) {
                return response()->json(['error' => 'case not found'], 404);
            }
            $case_file->update(['status' => 'rejected']);
            return response()->json(['success' => 'case updated'], 201);
        }
    }

    public function getAllPendingCaseFileSide()
    {
        $token = request()->bearerToken();
        $personal_token = PersonalAccessToken::find($token)->tokenable_type;
        $user = Auth::user();
        if ($personal_token == "Modules\Lawyer\Entities\Lawyer") {
            $case_file = CaseFile::with('lawyer')
                ->where('status', '!=', 'rejected')
                ->Where('status', 'pending')
                ->where('lawyer_id', $user->id)
                ->get();
            return response()->json([CaseFileResource::collection($case_file)]);
        }
    }

    public function getCaseFileById($id)
    {
        $token = request()->bearerToken();
        $personal_token = PersonalAccessToken::find($token)->tokenable_type;
        $user = Auth::user();
        if ($personal_token == "Modules\Lawyer\Entities\Lawyer") {
            $case_file =
                CaseFile::
                with(['caseDegree', 'caseType', 'employee'])
                    ->where('status', '!=', 'rejected')
                    ->where('lawyer_id', $user->id)
                    ->where('id', $id)
                    ->first();
            if (!$case_file) {
                return response()->json(['error' => 'Case not found'], 404);
            }
            return response()->json([CaseFileResource::make($case_file)]);
        } elseif ($personal_token == "Modules\Lawyer\Entities\Employee") {
            $case_file = CaseFile::with(['caseDegree', 'caseType'])
                ->where('status', '!=', 'rejected')
                ->whereHas('employee', function ($query) use ($user) {
                    $query->where('status', '=', 'confirmed')
                        ->where('employee_id', $user->id)
                        ->where('lawyer_id', $user->lawyer_id);
                })
                ->where('id', $id)
                ->first();
            if (!$case_file) {
                return response()->json(['error' => 'Case not found'], 404);
            }
            return response()->json([CaseFileResource::make($case_file)]);
        } elseif ($personal_token == "Modules\Customer\Entities\Customer") {
            $case_file = CaseFile::with(['caseDegree', 'caseType'])
                ->where('status', '!=', 'rejected')
                ->where('customer_id', $user->id)
                ->where('id', $id)
                ->first();
            if (!$case_file) {
                return response()->json(['error' => 'Case not found'], 404);
            }
            return response()->json([CaseFileResource::make($case_file)]);
        }

    }

    public function getAllEmployeeConfirm($case_id)
    {
       $case_file = CaseEmployee::where('case_id', $case_id)
                    ->with('employee')
                    ->where('status', '=', 'pending')
                   ->whereHas('case', function ($query) {
                    $query->where('lawyer_id',Auth::user()->id);
                    })
                 ->get();
       return $case_file ;
    }
    public function assignCaseFileToEmployee(Request $request ,$case_id)
    {
        $validator = Validator::make($request->all(),[
            'employee_id' => 'required',
        ] );
        $case_id = CaseFile::where('id', $case_id)->first();
        if (!$case_id) {
            return response()->json(['error' => 'Case not found'], 404);
        }
        $employee = Employee::where('id', $request->employee_id)->where('lawyer_id',Auth::user()->id)->first();
        if (!$employee) {
            return response()->json(['error' => 'employee not found'], 404);
        }
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }
        CaseEmployee::updateOrCreate(
            [
                'case_id' => $case_id->id,
                'employee_id' => $request->employee_id
            ],
            [
                'status' => 'confirmed'
            ]
        );

        return response()->json(['success' => 'case updated'], 201);
    }
}

