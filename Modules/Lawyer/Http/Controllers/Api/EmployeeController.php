<?php

namespace Modules\Lawyer\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Rules\ChechEmailUniqe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Testing\Fluent\Concerns\Has;
use Illuminate\Validation\Rule;
use Modules\Lawyer\Entities\Employee;
use Modules\Lawyer\Services\EmployeeServices;

class EmployeeController extends Controller
{
    protected $employeeService;

    public function __construct(EmployeeServices $employeeService)
    {
        $this->employeeService = $employeeService;
    }
    public function createNewEmployee(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name_en' => 'required_without:name_ar|string|max:255',
            'name_ar' => 'required_without:name_en|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'code' => 'required|string|max:10|unique:customers,code',
            'personal_id' => 'required|string|size:14',
            'email' => ['required','string','email','max:255',new ChechEmailUniqe ],
            'gender' => 'required|in:male,female',
            'addresses' => 'required|array',
            'addresses.*' => 'required|string|max:255',
            'phone_numbers' => 'required|array',
            'phone_numbers.*' => 'required|integer',
            'litigationDegree_en' => 'required_without:litigationDegree_en|string|max:255',
            'litigationDegree_ar' => 'required_without:litigationDegree_ar|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

       return $this->employeeService->createEmployee($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Customer created successfully',
            'user' => $customer,
        ], 201);

    }
    public function getAllEmployees()
    {
        $user = Auth::user();
        return response()->json([
            'Employee' =>UserResource::collection( Employee::where('lawyer_id',$user->id)->get() )
        ]);

    }
    public function getEmployeesById($employee_id)
    {
        $auth = Auth::user();
        $employee = Employee::where('id',$employee_id)
            ->where('lawyer_id',$auth->id)
            ->first();
        if(!isset($employee)) {
            return response()->json(['message' => 'employee not found']);
        }
        return response()->json([UserResource::make($employee)]);
    }
    public function editEmployees(Request $request,$user_id){
        $validator = Validator::make($request->all(), [
            'name_en' => 'nullable|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'code' => [
                'nullable',
                'string',
                'max:10',
                Rule::unique('employees')->ignore($user_id),
            ],
            'password' => 'nullable|string|min:8',
            'phone_number' => 'nullable|string|max:15',
            'personal_id' => 'nullable|string|size:14',
            'address' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female',
            'user_type' => 'nullable|string|in:lawyer,client',
            'litigationDegree_en' => 'nullable|string|max:255',
            'litigationDegree_ar' => 'nullable|string|max:255',
            'lawyer_id' => 'nullable|exists:lawyers,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }
        $auth = Auth::user();
        $employee = Employee::where('id',$user_id)->where('lawyer_id',$auth->id)->first();
        if (!isset($employee)){
            return response()->json([
                'message' => 'employee not found',
            ]);
        }
        $password = '' ;
        $password = $request->filled('password') ? bcrypt($request->password) : $employee->password;
        $name_en = $request->filled('name_en') ? $request->name_en : $employee->name_en;
        $name_ar = $request->filled('name_ar') ? $request->name_ar : $employee->name_ar;
        $code = $request->filled('code') ? $request->code : $employee->code;
        $phone_number = $request->filled('phone_number') ? $request->phone_number : $employee->phone_number;
        $personal_id = $request->filled('personal_id') ? $request->personal_id : $employee->personal_id;
        $address = $request->filled('address') ? $request->address : $employee->address;
        $gender = $request->filled('gender') ? $request->gender : $employee->gender;
        $litigationDegree_en = $request->filled('litigationDegree_en') ? $request->litigationDegree_en : $employee->litigationDegree_en;
        $litigationDegree_ar = $request->filled('litigationDegree_ar') ? $request->litigationDegree_ar : $employee->litigationDegree_ar;
        $employee->update([
            'name_en' => $name_en,
            'name_ar' => $name_ar,
            'password' => $password,
            'code' => $code,
            'phone_number' => $phone_number,
            'personal_id' => $personal_id,
            'address' => $address,
            'gender' => $gender,
            'litigationDegree_en' => $litigationDegree_en,
            'litigationDegree_ar' => $litigationDegree_ar,
            'lawyer_id' => $auth->id,
        ]);
        return response()->json([
            'message' => 'employee updated successfully'
        ]);



    }
    public function deleteEmployees($user_id){

        $lawyer = Auth::user();
        $employee =   Employee::where('id',$user_id)->where('lawyer_id',$lawyer->id)->first();
        if(!isset($employee)) {
            return response()->json([
                'message' => 'employee not found'
            ]);
        }
        $employee->delete();
        return response()->json([
            'message' => 'user deleted successfully'
        ]);

    }
}
